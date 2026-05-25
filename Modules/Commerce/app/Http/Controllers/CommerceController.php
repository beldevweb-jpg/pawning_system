<?php

namespace Modules\Commerce\Http\Controllers;

use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use Modules\Commerce\Exports\ReportSaleMultiExport;
use Modules\Commerce\Exports\SalefontExport;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Http\Request;
use Modules\Commerce\Models\Settings;
use Modules\Commerce\Models\Sale;
use Modules\Commerce\Models\Expenses;
use Modules\Commerce\Models\interest;
use Endroid\QrCode\QrCode;
use Endroid\QrCode\Writer\SvgWriter;
use Illuminate\Support\Facades\Storage;
use Barryvdh\DomPDF\Facade\Pdf;
use Modules\Commerce\Models\DailyReport;
use Modules\Members\Models\Member;
use Carbon\Carbon;



class CommerceController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function create_search()
    {
        $sale = Sale::where('status', 'between')->first();
        return view('commerce::commerce.create_search', compact('sale'));
    }

    // ค่้นหาประวัติการขาย/ลูกค้า
    public function store_create_search(Request $request)
    {
        $tax_number    = $request->tax_number;
        $serial_number = $request->serial_number;

        if (!empty($serial_number)) {
            $sale = Sale::where('serial_number', 'LIKE', "%{$serial_number}%")->first();

            if (!$sale) {
                return back()->withErrors(['error' => 'ไม่พบประวัติเครื่อง']);
            }

            return redirect()->route('commerce.report_member', $sale->id);
        }

        if (!empty($tax_number)) {
            $member = Member::where('tax_number', 'LIKE', "%{$tax_number}%")->first();

            if (!$member) {
                return redirect()
                    ->route('commerce.create_member')
                    ->withErrors(['error' => 'ไม่พบประวัติลูกค้า']);
            }

            return redirect()->route('commerce.report_member', $member->member_id);
        }

        return back()->withErrors(['error' => 'กรุณากรอกข้อมูลเพื่อค้นหา']);
    }

    public function create_type_of_sale($id)
    {
        $member = Member::findOrFail($id);
        return view('commerce::commerce.create_type_of_sale', compact('member'));
    }

    public function create_member()
    {
        return view('commerce::commerce.create_member');
    }

    public function store_create_member(Request $request)
    {
        $request->validate([
            'fullname' => 'required|string',
            // 'phone' => 'required|numeric',
            'tax_number' => 'nullable|numeric|unique:members,tax_number',
            'idcard_image' => 'nullable|image|max:2048',
        ]);

        $member = DB::transaction(function () use ($request) {

            $member = new Member();
            $member->fullname = $request->fullname;
            $member->phone = $request->phone;
            $member->tax_number = $request->tax_number;
            $member->save();

            if ($request->hasFile('idcard_images')) {

                $paths = [];

                foreach ($request->file('idcard_images') as $file) {
                    $paths[] = $file->store('idcards', 'public');
                }

                // เก็บเป็น JSON ใน database
                $member->idcard_image = json_encode($paths);
                $member->save();
            }

            return $member;
        });

        return redirect()->route(
            'commerce.create_type_of_sale',
            ['id' => $member->member_id]
        )->with('success', 'บันทึกข้อมูลเรียบร้อย');
    }

    // ฟอร์มแสดงการจำนำ
    public function create_pawning($id)
    {
        $mode = request('mode');
        if (!in_array($mode, ['view', 'edit'])) {
            $mode = 'view';
        }
        $sale = Sale::find($id) ?? new Sale();

        $sale_between = Sale::where('status', 'between')->first();
        if (Member::where('member_id', $id)->exists()) {
            $member = Member::where('member_id', $id)->first();
        } else {
            $member = Member::where('sale_id', $id)->first();
        }
        return view('commerce::commerce.create_pawning', compact('sale', 'member', 'sale_between'));
    }

    public function store_pawning(Request $request, $id = null)
    {
        $request->validate([
            'type_category'      => 'required|string',
            'action_type'        => 'required_if:sale_between,1',
            'action_type_other'  => 'required_if:action_type,other|string',
            'brand'              => 'nullable|string',
            'model'              => 'nullable|string',
            'locker_pass'        => 'nullable|string',
            'drawn_lock'         => 'nullable|string',
            'serial_number' => 'required|string|unique:sales,serial_number',
            'cash'               => 'nullable|numeric|min:0',
            'transfer'           => 'nullable|numeric|min:0',
            'note'               => 'nullable|string',
            'appointment_date' => 'required|date|after_or_equal:today',
            'product_images'   => 'nullable|image|mimes:jpg,jpeg,png|max:3000',
            'bill'               => 'nullable|image|mimes:jpg,jpeg,png|max:3000',
            'product_images_behind' => 'nullable|array|max:10',
            'product_images_behind.*' => 'image|mimes:jpg,jpeg,png,webp|max:3000',
        ], [

            'product_images_behind.array' => 'รูปภาพต้องเป็นชุดข้อมูล',
            'product_images_behind.max' => 'อัปโหลดได้ไม่เกิน 10 รูป',

            'product_images_behind.*.image' => 'ไฟล์ต้องเป็นรูปภาพ',
            'product_images_behind.*.mimes' => 'รองรับเฉพาะ jpg jpeg png webp',
            'product_images_behind.*.max' => 'ขนาดรูปต้องไม่เกิน 3MB',

            'product_images_behind' => 'nullable|array|max:10',
            'product_images_behind.*' => 'image|mimes:jpg,jpeg,png,webp|max:3000',

            // type
            'type_category.required' => 'กรุณาเลือกประเภทสินค้า',

            // action
            'action_type.required_if' => 'กรุณาเลือกประเภทการทำรายการ',
            'action_type_other.required_if' => 'กรุณากรอกประเภทอื่นๆ',

            // basic info
            'brand.string' => 'ยี่ห้อต้องเป็นข้อความ',
            'model.string' => 'รุ่นต้องเป็นข้อความ',

            // lock
            'locker_pass.string' => 'รหัสล็อคต้องเป็นข้อความ',
            'drawn_lock.string' => 'รูปแบบการวาดต้องเป็นข้อความ',

            // serial
            'serial_number.required' => 'กรุณากรอก IMEI / รหัสเครื่อง',
            'serial_number.string' => 'รหัสเครื่องต้องเป็นข้อความ',
            'serial_number.unique' => 'IMEI / รหัสเครื่องนี้มีอยู่ในระบบแล้ว',

            // payment
            'cash.numeric' => 'เงินสดต้องเป็นตัวเลข',
            'cash.min' => 'เงินสดต้องไม่น้อยกว่า 0',

            'transfer.numeric' => 'เงินโอนต้องเป็นตัวเลข',
            'transfer.min' => 'เงินโอนต้องไม่น้อยกว่า 0',

            // note
            'note.string' => 'หมายเหตุต้องเป็นข้อความ',

            // date
            'appointment_date.required' => 'กรุณาเลือกวันที่นัดรับ',
            'appointment_date.date' => 'รูปแบบวันที่ไม่ถูกต้อง',
            'appointment_date.after_or_equal' => 'วันที่นัดต้องเป็นวันนี้หรือวันถัดไป',

            // images
            'product_images.image' => 'ไฟล์สินค้าต้องเป็นรูปภาพ',
            'product_images.mimes' => 'รูปสินค้าต้องเป็นไฟล์ jpg, jpeg หรือ png',
            'product_images.max' => 'ขนาดรูปสินค้าต้องไม่เกิน 3MB',

            'bill.image' => 'ใบเสร็จต้องเป็นรูปภาพ',
            'bill.mimes' => 'ใบเสร็จต้องเป็นไฟล์ jpg, jpeg หรือ png',
            'bill.max' => 'ขนาดใบเสร็จต้องไม่เกิน 3MB',
        ]);

        $cash = $request->cash ?? 0;
        $transfer = $request->transfer ?? 0;
        $principal = $cash + $transfer;

        if ($principal <= 0) {
            return back()
                ->withErrors(['payment' => 'กรุณาระบุจำนวนเงิน'])
                ->withInput();
        }

        // Member::findOrFail($id);
        $member_id = DB::transaction(function () use ($request, $cash, $transfer, $principal, $id) {

            // =========================
            // RUNNING NO (LOCK SAFE)
            // =========================
            $settings = Settings::where('id', 1)
                ->lockForUpdate()
                ->first();

            $next = ($settings->running_no ?? 900000) + 1;

            $settings->update([
                'running_no' => $next
            ]);

            // =========================
            // CREATE SALE
            // =========================
            $sale = new Sale();
            $sale->type_category     = $request->type_category;
            $sale->status           = $request->status;
            $sale->member_id        = $id;
            $sale->other_type    = $request->other_type;
            $sale->brand            = $request->brand;
            $sale->model            = $request->model;
            $sale->serial_number    = $request->serial_number;
            $sale->cash             = $cash;
            $sale->transfer         = $transfer;
            $sale->note             = $request->note;
            $sale->appointment_date = $request->appointment_date;
            $sale->user_id          = auth()->user()->user_id;
            $sale->running_no       = $next;

            $sale->save();

            // =========================
            // MEMBER UPDATE
            // =========================
            Member::where('member_id', $id)
                ->update(['sale_id' => $sale->id]);

            $slipPath = $request->hasFile('slip')
                ? $request->file('slip')->store('slips', 'public')
                : null;

            // dd($request);
            $expenses = new Expenses();
            $expenses->user_id = auth()->user()->user_id;
            if ($other_type = $request->other_type) {
                $product = "จำ {$other_type}";
            } else {
                $product = "จำ {$sale->brand} {$sale->model}";
            }
            $expenses->product = $product;
            $expenses->cash = $cash;
            $expenses->transfer = $transfer;
            $expenses->type = 'pay';
            $expenses->slip = $slipPath;
            $expenses->sale_id = $sale->id;
            $expenses->user_id = auth()->user()->user_id;
            $expenses->save();
            return $sale->id;
        });
        $sale = Sale::findOrFail($member_id);

        // QR
        $url = route('commerce.show_sale', $sale->id);

        $qrCode = QrCode::create($url)->setSize(300)->setMargin(10);
        $writer = new SvgWriter();
        $result = $writer->write($qrCode);

        $qrPath = 'qrcodes/create_pawning_' . $sale->id . '.svg';

        Storage::disk('public')->put($qrPath, $result->getString());

        // prepare update data
        $data = [
            'qr_code' => $qrPath,
            'dok' => 0,
        ];

        // files
        if ($request->hasFile('product_images')) {
            $data['product_images'] = $request->file('product_images')->store('products', 'public');
        }

        if ($request->hasFile('product_images_behind')) {

            $images = [];

            foreach ($request->file('product_images_behind') as $file) {

                $path = $file->store('products', 'public');

                $images[] = $path;
            }

            $data['product_images_behind'] = json_encode($images);
        }

        if ($request->hasFile('bill')) {
            $data['bill'] = $request->file('bill')->store('bills', 'public');
        }

        // interest
        if ($request->appointment_date) {
            $days = now()->diffInDays(Carbon::parse($request->appointment_date));

            $interestRate = Interest::where('days', '>=', $days)
                ->orderBy('days', 'asc')
                ->first()
                ?? Interest::orderBy('days', 'desc')->first();

            if ($interestRate) {
                $data['dok'] = ($principal * $interestRate->percent) / 100;
            }
        }

        // FINAL SAVE (ครั้งเดียว)
        $sale->update($data);
        return redirect()->route('commerce.create_search', [
            'member_id' => $member_id,
            'success'   => 'บันทึกข้อมูลเรียบร้อย'
        ]);
    }

    public function show($id)
    {
        $sale = Sale::with('member_r')->findOrFail($id);
        return view('commerce::commerce.create_pawning', compact('sale'));
    }


    public function report_member($id)
    {
        $member = Member::with('sales_r')
            ->where('member_id', $id)
            ->get();
        // dd($member);

        return view('commerce::commerce.report_member', compact('member'));
    }

    public function scopeSearch($query, $keyword)
    {
        if (!$keyword) return $query;

        return $query->where(function ($q) use ($keyword) {

            // member fields
            $q->where('name', 'like', "%{$keyword}%")
                ->orWhere('phone', 'like', "%{$keyword}%")
                ->orWhere('email', 'like', "%{$keyword}%")
                ->orWhere('tax_number', 'like', "%{$keyword}%");

            // sales fields
            $q->orWhereHas('sales_r', function ($sale) use ($keyword) {
                $sale->where('brand', 'like', "%{$keyword}%")
                    ->orWhere('model', 'like', "%{$keyword}%")
                    ->orWhere('serial_number', 'like', "%{$keyword}%")
                    ->orWhere('note', 'like', "%{$keyword}%");
            });
        });
    }



    public function index()
    {
        $sale = DB::table('sale')->all();
        return view('commerce::index');
    }

    public function report_pawning($id = null)
    {
        $sale = Sale::where('id', $id)->first();
        $member_id = $sale->member_id;
        $member = Member::where('id', $member_id)->first();
        return view('commerce::commerce.report_pawning', compact('sale', 'member'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request) {}

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        return view('commerce::edit');
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id) {}

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $sale = Sale::findOrFail($id);
        $sale->delete();
        return redirect()->route('commerce.create_pawning')
            ->with('success', 'ยกเลิกรายการขายเรียบร้อย');
    }

    public function dok($id)
    {
        $sale = Sale::findOrFail($id);

        // dd($id);
        $dok = $sale->dok;

        $totalPrice = ($sale->transfer ?? 0) + ($sale->cash ?? 0);
        return view('commerce::commerce.dok', compact('sale', 'totalPrice', 'dok'));
    }



    public function dok_store(Request $request, $id)
    {
        $request->validate([
            'dok'  => 'required',
            'slip' => 'nullable|image|max:2048',
        ]);

        $oldSale = Sale::findOrFail($id);

        // dd($request->all(), $oldSale);

        $cash     = (int) str_replace(',', '', $request->cash);
        $transfer = (int) str_replace(',', '', $request->transfer);
        $interest = (int) str_replace(',', '', $request->dok);

        $paid = $cash + $transfer;

        if ($paid !== $interest) {
            return back()->withErrors('ยอดชำระไม่ตรง')->withInput();
        }

        DB::transaction(function () use ($request, $oldSale, $cash, $transfer, $id) {

            $slipPath = $request->hasFile('slip')
                ? $request->file('slip')->store('slips', 'public')
                : null;

            // ✅ บันทึกการรับเงิน
            Expenses::create([
                'user_id'   => auth()->user()->user_id,
                'product'   => "ต่อดอกเบี้ย {$oldSale->brand} {$oldSale->model}",
                'cash'      => $cash,
                'transfer'  => $transfer,
                'type'      => 'receive',
                'slip'      => $slipPath,
                'sale_id'   => $id,
            ]);

            // ✅ ปิดรายการเดิม
            $oldSale->update([
                'note_dok' => $request->note_dok,
                'status'   => 'closed',
            ]);

            $last = Sale::lockForUpdate()->orderBy('running_no', 'desc')->first();

            $newSale = $oldSale->replicate();

            $newSale->running_no = $request->running_no
                ?? ($last ? $last->running_no + 1 : 900000);

            $newSale->appointment_date = now()->addDays(10);

            // 👉 สถานะใม่
            $newSale->status = 'between';

            $newSale->note_dok = null; // หรือ $request->note_dok
            $newSale->dok = null;      // ถ้าเป็นดอกเก่า

            $newSale->user_id = auth()->user()->user_id;

            // save
            $newSale->save();
        });

        return redirect()->route('commerce.create_search')
            ->with('success', 'บันทึกการต่อดอกเรียบร้อย');
    }


    public function tai($id = null)
    {
        $sale = Sale::findOrFail($id);
        $totalPrice = ($sale->transfer ?? 0) + ($sale->cash ?? 0);
        return view('commerce::commerce.tai', compact('sale', 'totalPrice'));
    }

    public function tai_store(Request $request, $id)
    {
        $request->merge([
            'dok'      => str_replace(',', '', $request->dok),
            'transfer' => str_replace(',', '', $request->transfer),
            'cash'     => str_replace(',', '', $request->cash),
        ]);

        if ($request->cash) {
            $request->validate([
                'slip' => 'required|image|max:2048',
            ]);
        }
        $request->validate([
            'dok'      => 'required|numeric|min:0',
            'transfer' => 'nullable|numeric|min:0',
            'cash'     => 'nullable|numeric|min:0',
            'slip'     => 'nullable|image|max:2048',
        ]);

        $sale = Sale::findOrFail($id);

        $principal = (int) preg_replace('/[^\d]/', '', $request->total_price);

        $cash     = (float) $request->input('cash', 0);
        $transfer = (float) $request->input('transfer', 0);
        $paid     = $cash + $transfer;

        if ($paid !=  $principal) {
            return back()->withErrors(['payment' => 'ยอดชำระไม่ตรง'])->withInput();
        }

        DB::transaction(function () use ($request, $sale, $cash, $transfer) {

            $slipPath = $request->hasFile('slip')
                ? $request->file('slip')->store('slips', 'public')
                : null;


            $expenses = new Expenses();

            $expenses->product  = "ปิด {$sale->brand} {$sale->model}";
            $expenses->cash     = $cash;
            $expenses->transfer = $transfer;
            $expenses->type     = 'receive';
            $expenses->slip     = $slipPath;
            $expenses->sale_id = $sale->id;
            $expenses->user_id  = auth()->user()->user_id;
            $expenses->save();

            $sale->status = 'closed';
            $sale->note_dok = $request->note_dok;
            $sale->save();
        });

        return redirect()->route('commerce.create_search')->with('success', 'บันทึกการต่อดอกเรียบร้อย');
    }

    public function pueam($id = null)
    {
        $sale = Sale::findOrFail($id);
        $totalPrice = ($sale->transfer ?? 0) + ($sale->cash ?? 0);
        return view('commerce::commerce.pueam', compact('sale', 'totalPrice'));
    }

    public function store_pueam(Request $request, $id)
    {
        $request->merge([
            'transfer' => str_replace(',', '', $request->transfer),
            'cash'     => str_replace(',', '', $request->cash),
        ]);

        $request->validate([
            'list_pueam'       => 'required|string',
            'transfer'         => 'nullable|numeric|min:0',
            'cash'             => 'nullable|numeric|min:0',
            'slip'             => 'nullable|image|max:2048',
            'added_by_image'   => 'nullable|array',
            'added_by_image' => 'image|max:2048',
        ]);

        $cash     = (float) $request->cash;
        $transfer = (float) $request->transfer;

        DB::transaction(function () use ($request, $cash, $transfer, $id) {

            $slipPath = $request->hasFile('slip')
                ? $request->file('slip')->store('slips', 'public')
                : null;

            $paths = [];

            if ($request->hasFile('added_by_image')) {
                foreach ($request->file('added_by_image') as $image) {
                    $paths[] = $image->store('products', 'public');
                }
            }

            $expenses = new Expenses();
            $expenses->product  = "เพิ่มรายการจาก {$request->list_pueam}";
            $expenses->cash     = $cash;
            $expenses->transfer = $transfer;
            $expenses->type     = 'receive';
            $expenses->slip     = $slipPath;
            $expenses->product_images = count($paths) ? json_encode($paths) : null;
            $expenses->user_id  = auth()->user()->user_id;
            $expenses->sale_id  = $id;

            if ($request->hasFile('product_images')) {
                $expenses->product_images = $request->file('product_images')->store('products', 'public');
            } else {
                $expenses->product_images = null;
            }

            if ($request->hasFile('product_images_behind')) {
                $expenses->product_images_behind = $request->file('product_images_behind')->store('products', 'public');
            } else {
                $expenses->product_images_behind = null;
            }

            $expenses->save();

            $sale = Sale::findOrFail($id);
            $sale->cash += $cash;
            $sale->transfer += $transfer;
            $sale->save();
        });

        return redirect()->route('commerce.create_search')->with('success', 'บันทึกการต่อดอกเรียบร้อย');
    }

    public function show_member()
    {
        $member = member::paginate(20);
        return view('commerce::commerce.show_member', compact('member'));
    }

    public function search_member(Request $request)
    {
        $search = $request->search;

        $member = Member::when($search, function ($query, $search) {
            $query->where('fullname', 'like', "%{$search}%")
                ->orWhere('phone', 'like', "%{$search}%")
                ->orWhere('tax_number', 'like', "%{$search}%");
        })->paginate(20)->withQueryString();

        // dd($member);

        return view('commerce::commerce.show_member', compact('member'));
    }

    public function create_salefront($id = null)
    {
        return view('commerce::commerce.create_salefront', compact('id'));
    }

    // ขายหน้าร้าน

    public function store_salefront(Request $request)
    {
        $request->validate([
            'product' => 'required|string|max:255',
            'cash' => 'nullable|numeric|min:0',
            'transfer' => 'nullable|numeric|min:0',
            'type' => 'required|in:receive,pay',
            'slip' => 'nullable|image|mimes:jpg,jpeg,png|max:3000',
        ], [
            'type.in' => 'ประเภทต้องเป็น receive หรือ pay เท่านั้น',
            'type.required' => 'กรุณาระบุประเภทการทำรายการ',
        ]);

        $slipPath = null;

        if ($request->hasFile('slip')) {
            $slipPath = $request->file('slip')->store('slips', 'public');
        }

        $expenses = new Expenses();
        $expenses->product = $request->product;
        $expenses->cash = $request->cash ?? 0;
        $expenses->transfer = $request->transfer ?? 0;
        $expenses->type = $request->type;
        $expenses->note = $request->note;
        $expenses->slip = $slipPath;
        $expenses->user_id = auth()->user()->user_id;
        // dd(auth()->user()->user_idx);

        $expenses->save();

        return redirect()->route('commerce.report_salefront')->with('success', 'บันทึกข้อมูลเรียบร้อย');
    }

    public function report_salefront(Request $request)
    {
        $start = $request->start_date;
        $end = $request->end_date;

        $query = Expenses::query()
            ->when($start, fn($q) =>
            $q->whereDate('created_at', '>=', $start))
            ->when($end, fn($q) =>
            $q->whereDate('created_at', '<=', $end));

        $totals = (clone $query)
            ->selectRaw('
            type,
            SUM(COALESCE(cash,0)) as cash_sum,
            SUM(COALESCE(transfer,0)) as transfer_sum
        ')
            ->groupBy('type')
            ->get();

        $totalReceive = 0;
        $totalPay = 0;

        foreach ($totals as $row) {
            $sum = $row->cash_sum + $row->transfer_sum;

            if ($row->type === 'receive') {
                $totalReceive = $sum;
            } elseif ($row->type === 'pay') {
                $totalPay = $sum;
            }
        }

        $balance = $totalReceive - $totalPay;

        $expenses = (clone $query)
            ->with('user:user_id,name')
            ->latest()
            ->simplePaginate(20)
            ->withQueryString();

        $report = DailyReport::latest()->first();
        // dd($report);
        return view('commerce::commerce.report_sale', compact(
            'expenses',
            'totalReceive',
            'totalPay',
            'balance',
            'start',
            'end',
            'report'
        ));
    }

    public function saleListExcel(Request $request)
    {
        return Excel::download(
            new SalefontExport($request),
            'sale_list.xlsx'
        );
    }

    // PDFFFFF
    public function reportsalefrontPdf(Request $request)
    {
        $query = Expenses::query()
            ->when($request->filled('status'), function ($q) use ($request) {
                $q->where('type', $request->status);
            })
            ->when($request->filled('search'), function ($q) use ($request) {
                $q->where('product', 'like', "%{$request->search}%");
            });

        $expenses = $query->with('user')
            ->latest()
            ->get();

        $totalReceive = $expenses->where('type', 'receive')->sum(function ($item) {
            return $item->cash + $item->transfer;
        });
        // dd($query);

        $totalPay = $expenses->where('type', 'pay')->sum(function ($item) {
            return $item->cash + $item->transfer;
        });

        $balance = $totalReceive - $totalPay;

        $start = $request->start ?? null;
        $end = $request->end ?? null;

        // ก่อนแก้ถ้ามีปัญหาอะไรก็ให้ใช้แบบเดิม
        // $pdf = Pdf::loadView('commerce::commerce.sale_list_PDF', compact(
        //     'expenses',
        //     'totalReceive',
        //     'totalPay',
        //     'balance',
        //     'start',
        //     'end'
        // ))->setPaper('a4', 'portrait');

        $pdf = Pdf::loadView('commerce::commerce.report_sale_pdf', compact(
            'expenses',
            'totalReceive',
            'totalPay',
            'balance',
            'start',
            'end'
        ))->setPaper('a4', 'portrait');
        return $pdf->stream('report_sale.pdf');
    }

    public function detil_sale($id)
    {
        $sale = Sale::with(['member_r', 'user_r'])->findOrFail($id);
        $price = $sale->transfer + $sale->cash;
        $settings = Settings::first();
        // dd($settings);

        return view('commerce::commerce.detil_sale', compact('sale', 'price', 'settings'));
    }


    public function reportSalePdf()
    {
        $member = Member::with('sales_r')->get();

        $pdf = Pdf::loadView('commerce::commerce.report_sale_pdf', compact('member'))
            ->setPaper('a4', 'portrait');

        return $pdf->stream('report_sale.pdf');
    }

    public function sale_list(Request $request)
    {
        $status = $request->status;
        $search = $request->search;

        $start_date = $request->start_date;
        $end_date   = $request->end_date;

        $query = Sale::query();


        if (!empty($status)) {

            $query->where('status', $status);
        }

        if (!empty($search)) {

            $query->where(function ($q) use ($search) {

                $q->where('brand', 'like', "%{$search}%")
                    ->orWhere('model', 'like', "%{$search}%")
                    ->orWhere('serial_number', 'like', "%{$search}%")
                    ->orWhere('running_no', 'like', "%{$search}%")
                    ->orWhere('note', 'like', "%{$search}%");
            });
        }

        if (!empty($start_date) && !empty($end_date)) {

            $query->whereBetween('created_at', [
                $start_date . ' 00:00:00',
                $end_date . ' 23:59:59'
            ]);
        } elseif (!empty($start_date)) {

            $query->whereDate('created_at', '>=', $start_date);
        } elseif (!empty($end_date)) {

            $query->whereDate('created_at', '<=', $end_date);
        }

        $total = (clone $query)->count();

        $sales = $query
            ->latest()
            ->paginate(20)
            ->withQueryString();

        return view(
            'commerce::commerce.sale_list',
            compact(
                'sales',
                'status',
                'search',
                'start_date',
                'end_date',
                'total'
            )
        );
    }

    public function reportSaleExcel(Request $request)
    {

        return Excel::download(
            new ReportSaleExport($request),
            'report_sale.xlsx'
        );
    }

    public function saleListPdf(Request $request)
    {
        $status = $request->status;
        $search = $request->search;

        $start_date = $request->start_date;
        $end_date   = $request->end_date;

        $query = Sale::query()
            ->with([
                'user_r:user_id,name',
                'member_r:member_id,fullname'
            ]);

        /*
    |--------------------------------------------------------------------------
    | Filter Status
    |--------------------------------------------------------------------------
    */

        if (!empty($status)) {

            $query->where('status', $status);
        }

        /*
    |--------------------------------------------------------------------------
    | Search
    |--------------------------------------------------------------------------
    */

        if (!empty($search)) {

            $query->where(function ($q) use ($search) {

                $q->where('brand', 'like', "%{$search}%")
                    ->orWhere('model', 'like', "%{$search}%")
                    ->orWhere('serial_number', 'like', "%{$search}%")
                    ->orWhere('running_no', 'like', "%{$search}%")
                    ->orWhere('note', 'like', "%{$search}%");
            });
        }

        /*
    |--------------------------------------------------------------------------
    | Date Filter
    |--------------------------------------------------------------------------
    */

        if (!empty($start_date) && !empty($end_date)) {

            $query->whereBetween('created_at', [
                $start_date . ' 00:00:00',
                $end_date . ' 23:59:59'
            ]);
        } elseif (!empty($start_date)) {

            $query->whereDate('created_at', '>=', $start_date);
        } elseif (!empty($end_date)) {

            $query->whereDate('created_at', '<=', $end_date);
        }

        $sales = $query
            ->latest()
            ->get();

        $total = $sales->sum(function ($item) {

            return ($item->cash ?? 0) + ($item->transfe ?? 0);
        });

        $pdf = Pdf::loadView(
            'commerce::commerce.sale_list_PDF',
            compact(
                'sales',
                'total',
                'status',
                'search',
                'start_date',
                'end_date'
            )
        )
            ->setPaper('a4', 'portrait');

        return $pdf->stream('report_sale.pdf');
    }


    public function show_sale($id)
    {
        $sale = Sale::findOrFail($id);

        return redirect()->route('commerce.create_pawning', [
            'id' => $sale->id,
            'mode' => 'view', // สำคัญ
        ]);
    }
    public function slip($id)
    {
        $sale = Sale::findOrFail($id);

        $sale->status = 'fall';
        $sale->save();

        $member = $sale->member_r;

        if ($member) {
            $member->status = 'foreclosed';
            $member->save();
        }

        return back()->with('success', 'หลุดจำนำเรียบร้อย');
    }

    public function edit_status_member($id)
    {
        $member = Member::findOrFail($id);

        return view('commerce::commerce.edit_status_member', compact('member'));
    }

    public function stor_edit_status_member(Request $request, $id)
    {
        $member = Member::findOrFail($id);

        $member->status = $request->status;
        $member->save();

        return redirect()->route('commerce.show_member')
            ->with('success', 'ปรับสถานะลูกค้าแล้ว');
    }

    public function manage_dok()
    {
        $interest = Interest::where('id', 3)->first();
        return view('commerce::commerce.manage_dok', compact('interest'));
    }

    public function stor_manage_dok(Request $request, $id)
    {
        $request->validate([
            'date' => 'required|numeric|min:0|max:30',
            'percent' => 'required|numeric',
        ]);
        Interest::where('id', $id)->update([
            'date' => $request->date,
            'percent' => $request->percent
        ]);

        return back();
    }


    public function report_detil_sale()
    {
        $member = Member::with('sales_r')->get();

        foreach ($member as $m) {
            foreach ($m->sales_r as $sale) {

                // normalize product_images
                $sale->product_images = $this->normalizeArray($sale->product_images);

                // normalize qr_code
                $sale->qr_code = $this->normalizeArray($sale->qr_code);
            }
        }

        $pdf = Pdf::loadView('commerce::commerce.report_sale_pdf', compact('member'))
            ->setPaper('a5', 'landscape');

        return $pdf->stream('report_sale.pdf');
    }

    public function settings()
    {
        $settings = Settings::first();
        // dd($settings);
        return view('commerce::commerce.settings', compact('settings'));
    }

    public function save_settings(Request $request, $id = null)
    {
        // dd($id);
        $request->validate([
            'running_no' => 'required|nullable|min:6|max:6',
            'company_name' => 'required|string',
            'phone' => 'required|nullable|min:10|max:10',
        ]);

        // ถ้ามี id แก้ไข ถ้าไม่มีสร้างใหม่
        $settings = $id ? Settings::findOrFail($id) : new Settings();

        // กำหนดค่า
        $settings->running_no = $request->running_no;
        $settings->company_name = $request->company_name;
        $settings->phone = $request->phone;
        $settings->save();
        return back()->with('success', 'บันทึกการตั้งค่าเรียบร้อย');
    }

    public function closeDay(Request $request)
    {
        $result = DB::transaction(function () {

            // 1. Query
            $salesQuery = Sale::whereNull('daily_report_id');

            $expensesQuery = Expenses::whereNull('daily_report_id');

            // ไม่มีข้อมูล
            if (
                $salesQuery->count() === 0 &&
                $expensesQuery->count() === 0
            ) {

                return [
                    'error' => 'ไม่มีรายการค้างปิดยอดในขณะนี้'
                ];
            }

            // 2. คำนวณยอด
            $totalSales = (clone $salesQuery)
                ->sum(DB::raw('transfer + cash'));

            $totalExpenses = (clone $expensesQuery)
                ->sum(DB::raw('transfer + cash'));

            $netAmount = $totalSales + $totalExpenses;

            // 3. สร้างรายงาน
            $report = new DailyReport();

            $report->report_date = now()->toDateString();

            $report->total_sales = $totalSales;

            $report->total_expenses = $totalExpenses;

            $report->net_amount = $netAmount;

            $report->user_id = auth()->user()->user_id;

            $report->save();

            // 4. update daily_report_id
            $salesQuery->update([
                'daily_report_id' => $report->id
            ]);

            $expensesQuery->update([
                'daily_report_id' => $report->id
            ]);

            return [
                'report_id' => $report->id,
                'totalSales' => $totalSales,
                'totalExpenses' => $totalExpenses,
                'netAmount' => $netAmount,
            ];
        });

        // error
        if (isset($result['error'])) {

            return back()->with(
                'error',
                $result['error']
            );
        }

        $settings = Settings::first();

        $fileName =
            $settings->company_name .
            '-' .
            now()->format('Y-m-d') .
            '.xlsx';

        return Excel::download(
            new ReportSaleMultiExport($request),
            $fileName
        );
    }

    public function daily_report_preview($id)
    {
        $report = DailyReport::findOrFail($id);
        // dd($report->created_at); 
        $sales = Sale::where('daily_report_id', $id)->get();
        $expenses = Expenses::where('daily_report_id', $id)->get();

        $pay = Expenses::where('daily_report_id', $id)->where('type', 'pay')->sum(DB::raw('cash + transfer'));
        $receive = Expenses::where('daily_report_id', $id)->where('type', 'receive')->sum(DB::raw('cash + transfer'));

        return view('commerce::commerce.daily_report_preview', compact('report', 'sales', 'expenses', 'pay', 'receive'));
    }

    public function daily_report_exportpdf($id)
    {
        // dd($id);
        // ดึงข้อมูลสรุปยอด
        $report = DailyReport::findOrFail($id);
        // dd($report);

        // ดึงรายการย่อยที่ผูกกับ Report นี้
        // $sales = Sale::where('daily_report_id', $id)->get();
        $expenses = Expenses::where('daily_report_id', $id)->get();

        $data = [
            'report' => $report,
            // 'sales' => $sales,
            'expenses' => $expenses,
            'date' => Carbon::parse($report->report_date)->format('d/m/Y'),
        ];

        // โหลด View สำหรับทำ PDF (ต้องสร้างไฟล์ blade ในขั้นตอนถัดไป)
        $pdf = Pdf::loadView('commerce::commerce.daily_report_exportpdf', $data);

        // ตั้งชื่อไฟล์: report_2023-10-27.pdf
        return $pdf->stream('report_' . $report->report_date . '.pdf');
    }
}

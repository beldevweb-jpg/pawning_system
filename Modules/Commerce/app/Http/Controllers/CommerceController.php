<?php

namespace Modules\Commerce\Http\Controllers;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Modules\Commerce\Models\Settings;
use Modules\Commerce\Models\Sale;
use Modules\Members\Models\Member;
use Modules\Commerce\Models\Expenses;
use Endroid\QrCode\QrCode;
use Endroid\QrCode\Writer\SvgWriter;
use Illuminate\Support\Facades\Storage;
use Modules\Commerce\Models\interest;
use Carbon\Carbon;
use Barryvdh\DomPDF\Facade\Pdf;



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
        // dd($id);
        $sale = Sale::find($id) ?? new Sale();

        $sale_between = Sale::where('status', 'between')->first();
        // dd($sale_between);
        if (Member::where('member_id', $id)->exists()) {
            $member = Member::where('member_id', $id)->first();
        } else {
            $member = Member::where('sale_id', $id)->first();
        }
        // dd($sale, $member, $sale_between);
        return view('commerce::commerce.create_pawning', compact('sale', 'member', 'sale_between'));
    }

    public function store_pawning(Request $request, $id = null)
    {
        // dd($request);

        $request->validate([
            'type_category'      => 'required|string',
            'action_type'        => 'required_if:sale_between,1',
            'action_type_other'  => 'required_if:action_type,other|string',
            'brand'              => 'nullable|string',
            'model'              => 'nullable|string',
            'locker_pass'        => 'nullable|string',
            'drawn_lock'         => 'nullable|string',
            'serial_number'      => 'required|string',
            'cash'               => 'nullable|numeric|min:0',
            'transfer'           => 'nullable|numeric|min:0',
            'note'               => 'nullable|string',
            'appointment_date' => 'required|date|after_or_equal:today',
            'product_images'   => 'nullable|image|mimes:jpg,jpeg,png|max:3000',
            // 'bill_QR_store'    => 'nullable|image|mimes:jpg,jpeg,png|max:3000',
            'bill'               => 'nullable|image|mimes:jpg,jpeg,png|max:3000',
        ], [

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
            $sale->member_id        = $id;
            $sale->type_category    = $request->type_category;
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

            $expenses = new Expenses();
            $expenses->user_id = auth()->user()->user_id;
            $product = "จำ {$sale->brand} {$sale->model}";
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
            $data['product_images_behind'] = $request->file('product_images_behind')->store('products', 'public');
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

        // ✅ รวม query เดียว (เร็วขึ้น)
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

        // ✅ เพิ่มอันนี้ (ของเดิมลืม)
        $balance = $totalReceive - $totalPay;

        $expenses = (clone $query)
            ->with('user:user_id,name') // ✅ ลดโหลด
            ->latest()
            ->simplePaginate(20)   // ✅ เร็วกว่า paginate
            ->withQueryString();

        return view('commerce::commerce.report_sale', compact(
            'expenses',
            'totalReceive',
            'totalPay',
            'balance',
            'start',
            'end'
        ));
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

        $totalPay = $expenses->where('type', 'pay')->sum(function ($item) {
            return $item->cash + $item->transfer;
        });

        $balance = $totalReceive - $totalPay;

        $start = $request->start ?? null;
        $end = $request->end ?? null;

        $pdf = Pdf::loadView('commerce::commerce.sale_list_PDF', compact(
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

        $query = Sale::query();

        if ($request->filled('status')) {
            $query->where('status', $status);
        }

        if ($request->filled('search')) {
            $query->where(function ($q) use ($search) {
                $q->where('brand', 'like', "%{$search}%")
                    ->orWhere('model', 'like', "%{$search}%")
                    ->orWhere('serial_number', 'like', "%{$search}%")
                    ->orWhere('note', 'like', "%{$search}%");
            });
        }

        $total = (clone $query)->count();

        $sales = $query->latest()
            ->paginate(20)
            ->withQueryString();

        return view('commerce::commerce.sale_list', compact('sales', 'status', 'search', 'total'));
    }

    public function saleListPdf(Request $request)
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

        $totalPay = $expenses->where('type', 'pay')->sum(function ($item) {
            return $item->cash + $item->transfer;
        });

        $balance = $totalReceive - $totalPay;

        $start = $request->start ?? null;
        $end = $request->end ?? null;

        $pdf = Pdf::loadView('commerce::commerce.sale_list_PDF', compact(
            'expenses',
            'totalReceive',
            'totalPay',
            'balance',
            'start',
            'end'
        ))->setPaper('a4', 'portrait');

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
}

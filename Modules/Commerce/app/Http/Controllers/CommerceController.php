<?php

namespace Modules\Commerce\Http\Controllers;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Modules\Commerce\Models\Sale;
use Modules\Members\Models\Member;
use Illuminate\Support\Str;
use Modules\Commerce\Models\Expenses;
use Endroid\QrCode\QrCode;
use Endroid\QrCode\Writer\SvgWriter;
use Illuminate\Support\Facades\Storage;
use Modules\Commerce\Models\interest;
use Carbon\Carbon;
use Endroid\QrCode\Builder\Builder;



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
            'phone' => 'required|numeric',
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
        $request->validate([
            'type_category'      => 'required|string',
            'brand'              => 'nullable|string',
            'model'              => 'nullable|string',
            'locker_pass'        => 'nullable|string',
            'drawn_lock'         => 'nullable|string',
            'serial_number'      => 'required|string',
            'cash'               => 'nullable|numeric|min:0',
            'transfer'           => 'nullable|numeric|min:0',
            'note'               => 'nullable|string',
            'appointment_date'   => 'nullable|date|after_or_equal:today',
            'product_images.*'   => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
            'bill'               => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
        ]);

        $cash = $request->cash ?? 0;
        $transfer = $request->transfer ?? 0;
        $principal = $cash + $transfer;

        if ($principal <= 0) {
            return back()
                ->withErrors(['payment' => 'กรุณาระบุจำนวนเงิน'])
                ->withInput();
        }

        Member::findOrFail($id);

        $member_id = DB::transaction(function () use ($request, $cash, $transfer, $principal, $id) {

            $sale = new Sale();

            // =========================
            // กำหนดค่าพื้นฐาน
            // =========================
            $sale->member_id       = $id;
            $sale->type_category   = $request->type_category;
            $sale->brand           = $request->brand;
            $sale->model           = $request->model;
            $sale->other_brand     = $request->other_brand;
            $sale->other_type      = $request->other_type;
            $sale->locker_pass     = $request->locker_pass;
            $sale->drawn_lock      = $request->drawn_lock;
            $sale->serial_number   = $request->serial_number;
            $sale->cash            = $cash;
            $sale->transfer        = $transfer;
            $sale->note            = $request->note;
            $sale->appointment_date = $request->appointment_date;

            // =========================
            // Upload รูปสินค้า
            // =========================
            if ($request->hasFile('product_images')) {
                $paths = [];
                foreach ($request->file('product_images') as $image) {
                    $paths[] = $image->store('products', 'public');
                }
                $sale->product_images = json_encode($paths);
            }

            if ($request->hasFile('bill')) {
                $sale->bill = $request->file('bill')->store('bills', 'public');
            }

            // =========================
            // คำนวณดอกเบี้ย
            // =========================
            $sale->dok = 0;

            if ($request->appointment_date) {

                $appointmentDate = Carbon::parse($request->appointment_date);
                $today = Carbon::now();

                $days = $today->diffInDays($appointmentDate);

                $interestRate = Interest::where('days', '>=', $days)
                    ->orderBy('days', 'asc')
                    ->first();

                if (!$interestRate) {
                    $interestRate = Interest::orderBy('days', 'desc')->first();
                }

                if ($interestRate) {
                    $percent = $interestRate->percent;
                    $interestAmount = ($principal * $percent) / 100;
                    $sale->dok = $interestAmount;
                }
            }

            // =========================
            // save ครั้งแรก (เอา id)
            // =========================
            $sale->save();

            // =========================
            // สร้าง QR Code
            // =========================
            $url = route('commerce.show_sale', $sale->id);

            $qrCode = QrCode::create($url)
                ->setSize(300)
                ->setMargin(10);

            $writer = new SvgWriter();
            $result = $writer->write($qrCode);

            $qrPath = 'qrcodes/create_pawning_' . $sale->id . '.svg';


            Storage::disk('public')->put($qrPath, $result->getString());

            $sale->qr_code = $qrPath;
            $sale->save();

            // =========================
            // อัปเดต member
            // =========================
            Member::where('member_id', $id)->update([
                'sale_id' => $sale->id
            ]);

            // =========================
            // บันทึกการเงิน
            // =========================
            $expense = new Expenses();
            $expense->product  = "ขายสินค้า {$sale->brand} {$sale->model}";
            $expense->cash     = $cash;
            $expense->transfer = $transfer;
            $expense->type     = 'receive';
            // $expense->user_id  = auth()->id();
            $expense->save();

            return $sale->member_id;
        });

        return redirect()->route('commerce.create_search', [
            'member_id' => $member_id,
            'success'   => 'บันทึกข้อมูลเรียบร้อย'
        ]);
    }

    public function show($id)
    {
        $sale = Sale::with('member_r')->findOrFail($id);
        return view('commerce::commerce.show', compact('sale'));
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

        $sale = Sale::findOrFail($id);

        $cash     = (int) str_replace(',', '', $request->cash);
        $transfer = (int) str_replace(',', '', $request->transfer);
        $interest = (int) str_replace(',', '', $request->dok);

        $paid = $cash + $transfer;

        if ($paid !== $interest) {
            return back()->withErrors('ยอดชำระไม่ตรง')->withInput();
        }

        DB::transaction(function () use ($request, $sale, $cash, $transfer, $id) {

            $slipPath = $request->hasFile('slip')
                ? $request->file('slip')->store('slips', 'public')
                : null;

            $expenses = new Expenses();
            // $expenses->user_id = auth()->id();
            $product = "ต่อดอกเบี้ย {$sale->brand} {$sale->model}";
            $expenses->product = $product;
            $expenses->cash = $cash;
            $expenses->transfer = $transfer;
            $expenses->type = 'receive';
            $expenses->slip = $slipPath;
            $expenses->sale_id = $id;
            $expenses->save();
        });

        return redirect()->route('commerce.create_search')->with('success', 'บันทึกการต่อดอกเรียบร้อย');
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
            $expenses->user_id  = auth()->id();
            $expenses->save();

            $sale->status = 'closed';
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
            'added_by_image.*' => 'image|max:2048',
        ]);

        $originalSale = Sale::findOrFail($id);

        $cash     = (float) $request->cash;
        $transfer = (float) $request->transfer;

        DB::transaction(function () use ($request, $originalSale, $cash, $transfer, $id) {

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
            // $expenses->user_id  = auth()->id();
            $expenses->sale_id = $id;
            $expenses->save();

            $sale = Sale::findOrFail($id);
            $sale->cash += $cash;
            $sale->transfer += $transfer;
            $sale->save();
        });

        return redirect()->route('commerce.create_search')->with('success', 'บันทึกการต่อดอกเรียบร้อย');
    }

    public function create_sellfront($id = null)
    {
        return view('commerce::commerce.create_sellfront', compact('id'));
    }

    public function store_sellfront(Request $request)
    {
        $request->validate([
            'product' => 'required|string|max:255',
            'cash' => 'nullable|numeric|min:0',
            'transfer' => 'nullable|numeric|min:0',
            'type' => 'required|in:receive,pay',
            'slip' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
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
        $expenses->user_id = auth()->id();

        $expenses->save();

        return redirect()->route('commerce.report_sellfront')->with('success', 'บันทึกข้อมูลเรียบร้อย');
    }

    public function report_sellfront(Request $request)
    {
        $start = $request->start_date;
        $end = $request->end_date;

        $query = Expenses::query()
            ->when($start, fn($q) =>
            $q->whereDate('created_at', '>=', $start))
            ->when($end, fn($q) =>
            $q->whereDate('created_at', '<=', $end));

        $totalReceive = (clone $query)
            ->where('type', 'receive')
            ->sum(DB::raw('cash + transfer'));

        $totalPay = (clone $query)
            ->where('type', 'pay')
            ->sum(DB::raw('cash + transfer'));

        $balance = $totalReceive - $totalPay;

        $expenses = (clone $query)
            ->with('user')
            ->latest()
            ->paginate(20)
            ->withQueryString();

        return view('commerce::commerce.report_sellfont', compact(
            'expenses',
            'totalReceive',
            'totalPay',
            'balance',
            'start',
            'end'
        ));
    }

    public function sale_list()
    {
        $sales = Sale::paginate(50);
        // dd($sales);
        return view('commerce::commerce.sale_list', compact('sales'));
    }

    public function show_sale($id)
    {
        $sale = Sale::findOrFail($id);
        // dd($sale);
        return redirect()->route('commerce.create_pawning', $sale->id);
    }

    public function slip($id)
    {
        $sale = Sale::findOrFail($id);
        $sale->status = 'fall';
        $sale->save();

        return back()->with('success', 'หลุดจำนำเรียบร้อย');
    }
}

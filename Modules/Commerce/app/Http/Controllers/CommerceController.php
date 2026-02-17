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

class CommerceController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function create_search()
    {
        return view('commerce::commerce.create_search');
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
        // dd($id);
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

            if ($request->hasFile('idcard_image')) {
                $member->idcard_image = $request->file('idcard_image')
                    ->store('idcards', 'public');
            }

            $member->save();
            return $member;
        });

        return redirect()
            ->route('commerce.create_type_of_sale', ['id' => $member->member_id]);
    }

    // ฟอร์มแสดงการจำนำ
    public function create_pawning($id)
    {
        $sale = Sale::find($id) ?? new Sale();

        // dd($sale, $id);
        $member = Member::where('sale_id', $id)->first();

        return view('commerce::commerce.create_pawning', compact('sale', 'member'));
    }


    // public function store_pawning(Request $request)
    // {
    //     $request->validate([
    //         'sale_id' => 'required|exists:sales,id',
    //         'subcategories' => 'required',
    //         'type_category' => 'required',
    //         'price' => 'required|numeric',
    //         'type_price' => 'required|string',
    //         'lock_pass' => 'required|string',
    //         'serial_number' => 'required|string',
    //         'note' => 'nullable|string',
    //         'idcard_images' => 'required',
    //     ]);

    //     $member_id = null;

    //     DB::transaction(function () use ($request, &$member_id) {

    //         $sale = Sale::findOrFail($request->sale_id);
    //         $member_id = $sale->member_id;

    //         $updateData = [
    //             'serial_number' => $request->serial_number,
    //             'note' => $request->note,
    //             'type_price' => $request->type_price,
    //             'lock_pass' => $request->lock_pass,
    //             'price' => $request->price,
    //             'type_category' => $request->type_category,
    //             'subcategories' => $request->subcategories,
    //             'idcard_images' => $request->required,
    //         ];

    //         if ($request->filled('type_serve')) {
    //             $updateData['type_serve'] = $request->type_serve;
    //         }

    //         $sale->update($updateData);
    //     });

    //     return redirect()->route('commerce.create_search', [
    //         'member_id' => $member_id
    //     ]);
    // }

    public function store_pawning(Request $request, $id)
    {
        // dd($request->all(), $id);
        $request->validate([
            'type_category' => 'required|string',

            'brand' => 'nullable|string',
            'model' => 'nullable|string',

            'locker_pass' => 'nullable|string',
            'drawn_lock' => 'nullable|string',

            'serial_number' => 'required|string',

            'cash' => 'nullable|numeric|min:0',
            'transfer' => 'nullable|numeric|min:0',

            'note' => 'nullable|string',
            'appointment_date' => 'nullable|date',

            'product_images.*' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
            'bill' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',

        ]);

        $cash = $request->cash ?? 0;
        $transfer = $request->transfer ?? 0;
        $totalPrice = $cash + $transfer;

        if ($totalPrice <= 0) {
            return back()
                ->withErrors(['payment' => 'กรุณาระบุจำนวนเงิน'])
                ->withInput();
        }
        Member::findOrFail($id);

        $member_id = DB::transaction(function () use ($request, $cash, $transfer, $totalPrice, $id) {

            $sale = new Sale();
            $sale->brand = strtolower($request->brand);

            $sale->member_id = $id;
            $sale->other_brand = $request->other_brand;
            $sale->other_type = $request->other_type;
            $sale->brand = $request->brand;
            $sale->model = $request->model;
            $sale->locker_pass = $request->locker_pass;
            $sale->drawn_lock = $request->drawn_lock;
            $sale->serial_number = $request->serial_number;

            $sale->cash = $cash;
            $sale->transfer = $transfer;

            $sale->note = $request->note;
            $sale->appointment_date = $request->appointment_date;

            // upload รูป
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

            $sale->save();

            Member::where('id', $id)->update([
                'sale_id' => $sale->id
            ]);

            $Expenses = new Expenses();
            $Expenses->product = "ขายสินค้า {$sale->brand} {$sale->model}";
            $Expenses->cash = $cash;
            $Expenses->transfer = $transfer;
            $Expenses->type = 'receive';
            $Expenses->user_id = auth()->id();
            $Expenses->save();

            return $sale->member_id; // ✅ อันนี้จะถูกส่งออกไป
        });


        return redirect()->route('commerce.create_search', [
            'member_id' => $member_id,
            'success' => 'บันทึกข้อมูลเรียบร้อย'
        ]);
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
     * Show the specified resource.
     */
    public function show($id)
    {
        return view('commerce::show');
    }

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

        $totalPrice = ($sale->transfer ?? 0) + ($sale->cash ?? 0);

        return view('commerce::commerce.dok', compact('sale', 'totalPrice'));
    }



    public function store_dok(Request $request)
    {
        $request->validate([
            'sale_id' => 'required|exists:sales,id',
            'dok' => 'required|integer|min:0',
            'transfer' => 'nullable|integer|min:0',
            'cash' => 'nullable|integer|min:0',
            'slip' => 'nullable|image|max:2048',
        ]);

        $sale = Sale::findOrFail($request->sale_id);

        $principal = $sale->total_price;
        $interest = $request->dok;
        $total = $principal + $interest;

        $paid = $request->input('cash', 0) + $request->input('transfer', 0);

        if ($paid !== $total) {
            return back()->withErrors('ยอดชำระไม่ตรง');
        }

        DB::transaction(function () use ($request, $sale, $principal, $interest, $total, $paid) {

            $slipPath = $request->hasFile('slip')
                ? $request->file('slip')->store('slips', 'public')
                : null;

            $expenses = new Expenses();
            
            $productName = "ต่อดอกเบี้ย {$sale->brand} {$sale->model}";
            $expenses->product = $productName;
            $expenses->cash = $request->input('cash', 0);
            $expenses->transfer = $request->input('transfer', 0);
            $expenses->type = 'receive';
            $expenses->slip = $slipPath;
            $expenses->user_id = auth()->id();
            $expenses->save();
        });

        return back()->with('success', 'บันทึกการต่อดอกเรียบร้อย');
    }

    public function tai($id = null)
    {
        $sale = Sale::find($id);
        // dd($sale);
        return view('commerce::commerce.tai', compact('sale'));
    }

    public function tai_store(Request $request)
    {
        $request->validate([
            'principal' => 'required|numeric|min:0',
            'interest' => 'required|numeric|min:0',
            'total' => 'required|numeric|min:0',
            'cash' => 'nullable|numeric|min:0',
            'transfer' => 'nullable|numeric|min:0',
            'slip' => 'nullable|image|max:2048',
        ]);

        $slipPath = null;

        if ($request->hasFile('slip')) {
            $slipPath = $request->file('slip')
                ->store('slips', 'public');
        }

        $tai = new Tai();
        $tai->principal = $request->principal;
        $tai->interest = $request->interest;
        $tai->total = $request->total;
        $tai->cash = $request->cash ?? 0;
        $tai->transfer = $request->transfer ?? 0;
        $tai->slip = $slipPath;
        $tai->user_id = auth()->id();
        $tai->save();

        return back()->with('success', 'บันทึกการไถ่ถอนเรียบร้อย');
    }

    public function pueam()
    {
        return view('commerce::commerce.pueam');
    }

    public function store_pueam(Request $request)
    {
        $request->validate([
            'amount' => 'required|numeric|min:0',
            'cash' => 'nullable|numeric|min:0',
            'transfer' => 'nullable|numeric|min:0',
            'slip' => 'nullable|image|max:2048',
        ]);

        $slipPath = null;

        if ($request->hasFile('slip')) {
            $slipPath = $request->file('slip')
                ->store('slips', 'public');
        }

        Pueam::create([
            'amount' => $request->amount,
            'cash' => $request->cash ?? 0,
            'transfer' => $request->transfer ?? 0,
            'slip' => $slipPath,
            'user_id' => auth()->id(),
        ]);

        return back()->with('success', 'บันทึกการปื้เอมเรียบร้อย');
    }

    public function create_sellfront($id = null)
    {
        return view('commerce::commerce.create_sellfront', compact('id'));
    }

    public function store_sellfront(Request $request)
    {
        // dd($request->all());
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

        // upload file
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

        // ⭐ สร้าง query กลาง
        $query = Expenses::query()
            ->when($start, fn($q) =>
            $q->whereDate('created_at', '>=', $start))
            ->when($end, fn($q) =>
            $q->whereDate('created_at', '<=', $end));

        // 🔥 sum จาก query จริง
        $totalReceive = (clone $query)
            ->where('type', 'receive')
            ->sum(DB::raw('cash + transfer'));

        $totalPay = (clone $query)
            ->where('type', 'pay')
            ->sum(DB::raw('cash + transfer'));

        $balance = $totalReceive - $totalPay;

        // ⭐ paginate แยก
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
        $sales = Sale::get();
        // dd($sales);
        return view('commerce::commerce.sale_list', compact('sales'));
    }

    public function show_sale($id)
    {
        $sale = Sale::findOrFail($id);
        // dd($sale);
        return redirect()->route('commerce.create_pawning', $sale->id);
    }
}

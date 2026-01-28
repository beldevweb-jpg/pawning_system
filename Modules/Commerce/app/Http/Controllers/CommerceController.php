<?php

namespace Modules\Commerce\Http\Controllers;

use DB;
use Illuminate\Support\Facades\Validator;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Modules\Commerce\Models\Sale;
use Modules\Members\Models\Member;

class CommerceController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function create_search()
    {
        return view('commerce::commerce.create_search');
    }

    public function create_member()
    {
        return view('commerce::commerce.create_member');
    }

    public function store_create_member(Request $request)
    {
        // Logic to store member information
    }

    // ค่้นหาประวัติการขาย/ลูกค้า
    public function store_create_search(Request $request)
    {
        $tax_number = $request->tax_number;
        $serial_number = $request->serial_number;

        // ค้นหาด้วย serial_number
        if ($serial_number) {
            $sale = Sale::where('serial_number', $serial_number)->first();

            if (!$sale) {
                return redirect()
                    ->route('commerce.create_type_of_sale')
                    ->withErrors(['error', 'ไม่พบประวัติเครื่อง']);
            }

            return view('commerce::commerce.create_type_of_sale', compact('sale'));
        }

        // ค้นหาด้วย tax_number
        if ($tax_number) {
            $member = Member::where('tax_number', $tax_number)->first();

            if (!$member) {
                return redirect()
                    ->route('commerce.create_type_of_sale')
                    ->withErrors(['error', 'ไม่พบประวัติลูกค้า']);
            }

            return view('commerce::commerce.create_type_of_sale', compact('member'));
        }
    }

    // แสดงหน้าลูกค้า
    public function customer()
    {
        return view('commerce::commerce.customer');
    }

    // ฟอร์มแสดงประเป็นการขาย
    public function create_type_of_sale()
    {
        return view('commerce::commerce.create_type_of_sale');
    }

    // ประเภทการขาย
    public function store_create_type_of_sale(Request $request)
    {
        $request->validate([
            'type' => 'required|in:pawn,counter,service',
        ]);

        if ($request->filled('sale_id')) {
            $sale = Sale::findOrFail($request->sale_id);
        } else {
            $sale = new Sale();
        }

        $sale->type_serve = $request->type;

        if ($request->filled('member_id')) {
            $sale->member_id = $request->member_id;
        }

        $sale->save();

        return redirect()
            ->route('commerce.create_pawning', ['id' => $sale->id])
            ->with('success', 'เลือกประเภทรายการเรียบร้อย');
    }

    // ฟอร์มแสดงการจำนำ
    public function create_pawning($id)
    {
        $sale = Sale::where('id', $id)->first();
        return view('commerce::commerce.create_pawning', compact('id'));
    }

    public function store_pawning(Request $request, $id)
    {
        $request->validate(
            [
                'fullname' => 'required|string',
                'phone' => 'required|numeric',
                'tax_number' => 'nullable|numeric',
                'type_serve' => 'required',
                'type_category' => 'required',
                'brand' => 'required|string',
                'model' => 'required|string',
                'price' => 'required|numeric',
                'serial_number' => 'nullable|string',
                'description' => 'nullable|string',
            ],
            [
                'fullname.required' => 'กรุณากรอกชื่อลูกค้า',
                'phone.required' => 'กรุณากรอกเบอร์โทรศัพท์',
                'phone.numeric' => 'เบอร์โทรศัพท์ต้องเป็นตัวเลข',
                'type_serve.required' => 'กรุณาเลือกประเภทการให้บริการ',
                'type_category.required' => 'กรุณาเลือกหมวดหมู่สินค้า',
                'brand.required' => 'กรุณาเลือกยี่ห้อสินค้า',
                'model.required' => 'กรุณากรอกรุ่นสินค้า',
                'price.required' => 'กรุณากรอกราคาสินค้า',
                'price.numeric' => 'ราคาสินค้าต้องเป็นตัวเลข',
            ]
        );

        DB::transaction(function () use ($request, $id) {
            $sale = Sale::findOrFail($id); // ❗ ถ้าไม่เจอ id จะ error ทันที
            $sale->fullname = $request->fullname;
            $sale->phone = $request->phone;
            $sale->tax_number = $request->tax_number;
            $sale->type_serve = $request->type_serve;
            $sale->type_category = $request->type_category;
            $sale->brand = $request->brand;
            $sale->model = $request->model;
            $sale->price = $request->price;
            $sale->serial_number = $request->serial_number;
            $sale->description = $request->description;
            $sale->save();
        });

        return redirect()
            ->route('commerce.crate_search')
            ->with('success', 'แก้ไขรายการจำนำเรียบร้อยแล้ว', $id);
    }

    public function index()
    {
        $sale = DB::table('sale')->all();
        return view('commerce::index');
    }

    public function report_pawning($id)
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
}

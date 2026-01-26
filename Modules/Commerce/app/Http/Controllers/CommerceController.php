<?php

namespace Modules\Commerce\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use DB;
use Modules\Commerce\Models\Sale;

class CommerceController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $sale = DB::table('sale')->all();
        return view('commerce::index');
    }

    public function create_search()
    {
        return view('commerce::commerce.create_search');
    }

    // ค้นหาประวัติการขาย
    public function store_create_search(request $request)
    {
        $phone = $request->phone;
        $serial = $request->serial;

        $sales = Sale::where('serial_number', $serial)
            ->orWhere('phone', $phone)
            ->get();
        if ($sales->isEmpty()) {
            return redirect()->route('commerce.create_type_of_sale')->with('error', 'ไม่พบข้อมูลที่ค้นหา');
        } else {
            return view('commerce::commerce.customer', compact('sales'));
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

    // เพิ่มประเภทการขาย
    public function store_create_type_of_sale(Request $request)
    {
        $request->validate([
            'type' => 'required|in:pawn,counter,service',
        ]);

        $type_serve = $request->type;

        $sale = new Sale();
        // dd($type_serve);
        $sale->type_serve = $type_serve;
        $sale->save();

        return redirect()->route('commerce.create_pawning', ['id' => $sale->id])
            ->with('success', 'บันทึกรายการจำนำเรียบร้อย');
    }

    // ฟอร์มแสดงการจำนำ
    public function create_pawning($id)
    {
        $sale = Sale::where('id', $id)->first();
        // dd($id);
        return view('commerce::commerce.create_pawning', compact('id'));
    }

    public function store_create_pawning(Request $request)
    {
        // dd($request->all());
        $request->validate(
            [
                'members' => 'required|string',
                'phone' => 'required|string',
                'tax_number' => 'nullable|string',
                'type_serve' => 'required',
                'type_category' => 'required',
                'brand' => 'required',
                'model' => 'required',
                'price' => 'required|numeric',
            ],
            [
                'members.required' => 'กรุณากรอกชื่อลูกค้า',
                'phone.required' => 'กรุณากรอกเบอร์โทรศัพท์',
                'type_serve.required' => 'กรุณาเลือกประเภทการให้บริการ',
                'type_category.required' => 'กรุณาเลือกหมวดหมู่สินค้า',
                'brand.required' => 'กรุณากรอกยี่ห้อสินค้า',
                'model.required' => 'กรุณากรอกรุ่นสินค้า',
                'price.required' => 'กรุณากรอกราคาสินค้า',
                'price.numeric' => 'ราคาสินค้าต้องเป็นตัวเลขเท่านั้น',
            ]
        );

        DB::transaction(function () use ($request) {
            Sale::create([
                'members' => $request->members,
                'phone' => $request->phone,
                'tax_number' => $request->tax_number,
                'type_serve' => $request->type_serve,
                'type_category' => $request->type_category,
                'brand' => $request->brand,
                'model' => $request->model,
                'serial_number' => $request->serial_number,
                'description' => $request->description,
                'price' => $request->price,
                'others' => $request->others,
            ]);
        });

        return redirect()
            ->route('commerce.report_pawming')
            ->with('success', 'บันทึกรายการจำนำเรียบร้อย');
    }


    public function report_pawning_confirm()
    {
        return view('commerce::commerce.report_pawning_confirm');
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

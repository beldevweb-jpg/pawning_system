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

    // ฟอร์มแสดงการจำนำ
    public function create_pawning()
    {
        return view('commerce::commerce.create_pawning',);
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

        return redirect()->route('commerce.create_pawning')
            ->with('success', 'บันทึกรายการจำนำเรียบร้อย');
    }

    // เพิ่มข้อมูลจำนำ
    public function store_crate_pawming(Request $request)
    {
        $request->validate([

            'sale' => 'required',
            'service' => 'required',
            'phone' => 'nullable|string',
            'tax_number' => 'nullable|string',
            'brand' => 'required',
            'model' => 'required',
        ]);

        DB::transaction(function () use ($request) {
            $sale = new Sale();
            $sale->members = $request->members;
            $sale->phone = $request->phone;
            $sale->tax_number = $request->tax_number;
            $sale->type_serve = $request->type_serve;
            $sale->type_category = $request->type_category;
            $sale->brand = $request->brand;
            $sale->model = $request->model;
            $sale->serial_number = $request->serial_number;
            $sale->description = $request->description;
            $sale->price = $request->price;
            $sale->others = $request->others;
            $sale->save();
        });

        return redirect()->route('commerce.report_pawming')
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

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
                    ->withErrors('error', 'ไม่พบประวัติเครื่อง');
            }

            return view('commerce::commerce.create_type_of_sale', compact('sale'));
        }

        // ค้นหาด้วย tax_number
        if ($tax_number) {
            $member = Member::where('tax_number', $tax_number)->first();

            if (!$member) {
                return redirect()
                    ->route('commerce.create_type_of_sale')
                    ->withErrors(['ไม่พบประวัติลูกค้า']);
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
        return view('commerce::commerce.create_pawning', compact('id'));
    }

    public function update_pawning(Request $request, $id)
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

            $sale->update([
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
            ->route('commerce.crate_search')
            ->with('success', 'แก้ไขรายการจำนำเรียบร้อยแล้ว');
    }

    public function index()
    {
        $sale = DB::table('sale')->all();
        return view('commerce::index');
    }

    // public function report_pawning_confirm()
    // {
    //     return view('commerce::commerce.report_pawning_confirm');
    // }

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

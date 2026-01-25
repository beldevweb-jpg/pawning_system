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

    /**
     * Show the form for creating a new resource.
     */
    public function create_type_of_sale(Request $request)
    {
        $request->validate([
            'service' => 'required|string',
            'pawning' => 'nullable|string',
            'sale'    => 'nullable|string',
        ]);

        DB::transaction(function () use ($request) {
            Sale::create([
                'service' => $request->service,
                'pawning' => $request->pawning,
                'sale'    => $request->sale,
            ]);
        });

        return redirect()->route('commerce.create_pawning');
    }

    public function crate_pawming(Request $request)
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
                // 'user' => auth()->id(),
            ]);
        });

        return redirect()->route('commerce.report_pawming')
            ->with('success', 'บันทึกรายการจำนำเรียบร้อย');
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

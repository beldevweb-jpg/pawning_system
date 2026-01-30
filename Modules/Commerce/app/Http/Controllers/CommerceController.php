<?php

namespace Modules\Commerce\Http\Controllers;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Modules\Commerce\Models\Sale;
use Modules\Members\Models\Member;
use Illuminate\Support\Str;

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

            return redirect()->route('commerce.customer', $sale->id);
        }

        if (!empty($tax_number)) {
            $member = Member::where('tax_number', 'LIKE', "%{$tax_number}%")->first();

            if (!$member) {
                return redirect()
                    ->route('commerce.create_member')
                    ->withErrors(['error' => 'ไม่พบประวัติลูกค้า']);
            }

            return redirect()->route('commerce.customer', $member->member_id);
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

    public function store_create_type_of_sale(Request $request, $id)
    {
        // validate
        $request->validate([
            'type' => 'required|string'
        ]);

        // หา member จาก id ที่มาจาก route
        $member = Member::findOrFail($id);

        $sale = new Sale();
        $sale->type_serve = $request->type;
        $sale->member_id = $member->member_id;
        $sale->save();

        $member->sale_id = $sale->id;
        $member->save();

        return redirect()->route('commerce.create_pawning', [
            'id' => $sale->id
        ]);
    }


    // ฟอร์มแสดงการจำนำ
    public function create_pawning($id)
    {
        $sale = Sale::findOrFail($id);
        $member = Member::findOrFail($sale->member_id);

        return view('commerce::commerce.create_pawning', compact('sale', 'member'));
    }

    public function store_pawning(Request $request)
    {
        $request->validate([
            'sale_id' => 'required|exists:sales,id',
            'subcategories' => 'required',
            'type_category' => 'required',
            'brand' => 'required|string',
            'model' => 'required|string',
            'price' => 'required|numeric',
            'type_price' => 'required|string',
            'lock_pass' => 'required|string',
            'serial_number' => 'required|string',
            'note' => 'nullable|string',
        ]);

        $member_id = null;

        DB::transaction(function () use ($request, &$member_id) {

            $sale = Sale::findOrFail($request->sale_id);
            $member_id = $sale->member_id;

            $updateData = [
                'brand' => $request->brand,
                'model' => $request->model,
                'serial_number' => $request->serial_number,
                'note' => $request->note,
                'type_price' => $request->type_price,
                'lock_pass' => $request->lock_pass,
                'price' => $request->price,
                'type_category' => $request->type_category,
                'subcategories' => $request->subcategories,
            ];

            if ($request->filled('type_serve')) {
                $updateData['type_serve'] = $request->type_serve;
            }

            $sale->update($updateData);
        });

        return redirect()->route('commerce.create_search', [
            'member_id' => $member_id
        ]);
    }

    public function customer($id)
    {
        $member = Member::with('sales_r')
            ->where('member_id', $id)
            ->get();

        return view('commerce::commerce.customer', compact('member'));
    }

    // public function customer(Request $request)
    // {
    //     $keyword = $request->keyword;

    //     $sales = Sale::with('member_r')
    //         ->where(function ($q) use ($keyword) {

    //             $q->where('serial_number', 'like', "%{$keyword}%")

    //                 ->orWhereHas('member_r', function ($q2) use ($keyword) {
    //                     $q2->where('tax_number', 'like', "%{$keyword}%");
    //                 });
    //         })
    //         ->get();

    //     return view('commerce::commerce.customer', compact('sales'));
    // }

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

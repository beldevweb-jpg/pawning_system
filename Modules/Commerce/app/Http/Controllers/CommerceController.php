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
                    ->route('commerce.create_member')
                    ->withErrors(['error', 'ไม่พบประวัติลูกค้า']);
            }

            return view('commerce::commerce.create_type_of_sale', compact('member'));
        }
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
            ->route('commerce.create_type_of_sale');
    }

    public function create_type_of_sale()
    {
        return view('commerce::commerce.create_type_of_sale');
    }


    public function store_create_type_of_sale(Request $request)
    {
        $request->validate([
            'type' => 'required|in:pawn,counter,service',
            'member_id' => 'required|exists:members,id',
        ]);

        $sale = Sale::create([
            'subcategories' => $request->type,
            'member_id' => $request->member_id,
        ]);

        return redirect()
            ->route('commerce.create_pawning', [
                'sale_id' => $sale->id
            ]);
    }

    // ฟอร์มแสดงการจำนำ
    public function create_pawning(Request $request)
    {
        $sale = Sale::with('member')->findOrFail($request->sale_id);

        return view(
            'commerce::commerce.create_pawning',
            [
                'sale' => $sale,
                'member' => $sale->member
            ]
        );
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
            'serial_number' => 'required|string',
            'note' => 'nullable|string',
        ]);

        DB::transaction(function () use ($request) {
            $sale = Sale::findOrFail($request->sale_id);

            $sale->update([
                'brand' => $request->brand,
                'model' => $request->model,
                'serial_number' => $request->serial_number,
                'note' => $request->note,
                'price' => $request->price,
                'type_category' => $request->type_category,
                'subcategories' => $request->subcategories,
            ]);
        });

        return redirect()
            ->route('commerce.create_search')
            ->with('success', 'รายการขายเรียบร้อยแล้ว');
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

<?php

namespace Modules\Commerce\Exports;

use Modules\Commerce\Models\expenses;

use Modules\Commerce\Models\Settings;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class SalefontExport implements FromCollection, WithHeadings
{
    protected $request;

    public function __construct($request)
    {
        $this->request = $request;
    }

    public function collection()
    {
        $query = expenses::query();

        // status
        if ($this->request->filled('status')) {

            $query->where(
                'status',
                $this->request->status
            );
        }

        // search
        if ($this->request->filled('search')) {

            $search = $this->request->search;

            $query->where(function ($q) use ($search) {

                $q->where('brand', 'like', "%{$search}%")
                    ->orWhere('model', 'like', "%{$search}%")
                    ->orWhere('serial_number', 'like', "%{$search}%")
                    ->orWhere('running_no', 'like', "%{$search}%")
                    ->orWhere('note', 'like', "%{$search}%");
            });
        }

        // วันที่
        if (
            $this->request->filled('start_date') &&
            $this->request->filled('end_date')
        ) {

            $query->whereBetween('created_at', [
                $this->request->start_date . ' 00:00:00',
                $this->request->end_date . ' 23:59:59'
            ]);
        }

        $expenses = $query->latest()->get();

        return $expenses->map(function ($item, $index) {

            return [

                'no' => $index + 1,

                'product' => $item->product,

                'transfer' => $item->transfer,

                'cash' => $item->cash,

                'created_at' => $item->created_at
                    ? $item->created_at->format('d/m/Y H:i')
                    : '',

                'note' => $item->note,

            ];
        });
    }

    public function headings(): array
    {
        return [

            [
                Settings::first()?->company_name
            ],

            [
                'รายงานรายการจำ'
            ],

            [
                ''
            ],

            [
                'ลำดับ',
                'รายการ',
                'เงินสด',
                'เงินโอน',
                'วันที่',
                'หมายเหตุ',
            ]
        ];
    }
}

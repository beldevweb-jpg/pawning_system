<?php

namespace Modules\Commerce\Exports\Sheets;

use Modules\Commerce\Models\expenses;
use Modules\Commerce\Models\Settings;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithTitle;

class SaleSheetExport implements
    FromCollection,
    WithHeadings,
    WithTitle
{
    protected $request;

    public function __construct($request)
    {
        $this->request = $request;
    }

    public function collection()
    {
        return Expenses::latest()
            ->get()
            ->map(function ($item, $index) {

                return [

                    'ลำดับ' => $index + 1,

                    'รายการร้านนอก' =>
                    $item->product ?? '-',

                    'ขายเครื่อง' =>
                    $item->type == 'receive'
                        ? (($item->cash ?? 0)
                            + ($item->transfer ?? 0))
                        : 0,

                    'ซ่อม' => '-',

                    'อื่น' => '-',

                    'รายจ่าย' =>
                    $item->type == 'pay'
                        ? (($item->cash ?? 0)
                            + ($item->transfer ?? 0))
                        : 0,

                    'หมายเหตุ' =>
                    $item->note ?? '-',
                ];
            });
    }

    public function headings(): array
    {
        return [

            [Settings::value('company_name')],

            ['วันที่ ' . now()->format('d/m/Y')],

            [''],

            [
                'ลำดับ',
                'รายการร้านนอก',
                'ขายเครื่อง',
                'ซ่อม',
                'อื่น',
                'รายจ่าย',
                'หมายเหตุ',
            ]
        ];
    }

    public function title(): string
    {
        return 'รายการขาย';
    }
}

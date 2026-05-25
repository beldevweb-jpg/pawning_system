<?php

namespace Modules\Commerce\Exports\Sheets;

use Carbon\Carbon;

use Modules\Commerce\Models\Sale;
use Modules\Commerce\Models\Settings;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithTitle;

class TaiSheetExport implements
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
        return Sale::where('status', 'tai')
            ->latest()
            ->get()
            ->map(function ($item) {

                return [

                    'รหัส' =>
                        $item->running_no,

                    'เครื่อง' =>
                        ($item->brand ?? '') .
                        ' ' .
                        ($item->model ?? ''),

                    'ต้น' =>
                        ($item->cash ?? 0)
                        + ($item->transfer ?? 0),

                    'ดอก' =>
                        $item->dok ?? 0,

                    'วันที่วาง' =>
                        !empty($item->created_at)
                        ? Carbon::parse($item->created_at)
                            ->format('d/m/Y')
                        : '-',

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
                'รหัส',
                'เครื่อง',
                'ต้น',
                'ดอก',
                'วันที่วาง',
                'หมายเหตุ',
            ]
        ];
    }

    public function title(): string
    {
        return 'ไถ่';
    }
}
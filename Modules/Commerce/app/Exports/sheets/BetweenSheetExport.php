<?php

namespace Modules\Commerce\Exports\Sheets;

use Carbon\Carbon;

use Modules\Commerce\Models\Sale;
use Modules\Commerce\Models\Settings;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithTitle;

class BetweenSheetExport implements
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
        return Sale::where('status', 'between')
            ->latest()
            ->get()
            ->map(function ($item, $index) {

                return [

                    'ลำดับ' => $index + 1,

                    'รหัส' =>
                        $item->running_no,

                    'เครื่อง' =>
                        ($item->brand ?? '') .
                        ' ' .
                        ($item->model ?? ''),

                    'สี/ตำหนิ' =>
                        $item->note ?? '-',

                    'ต้น' =>
                        ($item->cash ?? 0)
                        + ($item->transfer ?? 0),

                    'หมายเหตุ' =>
                        $item->note ?? '-',

                    'วันไถ่' =>
                        !empty($item->appointment_date)
                        ? Carbon::parse($item->appointment_date)
                            ->format('d/m/Y')
                        : '-',

                    'ดอก' =>
                        $item->dok ?? 0,
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
                'รหัส',
                'เครื่อง',
                'สี/ตำหนิ',
                'ต้น',
                'หมายเหตุ',
                'วันไถ่',
                'ดอก',
            ]
        ];
    }

    public function title(): string
    {
        return 'วาง';
    }
}
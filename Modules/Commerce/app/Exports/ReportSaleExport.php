<?php

namespace Modules\Commerce\Exports;

use Carbon\Carbon;
use Modules\Commerce\Models\Sale;
use Modules\Commerce\Models\expenses;
use Modules\Commerce\Models\Settings;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithTitle;

class ReportSaleExport implements
    FromCollection,
    WithHeadings,
    WithTitle
{
    protected $request;

    protected $title;

    public function __construct($request, $title = 'รายงาน')
    {
        $this->request = $request;
        $this->title   = $title;
    }

    public function collection()
    {

        $expenses = expenses::query();

        $settings = Settings::query();

        if ($this->request->filled('status')) {

            // $expenses->where('status' $settings
        }

        $status = $this->request->status;

        $query = Sale::query()
            ->with([
                'user_r:user_id,name',
                'member_r:member_id,fullname'
            ]);



        /*
        |--------------------------------------------------------------------------
        | STATUS
        |--------------------------------------------------------------------------
        */

        if (!empty($status)) {

            $query->where('status', $status);
        }

        $sales = $query
            ->latest()
            ->get();

        /*
        |--------------------------------------------------------------------------
        | รายการขาย
        |--------------------------------------------------------------------------
        */

        if ($this->title == 'รายการขาย') {

            return $sales->map(function ($item, $index) {

                return [

                    'ลำดับ' => $index + 1,

                    'รายการร้านนอก' =>
                    $item->other_type ?? '-',

                    'ขายเครื่อง' => ($item->cash ?? 0)
                        + ($item->transfer ?? 0),

                    'ซ่อม' => '-',

                    'อื่น' => '-',

                    'รายจ่าย' => '-',

                    'หมายเหตุ' =>
                    $item->note ?? '-',
                ];
            });
        }

        /*
        |--------------------------------------------------------------------------
        | วาง
        |--------------------------------------------------------------------------
        */

        if ($this->title == 'วาง') {

            return $sales->map(function ($item, $index) {

                return [

                    'ลำดับ' => $index + 1,

                    'รหัส' =>
                    $item->running_no ?? '-',

                    'เครื่อง' =>
                    $item->brand || $item->model
                        ? ($item->brand ?? '-') . ' ' . ($item->model ?? '')
                        : $item->other_type ?? '-',

                    'สี/ตำหนิ' =>
                    $item->note ?? '-',

                    'ต้น' => ($item->cash ?? 0)
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

        /*
        |--------------------------------------------------------------------------
        | ไถ่
        |--------------------------------------------------------------------------
        */

        if ($this->title == 'ไถ่') {

            return $sales->map(function ($item, $index) {

                return [

                    'รหัส' =>
                    $item->running_no ?? '-',

                    'เครื่อง' =>
                    $item->brand || $item->model
                        ? ($item->brand ?? '-') . ' ' . ($item->model ?? '')
                        : $item->other_type ?? '-',

                    'ต้น' => ($item->cash ?? 0)
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

        return collect([]);
    }

    public function headings(): array
    {
        /*
        |--------------------------------------------------------------------------
        | รายการขาย
        |--------------------------------------------------------------------------
        */

        if ($this->title == 'รายการขาย') {

            return [

                [
                    Settings::value('company_name')
                ],

                [
                    'วันที่ ' . now()->format('d/m/Y')
                ],

                [
                    ''
                ],

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

        /*
        |--------------------------------------------------------------------------
        | วาง
        |--------------------------------------------------------------------------
        */

        if ($this->title == 'วาง') {

            return [

                [
                    Settings::value('company_name')
                ],

                [
                    'วันที่ ' . now()->format('d/m/Y')
                ],

                [
                    ''
                ],

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

        /*
        |--------------------------------------------------------------------------
        | ไถ่
        |--------------------------------------------------------------------------
        */

        if ($this->title == 'ไถ่') {

            return [

                [
                    Settings::value('company_name')
                ],

                [
                    'วันที่ ' . now()->format('d/m/Y')
                ],

                [
                    ''
                ],

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

        return [];
    }

    public function title(): string
    {
        return $this->title;
    }
}

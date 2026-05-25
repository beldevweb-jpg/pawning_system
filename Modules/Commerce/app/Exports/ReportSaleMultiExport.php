<?php

namespace Modules\Commerce\Exports;

use Modules\Commerce\Exports\Sheets\SaleSheetExport;
use Modules\Commerce\Exports\Sheets\BetweenSheetExport;
use Modules\Commerce\Exports\Sheets\TaiSheetExport;

use Maatwebsite\Excel\Concerns\WithMultipleSheets;

class ReportSaleMultiExport implements WithMultipleSheets
{
    protected $request;

    public function __construct($request)
    {
        $this->request = $request;
    }

    public function sheets(): array
    {
        return [

            new SaleSheetExport($this->request),

            new BetweenSheetExport($this->request),

            new TaiSheetExport($this->request),

        ];
    }
}

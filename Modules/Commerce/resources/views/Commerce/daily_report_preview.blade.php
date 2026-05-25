<!DOCTYPE html>
<html lang="th">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>ยอดขาย</title>

    <!-- Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">

    <!-- CSS -->
    <link rel="stylesheet" href="{{ asset('css/daily-report.css') }}">
    <link rel="stylesheet" href="{{ asset('css/table.css') }}">
</head>

<body>

    <div class="container py-5">

        @if (session('success'))
            <div class="msg ok">
                <i class="fa fa-circle-check"></i>
                {{ session('success') }}
            </div>
        @endif

        @if (session('error'))
            <div class="msg err">
                <i class="fa fa-circle-exclamation"></i>
                {{ session('error') }}
            </div>
        @endif

        @if ($errors->any())
            <div class="msg err">
                @foreach ($errors->all() as $error)
                    <div>- {{ $error }}</div>
                @endforeach
            </div>
        @endif
        @if (auth()->user()->role_id == 1)
            <nav>
                <a href="{{ route('commerce.report_salefront') }}">
                    {{ request()->routeIs('commerce.report_salefront') ? '► ' : '' }}รายการรับจ่าย
                </a> |
                <a href="{{ route('commerce.sale_list') }}">
                    {{ request()->routeIs('commerce.sale_list') ? '► ' : '' }}รายการจำนำ
                </a>
            </nav>
        @endif
        <hr>
        @if (auth()->user()->role_id == 3)
            <nav>
                <a href="{{ route('commerce.report_salefront') }}">
                    {{ request()->routeIs('commerce.report_salefront') ? '► ' : '' }}รายการรับจ่าย
                </a> |

                <a href="{{ route('commerce.sale_list') }}">
                    {{ request()->routeIs('commerce.sale_list') ? '► ' : '' }}รายการจำนำ
                </a> |

                <a href="{{ route('user.index') }}">
                    {{ request()->routeIs('user.*') ? '► ' : '' }}จัดการพนักงาน
                </a> |

                <a href="{{ route('commerce.show_member') }}">
                    {{ request()->routeIs('commerce.show_member') ? '► ' : '' }}รายชื่อลูกค้า

                </a>|

                <a href="{{ route('commerce.manage_dok') }}">
                    {{ request()->routeIs('commerce.manage_dok') ? '► ' : '' }}จัดการคอกเบี้ยต่อเดือน

                </a>|

                <a href="{{ route('commerce.settings') }}">
                    {{ request()->routeIs('commerce.settings') ? '► ' : '' }}ตั้งค่าอื่นๆ
                </a> |
            </nav>
            <hr>
        @endif
        <div class="card report-card shadow-lg">

            <div class="card-header-custom d-flex justify-content-between align-items-center flex-wrap gap-3">
                <div>
                    <h4>
                        <i class="fa-solid fa-file-invoice-dollar"></i>
                        รายงานสรุปยอดประจำวันที่ {{ $report->report_date }}
                    </h4>

                    <div class="mt-2 text-light opacity-75">
                        ผู้ปิดยอด:
                        <strong>{{ $report->user->name ?? 'System' }}</strong>
                        |
                        เวลา:
                        {{ $report->created_at->format('H:i') }} น.
                    </div>
                </div>

                <a href="{{ route('commerce.daily_report_exportpdf', $report->id) }}"
                    class="btn btn-danger btn-custom">
                    <i class="fa fa-file-pdf"></i>
                    ดาวน์โหลด PDF
                </a>
            </div>

            <div class="card-body p-4">
                <div class="row g-4">

                    <!-- Income -->
                    <div class="col-lg-6">
                        <div class="summary-box">
                            <div class="summary-title income-title">
                                <i class="fa-solid fa-arrow-trend-up"></i>
                                รายรับ
                            </div>

                            <div class="table-responsive">
                                <table class="table table-hover align-middle">
                                    <tbody>
                                        @forelse ($sales as $sale)
                                            <tr>
                                                <td>
                                                    <strong>รายการขาย #{{ $sale->id }}</strong>
                                                </td>
                                                <td class="text-end amount-positive">
                                                    +{{ number_format($receive, 2) }}
                                                </td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="2" class="text-center text-muted">
                                                    ไม่มีข้อมูลรายรับ
                                                </td>
                                            </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>

                    <!-- Expense -->
                    <div class="col-lg-6">
                        <div class="summary-box">
                            <div class="summary-title expense-title">
                                <i class="fa-solid fa-arrow-trend-down"></i>
                                รายจ่าย
                            </div>

                            <div class="table-responsive">
                                <table class="table table-hover align-middle">
                                    <tbody>
                                        @forelse ($expenses as $expense)
                                            <tr>
                                                <td>
                                                    <strong>{{ $expense->title }}</strong>
                                                </td>
                                                <td class="text-end amount-negative">
                                                    -{{ number_format($pay, 2) }}
                                                </td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="2" class="text-center text-muted">
                                                    ไม่มีข้อมูลรายจ่าย
                                                </td>
                                            </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>

                </div>

                <div class="total-section">

                    <div class="total-row">
                        <span>
                            <i class="fa-solid fa-money-bill-wave text-success"></i>
                            ยอดขายรวม
                        </span>
                        <span class="fw-bold">
                            {{ number_format($report->total_sales, 2) }} บาท
                        </span>
                    </div>
                    <div class="total-row text-danger">
                        <span>
                            <i class="fa-solid fa-receipt"></i>
                            รายจ่ายรวม
                        </span>
                        <span class="fw-bold">
                            -{{ number_format($report->total_expenses, 2) }} บาท
                        </span>
                    </div>

                    <hr>

                    <div class="d-flex justify-content-between align-items-center mt-3">
                        <span class="h4 fw-bold mb-0">
                            ยอดสุทธิคงเหลือ
                        </span>

                        <span class="net-total">
                            {{ number_format($report->net_amount, 2) }}
                            <small class="fs-5">บาท</small>
                        </span>
                    </div>

                </div>

            </div>
        </div>
    </div>

</body>

</html>

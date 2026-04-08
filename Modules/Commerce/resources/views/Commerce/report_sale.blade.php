<!DOCTYPE html>
<html lang="th">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>แสดงรายรับรายจ่าย</title>
    <link rel="stylesheet" href="{{ asset('css/table.css') }}">
    <style>
        * {
            box-sizing: border-box;
            font-family: sans-serif;
        }

        body {
            margin: 0;
            background: #f5f6fa;
        }

        /* HEADER */
        .header {
            display: flex;
            justify-content: space-between;
            padding: 12px 16px;
        }

        /* CONTAINER */
        .container {
            max-width: 1500px;
            margin: auto;
            padding: 16px;
        }

        /* NAV */
        nav {
            display: flex;
            flex-wrap: wrap;
            gap: 8px;
            font-size: 14px;
        }

        /* TITLE */
        h1 {
            font-size: 20px;
        }

        /* SUMMARY */
        .summary {
            display: flex;
            flex-direction: column;
            gap: 12px;
            margin-bottom: 20px;
        }

        .box {
            padding: 16px;
            border-radius: 12px;
        }

        .income {
            background: #dff9fb;
        }

        .expense {
            background: #f6e58d;
        }

        /* FILTER */
        .filter {
            display: flex;
            flex-direction: column;
            gap: 10px;
            margin-bottom: 20px;
        }

        .filter input,
        .filter button {
            height: 40px;
            font-size: 14px;
        }

        /* TABLE */
        .table-wrapper {
            overflow-x: auto;
        }

        table {
            width: 100%;
            min-width: 700px;
            background: white;
            border-radius: 12px;
            border-collapse: collapse;
        }

        th,
        td {
            padding: 10px;
            font-size: 13px;
            text-align: center;
            border-bottom: 1px solid #eee;
            white-space: nowrap;
        }

        thead {
            background: #2f3640;
            color: white;
        }

        tfoot td {
            font-weight: bold;
        }

        /* DESKTOP */
        @media (min-width: 768px) {

            .summary {
                flex-direction: row;
            }

            .filter {
                flex-direction: row;
                align-items: center;
            }

            h1 {
                font-size: 26px;
            }

            th,
            td {
                font-size: 14px;
            }
        }
    </style>
</head>

<body>
    <!-- HEADER -->
    <header class="header">
        <div>👤 {{ auth()->user()->name ?? '' }}</div>

        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button style="background:red;color:white;border:none;padding:8px 12px;border-radius:6px">
                ออกจากระบบ
            </button>
        </form>
    </header>

    <div class="container">
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

        <h1>📊 รายรับรายจ่ายขายหน้าร้าน</h1>

        <!-- SUMMARY -->
        <div class="summary">
            <div class="box income">
                <h3>💰 รายรับ</h3>
                <h2 style="color:green">
                    {{ number_format($totalReceive, 2) }} บาท
                </h2>
            </div>

            <div class="box expense">
                <h3>💸 รายจ่าย</h3>
                <h2 style="color:red">
                    {{ number_format($totalPay, 2) }} บาท
                </h2>
            </div>
        </div>

        <!-- FILTER -->
        <form method="GET" class="filter">
            <input type="date" name="start_date" value="{{ request('start_date') }}">
            <input type="date" name="end_date" value="{{ request('end_date') }}">

            <button type="submit">ค้นหา</button>

            <a href="{{ url()->current() }}">
                <button type="button">รีเซ็ต</button>
            </a>
        </form>

        <!-- TABLE -->
        <div class="table-wrapper">

            <a href="{{ route('commerce.report_sale_pdf', request()->query()) }}" target="_blank"
                style="display:inline-block;margin-bottom:10px;background:black;color:white;padding:8px 12px;border-radius:6px;text-decoration:none">
                Export PDF
            </a>

            <table>
                <thead>
                    <tr>
                        <th>รายการ</th>
                        <th>เงินสด</th>
                        <th>โอน</th>
                        <th>ประเภท</th>
                        <th>วันที่</th>
                        <th>ผู้บันทึก</th>
                        <th>เหตุแก้ดอก</th>
                        <th>หมายเหตุ</th>
                    </tr>
                </thead>

                <tbody>
                    @foreach ($expenses as $e)
                        <tr>
                            <td>{{ $e->product }}</td>
                            <td>{{ number_format($e->cash, 2) }}</td>
                            <td>{{ number_format($e->transfer, 2) }}</td>

                            <td>
                                @if ($e->type == 'receive')
                                    <span style="color:green;font-weight:bold">รายรับ</span>
                                @else
                                    <span style="color:red;font-weight:bold">รายจ่าย</span>
                                @endif
                            </td>
                            <td>{{ $e->created_at->format('d/m/Y') }}</td>
                            <td>{{ $e->user_r->name ?? '-' }}</td>
                            <td>{{ $e->sale_r->note_dok ?? '-' }}</td>
                            <td>{{ $e->note ?? '-' }}</td>
                        </tr>
                    @endforeach
                </tbody>

                <tfoot>
                    <tr>
                        <td colspan="2">
                            💰 รวมรายรับ <br>
                            <span style="color:green;font-size:18px">
                                {{ number_format($totalReceive, 2) }} บาท
                            </span>
                        </td>

                        <td colspan="2">
                            💸 รวมรายจ่าย <br>
                            <span style="color:red;font-size:18px">
                                {{ number_format($totalPay, 2) }} บาท
                            </span>
                        </td>

                        <td colspan="2">
                            🧾 ยอดคงเหลือ <br>
                            <span style="font-size:20px;color:{{ $balance >= 0 ? 'green' : 'red' }}">
                                {{ number_format($balance, 2) }} บาท
                            </span>
                        </td>
                    </tr>
                </tfoot>
            </table>

        </div>

    </div>

</body>

</html>

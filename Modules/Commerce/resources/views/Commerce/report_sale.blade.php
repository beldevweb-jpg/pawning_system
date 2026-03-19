<!DOCTYPE html>
<html lang="th">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>แสดงรายรับรายจ่าย</title>
</head>

<body style="font-family: sans-serif; background:#f5f6fa">

    <header style="display:flex;justify-content:space-between;padding:15px;">
        <div>
            👤 {{ auth()->user()->name ?? '' }}
        </div>

        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button style="background:red;color:white;border:none;padding:8px 12px;border-radius:6px">
                ออกจากระบบ
            </button>
        </form>
    </header>

    <div style="max-width:1500px;margin:auto">
        @if (auth()->user()->role_id == 3)
            <nav>
                <a href="{{ route('commerce.report_sellfront') }}">
                    {{ request()->routeIs('commerce.report_sellfront') ? '► ' : '' }}รายการรับจ่าย
                </a> |

                <a href="{{ route('commerce.sale_list') }}">
                    {{ request()->routeIs('commerce.sale_list') ? '► ' : '' }}รายการจำนำ
                </a> |

                <a href="{{ route('user.index') }}">
                    {{ request()->routeIs('user.*') ? '► ' : '' }}จัดการพนักงาน
                </a> |

                <a href="{{ route('commerce.show_member') }}">
                    {{ request()->routeIs('commerce.show_member') ? '► ' : '' }}รายชื่อลูกค้า
                </a>
            </nav>
            <hr>
        @endif
        <h1>📊 รายรับรายจ่ายขายหน้าร้าน</h1>
        @if (session('success'))
            <div class="msg ok">
                {{ session('success') }}
            </div>
        @endif

        @if ($errors->any())
            <div class="msg err">
                @foreach ($errors->all() as $error)
                    <div>- {{ $error }}</div>
                @endforeach
            </div>
        @endif
        <!-- summary -->
        <div style="display:flex;gap:20px;margin-bottom:20px">

            <div style="flex:1;background:#dff9fb;padding:20px;border-radius:12px">
                <h3>💰 รายรับ</h3>
                <h2 style="color:green">
                    {{ number_format($totalReceive, 2) }} บาท
                </h2>
            </div>

            <div style="flex:1;background:#f6e58d;padding:20px;border-radius:12px">
                <h3>💸 รายจ่าย</h3>
                <h2 style="color:red">
                    {{ number_format($totalPay, 2) }} บาท
                </h2>
            </div>

        </div>
        <form method="GET" style="margin-bottom:20px">
            <input type="date" name="start_date" value="{{ request('start_date') }}">
            ถึง
            <input type="date" name="end_date" value="{{ request('end_date') }}">
            <button type="submit">ค้นหา</button>

            <a href="{{ url()->current() }}">
                <button type="button">
                    รีเซ็ต
                </button>
            </a>
        </form>

        <!-- table -->
        <table width="100%" cellpadding="12" style="background:white;border-radius:12px">
            <a href="{{ route('commerce.report_sale_pdf', request()->query()) }}" target="_blank"
                style="background:black;color:white;padding:8px 12px;border-radius:6px;text-decoration:none">
                Export PDF
            </a>
            <thead style="background:#2f3640;color:white">
                <tr>

                    <th>รายการ</th>
                    <th>เงินสด</th>
                    <th>โอน</th>
                    <th>ประเภท</th>
                    <th>วันที่</th>
                    <th>ผู้บันทึก</th>
                </tr>
            </thead>

            <tbody style="border-top:1px solid #eee">
                @foreach ($expenses as $e)
                    <tr style="border-bottom:1px solid #eee;text-align:center">
                        <td>{{ $e->product }}</td>

                        <td>{{ number_format($e->cash, 2) }}</td>
                        <td>{{ number_format($e->transfer, 2) }}</td>

                        <td>
                            @if ($e->type == 'receive')
                                <span style="color:green;font-weight:bold">
                                    รายรับ
                                </span>
                            @else
                                <span style="color:red;font-weight:bold">
                                    รายจ่าย
                                </span>
                            @endif
                        </td>
                        <td>{{ $e->created_at->format('d/m/Y') }}</td>
                        <td>{{ $e->user->name ?? '-' }}</td>
                    </tr>
                @endforeach
            </tbody>

            <tfoot style="font-weight:bold">
                <tr style="text-align:center">
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
                        <span
                            style="
                font-size:20px;
                color:{{ $balance >= 0 ? 'green' : 'red' }};
            ">
                            {{ number_format($balance, 2) }} บาท
                        </span>
                    </td>
                </tr>
            </tfoot>

        </table>
    </div>
</body>

</html>

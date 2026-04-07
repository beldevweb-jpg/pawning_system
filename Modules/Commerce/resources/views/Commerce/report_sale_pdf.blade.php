<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <style>
        @font-face {
            font-family: 'THSarabunNew';
            font-style: normal;
            font-weight: normal;
            src: url("{{ public_path('font/THSarabunNew.ttf') }}") format("truetype");
        }

        h2 {
            text-align: center;
            margin-bottom: 10px;
        }

        .summary {
            margin-bottom: 15px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        th,
        td {
            border: none;
            padding: 6px;
            text-align: center;
        }

        th {
            background: #eee;
        }

        .green {
            color: green;
            font-weight: bold;
        }

        .red {
            color: red;
            font-weight: bold;
        }
    </style>
</head>

<body>
    <h2>รายงานรายรับรายจ่ายขายหน้าร้าน</h2>
    @if ($start || $end)
        <div style="text-align:center;margin-bottom:10px;">
            ช่วงวันที่:
            {{ $start ?? '-' }} ถึง {{ $end ?? '-' }}
        </div>
    @endif
    <table>
        <thead>
            <tr>
                <th>รายการ</th>
                <th>เงินสด</th>
                <th>โอน</th>
                <th>ลูกค้า</th>
                <th>วันที่ทำรายการ</th>
                <th>ครบกำหนด</th>
                <th>วันที่</th>
                <th>ผู้บันทึก</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($expenses as $e)
                <tr>
                    <td>{{ $e->product }}</td>
                    <td>{{ number_format($e->cash, 2) }}</td>
                    <td>{{ number_format($e->transfer, 2) }}</td>
                    <td>
                        {{ $e->member_r->fullname ?? '-' }}
                    </td>
                    <td>{{ $e->created_at->format('d/m/Y') }}</td>
                    <td>{{ $e->user->name ?? '-' }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
    <br>
    <table>
        <tr>
            <td>
                รวมรายรับ<br>
                <span class="green">
                    {{ number_format($totalReceive, 2) }} บาท
                </span>
            </td>
            <td>
                รวมรายจ่าย<br>
                <span class="red">
                    {{ number_format($totalPay, 2) }} บาท
                </span>
            </td>
            <td>
                ยอดคงเหลือ<br>
                <span style="font-weight:bold;
                color:{{ $balance >= 0 ? 'green' : 'red' }}">
                    {{ number_format($balance, 2) }} บาท
                </span>
            </td>
        </tr>
    </table>
</body>

</html>

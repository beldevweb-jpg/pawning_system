<!DOCTYPE html>
<html lang="th">

<head>
    <meta charset="UTF-8">
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

    <h2>ประวัติการขาย</h2>

    <table>
        <thead>
            <tr>
                <th>ลำดับ</th>
                <th>เลขตั๋ว</th>
                <th>สินค้า</th>
                <th>พนักงาน</th>
                <th>ลูกค้า</th>
                <th>วันที่</th>
                <th>ครบกำหนด</th>
                <th>สถานะ</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($expenses as $i => $e)
                <tr>
                    <td>{{ $i + 1 }}</td>
                    <td>{{ $e->sale_r->running_no ?? '-' }}</td>
                    <td>{{ $e->product }}</td>
                    <td>{{ $e->sale_r->user_r->name ?? '-' }}</td>

                    <td>
                        {{ $e->sale_r->member_r->fullname ?? '-' }}
                    </td>
                    <td>{{ $e->created_at->format('d/m/Y') }}</td>
                    <td>{{ $e->sale_r?->appointment_date?->format('d/m/Y') ?? '-' }}</td>
                    <td>
                        {{ match ($e->sale_r->status) {
                            'between' => 'จำนำอยู่',
                            'foreclosed' => 'หลุด',
                            'problem' => 'มีปัญหา',
                            'closed' => 'ปิดรายการ',
                            'fall' => 'ไม่รับ',
                            default => 'ปกติ',
                        } }}
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>

</body>

</html>

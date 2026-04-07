<!DOCTYPE html>
<html lang="th">

<head>
    <meta charset="UTF-8">
    <style>
        body {
            font-family: DejaVu Sans, sans-serif;
            font-size: 14px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        th,
        td {
            border: 1px solid #000;
            padding: 6px;
            text-align: left;
        }

        th {
            background: #eee;
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
            @foreach ($sales as $i => $sale)
                <tr>
                    <td>{{ $i + 1 }}</td>
                    <td>{{ $sale->running_no }}</td>
                    <td>{{ $sale->brand }} {{ $sale->model }}</td>
                    <td>{{ $sale->user_r->name ?? '-' }}</td>
                    <td>{{ $sale->member_r->fullname ?? '-' }}</td>
                    <td>{{ $sale->created_at->format('d/m/Y') }}</td>
                    <td>{{ optional($sale->appointment_date)->format('d/m/Y') }}</td>
                    <td>
                        {{ match ($sale->status) {
                            'between' => 'จำนำอยู่',
                            'fall' => 'หลุด',
                            'problem' => 'มีปัญหา',
                            'closed' => 'ปิดรายการ',
                            default => 'ปกติ',
                        } }}
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>

</body>

</html>

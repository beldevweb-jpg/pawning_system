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

        body {
            font-family: 'THSarabunNew';
            font-size: 18px;
        }

        h2 {
            text-align: center;
            margin-bottom: 10px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        th,
        td {
            border: 1px solid #000;
            padding: 6px;
            text-align: center;
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

                    <td>
                        {{ $sale->running_no ?? '-' }}
                    </td>

                    <td>
                        @if ($sale->brand || $sale->model)
                            {{ $sale->brand ?? '-' }} {{ $sale->model ?? '' }}
                        @else
                            {{ $sale->other_type ?? '-' }}
                        @endif
                    </td>

                    <td>
                        {{ $sale->user_r?->name ?? '-' }}
                    </td>

                    <td>
                        {{ $sale->member_r?->fullname ?? '-' }}
                    </td>

                    <td>
                        {{ $sale->created_at?->format('d/m/Y') ?? '-' }}
                    </td>

                    <td>
                        {{ $sale->appointment_date?->format('d/m/Y') ?? '-' }}
                    </td>

                    <td>

                        @php

                            $statusText = match ($sale->status) {
                                'between' => 'จำนำอยู่',
                                'foreclosed' => 'หลุด',
                                'problem' => 'มีปัญหา',
                                'closed' => 'ปิดรายการ',
                                'fall' => 'ไม่รับ',

                                default => 'ปกติ',
                            };

                        @endphp

                        {{ $statusText }}

                    </td>

                </tr>
            @endforeach

        </tbody>

    </table>

</body>

</html>

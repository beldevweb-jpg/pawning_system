<!DOCTYPE html>
<html lang="th">

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title>Daily Report PDF</title>

    <link rel="stylesheet" href="{{ public_path('css/daily-pdf.css') }}">
</head>

<body>

    <!-- Header -->
    <div class="header">
        <h2>รายงานสรุปยอดรายวัน</h2>
        <p>ประจำวันที่: {{ $date }}</p>
    </div>

    <!-- Info -->
    <div class="info-box">
        <div>
            <strong>ผู้จัดทำ:</strong>
            {{ $report->user->name ?? 'System' }}
        </div>

        <div>
            <strong>เวลา:</strong>
            {{ $report->created_at->format('H:i') }} น.
        </div>
    </div>

    <!-- Table -->
    <table class="table">
        <thead>
            <tr>
                <th width="10%">ลำดับ</th>
                <th width="40%">รายการ</th>
                <th width="25%">เงินสด</th>
                <th width="25%">เงินโอน</th>
            </tr>
        </thead>

        <tbody>
            @forelse ($expenses as $index => $item)
                <tr>
                    <td class="text-center">
                        {{ $index + 1 }}
                    </td>

                    <td>
                        <strong>{{ $item->title }}</strong>
                        <br>
                        <small>{{ $item->description }}</small>
                    </td>

                    <td class="text-right">
                        -{{ number_format($item->cash, 2) }}
                    </td>

                    <td class="text-right">
                        -{{ number_format($item->transfer, 2) }}
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="4" class="text-center">
                        ไม่มีข้อมูล
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>

    <!-- Summary -->
    <div class="summary">

        <div class="summary-row">
            <span>รวมยอดขาย</span>
            <span>{{ number_format($report->total_sales, 2) }} บาท</span>
        </div>

        <div class="summary-row">
            <span>รวมรายจ่าย</span>
            <span>{{ number_format($report->total_expenses, 2) }} บาท</span>
        </div>

        <div class="summary-row total">
            <span>ยอดสุทธิคงเหลือ</span>
            <span>{{ number_format($report->net_amount, 2) }} บาท</span>
        </div>

    </div>

</body>

</html>

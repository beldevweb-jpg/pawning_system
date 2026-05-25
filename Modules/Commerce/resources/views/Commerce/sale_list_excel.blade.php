<table>

    <!-- หัวกระดาษ -->

    <tr>
        <td colspan="9" align="center">
            <strong style="font-size: 20px;">
                ร้านทอง ABC
            </strong>
        </td>
    </tr>

    <tr>
        <td colspan="9" align="center">
            <strong style="font-size: 16px;">
                รายงานรายการขาย
            </strong>
        </td>
    </tr>

    <tr>
        <td colspan="9" align="center">
            วันที่ออกรายงาน :
            {{ now()->format('d/m/Y H:i') }}
        </td>
    </tr>

    <tr>
        <td colspan="9"></td>
    </tr>

</table>

<!-- ตารางข้อมูล -->

<table border="1">

    <thead>

        <tr>

            <th>ลำดับ</th>

            <th>เลขที่</th>

            <th>ยี่ห้อ</th>

            <th>รุ่น</th>

            <th>Serial Number</th>

            <th>สถานะ</th>

            <th>เงินสด</th>

            <th>โอน</th>

            <th>วันที่</th>

        </tr>

    </thead>

    <tbody>

        @foreach ($sales as $item)
            <tr>

                <td>{{ $loop->iteration }}</td>

                <td>{{ $item->running_no }}</td>

                <td>{{ $item->brand }}</td>

                <td>{{ $item->model }}</td>

                <td>{{ $item->serial_number }}</td>

                <td>{{ $item->status }}</td>

                <td>{{ number_format($item->cash, 2) }}</td>

                <td>{{ number_format($item->transfer, 2) }}</td>

                <td>
                    {{ $item->created_at?->format('d/m/Y H:i') }}
                </td>

            </tr>
        @endforeach

    </tbody>

</table>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="{{ asset('css/customer.css') }}">
</head>

<body>
    <div class="container">
        <div class="card">
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
            <div class="page-header">
                <h2>ข้อมูลลูกค้า / ข้อมูลโทรศัพท์</h2>

                <input type="text" class="search-box" placeholder="ค้นหา..." />
            </div>

            <div class="table-wrapper">
                <table>
                    <thead>
                        <tr>
                            <th>ลำดับ</th>
                            <th>ยี่ห้อ</th>
                            <th>รุ่น</th>
                            <th>รหัสเครื่อง</th>
                            <th>ชื่อลูกค้า</th>
                            <th>สถานะ</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($member as $item)
                            @forelse ($item->sales_r as $sale)
                                <tr class="clickable-row"
                                    onclick="window.location='{{ route('commerce.create_pawning', $item->sale_id) }}'">

                                    <td>{{ $loop->iteration }}</td>
                                    <td>{{ $sale->brand }}</td>
                                    <td>{{ $sale->model }}</td>
                                    <td>{{ $sale->serial_number }}</td>
                                    <td>{{ $item->fullname }}</td>
                                    <td>{{ $sale->status }}</td>

                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6">ไม่พบข้อมูล</td>
                                </tr>
                            @endforelse
                        @endforeach

                    </tbody>
                </table>
            </div>

        </div>
    </div>

</body>

</html>

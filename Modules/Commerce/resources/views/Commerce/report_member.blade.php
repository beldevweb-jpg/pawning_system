<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Member report</title>
    <link rel="stylesheet" href="{{ asset('css/table.css') }}">
</head>

<body>

    <!-- Top Header -->
    <header class="header">
        <div class="user">
            <span class="avatar">👤</span>
            <span>username</span>
        </div>
        <button type="button" class="logout">ออกจากระบบ</button>
    </header>

    <div class="container">
        <div class="card">

            {{-- แจ้งเตือน --}}
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
            <!-- Card Header -->
            <div class="card-header">
                <div class="header-left">
                    <h2>ข้อมูล user</h2>
                </div>

                <div class="header-right">
                    <input type="text" class="search-box" placeholder="ค้นหา..." />
                </div>
            </div>


            <!-- Table -->
            <div class="table-wrapper">
                <table>
                    <thead>
                        <tr>
                            <th>ลำดับ</th>
                            <th>ยี่ห้อ</th>
                            <th>รุ่น</th>
                            <th>รหัสเครื่อง</th>
                            <th>ชื่อลูกค้า</th>
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



</html>

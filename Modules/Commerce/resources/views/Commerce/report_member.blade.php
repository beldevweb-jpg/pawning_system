<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Member report</title>
    <link rel="stylesheet" href="{{ asset('css/table.css') }}">
</head>

<body>
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
                <form method="GET" id="searchForm" action="{{ route('commerce.search_member') }}">
                    <input type="text" name="keyword" placeholder="ค้นหา..." value="{{ request('keyword') }}"
                        autocomplete="off" id="searchInput">
                </form>

                <script>
                    let timer;

                    document.getElementById('searchInput').addEventListener('input', function() {
                        clearTimeout(timer);
                        timer = setTimeout(() => {
                            document.getElementById('searchForm').submit();
                        }, 500); // รอ 0.5 วิ
                    });
                </script>
                <!-- Table -->
                <div class="table-wrapper">
                    <table>
                        <thead>
                            <tr>
                                <th>ลำดับ</th>
                                <th>เลขตั๋ว</th>
                                <th>ยี่ห้อ</th>
                                <th>รุ่น</th>
                                <th>รหัสเครื่อง</th>
                                <th>ชื่อลูกค้า</th>
                                <th>ประเภทบริการ</th>
                            </tr>
                        </thead>

                        <tbody>
                            @foreach ($member as $item)
                                @forelse ($item->sales_r as $sale)
                                    <tr class="clickable-row"
                                        onclick="window.location='{{ route('commerce.show_sale', $sale->id) }}'">
                                        <td>{{ $loop->iteration }}</td>
                                        <td>{{ $sale->id }}</td>
                                        <td>{{ $sale->brand }}</td>
                                        <td>{{ $sale->model }}</td>
                                        <td>{{ $sale->serial_number }}</td>
                                        <td>{{ $item->fullname }}</td>
                                        <td>
                                            @if ($sale->type_serve == 'pawm')
                                                จำนำ
                                            @else
                                                ขาย
                                            @endif
                                        </td>
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

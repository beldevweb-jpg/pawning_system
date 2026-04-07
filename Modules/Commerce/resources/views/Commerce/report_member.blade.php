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

    <div class="container" style="max-width:1500px;margin:auto">
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
                    <h2>ข้อมูลลูกค้า</h2>
                    <span class="subtitle">user</span>
                </div>

                @foreach ($member as $members)
                    <a href="{{ route('commerce.create_pawning', [
                        'id' => $members->member_id,
                        'mode' => 'edit',
                    ]) }}"
                        style="display:block; margin-bottom:5px;">
                        ดำเนินรายการ
                    </a>
                @endforeach
            </div>

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
                @php
                    $hasBrandModel = false;

                    foreach ($member as $m) {
                        foreach ($m->sales_r as $s) {
                            if (!empty($s->brand) || !empty($s->model)) {
                                $hasBrandModel = true;
                                break 2;
                            }
                        }
                    }
                @endphp
                <table>
                    <thead>
                        <tr>
                            <th>ลำดับ</th>
                            <th>เลขตั๋ว</th>

                            @if ($hasBrandModel)
                                <th>ยี่ห้อ</th>
                                <th>รุ่น</th>
                            @else
                                <th>รายการ</th>
                            @endif

                            <th>รหัสเครื่อง</th>
                            <th>ชื่อลูกค้า</th>
                            <th>ประเภทบริการ</th>
                            <th>สถานะเครื่อง</th>
                        </tr>
                    </thead>

                    <tbody>
                        @foreach ($member as $item)
                            @forelse ($item->sales_r as $sale)
                                <tr class="clickable-row"
                                    onclick="window.location='{{ route('commerce.show_sale', ['id' => $sale->id, 'mode' => 'view']) }}'">
                                    <td>{{ $loop->iteration }}</td>
                                    <td>{{ $sale->running_no }}</td>

                                    @if ($hasBrandModel)
                                        <td>{{ $sale->brand ?? ($sale->other_brand ?? 'ไม่ระบุ') }}</td>
                                        <td>{{ $sale->model }}</td>
                                    @else
                                        <td>{{ $sale->other_type }} </td>
                                    @endif

                                    <td>{{ $sale->serial_number }}</td>
                                    <td>{{ $item->fullname }}</td>
                                    <td>
                                        @if ($sale->type_serve == 'pawm')
                                            จำนำ
                                        @else
                                            ขาย
                                        @endif
                                    </td>
                                    <td>
                                        @php
                                            $statusMap = [
                                                'between' => 'จำนำ',
                                                'foreclosed' => 'หลุด',
                                                'problem' => 'มีปัญหา',
                                                'closed' => 'ปิดรายการ',
                                                'fall' => 'หลุด',
                                            ];
                                        @endphp
                                        {{ $statusMap[trim($sale->status)] ?? '-' }}
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

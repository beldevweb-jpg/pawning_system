<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ประวัติการขาย</title>
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
        @if (auth()->user()->role_id == 3)
            <nav>
                <a href="{{ route('commerce.report_sellfront') }}">
                    {{ request()->routeIs('commerce.report_sellfront') ? '► ' : '' }}รายการรับจ่าย
                </a> |

                <a href="{{ route('commerce.sale_list') }}">
                    {{ request()->routeIs('commerce.sale_list') ? '► ' : '' }}รายการจำนำ
                </a> |

                <a href="{{ route('user.index') }}">
                    {{ request()->routeIs('user.*') ? '► ' : '' }}จัดการพนักงาน
                </a> |

                <a href="{{ route('commerce.show_member') }}">
                    {{ request()->routeIs('commerce.show_member') ? '► ' : '' }}รายชื่อลูกค้า
                </a>
            </nav>
            <hr>
        @endif

        <div class="card">

            <div class="page-header">
                <div class="header-left">
                    <h2>ประวัติการขาย</h2>
                    <span class="subtitle">User Management</span>
                </div>

                <div class="header-right">
                    <input type="text" class="search-box" placeholder="ค้นหา..." />
                </div>
            </div>

            <!-- TABLE -->
            @php
                $showFallColumn = $sales->where('status', '!=', 'fall')->count() > 0;
            @endphp

            <div class="table-wrapper">
                <table class="table">

                    <thead>
                        <tr>
                            <th>ลำดับ</th>
                            <th>ID</th>
                            <th>สินค้า</th>
                            <th>พนักงาน</th>
                            <th>ลูกค้า</th>
                            <th>วันที่ทำรายการ</th>
                            <th>ครบกำหนด</th>

                            @if (auth()->user()->role_id == 3)
                                <th>แก้ไข</th>
                                @if ($showFallColumn)
                                    <th>หลุดจำนำ</th>
                                @endif
                            @endif

                            <th>สถานะ</th>
                        </tr>
                    </thead>

                    <tbody>
                        @forelse ($sales as $sale)

                            <tr onclick="window.location='{{ route('commerce.detil_sale', $sale->id) }}'"
                                style="cursor:pointer;">

                                <td>{{ $sales->firstItem() + $loop->index }}</td>
                                <td>{{ $sale->id }}</td>

                                <td>{{ $sale->brand ?? '-' }} {{ $sale->model ?? '-' }}</td>

                                <td>{{ $sale->user_r->name ?? '-' }}</td>

                                <td>{{ $sale->member_r->fullname ?? '-' }}</td>

                                <td>{{ $sale->created_at->format('d/m/y') }}</td>

                                <td>{{ $sale->appointment_date->format('d/m/y') }}</td>

                                @if (auth()->user()->role_id == 3)
                                    <td onclick="event.stopPropagation()">
                                        <a href="{{ route('commerce.create_pawning', $sale->id) }}" class="btn-edit">
                                            Edit
                                        </a>
                                    </td>

                                    @if ($showFallColumn)
                                        <td onclick="event.stopPropagation()">
                                            @if ($sale->status != 'fall')
                                                <a href="{{ route('commerce.slip', $sale->id) }}" class="btn-delete">
                                                    หลุด
                                                </a>
                                            @endif
                                        </td>
                                    @endif
                                @endif

                                <td>
                                    {{ match ($sale->status) {
                                        'between' => 'จำนำอยู่',
                                        'fall' => 'หลุด',
                                        'problem' => 'มีปัญหา',
                                        'closed' => 'ปิดรายการ',
                                        'bad' => 'ไม่รับ',
                                        default => 'ปกติ',
                                    } }}
                                </td>

                            </tr>

                        @empty
                            <tr>
                                <td colspan="10" style="text-align:center;">ไม่มีข้อมูล</td>
                            </tr>
                        @endforelse

                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            <div class="pagination-wrapper">
                {{ $sales->links() }}
            </div>

        </div>
    </div>


</body>

</html>

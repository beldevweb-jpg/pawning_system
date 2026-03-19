<!DOCTYPE html>
<html lang="th">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="stylesheet" href="{{ asset('css/table.css') }}">
    <title>รายชื่อลูกค้า</title>
</head>
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

<body>
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
                    <h2>รายชื่อลูกค้า</h2>
                    <span class="subtitle">member</span>
                </div>

                <div class="header-right">
                    <input type="text" class="search-box" placeholder="ค้นหา..." />
                </div>
            </div>

            <div class="table-wrapper">
                <table class="table">
                    <thead>
                        <tr>
                            <th>ลำดับ</th>
                            <th>รหัสบัตรประชาชน</th>
                            <th>ชื่อ-นามสกุล</th>
                            <th>เบอร์โทร</th>
                            <th>แก้ไชสถานะลูกค้า</th>
                            <th>สถานะ</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($member as $index => $members)
                            <tr>
                                <td>{{ $member->firstItem() + $index }}</td>
                                <td>{{ $members->tax_number }}</td>
                                <td>{{ $members->fullname }}</td>
                                <td>{{ $members->phone }}</td>
                                <td><a
                                        href="{{ route('commerce.edit_status_member', $members->member_id) }}">แก้ไขสถานะ</a>
                                </td>
                                <td>
                                    {{ match ($members->status) {
                                        'between' => 'เคยทำรายการ',
                                        'foreclosed' => 'มีประวัติโดนยึด',
                                        'problem' => 'มีปัญหา',
                                        default => '-',
                                    } }}
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            <div class="pagination-wrapper">
                {{ $member->links() }}
            </div>

        </div>
    </div>
</body>

</html>

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
            <div class="page-header">
                <div class="header-left">
                    <h2>ข้อมูล user</h2>
                    <span class="subtitle">member</span>
                </div>

                <div class="header-right">
                    <a href="{{ route('register') }}">+ เพิ่มผู้ใช้</a>
                    <input type="text" class="search-box" placeholder="ค้นหา..." />
                </div>

            </div>

            <!-- Table -->
            <div class="table-wrapper">
                <table>
                    <thead>
                        <tr>
                            <th>ลำดับ</th>
                            <th>username</th>
                            <th>password</th>
                            <th>role</th>
                            <th>status</th>
                            <th class="edit-col">แก้ไขข้อมูล</th>
                        </tr>
                    </thead>

                    <tbody>
                        @forelse ($users as $user)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td>
                                    {{ $user->name }}
                                </td>
                                </td>
                                <td>{{ $user->username }}</td>
                                <td>{{ $user->phone }}</td>
                                <td>{{ $user->status }}</td>
                                <td>
                                    <a href="{{ route('user.edit', $user->user_id) }}">
                                        แก้ไขข้อมูล
                                    </a>
                                </td>
                            </tr>

                        @empty
                            <tr>
                                <td colspan="6">ไม่พบข้อมูล</td>
                            </tr>
                        @endforelse

                    </tbody>
                </table>
            </div>
        </div>



</html>

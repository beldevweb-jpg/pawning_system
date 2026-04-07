<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="stylesheet" href="{{ asset('css/table.css') }}">
    <title>จัดการดอกเบี้ย</title>
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
                <a href="{{ route('commerce.report_salefront') }}">
                    {{ request()->routeIs('commerce.report_salefront') ? '► ' : '' }}รายการรับจ่าย
                </a> |

                <a href="{{ route('commerce.sale_list') }}">
                    {{ request()->routeIs('commerce.sale_list') ? '► ' : '' }}รายการจำนำ
                </a> |

                <a href="{{ route('user.index') }}">
                    {{ request()->routeIs('user.*') ? '► ' : '' }}จัดการพนักงาน
                </a> |

                <a href="{{ route('commerce.show_member') }}">
                    {{ request()->routeIs('commerce.show_member') ? '► ' : '' }}รายชื่อลูกค้า

                </a>|

                <a href="{{ route('commerce.manage_dok') }}">
                    {{ request()->routeIs('commerce.manage_dok') ? '► ' : '' }}จัดการคอกเบี้ยต่อเดือน

                </a>|

                <a href="{{ route('commerce.settings') }}">
                    {{ request()->routeIs('commerce.settings') ? '► ' : '' }}ตั้งค่าอื่นๆ
                </a> |
            </nav>
            <hr>
        @endif
        <div class="card">
            <div class="page-header">
                <div class="header-left">
                    <h2>จัดการดอกเบี้ย</h2>
                </div>
            </div>
            <form action="{{ route('commerce.stor_manage_dok', $interest->id) }}" method="POST">
                @csrf

                <label>{{ $interest->days }} วัน</label>
                <br>
                <label>วันที่</label>
                <input type="text" name="date" value="{{ $interest->days }}">
                <label>ดอกเบี้ย (%)</label>
                <input type="text" name="percent" value="{{ $interest->percent }}">

                <button type="submit">บันทึก</button>
            </form>
        </div>

</body>

</html>

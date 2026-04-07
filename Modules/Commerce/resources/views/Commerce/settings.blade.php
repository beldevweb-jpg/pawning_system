<!DOCTYPE html>
<html lang="th">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="stylesheet" href="{{ asset('css/table.css') }}">
    <title>settings</title>
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
        <div class="container" style="max-width:1500px;margin:auto">
            <div class="card-header">
                <div class="header-left">
                    <h2>ตั้งค่าอื่นๆ</h2>
                    <span class="subtitle">settings</span>
                </div>
            </div>
            <form method="POST" action="{{ route('commerce.save_settings', $settings->id ?? null) }}">
                @csrf
                @method('POST')
                <div class="card-body">
                    <label>ชื่อร้าน</label>
                    <input type="text" name="company_name"
                        value="{{ old('company_name', $settings->company_name ?? '') }}">

                    <label>เบอร์โทร</label>
                    <input type="text" name="phone" value="{{ old('phone', $settings->phone ?? '') }}">

                    <label>เลขตั๋ว</label>
                    <input type="text" name="running_no"
                        value="{{ old('running_no', $settings->running_no ?? '') }}">

                    <button type="submit">{{ isset($settings) ? 'อัปเดต' : 'บันทึก' }}</button>
            </form>
        </div>
    </div>
</body>

</html>

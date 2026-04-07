<!DOCTYPE html>
<html lang="th">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ค้นหาประวัติ</title>
    <link rel="stylesheet" href="{{ asset('css/search.css') }}">
    <script src="{{ asset('js/app.js') }}"></script>
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

            <a href="{{ route('commerce.running_no') }}">
                {{ request()->routeIs('commerce.running_no') ? '► ' : '' }}จัดการเลขที่ตั๋ว
                (ห้ามเปลี่ยนถ้าไม่จำเป็น)
            </a> |
        </nav>
        <hr>
    @endif

    <main class="page">
        @if (auth()->user()->role_id == 1)
            <nav>
                <a href="{{ route('commerce.report_salefront') }}">
                    {{ request()->routeIs('commerce.report_salefront') ? '► ' : '' }}รายการรับจ่าย
                </a> |

                <a href="{{ route('commerce.sale_list') }}">
                    {{ request()->routeIs('commerce.sale_list') ? '► ' : '' }}รายการจำนำ
                </a> |
            </nav>
            <hr>
        @endif

        <h1 class="page-title">ค้นหาประวัติ</h1>
        <p class="page-subtitle">ค้นหาข้อมูลจากเบอร์โทรหรือหมายเลขเครื่อง</p>

        <form id="searchForm" method="POST" action="{{ route('commerce.store_create_search') }}">
            @csrf
            <div class="search-card">
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
                <div class="field">
                    <label>บัตรประชาชน</label>
                    <input id="tax_number" name="tax_number" type="number" placeholder="บัตรประชาชน">
                </div>

                <div class="divider">หรือ</div>

                <div class="field">
                    <label>หมายเลขเครื่อง (IMEI / Serial)</label>
                    <input id="serial" name="serial_number" type="text" placeholder="กรอกหมายเลขเครื่อง"
                        oninput="this.value=this.value.replace(/[^a-zA-Z0-9ก-๙\s]/g,'')">
                </div>

                <button type="submit" class="search-btn">
                    🔍 ค้นหาประวัติ
                </button>
        </form>
        <a href="{{ route('commerce.create_salefront', $sale->id ?? null) }}">รายการขาย</a>
        <button type="button" onclick="startScan()">📷 สแกน QR Code</button>

        <div id="reader" style="width:300px; margin-top:10px;"></div>


        </div>
    </main>

</body>

</html>

<!-- โหลด library -->
<script src="https://unpkg.com/html5-qrcode"></script>

<!-- เขียนโค้ดของเรา -->
<script>
    function startScan() {
        const html5QrCode = new Html5Qrcode("reader");

        html5QrCode.start({
                facingMode: "environment"
            }, // กล้องหลัง
            {
                fps: 10,
                qrbox: 250
            },
            (decodedText) => {
                alert("QR Code: " + decodedText);

                // ถ้าเป็น URL → ไปหน้าเลย
                window.location.href = decodedText;

                html5QrCode.stop();
            },
            (errorMessage) => {
                // ignore
            }
        );
    }
</script>

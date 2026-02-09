<!DOCTYPE html>
<html lang="th">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>ค้นหาประวัติ</title>
    <link rel="stylesheet" href="{{ asset('css/search.css') }}">
    <script src="{{ asset('js/app.js') }}"></script>
</head>

<header class="header">
    <div class="user">
        <span class="avatar">👤</span>
        <span>{{ auth()->user()->name }}</span>
    </div>

    <form method="POST" action="{{ route('logout') }}">
        @csrf
        <button type="submit" class="logout">
            ออกจากระบบ
        </button>
    </form>
</header>


<body>
    <main class="page">
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


        </div>
    </main>

</body>

</html>

<!DOCTYPE html>
<html lang="th">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>ค้นหาประวัติ</title>
    <link rel="stylesheet" href="search.css">
    <script src="script.js" defer></script>
    <link rel="stylesheet" href="{{ asset('css/search.css') }}">
    <script src="{{ asset('js/app.js') }}"></script>
</head>

<body>

    <main class="page">
        <h1 class="page-title">ค้นหาประวัติ</h1>
        <p class="page-subtitle">ค้นหาข้อมูลจากเบอร์โทรหรือหมายเลขเครื่อง</p>

        <div class="search-card">
            <form method="POST" action="{{ route('commerce.store_create_search') }}" id="searchForm">
                @csrf
                <div class="field">
                    <label>เบอร์โทรศัพท์</label>
                    <input id="phone" name="phone" type="text" placeholder="เช่น 089xxxxxxx">
                </div>

                <div class="divider">หรือ</div>

                <div class="field">
                    <label>หมายเลขเครื่อง (IMEI / Serial)</label>
                    <input id="serial" name="serial" type="text" placeholder="กรอกหมายเลขเครื่อง">
                </div>

                <button type="submit" class="search-btn">
                    🔍 ค้นหาประวัติ
                </button>
            </form>

        </div>
    </main>

</body>

</html>

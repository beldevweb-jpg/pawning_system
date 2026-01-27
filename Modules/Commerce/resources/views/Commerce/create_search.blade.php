<!DOCTYPE html>
<html lang="th">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>ค้นหาประวัติ</title>
    <link rel="stylesheet" href="{{ asset('css/search.css') }}">
    <script src="{{ asset('js/app.js') }}"></script>
</head>

<body>
    @if (session()->has('success'))
        <div class="alert alert-info" role="alert">
            <strong>{{ session()->get('success') }}</strong>
        </div>
        @php session()->forget('success') @endphp
    @endif
    @if ($errors->any())
        <div class="alert alert-danger" role="alert">
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </div>
    @endif

    <main class="page">
        <h1 class="page-title">ค้นหาประวัติ</h1>
        <p class="page-subtitle">ค้นหาข้อมูลจากเบอร์โทรหรือหมายเลขเครื่อง</p>

        <div class="search-card">
            <form id="searchForm" method="POST" action="{{ route('commerce.store_create_search') }}">
                @csrf
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

<!DOCTYPE html>
<html lang="th">

<head>
    <meta charset="UTF-8">
    <title>เพิ่มข้อมูลลูกค้า</title>
    <link rel="stylesheet" href="{{ asset('css/add-customer.css') }}">
    <link rel="stylesheet" href="{{ asset('css/error.css') }}">
</head>

<body>

    <!-- HEADER -->
    <header class="header">
        <div class="user">
            <span class="avatar">👤</span>
            <span>username</span>
        </div>
        <button class="logout">ออกจากระบบ</button>
    </header>

    <!-- TITLE -->
    <h1 class="title">เพิ่มข้อมูลลูกค้า</h1>
    {{-- success --}}



    <!-- FORM CARD -->
    <form action="{{ route('commerce.store_create_member') }}" method="POST" enctype="multipart/form-data">
        @csrf
        <div class="card">
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
            <div class="form-row">
                <label>เลขบัตรประชาชน</label>
                <input type="text" name="tax_number" id="idcard" placeholder="กรอกเลขบัตรประชาชน">
            </div>

            <div class="form-row">
                <label>ชื่อ - นามสกุล</label>
                <input type="text" name="fullname" id="fullname" placeholder="กรอกชื่อ - นามสกุล">
            </div>

            <div class="form-row">
                <label>เบอร์โทรศัพท์</label>
                <input type="text" name="phone" id="phone" placeholder="กรอกเบอร์โทรศัพท์">
            </div>

            <div class="form-row">
    <label>รูปบัตรประชาชน</label>
    <input
        type="file"
        name="idcard_images[]"
        id="idcard-image"
        accept="image/*"
        capture="environment"
        multiple
    >

    <div id="preview" style="display:flex; gap:10px; margin-top:10px;"></div>
</div>

            <div class="action-buttons">
                <button type="button" class="btn cancel" id="clearForm">
                    ยกเลิก
                </button>

                <button type="submit" class="btn confirm">
                    ดำเนินการต่อ
                </button>
            </div>
        </div>
    </form>

    </div>
    <script src="{{ asset('js/app.js') }}" defer></script>
</body>

</html>

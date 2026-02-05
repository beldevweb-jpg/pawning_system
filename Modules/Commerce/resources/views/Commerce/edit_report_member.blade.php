<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit member</title>
    <link rel="stylesheet" href="edit.css">
</head>
<body>

<div class="container">
    <div class="card">

            <!-- TITLE -->
    <h1 class="title">แก้ไขข้อมูลลูกค้า</h1>
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
                <input type="file" name="idcard_image" id="idcard-image" accept="image/*">
            </div>

            <!-- ACTION -->
            <div class="form-action">
                <a href="/report" class="btn-cancel">ยกเลิก</a>
                <button type="submit" class="btn-save">บันทึก</button>
            </div>
        </form>

    </div>
</div>

</body>
</html>

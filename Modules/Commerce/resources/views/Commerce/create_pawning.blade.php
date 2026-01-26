<!DOCTYPE html>
<html lang="th">

<head>
    <meta charset="UTF-8">
    <title>โอ๋ โมบาย</title>
    <link rel="stylesheet" href="{{ asset('css/pawning.css') }}">
    <link rel="stylesheet" href="{{ asset('css/error.css') }}">
    <script src="{{ asset('js/app.js') }}"></script>
</head>

<body>

    <header class="header">
        <div class="user">
            <span class="avatar">👤</span>
            <span>username</span>
        </div>
        <button class="logout">ออกจากระบบ</button>
    </header>

    @if ($errors->any())
        <div class="error-box">
            กรุณากรอกข้อมูลให้ครบ
            <ul>
                @foreach ($errors->all() as $error)
                    <li>- {{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif



    <h1 class="title">ขาย</h1>

    <form method="POST" action="{{ route('commerce.store_create_pawning') }}">
        @csrf

        <div class="container">

            <!-- ประเภทการขาย -->
            <div class="card">
                <h2>ประเภท</h2>

                <label class="option">
                    <input type="radio" name="type_serve" value="pawn"> วาง
                </label>
                <label class="option">
                    <input type="radio" name="type_serve" value="extend"> ต่อ
                </label>
                <label class="option">
                    <input type="radio" name="type_serve" value="redeem"> ไถ่
                </label>
                <label class="option">
                    <input type="radio" name="type_serve" value="other" id="pawn-other">
                    อื่นๆ
                </label>

                <input class="input" name="others" id="typesell-other-input" placeholder="กรอกประเภท" disabled>
            </div>

            <!-- ข้อมูลลูกค้า -->
            <div class="card">
                <h2>ข้อมูลลูกค้า</h2>

                <div class="form-row">
                    <label>ชื่อ-นามสกุล</label>
                    <input name="members">
                </div>

                <div class="form-row">
                    <label>บัตรประชาชน</label>
                    <input name="tax_number">
                </div>

                <div class="form-row">
                    <label>เบอร์ติดต่อ</label>
                    <input name="phone">
                </div>
            </div>

            <!-- ประเภทสินค้า -->
            <div class="card">
                <h2>ประเภทสินค้า</h2>

                <label class="option">
                    <input type="radio" name="type_category" value="mobile"> มือถือ
                </label>
                <label class="option">
                    <input type="radio" name="type_category" value="tablet"> Tablet
                </label>
                <label class="option">
                    <input type="radio" name="type_category" value="other" id="type-other">
                    อื่นๆ
                </label>

                <input class="input" name="description" id="type-other-input" placeholder="กรอกประเภทสินค้า" disabled>
            </div>

            <!-- ยี่ห้อ -->
            <div class="card">
                <h2>ยี่ห้อ</h2>

                <label class="option"><input type="radio" name="brand" value="iphone"> iPhone</label>
                <label class="option"><input type="radio" name="brand" value="samsung"> Samsung</label>
                <label class="option"><input type="radio" name="brand" value="oppo"> OPPO</label>
                <label class="option"><input type="radio" name="brand" value="vivo"> vivo</label>
            </div>
            <div class="submit">
                <form method="POST" action="{{ route('commerce.destroy', $id) }}">
                    @csrf
                    {{-- {{ dd($id) }} --}}
                    <button type="button">ยกเลิก</button>
                </form>
                <button type="submit">บันทึก</button>
            </div>

        </div>
    </form>
</body>

</html>

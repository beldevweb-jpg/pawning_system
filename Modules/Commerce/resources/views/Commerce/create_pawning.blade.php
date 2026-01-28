<!DOCTYPE html>
<html lang="th">

<head>
    <meta charset="UTF-8">
    <title>โอ๋ โมบาย</title>
    <link rel="stylesheet" href="{{ asset('css/pawning.css') }}">
</head>

<body>

    <form method="POST" action="{{ route('commerce.store_pawning') }}">
        @csrf

        <header class="header">
            <div class="user">
                <span class="avatar">👤</span>
                <span>username</span>
            </div>
            <button type="button" class="logout">ออกจากระบบ</button>
        </header>

        <h1 class="title">ขาย</h1>

        <div class="container">

            <div class="card">
                <h2>ประเภท</h2>
                <input type="hidden" name="sale_id" value="{{ $sale->id }}">

                <label class="option">
                    <input type="radio" name="subcategories" value="วาง"> วาง
                </label>
                <label class="option">
                    <input type="radio" name="subcategories" value="ต่อ"> ต่อ
                </label>
                <label class="option">
                    <input type="radio" name="subcategories" value="ไถ่"> ไถ่
                </label>
                <label class="option">
                    <input type="radio" id="pawn-other" name="subcategories" value="อื่นๆ"> อื่นๆ
                </label>

                <input class="input" id="typesell-other-input" name="subcategories_other" placeholder="กรอกประเภท"
                    disabled>
            </div>

            <div class="card">
                <h2 class="section-title">ข้อมูลลูกค้า</h2>

                <div class="form-row">
                    <label>ชื่อ-นามสกุล</label>
                    <input value="{{ $member->fullname }}" disabled>
                </div>

                <div class="form-row">
                    <label>บัตรประชาชน</label>
                    <input value="{{ $member->tax_number }}" disabled>
                </div>

                <div class="form-row">
                    <label>เบอร์ติดต่อ</label>
                    <input value="{{ $member->phone }}" disabled>
                </div>

                <div class="form-row">
                    <label>สถานะ</label>
                    <input class="status"
                        value="{{ $member->status == 'new' ? 'ลูกค้าใหม่' : ($member->status == 'history' ? 'ลูกค้าเก่า' : 'มีปัญหา') }}"
                        disabled>
                </div>
            </div>

            <div class="card">
                <h2>ประเภทสินค้า</h2>

                <label class="option">
                    <input type="radio" name="type_category" value="มือถือ"> มือถือ
                </label>
                <label class="option">
                    <input type="radio" name="type_category" value="Tablet"> Tablet
                </label>
                <label class="option">
                    <input type="radio" id="type-other" name="type_category" value="อื่นๆ"> อื่นๆ
                </label>

                <input class="input" id="type-other-input" name="type_category_other" placeholder="กรอกประเภทสินค้า"
                    disabled>
            </div>

            <div class="card">
                <h2>ยี่ห้อ</h2>

                <div class="option-grid">
                    <label class="option"><input type="radio" name="brand" value="iphone"> iPhone</label>
                    <label class="option"><input type="radio" name="brand" value="Samsung"> Samsung</label>
                    <label class="option"><input type="radio" name="brand" value="oppo"> OPPO</label>
                    <label class="option"><input type="radio" name="brand" value="huawei"> Huawei</label>
                    <label class="option"><input type="radio" name="brand" value="realme"> realme</label>
                    <label class="option"><input type="radio" name="brand" value="vivo"> vivo</label>

                    <div class="option-other">
                        <label class="option">
                            <input type="radio" id="brand-other" name="brand" value="อื่นๆ">
                            อื่นๆ
                        </label>
                        <input class="input" id="brand-other-input" name="brand_other" placeholder="กรอกยี่ห้อ"
                            disabled>
                    </div>
                </div>
            </div>

            <div class="card">
                <h2>รุ่น</h2>
                <input class="input" name="model" type="text" value="{{ old('model') }}" required>

                <h2>รหัส IMEI</h2>
                <input class="input" name="serial_number" type="text" value="{{ old('imei') }}" required>
            </div>

            <div class="card">
                <h2>ราคา</h2>
                <input class="input" name="price" type="number" value="{{ old('price') }}" required>

                <h2>หมายเหตุ</h2>
                <input class="input" name="note" type="text" value="{{ old('note') }}">
            </div>

            <div class="submit">
                <button type="button" name="cancel" onclick="goPage('index.html')">ยกเลิก</button>
                <button type="submit">ดำเนินการต่อ</button>
            </div>

        </div>
    </form>

    <script src="{{ asset('js/app.js') }}"></script>
</body>


</html>

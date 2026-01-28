<!DOCTYPE html>
<html lang="th">

<head>
    <meta charset="UTF-8">
    <title>โอ๋ โมบาย</title>
    <link rel="stylesheet" href="{{ asset('css/pawning.css') }}">
    <link rel="stylesheet" href="{{ asset('css/error.css') }}">
</head>

<body>

    <header class="header">
        <div class="user">
            <span class="avatar">👤</span>
            <span>username</span>
        </div>
        <button class="logout">ออกจากระบบ</button>
    </header>

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

    <h1 class="title">ขาย</h1>

    <form method="POST" action="{{ route('commerce.store_pawning', $id) }}">
        @csrf

        <div class="container">

            <!-- ประเภทการขาย -->
            <div class="card">
                <h2>ประเภท</h2>

                <label class="option">
                    <input type="radio" name="type_serve" value="pawn"
                        {{ old('type_serve') == 'pawn' ? 'checked' : '' }} required> วาง
                </label>
                <label class="option">
                    <input type="radio" name="type_serve" value="extend"
                        {{ old('type_serve') == 'extend' ? 'checked' : '' }}> ต่อ
                </label>
                <label class="option">
                    <input type="radio" name="type_serve" value="redeem"
                        {{ old('type_serve') == 'redeem' ? 'checked' : '' }}> ไถ่
                </label>
                <label class="option">
                    <input type="radio" name="type_serve" value="other" id="type-serve-other"
                        {{ old('type_serve') == 'other' ? 'checked' : '' }}> อื่นๆ
                </label>

                <input class="input" name="others" id="type-serve-input" placeholder="กรอกประเภท"
                    value="{{ old('others') }}" {{ old('type_serve') == 'other' ? '' : 'disabled' }}>
            </div>

            <!-- ข้อมูลลูกค้า -->
            <div class="card">
                <h2>ข้อมูลลูกค้า</h2>

                <div class="form-row">
                    <label>ชื่อ-นามสกุล</label>
                    <input name="fullname" type="text" value="{{ old('fullname') }}">
                </div>

                <div class="form-row">
                    <label>บัตรประชาชน</label>
                    <input name="tax_number" type="number" value="{{ old('tax_number') }}">
                </div>

                <div class="form-row">
                    <label>เบอร์ติดต่อ</label>
                    <input name="phone" type="number" value="{{ old('phone') }}">
                </div>

                <div class="form-row">
                    <label>สถานะ</label>
                </div>
            </div>

            <!-- ประเภทสินค้า -->
            <div class="card">
                <h2>ประเภทสินค้า</h2>

                <label class="option">
                    <input type="radio" name="type_category" value="mobile"
                        {{ old('type_category') == 'mobile' ? 'checked' : '' }} required> มือถือ
                </label>
                <label class="option">
                    <input type="radio" name="type_category" value="tablet"
                        {{ old('type_category') == 'tablet' ? 'checked' : '' }}> Tablet
                </label>
                <label class="option">
                    <input type="radio" name="type_category" value="other" id="type-category-other"
                        {{ old('type_category') == 'other' ? 'checked' : '' }}> อื่นๆ
                </label>

                <input class="input" name="other" id="type-category-input" placeholder="กรอกประเภทสินค้า"
                    value="{{ old('description') }}" {{ old('type_category') == 'other' ? '' : 'disabled' }}>
            </div>

            <!-- ยี่ห้อ -->
            <div class="card">
                <h2>ยี่ห้อ</h2>

                @foreach (['iphone', 'samsung', 'oppo', 'vivo', 'huawei', 'realme',] as $brand)
                    <label class="option">
                        <input type="radio" name="brand" value="{{ $brand }}"
                            {{ old('brand') == $brand ? 'checked' : '' }} required>
                        {{ ucfirst($brand) }}
                    </label>
                @endforeach
                <label class="option">
                    <input type="radio" name="brand" value="other" id="brand-other"
                        {{ old('type_category') == 'other' ? 'checked' : '' }}> อื่นๆ
                </label>
                <input class="input" name="other" id="brand-other-input" placeholder="กรอกยี่ห้อ"
                    value="{{ old('description') }}" {{ old('type_category') == 'other' ? '' : 'disabled' }}>
            </div>

            <!-- รุ่น -->
            <div class="card">
                <h2>รุ่น</h2>
                <input class="input" name="model" type="text" value="{{ old('model') }}" required>
                <h2>รหัส IMEI</h2>
                <input class="input" name="imei" type="text" value="{{ old('imei') }}" required>
            </div>
            {{-- ราคา --}}
            <div class="card">
                <h2>ราคา</h2>
                <input class="input" name="price" type="number" value="{{ old('price') }}" required>
                <h2>หมายเหตุ</h2>
                <input class="input" name="note" type="text" value="{{ old('note') }}">
            </div>
            <div class="submit">
                <button type="button" onclick="window.location.href='{{ '' }}'">
                    ยกเลิก
                </button>

                <button type="submit" id="btn-submit">บันทึก</button>

            </div>
        </div>
    </form>

    <script src="{{ asset('js/app.js') }}" defer></script>

</body>

</html>

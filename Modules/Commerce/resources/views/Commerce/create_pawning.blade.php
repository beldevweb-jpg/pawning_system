<!DOCTYPE html>
<html lang="th">

<head>
    <meta charset="UTF-8">
    <title>โอ๋ โมบาย</title>
    <link rel="stylesheet" href="{{ asset('css/pawning.css') }}">
</head>

<body>
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

    <form method="POST" action="{{ route('commerce.store_pawning') }}">
        @csrf
        <h1 class="title">ขาย</h1>

        <div class="container">
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

            <div class="card">
                <h2>ประเภท</h2>
                <input type="hidden" name="sale_id" value="{{ $sale->id }}">

                <div class="radio-group">
                    <label class="option">
                        <input type="radio" name="subcategories" value="วาง"
                            {{ old('subcategories') == 'วาง' ? 'checked' : '' }}> วาง
                    </label>
                    <label class="option">
                        <input type="radio" name="subcategories" value="ต่อ"
                            {{ old('subcategories') == 'ต่อ' ? 'checked' : '' }}> ต่อ
                    </label>
                    <label class="option">
                        <input type="radio" name="subcategories" value="ไถ่"
                            {{ old('subcategories') == 'ไถ่' ? 'checked' : '' }}> ไถ่
                    </label>
                    <label class="option">
                        <input type="radio" id="pawn-other" name="subcategories" value="อื่นๆ"
                            {{ old('subcategories') == 'อื่นๆ' ? 'checked' : '' }}> อื่นๆ
                    </label>
                </div>

                <input class="input" id="typesell-other-input" name="subcategories_other"
                    value="{{ old('subcategories_other') }}" placeholder="กรอกประเภท" disabled>
            </div>

            <div class="card">
                <h2 class="section-title">ข้อมูลลูกค้า</h2>

                <div class="form-row">
                    <label>ชื่อ-นามสกุล</label>
                    <input value="{{ $member->fullname }}" readonly>
                </div>

                <div class="form-row">
                    <label>บัตรประชาชน</label>
                    <input value="{{ $member->tax_number }}" readonly>
                </div>

                <div class="form-row">
                    <label>เบอร์ติดต่อ</label>
                    <input value="{{ $member->phone }}" readonly>
                </div>
            </div>

            <div class="card">
                <h2>ประเภทสินค้า</h2>

                <div class="radio-group">
                    <label class="option">
                        <input type="radio" name="type_category" value="มือถือ" onchange="toggleModelField()"
                            {{ old('type_category') == 'มือถือ' ? 'checked' : '' }}> มือถือ
                    </label>
                    <label class="option">
                        <input type="radio" name="type_category" value="Tablet" onchange="toggleModelField()"
                            {{ old('type_category') == 'Tablet' ? 'checked' : '' }}> Tablet
                    </label>
                    <label class="option">
                        <input type="radio" id="type-other" name="type_category" value="อื่นๆ"
                            onchange="toggleModelField()" {{ old('type_category') == 'อื่นๆ' ? 'checked' : '' }}> อื่นๆ
                    </label>
                </div>

                <input class="input" id="type-other-input" name="type_category_other"
                    value="{{ old('type_category_other') }}" placeholder="กรอกประเภทสินค้า" disabled>
            </div>

            <div class="card" id="model-wrapper">
                <h2>ยี่ห้อ</h2>

                <div class="option-grid">
                    <label class="option"><input type="radio" name="brand" value="iphone"
                            {{ old('brand') == 'iphone' ? 'checked' : '' }}> iPhone</label>
                    <label class="option"><input type="radio" name="brand" value="Samsung"
                            {{ old('brand') == 'Samsung' ? 'checked' : '' }}> Samsung</label>
                    <label class="option"><input type="radio" name="brand" value="oppo"
                            {{ old('brand') == 'oppo' ? 'checked' : '' }}> OPPO</label>
                    <label class="option"><input type="radio" name="brand" value="huawei"
                            {{ old('brand') == 'huawei' ? 'checked' : '' }}> Huawei</label>
                    <label class="option"><input type="radio" name="brand" value="realme"
                            {{ old('brand') == 'realme' ? 'checked' : '' }}> realme</label>
                    <label class="option"><input type="radio" name="brand" value="vivo"
                            {{ old('brand') == 'vivo' ? 'checked' : '' }}> vivo</label>

                    <div class="option-other">
                        <label class="option">
                            <input type="radio" id="brand-other" name="brand" value="อื่นๆ"
                                {{ old('brand') == 'อื่นๆ' ? 'checked' : '' }}>
                            อื่นๆ
                        </label>
                        <input class="input" id="brand-other-input" name="brand_other"
                            value="{{ old('brand_other') }}" placeholder="กรอกยี่ห้อ" disabled>
                    </div>
                </div>
                <div class="option-other">
                    <h2>รุ่น</h2>
                    <input class="input" name="model" type="text" value="{{ old('model') }}"
                        placeholder="ระบุรุ่น">
                </div>
                <label style="display:block; margin-bottom:5px;">รหัสล็อคหน้าจอ</label>
                <input class="input" name="locker_pass+" type="text" value="{{ old('locker_pass+') }}"
                    placeholder="เช่น 123456">

                <label style="display:block; margin-bottom:5px;">วาดรหัส</label>
                <input class="input" name="locker_pass" type="text" value="{{ old('locker_pass') }}"
                    placeholder="เช่น วาดเป็นตัว L">
            </div>
            <div class="card">
                <h2>IMEL</h2>
                <label style="display:block; margin-bottom:5px;">IMEI</label>
                <input class="input" name="serial_number" type="text" value="{{ old('serial_number') }}"
                    required oninput="this.value = this.value.replace(/[^A-Za-z0-9]/g, '')"
                    placeholder="ภาษาอังกฤษและตัวเลขเท่านั้น">
            </div>
            <div class="card">
                <h2>ราคา</h2>
                <input class="input" name="price" type="number" value="{{ old('price') }}" required>

                <h2>ประเภทการชำระเงิน</h2>
                <div style="display: flex; gap: 20px; margin-bottom: 16px;">
                    <label class="option">
                        <input type="radio" name="type_price" value="เงินสด"
                            {{ old('type_price', 'เงินสด') == 'เงินสด' ? 'checked' : '' }}> เงินสด
                    </label>
                    <label class="option">
                        <input type="radio" name="type_price" value="เงินโอน"
                            {{ old('type_price') == 'เงินโอน' ? 'checked' : '' }}> เงินโอน
                    </label>
                </div>

                <h2>หมายเหตุ</h2>
                <input class="input" name="note" type="text" value="{{ old('note') }}">
            </div>
            <div class="card">
                <label>รูปภาพสินค้า</label>
                <input type="file" name="idcard_images[]" id="idcard-image" accept="image/*"
                    capture="environment" multiple>

                <div id="preview" style="display:flex; gap:10px; margin-top:10px;"></div>
            </div>

            <div class="submit">
                <button type="button" class="btn-cancel" onclick="history.back()">
                    ยกเลิก
                </button>

                <button type="submit" class="btn-submit" id="btn-submit">
                    ดำเนินการต่อ
                </button>
            </div>
        </div>
    </form>

    <script src="{{ asset('js/app.js') }}"></script>
</body>

</html>

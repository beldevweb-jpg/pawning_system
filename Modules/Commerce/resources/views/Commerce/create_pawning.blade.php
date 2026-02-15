<!DOCTYPE html>
<html lang="th">

<head>
    <meta charset="UTF-8">
    <title>โอ๋ โมบาย</title>
    <link rel="stylesheet" href="{{ asset('css/pawning.css') }}">
</head>

<body>
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

    <form method="POST" action="{{ route('commerce.store_pawning', ['id' => $member->member_id]) }}"
        enctype="multipart/form-data">
        @csrf
        <h1 class="title">ขาย</h1>
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
        <div class="container">
            <div class="card">
                <h2>ประเภท</h2>
                <div class="radio-group">
                    <label class="option">
                        <input type="radio" name="other_type" value="wand"
                            {{ old('other_type') == 'wand' ? 'checked' : '' }}> วาง
                    </label>

                    <a href="{{ route('commerce.dok') }}" class="btn-option">
                        ต่อ
                    </a>

                    <a href="{{ route('commerce.tai') }}" class="btn-option">
                        ไถ่
                    </a>

                    <a href="{{ route('commerce.pueam') }}" class="btn-option">
                        เพิ่ม
                    </a>
                    <label class="option">
                        <input type="radio" id="pawn-other" name="other_type" value="อื่นๆ"
                            {{ old('other_type') == 'อื่นๆ' ? 'checked' : '' }}> อื่นๆ
                    </label>
                </div>

                <input class="input" id="typesell-other-input" name="other_type_other"
                    value="{{ old('other_type_other') }}" placeholder="กรอกประเภท" disabled>
            </div>

            <div class="card">
                <h2 class="section-title">ข้อมูลลูกค้า</h2>
                <div class="form-row">
                    <label>ชื่อ-นามสกุล</label>
                    <input value="{{ $member->fullname }}">
                </div>

                <div class="form-row">
                    <label>บัตรประชาชน</label>
                    <input value="{{ $member->tax_number }}">
                </div>

                <div class="form-row">
                    <label>เบอร์ติดต่อ</label>
                    <input value="{{ $member->phone }}">
                </div>
            </div>

            <div class="card">
                <h2>ประเภทสินค้า</h2>

                <div class="radio-group">
                    <label class="option">
                        <input type="radio" name="type_category" value="phone" onchange="toggleModelField()"
                            {{ $sale->type_category == 'phone' ? 'checked' : '' }}>
                        มือถือ
                    </label>
                    <label class="option">
                        <input type="radio" name="type_category" value="Tablet" onchange="toggleModelField()"
                            {{ $sale->type_category == 'Tablet' ? 'checked' : '' }}> Tablet
                    </label>
                    <label class="option">
                        <input type="radio" id="type-other" name="type_category" value="other"
                            onchange="toggleModelField()" {{ $sale->type_category == 'other' ? 'checked' : '' }}> อื่นๆ
                    </label>
                </div>

                <input class="input" id="type-other-input" name="other_type" value="{{ old('other_type') }}"
                    placeholder="กรอกประเภทสินค้า" disabled>
            </div>

            @php
                $selectedBrand = old('brand', $sale->brand ?? '');
            @endphp


            <div class="card" id="model-wrapper">
                <h2>ยี่ห้อ</h2>
                <div class="option-grid">
                    <label class="option">
                        <input type="radio" name="brand" value="iPhone"
                            {{ $selectedBrand == 'iPhone' ? 'checked' : '' }}>
                        iPhone
                    </label>

                    <label class="option">
                        <input type="radio" name="brand" value="Samsung"
                            {{ $selectedBrand == 'Samsung' ? 'checked' : '' }}>
                        Samsung
                    </label>

                    <label class="option">
                        <input type="radio" name="brand" value="oppo"
                            {{ $selectedBrand == 'oppo' ? 'checked' : '' }}>
                        OPPO
                    </label>

                    <label class="option">
                        <input type="radio" name="brand" value="huawei"
                            {{ $selectedBrand == 'huawei' ? 'checked' : '' }}>
                        Huawei
                    </label>

                    <label class="option">
                        <input type="radio" name="brand" value="realme"
                            {{ $selectedBrand == 'realme' ? 'checked' : '' }}>
                        realme
                    </label>

                    <label class="option">
                        <input type="radio" name="brand" value="vivo"
                            {{ $selectedBrand == 'vivo' ? 'checked' : '' }}>
                        vivo
                    </label>

                    <div class="option-other">
                        <label class="option">
                            <input type="radio" id="brand-other" name="brand" value="other"
                                {{ $selectedBrand == 'other' ? 'checked' : '' }}>
                            อื่นๆ
                        </label>

                        <input class="input" id="brand-other-input" name="other_brand"
                            value="{{ old('other_brand') }}" placeholder="กรอกยี่ห้อ">
                    </div>
                </div>

                <div class="option-other">
                    <h2>รุ่น</h2>
                    <input class="input" name="model" type="text" value="{{ old('model') }}"
                        placeholder="ระบุรุ่น">
                </div>
                <label style="display:block; margin-bottom:5px;">รหัสล็อคหน้าจอ</label>
                <input class="input" name="locker_pass" type="text" value="{{ old('locker_pass') }}"
                    placeholder="เช่น 123456">

                <label style="display:block; margin-bottom:5px;">วาดรหัส</label>
                <input class="input" name="drawn_lock" type="text" value="{{ old('drawn_lock') }}"
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
                <h2>การชำระเงิน</h2>

                <label>เงินสด</label>
                <input type="number" name="cash" value="{{ old('cash') }}" placeholder="0">

                <label>เงินโอน</label>
                <input type="number" name="transfer" value="{{ old('transfer') }}" placeholder="0">

                <h2>หมายเหตุ</h2>
                <input class="input" name="note" type="text" value="{{ old('note') }}">
            </div>

            <div class="card">
                <div class="form-row">
                    <label>วันที่นัดรับเครื่อง</label>
                    <input class="input" name="appointment_date" type="date"
                        value="{{ old('appointment_date', isset($sale) ? optional($sale->appointment_date)->format('Y-m-d') : '') }}">
                </div>
                <div class="form-row">
                    <label>รูปภาพสินค้า</label>
                    <input type="file" name="product_images[]" id="product-image" accept="image/*"
                        capture="environment" multiple>
                    <div id="preview" style="display:flex; gap:10px; margin-top:10px;"></div>
                </div>
                <div class="form-row">
                    <label>รูปภาพใบเสร็จ</label>
                    <input type="file" name="bill" id="bill-image" accept="image/*" capture="environment"
                        multiple>
                    <div id="preview-bill" style="display:flex; gap:10px; margin-top:10px;"></div>
                </div>
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

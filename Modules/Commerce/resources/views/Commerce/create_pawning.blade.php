<!DOCTYPE html>
<html lang="th">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>โอ๋ โมบาย</title>
    <link rel="stylesheet" href="{{ asset('css/pawning.css') }}">
</head>
@php
    $mode = request('mode', 'create'); // รับ query string 'mode'
    $isView = $mode === 'view';
@endphp

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
    <fieldset {{ $isView ? 'disabled' : '' }}>

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
                @if ($sale_between)
                    <div class="card">
                        <h2>ประเภท</h2>

                        <div class="action-vertical">

                            <!-- วาง -->
                            <label class="option">
                                <input type="radio" name="action_type" value="wand"
                                    {{ old('action_type', $sale->action_type ?? '') == 'wand' ? 'checked' : '' }}>
                                วาง
                            </label>

                            <!-- ต่อ -->
                            @if ($isView)
                                <a href="{{ route('commerce.dok', $sale->id) }}" class="btn-option yellow">ต่อ</a>
                            @endif

                            <!-- ไถ่ -->
                            @if ($isView)
                                <a href="{{ route('commerce.tai', $sale->id) }}" class="btn-option green">ไถ่</a>
                            @endif

                            <!-- เพิ่ม -->
                            @if ($isView)
                                <a href="{{ route('commerce.pueam', $sale->id) }}" class="btn-option blue">เพิ่ม</a>
                            @endif

                            <!-- อื่นๆ -->
                            <label class="option">
                                <input type="radio" id="action-other" name="action_type"
                                    {{ old('action_type', $sale->action_type ?? '') == 'other' ? 'checked' : '' }}>
                                อื่นๆ
                            </label>
                            <input class="input" id="action-other-input" name="action_type_other"
                                value="{{ old('action_type_other') }}" placeholder="กรอกประเภท" disabled>
                        </div>
                    </div>
                @endif

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
                                onchange="toggleModelField()" {{ $sale->type_category == 'other' ? 'checked' : '' }}>
                            อื่นๆ
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
                        @php
                            $selectedBrand = old('brand', $sale->brand ?? ($sale->other_brand ? 'other' : ''));
                        @endphp

                        <div class="option-other">
                            <label class="option">
                                <input type="radio" id="brand-other" name="brand" value="other"
                                    {{ $selectedBrand == 'other' ? 'checked' : '' }}>
                                อื่นๆ
                            </label>

                            <input class="input" id="brand-other-input" name="other_brand"
                                value="{{ old('other_brand', $sale->other_brand ?? '') }}" placeholder="กรอกยี่ห้อ">
                        </div>
                    </div>

                    <div class="option-other">
                        <h2>รุ่น</h2>
                        <input class="input" name="model" type="text"
                            value="{{ old('model', $sale->model ?? '') }}" placeholder="ระบุรุ่น">
                    </div>
                    <label style="display:block; margin-bottom:5px;">รหัสล็อคหน้าจอ</label>
                    <input class="input" name="locker_pass" type="text"
                        value="{{ old('locker_pass', $sale->locker_pass ?? '') }}" placeholder="เช่น 123456">

                    <label style="display:block; margin-bottom:5px;">วาดรหัส</label>
                    <input class="input" name="drawn_lock" type="text"
                        value="{{ old('drawn_lock', $sale->drawn_lock ?? '') }}" placeholder="เช่น วาดเป็นตัว L">
                </div>
                <div class="card">
                    <h2>IMEL</h2>
                    <label style="display:block; margin-bottom:5px;">IMEI</label>
                    <input class="input" name="serial_number" type="text"
                        value="{{ old('serial_number', $sale->serial_number ?? '') }}" required
                        oninput="this.value = this.value.replace(/[^A-Za-z0-9]/g, '')"
                        placeholder="ภาษาอังกฤษและตัวเลขเท่านั้น">
                </div>
                <div class="card">
                    <h2>การชำระเงิน</h2>

                    <label>เงินสด</label>
                    <input class="input" type="number" name="cash" value="{{ old('cash', $sale->cash) }}"
                        placeholder="0" step="0.01" min="0">
                    <label>เงินโอน</label>
                    <input class="input" type="number" name="transfer"
                        value="{{ old('transfer', $sale->transfer) }}" placeholder="0" step="0.01"
                        min="0">
                    <h2>หมายเหตุ</h2>
                    <input class="input" name="note" type="text"
                        value="{{ old('note', $sale->note ?? '') }}">
                </div>
                <div class="card">
                    <div class="form-row">
                        <label>วันที่นัดรับเครื่อง</label>
                        <input class="input" name="appointment_date" type="date"
                            value="{{ old('appointment_date', $sale->appointment_date?->format('Y-m-d')) }}">
                    </div>
                    <div class="form-row">
                        <label>รูปบัตรประชาชน</label>
                        <input type="file" name="product_images" accept="image/*" capture="environment">

                        <div style="margin-top:10px;">
                            @if (!empty($sale->product_images))
                                <img src="{{ asset('storage/' . $sale->product_images) }}"
                                    style="width:80px; height:80px; object-fit:cover; border-radius:8px;">
                            @else
                                <span>ไม่มีรูป</span>
                            @endif
                        </div>
                    </div>

                    <div class="form-row">
                        <label>รูปภาพคนถือสินค้า</label>
                        <input type="file" name="product_images_behind" accept="image/*" capture="environment">

                        <div style="margin-top:10px;">
                            @if (!empty($sale->product_images_behind))
                                <img src="{{ asset('storage/' . $sale->product_images_behind) }}"
                                    style="width:80px; height:80px; object-fit:cover; border-radius:8px;">
                            @else
                                <span>ไม่มีรูป</span>
                            @endif
                        </div>
                    </div>
                    <div class="form-row">
                        <label>รูปภาพใบเสร็จ</label>
                        <input type="file" name="bill" id="bill-image" accept="image/*"
                            capture="environment">
                        <div id="preview-bill" style="display:flex; gap:10px; margin-top:10px;">
                            @if (!empty($sale->bill))
                                <img src="{{ asset('storage/' . $sale->bill) }}"
                                    style="width:80px; height:80px; object-fit:cover; border-radius:8px;">
                            @endif
                        </div>
                    </div>
                    {{-- <div class="form-row">
                        <label>ใบเสร็จหน้าร้าน</label>
                        <input type="file" name="bill_QR_store" id="bill_QR_store-image" accept="image/*"
                            capture="environment">
                        <div id="preview-bill_QR_store" style="display:flex; gap:10px; margin-top:10px;">
                            @if (!empty($sale->bill_QR_store))
                                <img src="{{ asset('storage/' . $sale->bill_QR_store) }}"
                                    style="width:80px; height:80px; object-fit:cover; border-radius:8px;">
                            @endif
                        </div>
                    </div> --}}
                </div>
                @if (!$isView)
                    <div class="submit">
                        <button type="button" class="btn-cancel" onclick="history.back()">
                            ยกเลิก
                        </button>
                        <button type="submit" class="btn-submit" id="btn-submit">
                            ดำเนินการต่อ
                        </button>
                    </div>
                @endif
            </div>
        </form>
    </fieldset>

    <script src="{{ asset('js/app.js') }}"></script>
</body>

</html>

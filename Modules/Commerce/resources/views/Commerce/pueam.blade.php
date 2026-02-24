<!DOCTYPE html>
<html lang="th">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ทำรายการ</title>
    <link rel="stylesheet" href="{{ asset('css/tai.css') }}">
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

        <form method="POST" action="{{ route('commerce.pueam_store', $sale->id) }}" enctype="multipart/form-data">
            @csrf

            <div class="card">
                <h2 class="center-title">เพื่ม</h2>
                <!-- รูป -->
                <div class="image-grid">
                    @if (!empty($sale->product_images))
                        @foreach (array_slice(json_decode($sale->product_images), 0, 3) as $img)
                            <div class ="image-box">
                                <img src="{{ asset('storage/' . $img) }}"
                                    style="width:100%; height:100%; object-fit:cover; border-radius:8px;">
                            </div>
                        @endforeach
                    @endif
                </div>

                <!-- เงิน -->
                <div class="grid-2">
                    <div id="paymentWarning" style="color:red; display:none; font-weight:600;">
                        ยอดชำระไม่ตรงกับ "รวมต้องจ่าย"
                    </div>
                    <div class="form-group">
                        <div class="form-group">
                            <label>รายการเพิ่ม</label>
                            <input id="listInput" name="list_pueam" class="input" type="text" inputmode="numeric">
                        </div>
                        <label>เงินต้น</label>
                        <input id="principalInput" name="principal" class="input"
                            value="{{ number_format($totalPrice ?? ($principal ?? 0)) }}" readonly>
                    </div>
                    <!-- หมายเหตุ -->
                    <div id="noteSection" class="form-row column" style="display:none;">
                        <label>หมายเหตุ</label>
                        <textarea class="textarea" placeholder="(แสดงหลังตัดแก้ไข)"></textarea>
                    </div>
                </div>
            </div>

            <!-- การชำระเงิน -->
            <h3 class="section-title">การชำระเงิน</h3>
            <div class="grid-2">
                <div class="form-group">
                    <label>เงินโอน</label>
                    <input class="input" name="transfer" id="transferInput" type="text" inputmode="numeric"
                        oninput="checkNegative(this)">

                </div>

                <div class="form-group">
                    <label>เงินสด</label>
                    <input class="input" name="cash" id="cashInput" type="text" inputmode="numeric">
                </div>
            </div>
            <div class="grid-2">
                <div class="form-group">
                    <label>แนบสลิป</label>

                    <input type="file" class="file-input" accept="image/*" name="slip">
                </div>

                <div class="form-group">
                    <label>ใครเพิ่ม/สินค้าที่เพิ่ม</label>
                    <input type="file" name="added_by_image[]" accept="image/*" multiple class="file-input">
                </div>
            </div>

            <div class="submit">
                <button type="button" class="btn cancel" id="clearForm">
                    ยกเลิก
                </button>

                <button type="submit" class="btn confirm">
                    ดำเนินการต่อ
                </button>
            </div>
        </form>
    </div>
    </div>
</body>

</html>

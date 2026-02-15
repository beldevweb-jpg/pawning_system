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

        <form method="POST" action="{{ route('commerce.pueam.store') }}" enctype="multipart/form-data">
            @csrf

            <div class="card">

                <!-- menu tabs -->
                <div class="menu-tabs">
                    <button type="button" class="tab">จัดการข้อมูลหน้าร้าน</button>
                    <button type="button" class="tab">จัดการข้อมูลลูกค้า</button>
                    <button type="button" class="tab">จัดการขาย</button>
                </div>

                <h2 class="center-title">เพิ่ม</h2>

                <!-- รูป -->
                <div class="image-grid">
                    <div class="image-box">+</div>
                    <div class="image-box">+</div>
                    <div class="image-box">+</div>
                </div>

                <!-- เงิน -->
                <div class="two-column">
                    <div>
                        <label>เงินต้น</label>
                        <input class="input" value="(แก้ไม่ได้)" disabled>
                    </div>

                    <div>
                        <label>ดอก</label>
                        <div class="plus-line">
                            <span>+</span>
                            <input name="interest" class="input small" type="number">
                        </div>
                    </div>
                </div>

                <!-- รายการเพิ่ม -->
                <div class="form-row column">
                    <label>รายการเพิ่ม</label>
                    <input name="detail" class="input">
                </div>

                <!-- การชำระเงิน -->
                <h3 class="section-title">การชำระเงิน</h3>

                <div class="payment-row">
                    <label>เงินโอน</label>
                    <input name="transfer" class="input small" type="number">

                    <label>เงินสด</label>
                    <input name="cash" class="input small" type="number">
                </div>

                <div class="form-row column">
                    <label>สลิป</label>
                    <input name="slip" type="file" class="file-input" accept="image/*">
                </div>

            </div>

            <!-- ปุ่ม -->
            <div class="submit">
                <button type="button" class="danger" onclick="history.back()">ยกเลิก</button>
                <button type="submit" class="primary">บันทึก</button>
            </div>
        </form>
    </div>

</body>

</html>

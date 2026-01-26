<!DOCTYPE html>
<html lang="th">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ขาย</title>
    <link rel="stylesheet" href="{{ asset('css/pawning.css') }}">
</head>

<body>

    <header class="header">
        <div class="user">
            <span class="avatar">👤</span>
            <span>username</span>
        </div>
        <button class="logout">ออกจากระบบ</button>
    </header>

    <h1 class="title">ยืนยันข้อมูล</h1>

    <div class="container">

        <!-- ประเภท -->
        <div class="card">
            <h2>ประเภท</h2>

            <label class="option"><input type="radio" name="selltype" disabled> วาง</label>
            <label class="option"><input type="radio" name="selltype" disabled> ต่อ</label>
            <label class="option"><input type="radio" name="selltype" disabled> ไถ่</label>
            <label class="option"><input type="radio" name="selltype" disabled> อื่นๆ</label>

            <input class="input" placeholder="กรอกประเภท" readonly>
        </div>

        <!-- ข้อมูลลูกค้า -->
        <div class="card">
            <h2 class="section-title">ข้อมูลลูกค้า</h2>

            <div class="form-row">
                <label>หมายเลขเครื่อง</label>
                <input readonly>
            </div>

            <div class="form-row">
                <label>ชื่อ-นามสกุล</label>
                <input readonly>
            </div>

            <div class="form-row">
                <label>บัตรประชาชน</label>
                <input readonly>
            </div>

            <div class="form-row">
                <label>เบอร์ติดต่อ</label>
                <input readonly>
            </div>

            <h5>สถานะ</h5>
        </div>

        <!-- ประเภทสินค้า -->
        <div class="card">
            <h2>ประเภทสินค้า</h2>

            <label class="option"><input type="radio" name="product_type" disabled> มือถือ</label>
            <label class="option"><input type="radio" name="product_type" disabled> Tablet</label>
            <label class="option"><input type="radio" name="product_type" disabled> อื่นๆ</label>

            <input class="input" placeholder="กรอกประเภทสินค้า" readonly>
        </div>

        <!-- ยี่ห้อ -->
        <div class="card">
            <h2>ยี่ห้อ</h2>

            <div class="option-grid">
                <label class="option"><input type="radio" name="brand" disabled> iPhone</label>
                <label class="option"><input type="radio" name="brand" disabled> Samsung</label>
                <label class="option"><input type="radio" name="brand" disabled> OPPO</label>

                <label class="option"><input type="radio" name="brand" disabled> Huawei</label>
                <label class="option"><input type="radio" name="brand" disabled> realme</label>
                <label class="option"><input type="radio" name="brand" disabled> vivo</label>

                <div class="option-other">
                    <label class="option">
                    <input type="radio" name="brand" disabled>
                    อื่นๆ
                </label>
                    <input class="input" placeholder="กรอกยี่ห้อ" readonly>
                </div>
            </div>

            <div class="submit">
                <button onclick="goPage('index.html')">ยกเลิก</button>
                <button>ดำเนินการต่อ</button>
            </div>
        </div>

    </div>

</body>

</html>
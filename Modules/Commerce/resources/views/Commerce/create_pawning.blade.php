<!DOCTYPE html>
<html lang="th">

<head>
    <meta charset="UTF-8">
    <title>โอ๋ โมบาย</title>
    <link rel="stylesheet" href="{{ asset('css/pawning.css') }}">
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

    <h1 class="title">ขาย</h1>

    <div class="container">

        <div class="card">
            <h2>ประเภท</h2>

            <label class="option"><input type="radio" name="selltype"> วาง</label>
            <label class="option"><input type="radio" name="selltype"> ต่อ</label>
            <label class="option"><input type="radio" name="selltype"> ไถ่</label>
            <label class="option"><input type="radio" id="pawn-other" name="selltype"> อื่นๆ</label>

            <input class="input" id="typesell-other-input" placeholder="กรอกประเภท" disabled>
        </div>

        <div class="card">
            <h2 class="section-title">ข้อมูลลูกค้า</h2>

            <div class="form-row">
                <label>หมายเลขเครื่อง</label>
                <input>
            </div>

            <div class="form-row">
                <label>ชื่อ-นามสกุล</label>
                <input>
            </div>

            <div class="form-row">
                <label>บัตรประชาชน</label>
                <input>
            </div>

            <div class="form-row">
                <label>เบอร์ติดต่อ</label>
                <input>
            </div>
                <h5>ประเภทสินค้า</h5>
        </div>

        <div class="card">
            <h2>ประเภทสินค้า</h2>

            <label class="option"><input type="radio" name="product_type"> มือถือ</label>
            <label class="option"><input type="radio" name="product_type"> Tablet</label>
            <label class="option"><input type="radio" id="type-other" name="product_type"> อื่นๆ</label>

            <input class="input" id="type-other-input" placeholder="กรอกประเภทสินค้า" disabled>
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
                        <input type="radio" id="brand-other" name="brand">
                        อื่นๆ
                    </label>
                    <input class="input" id="brand-other-input" placeholder="กรอกยี่ห้อ" disabled>
                </div>
            </div>

            <div class="submit">
                
                <button name="cancel" onclick="goPage('index.html')">ยกเลิก</button>
                <button name="next">ดำเนินการต่อ</button>
            </div>

        </div>
</body>

</html>

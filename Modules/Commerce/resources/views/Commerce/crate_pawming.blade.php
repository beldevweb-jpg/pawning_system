<!DOCTYPE html>
<html lang="th">
<head>
  <meta charset="UTF-8">
  <title>โอ๋ โมบาย</title>
  <link rel="stylesheet" href="pawning.css">
  <script src="app.js" defer></script> 
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

  <div class="card">
    <h2>ประเภท</h2>

    <label class="option">
      <input type="radio" name="selltype" value="pawn">
      วาง
    </label>

    <label class="option">
      <input type="radio" name="selltype" value="continue">
      ต่อ
    </label>

    <label class="option">
      <input type="radio" name="selltype" value="Redeem">
      ไถ่
    </label>

    <label class="option">
      <input type="radio" name="selltype" value="other" id="pawn-other">
      อื่นๆ
    </label>

    <input
      type="text"
      id="typesell-other-input"
      class="input"
      placeholder="กรอกประเภทสินค้า"
      disabled
    >
    </div>  

  <!-- CUSTOMER FORM -->  
  <div class="card">
    <h2 class="section-title">ข้อมูลลูกค้า</h2>

    <div class="form-row">
      <label>หมายเลขเครื่อง</label>
      <input type="text" name="serial-number" placeholder="กรอกข้อมูล">
    </div>

    <div class="form-row">
      <label>ชื่อ-นามสกุล</label>
      <input type="text" name="name" placeholder="กรอกข้อมูล">
    </div>

    <div class="form-row">
      <label>บัตรประชาชน</label>
      <input type="text"  name="tax-number" placeholder="กรอกข้อมูล">
    </div>

    <div class="form-row">
      <label>เบอร์ติดต่อ</label>
      <input type="text" name="phone" placeholder="กรอกข้อมูล">
    </div>
  </div>

<div class="container">

  <!-- ประเภทสินค้า -->
  <div class="card">
    <h2>ประเภทสินค้า</h2>

    <label class="option">
      <input type="radio" name="product_type" value="mobile">
      มือถือ
    </label>

    <label class="option">
      <input type="radio" name="product_type" value="tablet">
      Tablet
    </label>

    <label class="option">
      <input type="radio" name="product_type" value="other" id="type-other">
      อื่นๆ
    </label>

    <input
      type="text"
      id="type-other-input"
      class="input"
      placeholder="กรอกประเภทสินค้า"
      disabled
    >
  </div>

  <!-- ยี่ห้อ -->
  <div class="card">
    <h2>ยี่ห้อ</h2>

    <label class="option">
      <input type="radio" name="brand" value="iphone">
      iPhone
    </label>

    <label class="option">
      <input type="radio" name="brand" value="samsung">
      Samsung
    </label>

    <label class="option">
      <input type="radio" name="brand" value="oppo">
      OPPO
    </label>

    <label class="option">
      <input type="radio" name="brand" value="huawei">
      huawei
    </label>

    <label class="option">
      <input type="radio" name="brand" value="realme">
      realme
    </label>

    <label class="option">
      <input type="radio" name="brand" value="vivo">
      vivo
    </label>

    <label class="option">
      <input type="radio" name="brand" value="other" id="brand-other">
      อื่นๆ
    </label>

    <input  
      type="text"
      id="brand-other-input"
      class="input"
      placeholder="กรอกยี่ห้อ"
      disabled
    >
  </div>

  <div class="submit">
    <button name="cancel" type="button" onclick="goPage('index.html')">ยกเลิก</button>
    <button name="next" type="button" onclick="goPage('report.html')">ดำเนินการต่อ</button>
  </div>

</div>

</body>
</html>

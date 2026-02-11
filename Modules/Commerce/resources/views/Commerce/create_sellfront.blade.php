<!DOCTYPE html>
<html lang="th">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>ขายหน้าร้าน</title>
<link rel="stylesheet" href="sell-front.css">
</head>
<body>

<div class="header">
  <div>username</div>
  <div class="logout">log out</div>
</div>

<h1 class="title">ขายหน้าร้าน</h1>

<div class="container">

  <div class="card">

    <div class="form-row column">
      <label>สินค้า</label>
      <input type="text" class="input">
    </div>

    <div class="form-row column">
      <label>ราคา</label>
      <input type="number" class="input">
    </div>

    <div class="form-row column">
      <label>เงินสด</label>
      <input type="number" class="input" placeholder="จำนวนเงินสด">
    </div>
    <div class="form-row column">
      <label>เงินโอน</label>
      <input type="number" class="input" placeholder="จำนวนเงินโอน">
    </div>

    <div class="form-row column">
      <label>สลิปโอน</label>
      <input type="file" class="input">
    </div>

    <div class="option">
      <label><input type="radio" name="type"> รับ</label>
      <label><input type="radio" name="type"> จ่าย</label>
    </div>

  </div>

  <div class="submit">
    <button name="cancel" class="danger">ยกเลิก</button>
    <button name="next" class="primary">บันทึก</button>
  </div>

</div>

</body>
</html>
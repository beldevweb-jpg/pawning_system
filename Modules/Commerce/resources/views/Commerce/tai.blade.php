<!DOCTYPE html>
<html lang="th">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>ทำรายการ</title>
<link rel="stylesheet" href="tai.css">
</head>
<body>

<div class="header">
  <div>username</div>
  <div class="logout">log out</div>
</div>

<div class="container">

  <div class="card">

    <!-- menu tabs -->
    <div class="menu-tabs">
      <button class="tab">จัดการข้อมูลหน้าร้าน</button>
      <button class="tab">จัดการข้อมูลลูกค้า</button>
      <button class="tab">จัดการขาย</button>
    </div>

    <!-- title -->
    <h2 class="center-title">ไถ่</h2>

    <!-- รูป 3 ช่อง -->
    <div class="image-grid">
      <div class="image-box">รูป</div>
      <div class="image-box">รูป</div>
      <div class="image-box">รูป</div>
    </div>

    <!-- เงินต้น -->
    <div class="two-column">
      <div>
        <label>เงินต้น</label>
        <input class="input" value="(แก้ไม่ได้)" disabled>
      </div>

      <div>
  <label>ดอก</label>
  <div class="plus-line">
    <span>+</span>
    <input id="interestInput" 
           class="input small" 
           placeholder="(กดแก้ไขจำนวนเงินก่อนแก้ไข)"
           disabled>
  </div>
  <small class="edit-link" onclick="toggleEdit()">แก้ไข</small>

</div>

    </div>

    <!-- รวม -->
    <div class="form-row column">
      <label>รวม</label>
      <input class="input" disabled>
    </div>

    <!-- หมายเหตุ -->
<div id="noteSection" class="form-row column" style="display:none;">
  <label>หมายเหตุ</label>
  <textarea class="textarea" placeholder="(แสดงหลังตัดแก้ไข)"></textarea>
</div>


    <!-- การชำระเงิน -->
    <h3 class="section-title">การชำระเงิน</h3>

    <div class="payment-row">
      <label>เงินโอน</label>
      <input class="input small">

      <label>เงินสด</label>
      <input class="input small">
    </div>

   <div class="form-row column">
  <label>สลิป</label>
  <input type="file" class="file-input" accept="image/*">
</div>

  </div>

  <!-- ปุ่ม -->
  <div class="submit">
    <button class="danger">ยกเลิก</button>
    <button class="primary">บันทึก</button>
  </div>

</div>
<script>
function toggleEdit() {
  const input = document.getElementById("interestInput");
  const note = document.getElementById("noteSection");

  // สลับสถานะ disabled
  input.disabled = !input.disabled;

  if (!input.disabled) {
    input.focus();
    note.style.display = "flex";   // แสดงหมายเหตุ
  } else {
    note.style.display = "none";   // ซ่อนหมายเหตุ
  }
}
</script>

</script>

</body>
</html>

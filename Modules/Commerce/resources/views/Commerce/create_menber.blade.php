<!DOCTYPE html>
<html lang="th">
<head>
  <meta charset="UTF-8">
  <title>เพิ่มข้อมูลลูกค้า</title>
    <link rel="stylesheet" href="{{ asset('css/add-member.css') }}">
    <link rel="stylesheet" href="{{ asset('css/error.css') }}">
</head>
<body>

  <!-- HEADER -->
  <header class="header">
    <div class="user">
      <span class="avatar">👤</span>
      <span>username</span>
    </div>
    <button class="logout">ออกจากระบบ</button>
  </header>

  <!-- TITLE -->
  <h1 class="title">เพิ่มข้อมูลลูกค้า</h1>

  <!-- FORM CARD -->
  <div class="card">
    <div class="form-row">
      <label>เลขบัตรประชาชน</label>
      <input type="text" id="idcard" placeholder="กรอกเลขบัตรประชาชน">
    </div>

    <div class="form-row">
      <label>ชื่อ - นามสกุล</label>
      <input type="text" id="fullname" placeholder="กรอกชื่อ - นามสกุล">
    </div>

    <div class="form-row">
      <label>เบอร์โทรศัพท์</label>
      <input type="text" id="phone" placeholder="กรอกเบอร์โทรศัพท์">
    </div>

    <div class="form-row">
      <label>รูปบัตรประชาชน</label>
      <input type="file" id="idcard-image" accept="image/*">
    </div>

    <!-- ACTION BUTTONS -->
    <div class="action-buttons">
      <button class="btn cancel" id="clearForm">ยกเลิก</button>
      <button class="btn confirm">ดำเนินการต่อ</button>
    </div>
  </div>
    <script src="{{ asset('js/app.js') }}" defer></script>
</body>
</html>

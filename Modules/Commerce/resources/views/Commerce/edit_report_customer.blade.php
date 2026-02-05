<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit User</title>
    <link rel="stylesheet" href="edit.css">
</head>
<body>

<div class="container">
    <div class="card">

        <!-- HEADER -->
        <div class="page-header">
            <h2>แก้ไขข้อมูล</h2>
            <span class="subtitle">Edit User / Sale Report</span>
        </div>

        <!-- FORM -->
        <form class="edit-form">
            <div class="form-group">
                <label>รหัสรายการ</label>
                <input type="text" value="00123" disabled>
            </div>

            <div class="form-group">
                <label>ชื่อรายการ</label>
                <input type="text" value="Notebook Lenovo">
            </div>

            <div class="form-group">
                <label>ชื่อพนักงานขาย</label>
                <input type="text" value="Admin">
            </div>

            <div class="form-group">
                <label>ชื่อลูกค้า</label>
                <input type="text" value="Pao">
            </div>

            <div class="form-group">
                <label>สถานะการขาย</label>
                <select>
                    <option selected>ปกติ</option>
                    <option>รอดำเนินการ</option>
                    <option>ยกเลิก</option>
                </select>
            </div>

            <div class="form-group">
                <label>ราคา (บาท)</label>
                <input type="number" value="12500">
            </div>

            <!-- ACTION -->
            <div class="form-action">
                <a href="/report" class="btn-cancel">ยกเลิก</a>
                <button type="submit" class="btn-save">บันทึก</button>
            </div>
        </form>

    </div>
</div>

</body>
</html>

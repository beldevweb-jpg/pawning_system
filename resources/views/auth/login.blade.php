<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link rel="stylesheet" href="login.css">
</head>
<body>

<div class="container">
    <div class="card">

        <!-- HEADER -->
        <div class="page-header">
            <h2>เข้าสู่ระบบ</h2>
            <span class="subtitle">User Login</span>
        </div>

        <!-- FORM -->
        <form class="login-form">

            <div class="form-group">
                <label>ชื่อผู้ใช้</label>
                <input type="text" placeholder="Username" required>
            </div>

            <div class="form-group">
                <label>รหัสผ่าน</label>
                <input type="password" placeholder="Password" required>
            </div>

            <!-- ACTION -->
            <div class="form-action">
                <button type="submit" class="btn-login">เข้าสู่ระบบ</button>
            </div>

        </form>

    </div>
</div>

</body>
</html>

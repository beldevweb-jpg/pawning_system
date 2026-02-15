<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register User</title>
    <link rel="stylesheet" href="register.css">
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
        <div class="card">

            <!-- HEADER -->
            <div class="page-header">
                <h2>สมัครผู้ใช้งาน</h2>
                <span class="subtitle">User Registration</span>
            </div>
            <!-- FORM -->
            <form class="register-form">

                <div class="form-group">
                    <label>ชื่อนามสกุล</label>
                    <input type="text" placeholder="กรอกชื่อนามสกุล" required>
                </div>

                <div class="form-group">
                    <label>ชื่อผู้ใช้ (Username)</label>
                    <input type="text" placeholder="กรอกชื่อผู้ใช้" required>
                </div>



                <div class="form-group">
                    <label>รหัสผ่าน</label>
                    <input type="password" placeholder="อย่างน้อย 8 ตัวอักษร" required>
                </div>

                <div class="form-group">
                    <label>ยืนยันรหัสผ่าน</label>
                    <input type="password" placeholder="กรอกรหัสผ่านอีกครั้ง" required>
                </div>

                <div class="form-group">
                    <label>บทบาทผู้ใช้</label>
                    <select required>
                        <option value="">-- เลือกบทบาท --</option>
                        <option>Admin</option>
                        <option>Staff</option>
                        <option>User</option>
                    </select>
                </div>

                <div class="form-group">
                    <label>สถานะผู้ใช้</label>
                    <select required>
                        <option>ใช้งาน</option>
                        <option>ปิดการใช้งาน</option>
                    </select>
                </div>

                <!-- ACTION -->
                <div class="form-action">
                    <a href="/login" class="btn-cancel">ยกเลิก</a>
                    <button type="submit" class="btn-save">สมัครสมาชิก</button>
                </div>

            </form>

        </div>
    </div>

</body>

</html>

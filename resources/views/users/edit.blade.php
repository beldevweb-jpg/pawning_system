<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register User</title>
    <link rel="stylesheet" href="{{ asset('css/register.css') }}">
</head>

<body>

    <div class="container">
        <div class="card">
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
            <h2>สมัครผู้ใช้งาน</h2>

            <!-- FORM -->
            <form method="POST" action="{{ route('register') }}" class="register-form">
                @csrf
                <div class="form-group">
                    <label>ชื่อนามสกุล</label>
                    <input type="text" name="name" placeholder="กรอกชื่อนามสกุล"
                        value="{{ old('name', $user->name ?? '') }}" required>
                </div>

                <div class="form-group">
                    <label>เบอร์โทรศัพท์</label>
                    <input type="phone" name="phone" placeholder="เบอร์โทรศัพท์"
                        value="{{ old('phone', $user->phone ?? '') }}">
                </div>

                <div class="form-group">
                    <label>ชื่อผู้ใช้ (Username)</label>
                    <input type="text" name="username" placeholder="กรอกชื่อผู้ใช้"
                        value="{{ old('username', $user->username ?? '') }}" required>
                </div>


                <div class="form-group">
                    <label>รหัสผ่าน</label>
                    <input type="password" name="password" placeholder="อย่างน้อย 8 ตัวอักษร" required>
                </div>

                <div class="form-group">
                    <label>ยืนยันรหัสผ่าน</label>
                    <input type="password" name="password_confirmation" required>
                </div>

                <div class="form-group">
                    <label>บทบาทผู้ใช้</label>
                    <select name="role_id" required>
                        <option value="">-- เลือกบทบาท --</option>
                        <option value="1">Admin</option>
                        <option value="2">Staff</option>
                        <option value="3">User</option>
                    </select>
                </div>

                <div class="form-group">
                    <label>สถานะผู้ใช้</label>
                    <select name="status" required>
                        <option value="active">ใช้งาน</option>
                        <option value="inactive">ปิดการใช้งาน</option>
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

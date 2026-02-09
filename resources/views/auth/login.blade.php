<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link rel="stylesheet" href="{{ asset('css/login.css') }}">
</head>

<body>

    <div class="container">
        <div class="card">

            <!-- HEADER -->
            <div class="page-header">
                <h2>เข้าสู่ระบบ</h2>
                <span class="subtitle">User Login</span>
            </div>
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

            <!-- FORM -->
            <form class="login-form" method="POST" action="{{ route('login') }}">
                @csrf

                <div class="form-group">
                    <label>ชื่อผู้ใช้</label>
                    <input type="text" name="username" placeholder="Username" value="{{ old('username') }}" required>
                </div>

                <div class="form-group">
                    <label>รหัสผ่าน</label>
                    <input type="password" name="password" placeholder="Password" required>
                </div>

                <div class="form-action">
                    <button type="submit" class="btn-login">เข้าสู่ระบบ</button>
                </div>
            </form>


        </div>
    </div>

</body>

</html>

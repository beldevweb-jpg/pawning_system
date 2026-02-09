<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User report</title>
    <link rel="stylesheet" href="{{ asset('css/table.css') }}">
</head>

<body>
    <div class="container">
        <div class="card">

            <div class="page-header">
                <div class="header-left">
                    <h2>ข้อมูล user</h2>
                    <span class="subtitle">user Management</span>
                </div>

                <div class="header-right">
                    <input type="text" class="search-box" placeholder="ค้นหา..." />

                </div>
            </div>
            <header class="header">
                <div class="user">
                    <span class="avatar">👤</span>
                    <span>{{ auth()->user()->name }}</span>
                </div>

                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="logout">
                        ออกจากระบบ
                    </button>
                </form>
            </header>

            <!-- TABLE -->
            <div class="table-wrapper">
                <table>
                    <thead>
                        <tr>
                            <th>ลำดับ</th>
                            <th>username</th>
                            <th>password</th>
                            <th>role</th>
                            <th>status</th>
                            <th class="edit-col">แก้ไขข้อมูล</th>
                        </tr>
                    </thead>
                    <tbody>
                        <!-- ตัวอย่างข้อมูล -->
                        <tr>
                            <td>1</td>
                            <td>pao</td>
                            <td>123456</td>
                            <td>admin</td>
                            <td class="status active">ปกติ</td>
                            <td class="edit-col"><button class="btn-edit">Edit</button></td>
                        </tr>
                    </tbody>
                </table>
            </div>

        </div>
    </div>

</body>

</html>

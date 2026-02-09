<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Member report</title>
    <link rel="stylesheet" href="{{ asset('css/table.css') }}">
</head>

<body>

    <!-- Top Header -->
    <header class="header">
        <div class="user">
            <span class="avatar">👤</span>
            <span>username</span>
        </div>
        <button type="button" class="logout">ออกจากระบบ</button>
    </header>

    <div class="container">
        <div class="card">

            {{-- แจ้งเตือน --}}
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
            <!-- Card Header -->
            <div class="card-header">
                <div class="header-left">
                    <h2>ข้อมูล user</h2>
                </div>

                <div class="header-right">
                    <input type="text" class="search-box" placeholder="ค้นหา..." />
                </div>
            </div>


            <!-- Table -->
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
                        @forelse ($users as $user)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td>
                                    {{ $user->name }}
                                </td>
                                </td>
                                <td>{{ $user->username }}</td>
                                <td>{{ $user->phone }}</td>
                                <td>{{ $user->status }}</td>
                                <td>
                                    <a href="{{ route('user.edit', $user->user_id) }}">
                                        แก้ไขข้อมูล
                                    </a>
                                </td>
                            </tr>

                        @empty
                            <tr>
                                <td colspan="6">ไม่พบข้อมูล</td>
                            </tr>
                        @endforelse

                    </tbody>
                </table>
            </div>



</html>

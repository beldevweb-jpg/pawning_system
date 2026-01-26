<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="{{ asset('css/customer.css') }}">
</head>

<body>
    <div class="container">
        <div class="card">

            <!-- HEADER -->
            <div class="page-header">
                <h2>ข้อมูลลูกค้า / ข้อมูลโทรศัพท์</h2>

                <input type="text" class="search-box" placeholder="ค้นหา..." />
            </div>

            <!-- TABLE -->
            <div class="table-wrapper">
                <table>
                    <thead>
                        <tr>
                            <th>ลำดับ</th>
                            <th>ยี่ห้อ</th>
                            <th>รุ่น</th>
                            <th>รหัสเครื่อง</th>
                            <th>ชื่อลูกค้า</th>
                            <th>สถานะ</th>
                        </tr>
                    </thead>
                    <tbody>
                        <!-- ตัวอย่างข้อมูล -->
                        <tr>
                            <td>1</td>
                            <td>iPhone</td>
                            <td>13 Pro</td>
                            <td>ABC123456</td>
                            <td>สมชาย</td>
                            <td class="status active">ปกติ</td>
                        </tr>
                    </tbody>
                </table>
            </div>

        </div>
    </div>

</body>

</html>
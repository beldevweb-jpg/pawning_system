<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ประวัติการขาย</title>
    <link rel="stylesheet" href="{{ asset('css/table.css') }}">
</head>

<body>
    <div class="container">
        <div class="card">

            <div class="page-header">
    <div class="header-left">
        <h2>ประวัติการขาย</h2>
        <span class="subtitle">user Management</span>
    </div>

    <div class="header-right">
        <input type="text" class="search-box" placeholder="ค้นหา..." />

    </div>
</div>


            <!-- TABLE -->
            <div class="table-wrapper">
                <table>
                    <thead>
                        <tr>
                                <th>ลำดับ</th>
                                <th>รหัสรายการ</th>
                                <th>ชื่อรายการ</th>
                                <th>ชื่อพนักงานขาย</th>
                                <th>ชื่อลูกค้า</th>
                                <th>สถานะการขาย</th>
                                <th class="edit-col" >แก้ไขข้อมูล</th>
                        </tr>
                    </thead>
                    <tbody>
                        <!-- ตัวอย่างข้อมูล -->
                        <tr>
                            <td>1</td>
                            <td>pao</td>
                            <td>123456</td>
                            <td>admin</td>
                            <td>admin</td>
                            <td class="status active">ปกติ</td>
                            <td class="edit-col" ><button class="btn-edit">Edit</button></td>
                        </tr>
                    </tbody>
                    <tfoot>
    <tr class="table-summary">
        <td colspan="5">รวมยอดขายทั้งหมด</td>
        <td class="summary-price">฿ 12,500.00</td>
        <td class="edit-col">-</td>
    </tr>
</tfoot>
                </table>
            </div>

        </div>
    </div>

</body> 

</html>
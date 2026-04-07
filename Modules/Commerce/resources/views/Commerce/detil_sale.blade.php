<!DOCTYPE html>
<html lang="th">

<head>
    <meta charset="UTF-8">
    <title>ใบสั่งซื้อสินค้า</title>
    <link rel="stylesheet" href="{{ asset('css/detil_sale.css') }}">
</head>
<style id="r0yzde">
    @media print {
        .no-print {
            display: none !important;
        }
    }
</style>
@php
    $qr = $sale->qr_code;
    $productImg = $sale->product_images[0] ?? null;

    $data = $sale->product_images;

    if (is_string($data)) {
        $data = json_decode($data, true);

        if (is_string($data)) {
            $data = json_decode($data, true);
        }
    }

    $img = is_array($data) ? $data[0] ?? null : null;
@endphp

<body onload="loadData()">

    <div class="no-print">
        <button onclick="window.print()">พิมพ์</button>
    </div>

    <div class="receipt-container">
        <aside class="sidebar" style="width:200px; font-size:14px; line-height:1.6;">
            <!-- เลขที่ -->
            <div style="text-align:center; font-weight:bold;">
                เลขที่ {{ $sale->running_no }}
            </div>
            <!-- QR -->
            <div style="text-align:center; margin:0px 0;">
                @if ($qr)
                    <img src="{{ asset('storage/' . $qr) }}" style="width:100px; height:100px;">
                @else
                    <div class="qr-box">QR</div>
                @endif
                {{ $sale->created_at->format('d/m/Y') }}
                ..........................................
            </div>
            <div>
                {{ $sale->member_r->fullname ?? '-' }}<br>
                ({{ $sale->member_r->tax_number }})<br>
                <strong>ราคา</strong><br>
                {{ number_format($price) }} บาท<br>
                {{ $settings->phone ?? '' }}
            </div>
            <!-- footer -->
            <div style="margin-top:50px; font-weight:bold;">
                <p style="font-size: 9px">
                    ข้าพเตจ้ารับทราบว่าธุรกรรมนี้คือการขายและผู้ขายรับรองว่าสินค้าดังกลาวเป็นทรัพย์สินโดยชอบธรรมด้วยกฏหมายของผู้ขาย
                    และไม่อยู่ในระหว่างการลักลอบหรือกระทำผิดกฏหมาย</p> <br>
                ลงชื่อ ......................
            </div>
        </aside>
        <div style="width:100%; padding:10px;">
            <!-- TITLE -->
            <div style="text-align:center; font-weight:bold; font-size:18px; margin-bottom:8px;">
                บันทึกการรับซื้อสินค้า / หลักฐานในการส่งมอบสินค้า
                <br>
                {{ optional($settings)->company_name }}
            </div>
            <div style="display:flex; justify-content:space-between; align-items:flex-start;">
                <!-- LEFT CONTENT -->
                <div style="width:100%; font-size:20px; text-align:left;">
                    ข้าพเจ้า {{ $sale->member_r->fullname ?? '-' }}
                    ({{ $sale->member_r->tax_number }})
                    {{ $sale->brand }} {{ $sale->model }} {{ $sale->serial_number }}
                    <br> ราคา {{ number_format($price) }} บาท
                    วันที่ {{ $sale->created_at->format('d/m/Y') }}
                    <br> หมายเหตุ {{ $sale->note }}
                </div>
                <div style="width:25%; text-align:right;">
                    <!-- เลขที่ -->
                    <div style="font-size:18px; margin-bottom:5px; font-weight:bold; ">
                        เลขที่ {{ $sale->running_no }}
                    </div>
                    @if ($qr)
                        <img src="{{ asset('storage/' . $qr) }}" style="width:110px; height:110px;">
                    @endif
                    @if (!empty($sale->locker_pass))
                        <div style="margin-top:10px;">
                            รหัสล็อกหน้าจอ {{ $sale->locker_pass }}
                        </div>
                    @elseif (!empty($sale->drawn_lock))
                        <div style="margin-top:10px;">
                            รหัสล็อกหน้าจอแบบวาด {{ $sale->drawn_lock }}
                        </div>
                    @else
                        <div style="color: red;">
                            ไม่มีหัสล็อกหน้าจอ
                        </div>
                    @endif
                </div>
            </div>
            <!-- IMAGES -->
            <div style="text-align:center;">
                <img src="{{ asset('storage/' . $sale->product_images) }}"
                    style="width:150px; height:180px; object-fit:cover; margin-right:10px;">

                <img src="{{ asset('storage/' . $sale->product_images_behind) }}"
                    style="width:150px; height:180px; object-fit:cover;">
            </div>

            <!-- SIGN -->
            <div style="text-align:right;">
                ข้าพเตจ้ารับทราบว่าธุรกรรมนี้คือการขายและผู้ขายรับรองว่าสินค้าดังกลาวเป็นทรัพย์สินโดยชอบธรรมด้วยกฏหมายของผู้ขาย
                และไม่อยู่ในระหว่างการลักลอบหรือกระทำผิดกฏหมาย
            </div>
            <br>
            <div style="text-align:right; font-weight:bold;"> ลงชื่อ
                .............................
                เบอร์โทร .............................
            </div>
        </div>
    </div>
</body>
<script>
    function loadData() {
        const data = JSON.parse(localStorage.getItem('receipt_data'));
        const img = localStorage.getItem('receipt_img');

        if (data && data.details) {
            document.getElementById('viewDetails').innerText = data.details;
        }
        if (img) {
            const out = document.getElementById('outputImg');
            out.src = img;
            out.style.display = 'block';
            document.getElementById('imgPlaceholder').style.display = 'none';
        }
    }

    function printPDF() {
        document.title = "receipt-A5";
        window.print();
    }
</script>

</html>

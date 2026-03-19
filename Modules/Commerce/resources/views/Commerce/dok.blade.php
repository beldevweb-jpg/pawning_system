<!DOCTYPE html>
<html lang="th">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ทำรายการ</title>
    <link rel="stylesheet" href="{{ asset('css/tai.css') }}">
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

        <form method="POST" action="{{ route('commerce.dok_store', ['id' => $sale->id]) }}"
            enctype="multipart/form-data">
            @csrf

            <div class="card">
                <h2 class="center-title">ต่อดอก</h2>
                <!-- รูป -->
                <div class="image-grid">
                    @if (!empty($sale->product_images))
                        @foreach (array_slice(json_decode($sale->product_images), 0, 3) as $img)
                            <div class ="image-box">
                                <img src="{{ asset('storage/' . $img) }}"
                                    style="width:100%; height:100%; object-fit:cover; border-radius:8px;">
                            </div>
                        @endforeach
                    @endif
                </div>

                <!-- เงิน -->
                <div class="grid-2">
                    <div id="paymentWarning" style="color:red; display:none; font-weight:600;">
                        ยอดชำระไม่ตรงกับ "รวมต้องจ่าย"
                    </div>
                    <div class="form-group">
                        <label>เงินต้น</label>
                        <input id="principalInput" name="principal" class="input"
                            value="{{ number_format($totalPrice ?? ($principal ?? 0)) }}" readonly>

                    </div>
                    <div class="form-group">
                        <label>ดอก</label>
                        <div class="plus-line">
                            <span>+</span>
                            <input id="interestInput" value="{{ $sale->dok }}" name="dok" class="input"
                                type="text" inputmode="numeric" readonly>
                            <button type="button" class="edit-link" onclick="toggleEdit()">
                                แก้ไข
                            </button>
                        </div>
                        <!-- หมายเหตุ -->
                        <div id="noteSection" class="form-row column" style="display:none;">
                            <label>หมายเหตุ</label>
                            <textarea name="note" id="note" class="textarea" placeholder="(แสดงหลังตัดแก้ไข)"></textarea>
                        </div>
                    </div>
                </div>
                <!-- การชำระเงิน -->
                <h3 class="section-title">การชำระเงิน</h3>
                <div class="grid-2">
                    <div class="form-group">
                        <label>เงินโอน</label>
                        <input class="input" name="transfer" id="transferInput" type="text" inputmode="numeric">
                    </div>

                    <div class="form-group">
                        <label>เงินสด</label>
                        <input class="input" name="cash" id="cashInput" type="text" inputmode="numeric">
                    </div>
                </div>

                <div class="form-group">
                    <label>แนบสลิป</label>

                    <input type="file" class="file-input" accept="image/*" name="slip">
                </div>

                <div class="submit">
                    <button type="button" class="btn cancel" id="clearForm">
                        ยกเลิก
                    </button>

                    <button type="submit" class="btn confirm">
                        ดำเนินการต่อ
                    </button>
                </div>
            </div>
        </form>
    </div>
</body>

</html>

<script>
    const interestInput = document.getElementById("interestInput");
    const transferInput = document.getElementById("transferInput");
    const cashInput = document.getElementById("cashInput");
    const paymentWarning = document.getElementById("paymentWarning");
    const confirmBtn = document.querySelector(".confirm");

    function toggleEdit() {
        const note = document.getElementById("noteSection");
        const btn = document.querySelector(".edit-link");

        interestInput.readOnly = !interestInput.readOnly;

        if (!interestInput.readOnly) {
            interestInput.focus();
            note.style.display = "flex";
            btn.textContent = "ล็อก";
        } else {
            note.style.display = "none";
            btn.textContent = "แก้ไข";
        }
    }

    function parseNumber(val) {
        return parseFloat((val || "0").toString().replace(/,/g, '')) || 0;
    }

    function formatNumber(num) {
        return num.toLocaleString('th-TH');
    }

    function checkPayment() {
        const dok = parseNumber(interestInput.value);
        const transfer = parseNumber(transferInput.value);
        const cash = parseNumber(cashInput.value);

        const noteInput = document.getElementById("note");

        const paid = transfer + cash;

        if (paid === dok && noteInput !== "") {
            paymentWarning.style.display = "none";
            confirmBtn.disabled = false;
        } else {
            paymentWarning.style.display = "block";
            confirmBtn.disabled = true;
        }
    }
    
    function autoFormat(e) {
        const val = parseNumber(e.target.value);

        if (val === 0) {
            e.target.value = "";
            return;
        }

        e.target.value = formatNumber(val);
    }

    window.addEventListener('DOMContentLoaded', () => {

        checkPayment();

        transferInput.addEventListener('input', checkPayment);
        cashInput.addEventListener('input', checkPayment);
        interestInput.addEventListener('input', checkPayment);

        transferInput.addEventListener('blur', autoFormat);
        cashInput.addEventListener('blur', autoFormat);
        interestInput.addEventListener('blur', autoFormat);
    });

    function checkNegative(el) {
        if (parseNumber(el.value) < 0) {
            el.value = 0;
        }
    }
</script>

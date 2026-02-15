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
        <form method="POST" action="{{ route('commerce.tai.store') }}" enctype="multipart/form-data">
            @csrf

            <div class="card">

                <h2 class="center-title">ไถ่</h2>

                <!-- รูป -->
                <div class="image-grid">
                    <div class="image-box">+</div>
                    <div class="image-box">+</div>
                    <div class="image-box">+</div>
                </div>

                <!-- เงิน -->
                <div class="grid-2">

                    <!-- เงินต้น -->
                    <div class="two-column">
                        <div>
                            <label>เงินต้น</label>
                            <input class="input" value="(แก้ไม่ได้)" disabled>
                        </div>
                        <div>
                            <label>ดอก</label>
                            <div class="plus-line">
                                <span>+</span>
                                <input id="interestInput" name="dok" class="input" type="number" disabled>
                            </div>
                            <small class="edit-link" onclick="toggleEdit()">
                                แก้ไข
                            </small>
                        </div>
                    </div>

                    <div id="noteSection" class="form-group" style="display:none;">
                        <label>หมายเหตุ</label>

                        <textarea name="note" class="textarea" placeholder="ระบุเหตุผลที่แก้ไข"></textarea>
                    </div>
                </div>
                <!-- รวม -->
                <div class="form-group">
                    <label>รวมต้องจ่าย</label>

                    <input id="totalInput" name="total" class="input total" readonly>
                </div>



                <!-- การชำระ -->
                <h3 class="section-title">การชำระเงิน</h3>

                <div class="grid-2">

                    <div class="form-group">
                        <label>เงินโอน</label>
                        <input name="transfer" id="transferInput" class="input" type="number">
                    </div>

                    <div class="form-group">
                        <label>เงินสด</label>
                        <input name="cash" id="cashInput" class="input" type="number">
                    </div>

                </div>

                <div class="form-group">
                    <label>แนบสลิป</label>

                    <input type="file" name="slip" class="file-input" accept="image/*">
                </div>

                <!-- ปุ่ม -->
                <div class="submit">

                    <button type="button" class="danger">
                        ยกเลิก
                    </button>

                    <button class="primary">
                        💾 บันทึก
                    </button>
                </div>
            </div>
        </form>
    </div>

    <script>
        function toggleEdit() {

            const input =
                document.getElementById("interestInput");

            const note =
                document.getElementById("noteSection");

            const isDisabled =
                input.disabled;

            input.disabled = !isDisabled;

            if (!input.disabled) {

                input.focus();
                note.style.display = "flex";

            } else {

                note.style.display = "none";
            }
        }
    </script>



</body>

</html>

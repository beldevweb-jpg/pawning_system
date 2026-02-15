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
        <form method="POST" action="{{ route('commerce.dok.store') }}" enctype="multipart/form-data">
            @csrf

            <div class="card">

                <h2 class="center-title">ต่อดอก</h2>

                <!-- รูป -->
                <div class="image-grid">
                    <div class="image-box">+</div>
                    <div class="image-box">+</div>
                    <div class="image-box">+</div>
                </div>

                <!-- เงิน -->
                <div class="grid-2">

                    <div class="form-group">
                        <label>เงินต้น</label>

                        <input class="input" value="{{ $principal ?? '' }}" disabled>

                        <input type="hidden" name="principal" value="{{ $principal ?? '' }}">
                    </div>

                    <div class="form-group">
                        <label>ดอก</label>

                        <div class="plus-line">
                            <span>+</span>

                            <input id="interestInput" name="dok" class="input" type="number">
                        </div>
                    </div>
                </div>

                <!-- รวม -->
                <div class="form-group">
                    <label>รวมต้องจ่าย</label>

                    <input class="input total" name="total" id="totalInput" readonly>
                </div>

                <!-- การชำระเงิน -->
                <h3 class="section-title">การชำระเงิน</h3>
                <div class="grid-2">
                    <div class="form-group">
                        <label>เงินโอน</label>
                        <input class="input" name="transfer" id="transferInput" type="number">
                    </div>

                    <div class="form-group">
                        <label>เงินสด</label>
                        <input class="input" name="cash" id="cashInput" type="number">
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
</body>

</html>

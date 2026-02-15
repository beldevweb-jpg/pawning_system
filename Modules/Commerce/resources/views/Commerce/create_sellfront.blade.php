<!DOCTYPE html>
<html lang="th">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ขายหน้าร้าน</title>
    <link rel="stylesheet" href="{{ asset('css/sell-front.css') }}">
</head>

<body>

    <header class="header">
        <div class="user">
            <span class="avatar">👤</span>
            <span>{{ auth()->user()->name ?? '' }}</span>
        </div>

        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit" class="logout">
                ออกจากระบบ
            </button>
        </form>
    </header>

    <h1 class="title">ขายหน้าร้าน</h1>

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
        <div class="card">
            <Form method="POST" action="{{ route('commerce.store_sellfront') }}" enctype="multipart/form-data">
                @csrf
                <div class="form-row column">
                    <label>สินค้า</label>
                    <input type="text" name="product" class="input">
                </div>

                <div class="form-row column">
                    <label>เงินสด</label>
                    <input type="number" name="cash" value="{{ $expenses->product ?? '' }}" class="input"
                        placeholder="จำนวนเงินสด">
                </div>
                <div class="form-row column">
                    <label>เงินโอน</label>
                    <input type="number" name="transfer" value="{{ $expenses->transfer ?? '' }}" class="input"
                        placeholder="จำนวนเงินโอน">
                </div>

                <div class="form-row column">
                    <label>สลิปโอน</label>
                    <input type="file" name="slip" value="{{ $expenses->slip ?? '' }}" class="input">
                </div>

                <div class="form-row column">
                    <label>หมายเหตุ</label>
                    <textarea name="note" class="input" rows="3" placeholder="ระบุหมายเหตุ (ถ้ามี)"></textarea>
                </div>

                <div class="option">
                    <label><input type="radio" name="type" value="receive"> รับ</label>
                    <label><input type="radio" name="type" value="pay"> จ่าย</label>
                </div>

        </div>

        <div class="submit">
            <button name="cancel" class="danger">ยกเลิก</button>
            <button name="next" class="primary">บันทึก</button>
        </div>
        </Form>
    </div>

</body>

</html>

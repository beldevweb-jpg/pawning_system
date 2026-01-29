<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>โอ๋ โมบาย</title>
    <link rel="stylesheet" href="{{ asset('css/type_of_sale.css') }}">
    <script src="{{ asset('js/app.js') }}"></script>
</head>

<body>
    <header class="header">
        <div class="user-info">
            <div class="avatar"></div>
            <span class="avatar">👤</span>
            <span class="username">username</span>
        </div>
        <button class="logout">ออกจากระบบ</button>
    </header>

    <h1 class="title">โอ๋ โมบาย</h1>
    {{ $member->fullname }}
    <form method="POST" action="{{ route('commerce.store_type_of_sale', ['id' => $member->member_id]) }}">
        @csrf

        <main class="container">

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

            @isset($sale)
                <input type="hidden" name="sale_id" value="{{ $sale->id }}">
            @endisset

            @isset($member)
                <input type="hidden" name="member_id" value="{{ $member->member_id }}">
            @endisset

            <section class="menu-card">
                <button type="submit" name="type" value="pawn" class="menu-item">
                    ขาย
                </button>

                <button type="submit" name="type" value="counter" class="menu-item">
                    ขายหน้าร้าน
                </button>

                <button type="submit" name="type" value="service" class="menu-item">
                    บริการ
                </button>
            </section>
    </form>
    </main>
</body>

</html>

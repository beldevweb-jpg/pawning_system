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

    <h1 class="title">โอ๋ โมบาย</h1>

    <h2>{{ $member->fullname }}</h2>

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
        <section class="menu-card">
            <a href="{{ route('commerce.create_pawning', ['id' => $member->member_id, 'mode' => 'create']) }}"
                class="menu-item btn-pawm">
                ขาย
            </a>

            <a href="{{ route('commerce.create_pawning', ['id' => $member->member_id, 'mode' => 'create']) }}"
                class="menu-item btn-pawm">
                บริการ
            </a>

            <a href="{{ route('commerce.create_salefront', ['id' => $member->member_id, 'mode' => 'create']) }}"
                class="menu-item btn-pawm">
                ขายหน้าร้าน
            </a>
        </section>


    </main>
</body>


</html>

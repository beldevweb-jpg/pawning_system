<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ประวัติการขาย</title>
    <link rel="stylesheet" href="{{ asset('css/table.css') }}">
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
                <tbody>
                    @forelse ($sales as $sale)
                        <tr>
                            <td>{{ $sales->firstItem() + $loop->index }}</td>

                            <td>{{ $sale->id }}</td>

                            <td>{{ $sale->product_name ?? '-' }}</td>

                            <td>{{ $sale->user_r->name ?? '-' }}</td>

                            <td>{{ $sale->member_r->name ?? '-' }}</td>

                            <td class="status active">
                                {{ $sale->status ?? 'ปกติ' }}
                            </td>

                            <td class="edit-col">
                                <a href="{{ route('commerce.edit_sale', $sale->id) }}" class="btn-edit">
                                    Edit
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" style="text-align:center;">
                                ไม่มีข้อมูล
                            </td>
                        </tr>
                    @endforelse
                </tbody>

            </div>

        </div>
    </div>

</body>

</html>

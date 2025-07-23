<!DOCTYPE html>
<html lang="ja">

<head>
    <meta charset="UTF-8">
    <title>管理者画面</title>
    <link rel="stylesheet" href="{{ asset('css/admin/admin.css') }}">
    @yield('css')
</head>

<body>
    <header>
        <nav>
            <div class="header-bar">
                <div class="logo">
                    <img src="{{ asset('storage/images/coachtech-logo.svg') }}" alt="画像" style="height: 30px;"></a>
                </div>
                <div class="nav-links">
                    <a href="{{ route('admin.attendance.index') }}">勤怠一覧</a>
                    <a href="{{ route('admin.users.index') }}">スタッフ一覧</a>
                    <a href="{{ route('admin.requests.index') }}">申請一覧</a>
                    <form method="POST" action="{{ route('admin.logout') }}" style="display: inline;">
                        @csrf
                        <button type="submit">ログアウト</button>
                    </form>
                </div>
            </div>
        </nav>
    </header>

    <main class="container">
        @yield('content')
    </main>
</body>

</html>
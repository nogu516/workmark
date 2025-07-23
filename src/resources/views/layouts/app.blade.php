<!DOCTYPE html>
<html lang="ja">

<head>
    <meta charset="UTF-8">
    <title>Workmark - 勤怠管理</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
</head>

<body>
    {{-- ヘッダーなどがあればここに --}}
    <header>
        <nav>
            <div class="header-bar">
                <div class="logo">
                    <img src="{{ asset('storage/images/coachtech-logo.svg') }}" alt="画像" style="height: 30px;"></a>
                </div>
                <div class="nav-links">
                    <a href="{{ route('attendance.index') }}">勤怠</a>
                    <a href="{{ route('attendance.list') }}">勤怠一覧</a>
                    <a href="{{ route('applications.index') }}">申請</a>
                    <form method="POST" action="{{ route('logout') }}" style="display:inline;">
                        @csrf
                        <button type="submit">ログアウト</button>
                    </form>
                </div>
            </div>
        </nav>
    </header>

    <main>
        @yield('content')
    </main>

    {{-- フッター --}}
    <footer>
        <p>&copy; 2025 Workmark</p>
    </footer>
</body>

</html>
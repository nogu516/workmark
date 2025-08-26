@extends('layouts.admin')

@section('content')
<link rel="stylesheet" href="{{ asset('css/admin/login.css') }}">

<body>
    <div class="auth-container">
        <h2>管理者ログイン</h2>

        @if ($errors->any())
        <div>
            <strong>{{ $errors->first() }}</strong>
        </div>
        @endif

        <form method="POST" action="{{ route('admin.login') }}">
            @csrf
            <div>
                <label>メールアドレス</label>
                <input type="email" name="email" required>
                @error('email')
                <div class="error">{{ $message }}</div>
                @enderror
            </div>
            <div>
                <label>パスワード</label>
                <input type="password" name="password" required>
                @error('password')
                <div class="error">{{ $message }}</div>
                @enderror
            </div>
            <button type="submit">管理者ログインする</button>
        </form>
    </div>
</body>
@endsection
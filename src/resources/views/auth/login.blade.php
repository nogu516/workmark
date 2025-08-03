@extends('layouts.app')

@section('content')
<link rel="stylesheet" href="{{ asset('css/login.css') }}">

<div class="auth-container">
    <h2>ログイン</h2>

    <form method="POST" action="{{ route('login.store') }}">
        @csrf
        <input type="email" name="email" placeholder="メールアドレス" value="{{ old('email') }}">
        @error('email')
        <div class="error">{{ $message }}</div>
        @enderror
        <input type="password" name="password" placeholder="パスワード" value="{{ old('password') }}">
        @error('password')
        <div class="error">{{ $message }}</div>
        @enderror
        @error('auth')
        <div class="error">{{ $message }}</div>
        @enderror
        <button type="submit">ログインする</button>
    </form>
    <a href="{{ route('register') }}">会員登録はこちら</a>
</div>
@endsection
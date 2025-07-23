@extends('layouts.app')

@section('content')
<link rel="stylesheet" href="{{ asset('css/register.css') }}">

<div class="auth-container">
    <h2>会員登録</h2>
    <form method="POST" action="{{ route('register') }}">
        @csrf
        <input type="text" name="name" placeholder="名前" value="{{ old('name') }}">
        <input type="email" name="email" placeholder="メールアドレス" value="{{ old('email') }}">
        <input type="password" name="password" placeholder="パスワード">
        <input type="password" name="password_confirmation" placeholder="パスワード確認">
        <button type="submit">登録する</button>
    </form>
    <a href="{{ route('login') }}">ログインはこちら</a>
</div>
@endsection
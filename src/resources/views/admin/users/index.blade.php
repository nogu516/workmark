@extends('layouts.admin')

@section('content')
<link rel="stylesheet" href="{{ asset('css/admin/users.index.css') }}">

<div class="attendance-list-container">
    <h2>スタッフ一覧</h2>

    <table>
        <thead>
            <tr>
                <th>名前</th>
                <th>メールアドレス</th>
                <th>月次勤怠</th>
            </tr>
        </thead>
        <tbody>
            @foreach($users as $user)
            <tr>
                <td>{{ $user->name }}</td>
                <td>{{ $user->email }}</td>
                <td>
                    <a href="{{ route('admin.users.attendances', ['user' => $user->id]) }}">詳細
                    </a>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
    @endsection
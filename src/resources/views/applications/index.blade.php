@extends('layouts.app')

@section('content')
<link rel="stylesheet" href="{{ asset('css/applications/index.css') }}">

<div class="attendance-list-container">
    <h2>申請一覧</h2>

    <div class="tabs">
        <a href="{{ route('applications.index', ['tab' => 'pending']) }}" class="{{ $tab == 'pending' ? 'active' : '' }}">承認待ち</a>
        <a href="{{ route('applications.index', ['tab' => 'approved']) }}" class="{{ $tab == 'approved' ? 'active' : '' }}">承認済み</a>
    </div>

    <table>
        <thead>
            <tr>
                <th>状態</th>
                <th>名前</th>
                <th>対象日時</th>
                <th>申請理由</th>
                <th>申請日時</th>
                <th>詳細</th>
            </tr>
        </thead>
        <tbody>
            @foreach($applications as $attendance)
            <tr>
                <td>{{ $attendance->status == 'pending' ? '承認待ち' : '承認済み' }}</td>
                <td>{{ $attendance->user->name ?? '未登録' }}</td>
                <td>{{\Carbon\Carbon::parse($attendance->date)->format('Y年m月d日') }}</td>
                <td>{{ $attendance->note }}</td>
                <td>{{\Carbon\Carbon::parse($attendance->created_at)->format('Y年m月d日 H:i')  }}</td>
                <td><a href="{{ route('attendance.confirm', $attendance->id) }}">詳細</a></td>
            </tr>
            @endforeach
        </tbody>
    </table>
@extends('layouts.admin')

@section('content')
<link rel="stylesheet" href="{{ asset('css/admin/attendance.index.css') }}">

<div class="attendance-list-container">
    <h2>勤怠修正申請一覧</h2>

    <div class="tabs">
        <a href="{{ route('request_applications.index', ['tab' => 'pending']) }}"
            class="{{ $tab === 'pending' ? 'font-bold underline' : '' }}">
            承認待ち
        </a> |
        <a href="{{ route('request_applications.index', ['tab' => 'approved']) }}"
            class="{{ $tab === 'approved' ? 'font-bold underline' : '' }}">
            承認済み
        </a>
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
            @foreach($applications as $application)
            <tr>
                <td>{{ $application->status === 'pending' ? '承認待ち' : '承認済み' }}</td>
                <td>{{ $application->user->name ?? '未登録' }}</td>
                <td>{{ optional($application->attendance?->clock_in)->format('Y年m月d日') ?? '-' }}</td>
                <td>{{ $application->note }}</td>
                <td>{{ $application->created_at->format('Y年m月d日 H:i') }}</td>
                <td><a href="{{ route('admin.requests.confirm', $application->id) }}">詳細</a>
                    @if($tab === 'pending')
                    <form method="POST" action="{{ route('admin.requests.approve', $application->id) }}">
                        @csrf
                        <button type="submit">承認する</button>
                    </form>
                    @endif
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
    @endsection
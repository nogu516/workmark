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
            @foreach($applications as $application)
            <tr>
                <td>{{ $application->status === 'pending' ? '承認待ち' : '承認済み' }}</td>
                <td>{{ $application->user->name ?? '未登録' }}</td>
                <td>{{ optional($application->attendance?->clock_in)->format('Y年m月d日') ?? '-' }}</td>
                <td>{{ $application->note }}</td>
                <td>{{ $application->created_at->format('Y年m月d日 H:i') }}</td>
                <td>
                    <a href="{{ route('attendance.confirm', $application->id) }}" class="btn btn-primary">詳細</a>

                    <form method="POST" action="{{ route('admin.request-applications.destroy', $application->id) }}" style="display: inline;" onsubmit="return confirm('本当に削除しますか？');">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger">削除</button>
                    </form>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>
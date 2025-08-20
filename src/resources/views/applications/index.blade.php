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
                <td>{{ optional($application->attendance?->date)? \Carbon\Carbon::parse($application->attendance->date)->format('Y年m月d日') : '-' }}</td>
                <td>{{ $application->note }}</td>
                <td>{{ $application->created_at->format('Y年m月d日 H:i') }}</td>
                <td>
                    <form action="{{ route('attendance.confirm', ['id' => $application->id]) }}" method="POST">
                        @csrf
                        <input type="hidden" name="clock_in" value="{{ $application->new_clock_in }}">
                        <input type="hidden" name="clock_out" value="{{ $application->new_clock_out }}">
                        <input type="hidden" name="break_start" value="{{ $application->new_break_start }}">
                        <input type="hidden" name="break_end" value="{{ $application->new_break_end }}">
                        <input type="hidden" name="break2_start" value="{{ $application->new_break2_start }}">
                        <input type="hidden" name="break2_end" value="{{ $application->new_break2_end }}">
                        <input type="hidden" name="note" value="{{ $application->note }}">
                        <button type="submit" class="btn btn-primary">詳細</button>
                    </form>

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
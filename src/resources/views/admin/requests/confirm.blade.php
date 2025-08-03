@extends('layouts.admin')

@section('content')
<link rel="stylesheet" href="{{ asset('css/admin/request_confirm.css') }}">

<div class="attendance-detail-container">
    <h2>勤怠詳細</h2>

    <form method="POST" action="{{ route('request_applications.store') }}">

        @csrf
        @foreach ($data as $key => $value)
        <input type="hidden" name="{{ $key }}" value="{{ $value }}">
        @endforeach

        <table class="attendance-table">
            <tr>
                <th>名前</th>
                <td>{{ $attendance->user->name }}</td>
            </tr>
            <tr>
                <th>日付</th>
                <td>{{ \Carbon\Carbon::parse($attendance->clock_in)->format('Y年m月d日') }}</td>
            </tr>
            <tr>
                <th>出勤・退勤</th>
                <td>
                    {{ \Carbon\Carbon::parse($attendance->clock_in)->format('H:i') }}
                    〜
                    {{ \Carbon\Carbon::parse($attendance->clock_out)->format('H:i') }}
                </td>
            </tr>
            <tr>
                <th>休憩</th>
                <td>
                    {{ \Carbon\Carbon::parse($attendance->break_start)->format('H:i') }}
                    〜
                    {{ \Carbon\Carbon::parse($attendance->break_end)->format('H:i') }}
                </td>
            </tr>
            <tr>
                <th>備考</th>
                <td>{{ $attendance->note ?? '-' }}</td>
            </tr>
        </table>
        <div class="bottom-right-message">承認画面のため修正できません</div>
    </form>
</div>
@endsection
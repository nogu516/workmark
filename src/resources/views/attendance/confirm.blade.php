@extends('layouts.app')

@section('content')
<link rel="stylesheet" href="{{ asset('css/confirm.css') }}">
<div class="attendance-detail-container">
    <h2>勤怠詳細</h2>
    <table class="attendance-table">
        <tr>
            <th>名前</th>
            <td>{{ $attendance->user->name }}</td>
        </tr>
        <tr>
            <th>日付</th>
            <td>
                {{ $attendance->date }}
            </td>
        </tr>
        <tr>
            <th>出勤</th>
            <td>
                {{ $data['new_clock_in'] ? \Carbon\Carbon::parse($data['new_clock_in'])->format('H:i') : '未入力' }}
            </td>
        </tr>
        <tr>
            <th>退勤</th>
            <td>
                {{ $data['new_clock_out'] ? \Carbon\Carbon::parse($data['new_clock_out'])->format('H:i') : '未入力' }}
            </td>
        </tr>
        <tr>
            <th>休憩1</th>
            <td>
                {{ $data['new_break_start'] ? \Carbon\Carbon::parse($data['new_break_start'])->format('H:i') : '未入力' }}
                〜
                {{ $data['new_break_end'] ? \Carbon\Carbon::parse($data['new_break_end'])->format('H:i') : '未入力' }}
            </td>
        </tr>
        <tr>
            <th>休憩2</th>
            <td>
                {{ $data['new_break2_start'] ? \Carbon\Carbon::parse($data['new_break2_start'])->format('H:i') : '未入力' }}
                〜
                {{ $data['new_break2_end'] ? \Carbon\Carbon::parse($data['new_break2_end'])->format('H:i') : '未入力' }}
            </td>
        </tr>
        <tr>
            <th>備考</th>
            <td>{{ $data['note'] ?? 'なし' }}</td>
        </tr>
    </table>
    <div class="bottom-right-message">承認待ちのため修正できません</div>
</div>
@endsection
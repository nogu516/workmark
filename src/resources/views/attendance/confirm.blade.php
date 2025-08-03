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
                <input type="hidden" name="date" value="{{ $attendance->date }}">
            </td>
        </tr>
        <tr>
            <th>出勤</th>
            <td>
                {{ optional(isset($data['new_clock_in']) ? \Carbon\Carbon::parse($data['new_clock_in']) : null)->format('H:i') ?? '未入力' }}
                <input type="time" name="new_clock_in" value="{{ old('new_clock_in') }}">
            </td>
        </tr>
        <tr>
            <th>退勤</th>
            <td>
                {{ optional(isset($data['new_clock_out']) ? \Carbon\Carbon::parse($data['new_clock_out']) : null)->format('H:i') ?? '未入力' }}
                <input type="time" name="new_clock_out" value="{{ old('new_clock_out') }}">
            </td>
        </tr>
        <tr>
            <th>休憩1</th>
            <td>
                {{ optional(isset($data['new_break_start']) ? \Carbon\Carbon::parse($data['new_break_start']) : null)->format('H:i') ?? '未入力' }}
                <input type="time" name="new_break_start" value="{{ old('new_break_start') }}">
                〜
                {{ optional(isset($data['new_break_end']) ? \Carbon\Carbon::parse($data['new_break_end']) : null)->format('H:i') ?? '未入力' }}
                <input type="time" name="new_break_end" value="{{ old('new_break_end') }}">

            </td>
        </tr>
        <tr>
            <th>休憩2</th>
            <td>
                {{ optional(isset($data['new_break2_start']) ? \Carbon\Carbon::parse($data['new_break2_start']) : null)->format('H:i') ?? '未入力' }}
                <input type="time" name="new_break2_start" value="{{ old('new_break2_start') }}">
                〜
                {{ optional(isset($data['new_break2_end']) ? \Carbon\Carbon::parse($data['new_break2_end']) : null)->format('H:i') ?? '未入力' }}
                <input type="time" name="new_break2_end" value="{{ old('new_break2_end') }}">
            </td>
        </tr>
        <tr>
            <th>備考</th>
            <td>{{ $data['note'] ?? 'なし' }}</td>
        </tr>
    </table>
    <div class="bottom-right-message">承認画面のため修正できません</div>
</div>
<form id="autoSubmitForm" method="POST" action="{{ route('request_applications.store') }}">
    @csrf
    @foreach ($data as $key => $value)
    <input type="hidden" name="{{ $key }}" value="{{ $value }}">
    @endforeach
    {{-- attendance_id --}}
    <input type="hidden" name="attendance_id" value="{{ $data['attendance_id'] }}">
</form>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        document.getElementById('autoSubmitForm').submit();
    });
</script>
@endsection
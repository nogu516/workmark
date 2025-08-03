@extends('layouts.app')

@section('content')
<link rel="stylesheet" href="{{ asset('css/show.css') }}">

<div class="attendance-detail-container">
    <h2>勤怠詳細</h2>

    <form method="POST" action="{{ route('attendance.confirm', $attendance->id) }}">
        @csrf
        <input type="hidden" name="attendance_id" value="{{ $attendance->id }}">
        <input type="hidden" name="user_id" value="{{ $attendance->user_id }}">
        <input type="hidden" name="new_clock_in" value="{{ $attendance->clock_in }}">
        <input type="hidden" name="new_clock_out" value="{{ $attendance->clock_out }}">
        <input type="hidden" name="new_break_start" value="{{ $attendance->break_start }}">
        <input type="hidden" name="new_break_end" value="{{ $attendance->break_end }}">
        @if (session('success'))
        <p style="color: green">{{ session('success') }}</p>
        @endif
        <table class="attendance-table">
            <tr>
                <th>名前</th>
                <td>{{ Auth::user()->name }}</td>
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
                <td><input type="time" name="clock_in" value="{{ old('clock_in',optional($attendance->clock_in)->format('H:i')) }}"></td>
            </tr>
            <tr>
                <th>退勤</th>
                <td><input type="time" name="clock_out" value="{{ old('clock_out',optional($attendance->clock_out)->format('H:i')) }}"></td>
            </tr>
            <tr>
                <th>休憩</th>
                <td>
                    <input type="time" name="break_start" value="{{ old('break_start',optional($attendance->break_start)->format('H:i')) }}">
                    〜
                    <input type="time" name="break_end" value="{{ old('break_end',optional($attendance->break_end)->format('H:i')) }}">
                </td>
            </tr>
            <tr>
                <th>休憩2</th>
                <td>
                    <input type="time" name="break2_start" value="{{ old('break2_start',optional($attendance->break2_start)->format('H:i')) }}">
                    〜
                    <input type="time" name="break2_end" value="{{ old('break2_end',optional($attendance->break2_end)->format('H:i')) }}">
                </td>
            </tr>
            <tr>
                <th>備考</th>
                <td><textarea name="note" rows="3" class="w-full">{{ old('note', $attendance->note) }}</textarea></td>
            </tr>
        </table>

        <div class="mt-4">
            <button type="submit" class="btn btn-primary">修正</button>
        </div>
    </form>
</div>
@endsection
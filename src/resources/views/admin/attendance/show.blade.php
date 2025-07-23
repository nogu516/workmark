@extends('layouts.admin')

@section('content')
<link rel="stylesheet" href="{{ asset('css/show.css') }}">

<div class="attendance-detail-container">
    <h2>勤怠詳細</h2>

    @if (session('success'))
    <p style="color: green">{{ session('success') }}</p>
    @endif

    <form method="POST" action="{{ route('attendance.confirm', $attendance->id) }}">
        @csrf
        <input type="hidden" name="id" value="{{ $attendance->id }}">

        <table class="attendance-table">
            <tr>
                <th>名前</th>
                <td>{{$attendance->user->name }}</td>
            </tr>
            <tr>
                <th>日付</th>
                <td>{{ $attendance->date }}</td>
            </tr>
            <tr>
                <th>出勤</th>
                <td><input type="time" name="clock_in" value="{{ optional($attendance->clock_in)->format('H:i') }}"></td>
            </tr>
            <tr>
                <th>退勤</th>
                <td><input type="time" name="clock_out" value="{{ optional($attendance->clock_out)->format('H:i') }}"></td>
            </tr>
            <tr>
                <th>休憩</th>
                <td>
                    <input type="time" name="break_start" value="{{ optional($attendance->break_start)->format('H:i') }}">
                    〜
                    <input type="time" name="break_end" value="{{ optional($attendance->break_end)->format('H:i') }}">
                </td>
            </tr>
            <tr>
                <th>休憩2</th>
                <td>
                    <input type="time" name="break2_start" value="{{ optional($attendance->break2_start)->format('H:i') }}">
                    〜
                    <input type="time" name="break2_end" value="{{ optional($attendance->break2_end)->format('H:i') }}">
                </td>
            </tr>
            <tr>
                <th>備考</th>
                <td><textarea name="note" rows="3" class="w-full">{{ old('note', $attendance->note) }}</textarea></td>
            </tr>
        </table>

        <div class="mt-4">
            <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded">修正</button>
        </div>
    </form>
</div>
@endsection
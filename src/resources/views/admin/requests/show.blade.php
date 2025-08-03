@extends('layouts.admin')

@section('content')
<link rel="stylesheet" href="{{ asset('css/show.css') }}">

<div class="attendance-detail-container">
    <h2>勤怠詳細</h2>

    <form method="POST" action="{{ route('admin.requests.confirm', ['id' => $attendance->id]) }}">
        @csrf
        <input type="hidden" name="attendance_id" value="{{ $attendance->id }}">

        @if (session('success'))
        <p style="color: green">{{ session('success') }}</p>
        @endif

        <table class="table-auto border-collapse border border-gray-400 w-full">
            <thead class="bg-gray-200">
                <tr>
                    <th class="border p-2">名前</th>
                    <th class="border p-2">日付</th>
                    <th class="border p-2">出勤</th>
                    <th class="border p-2">退勤</th>
                    <th class="border p-2">休憩</th>
                    <th class="border p-2">休憩2</th>
                    <th class="border p-2">備考</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td class="border p-2">{{ $attendance->user->name }}</td>
                    <td class="border p-2">{{ $attendance->date }}</td>
                    <td class="border p-2">
                        <input type="time" name="clock_in" value="{{ optional($attendance->clock_in)->format('H:i') }}">
                    </td>
                    <td class="border p-2">
                        <input type="time" name="clock_out" value="{{ optional($attendance->clock_out)->format('H:i') }}">
                    </td>
                    <td class="border p-2">
                        <input type="time" name="break_start" value="{{ optional($attendance->break_start)->format('H:i') }}">
                        〜
                        <input type="time" name="break_end" value="{{ optional($attendance->break_end)->format('H:i') }}">
                    </td>
                    <td class="border p-2">
                        <input type="time" name="break2_start" value="{{ optional($attendance->break2_start)->format('H:i') }}">
                        〜
                        <input type="time" name="break2_end" value="{{ optional($attendance->break2_end)->format('H:i') }}">
                    </td>
                    <td class="border p-2">
                        <textarea name="note" rows="2" class="w-full">{{ old('note', $attendance->note) }}</textarea>
                    </td>
                </tr>
            </tbody>
        </table>
        <button type="submit">修正</button>
    </form>
</div>
@endsection
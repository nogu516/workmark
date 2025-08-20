@extends('layouts.admin')

@section('content')
<link rel="stylesheet" href="{{ asset('css/admin/users.attendances.css') }}">

<div class="attendance-list-container">
    <h2>{{ $user->name }}さんの勤怠</h2>

    <form method="GET" action="{{ route('admin.users.attendances', ['user' => $user->id]) }}">
        @csrf
        <button type="submit" name="prev" value="1">← 前月</button>
        <input type="month" name="date" value="{{ $date->format('Y-m') }}">
        <button type="submit" name="next" value="1">翌月 →</button>
    </form>

    <table border="1" cellpadding="8">
        <thead>
            <tr>
                <th>日付</th>
                <th>出勤</th>
                <th>退勤</th>
                <th>休憩</th>
                <th>合計</th>
                <th>詳細</th>
            </tr>
        </thead>
        <tbody>
            @php
            $year = $date->year;
            $month = $date->month;
            $daysInMonth = \Carbon\Carbon::create($year, $month, 1)->daysInMonth;
            @endphp

            @for ($d = 1; $d <= $daysInMonth; $d++)
                @php
                $currentDate=\Carbon\Carbon::create($year, $month, $d)->toDateString();
                $attendance = $attendances[$currentDate] ?? null;
                @endphp
                <tr>
                    <td>{{ $currentDate }}</td>
                    <td>{{ optional($attendance)->clock_in ? \Carbon\Carbon::parse($attendance->clock_in)->format('H:i') : '-' }}</td>
                    <td>{{ optional($attendance)->clock_out ? \Carbon\Carbon::parse($attendance->clock_out)->format('H:i') : '-' }}</td>
                    <td>
                        @if ($attendance && $attendance->break_start && $attendance->break_end)
                        {{ \Carbon\Carbon::parse($attendance->break_start)->diffInMinutes($attendance->break_end) }}分
                        @else
                        -
                        @endif
                    </td>
                    <td>
                        @if ($attendance && $attendance->clock_in && $attendance->clock_out)
                        @php
                        $workTime = \Carbon\Carbon::parse($attendance->clock_in)->diffInMinutes($attendance->clock_out);
                        $breakTime = ($attendance->break_start && $attendance->break_end)
                        ? \Carbon\Carbon::parse($attendance->break_start)->diffInMinutes($attendance->break_end)
                        : 0;
                        $total = $workTime - $breakTime;
                        @endphp
                        {{ floor($total / 60) }}時間{{ $total % 60 }}分
                        @else
                        -
                        @endif
                    </td>
                    <td>
                        @if ($attendance)
                        @php $clockInDate = \Carbon\Carbon::parse($attendance->clock_in); @endphp
                        <a href="{{ route('admin.attendance.show', [
                    'id' => $attendance->id,
                    'year' => $clockInDate->year,
                    'month' => $clockInDate->month,
                    ]) }}">詳細
                        </a>
                        @endif
                    </td>
                </tr>
                @endfor
        </tbody>
    </table>
    <div style="margin-bottom: 1rem;">
        <a href="{{ route('admin.users.attendances.csv', ['user' => $user->id, 'year' => $year, 'month' => $month]) }}">
            📥 CSV出力
        </a>
    </div>
    <a href="{{ route('admin.users.index') }}">← スタッフ一覧に戻る</a>
    @endsection
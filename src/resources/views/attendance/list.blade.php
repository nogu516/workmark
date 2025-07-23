@extends('layouts.app')

@section('content')
<link rel="stylesheet" href="{{ asset('css/list.css') }}">

<div class="attendance-list-container">
    <h2>勤怠一覧</h2>

    <form method="GET" action="{{ route('attendance.list') }}" class="mb-4">

        {{-- 前月・翌月 --}}
        <a href="{{ route('attendance.list', ['year' => \Carbon\Carbon::create($year, $month)->subMonth()->year, 'month' => \Carbon\Carbon::create($year, $month)->subMonth()->month]) }}">← 前月</a>
        <select name="year">
            @for ($y = now()->year - 1; $y <= now()->year + 1; $y++)
                <option value="{{ $y }}" {{ $y == $year ? 'selected' : '' }}>{{ $y }}年</option>
                @endfor
        </select>
        <select name="month">
            @for ($m = 1; $m <= 12; $m++)
                <option value="{{ $m }}" {{ $m == $month ? 'selected' : '' }}>{{ $m }}月</option>
                @endfor
        </select>
        <a href="{{ route('attendance.list', ['year' => \Carbon\Carbon::create($year, $month)->addMonth()->year, 'month' => \Carbon\Carbon::create($year, $month)->addMonth()->month]) }}">翌月 →</a>
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
            $daysInMonth = \Carbon\Carbon::create($year, $month)->daysInMonth;
            @endphp
            @for ($d = 1; $d <= $daysInMonth; $d++)
                @php
                $date=\Carbon\Carbon::create($year, $month, $d)->toDateString();
                $attendance = $attendances[$date] ?? null;
                @endphp
                <tr>
                    <td>{{ $date }}</td>
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
                    <td> @if ($attendance)<a href="{{ route('attendance.show', $attendance->id) }}">詳細</a>@endif</td>
                </tr>
                @endfor
        </tbody>
    </table>
    @endsection
@extends('layouts.admin')

@section('content')
<link rel="stylesheet" href="{{ asset('css/attendance.index.css') }}">

<body>
    @php
    $parsedDate = $date->copy(); // 1日固定での月表示にしたい場合
    @endphp

    <h2>{{ $parsedDate->format('Y年n月j日') }}の勤怠</h2>

    <form method="GET" action="{{ route('admin.attendance.index') }}" class="date-form" style="display: flex; gap: 10px; align-items: center;">
        <button type="submit" name="prev" value="1">← 前日</button>

        {{-- カレンダー形式の入力 --}}
        <input type="date" name="date" value="{{ \Carbon\Carbon::create($year, $month, $day)->format('Y-m-d') }}">

        <button type="submit" name="next" value="1">翌日 →</button>
    </form>

    <table>
        <thead>
            <tr>
                <th>名前</th>
                <th>出勤</th>
                <th>退勤</th>
                <th>休憩</th>
                <th>合計</th>
                <th>詳細</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($attendances as $attendance)
            <tr>
                <td>{{ $attendance->user->name }}</td>
                {{-- 出勤 --}}
                <td>{{ $attendance->clock_in ? \Carbon\Carbon::parse($attendance->clock_in)->format('H:i') : '-' }}</td>

                {{-- 退勤 --}}
                <td>{{ $attendance->clock_out ? \Carbon\Carbon::parse($attendance->clock_out)->format('H:i') : '-' }}</td>

                {{-- 休憩 --}}
                <td>
                    @if ($attendance->break_start && $attendance->break_end)
                    {{ \Carbon\Carbon::parse($attendance->break_start)->diffInMinutes($attendance->break_end) }}分
                    @else
                    -
                    @endif
                </td>

                {{-- 合計時間（分） --}}
                <td>
                    @php
                    $clockIn = $attendance->clock_in ? \Carbon\Carbon::parse($attendance->clock_in) : null;
                    $clockOut = $attendance->clock_out ? \Carbon\Carbon::parse($attendance->clock_out) : null;
                    $break = ($attendance->break_start && $attendance->break_end)
                    ? \Carbon\Carbon::parse($attendance->break_start)->diffInMinutes($attendance->break_end)
                    : 0;
                    $totalMinutes = ($clockIn && $clockOut) ? $clockIn->diffInMinutes($clockOut) - $break : null;
                    @endphp
                    {{ $totalMinutes !== null ? floor($totalMinutes / 60).'時間'.($totalMinutes % 60).'分' : '-' }}
                </td>

                {{-- 詳細 --}}
                <td><a href="{{ route('admin.attendance.show', ['id' => $attendance->id, 'year' => $year, 'month' => $month]) }}">詳細</a>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="6">出勤データがありません。</td>
            </tr>
            @endforelse
        </tbody>
    </table>
</body>

</html>
@endsection
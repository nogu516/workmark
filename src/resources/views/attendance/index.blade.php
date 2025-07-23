@extends('layouts.app')

@section('content')
<link rel="stylesheet" href="{{ asset('css/index.css') }}">

<div class="attendance-container">
    <h2>{{ \Carbon\Carbon::now()->format('Y年n月j日 (D)') }}</h2>
    <h3>{{ \Carbon\Carbon::now()->format('H:i') }}</h3>

    @if (!$attendance->clock_in)
    <p>勤務外</p>
    <form method="POST" action="{{ route('attendance.clockIn') }}">
        @csrf
        <button type="submit">出勤</button>
    </form>

    @elseif ($attendance->clock_in && !$attendance->clock_out && !$attendance->break_start)
    <p>勤務中</p>
    <form method="POST" action="{{ route('attendance.breakStart') }}">
        @csrf
        <button type="submit">休憩入</button>
    </form>
    <form method="POST" action="{{ route('attendance.clockOut') }}">
        @csrf
        <button type="submit">退勤</button>
    </form>

    @elseif ($attendance->break_start && !$attendance->break_end)
    <p>休憩中</p>
    <form method="POST" action="{{ route('attendance.breakEnd') }}">
        @csrf
        <button type="submit">休憩戻</button>
    </form>

    @elseif ($attendance->break_end && !$attendance->clock_out)
    <p>勤務中（休憩後）</p>
    <form method="POST" action="{{ route('attendance.breakStart') }}">
        @csrf
        <button type="submit">再休憩</button>
    </form>
    <form method="POST" action="{{ route('attendance.clockOut') }}">
        @csrf
        <button type="submit">退勤</button>
    </form>

    @elseif ($attendance->clock_out)
    <p>お疲れさまでした！</p>
    @endif
    @endsection
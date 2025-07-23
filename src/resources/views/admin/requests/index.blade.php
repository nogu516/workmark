@extends('layouts.admin')

@section('content')
<h2>勤怠修正申請一覧</h2>

<div class="tabs">
    <a href="{{ route('admin.requests.index', ['tab' => 'pending']) }}" class="{{ $tab === 'pending' ? 'active' : '' }}">承認待ち</a>
    <a href="{{ route('admin.requests.index', ['tab' => 'approved']) }}" class="{{ $tab === 'approved' ? 'active' : '' }}">承認済み</a>
</div>

<table>
    <thead>
        <tr>
            <th>状態</th>
            <th>名前</th>
            <th>対象日時</th>
            <th>申請理由</th>
            <th>申請日時</th>
            <th>詳細</th>
        </tr>
    </thead>
    <tbody>
        @foreach($applications as $request)
        <tr>
            <td>{{ $request->status === 'pending' ? '承認待ち' : '承認済み' }}</td>
            <td>{{ $request->user->name ?? '未登録' }}</td>
            <td>{{ optional($request->attendance?->clock_in)->format('Y年m月d日') ?? '-' }}</td>
            <td>{{ $request->note }}</td>
            <td>{{ $request->created_at->format('Y年m月d日 H:i') }}</td>
            <td><a href="{{ route('admin.requests.show', $request->id) }}">詳細</a>
                @if($request->status === 'pending')
                <form method="POST" action="{{ route('admin.requests.approve', $request->id) }}">
                    @csrf
                    <button type="submit">承認する</button>
                </form>
                @endif
            </td>
        </tr>
        @endforeach
    </tbody>
</table>
<style>
    .tabs a {
        display: inline-block;
        padding: 10px 20px;
        text-decoration: none;
        color: #333;
        border: 1px solid #ccc;
        border-bottom: none;
        margin-right: 8px;
        background-color: #f0f0f0;
    }

    .tabs a.active {
        background-color: #fff;
        font-weight: bold;
    }

    table {
        width: 100%;
        border-collapse: collapse;
        margin-top: 16px;
    }

    th,
    td {
        border: 1px solid #ccc;
        padding: 10px;
    }
</style>
@endsection
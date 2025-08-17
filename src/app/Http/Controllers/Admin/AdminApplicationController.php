<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\RequestApplication;
use Illuminate\Http\Request;
use App\Models\Attendance;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class AdminApplicationController extends Controller
{
    public function index(Request $request)
    {
        $tab = $request->input('tab', 'pending');     // pending / approved / all

        $query = RequestApplication::with(['user', 'attendance'])->latest();

        if ($tab === 'pending') {
            $query->where('status', 'pending');
        } elseif ($tab === 'approved') {
            $query->where('status', 'approved');
        }
        // all の場合は絞り込みしない

        $applications = $query->get();

        return view('admin.requests.index', compact('applications', 'tab'));
    }

    public function approve($id)
    {
        DB::transaction(function () use ($id) {
            $application = RequestApplication::lockForUpdate()->findOrFail($id);

            // 既に承認済みなら戻す
            if ($application->status === 'approved') return;

            // 勤怠を更新
            $attendance = Attendance::lockForUpdate()->findOrFail($application->attendance_id);

            $date = Carbon::parse($attendance->date)->format('Y-m-d');

            $attendance->clock_in = $application->new_clock_in
                ? Carbon::parse($application->new_clock_in)->format('Y-m-d H:i:s')
                : $attendance->clock_in;

            $attendance->clock_out = $application->new_clock_out
                ? Carbon::parse($application->new_clock_out)->format('Y-m-d H:i:s')
                : $attendance->clock_out;

            $attendance->break_start = $application->new_break_start
                ? Carbon::parse($application->new_break_start)->format('Y-m-d H:i:s')
                : $attendance->break_start;

            $attendance->break_end = $application->new_break_end
                ? Carbon::parse($application->new_break_end)->format('Y-m-d H:i:s')
                : $attendance->break_end;

            $attendance->break2_start = $application->new_break2_start
                ? Carbon::parse($application->new_break2_start)->format('Y-m-d H:i:s')
                : $attendance->break2_start;

            $attendance->break2_end = $application->new_break2_end
                ? Carbon::parse($application->new_break2_end)->format('Y-m-d H:i:s')
                : $attendance->break2_end;

            $attendance->note = $application->note;

            $attendance->save();

            // 申請ステータスを書き換え
            $application->status = 'approved';
            $application->save();
        });

        return redirect()
            ->route('admin.requests.index', ['tab' => 'approved'])
            ->with('success', '申請を承認しました。');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'attendance_id' => 'required|exists:attendances,id',
            'user_id' => 'required|exists:users,id',
            'new_clock_in' => 'nullable|date_format:H:i',
            'new_clock_out' => 'nullable|date_format:H:i',
            'new_break_start' => 'nullable|date_format:H:i',
            'new_break_end' => 'nullable|date_format:H:i',
            'note' => 'nullable|string',
        ]);

        // ✅ attendance_id から日付を取得
        $attendance = Attendance::findOrFail($validated['attendance_id']);
        $baseDate = Carbon::parse($attendance->date)->format('Y-m-d'); // 例: 2025-08-02

        // ✅ 時刻だけの値に日付を足して datetime 形式に変換
        foreach (['new_clock_in', 'new_clock_out', 'new_break_start', 'new_break_end'] as $field) {
            if (array_key_exists($field, $validated) && !empty($validated[$field])) {
                $validated[$field] = Carbon::createFromFormat('Y-m-d H:i', $baseDate . ' ' . $validated[$field]);
            } else {
                $validated[$field] = null;
            }
        }
        // ステータスを「pending（承認待ち）」にする
        $validated['status'] = 'pending';

        // 登録処理（$request->all()は絶対に使わないこと！）保存
        RequestApplication::create($validated);

        return redirect()->route('applications.index')->with('success', '申請を受け付けました。');
    }

    public function show($id)
    {
        $application = RequestApplication::with(['attendance', 'user'])->findOrFail($id);
        return view('request_applications.show', compact('application'));
    }

    public function applicationIndex()
    {
        $applications = \App\Models\RequestApplication::where('user_id', auth()->id())
            ->with('attendance')
            ->latest()
            ->get();

        return view('attendance.applications.index', compact('applications'));
    }

    public function confirm(Request $request)
    {
        $requestApp = new RequestApplication();
        $requestApp->user_id = auth()->id();
        $requestApp->attendance_id = $request->attendance_id;

        // カラム名に合わせて修正
        $requestApp->new_clock_in = $new_clock_in;
        $requestApp->new_clock_out = $new_clock_out;

        $requestApp->status = 'pending'; // ステータス設定
        $requestApp->save();

        return redirect()->route('requests.index');
    }
}

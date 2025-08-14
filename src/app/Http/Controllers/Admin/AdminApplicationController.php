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

        $applications = RequestApplication::with(['user', 'attendance'])
            ->latest()
            ->get();

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
}

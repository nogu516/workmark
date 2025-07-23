<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Attendance;
use App\Models\RequestApplication;
use Illuminate\Http\Request;

class RequestApplicationController extends Controller
{
    public function approve($id)
    {
        $request = RequestApplication::findOrFail($id);

        if ($request->status !== 'pending') {
            return back()->with('error', 'すでに承認済みまたは却下されています。');
        }

        $request->status = 'approved';
        $request->save();

        // 勤怠を更新
        $attendance = Attendance::find($request->attendance_id);
        if ($attendance) {
            $date = $attendance->date;

            $attendance->clock_in = $request->new_work_start ? "$date {$request->new_work_start}:00" : $attendance->clock_in;
            $attendance->clock_out = $request->new_work_end ? "$date {$request->new_work_end}:00" : $attendance->clock_out;

            // 追加：休憩時間を反映
            $attendance->break_start = $request->new_break_start ? "$date {$request->new_break_start}:00" : $attendance->break_start;
            $attendance->break_end = $request->new_break_end ? "$date {$request->new_break_end}:00" : $attendance->break_end;
            $attendance->break2_start = $request->new_break2_start ? "$date {$request->new_break2_start}:00" : $attendance->break2_start;
            $attendance->break2_end = $request->new_break2_end ? "$date {$request->new_break2_end}:00" : $attendance->break2_end;

            $attendance->note = $request->note;

            $attendance->save();
        }

        return redirect()->back()->with('success', '勤怠に反映しました');
    }
}

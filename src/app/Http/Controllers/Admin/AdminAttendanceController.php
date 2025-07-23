<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Carbon\Carbon;
use App\Models\Attendance;
use App\Models\User;
use Illuminate\Http\Request;

class AdminAttendanceController extends Controller
{
    public function index(Request $request)
    {
        // 日付の初期値は「今日」
        $date = $request->input('date') ? Carbon::parse($request->input('date')) : Carbon::today();

        // 「前日」ボタンが押されたとき
        if ($request->has('prev')) {
            $date->subDay();
        }

        // 「翌日」ボタンが押されたとき
        if ($request->has('next')) {
            $date->addDay();
        }

        // 該当日の日付を元に勤怠情報を取得（例：日付が一致するもの）
        $attendances = Attendance::with('user')
            ->whereDate('clock_in', $date->toDateString())
            ->get();

        return view('admin.attendance.index', [
            'date' => $date,
            'year' => $date->year,
            'month' => $date->month,
            'day' => $date->day,
            'attendances' => $attendances,
        ]);
    }

    public function show($id, $year, $month)
    {
        $attendance = Attendance::findOrFail($id);

        return view('admin.attendance.show', compact('attendance', 'year', 'month'));
    }
}

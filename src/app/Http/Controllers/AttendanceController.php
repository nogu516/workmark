<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Attendance; // Attendance モデルを使用
use Illuminate\Support\Facades\Auth; // ユーザー認証用
use App\Models\RequestApplication; // 勤怠修正申請モデルを使用

class AttendanceController extends Controller
{
    /**
     * 勤怠登録画面の表示処理
     */
    public function index()
    {
        // 今日の日付を取得
        $today = now()->toDateString();

        // 今日の勤怠レコードを取得または新規作成
        $attendance = Attendance::firstOrCreate([
            'user_id' => Auth::id(), // 認証ユーザーのID
            'date' => $today,
        ]);

        // Bladeビューに attendance を渡して表示
        return view('attendance.index', compact('attendance'));
    }

    /**
     * 出勤処理
     */
    public function clockIn(Request $request)
    {
        $today = now()->toDateString();

        $attendance = Attendance::firstOrCreate([
            'user_id' => Auth::id(),
            'date' => $today,
        ]);

        $attendance->clock_in = now();
        $attendance->save();

        return redirect()->route('attendance.index');
    }

    /**
     * 退勤処理
     */
    public function clockOut(Request $request)
    {
        $today = now()->toDateString();

        $attendance = Attendance::where('user_id', Auth::id())
            ->where('date', $today)
            ->first();

        if ($attendance) {
            $attendance->clock_out = now();
            $attendance->save();
        }

        return redirect()->route('attendance.index');
    }

    /**
     * 休憩開始処理
     */
    public function breakStart(Request $request)
    {
        $today = now()->toDateString();

        $attendance = Attendance::where('user_id', Auth::id())
            ->where('date', $today)
            ->first();

        if ($attendance && !$attendance->break_start) {
            $attendance->break_start = now();
            $attendance->save();
        }

        return redirect()->route('attendance.index');
    }

    /**
     * 休憩終了処理
     */
    public function breakEnd(Request $request)
    {
        $today = now()->toDateString();

        $attendance = Attendance::where('user_id', Auth::id())
            ->where('date', $today)
            ->first();

        if ($attendance && !$attendance->break_end) {
            $attendance->break_end = now();
            $attendance->save();
        }

        return redirect()->route('attendance.index');
    }

    public function list(Request $request)
    {
        $year = $request->input('year', now()->year);
        $month = $request->input('month', now()->month);

        $startDate = now()->setDate($year, $month, 1)->startOfMonth();
        $endDate = $startDate->copy()->endOfMonth();

        $user = Auth::user();
        $attendances = Attendance::where('user_id', $user->id)
            ->whereBetween('date', [$startDate, $endDate])
            ->orderBy('clock_in', 'desc')
            ->get()
            ->keyBy('date');

        return view('attendance.list', compact('attendances', 'year', 'month'));
    }

    public function show($id)
    {
        $attendance = Attendance::findOrFail($id); // ← 勤怠データを取得
        return view('attendance.show', compact('attendance')); // ← 渡す！
    }

    public function update(Request $request, $id)
    {
        $attendance = Attendance::where('id', $id)
            ->where('user_id', auth()->id())
            ->firstOrFail();

        $request->validate([
            'clock_in'     => 'nullable|date_format:H:i',
            'clock_out'    => 'nullable|date_format:H:i',
            'break_start'  => 'nullable|date_format:H:i',
            'break_end'    => 'nullable|date_format:H:i',
            'break2_start' => 'nullable|date_format:H:i',
            'break2_end' => 'nullable|date_format:H:i',
            'note' => 'nullable|string|max:255',
        ]);

        $date = $attendance->date;

        // 時刻文字列 → datetime に変換
        $attendance->clock_in = $request->clock_in ? "$date {$request->clock_in}:00" : null;
        $attendance->clock_out = $request->clock_out ? "$date {$request->clock_out}:00" : null;
        $attendance->break_start = $request->break_start ? "$date {$request->break_start}:00" : null;
        $attendance->break_end = $request->break_end ? "$date {$request->break_end}:00" : null;
        $attendance->break2_start = $request->break2_start ? "{$attendance->date} {$request->break2_start}:00" : null;
        $attendance->break2_end = $request->break2_end ? "{$attendance->date} {$request->break2_end}:00" : null;
        $attendance->note = $request->note;

        $attendance->save();

        return redirect()->route('attendance.show', $attendance->id)->with('success', '勤怠を更新しました');
    }

    public function confirm(Request $request, $id)
    {
        $attendance = Attendance::findOrFail($id);

        $data = [
            'attendance_id' => $id,
            'user_id' => auth()->id(),
            'new_clock_in' => $request->input('clock_in'),
            'new_clock_out' => $request->input('clock_out'),
            'new_break_start' => $request->input('break_start'),
            'new_break_end' => $request->input('break_end'),
            'new_break2_start' => $request->input('break2_start'),
            'new_break2_end' => $request->input('break2_end'),
            'note' => $request->input('note'),
        ];

        return view('attendance.confirm', compact('attendance', 'data'));
    }
}

<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Support\Facades\Response;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Models\Attendance;

class AdminUserController extends Controller
{
    public function index()
    {
        $users = User::all();

        return view('admin.users.index', compact('users'));
    }

    public function attendances(User $user, Request $request)
    {
        // 日付（年月）の取得（GETパラメータ or 現在）
    $date = $request->input('date') ? Carbon::parse($request->input('date')) : Carbon::today();
    $year = $date->year;
    $month = $date->month;

    // ユーザーの該当月の勤怠データ取得
    $attendances = Attendance::where('user_id', $user->id)
        ->whereYear('clock_in', $year)
        ->whereMonth('clock_in', $month)
        ->get();

    return view('admin.users.attendances', [
        'user' => $user,
        'attendances' => $attendances,
        'date' => $date,
        'year' => $year,
        'month' => $month,
    ]);
    }

    public function exportCsv(User $user, Request $request)
    {
        $year = $request->input('year') ?? now()->year;
        $month = $request->input('month') ?? now()->month;

        $attendances = $user->attendances()
            ->whereYear('clock_in', $year)
            ->whereMonth('clock_in', $month)
            ->get()
            ->keyBy(fn($a) => \Carbon\Carbon::parse($a->clock_in)->toDateString());

        $daysInMonth = \Carbon\Carbon::create($year, $month, 1)->daysInMonth;

        $csv = [];
        $csv[] = ['日付', '出勤', '退勤', '休憩（分）', '合計時間（分）'];

        for ($d = 1; $d <= $daysInMonth; $d++) {
            $date = \Carbon\Carbon::create($year, $month, $d)->toDateString();
            $attendance = $attendances[$date] ?? null;

            $clockIn = $attendance->clock_in ?? '';
            $clockOut = $attendance->clock_out ?? '';

            $break = ($attendance && $attendance->break_start && $attendance->break_end)
                ? \Carbon\Carbon::parse($attendance->break_start)->diffInMinutes($attendance->break_end)
                : 0;

            $total = ($attendance && $attendance->clock_in && $attendance->clock_out)
                ? \Carbon\Carbon::parse($attendance->clock_in)->diffInMinutes($attendance->clock_out) - $break
                : 0;

            $csv[] = [
                $date,
                $clockIn ? \Carbon\Carbon::parse($clockIn)->format('H:i') : '',
                $clockOut ? \Carbon\Carbon::parse($clockOut)->format('H:i') : '',
                $break ?: '',
                $total ?: '',
            ];
        }

        $filename = "{$user->name}_{$year}_{$month}_勤怠.csv";

        $output = fopen('php://temp', 'r+');
        foreach ($csv as $row) {
            fputcsv($output, $row);
        }
        rewind($output);
        $csvContent = stream_get_contents($output);
        fclose($output);

        return Response::make($csvContent, 200, [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => "attachment; filename=\"{$filename}\"",
        ]);
    }

    public function show(User $user, Request $request)
    {
        $baseDate = $request->input('date') ? Carbon::parse($request->input('date')) : Carbon::today();

        // 前月・翌月ボタン処理
        if ($request->has('prev')) {
            $baseDate->subMonth();
        } elseif ($request->has('next')) {
            $baseDate->addMonth();
        }

        $startOfMonth = $baseDate->copy()->startOfMonth();
        $endOfMonth = $baseDate->copy()->endOfMonth();

        $attendances = Attendance::where('user_id', $user->id)
            ->whereBetween('clock_in', [$startOfMonth, $endOfMonth])
            ->get()
            ->keyBy(function ($item) {
                return Carbon::parse($item->clock_in)->toDateString();
            });

        return view('admin.users.attendances', [
            'user' => $user,
            'date' => $baseDate,
            'attendances' => $attendances,
        ]);

    }
}

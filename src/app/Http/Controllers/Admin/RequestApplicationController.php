<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\RequestApplication;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use App\Models\Attendance;

class RequestApplicationController extends Controller
{
    public function index(Request $request)
    {
        $tab = $request->get('tab', 'pending');

        $requestApplications = RequestApplication::where('user_id', auth()->id())->get();

        $applications = RequestApplication::with(['user', 'attendance'])
            ->where('user_id', Auth::id())
            ->where('status', $tab)
            ->latest()
            ->get();

        return view('admin.requests.index', [
            'applications' => $applications,
            'tab' => $tab,
            'request' => $request,
        ]);
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
        $requestApp->start_time = $request->start_time;
        $requestApp->end_time = $request->end_time;
        $requestApp->status = 'pending'; // ← これがないと表示されない
        $requestApp->save();

        return redirect()->route('requests.index');
    }

    public function destroy($id)
    {
        $application = RequestApplication::findOrFail($id);
        $application->delete();

        return redirect()->route('admin.request-applications.index')
            ->with('success', '申請を削除しました。');
    }
}

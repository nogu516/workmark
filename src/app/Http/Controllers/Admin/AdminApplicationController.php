<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\RequestApplication;
use Illuminate\Http\Request;

class AdminApplicationController extends Controller
{
    public function index(Request $request)
    {

        $tab = $request->get('tab', 'pending'); // デフォルトは承認待ち

        $applications = RequestApplication::with(['user', 'attendance'])
            ->where('status', $tab)
            ->latest()
            ->get();

        return view('admin.requests.index', compact('applications', 'tab'));
    }
    public function show(RequestApplication $application)
    {
        return view('admin.requests.show', compact('application'));
    }

    public function approve(RequestApplication $application)
    {
        // 勤怠情報の更新
        $attendance = $application->attendance;
        $attendance->update([
            'clock_in'     => $application->new_clock_in,
            'clock_out'    => $application->new_clock_out,
            'break_start'  => $application->new_break_start,
            'break_end'    => $application->new_break_end,
        ]);

        // 申請ステータス更新
        $application->status = 'approved';
        $application->save();

        return redirect()->route('admin.requests.index', ['tab' => 'pending'])->with('success', '申請を承認しました');
    }
}

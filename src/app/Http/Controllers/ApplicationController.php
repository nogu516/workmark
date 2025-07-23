<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Attendance;

class ApplicationController extends Controller
{
    /**
     * 申請一覧画面を表示（承認待ち / 承認済み 切り替え）
     */
    public function index(Request $request)
    {
        // クエリパラメータ ?tab=pending または ?tab=approved を取得
        $tab = $request->get('tab', 'pending'); // デフォルトは「承認待ち」

        // ステータスでフィルタリングされた勤怠データを取得
        $applications = Attendance::where('status', $tab)->orderBy('date', 'desc')->get();

        // ビューに渡す
        return view('applications.index', compact('applications', 'tab'));
    }
}

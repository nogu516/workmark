<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\RequestApplication;
use Illuminate\Http\Request;

class AdminRequestController extends Controller
{
    public function index(Request $request)
    {
        $tab = $request->input('tab', 'pending');

        $query = RequestApplication::with(['user', 'attendance']);

        if ($tab === 'pending') {
            $query->where('status', 'pending');
        } elseif ($tab === 'approved') {
            $query->where('status', 'approved');
        }

        // $applications = $query->orderBy('created_at', 'desc')->get();
        $applications = RequestApplication::with('attendance', 'user')->where('status', $tab)->get();

        return view('admin.requests.index', compact('applications', 'tab'));
    }

    public function destroy($id)
    {
        $request_application = RequestApplication::findOrFail($id);
        $request_application->delete();

        return redirect()->route('admin.request-applications.index')->with('success', '削除しました');
    }
}

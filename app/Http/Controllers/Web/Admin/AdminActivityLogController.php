<?php

namespace App\Http\Controllers\Web\Admin;

use App\Http\Controllers\Controller;
use App\Models\ActivityLog;
use Illuminate\Http\Request;
use Illuminate\View\View;

final class AdminActivityLogController extends Controller
{
    public function index(Request $request): View
    {
        $logs = ActivityLog::with('user')->when($request->filled('q'), fn ($q) => $q->where('description', 'like', '%'.$request->q.'%')->orWhere('action', 'like', '%'.$request->q.'%'))
            ->when($request->filled('application'), fn ($q) => $q->where('application', $request->application))
            ->latest()->paginate(20)->withQueryString();
        $applications = ActivityLog::query()->distinct()->orderBy('application')->pluck('application');
        return view('admin/activity/index', compact('logs','applications'));
    }
}

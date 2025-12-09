<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\ActivityLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ActivityLogController extends Controller
{
    /**
     * Display activity logs for current user
     */
    public function index(Request $request)
    {
        $user = Auth::guard('user')->user();

        // Base query - only show current user's logs
        $query = ActivityLog::where('user_id', $user->id)
            ->orderBy('created_at', 'desc');

        // Filters
        if ($request->filled('action')) {
            $query->where('action', $request->action);
        }

        if ($request->filled('module')) {
            $query->where('module', $request->module);
        }

        if ($request->filled('date_from')) {
            $query->whereDate('created_at', '>=', $request->date_from);
        }

        if ($request->filled('date_to')) {
            $query->whereDate('created_at', '<=', $request->date_to);
        }

        if ($request->filled('search')) {
            $query->where(function($q) use ($request) {
                $q->where('description', 'like', '%' . $request->search . '%')
                  ->orWhere('ip_address', 'like', '%' . $request->search . '%');
            });
        }

        $logs = $query->paginate(20)->withQueryString();

        // Get unique actions and modules for filter dropdowns (from user's logs only)
        $actions = ActivityLog::where('user_id', $user->id)
            ->distinct()
            ->pluck('action')
            ->filter()
            ->sort()
            ->values();

        $modules = ActivityLog::where('user_id', $user->id)
            ->distinct()
            ->pluck('module')
            ->filter()
            ->sort()
            ->values();

        // Statistics for current user
        $stats = [
            'total' => ActivityLog::where('user_id', $user->id)->count(),
            'today' => ActivityLog::where('user_id', $user->id)
                ->whereDate('created_at', today())
                ->count(),
            'logins' => ActivityLog::where('user_id', $user->id)
                ->where('action', 'login')
                ->whereDate('created_at', today())
                ->count(),
            'activities' => ActivityLog::where('user_id', $user->id)
                ->whereIn('action', ['create', 'update', 'delete', 'download', 'upload'])
                ->whereDate('created_at', today())
                ->count(),
        ];

        return view('user.activity-log.index', compact('logs', 'actions', 'modules', 'stats'));
    }

    /**
     * Show detail of specific log
     */
    public function show($id)
    {
        $user = Auth::guard('user')->user();

        // Only allow viewing own logs
        $log = ActivityLog::where('user_id', $user->id)
            ->findOrFail($id);

        return view('user.activity-log.show', compact('log'));
    }
}

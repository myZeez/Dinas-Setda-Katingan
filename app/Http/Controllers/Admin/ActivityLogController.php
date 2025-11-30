<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ActivityLog;
use Illuminate\Http\Request;

class ActivityLogController extends Controller
{
    /**
     * Display activity logs
     */
    public function index(Request $request)
    {
        $query = ActivityLog::with('user')->orderBy('created_at', 'desc');

        // Filter by user
        if ($request->user_id) {
            $query->where('user_id', $request->user_id);
        }

        // Filter by action
        if ($request->action) {
            $query->where('action', $request->action);
        }

        // Filter by module
        if ($request->module) {
            $query->where('module', $request->module);
        }

        // Filter by date range
        if ($request->date_from) {
            $query->whereDate('created_at', '>=', $request->date_from);
        }
        if ($request->date_to) {
            $query->whereDate('created_at', '<=', $request->date_to);
        }

        // Search
        if ($request->search) {
            $query->where(function($q) use ($request) {
                $q->where('description', 'like', '%' . $request->search . '%')
                  ->orWhere('user_name', 'like', '%' . $request->search . '%')
                  ->orWhere('ip_address', 'like', '%' . $request->search . '%');
            });
        }

        $logs = $query->paginate(20);

        // Get unique values for filters
        $actions = ActivityLog::distinct()->pluck('action');
        $modules = ActivityLog::distinct()->pluck('module');

        // Stats
        $stats = [
            'total' => ActivityLog::count(),
            'today' => ActivityLog::whereDate('created_at', today())->count(),
            'logins' => ActivityLog::where('action', 'login')->whereDate('created_at', today())->count(),
            'changes' => ActivityLog::whereIn('action', ['create', 'update', 'delete'])->whereDate('created_at', today())->count(),
        ];

        return view('admin.activity-log.index', compact('logs', 'actions', 'modules', 'stats'));
    }

    /**
     * Show detail of a log
     */
    public function show($id)
    {
        $log = ActivityLog::with('user')->findOrFail($id);
        return view('admin.activity-log.show', compact('log'));
    }

    /**
     * Clear old logs (older than 30 days)
     */
    public function clear(Request $request)
    {
        $days = $request->input('days', 30);
        $date = now()->subDays($days);

        $deleted = ActivityLog::where('created_at', '<', $date)->delete();

        ActivityLog::log('delete', 'activity_log', "Menghapus {$deleted} log aktivitas yang lebih lama dari {$days} hari");

        return back()->with('success', "Berhasil menghapus {$deleted} log aktivitas.");
    }
}

<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\PengajuanLayanan;

class DashboardController extends Controller
{
    /**
     * Show dashboard
     */
    public function index()
    {
        $user = Auth::guard('user')->user();

        // Get real statistics from database
        $stats = [
            'total_layanan' => PengajuanLayanan::where('user_id', $user->id)->count(),
            'layanan_proses' => PengajuanLayanan::where('user_id', $user->id)
                ->whereNotIn('status', ['selesai', 'ditolak'])
                ->count(),
            'layanan_selesai' => PengajuanLayanan::where('user_id', $user->id)
                ->where('status', 'selesai')
                ->count(),
            'layanan_ditolak' => PengajuanLayanan::where('user_id', $user->id)
                ->where('status', 'ditolak')
                ->count(),
            'layanan_bulan_ini' => PengajuanLayanan::where('user_id', $user->id)
                ->whereMonth('created_at', date('m'))
                ->whereYear('created_at', date('Y'))
                ->count(),
        ];

        // Recent pengajuan
        $recentPengajuan = PengajuanLayanan::where('user_id', $user->id)
            ->with('jenisLayanan')
            ->orderBy('created_at', 'desc')
            ->limit(5)
            ->get();

        return view('user.dashboard', compact('user', 'stats', 'recentPengajuan'));
    }
}

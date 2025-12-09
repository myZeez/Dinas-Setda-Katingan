<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Berita;
use App\Models\JenisLayanan;
use App\Models\InformasiPublik;
use App\Models\Visitor;
use App\Models\PengajuanLayanan;
use App\Models\User;

class DashboardController extends Controller
{
    public function index()
    {
        $stats = [
            'total_berita' => Berita::count(),
            'total_layanan' => JenisLayanan::count(),
            'total_informasi' => InformasiPublik::count(),
            'total_pengajuan' => PengajuanLayanan::count(),
            'total_user' => User::where('role', 'user')->count(),
            'pengajuan_proses' => PengajuanLayanan::whereNotIn('status', ['selesai', 'ditolak'])->count(),
            'pengajuan_selesai' => PengajuanLayanan::where('status', 'selesai')->count(),
            'pengajuan_bulan_ini' => PengajuanLayanan::whereMonth('created_at', date('m'))
                ->whereYear('created_at', date('Y'))
                ->count(),
        ];

        // Visitor Statistics
        $visitorStats = [
            'total' => Visitor::getTotalCount(),
            'today' => Visitor::getTodayCount(),
            'this_month' => Visitor::getThisMonthCount(),
            'this_year' => Visitor::getThisYearCount(),
        ];

        // Monthly visitor data for chart (current year)
        $currentYear = date('Y');
        $monthlyVisitors = Visitor::getMonthlyStats($currentYear);
        $visitorChartData = [];
        for ($month = 1; $month <= 12; $month++) {
            $visitorChartData[$month] = $monthlyVisitors->get($month)->total_visits ?? 0;
        }

        // Recent pengajuan layanan
        $recentPengajuan = PengajuanLayanan::with(['user', 'jenisLayanan'])
            ->orderBy('created_at', 'desc')
            ->limit(5)
            ->get();

        return view('admin.dashboard', compact('stats', 'visitorStats', 'visitorChartData', 'currentYear', 'recentPengajuan'));
    }
}

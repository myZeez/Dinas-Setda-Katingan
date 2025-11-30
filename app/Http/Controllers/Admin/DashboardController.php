<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Berita;
use App\Models\JenisLayanan;
use App\Models\InformasiPublik;
use App\Models\Visitor;

class DashboardController extends Controller
{
    public function index()
    {
        $stats = [
            'total_berita' => Berita::count(),
            'total_layanan' => JenisLayanan::count(),
            'total_informasi' => InformasiPublik::count(),
            'total_kontrak' => 0,
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

        // Data Capaian Tahunan Kerja Sama (bisa diganti dengan data dari database)
        $capaian = [
            'ksd' => [
                2020 => 72, 2021 => 81, 2022 => 72, 2023 => 77, 2024 => 76, 2025 => 69
            ],
            'kspk' => [
                2020 => 74, 2021 => 81, 2022 => 78, 2023 => 77, 2024 => 77, 2025 => 78
            ],
            'nks' => [
                2020 => 0, 2021 => 0, 2022 => 0, 2023 => 79, 2024 => 79, 2025 => 82
            ]
        ];

        // Data Kendala Tahunan (bisa diganti dengan data dari database)
        $kendala = [
            '2022' => '1. Koordinasi antar instansi belum optimal
2. Keterbatasan anggaran
3. SDM yang belum memadai',
            '2023' => '1. Proses administrasi yang panjang
2. Perubahan regulasi
3. Komunikasi yang kurang efektif',
            '2024' => '1. Adaptasi sistem baru
2. Keterlambatan pelaporan
3. Koordinasi lintas sektor',
            '2025' => '1. Penyesuaian kebijakan pusat
2. Optimalisasi sumber daya
3. Peningkatan kapasitas SDM',
        ];

        return view('admin.dashboard', compact('stats', 'capaian', 'kendala', 'visitorStats', 'visitorChartData', 'currentYear'));
    }
}

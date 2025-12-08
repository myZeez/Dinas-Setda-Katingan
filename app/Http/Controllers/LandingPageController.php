<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Berita;
use App\Models\Pegawai;
use App\Models\UnitKerja;
use App\Models\InformasiPublik;
use App\Models\KategoriInformasi;
use App\Models\Carousel;
use App\Models\VideoYoutube;
use App\Models\PemimpinDaerah;
use App\Models\Visitor;
use App\Helpers\SettingHelper;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class LandingPageController extends Controller
{
    public function index()
    {
        // Get carousels
        $carousels = Carousel::active()->ordered()->get();

        // Get latest news (no is_sorotan column, just get latest published)
        $beritaTerbaru = Berita::where('status', 'published')
            ->orderBy('created_at', 'desc')
            ->take(8)
            ->get();

        // Get pemimpin daerah from new table
        $pemimpinDaerahs = PemimpinDaerah::active()->ordered()->get();

        // Get pimpinan (leadership) from pegawai as fallback
        $pimpinan = Pegawai::whereHas('unitKerja', function($q) {
            $q->where('nama', 'like', '%Pimpinan%')
              ->orWhere('nama', 'like', '%Kepala%')
              ->orWhere('nama', 'like', '%Sekretaris%');
        })->orderBy('urutan')->get();

        // If no pimpinan found, get top pegawai
        if ($pimpinan->isEmpty()) {
            $pimpinan = Pegawai::orderBy('urutan')->take(5)->get();
        }

        // Get unit kerja for structure
        $unitKerjas = UnitKerja::with(['pegawais' => function($q) {
            $q->orderBy('urutan');
        }])->orderBy('urutan')->get();

        // Get YouTube videos
        $videos = VideoYoutube::active()->ordered()->take(6)->get();

        // Settings
        $settings = SettingHelper::all();

        // Get visitor statistics
        $currentYear = date('Y');
        $previousYear = $currentYear - 1;

        $visitorStats = [
            'current_year' => $currentYear,
            'previous_year' => $previousYear,
            'current_year_data' => $this->getMonthlyVisitorData($currentYear),
            'previous_year_data' => $this->getMonthlyVisitorData($previousYear),
            'total_visitors' => Visitor::getTotalCount(),
            'today_visitors' => Visitor::getTodayCount(),
            'this_month_visitors' => Visitor::getThisMonthCount(),
            'this_year_visitors' => Visitor::getThisYearCount(),
        ];

        // Get informasi publik categories untuk landing page
        $kategoriPemerintahan = KategoriInformasi::where('slug', 'informasi-publik-bagian-pemerintahan')->first();
        $kategoriKewilayahan = KategoriInformasi::where('slug', 'informasi-kewilayahan')->first();
        $kategoriKerjaSama = KategoriInformasi::where('slug', 'informasi-kerja-sama')->first();

        // Get latest informasi for each category
        $infoPemerintahan = $kategoriPemerintahan
            ? InformasiPublik::where('kategori_informasi_id', $kategoriPemerintahan->id)
                ->where('is_active', true)
                ->orderBy('created_at', 'desc')
                ->take(3)
                ->get()
            : collect();

        $infoKewilayahan = $kategoriKewilayahan
            ? InformasiPublik::where('kategori_informasi_id', $kategoriKewilayahan->id)
                ->where('is_active', true)
                ->orderBy('created_at', 'desc')
                ->take(3)
                ->get()
            : collect();

        $infoKerjaSama = $kategoriKerjaSama
            ? InformasiPublik::where('kategori_informasi_id', $kategoriKerjaSama->id)
                ->where('is_active', true)
                ->orderBy('created_at', 'desc')
                ->take(3)
                ->get()
            : collect();

        return view('landing.index', compact(
            'carousels',
            'beritaTerbaru',
            'pemimpinDaerahs',
            'pimpinan',
            'unitKerjas',
            'videos',
            'settings',
            'visitorStats',
            'kategoriPemerintahan',
            'kategoriKewilayahan',
            'kategoriKerjaSama',
            'infoPemerintahan',
            'infoKewilayahan',
            'infoKerjaSama'
        ));
    }

    /**
     * Get monthly visitor data for a specific year
     */
    private function getMonthlyVisitorData($year)
    {
        $monthlyStats = Visitor::getMonthlyStats($year);

        $data = [];
        for ($month = 1; $month <= 12; $month++) {
            $data[$month] = $monthlyStats->get($month)->total_visits ?? 0;
        }

        return $data;
    }

    public function berita()
    {
        $beritas = Berita::where('status', 'published')
            ->orderBy('created_at', 'desc')
            ->paginate(12);

        return view('landing.berita', compact('beritas'));
    }

    public function beritaDetail($slug)
    {
        $berita = Berita::where('slug', $slug)
            ->where('status', 'published')
            ->firstOrFail();

        // Increment views
        $berita->increment('views');

        // Related news
        $relatedBerita = Berita::where('status', 'published')
            ->where('id', '!=', $berita->id)
            ->orderBy('created_at', 'desc')
            ->take(3)
            ->get();

        return view('landing.berita-detail', compact('berita', 'relatedBerita'));
    }

    public function strukturOrganisasi()
    {
        $unitKerjas = UnitKerja::with(['pegawais' => function($q) {
            $q->orderBy('urutan');
        }])->orderBy('urutan')->get();

        return view('landing.struktur-organisasi', compact('unitKerjas'));
    }

    public function layanan()
    {
        $jenisLayanans = \App\Models\JenisLayanan::where('is_active', true)->get();

        return view('landing.layanan', compact('jenisLayanans'));
    }

    public function informasiPublik($kategori)
    {
        $kategoriInfo = KategoriInformasi::where('slug', $kategori)->firstOrFail();

        $informasis = InformasiPublik::with('jenisDokumen')
            ->where('kategori_informasi_id', $kategoriInfo->id)
            ->orderBy('created_at', 'desc')
            ->paginate(12);

        return view('landing.informasi-publik', compact('kategoriInfo', 'informasis'));
    }

    public function informasiDetail($kategori, $id)
    {
        $kategoriInfo = KategoriInformasi::where('slug', $kategori)->firstOrFail();

        $informasi = InformasiPublik::with('jenisDokumen')
            ->where('kategori_informasi_id', $kategoriInfo->id)
            ->findOrFail($id);

        return view('landing.informasi-detail', compact('kategoriInfo', 'informasi'));
    }

    public function downloadInformasi($kategori, $id)
    {
        $kategoriInfo = KategoriInformasi::where('slug', $kategori)->firstOrFail();

        $informasi = InformasiPublik::where('kategori_informasi_id', $kategoriInfo->id)
            ->findOrFail($id);

        if ($informasi->file_path && Storage::disk('public')->exists($informasi->file_path)) {
            return Storage::disk('public')->download($informasi->file_path, $informasi->judul . '.' . pathinfo($informasi->file_path, PATHINFO_EXTENSION));
        }

        return back()->with('error', 'File tidak ditemukan');
    }

    public function kontak()
    {
        $settings = SettingHelper::all();
        return view('landing.kontak', compact('settings'));
    }
}

<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\AuthController as AdminAuthController;
use App\Http\Controllers\Admin\DashboardController as AdminDashboardController;
use App\Http\Controllers\Admin\LayananController as AdminLayananController;
use App\Http\Controllers\Admin\BeritaController as AdminBeritaController;
use App\Http\Controllers\Admin\StrukturOrganisasiController;
use App\Http\Controllers\Admin\InformasiPublikController;
use App\Http\Controllers\Admin\SettingController;
use App\Http\Controllers\Admin\ActivityLogController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\KontenPublikController;
use App\Http\Controllers\Admin\ContohDokumenController;
use App\Http\Controllers\User\AuthController as UserAuthController;
use App\Http\Controllers\User\DashboardController as UserDashboardController;
use App\Http\Controllers\User\LayananController;
use App\Http\Controllers\User\ProfileController;
use App\Http\Controllers\LandingPageController;

// Landing Page Routes
Route::get('/', [LandingPageController::class, 'index'])->name('landing');
Route::get('/berita', [LandingPageController::class, 'berita'])->name('landing.berita');
Route::get('/berita/{slug}', [LandingPageController::class, 'beritaDetail'])->name('landing.berita.detail');
Route::get('/struktur-organisasi', [LandingPageController::class, 'strukturOrganisasi'])->name('landing.struktur');
Route::get('/layanan', [LandingPageController::class, 'layanan'])->name('landing.layanan');
Route::get('/informasi/{kategori}', [LandingPageController::class, 'informasiPublik'])->name('landing.informasi');
Route::get('/informasi/{kategori}/{id}', [LandingPageController::class, 'informasiDetail'])->name('landing.informasi.detail');
Route::get('/informasi/{kategori}/{id}/download', [LandingPageController::class, 'downloadInformasi'])->name('landing.informasi.download');

// Admin Routes
Route::prefix('admin')->name('admin.')->group(function () {
    // Auth Routes (Guest Only)
    Route::middleware('guest')->group(function () {
        Route::get('/login', [AdminAuthController::class, 'showLogin'])->name('login');
        Route::post('/login', [AdminAuthController::class, 'login'])->name('login.submit');
    });

    // Protected Routes (Auth Required)
    Route::middleware('admin')->group(function () {
        Route::get('/', [AdminDashboardController::class, 'index'])->name('dashboard');
        Route::get('/dashboard', [AdminDashboardController::class, 'index'])->name('dashboard.index');
        Route::post('/logout', [AdminAuthController::class, 'logout'])->name('logout');

        // Layanan Bag. Pemerintahan
        Route::get('/layanan', [AdminLayananController::class, 'index'])->name('layanan');
        Route::get('/layanan/pengajuan', [AdminLayananController::class, 'pengajuan'])->name('layanan.pengajuan');
        Route::get('/layanan/pengajuan/{id}', [AdminLayananController::class, 'detail'])->name('layanan.detail');
        Route::put('/layanan/pengajuan/{id}/status', [AdminLayananController::class, 'updateStatus'])->name('layanan.update-status');
        // SP Routes
        Route::post('/layanan/pengajuan/{id}/approve-sp', [AdminLayananController::class, 'approveSuratPenawaran'])->name('layanan.approve-sp');
        Route::post('/layanan/pengajuan/{id}/revisi-sp', [AdminLayananController::class, 'revisiSuratPenawaran'])->name('layanan.revisi-sp');
        // KAK Routes
        Route::post('/layanan/pengajuan/{id}/approve-kak', [AdminLayananController::class, 'approveKAK'])->name('layanan.approve-kak');
        Route::post('/layanan/pengajuan/{id}/revisi-kak', [AdminLayananController::class, 'revisiKAK'])->name('layanan.revisi-kak');
        // Other Layanan Routes
        Route::put('/layanan/pengajuan/{id}/dokumen/{dokumenId}', [AdminLayananController::class, 'updateDokumen'])->name('layanan.update-dokumen');
        Route::post('/layanan/pengajuan/{id}/upload-hasil', [AdminLayananController::class, 'uploadHasil'])->name('layanan.upload-hasil');
        Route::get('/layanan/pengajuan/{id}/dokumen/{dokumenId}/download', [AdminLayananController::class, 'downloadDokumen'])->name('layanan.download');
        Route::get('/layanan/jenis', [AdminLayananController::class, 'jenisLayanan'])->name('layanan.jenis');
        Route::put('/layanan/jenis/{id}', [AdminLayananController::class, 'updateJenisLayanan'])->name('layanan.update-jenis');

        // Berita
        Route::resource('berita', AdminBeritaController::class)->parameters(['berita' => 'berita']);
        Route::post('/berita/upload-image', [AdminBeritaController::class, 'uploadImage'])->name('berita.upload-image');

        // Struktur Organisasi
        Route::get('/struktur', [StrukturOrganisasiController::class, 'index'])->name('struktur.index');
        Route::get('/struktur/pegawai-unit/{unitKerjaId}', [StrukturOrganisasiController::class, 'getPegawai'])->name('struktur.pegawai-unit');
        Route::get('/struktur/unit-kerja', [StrukturOrganisasiController::class, 'unitKerja'])->name('struktur.unit-kerja');
        Route::post('/struktur/unit-kerja', [StrukturOrganisasiController::class, 'storeUnitKerja'])->name('struktur.unit-kerja.store');
        Route::put('/struktur/unit-kerja/{id}', [StrukturOrganisasiController::class, 'updateUnitKerja'])->name('struktur.unit-kerja.update');
        Route::delete('/struktur/unit-kerja/{id}', [StrukturOrganisasiController::class, 'destroyUnitKerja'])->name('struktur.unit-kerja.destroy');
        Route::get('/struktur/pegawai', [StrukturOrganisasiController::class, 'pegawai'])->name('struktur.pegawai');
        Route::post('/struktur/pegawai', [StrukturOrganisasiController::class, 'storePegawai'])->name('struktur.pegawai.store');
        Route::put('/struktur/pegawai/{id}', [StrukturOrganisasiController::class, 'updatePegawai'])->name('struktur.pegawai.update');
        Route::delete('/struktur/pegawai/{id}', [StrukturOrganisasiController::class, 'destroyPegawai'])->name('struktur.pegawai.destroy');

        // Informasi Publik
        Route::get('/informasi/{kategoriSlug}', [InformasiPublikController::class, 'index'])->name('informasi-publik.index');
        Route::get('/informasi/{kategoriSlug}/create', [InformasiPublikController::class, 'create'])->name('informasi-publik.create');
        Route::post('/informasi/{kategoriSlug}', [InformasiPublikController::class, 'store'])->name('informasi-publik.store');
        Route::get('/informasi/{kategoriSlug}/jenis-dokumen', [InformasiPublikController::class, 'jenisDokumen'])->name('informasi-publik.jenis-dokumen');
        Route::post('/informasi/{kategoriSlug}/jenis-dokumen', [InformasiPublikController::class, 'storeJenisDokumen'])->name('informasi-publik.jenis-dokumen.store');
        Route::put('/informasi/{kategoriSlug}/jenis-dokumen/{id}', [InformasiPublikController::class, 'updateJenisDokumen'])->name('informasi-publik.jenis-dokumen.update');
        Route::delete('/informasi/{kategoriSlug}/jenis-dokumen/{id}', [InformasiPublikController::class, 'destroyJenisDokumen'])->name('informasi-publik.jenis-dokumen.destroy');
        Route::get('/informasi/{kategoriSlug}/{id}', [InformasiPublikController::class, 'show'])->name('informasi-publik.show');
        Route::get('/informasi/{kategoriSlug}/{id}/edit', [InformasiPublikController::class, 'edit'])->name('informasi-publik.edit');
        Route::put('/informasi/{kategoriSlug}/{id}', [InformasiPublikController::class, 'update'])->name('informasi-publik.update');
        Route::delete('/informasi/{kategoriSlug}/{id}', [InformasiPublikController::class, 'destroy'])->name('informasi-publik.destroy');
        Route::get('/informasi/{kategoriSlug}/{id}/download/{type?}', [InformasiPublikController::class, 'download'])->name('informasi-publik.download');
        Route::get('/informasi/{kategoriSlug}/{id}/view', [InformasiPublikController::class, 'view'])->name('informasi-publik.view');

        // Settings
        Route::get('/settings/profile', [SettingController::class, 'profile'])->name('settings.profile');
        Route::put('/settings/profile', [SettingController::class, 'updateProfile'])->name('settings.profile.update');
        Route::put('/settings/password', [SettingController::class, 'updatePassword'])->name('settings.password.update');
        Route::get('/settings/website', [SettingController::class, 'website'])->name('settings.website');
        Route::put('/settings/website', [SettingController::class, 'updateWebsite'])->name('settings.website.update');

        // Konten Publik - Carousel
        Route::get('/konten-publik/carousel', [KontenPublikController::class, 'carouselIndex'])->name('konten-publik.carousel');
        Route::get('/konten-publik/carousel/create', [KontenPublikController::class, 'carouselCreate'])->name('konten-publik.carousel.create');
        Route::post('/konten-publik/carousel', [KontenPublikController::class, 'carouselStore'])->name('konten-publik.carousel.store');
        Route::get('/konten-publik/carousel/{id}/edit', [KontenPublikController::class, 'carouselEdit'])->name('konten-publik.carousel.edit');
        Route::put('/konten-publik/carousel/{id}', [KontenPublikController::class, 'carouselUpdate'])->name('konten-publik.carousel.update');
        Route::delete('/konten-publik/carousel/{id}', [KontenPublikController::class, 'carouselDestroy'])->name('konten-publik.carousel.destroy');
        Route::post('/konten-publik/carousel/update-order', [KontenPublikController::class, 'carouselUpdateOrder'])->name('konten-publik.carousel.update-order');

        // Konten Publik - Video YouTube
        Route::get('/konten-publik/video', [KontenPublikController::class, 'videoIndex'])->name('konten-publik.video');
        Route::get('/konten-publik/video/create', [KontenPublikController::class, 'videoCreate'])->name('konten-publik.video.create');
        Route::post('/konten-publik/video', [KontenPublikController::class, 'videoStore'])->name('konten-publik.video.store');
        Route::get('/konten-publik/video/{id}/edit', [KontenPublikController::class, 'videoEdit'])->name('konten-publik.video.edit');
        Route::put('/konten-publik/video/{id}', [KontenPublikController::class, 'videoUpdate'])->name('konten-publik.video.update');
        Route::delete('/konten-publik/video/{id}', [KontenPublikController::class, 'videoDestroy'])->name('konten-publik.video.destroy');

        // Konten Publik - Pemimpin Daerah
        Route::get('/konten-publik/pemimpin', [KontenPublikController::class, 'pemimpinIndex'])->name('konten-publik.pemimpin');
        Route::get('/konten-publik/pemimpin/create', [KontenPublikController::class, 'pemimpinCreate'])->name('konten-publik.pemimpin.create');
        Route::post('/konten-publik/pemimpin', [KontenPublikController::class, 'pemimpinStore'])->name('konten-publik.pemimpin.store');
        Route::post('/konten-publik/pemimpin/reorder', [KontenPublikController::class, 'pemimpinReorder'])->name('konten-publik.pemimpin.reorder');
        Route::get('/konten-publik/pemimpin/{id}/edit', [KontenPublikController::class, 'pemimpinEdit'])->name('konten-publik.pemimpin.edit');
        Route::put('/konten-publik/pemimpin/{id}', [KontenPublikController::class, 'pemimpinUpdate'])->name('konten-publik.pemimpin.update');
        Route::delete('/konten-publik/pemimpin/{id}', [KontenPublikController::class, 'pemimpinDestroy'])->name('konten-publik.pemimpin.destroy');

        // Contoh Dokumen
        Route::get('/contoh-dokumen', [ContohDokumenController::class, 'index'])->name('contoh-dokumen.index');
        Route::get('/contoh-dokumen/create', [ContohDokumenController::class, 'create'])->name('contoh-dokumen.create');
        Route::post('/contoh-dokumen', [ContohDokumenController::class, 'store'])->name('contoh-dokumen.store');
        Route::get('/contoh-dokumen/{contohDokumen}/edit', [ContohDokumenController::class, 'edit'])->name('contoh-dokumen.edit');
        Route::put('/contoh-dokumen/{contohDokumen}', [ContohDokumenController::class, 'update'])->name('contoh-dokumen.update');
        Route::delete('/contoh-dokumen/{contohDokumen}', [ContohDokumenController::class, 'destroy'])->name('contoh-dokumen.destroy');
        Route::get('/contoh-dokumen/{contohDokumen}/download', [ContohDokumenController::class, 'download'])->name('contoh-dokumen.download');
        Route::get('/contoh-dokumen/{contohDokumen}/preview', [ContohDokumenController::class, 'preview'])->name('contoh-dokumen.preview');

        // Super Admin Only Routes
        Route::middleware('super_admin')->group(function () {
            // Activity Log
            Route::get('/activity-log', [ActivityLogController::class, 'index'])->name('activity-log.index');
            Route::get('/activity-log/{id}', [ActivityLogController::class, 'show'])->name('activity-log.show');
            Route::post('/activity-log/clear', [ActivityLogController::class, 'clear'])->name('activity-log.clear');

            // User Management - Admin
            Route::get('/users/admin', [UserController::class, 'adminIndex'])->name('users.admin');
            Route::get('/users/admin/create', [UserController::class, 'createAdmin'])->name('users.admin.create');
            Route::post('/users/admin', [UserController::class, 'storeAdmin'])->name('users.admin.store');
            Route::get('/users/admin/{id}/edit', [UserController::class, 'editAdmin'])->name('users.admin.edit');
            Route::put('/users/admin/{id}', [UserController::class, 'updateAdmin'])->name('users.admin.update');
            Route::delete('/users/admin/{id}', [UserController::class, 'destroyAdmin'])->name('users.admin.destroy');

            // User Management - User Masyarakat
            Route::get('/users/user', [UserController::class, 'userIndex'])->name('users.user');
            Route::get('/users/user/{id}', [UserController::class, 'showUser'])->name('users.user.show');
            Route::delete('/users/user/{id}', [UserController::class, 'destroyUser'])->name('users.user.destroy');

            // Toggle Status (both admin and user)
            Route::patch('/users/{id}/toggle-status', [UserController::class, 'toggleStatus'])->name('users.toggle-status');
        });
    });
});

// User Routes
Route::prefix('layanan')->name('user.')->group(function () {
    // Auth Routes (Guest Only)
    Route::middleware('guest')->group(function () {
        Route::get('/login', [UserAuthController::class, 'showLogin'])->name('login');
        Route::post('/login', [UserAuthController::class, 'login'])->name('login.submit');
        Route::get('/register', [UserAuthController::class, 'showRegister'])->name('register');
        Route::post('/register', [UserAuthController::class, 'register'])->name('register.submit');
    });

    // Protected Routes (Auth Required)
    Route::middleware('user')->group(function () {
        Route::get('/dashboard', [UserDashboardController::class, 'index'])->name('dashboard');
        Route::post('/logout', [UserAuthController::class, 'logout'])->name('logout');

        // Layanan - Alur baru
        Route::get('/ajukan', [LayananController::class, 'index'])->name('layanan');
        Route::get('/ajukan/{kode}', [LayananController::class, 'create'])->name('layanan.create');
        Route::post('/ajukan/{kode}', [LayananController::class, 'store'])->name('layanan.store');

        // Riwayat & Detail
        Route::get('/riwayat', [LayananController::class, 'riwayat'])->name('layanan.riwayat');
        Route::get('/riwayat/{id}', [LayananController::class, 'detail'])->name('layanan.detail');

        // Upload ulang Surat Penawaran (jika revisi)
        Route::post('/riwayat/{id}/reupload-sp', [LayananController::class, 'reuploadSuratPenawaran'])->name('layanan.reupload-sp');

        // Upload KAK (setelah SP disetujui atau revisi KAK)
        Route::post('/riwayat/{id}/upload-kak', [LayananController::class, 'uploadKAK'])->name('layanan.upload-kak');

        // Upload Link Nota Kesepakatan (setelah KAK disetujui)
        Route::post('/riwayat/{id}/upload-nota', [LayananController::class, 'uploadNotaKesepakatan'])->name('layanan.upload-nota');

        // Contoh Dokumen (untuk user)
        Route::get('/contoh-dokumen/{id}/download', [LayananController::class, 'downloadContohDokumen'])->name('contoh-dokumen.download');

        // Profile
        Route::get('/profil', [ProfileController::class, 'index'])->name('profile');
        Route::put('/profil', [ProfileController::class, 'update'])->name('profile.update');
        Route::put('/profil/password', [ProfileController::class, 'updatePassword'])->name('profile.password');
    });
});

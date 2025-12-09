@extends('admin.layouts.app')

@section('title', 'Dashboard')

@section('content')
<div class="page-header">
    <h1 class="page-title">Dashboard</h1>
    <p class="page-subtitle">Selamat datang di Panel Administrator Setda Katingan</p>
</div>

<!-- Stats Cards -->
<div class="row g-4 mb-4">
    <div class="col-sm-6 col-xl-3">
        <div class="card stat-card">
            <div class="d-flex justify-content-between align-items-start">
                <div>
                    <p class="stat-value">{{ $stats['total_berita'] ?? 0 }}</p>
                    <p class="stat-label">Total Berita</p>
                </div>
                <div class="stat-icon blue">
                    <i class="bi bi-newspaper"></i>
                </div>
            </div>
        </div>
    </div>
    <div class="col-sm-6 col-xl-3">
        <div class="card stat-card">
            <div class="d-flex justify-content-between align-items-start">
                <div>
                    <p class="stat-value">{{ $stats['total_layanan'] ?? 0 }}</p>
                    <p class="stat-label">Total Layanan</p>
                </div>
                <div class="stat-icon green">
                    <i class="bi bi-building-gear"></i>
                </div>
            </div>
        </div>
    </div>
    <div class="col-sm-6 col-xl-3">
        <div class="card stat-card">
            <div class="d-flex justify-content-between align-items-start">
                <div>
                    <p class="stat-value">{{ $stats['total_informasi'] ?? 0 }}</p>
                    <p class="stat-label">Total Informasi</p>
                </div>
                <div class="stat-icon orange">
                    <i class="bi bi-info-circle"></i>
                </div>
            </div>
        </div>
    </div>
    <div class="col-sm-6 col-xl-3">
        <div class="card stat-card">
            <div class="d-flex justify-content-between align-items-start">
                <div>
                    <p class="stat-value">{{ $stats['total_pengajuan'] ?? 0 }}</p>
                    <p class="stat-label">Total Pengajuan</p>
                </div>
                <div class="stat-icon red">
                    <i class="bi bi-file-earmark-text"></i>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Additional Stats Cards -->
<div class="row g-4 mb-4">
    <div class="col-sm-6 col-xl-3">
        <div class="card stat-card">
            <div class="d-flex justify-content-between align-items-start">
                <div>
                    <p class="stat-value">{{ $stats['total_user'] ?? 0 }}</p>
                    <p class="stat-label">Total User</p>
                </div>
                <div class="stat-icon purple">
                    <i class="bi bi-people"></i>
                </div>
            </div>
        </div>
    </div>
    <div class="col-sm-6 col-xl-3">
        <div class="card stat-card">
            <div class="d-flex justify-content-between align-items-start">
                <div>
                    <p class="stat-value">{{ $stats['pengajuan_proses'] ?? 0 }}</p>
                    <p class="stat-label">Pengajuan Diproses</p>
                </div>
                <div class="stat-icon warning">
                    <i class="bi bi-hourglass-split"></i>
                </div>
            </div>
        </div>
    </div>
    <div class="col-sm-6 col-xl-3">
        <div class="card stat-card">
            <div class="d-flex justify-content-between align-items-start">
                <div>
                    <p class="stat-value">{{ $stats['pengajuan_selesai'] ?? 0 }}</p>
                    <p class="stat-label">Pengajuan Selesai</p>
                </div>
                <div class="stat-icon success">
                    <i class="bi bi-check-circle"></i>
                </div>
            </div>
        </div>
    </div>
    <div class="col-sm-6 col-xl-3">
        <div class="card stat-card">
            <div class="d-flex justify-content-between align-items-start">
                <div>
                    <p class="stat-value">{{ $stats['pengajuan_bulan_ini'] ?? 0 }}</p>
                    <p class="stat-label">Pengajuan Bulan Ini</p>
                </div>
                <div class="stat-icon info">
                    <i class="bi bi-calendar-event"></i>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Visitor Statistics Cards -->
<div class="row g-4 mb-4">
    <div class="col-12">
        <div class="card">
            <div class="card-header bg-transparent border-0 pt-4 pb-0 px-4">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h5 class="card-title mb-1"><i class="bi bi-graph-up text-primary me-2"></i>Statistik Pengunjung Website</h5>
                        <p class="text-muted small mb-0">Data kunjungan website secara real-time</p>
                    </div>
                </div>
            </div>
            <div class="card-body p-4">
                <div class="row g-4">
                    <div class="col-sm-6 col-lg-3">
                        <div class="visitor-stat-card">
                            <div class="visitor-stat-icon" style="background: linear-gradient(135deg, #3b82f6 0%, #1d4ed8 100%);">
                                <i class="bi bi-people-fill"></i>
                            </div>
                            <div class="visitor-stat-info">
                                <span class="visitor-stat-value">{{ number_format($visitorStats['total'] ?? 0) }}</span>
                                <span class="visitor-stat-label">Total Pengunjung</span>
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-6 col-lg-3">
                        <div class="visitor-stat-card">
                            <div class="visitor-stat-icon" style="background: linear-gradient(135deg, #22c55e 0%, #16a34a 100%);">
                                <i class="bi bi-calendar-check"></i>
                            </div>
                            <div class="visitor-stat-info">
                                <span class="visitor-stat-value">{{ number_format($visitorStats['today'] ?? 0) }}</span>
                                <span class="visitor-stat-label">Hari Ini</span>
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-6 col-lg-3">
                        <div class="visitor-stat-card">
                            <div class="visitor-stat-icon" style="background: linear-gradient(135deg, #f97316 0%, #ea580c 100%);">
                                <i class="bi bi-calendar-month"></i>
                            </div>
                            <div class="visitor-stat-info">
                                <span class="visitor-stat-value">{{ number_format($visitorStats['this_month'] ?? 0) }}</span>
                                <span class="visitor-stat-label">Bulan Ini</span>
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-6 col-lg-3">
                        <div class="visitor-stat-card">
                            <div class="visitor-stat-icon" style="background: linear-gradient(135deg, #8b5cf6 0%, #7c3aed 100%);">
                                <i class="bi bi-calendar-range"></i>
                            </div>
                            <div class="visitor-stat-info">
                                <span class="visitor-stat-value">{{ number_format($visitorStats['this_year'] ?? 0) }}</span>
                                <span class="visitor-stat-label">Tahun Ini</span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Visitor Chart -->
                <div class="mt-4">
                    <h6 class="mb-3"><i class="bi bi-bar-chart me-2"></i>Grafik Pengunjung Bulanan {{ $currentYear ?? date('Y') }}</h6>
                    <div style="position: relative; height: 280px; width: 100%;">
                        <canvas id="visitorChart"></canvas>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row g-4">
    <!-- Recent Activities -->
    <div class="col-lg-8">
        <div class="card">
            <div class="card-header bg-transparent border-0 pt-4 pb-0 px-4">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h5 class="card-title mb-1">Berita Terbaru</h5>
                        <p class="text-muted small mb-0">Berita yang baru dipublikasikan</p>
                    </div>
                    <a href="#" class="btn btn-sm btn-primary">
                        <i class="bi bi-plus-lg me-1"></i> Tambah
                    </a>
                </div>
            </div>
            <div class="card-body p-4">
                <div class="text-center py-5">
                    <i class="bi bi-newspaper text-muted" style="font-size: 48px;"></i>
                    <p class="text-muted mt-3">Belum ada berita</p>
                    <a href="#" class="btn btn-outline-primary btn-sm">Buat Berita Pertama</a>
                </div>
            </div>
        </div>
    </div>

    <!-- Quick Actions -->
    <div class="col-lg-4">
        <div class="card">
            <div class="card-header bg-transparent border-0 pt-4 pb-0 px-4">
                <h5 class="card-title mb-1">Aksi Cepat</h5>
                <p class="text-muted small mb-0">Menu yang sering digunakan</p>
            </div>
            <div class="card-body p-4">
                <div class="d-grid gap-2">
                    <a href="#" class="btn btn-light text-start py-3 px-3">
                        <i class="bi bi-plus-circle text-primary me-2"></i>
                        Tambah Berita Baru
                    </a>
                    <a href="#" class="btn btn-light text-start py-3 px-3">
                        <i class="bi bi-building-gear text-success me-2"></i>
                        Tambah Layanan
                    </a>
                    <a href="#" class="btn btn-light text-start py-3 px-3">
                        <i class="bi bi-info-circle text-warning me-2"></i>
                        Tambah Informasi
                    </a>
                    <a href="#" class="btn btn-light text-start py-3 px-3">
                        <i class="bi bi-file-earmark-plus text-info me-2"></i>
                        Upload Kontrak
                    </a>
                </div>
            </div>
        </div>

        <!-- Info Card -->
        <div class="card mt-4" style="background: linear-gradient(135deg, #2563eb 0%, #1d4ed8 100%);">
            <div class="card-body p-4 text-white">
                <h5 class="mb-2">Butuh Bantuan?</h5>
                <p class="mb-3 opacity-75" style="font-size: 14px;">Hubungi tim teknis jika mengalami kendala dalam menggunakan sistem.</p>
                <a href="#" class="btn btn-light btn-sm">
                    <i class="bi bi-headset me-1"></i> Hubungi Support
                </a>
            </div>
        </div>
    </div>
</div>

<!-- System Info -->
<div class="row mt-4">
    <div class="col-12">
        <div class="card">
            <div class="card-body p-4">
                <div class="row align-items-center">
                    <div class="col-auto">
                        <div class="stat-icon blue">
                            <i class="bi bi-info-circle"></i>
                        </div>
                    </div>
                    <div class="col">
                        <h6 class="mb-1">Informasi Sistem</h6>
                        <p class="text-muted mb-0 small">Website Profil Sekretariat Daerah Kabupaten Katingan | Laravel Framework | Terakhir diupdate: {{ now()->format('d M Y H:i') }}</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Pengajuan Layanan Terbaru -->
<div class="row mt-4">
    <div class="col-12">
        <div class="card">
            <div class="card-header bg-transparent border-0 pt-4 pb-0 px-4">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h5 class="card-title mb-1"><i class="bi bi-file-earmark-text text-primary me-2"></i>Pengajuan Layanan Terbaru</h5>
                        <p class="text-muted small mb-0">5 pengajuan layanan terakhir</p>
                    </div>
                    <a href="{{ route('admin.layanan.pengajuan') }}" class="btn btn-sm btn-outline-primary">
                        <i class="bi bi-eye me-1"></i> Lihat Semua
                    </a>
                </div>
            </div>
            <div class="card-body p-4">
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead class="table-light">
                            <tr>
                                <th>No. Pengajuan</th>
                                <th>Pemohon</th>
                                <th>Jenis Layanan</th>
                                <th>Tanggal</th>
                                <th>Status</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($recentPengajuan as $pengajuan)
                            <tr>
                                <td><span class="badge bg-secondary">{{ $pengajuan->nomor_pengajuan }}</span></td>
                                <td>
                                    <div class="fw-medium">{{ $pengajuan->user->name ?? '-' }}</div>
                                    <small class="text-muted">{{ $pengajuan->user->instansi ?? '-' }}</small>
                                </td>
                                <td>{{ $pengajuan->jenisLayanan->nama ?? '-' }}</td>
                                <td>
                                    <small>{{ $pengajuan->created_at->format('d M Y') }}</small><br>
                                    <small class="text-muted">{{ $pengajuan->created_at->format('H:i') }}</small>
                                </td>
                                <td>
                                    <span class="badge bg-{{ $pengajuan->status_color ?? 'secondary' }}">
                                        {{ $pengajuan->status_label ?? $pengajuan->status }}
                                    </span>
                                </td>
                                <td>
                                    <a href="{{ route('admin.layanan.detail', $pengajuan->id) }}" class="btn btn-sm btn-outline-primary">
                                        <i class="bi bi-eye"></i>
                                    </a>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="6" class="text-center py-4">
                                    <i class="bi bi-inbox text-muted" style="font-size: 2rem;"></i>
                                    <p class="text-muted mt-2">Belum ada pengajuan layanan</p>
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    // =============================================
    // VISITOR CHART
    // =============================================
    const visitorChartData = @json($visitorChartData ?? []);
    const monthLabels = ['Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun', 'Jul', 'Agu', 'Sep', 'Okt', 'Nov', 'Des'];

    const visitorCtx = document.getElementById('visitorChart');
    if (visitorCtx) {
        // Convert data to array format
        const visitorDataArray = monthLabels.map((_, i) => visitorChartData[i + 1] || 0);

        // Destroy existing chart if exists
        if (window.visitorChartInstance) {
            window.visitorChartInstance.destroy();
        }

        window.visitorChartInstance = new Chart(visitorCtx.getContext('2d'), {
            type: 'line',
            data: {
                labels: monthLabels,
                datasets: [{
                    label: 'Pengunjung',
                    data: visitorDataArray,
                    borderColor: '#f97316',
                    backgroundColor: 'rgba(249, 115, 22, 0.1)',
                    borderWidth: 3,
                    pointBackgroundColor: '#f97316',
                    pointBorderColor: '#fff',
                    pointBorderWidth: 2,
                    pointRadius: 5,
                    pointHoverRadius: 7,
                    tension: 0.4,
                    fill: true,
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                animation: {
                    duration: 1000,
                    easing: 'easeOutQuart'
                },
                interaction: {
                    intersect: false,
                    mode: 'index'
                },
                plugins: {
                    legend: { display: false },
                    tooltip: {
                        backgroundColor: 'rgba(0, 0, 0, 0.8)',
                        padding: 12,
                        displayColors: false,
                        callbacks: {
                            label: function(context) {
                                return context.parsed.y.toLocaleString() + ' pengunjung';
                            }
                        }
                    }
                },
                scales: {
                    x: {
                        grid: { display: false },
                        ticks: { font: { size: 11 }, color: '#64748b' }
                    },
                    y: {
                        beginAtZero: true,
                        grid: { color: 'rgba(0, 0, 0, 0.05)', drawBorder: false },
                        ticks: {
                            font: { size: 11 },
                            color: '#64748b',
                            callback: function(value) {
                                if (value >= 1000) return (value/1000).toFixed(0) + 'k';
                                return value;
                            }
                        }
                    }
                }
            }
        });
    }
</script>
@endpush

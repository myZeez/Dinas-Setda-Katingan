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
                    <p class="stat-value">{{ $stats['total_kontrak'] ?? 0 }}</p>
                    <p class="stat-label">Total Kontrak</p>
                </div>
                <div class="stat-icon red">
                    <i class="bi bi-file-earmark-text"></i>
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

<!-- Capaian & Kendala Tahunan Kerja Sama -->
<div class="row mt-4 g-4">
    <!-- Capaian Tahunan Kerja Sama -->
    <div class="col-lg-7">
        <div class="card">
            <div class="card-header bg-transparent border-0 pt-4 pb-0 px-4">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h5 class="card-title mb-1">Capaian Tahunan Kerja Sama</h5>
                        <p class="text-muted small mb-0">Statistik kerja sama per tahun</p>
                    </div>
                    <button class="btn btn-sm btn-outline-primary" data-bs-toggle="modal" data-bs-target="#editCapaianModal">
                        <i class="bi bi-pencil me-1"></i> Edit Data
                    </button>
                </div>
            </div>
            <div class="card-body p-4">
                <div style="position: relative; height: 280px; width: 100%;">
                    <canvas id="capaianChart"></canvas>
                </div>
                <div class="mt-3 d-flex justify-content-center gap-4 flex-wrap">
                    <div class="d-flex align-items-center gap-2">
                        <div style="width: 12px; height: 12px; background: #3b82f6; border-radius: 2px;"></div>
                        <small class="text-muted">Kerja Sama Daerah</small>
                    </div>
                    <div class="d-flex align-items-center gap-2">
                        <div style="width: 12px; height: 12px; background: #f59e0b; border-radius: 2px;"></div>
                        <small class="text-muted">Kerja Sama Pihak Ketiga</small>
                    </div>
                    <div class="d-flex align-items-center gap-2">
                        <div style="width: 12px; height: 12px; background: #22c55e; border-radius: 2px;"></div>
                        <small class="text-muted">Nota Kesepakatan Sinergi</small>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Kendala Tahunan Kerja Sama -->
    <div class="col-lg-5">
        <div class="card h-100">
            <div class="card-header bg-transparent border-0 pt-4 pb-0 px-4">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h5 class="card-title mb-1">Kendala Tahunan Kerja Sama</h5>
                        <p class="text-muted small mb-0">Catatan kendala per tahun</p>
                    </div>
                    <button class="btn btn-sm btn-outline-primary" data-bs-toggle="modal" data-bs-target="#editKendalaModal">
                        <i class="bi bi-pencil me-1"></i> Edit
                    </button>
                </div>
            </div>
            <div class="card-body p-4">
                <div class="table-responsive">
                    <table class="table table-bordered table-sm" id="kendalaTable">
                        <thead class="table-light">
                            <tr class="text-center">
                                <th>2022</th>
                                <th>2023</th>
                                <th>2024</th>
                                <th>2025</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td class="small p-2" id="kendala2022">{{ $kendala['2022'] ?? '-' }}</td>
                                <td class="small p-2" id="kendala2023">{{ $kendala['2023'] ?? '-' }}</td>
                                <td class="small p-2" id="kendala2024">{{ $kendala['2024'] ?? '-' }}</td>
                                <td class="small p-2" id="kendala2025">{{ $kendala['2025'] ?? '-' }}</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
                <div class="mt-3 p-3 bg-light rounded">
                    <p class="small text-muted mb-0"><em>Klik tombol Edit untuk mengubah data kendala tahunan.</em></p>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal Edit Capaian -->
<div class="modal fade" id="editCapaianModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Edit Data Capaian Tahunan</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form id="formCapaian">
                <div class="modal-body">
                    <div class="table-responsive">
                        <table class="table table-bordered">
                            <thead class="table-light">
                                <tr>
                                    <th>Tahun</th>
                                    <th>Kerja Sama Daerah</th>
                                    <th>Kerja Sama Pihak Ketiga</th>
                                    <th>Nota Kesepakatan</th>
                                </tr>
                            </thead>
                            <tbody>
                                @for($year = 2020; $year <= 2025; $year++)
                                <tr>
                                    <td class="fw-bold">{{ $year }}</td>
                                    <td><input type="number" class="form-control form-control-sm" name="ksd_{{ $year }}" value="{{ $capaian['ksd'][$year] ?? 0 }}"></td>
                                    <td><input type="number" class="form-control form-control-sm" name="kspk_{{ $year }}" value="{{ $capaian['kspk'][$year] ?? 0 }}"></td>
                                    <td><input type="number" class="form-control form-control-sm" name="nks_{{ $year }}" value="{{ $capaian['nks'][$year] ?? 0 }}"></td>
                                </tr>
                                @endfor
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary">
                        <i class="bi bi-check-lg me-1"></i> Simpan
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal Edit Kendala -->
<div class="modal fade" id="editKendalaModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Edit Kendala Tahunan Kerja Sama</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form id="formKendala">
                <div class="modal-body">
                    <div class="row g-3">
                        @for($year = 2022; $year <= 2025; $year++)
                        <div class="col-md-6">
                            <label class="form-label fw-bold">Kendala {{ $year }}</label>
                            <textarea class="form-control" name="kendala_{{ $year }}" rows="4" placeholder="Masukkan kendala tahun {{ $year }}...">{{ $kendala[$year] ?? '' }}</textarea>
                        </div>
                        @endfor
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary">
                        <i class="bi bi-check-lg me-1"></i> Simpan
                    </button>
                </div>
            </form>
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

    // =============================================
    // CAPAIAN CHART
    // =============================================
    // Data Capaian (dari database atau default)
    const capaianData = {
        ksd: {!! json_encode($capaian['ksd'] ?? [2020 => 72, 2021 => 81, 2022 => 72, 2023 => 77, 2024 => 76, 2025 => 69]) !!},
        kspk: {!! json_encode($capaian['kspk'] ?? [2020 => 74, 2021 => 81, 2022 => 78, 2023 => 77, 2024 => 77, 2025 => 78]) !!},
        nks: {!! json_encode($capaian['nks'] ?? [2020 => 0, 2021 => 0, 2022 => 0, 2023 => 79, 2024 => 79, 2025 => 82]) !!}
    };

    // Create Chart
    const ctx = document.getElementById('capaianChart').getContext('2d');

    // Destroy existing chart if exists
    if (window.capaianChartInstance) {
        window.capaianChartInstance.destroy();
    }

    window.capaianChartInstance = new Chart(ctx, {
        type: 'bar',
        data: {
            labels: ['2020', '2021', '2022', '2023', '2024', '2025'],
            datasets: [
                {
                    label: 'Kerja Sama Daerah',
                    data: Object.values(capaianData.ksd),
                    backgroundColor: '#3b82f6',
                    borderRadius: 4,
                },
                {
                    label: 'Kerja Sama Pihak Ketiga',
                    data: Object.values(capaianData.kspk),
                    backgroundColor: '#f59e0b',
                    borderRadius: 4,
                },
                {
                    label: 'Nota Kesepakatan Sinergi',
                    data: Object.values(capaianData.nks),
                    backgroundColor: '#22c55e',
                    borderRadius: 4,
                }
            ]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            animation: {
                duration: 1000,
                easing: 'easeOutQuart'
            },
            plugins: {
                legend: {
                    display: false
                }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    max: 100,
                    ticks: {
                        stepSize: 10
                    }
                }
            }
        }
    });

    // Handle form submissions
    document.getElementById('formCapaian').addEventListener('submit', function(e) {
        e.preventDefault();
        // Here you would send AJAX request to save data
        alert('Data capaian berhasil disimpan! (Demo)');
        bootstrap.Modal.getInstance(document.getElementById('editCapaianModal')).hide();
        location.reload();
    });

    document.getElementById('formKendala').addEventListener('submit', function(e) {
        e.preventDefault();
        // Here you would send AJAX request to save data
        alert('Data kendala berhasil disimpan! (Demo)');
        bootstrap.Modal.getInstance(document.getElementById('editKendalaModal')).hide();
        location.reload();
    });
</script>
@endpush

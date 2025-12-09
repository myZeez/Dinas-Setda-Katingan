@extends('landing.layouts.app')

@section('title', 'Informasi ' . $kategoriInfo->nama)

@section('content')
<!-- Page Header -->
<section class="page-header">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-lg-8">
                <nav class="breadcrumb-nav" data-aos="fade-up">
                    <a href="{{ route('landing') }}">Beranda</a>
                    <span>/</span>
                    <a href="#">Informasi Publik</a>
                    <span>/</span>
                    <span>{{ $kategoriInfo->nama }}</span>
                </nav>
                <h1 class="page-title" data-aos="fade-up" data-aos-delay="100">{{ $kategoriInfo->nama }}</h1>
                <p class="page-subtitle" data-aos="fade-up" data-aos-delay="200">
                    {{ $kategoriInfo->deskripsi ?? 'Informasi dan dokumen publik ' . $kategoriInfo->nama }}
                </p>
            </div>
        </div>
    </div>
</section>

<!-- Informasi Section -->
<section class="section section-light">
    <div class="container">
        <!-- Category Tabs -->
        <div class="category-tabs mb-4" data-aos="fade-up">
            <a href="{{ route('landing.informasi', 'informasi-publik-bagian-pemerintahan') }}" class="category-tab {{ $kategoriInfo->slug == 'informasi-publik-bagian-pemerintahan' ? 'active' : '' }}">
                <i class="bi bi-building"></i> Bag. Pemerintahan
            </a>
            <a href="{{ route('landing.informasi', 'informasi-kewilayahan') }}" class="category-tab {{ $kategoriInfo->slug == 'informasi-kewilayahan' ? 'active' : '' }}">
                <i class="bi bi-map"></i> Bag. Kewilayahan
            </a>
            <a href="{{ route('landing.informasi', 'informasi-kerja-sama') }}" class="category-tab {{ $kategoriInfo->slug == 'informasi-kerja-sama' ? 'active' : '' }}">
                <i class="bi bi-people"></i> Bag. Kerja Sama
            </a>
        </div>

        @if($informasis->count() > 0)
            <!-- Filter dan Pencarian -->
            <div class="table-controls mb-4" data-aos="fade-up">
                <div class="row g-3 align-items-end">
                    <div class="col-md-4">
                        <label class="form-label fw-medium">
                            <i class="bi bi-search me-1"></i> Pencarian
                        </label>
                        <input type="text" id="searchInput" class="form-control" placeholder="Cari judul dokumen...">
                    </div>
                    <div class="col-md-3">
                        <label class="form-label fw-medium">
                            <i class="bi bi-funnel me-1"></i> Jenis Dokumen
                        </label>
                        <select id="filterJenis" class="form-select">
                            <option value="">Semua Jenis</option>
                            @php
                                $jenisOptions = $informasis->pluck('jenisDokumen.nama')->unique()->filter()->sort();
                            @endphp
                            @foreach($jenisOptions as $jenis)
                                <option value="{{ $jenis }}">{{ $jenis }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-3">
                        <label class="form-label fw-medium">
                            <i class="bi bi-calendar-range me-1"></i> Tahun
                        </label>
                        <select id="filterTahun" class="form-select">
                            <option value="">Semua Tahun</option>
                            @php
                                $years = $informasis->map(function($item) {
                                    return $item->tanggal ? \Carbon\Carbon::parse($item->tanggal)->year : $item->created_at->year;
                                })->unique()->sort()->reverse();
                            @endphp
                            @foreach($years as $year)
                                <option value="{{ $year }}">{{ $year }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-2">
                        <button type="button" id="resetFilter" class="btn btn-outline-secondary w-100">
                            <i class="bi bi-arrow-clockwise me-1"></i> Reset
                        </button>
                    </div>
                </div>
            </div>

            <!-- Tabel Dokumen -->
            <div class="table-responsive" data-aos="fade-up">
                <table class="table table-hover dokumen-table" id="dokumenTable">
                    <thead>
                        <tr>
                            <th width="5%">No</th>
                            <th width="35%">Judul Dokumen</th>
                            <th width="15%">Jenis</th>
                            <th width="15%">Nomor</th>
                            <th width="12%">Tanggal</th>
                            <th width="18%" class="text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($informasis as $index => $informasi)
                        <tr data-jenis="{{ $informasi->jenisDokumen->nama ?? '' }}"
                            data-tahun="{{ $informasi->tanggal ? \Carbon\Carbon::parse($informasi->tanggal)->year : $informasi->created_at->year }}"
                            data-search="{{ strtolower($informasi->judul . ' ' . ($informasi->nomor ?? '') . ' ' . ($informasi->jenisDokumen->nama ?? '')) }}">
                            <td class="text-center">{{ $informasis->firstItem() + $index }}</td>
                            <td>
                                <div class="d-flex align-items-start gap-2">
                                    <div class="doc-icon-small">
                                        @if($informasi->jenisDokumen)
                                            @php
                                                $icon = match(strtolower($informasi->jenisDokumen->nama)) {
                                                    'lkpj (laporan penyelenggaraan pemerintah daerah)', 'laporan' => 'bi-file-earmark-text',
                                                    'spm (standar pelayanan minimal)' => 'bi-list-check',
                                                    'keputusan bupati', 'peraturan bupati', 'peraturan daerah' => 'bi-journal-bookmark',
                                                    'nota kesepakatan (mou)', 'perjanjian kerja sama (pks)', 'kesepakatan bersama' => 'bi-file-earmark-medical',
                                                    default => 'bi-file-earmark-pdf'
                                                };
                                            @endphp
                                            <i class="bi {{ $icon }}"></i>
                                        @else
                                            <i class="bi bi-file-earmark-pdf"></i>
                                        @endif
                                    </div>
                                    <div>
                                        <div class="fw-medium">{{ $informasi->judul }}</div>
                                        @if($informasi->keterangan)
                                            <small class="text-muted">{{ Str::limit($informasi->keterangan, 60) }}</small>
                                        @endif
                                    </div>
                                </div>
                            </td>
                            <td>
                                @if($informasi->jenisDokumen)
                                    <span class="badge-jenis">{{ $informasi->jenisDokumen->nama }}</span>
                                @else
                                    <span class="text-muted">-</span>
                                @endif
                            </td>
                            <td>
                                <small class="text-muted">{{ $informasi->nomor ?? '-' }}</small>
                            </td>
                            <td>
                                <small>{{ $informasi->tanggal ? \Carbon\Carbon::parse($informasi->tanggal)->format('d M Y') : $informasi->created_at->format('d M Y') }}</small>
                            </td>
                            <td>
                                <div class="d-flex gap-2 justify-content-center">
                                    <a href="{{ route('landing.informasi.detail', [$kategoriInfo->slug, $informasi->id]) }}"
                                       class="btn-action btn-view" title="Lihat Detail">
                                        <i class="bi bi-eye"></i>
                                    </a>
                                    @if($informasi->file_dokumen)
                                        <a href="{{ route('landing.informasi.download', [$kategoriInfo->slug, $informasi->id]) }}"
                                           class="btn-action btn-download" title="Unduh Dokumen">
                                            <i class="bi bi-download"></i>
                                        </a>
                                    @endif
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <!-- No Results Message -->
            <div id="noResults" class="text-center py-5" style="display: none;">
                <i class="bi bi-search text-muted" style="font-size: 64px;"></i>
                <h4 class="mt-3">Tidak Ada Hasil</h4>
                <p class="text-muted">Tidak ada dokumen yang sesuai dengan filter yang dipilih</p>
            </div>

            <!-- Pagination -->
            @if($informasis->hasPages())
                <div class="d-flex justify-content-center mt-4" data-aos="fade-up">
                    <nav class="pagination-wrapper">
                        {{ $informasis->links() }}
                    </nav>
                </div>
            @endif
        @else
            <div class="text-center py-5">
                <i class="bi bi-folder text-muted" style="font-size: 80px;"></i>
                <h3 class="mt-4">Belum Ada Informasi</h3>
                <p class="text-muted">Informasi untuk kategori ini akan segera tersedia</p>
                <a href="{{ route('landing') }}" class="btn-primary-hero mt-3">
                    <i class="bi bi-house"></i> Kembali ke Beranda
                </a>
            </div>
        @endif
    </div>
</section>
@endsection

@push('styles')
<style>
    .category-tabs {
        display: flex;
        flex-wrap: wrap;
        gap: 15px;
        justify-content: center;
    }

    .category-tab {
        display: flex;
        align-items: center;
        gap: 8px;
        padding: 12px 24px;
        background: white;
        border-radius: 50px;
        color: #334155;
        text-decoration: none;
        font-weight: 500;
        transition: all 0.3s ease;
        box-shadow: 0 4px 15px rgba(0,0,0,0.05);
    }

    .category-tab:hover,
    .category-tab.active {
        background: linear-gradient(135deg, #dc2626, #b91c1c);
        color: white;
        transform: translateY(-2px);
        box-shadow: 0 8px 25px rgba(220, 38, 38, 0.3);
    }

    .dokumen-card {
        background: white;
        border-radius: 20px;
        padding: 30px;
        box-shadow: 0 10px 40px rgba(0,0,0,0.08);
        display: flex;
        flex-direction: column;
        transition: all 0.3s ease;
    }

    .dokumen-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 15px 50px rgba(0,0,0,0.12);
    }

    .dokumen-icon {
        width: 60px;
        height: 60px;
        background: linear-gradient(135deg, #dc2626, #b91c1c);
        border-radius: 15px;
        display: flex;
        align-items: center;
        justify-content: center;
        margin-bottom: 20px;
    }

    .dokumen-icon i {
        font-size: 28px;
        color: white;
    }

    .dokumen-content {
        flex: 1;
    }

    .dokumen-type {
        display: inline-block;
        background: #fef2f2;
        color: #dc2626;
        padding: 4px 12px;
        border-radius: 20px;
        font-size: 12px;
        font-weight: 500;
        margin-bottom: 12px;
    }

    .dokumen-title {
        font-size: 16px;
        font-weight: 600;
        color: #0a1628;
        line-height: 1.5;
        margin-bottom: 10px;
    }

    .dokumen-number {
        font-size: 13px;
        color: #64748b;
        margin-bottom: 8px;
    }

    .dokumen-date {
        font-size: 13px;
        color: #94a3b8;
        display: flex;
        align-items: center;
        gap: 6px;
        margin-bottom: 0;
    }

    .dokumen-actions {
        display: flex;
        gap: 10px;
        margin-top: 20px;
        padding-top: 20px;
        border-top: 1px solid #e2e8f0;
    }

    .dokumen-btn {
        flex: 1;
        padding: 10px;
        border-radius: 10px;
        text-align: center;
        text-decoration: none;
        font-size: 13px;
        font-weight: 500;
        transition: all 0.3s ease;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 6px;
    }

    .dokumen-btn.view {
        background: #f1f5f9;
        color: #334155;
    }

    .dokumen-btn.view:hover {
        background: #e2e8f0;
    }

    .dokumen-btn.download {
        background: linear-gradient(135deg, #dc2626, #b91c1c);
        color: white;
    }

    .dokumen-btn.download:hover {
        box-shadow: 0 5px 20px rgba(220, 38, 38, 0.3);
    }

    .pagination-wrapper .pagination {
        gap: 5px;
    }

    .pagination-wrapper .page-link {
        border: none;
        border-radius: 8px;
        padding: 10px 16px;
        color: #1e3a5f;
        background: white;
        box-shadow: 0 2px 10px rgba(0,0,0,0.05);
        transition: all 0.3s ease;
    }

    .pagination-wrapper .page-link:hover,
    .pagination-wrapper .page-item.active .page-link {
        background: linear-gradient(135deg, #dc2626, #b91c1c);
        color: white;
    }

    /* RESPONSIVE STYLES */
    /* TABLET (768px - 991px) */
    @media (max-width: 991px) {
        .category-tabs {
            gap: 12px;
        }

        .category-tab {
            padding: 10px 20px;
            font-size: 13px;
        }

        .dokumen-card {
            padding: 25px;
        }

        .dokumen-icon {
            width: 55px;
            height: 55px;
        }

        .dokumen-icon i {
            font-size: 24px;
        }

        .dokumen-title {
            font-size: 15px;
        }
    }

    /* SMARTPHONE (< 768px) */
    @media (max-width: 767px) {
        .category-tabs {
            gap: 8px;
            padding: 0 10px;
        }

        .category-tab {
            padding: 8px 14px;
            font-size: 12px;
            gap: 6px;
            border-radius: 30px;
        }

        .category-tab i {
            font-size: 14px;
        }

        .dokumen-card {
            padding: 20px;
            border-radius: 16px;
        }

        .dokumen-icon {
            width: 50px;
            height: 50px;
            margin-bottom: 15px;
            border-radius: 12px;
        }

        .dokumen-icon i {
            font-size: 22px;
        }

        .dokumen-type {
            font-size: 11px;
            padding: 3px 10px;
            margin-bottom: 10px;
        }

        .dokumen-title {
            font-size: 14px;
            margin-bottom: 8px;
        }

        .dokumen-number {
            font-size: 12px;
        }

        .dokumen-date {
            font-size: 12px;
        }

        .dokumen-actions {
            flex-direction: column;
            gap: 8px;
            margin-top: 15px;
            padding-top: 15px;
        }

        .dokumen-btn {
            padding: 10px;
            font-size: 12px;
        }

        .pagination-wrapper .page-link {
            padding: 8px 12px;
            font-size: 13px;
        }
    }

    /* SMALL SMARTPHONE (< 480px) */
    @media (max-width: 480px) {
        .category-tabs {
            flex-direction: column;
            align-items: stretch;
        }

        .category-tab {
            justify-content: center;
        }

        .dokumen-icon {
            width: 45px;
            height: 45px;
        }

        .dokumen-icon i {
            font-size: 20px;
        }

        .dokumen-title {
            font-size: 13px;
        }
    }
</style>
@endpush

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const searchInput = document.getElementById('searchInput');
        const filterJenis = document.getElementById('filterJenis');
        const filterTahun = document.getElementById('filterTahun');
        const resetButton = document.getElementById('resetFilter');
        const tableRows = document.querySelectorAll('#dokumenTable tbody tr');
        const noResults = document.getElementById('noResults');
        const tableContainer = document.querySelector('.table-responsive');

        // Filter function
        function filterTable() {
            const searchTerm = searchInput.value.toLowerCase();
            const selectedJenis = filterJenis.value;
            const selectedTahun = filterTahun.value;
            let visibleCount = 0;

            tableRows.forEach(row => {
                const searchData = row.getAttribute('data-search');
                const jenis = row.getAttribute('data-jenis');
                const tahun = row.getAttribute('data-tahun');

                const matchSearch = searchData.includes(searchTerm);
                const matchJenis = !selectedJenis || jenis === selectedJenis;
                const matchTahun = !selectedTahun || tahun === selectedTahun;

                if (matchSearch && matchJenis && matchTahun) {
                    row.style.display = '';
                    visibleCount++;
                } else {
                    row.style.display = 'none';
                }
            });

            // Show/hide no results message
            if (visibleCount === 0) {
                tableContainer.style.display = 'none';
                noResults.style.display = 'block';
            } else {
                tableContainer.style.display = 'block';
                noResults.style.display = 'none';
            }
        }

        // Reset function
        function resetFilters() {
            searchInput.value = '';
            filterJenis.selectedIndex = 0;
            filterTahun.selectedIndex = 0;
            filterTable();
        }

        // Event listeners
        searchInput.addEventListener('input', filterTable);
        filterJenis.addEventListener('change', filterTable);
        filterTahun.addEventListener('change', filterTable);
        resetButton.addEventListener('click', resetFilters);
    });
</script>
@endpush

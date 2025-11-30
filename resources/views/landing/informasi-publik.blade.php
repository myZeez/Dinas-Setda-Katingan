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
        <div class="category-tabs mb-5" data-aos="fade-up">
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
            <div class="row g-4">
                @foreach($informasis as $index => $informasi)
                    <div class="col-md-6 col-lg-4" data-aos="fade-up" data-aos-delay="{{ ($index % 3 + 1) * 100 }}">
                        <div class="dokumen-card h-100">
                            <div class="dokumen-icon">
                                @if($informasi->jenisDokumen)
                                    @php
                                        $icon = match(strtolower($informasi->jenisDokumen->nama)) {
                                            'sk', 'surat keputusan', 'keputusan' => 'bi-file-earmark-check',
                                            'peraturan', 'regulasi', 'perda' => 'bi-journal-bookmark',
                                            'laporan' => 'bi-file-earmark-text',
                                            'mou', 'perjanjian', 'kesepakatan' => 'bi-file-earmark-medical',
                                            'sop', 'prosedur' => 'bi-list-check',
                                            default => 'bi-file-earmark-pdf'
                                        };
                                    @endphp
                                    <i class="bi {{ $icon }}"></i>
                                @else
                                    <i class="bi bi-file-earmark-pdf"></i>
                                @endif
                            </div>
                            <div class="dokumen-content">
                                @if($informasi->jenisDokumen)
                                    <span class="dokumen-type">{{ $informasi->jenisDokumen->nama }}</span>
                                @endif
                                <h4 class="dokumen-title">{{ $informasi->judul }}</h4>
                                @if($informasi->nomor)
                                    <p class="dokumen-number">{{ $informasi->nomor }}</p>
                                @endif
                                <p class="dokumen-date">
                                    <i class="bi bi-calendar3"></i>
                                    {{ $informasi->tanggal_terbit ? \Carbon\Carbon::parse($informasi->tanggal_terbit)->format('d M Y') : $informasi->created_at->format('d M Y') }}
                                </p>
                            </div>
                            <div class="dokumen-actions">
                                <a href="{{ route('landing.informasi.detail', [$kategoriInfo->slug, $informasi->id]) }}" class="dokumen-btn view">
                                    <i class="bi bi-eye"></i> Lihat
                                </a>
                                @if($informasi->file_path)
                                    <a href="{{ route('landing.informasi.download', [$kategoriInfo->slug, $informasi->id]) }}" class="dokumen-btn download">
                                        <i class="bi bi-download"></i> Unduh
                                    </a>
                                @endif
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            <!-- Pagination -->
            @if($informasis->hasPages())
                <div class="d-flex justify-content-center mt-5" data-aos="fade-up">
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

@extends('landing.layouts.app')

@section('title', 'Berita & Informasi')

@section('content')
<!-- Page Header -->
<section class="page-header">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-lg-8">
                <nav class="breadcrumb-nav" data-aos="fade-up">
                    <a href="{{ route('landing') }}">Beranda</a>
                    <span>/</span>
                    <span>Berita</span>
                </nav>
                <h1 class="page-title" data-aos="fade-up" data-aos-delay="100">Berita & Informasi</h1>
                <p class="page-subtitle" data-aos="fade-up" data-aos-delay="200">
                    Informasi terkini seputar kegiatan dan kebijakan Sekretariat Daerah Kabupaten Katingan
                </p>
            </div>
        </div>
    </div>
</section>

<!-- News Section -->
<section class="section section-light">
    <div class="container">
        @if($beritas->count() > 0)
            <div class="row g-4">
                @foreach($beritas as $index => $berita)
                    <div class="col-md-6 col-lg-4" data-aos="fade-up" data-aos-delay="{{ ($index % 3 + 1) * 100 }}">
                        <div class="news-card h-100">
                            <div class="news-image">
                                @if($berita->foto)
                                    <img src="{{ asset('storage/' . $berita->foto) }}" alt="{{ $berita->judul }}">
                                @else
                                    <img src="https://images.unsplash.com/photo-1504711434969-e33886168f5c?w=600&h=400&fit=crop" alt="{{ $berita->judul }}">
                                @endif
                                @if($berita->status == 'published' && $berita->views > 100)
                                    <span class="news-badge">Populer</span>
                                @endif
                            </div>
                            <div class="news-body">
                                <div class="news-meta-info">
                                    <span class="news-meta">
                                        <i class="bi bi-calendar3"></i> {{ $berita->created_at->format('d M Y') }}
                                    </span>
                                    <span class="news-meta">
                                        <i class="bi bi-eye"></i> {{ number_format($berita->views ?? 0) }}x
                                    </span>
                                </div>
                                <h3 class="news-title">{{ $berita->judul }}</h3>
                                <p class="news-excerpt">{{ Str::limit(strip_tags($berita->konten), 120) }}</p>
                                <a href="{{ route('landing.berita.detail', $berita->slug) }}" class="news-link">
                                    Baca Selengkapnya <i class="bi bi-arrow-right"></i>
                                </a>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            <!-- Pagination -->
            @if($beritas->hasPages())
                <div class="d-flex justify-content-center mt-5" data-aos="fade-up">
                    <nav class="pagination-wrapper">
                        {{ $beritas->links() }}
                    </nav>
                </div>
            @endif
        @else
            <div class="text-center py-5">
                <i class="bi bi-newspaper text-muted" style="font-size: 80px;"></i>
                <h3 class="mt-4">Belum Ada Berita</h3>
                <p class="text-muted">Berita dan informasi akan segera tersedia</p>
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
    .news-badge {
        position: absolute;
        top: 15px;
        left: 15px;
        background: linear-gradient(135deg, #dc2626, #b91c1c);
        color: white;
        padding: 5px 12px;
        border-radius: 20px;
        font-size: 12px;
        font-weight: 600;
    }

    .news-meta-info {
        display: flex;
        gap: 15px;
        margin-bottom: 10px;
    }

    .news-meta i {
        margin-right: 5px;
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
        .news-card .news-image {
            height: 180px;
        }

        .news-card .news-body {
            padding: 20px;
        }

        .news-card .news-title {
            font-size: 16px;
        }

        .news-card .news-excerpt {
            font-size: 13px;
        }

        .news-badge {
            font-size: 11px;
            padding: 4px 10px;
        }
    }

    /* SMARTPHONE (< 768px) */
    @media (max-width: 767px) {
        .news-card .news-image {
            height: 160px;
        }

        .news-card .news-body {
            padding: 16px;
        }

        .news-meta-info {
            gap: 12px;
            margin-bottom: 8px;
        }

        .news-meta {
            font-size: 11px;
        }

        .news-card .news-title {
            font-size: 14px;
            margin-bottom: 8px;
        }

        .news-card .news-excerpt {
            font-size: 12px;
            -webkit-line-clamp: 2;
        }

        .news-card .news-link {
            font-size: 12px;
            padding: 8px 14px;
        }

        .news-badge {
            top: 10px;
            left: 10px;
            font-size: 10px;
            padding: 3px 8px;
        }

        .pagination-wrapper .page-link {
            padding: 8px 12px;
            font-size: 13px;
        }
    }

    /* SMALL SMARTPHONE (< 480px) */
    @media (max-width: 480px) {
        .news-meta-info {
            flex-wrap: wrap;
            gap: 8px;
        }

        .news-card .news-title {
            font-size: 13px;
        }
    }
</style>
@endpush

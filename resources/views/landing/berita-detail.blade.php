@extends('landing.layouts.app')

@section('title', $berita->judul)

@section('content')
<!-- Page Header -->
<section class="page-header page-header-sm">
    <div class="container">
        <nav class="breadcrumb-nav" data-aos="fade-up">
            <a href="{{ route('landing') }}">Beranda</a>
            <span>/</span>
            <a href="{{ route('landing.berita') }}">Berita</a>
            <span>/</span>
            <span>{{ Str::limit($berita->judul, 30) }}</span>
        </nav>
    </div>
</section>

<!-- Article Section -->
<section class="section section-light">
    <div class="container">
        <div class="row g-5">
            <!-- Main Content -->
            <div class="col-lg-8">
                <article class="article-content" data-aos="fade-up">
                    <header class="article-header">
                        <h1 class="article-title">{{ $berita->judul }}</h1>
                        <div class="article-meta">
                            <span><i class="bi bi-calendar3"></i> {{ $berita->created_at->format('d F Y') }}</span>
                            <span><i class="bi bi-eye"></i> {{ number_format($berita->views) }}x dibaca</span>
                            <span><i class="bi bi-person"></i> Admin</span>
                        </div>
                    </header>

                    @if($berita->foto)
                        <figure class="article-image">
                            <img src="{{ asset('storage/' . $berita->foto) }}" alt="{{ $berita->judul }}">
                        </figure>
                    @endif

                    <div class="article-body">
                        {!! $berita->konten !!}
                    </div>

                    <footer class="article-footer">
                        <div class="share-buttons">
                            <span>Bagikan:</span>
                            <a href="https://www.facebook.com/sharer/sharer.php?u={{ urlencode(request()->url()) }}" target="_blank" class="share-btn facebook">
                                <i class="bi bi-facebook"></i>
                            </a>
                            <a href="https://twitter.com/intent/tweet?url={{ urlencode(request()->url()) }}&text={{ urlencode($berita->judul) }}" target="_blank" class="share-btn twitter">
                                <i class="bi bi-twitter-x"></i>
                            </a>
                            <a href="https://wa.me/?text={{ urlencode($berita->judul . ' ' . request()->url()) }}" target="_blank" class="share-btn whatsapp">
                                <i class="bi bi-whatsapp"></i>
                            </a>
                            <button class="share-btn copy-link" onclick="copyLink()">
                                <i class="bi bi-link-45deg"></i>
                            </button>
                        </div>
                    </footer>
                </article>
            </div>

            <!-- Sidebar -->
            <div class="col-lg-4">
                <aside class="sidebar" data-aos="fade-up" data-aos-delay="200">
                    <!-- Related News -->
                    <div class="sidebar-widget">
                        <h4 class="widget-title">Berita Terkait</h4>
                        @if($relatedBerita->count() > 0)
                            <div class="related-news">
                                @foreach($relatedBerita as $related)
                                    <a href="{{ route('landing.berita.detail', $related->slug) }}" class="related-item">
                                        <div class="related-image">
                                            @if($related->foto)
                                                <img src="{{ asset('storage/' . $related->foto) }}" alt="{{ $related->judul }}">
                                            @else
                                                <img src="https://images.unsplash.com/photo-1504711434969-e33886168f5c?w=200&h=150&fit=crop" alt="{{ $related->judul }}">
                                            @endif
                                        </div>
                                        <div class="related-content">
                                            <h5>{{ Str::limit($related->judul, 60) }}</h5>
                                            <span class="related-date">{{ $related->created_at->format('d M Y') }}</span>
                                        </div>
                                    </a>
                                @endforeach
                            </div>
                        @else
                            <p class="text-muted">Belum ada berita terkait</p>
                        @endif
                    </div>

                    <!-- Quick Links -->
                    <div class="sidebar-widget">
                        <h4 class="widget-title">Link Cepat</h4>
                        <ul class="quick-links">
                            <li><a href="{{ route('user.login') }}"><i class="bi bi-file-earmark-text"></i> Portal Layanan</a></li>
                            <li><a href="{{ route('landing.struktur') }}"><i class="bi bi-diagram-3"></i> Struktur Organisasi</a></li>
                            <li><a href="{{ route('landing.informasi', 'informasi-publik-bagian-pemerintahan') }}"><i class="bi bi-folder"></i> Informasi Publik</a></li>
                        </ul>
                    </div>
                </aside>
            </div>
        </div>
    </div>
</section>
@endsection

@push('styles')
<style>
    .page-header-sm {
        padding: 100px 0 30px;
    }

    .article-content {
        background: white;
        border-radius: 20px;
        overflow: hidden;
        box-shadow: 0 10px 40px rgba(0,0,0,0.08);
    }

    .article-header {
        padding: 40px 40px 20px;
    }

    .article-title {
        font-size: 28px;
        font-weight: 700;
        color: #0a1628;
        line-height: 1.4;
        margin-bottom: 20px;
    }

    .article-meta {
        display: flex;
        flex-wrap: wrap;
        gap: 20px;
        color: #64748b;
        font-size: 14px;
    }

    .article-meta span {
        display: flex;
        align-items: center;
        gap: 6px;
    }

    .article-image {
        margin: 0;
    }

    .article-image img {
        width: 100%;
        max-height: 400px;
        object-fit: cover;
    }

    .article-body {
        padding: 30px 40px;
        font-size: 16px;
        line-height: 1.9;
        color: #334155;
    }

    .article-body img {
        max-width: 100%;
        height: auto;
        border-radius: 10px;
        margin: 20px 0;
    }

    .article-body h2, .article-body h3 {
        margin-top: 30px;
        margin-bottom: 15px;
        color: #0a1628;
    }

    .article-body p {
        margin-bottom: 15px;
    }

    .article-footer {
        padding: 20px 40px 40px;
        border-top: 1px solid #e2e8f0;
        margin-top: 20px;
    }

    .share-buttons {
        display: flex;
        align-items: center;
        gap: 12px;
    }

    .share-buttons span {
        font-weight: 500;
        color: #64748b;
    }

    .share-btn {
        width: 40px;
        height: 40px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        font-size: 18px;
        transition: all 0.3s ease;
        border: none;
        cursor: pointer;
    }

    .share-btn.facebook { background: #1877f2; }
    .share-btn.twitter { background: #000; }
    .share-btn.whatsapp { background: #25d366; }
    .share-btn.copy-link { background: #64748b; }

    .share-btn:hover {
        transform: translateY(-3px);
        box-shadow: 0 5px 20px rgba(0,0,0,0.2);
    }

    /* Sidebar */
    .sidebar-widget {
        background: white;
        border-radius: 20px;
        padding: 25px;
        box-shadow: 0 10px 40px rgba(0,0,0,0.08);
        margin-bottom: 30px;
    }

    .widget-title {
        font-size: 18px;
        font-weight: 600;
        color: #0a1628;
        margin-bottom: 20px;
        padding-bottom: 15px;
        border-bottom: 2px solid #dc2626;
    }

    .related-item {
        display: flex;
        gap: 15px;
        padding: 15px 0;
        border-bottom: 1px solid #e2e8f0;
        text-decoration: none;
        transition: all 0.3s ease;
    }

    .related-item:last-child {
        border-bottom: none;
    }

    .related-item:hover {
        transform: translateX(5px);
    }

    .related-image {
        width: 80px;
        height: 60px;
        flex-shrink: 0;
        border-radius: 8px;
        overflow: hidden;
    }

    .related-image img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }

    .related-content h5 {
        font-size: 14px;
        font-weight: 500;
        color: #0a1628;
        line-height: 1.4;
        margin-bottom: 5px;
    }

    .related-date {
        font-size: 12px;
        color: #64748b;
    }

    .quick-links {
        list-style: none;
        padding: 0;
        margin: 0;
    }

    .quick-links li {
        margin-bottom: 10px;
    }

    .quick-links a {
        display: flex;
        align-items: center;
        gap: 10px;
        padding: 12px 15px;
        background: #f8fafc;
        border-radius: 10px;
        color: #334155;
        text-decoration: none;
        transition: all 0.3s ease;
    }

    .quick-links a:hover {
        background: linear-gradient(135deg, #dc2626, #b91c1c);
        color: white;
        transform: translateX(5px);
    }

    /* RESPONSIVE STYLES */
    /* TABLET (768px - 991px) */
    @media (max-width: 991px) {
        .article-header,
        .article-body,
        .article-footer {
            padding-left: 30px;
            padding-right: 30px;
        }

        .article-title {
            font-size: 24px;
        }

        .article-body {
            font-size: 15px;
        }

        .sidebar-widget {
            padding: 20px;
        }

        .widget-title {
            font-size: 16px;
        }
    }

    /* SMARTPHONE (< 768px) */
    @media (max-width: 767px) {
        .page-header-sm {
            padding: 90px 0 25px;
        }

        .article-header {
            padding: 25px 20px 15px;
        }

        .article-body {
            padding: 20px;
            font-size: 14px;
            line-height: 1.8;
        }

        .article-footer {
            padding: 15px 20px 25px;
        }

        .article-title {
            font-size: 20px;
            margin-bottom: 15px;
        }

        .article-meta {
            gap: 10px;
            font-size: 12px;
        }

        .article-image img {
            max-height: 250px;
        }

        .share-buttons {
            flex-wrap: wrap;
            gap: 8px;
        }

        .share-btn {
            width: 36px;
            height: 36px;
            font-size: 16px;
        }

        .sidebar-widget {
            padding: 18px;
            border-radius: 16px;
            margin-bottom: 20px;
        }

        .widget-title {
            font-size: 15px;
            margin-bottom: 15px;
            padding-bottom: 12px;
        }

        .related-item {
            gap: 12px;
            padding: 12px 0;
        }

        .related-image {
            width: 70px;
            height: 50px;
        }

        .related-content h5 {
            font-size: 13px;
        }

        .related-date {
            font-size: 11px;
        }

        .quick-links a {
            padding: 10px 12px;
            font-size: 13px;
        }
    }

    /* SMALL SMARTPHONE (< 480px) */
    @media (max-width: 480px) {
        .article-title {
            font-size: 18px;
        }

        .article-meta {
            flex-direction: column;
            gap: 8px;
        }

        .article-body {
            font-size: 13px;
        }

        .related-image {
            width: 60px;
            height: 45px;
        }

        .related-content h5 {
            font-size: 12px;
        }
    }
</style>
@endpush

@push('scripts')
<script>
    function copyLink() {
        navigator.clipboard.writeText(window.location.href).then(() => {
            alert('Link berhasil disalin!');
        });
    }
</script>
@endpush

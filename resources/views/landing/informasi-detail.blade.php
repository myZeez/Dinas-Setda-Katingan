@extends('landing.layouts.app')

@section('title', $informasi->judul)

@section('content')
<!-- Page Header -->
<section class="page-header page-header-sm">
    <div class="container">
        <nav class="breadcrumb-nav" data-aos="fade-up">
            <a href="{{ route('landing') }}">Beranda</a>
            <span>/</span>
            <a href="{{ route('landing.informasi', $kategoriInfo->slug) }}">{{ $kategoriInfo->nama }}</a>
            <span>/</span>
            <span>{{ Str::limit($informasi->judul, 30) }}</span>
        </nav>
    </div>
</section>

<!-- Detail Section -->
<section class="section section-light">
    <div class="container">
        <div class="row g-5">
            <!-- Main Content -->
            <div class="col-lg-8">
                <div class="detail-card" data-aos="fade-up">
                    <div class="detail-header">
                        <div class="d-flex align-items-start gap-4">
                            <div class="detail-icon">
                                <i class="bi bi-file-earmark-pdf"></i>
                            </div>
                            <div class="flex-grow-1">
                                @if($informasi->jenisDokumen)
                                    <span class="detail-type">{{ $informasi->jenisDokumen->nama }}</span>
                                @endif
                                <h1 class="detail-title">{{ $informasi->judul }}</h1>
                            </div>
                        </div>
                    </div>

                    <div class="detail-meta">
                        @if($informasi->nomor)
                            <div class="meta-item">
                                <span class="meta-label">Nomor Dokumen</span>
                                <span class="meta-value">{{ $informasi->nomor }}</span>
                            </div>
                        @endif
                        @if($informasi->tanggal_terbit)
                            <div class="meta-item">
                                <span class="meta-label">Tanggal Terbit</span>
                                <span class="meta-value">{{ \Carbon\Carbon::parse($informasi->tanggal_terbit)->format('d F Y') }}</span>
                            </div>
                        @endif
                        <div class="meta-item">
                            <span class="meta-label">Kategori</span>
                            <span class="meta-value">{{ $kategoriInfo->nama }}</span>
                        </div>
                        @if($informasi->tahun)
                            <div class="meta-item">
                                <span class="meta-label">Tahun</span>
                                <span class="meta-value">{{ $informasi->tahun }}</span>
                            </div>
                        @endif
                    </div>

                    @if($informasi->keterangan)
                        <div class="detail-content">
                            <h5 class="content-title">Keterangan</h5>
                            <div class="content-body">
                                {!! nl2br(e($informasi->keterangan)) !!}
                            </div>
                        </div>
                    @endif

                    <!-- File Dokumen -->
                    @if($informasi->file_dokumen || $informasi->file_lampiran)
                        <div class="detail-file">
                            <h5 class="content-title"><i class="bi bi-paperclip me-2"></i>File Dokumen</h5>

                            <div class="files-container">
                                @if($informasi->file_dokumen)
                                <div class="file-card">
                                    <div class="file-preview-box">
                                        <div class="file-icon-large">
                                            <i class="bi bi-file-earmark-pdf text-danger"></i>
                                        </div>
                                        <div class="file-details">
                                            <p class="file-label">Dokumen PDF</p>
                                            <p class="file-name">{{ basename($informasi->file_dokumen) }}</p>
                                            @php
                                                $filePath = storage_path('app/public/' . $informasi->file_dokumen);
                                                $fileSize = file_exists($filePath) ? number_format(filesize($filePath) / 1024, 2) : '0';
                                            @endphp
                                            <span class="file-size">{{ $fileSize }} KB</span>
                                        </div>
                                    </div>
                                    <a href="{{ asset('storage/' . $informasi->file_dokumen) }}"
                                       class="btn-download-file"
                                       target="_blank"
                                       download>
                                        <i class="bi bi-download"></i> Download
                                    </a>
                                </div>
                                @endif

                                @if($informasi->file_lampiran)
                                <div class="file-card">
                                    <div class="file-preview-box">
                                        <div class="file-icon-large">
                                            @php
                                                $extension = pathinfo($informasi->file_lampiran, PATHINFO_EXTENSION);
                                                $iconClass = match(strtolower($extension)) {
                                                    'pdf' => 'bi-file-earmark-pdf text-danger',
                                                    'jpg', 'jpeg', 'png', 'gif' => 'bi-file-earmark-image text-success',
                                                    'doc', 'docx' => 'bi-file-earmark-word text-primary',
                                                    'xls', 'xlsx' => 'bi-file-earmark-excel text-success',
                                                    default => 'bi-file-earmark text-secondary'
                                                };
                                            @endphp
                                            <i class="bi {{ $iconClass }}"></i>
                                        </div>
                                        <div class="file-details">
                                            <p class="file-label">{{ $informasi->lampiran_label ?? 'Lampiran' }}</p>
                                            <p class="file-name">{{ basename($informasi->file_lampiran) }}</p>
                                            @php
                                                $filePath = storage_path('app/public/' . $informasi->file_lampiran);
                                                $fileSize = file_exists($filePath) ? number_format(filesize($filePath) / 1024, 2) : '0';
                                            @endphp
                                            <span class="file-size">{{ $fileSize }} KB</span>
                                        </div>
                                    </div>
                                    <a href="{{ asset('storage/' . $informasi->file_lampiran) }}"
                                       class="btn-download-file"
                                       target="_blank"
                                       download>
                                        <i class="bi bi-download"></i> Download
                                    </a>
                                </div>
                                @endif
                            </div>
                        </div>
                    @endif

                    <div class="detail-footer">
                        <a href="{{ route('landing.informasi', $kategoriInfo->slug) }}" class="btn-back">
                            <i class="bi bi-arrow-left"></i> Kembali ke Daftar
                        </a>
                    </div>
                </div>
            </div>

            <!-- Sidebar -->
            <div class="col-lg-4">
                <aside class="sidebar" data-aos="fade-up" data-aos-delay="200">
                    <!-- Quick Links -->
                    <div class="sidebar-widget">
                        <h4 class="widget-title">Kategori Informasi</h4>
                        <ul class="category-links">
                            <li>
                                <a href="{{ route('landing.informasi', 'informasi-publik-bagian-pemerintahan') }}" class="{{ $kategoriInfo->slug == 'informasi-publik-bagian-pemerintahan' ? 'active' : '' }}">
                                    <i class="bi bi-building"></i>
                                    <span>Bag. Pemerintahan</span>
                                    <i class="bi bi-chevron-right ms-auto"></i>
                                </a>
                            </li>
                            <li>
                                <a href="{{ route('landing.informasi', 'informasi-kewilayahan') }}" class="{{ $kategoriInfo->slug == 'informasi-kewilayahan' ? 'active' : '' }}">
                                    <i class="bi bi-map"></i>
                                    <span>Bag. Kewilayahan</span>
                                    <i class="bi bi-chevron-right ms-auto"></i>
                                </a>
                            </li>
                            <li>
                                <a href="{{ route('landing.informasi', 'informasi-kerja-sama') }}" class="{{ $kategoriInfo->slug == 'informasi-kerja-sama' ? 'active' : '' }}">
                                    <i class="bi bi-people"></i>
                                    <span>Bag. Kerja Sama</span>
                                    <i class="bi bi-chevron-right ms-auto"></i>
                                </a>
                            </li>
                        </ul>
                    </div>

                    <!-- Quick Actions -->
                    <div class="sidebar-widget">
                        <h4 class="widget-title">Akses Cepat</h4>
                        <ul class="quick-links">
                            <li><a href="{{ route('user.login') }}"><i class="bi bi-file-earmark-text"></i> Portal Layanan</a></li>
                            <li><a href="{{ route('landing.berita') }}"><i class="bi bi-newspaper"></i> Berita</a></li>
                            <li><a href="{{ route('landing.struktur') }}"><i class="bi bi-diagram-3"></i> Struktur Organisasi</a></li>
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

    .detail-card {
        background: white;
        border-radius: 20px;
        overflow: hidden;
        box-shadow: 0 10px 40px rgba(0,0,0,0.08);
    }

    .detail-header {
        padding: 35px;
        background: linear-gradient(135deg, #fef2f2, #fee2e2);
        border-bottom: 1px solid #fecaca;
    }

    .detail-icon {
        width: 70px;
        height: 70px;
        background: linear-gradient(135deg, #dc2626, #b91c1c);
        border-radius: 18px;
        display: flex;
        align-items: center;
        justify-content: center;
        flex-shrink: 0;
    }

    .detail-icon i {
        font-size: 32px;
        color: white;
    }

    .detail-type {
        display: inline-block;
        background: white;
        color: #dc2626;
        padding: 4px 14px;
        border-radius: 20px;
        font-size: 13px;
        font-weight: 500;
        margin-bottom: 12px;
    }

    .detail-title {
        font-size: 22px;
        font-weight: 700;
        color: #0a1628;
        line-height: 1.4;
        margin: 0;
    }

    .detail-meta {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(180px, 1fr));
        gap: 20px;
        padding: 30px 35px;
        background: #f8fafc;
        border-bottom: 1px solid #e2e8f0;
    }

    .meta-item {
        display: flex;
        flex-direction: column;
        gap: 5px;
    }

    .meta-label {
        font-size: 12px;
        color: #64748b;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    .meta-value {
        font-size: 15px;
        font-weight: 600;
        color: #0a1628;
    }

    .detail-content,
    .detail-file {
        padding: 30px 35px;
        border-bottom: 1px solid #e2e8f0;
    }

    .content-title {
        font-size: 16px;
        font-weight: 600;
        color: #0a1628;
        margin-bottom: 15px;
    }

    .content-body {
        color: #334155;
        line-height: 1.8;
    }

    /* File Cards */
    .files-container {
        display: grid;
        gap: 20px;
    }

    .file-card {
        background: #f8fafc;
        border: 2px solid #e2e8f0;
        border-radius: 16px;
        padding: 24px;
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 20px;
        transition: all 0.3s ease;
    }

    .file-card:hover {
        border-color: #dc2626;
        background: #fef2f2;
        transform: translateY(-2px);
        box-shadow: 0 8px 25px rgba(220, 38, 38, 0.15);
    }

    .file-preview-box {
        display: flex;
        align-items: center;
        gap: 16px;
        flex: 1;
    }

    .file-icon-large {
        width: 64px;
        height: 64px;
        background: white;
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        flex-shrink: 0;
        box-shadow: 0 4px 12px rgba(0,0,0,0.08);
    }

    .file-icon-large i {
        font-size: 32px;
    }

    .file-details {
        flex: 1;
        min-width: 0;
    }

    .file-label {
        font-size: 11px;
        text-transform: uppercase;
        color: #64748b;
        font-weight: 600;
        letter-spacing: 0.5px;
        margin-bottom: 4px;
    }

    .file-name {
        font-weight: 600;
        color: #0a1628;
        margin-bottom: 6px;
        font-size: 14px;
        overflow: hidden;
        text-overflow: ellipsis;
        white-space: nowrap;
    }

    .file-size {
        font-size: 12px;
        color: #64748b;
        background: white;
        padding: 2px 8px;
        border-radius: 4px;
        display: inline-block;
    }

    .btn-download-file {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        background: linear-gradient(135deg, #dc2626, #b91c1c);
        color: white;
        padding: 12px 24px;
        border-radius: 10px;
        text-decoration: none;
        font-weight: 600;
        font-size: 14px;
        transition: all 0.3s ease;
        flex-shrink: 0;
    }

    .btn-download-file:hover {
        color: white;
        box-shadow: 0 8px 25px rgba(220, 38, 38, 0.4);
        transform: translateY(-2px);
    }

    .btn-download-file i {
        font-size: 16px;
    }

    .file-preview {
        display: flex;
        align-items: center;
        justify-content: space-between;
        background: #f8fafc;
        padding: 20px;
        border-radius: 12px;
        flex-wrap: wrap;
        gap: 15px;
    }

    .file-info {
        display: flex;
        align-items: center;
        gap: 15px;
    }

    .file-icon {
        width: 50px;
        height: 50px;
        background: #fef2f2;
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .file-icon i {
        font-size: 24px;
        color: #dc2626;
    }

    .file-name {
        font-weight: 500;
        color: #0a1628;
        margin-bottom: 4px;
    }

    .file-size {
        font-size: 13px;
        color: #64748b;
    }

    .btn-download {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        background: linear-gradient(135deg, #dc2626, #b91c1c);
        color: white;
        padding: 12px 25px;
        border-radius: 10px;
        text-decoration: none;
        font-weight: 500;
        transition: all 0.3s ease;
    }

    .btn-download:hover {
        color: white;
        box-shadow: 0 8px 25px rgba(220, 38, 38, 0.35);
        transform: translateY(-2px);
    }

    .detail-footer {
        padding: 25px 35px;
    }

    .btn-back {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        color: #64748b;
        text-decoration: none;
        font-weight: 500;
        transition: all 0.3s ease;
    }

    .btn-back:hover {
        color: #dc2626;
        transform: translateX(-5px);
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

    .category-links {
        list-style: none;
        padding: 0;
        margin: 0;
    }

    .category-links li {
        margin-bottom: 8px;
    }

    .category-links a {
        display: flex;
        align-items: center;
        gap: 12px;
        padding: 14px 18px;
        background: #f8fafc;
        border-radius: 12px;
        color: #334155;
        text-decoration: none;
        transition: all 0.3s ease;
        font-weight: 500;
    }

    .category-links a:hover,
    .category-links a.active {
        background: linear-gradient(135deg, #dc2626, #b91c1c);
        color: white;
        transform: translateX(5px);
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
        .detail-header {
            padding: 28px;
        }

        .detail-icon {
            width: 60px;
            height: 60px;
        }

        .detail-icon i {
            font-size: 28px;
        }

        .detail-title {
            font-size: 20px;
        }

        .detail-meta {
            padding: 25px 28px;
        }

        .detail-content,
        .detail-file,
        .detail-footer {
            padding: 25px 28px;
        }

        .sidebar-widget {
            padding: 20px;
        }

        .widget-title {
            font-size: 16px;
        }

        .category-links a {
            padding: 12px 15px;
            font-size: 14px;
        }
    }

    /* SMARTPHONE (< 768px) */
    @media (max-width: 767px) {
        .detail-card {
            border-radius: 16px;
        }

        .detail-header {
            padding: 20px;
        }

        .detail-header .d-flex {
            flex-direction: column;
            align-items: flex-start !important;
            gap: 15px !important;
        }

        .detail-icon {
            width: 55px;
            height: 55px;
            border-radius: 14px;
        }

        .detail-icon i {
            font-size: 24px;
        }

        .detail-type {
            font-size: 11px;
            padding: 3px 10px;
            margin-bottom: 10px;
        }

        .detail-title {
            font-size: 17px;
        }

        .detail-meta {
            padding: 20px;
            grid-template-columns: 1fr 1fr;
            gap: 15px;
        }

        .meta-label {
            font-size: 11px;
        }

        .meta-value {
            font-size: 13px;
        }

        .detail-content,
        .detail-file {
            padding: 20px;
        }

        .content-title {
            font-size: 14px;
            margin-bottom: 12px;
        }

        .content-body {
            font-size: 14px;
        }

        /* File Cards Responsive */
        .file-card {
            flex-direction: column;
            align-items: stretch;
            padding: 18px;
        }

        .file-preview-box {
            flex-direction: column;
            align-items: flex-start;
            gap: 12px;
        }

        .file-icon-large {
            width: 56px;
            height: 56px;
        }

        .file-icon-large i {
            font-size: 28px;
        }

        .file-details {
            width: 100%;
        }

        .file-label {
            font-size: 10px;
        }

        .file-name {
            font-size: 13px;
            white-space: normal;
        }

        .btn-download-file {
            width: 100%;
            justify-content: center;
            padding: 10px 20px;
            font-size: 13px;
        }

        .detail-footer {
            margin-bottom: 12px;
        }

        .content-body {
            font-size: 14px;
        }

        .detail-footer {
            padding: 18px 20px;
        }

        .file-preview {
            padding: 15px;
            flex-direction: column;
            align-items: stretch;
            text-align: center;
        }

        .file-info {
            flex-direction: column;
            gap: 10px;
        }

        .file-icon {
            width: 45px;
            height: 45px;
        }

        .file-icon i {
            font-size: 20px;
        }

        .file-name {
            font-size: 13px;
        }

        .file-size {
            font-size: 12px;
        }

        .btn-download {
            padding: 12px 20px;
            font-size: 13px;
            justify-content: center;
        }

        .btn-back {
            font-size: 14px;
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

        .category-links a {
            padding: 12px 14px;
            font-size: 13px;
            gap: 10px;
        }

        .quick-links a {
            padding: 10px 12px;
            font-size: 13px;
        }
    }

    /* SMALL SMARTPHONE (< 480px) */
    @media (max-width: 480px) {
        .detail-header {
            padding: 15px;
        }

        .detail-title {
            font-size: 15px;
        }

        .detail-meta {
            grid-template-columns: 1fr;
            gap: 12px;
        }

        .detail-content,
        .detail-file,
        .detail-footer {
            padding: 15px;
        }

        .content-body {
            font-size: 13px;
        }

        .category-links a {
            font-size: 12px;
        }
    }
</style>
@endpush

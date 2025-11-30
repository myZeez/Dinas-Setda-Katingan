@extends('landing.layouts.app')

@section('title', 'Layanan Bag. Pemerintahan - ' . ($settings['site_name'] ?? 'Sekretariat Daerah'))

@section('content')
<!-- Hero Section -->
<section class="page-hero">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-lg-8">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb mb-3">
                        <li class="breadcrumb-item"><a href="{{ route('landing') }}">Beranda</a></li>
                        <li class="breadcrumb-item active">Layanan Bag. Pemerintahan</li>
                    </ol>
                </nav>
                <h1 class="page-title">Layanan Bagian <span class="text-primary">Pemerintahan</span></h1>
                <p class="page-subtitle">Portal layanan administrasi pemerintahan Sekretariat Daerah Kabupaten Katingan</p>
            </div>
            <div class="col-lg-4 text-end d-none d-lg-block">
                <img src="{{ asset('images/service-illustration.svg') }}" alt="Layanan" class="img-fluid" style="max-height: 200px;" onerror="this.style.display='none'">
            </div>
        </div>
    </div>
</section>

<!-- Layanan Section -->
<section class="section">
    <div class="container">
        <!-- Info Alert -->
        <div class="alert alert-info border-0 shadow-sm mb-5" data-aos="fade-up">
            <div class="d-flex align-items-start">
                <i class="bi bi-info-circle-fill fs-4 me-3 text-info"></i>
                <div>
                    <h6 class="alert-heading mb-1">Tentang Layanan Ini</h6>
                    <p class="mb-0">Layanan Bagian Pemerintahan meliputi administrasi kerjasama antar daerah, fasilitasi penyusunan produk hukum daerah, dan koordinasi penyelenggaraan pemerintahan. Untuk mengajukan layanan, silakan login atau daftar terlebih dahulu.</p>
                </div>
            </div>
        </div>

        <!-- Jenis Layanan -->
        <div class="row g-4 mb-5">
            @if($jenisLayanans->count() > 0)
                @foreach($jenisLayanans as $layanan)
                <div class="col-md-6 col-lg-4" data-aos="fade-up" data-aos-delay="{{ $loop->index * 100 }}">
                    <div class="layanan-card">
                        <div class="layanan-icon">
                            <i class="bi {{ $layanan->icon ?? 'bi-file-earmark-text' }}"></i>
                        </div>
                        <h4 class="layanan-title">{{ $layanan->nama }}</h4>
                        <p class="layanan-desc">{{ $layanan->deskripsi ?? 'Layanan administrasi pemerintahan.' }}</p>
                        <span class="layanan-code">Kode: {{ $layanan->kode }}</span>
                    </div>
                </div>
                @endforeach
            @else
                <!-- Default Layanan Cards -->
                <div class="col-md-6 col-lg-4" data-aos="fade-up" data-aos-delay="100">
                    <div class="layanan-card">
                        <div class="layanan-icon">
                            <i class="bi bi-file-earmark-text"></i>
                        </div>
                        <h4 class="layanan-title">Perjanjian Kerjasama (PKS)</h4>
                        <p class="layanan-desc">Fasilitasi penyusunan dan penandatanganan perjanjian kerjasama antar daerah atau dengan pihak ketiga.</p>
                        <span class="layanan-code">Kode: PKS</span>
                    </div>
                </div>
                <div class="col-md-6 col-lg-4" data-aos="fade-up" data-aos-delay="200">
                    <div class="layanan-card">
                        <div class="layanan-icon">
                            <i class="bi bi-building"></i>
                        </div>
                        <h4 class="layanan-title">Nota Kesepakatan</h4>
                        <p class="layanan-desc">Fasilitasi penyusunan nota kesepakatan bersama antara pemerintah daerah dengan instansi/lembaga lain.</p>
                        <span class="layanan-code">Kode: NK</span>
                    </div>
                </div>
                <div class="col-md-6 col-lg-4" data-aos="fade-up" data-aos-delay="300">
                    <div class="layanan-card">
                        <div class="layanan-icon">
                            <i class="bi bi-journal-text"></i>
                        </div>
                        <h4 class="layanan-title">Administrasi Pemerintahan</h4>
                        <p class="layanan-desc">Layanan administrasi umum terkait penyelenggaraan pemerintahan daerah.</p>
                        <span class="layanan-code">Kode: AP</span>
                    </div>
                </div>
            @endif
        </div>

        <!-- Alur Pengajuan -->
        <div class="section-header text-center mb-5" data-aos="fade-up">
            <h2>Alur <span class="highlight">Pengajuan</span></h2>
            <p>Langkah-langkah untuk mengajukan layanan</p>
        </div>

        <div class="process-steps" data-aos="fade-up">
            <div class="process-step">
                <div class="step-number">1</div>
                <div class="step-icon"><i class="bi bi-person-plus"></i></div>
                <h5>Daftar / Login</h5>
                <p>Buat akun atau login ke portal layanan</p>
            </div>
            <div class="process-connector"></div>
            <div class="process-step">
                <div class="step-number">2</div>
                <div class="step-icon"><i class="bi bi-file-earmark-arrow-up"></i></div>
                <h5>Ajukan Permohonan</h5>
                <p>Pilih jenis layanan dan upload dokumen</p>
            </div>
            <div class="process-connector"></div>
            <div class="process-step">
                <div class="step-number">3</div>
                <div class="step-icon"><i class="bi bi-search"></i></div>
                <h5>Verifikasi</h5>
                <p>Tim kami akan memverifikasi dokumen</p>
            </div>
            <div class="process-connector"></div>
            <div class="process-step">
                <div class="step-number">4</div>
                <div class="step-icon"><i class="bi bi-check-circle"></i></div>
                <h5>Selesai</h5>
                <p>Download hasil layanan yang sudah selesai</p>
            </div>
        </div>

        <!-- Dokumen Persyaratan -->
        <div class="requirements-section mt-5 pt-5" data-aos="fade-up">
            <div class="section-header text-center mb-4">
                <h2>Dokumen <span class="highlight">Persyaratan</span></h2>
                <p>Dokumen yang perlu disiapkan untuk pengajuan</p>
            </div>

            <div class="row g-4 justify-content-center">
                <div class="col-md-6 col-lg-4">
                    <div class="requirement-card">
                        <div class="requirement-icon">
                            <i class="bi bi-envelope-paper"></i>
                        </div>
                        <h5>Surat Penawaran</h5>
                        <p>Surat penawaran resmi dari instansi/lembaga yang mengajukan kerjasama</p>
                        <span class="badge bg-primary">Wajib</span>
                    </div>
                </div>
                <div class="col-md-6 col-lg-4">
                    <div class="requirement-card">
                        <div class="requirement-icon">
                            <i class="bi bi-file-text"></i>
                        </div>
                        <h5>Kerangka Acuan Kerja</h5>
                        <p>KAK yang berisi ruang lingkup, tujuan, dan detail kerjasama yang diajukan</p>
                        <span class="badge bg-primary">Wajib</span>
                    </div>
                </div>
                <div class="col-md-6 col-lg-4">
                    <div class="requirement-card">
                        <div class="requirement-icon">
                            <i class="bi bi-file-earmark-richtext"></i>
                        </div>
                        <h5>Draft Naskah</h5>
                        <p>Draft naskah PKS atau Nota Kesepakatan yang akan ditandatangani</p>
                        <span class="badge bg-primary">Wajib</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- CTA Section -->
        <div class="cta-section mt-5 pt-5" data-aos="fade-up">
            <div class="cta-card">
                <div class="row align-items-center">
                    <div class="col-lg-8">
                        <h3>Siap Mengajukan Layanan?</h3>
                        <p class="mb-0">Login ke portal untuk mulai mengajukan permohonan layanan Anda.</p>
                    </div>
                    <div class="col-lg-4 text-lg-end mt-3 mt-lg-0">
                        <a href="{{ route('user.login') }}" class="btn btn-light btn-lg me-2">
                            <i class="bi bi-box-arrow-in-right me-2"></i>Login
                        </a>
                        <a href="{{ route('user.register') }}" class="btn btn-outline-light btn-lg">
                            <i class="bi bi-person-plus me-2"></i>Daftar
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <!-- Contact Info -->
        <div class="contact-info-section mt-5" data-aos="fade-up">
            <div class="row g-4">
                <div class="col-md-4">
                    <div class="contact-info-card">
                        <i class="bi bi-telephone"></i>
                        <h5>Telepon</h5>
                        <p>{{ $settings['phone'] ?? '(0536) 123456' }}</p>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="contact-info-card">
                        <i class="bi bi-envelope"></i>
                        <h5>Email</h5>
                        <p>{{ $settings['email'] ?? 'setda@katingankab.go.id' }}</p>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="contact-info-card">
                        <i class="bi bi-clock"></i>
                        <h5>Jam Layanan</h5>
                        <p>Senin - Jumat, 08:00 - 16:00 WIB</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection

@push('styles')
<style>
    /* Page Hero */
    .page-hero {
        background: linear-gradient(135deg, #dc2626 0%, #991b1b 100%);
        color: white;
        padding: 120px 0 60px;
    }

    .breadcrumb {
        background: transparent;
        padding: 0;
    }

    .breadcrumb-item a {
        color: rgba(255,255,255,0.8);
        text-decoration: none;
    }

    .breadcrumb-item.active {
        color: white;
    }

    .breadcrumb-item + .breadcrumb-item::before {
        color: rgba(255,255,255,0.6);
    }

    .page-title {
        font-size: 42px;
        font-weight: 800;
        margin-bottom: 15px;
    }

    .page-title .text-primary {
        color: #fbbf24 !important;
    }

    .page-subtitle {
        font-size: 18px;
        opacity: 0.9;
        margin-bottom: 0;
    }

    /* Layanan Cards */
    .layanan-card {
        background: white;
        border-radius: 16px;
        padding: 30px;
        height: 100%;
        box-shadow: 0 4px 20px rgba(0,0,0,0.08);
        transition: all 0.3s ease;
        border: 1px solid #f0f0f0;
    }

    .layanan-card:hover {
        transform: translateY(-8px);
        box-shadow: 0 20px 50px rgba(0,0,0,0.15);
    }

    .layanan-icon {
        width: 70px;
        height: 70px;
        background: linear-gradient(135deg, #dc2626 0%, #b91c1c 100%);
        border-radius: 16px;
        display: flex;
        align-items: center;
        justify-content: center;
        margin-bottom: 20px;
    }

    .layanan-icon i {
        font-size: 32px;
        color: white;
    }

    .layanan-title {
        font-size: 20px;
        font-weight: 700;
        margin-bottom: 12px;
        color: #1a1a1a;
    }

    .layanan-desc {
        color: #666;
        font-size: 14px;
        line-height: 1.7;
        margin-bottom: 15px;
    }

    .layanan-code {
        display: inline-block;
        background: #f0f0f0;
        padding: 5px 12px;
        border-radius: 20px;
        font-size: 12px;
        color: #666;
        font-weight: 500;
    }

    /* Process Steps */
    .process-steps {
        display: flex;
        align-items: flex-start;
        justify-content: center;
        flex-wrap: wrap;
        gap: 10px;
        padding: 40px 0;
    }

    .process-step {
        text-align: center;
        flex: 1;
        min-width: 180px;
        max-width: 220px;
        position: relative;
    }

    .step-number {
        position: absolute;
        top: -10px;
        right: 20px;
        width: 30px;
        height: 30px;
        background: #dc2626;
        color: white;
        border-radius: 50%;
        font-size: 14px;
        font-weight: 700;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .step-icon {
        width: 80px;
        height: 80px;
        background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 0 auto 15px;
        border: 3px solid #dc2626;
    }

    .step-icon i {
        font-size: 32px;
        color: #dc2626;
    }

    .process-step h5 {
        font-size: 16px;
        font-weight: 700;
        margin-bottom: 8px;
    }

    .process-step p {
        font-size: 13px;
        color: #666;
        margin: 0;
    }

    .process-connector {
        width: 60px;
        height: 3px;
        background: linear-gradient(90deg, #dc2626, #fbbf24);
        margin-top: 40px;
        border-radius: 3px;
    }

    /* Requirement Cards */
    .requirement-card {
        background: white;
        border-radius: 16px;
        padding: 30px;
        text-align: center;
        height: 100%;
        box-shadow: 0 4px 20px rgba(0,0,0,0.08);
        border: 1px solid #f0f0f0;
    }

    .requirement-icon {
        width: 60px;
        height: 60px;
        background: #fef3c7;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 0 auto 15px;
    }

    .requirement-icon i {
        font-size: 28px;
        color: #d97706;
    }

    .requirement-card h5 {
        font-size: 18px;
        font-weight: 700;
        margin-bottom: 10px;
    }

    .requirement-card p {
        font-size: 14px;
        color: #666;
        margin-bottom: 15px;
    }

    /* CTA Section */
    .cta-card {
        background: linear-gradient(135deg, #dc2626 0%, #991b1b 100%);
        border-radius: 20px;
        padding: 40px 50px;
        color: white;
    }

    .cta-card h3 {
        font-size: 28px;
        font-weight: 700;
        margin-bottom: 10px;
    }

    .cta-card p {
        opacity: 0.9;
    }

    /* Contact Info */
    .contact-info-card {
        background: white;
        border-radius: 16px;
        padding: 30px;
        text-align: center;
        box-shadow: 0 4px 20px rgba(0,0,0,0.08);
    }

    .contact-info-card i {
        font-size: 36px;
        color: #dc2626;
        margin-bottom: 15px;
    }

    .contact-info-card h5 {
        font-size: 18px;
        font-weight: 700;
        margin-bottom: 8px;
    }

    .contact-info-card p {
        color: #666;
        margin: 0;
    }

    /* Responsive */
    /* TABLET (768px - 991px) */
    @media (max-width: 991px) {
        .page-hero {
            padding: 100px 0 50px;
        }

        .page-title {
            font-size: 34px;
        }

        .page-subtitle {
            font-size: 16px;
        }

        .process-connector {
            display: none;
        }

        .process-steps {
            gap: 25px;
        }

        .process-step {
            min-width: 150px;
        }

        .step-icon {
            width: 70px;
            height: 70px;
        }

        .step-icon i {
            font-size: 28px;
        }

        .layanan-card {
            padding: 25px;
        }

        .layanan-icon {
            width: 60px;
            height: 60px;
        }

        .layanan-icon i {
            font-size: 28px;
        }

        .layanan-title {
            font-size: 18px;
        }

        .cta-card {
            padding: 35px;
        }

        .cta-card h3 {
            font-size: 24px;
        }
    }

    /* SMARTPHONE (< 768px) */
    @media (max-width: 767px) {
        .page-hero {
            padding: 90px 0 40px;
        }

        .page-title {
            font-size: 26px;
            margin-bottom: 10px;
        }

        .page-subtitle {
            font-size: 14px;
        }

        .alert {
            padding: 15px;
        }

        .alert h6 {
            font-size: 14px;
        }

        .alert p {
            font-size: 13px;
        }

        .layanan-card {
            padding: 20px;
        }

        .layanan-icon {
            width: 55px;
            height: 55px;
            margin-bottom: 15px;
        }

        .layanan-icon i {
            font-size: 24px;
        }

        .layanan-title {
            font-size: 16px;
            margin-bottom: 10px;
        }

        .layanan-desc {
            font-size: 13px;
            margin-bottom: 12px;
        }

        .layanan-code {
            font-size: 11px;
            padding: 4px 10px;
        }

        .process-steps {
            flex-direction: column;
            align-items: center;
            gap: 20px;
            padding: 30px 0;
        }

        .process-step {
            max-width: 100%;
            width: 100%;
            display: flex;
            align-items: center;
            text-align: left;
            gap: 15px;
        }

        .step-number {
            position: static;
            flex-shrink: 0;
        }

        .step-icon {
            width: 60px;
            height: 60px;
            margin: 0;
            flex-shrink: 0;
        }

        .step-icon i {
            font-size: 24px;
        }

        .process-step h5 {
            font-size: 14px;
            margin-bottom: 4px;
        }

        .process-step p {
            font-size: 12px;
        }

        .requirement-card {
            padding: 20px;
        }

        .requirement-icon {
            width: 50px;
            height: 50px;
        }

        .requirement-icon i {
            font-size: 22px;
        }

        .requirement-card h5 {
            font-size: 15px;
        }

        .requirement-card p {
            font-size: 13px;
        }

        .cta-card {
            padding: 25px 20px;
            text-align: center;
        }

        .cta-card h3 {
            font-size: 20px;
        }

        .cta-card p {
            font-size: 14px;
        }

        .cta-card .btn {
            display: block;
            width: 100%;
            margin-bottom: 10px;
        }

        .cta-card .btn:last-child {
            margin-bottom: 0;
        }

        .contact-info-card {
            padding: 20px;
        }

        .contact-info-card i {
            font-size: 28px;
            margin-bottom: 10px;
        }

        .contact-info-card h5 {
            font-size: 15px;
        }

        .contact-info-card p {
            font-size: 13px;
        }
    }

    /* SMALL SMARTPHONE (< 480px) */
    @media (max-width: 480px) {
        .page-title {
            font-size: 22px;
        }

        .layanan-icon {
            width: 50px;
            height: 50px;
        }

        .layanan-icon i {
            font-size: 22px;
        }

        .layanan-title {
            font-size: 15px;
        }

        .step-icon {
            width: 50px;
            height: 50px;
        }

        .step-icon i {
            font-size: 20px;
        }

        .cta-card h3 {
            font-size: 18px;
        }
    }
</style>
@endpush

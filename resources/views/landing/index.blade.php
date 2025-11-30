@extends('landing.layouts.app')

@section('title', 'Beranda')

@section('content')
<!-- Hero Section -->
<section class="hero-section">
    <div class="container">
        <!-- Hero Content - Always on top -->
        <div class="hero-content text-center" data-aos="fade-up">
            <span class="hero-badge">Selamat Datang di Website Resmi</span>
            <h1 class="hero-title">
                Sekretariat Daerah<br>
                <span class="highlight">Kabupaten Katingan</span>
            </h1>
            <p class="hero-subtitle">
                Melayani masyarakat dengan sepenuh hati untuk mewujudkan Kabupaten Katingan yang maju, sejahtera, berkeadilan dan berakhlak mulia.
            </p>
            <div class="hero-buttons">
                <a href="{{ route('user.login') }}" class="btn-primary-hero">
                    <i class="bi bi-file-earmark-text"></i> Portal Layanan
                </a>
                <a href="#tentang" class="btn-secondary-hero">
                    <i class="bi bi-play-circle"></i> Lihat Profil Kami
                </a>
            </div>
        </div>

        <!-- Stacked Carousel -->
        @if($carousels->count() > 0)
        <div class="stacked-carousel-wrapper" data-aos="fade-up" data-aos-delay="200">
            <div class="stacked-carousel" id="stackedCarousel">
                @foreach($carousels as $index => $carousel)
                <div class="stacked-slide {{ $index == 0 ? 'active' : '' }}" data-index="{{ $index }}">
                    <img src="{{ asset('storage/' . $carousel->gambar) }}" alt="{{ $carousel->judul }}">
                </div>
                @endforeach
            </div>
        </div>
        @else
        <!-- Default Hero Image -->
        <div class="hero-media" data-aos="fade-up" data-aos-delay="200">
            <img src="{{ asset('images/hero-katingan.jpg') }}" alt="Kabupaten Katingan" onerror="this.src='https://images.unsplash.com/photo-1555899434-94d1368aa7af?w=1200&h=600&fit=crop'">
            <div class="play-button" id="heroPlayBtn" onclick="openVideoModal('https://www.youtube.com/embed/dQw4w9WgXcQ')">
                <i class="bi bi-play-fill"></i>
            </div>
        </div>
        @endif

        {{-- Partner Logos - Uncomment jika sudah ada file logo di public/images/
        <div class="partner-logos" data-aos="fade-up" data-aos-delay="300">
            <div class="d-flex justify-content-center align-items-center gap-5 flex-wrap">
                <img src="{{ asset('images/logo-kalteng.png') }}" alt="Kalimantan Tengah" onerror="this.style.display='none'">
                <img src="{{ asset('images/logo-katingan.png') }}" alt="Katingan" onerror="this.style.display='none'">
            </div>
        </div>
        --}}
    </div>
</section>

<!-- Pimpinan Section -->
<section class="section section-light" id="pimpinan">
    <div class="container">
        <div class="section-header text-center" data-aos="fade-up">
            <h2>Pimpinan <span class="highlight">Daerah</span></h2>
            <p class="mx-auto">Kepemimpinan Kabupaten Katingan periode saat ini</p>
        </div>

        @if($pemimpinDaerahs->count() > 0)
        <!-- Dynamic Pemimpin from Database with 5x3 Grid Layout -->
        <div class="leaders-grid" data-aos="fade-up">
            @php
                $positionMap = [];
                foreach($pemimpinDaerahs as $p) {
                    $pos = $p->grid_position ?? $p->urutan ?? 1;
                    $positionMap[$pos] = $p;
                }
            @endphp

            @for($i = 1; $i <= 15; $i++)
                <div class="leaders-grid-slot {{ isset($positionMap[$i]) ? 'has-content' : 'empty-slot' }}">
                    @if(isset($positionMap[$i]))
                        @php $pemimpin = $positionMap[$i]; @endphp
                        <div class="leader-card">
                            <div class="leader-photo">
                                @if($pemimpin->foto)
                                <img src="{{ asset('storage/' . $pemimpin->foto) }}" alt="{{ $pemimpin->nama }}">
                                @else
                                <img src="https://ui-avatars.com/api/?name={{ urlencode($pemimpin->nama) }}&size=200&background=dc2626&color=fff" alt="{{ $pemimpin->nama }}">
                                @endif
                            </div>
                            <h4 class="leader-name">{{ $pemimpin->nama }}</h4>
                            <p class="leader-position">{{ $pemimpin->jabatan }}</p>
                            @if($pemimpin->periode)
                            <small class="text-muted">{{ $pemimpin->periode }}</small>
                            @endif
                        </div>
                    @endif
                </div>
            @endfor
        </div>
        @else
        <!-- Default Static Pemimpin -->
        <div class="row justify-content-center g-5 mb-5">
            <!-- Bupati & Wakil Bupati -->
            <div class="col-md-4 col-lg-3" data-aos="fade-up" data-aos-delay="100">
                <div class="leader-card">
                    <div class="leader-photo">
                        <img src="{{ asset('images/bupati.png') }}" alt="Bupati" onerror="this.src='https://ui-avatars.com/api/?name=Bupati&size=200&background=dc2626&color=fff'">
                    </div>
                    <h4 class="leader-name">SAIFUL, S.Pd., M.Si</h4>
                    <p class="leader-position">Bupati Katingan</p>
                </div>
            </div>
            <div class="col-md-4 col-lg-3" data-aos="fade-up" data-aos-delay="200">
                <div class="leader-card">
                    <div class="leader-photo">
                        <img src="{{ asset('images/wakil-bupati.png') }}" alt="Wakil Bupati" onerror="this.src='https://ui-avatars.com/api/?name=Wakil+Bupati&size=200&background=dc2626&color=fff'">
                    </div>
                    <h4 class="leader-name">FIRDAUS, ST</h4>
                    <p class="leader-position">Wakil Bupati Katingan</p>
                </div>
            </div>
        </div>

        <div class="row justify-content-center g-5">
            <!-- Sekda -->
            <div class="col-12 text-center mb-4" data-aos="fade-up">
                <div class="leader-card d-inline-block">
                    <div class="leader-photo" style="width: 160px; height: 160px;">
                        <img src="{{ asset('images/sekda.png') }}" alt="Sekretaris Daerah" onerror="this.src='https://ui-avatars.com/api/?name=Sekda&size=200&background=dc2626&color=fff'">
                    </div>
                    <h4 class="leader-name">Drs. DEDDY FERRAS, M.Si., CGCAE</h4>
                    <p class="leader-position">Pj. Sekretaris Daerah</p>
                </div>
            </div>
        </div>

        <div class="row justify-content-center g-4 mt-3">
            <!-- Asisten -->
            <div class="col-md-4 col-lg-3" data-aos="fade-up" data-aos-delay="100">
                <div class="leader-card">
                    <div class="leader-photo" style="width: 140px; height: 140px;">
                        <img src="{{ asset('images/asisten1.png') }}" alt="Asisten I" onerror="this.src='https://ui-avatars.com/api/?name=Asisten+1&size=200&background=dc2626&color=fff'">
                    </div>
                    <h4 class="leader-name" style="font-size: 14px;">GEORGE HEPLIN EDWAR DODDY, S.Sos</h4>
                    <p class="leader-position">Asisten I Pemerintahan dan Kesejahteraan Rakyat</p>
                </div>
            </div>
            <div class="col-md-4 col-lg-3" data-aos="fade-up" data-aos-delay="200">
                <div class="leader-card">
                    <div class="leader-photo" style="width: 140px; height: 140px;">
                        <img src="{{ asset('images/asisten2.png') }}" alt="Asisten II" onerror="this.src='https://ui-avatars.com/api/?name=Asisten+2&size=200&background=dc2626&color=fff'">
                    </div>
                    <h4 class="leader-name" style="font-size: 14px;">Dr. Ir. CHRISTIAN RAIN, M.T.</h4>
                    <p class="leader-position">Plt. Asisten II Perekonomian dan Pembangunan</p>
                </div>
            </div>
            <div class="col-md-4 col-lg-3" data-aos="fade-up" data-aos-delay="300">
                <div class="leader-card">
                    <div class="leader-photo" style="width: 140px; height: 140px;">
                        <img src="{{ asset('images/asisten3.png') }}" alt="Asisten III" onerror="this.src='https://ui-avatars.com/api/?name=Asisten+3&size=200&background=dc2626&color=fff'">
                    </div>
                    <h4 class="leader-name" style="font-size: 14px;">EVIE SILVIA BABOE, ST</h4>
                    <p class="leader-position">Asisten III Administrasi Umum</p>
                </div>
            </div>
        </div>
        @endif
    </div>
</section>

<!-- Visi Misi Section -->
<section class="section" id="tentang">
    <div class="container">
        <div class="row g-4">
            <div class="col-lg-6" data-aos="fade-right">
                <div class="visi-misi-card">
                    <h3><i class="bi bi-bullseye"></i> VISI</h3>
                    <p class="fs-5 fw-medium" style="line-height: 1.8;">
                        "Terwujudnya Kabupaten Katingan yang Maju, Sejahtera, Berkeadilan dan Berakhlak Mulia"
                    </p>
                </div>
            </div>
            <div class="col-lg-6" data-aos="fade-left">
                <div class="visi-misi-card">
                    <h3><i class="bi bi-flag"></i> MISI</h3>
                    <ol class="ps-3" style="line-height: 2;">
                        <li>Mewujudkan suasana kehidupan yang rukun, aman, damai dan sejahtera</li>
                        <li>Mewujudkan kehidupan masyarakat yang religius dan harmonis</li>
                        <li>Mewujudkan kualitas sumber daya manusia yang handal dan berdaya saing</li>
                        <li>Mewujudkan tingkat kesehatan masyarakat yang baik dan memenuhi standar</li>
                        <li>Mewujudkan pelayanan publik yang memuaskan dan membahagiakan</li>
                        <li>Mewujudkan infrastruktur yang baik dan mantap</li>
                        <li>Mewujudkan kenyamanan dalam berusaha dan berinvestasi</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Video Section with Swiper Carousel -->
<section class="section section-light">
    <div class="container">
        <div class="section-header text-center" data-aos="fade-up">
            <h2>Video <span class="highlight">Profil</span></h2>
            <p class="mx-auto">Kenali lebih dekat Kabupaten Katingan melalui video profil kami</p>
        </div>

        <div class="video-swiper-container" data-aos="fade-up">
            <div class="swiper videoSwiper">
                <div class="swiper-wrapper">
                    @if($videos->count() > 0)
                        @foreach($videos as $video)
                        <div class="swiper-slide">
                            <div class="video-card" data-video-id="{{ $video->youtube_id }}">
                                <div class="video-card-thumbnail">
                                    <img src="{{ $video->thumbnail_url }}" alt="{{ $video->judul }}">
                                </div>
                                <div class="video-card-iframe">
                                    <iframe src="" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>
                                </div>
                                <div class="video-card-overlay">
                                    <div class="video-card-play"><i class="bi bi-play-fill"></i></div>
                                </div>
                                <div class="video-card-title">{{ $video->judul }}</div>
                            </div>
                        </div>
                        @endforeach
                    @else
                        <div class="swiper-slide">
                            <div class="video-card" data-video-id="dQw4w9WgXcQ">
                                <div class="video-card-thumbnail">
                                    <img src="https://img.youtube.com/vi/dQw4w9WgXcQ/maxresdefault.jpg" alt="Video 1">
                                </div>
                                <div class="video-card-iframe"><iframe src="" frameborder="0" allowfullscreen></iframe></div>
                                <div class="video-card-overlay"><div class="video-card-play"><i class="bi bi-play-fill"></i></div></div>
                                <div class="video-card-title">Profil Kabupaten Katingan</div>
                            </div>
                        </div>
                        <div class="swiper-slide">
                            <div class="video-card" data-video-id="ScMzIvxBSi4">
                                <div class="video-card-thumbnail">
                                    <img src="https://img.youtube.com/vi/ScMzIvxBSi4/maxresdefault.jpg" alt="Video 2">
                                </div>
                                <div class="video-card-iframe"><iframe src="" frameborder="0" allowfullscreen></iframe></div>
                                <div class="video-card-overlay"><div class="video-card-play"><i class="bi bi-play-fill"></i></div></div>
                                <div class="video-card-title">Sekretariat Daerah Katingan</div>
                            </div>
                        </div>
                        <div class="swiper-slide">
                            <div class="video-card" data-video-id="kJQP7kiw5Fk">
                                <div class="video-card-thumbnail">
                                    <img src="https://img.youtube.com/vi/kJQP7kiw5Fk/maxresdefault.jpg" alt="Video 3">
                                </div>
                                <div class="video-card-iframe"><iframe src="" frameborder="0" allowfullscreen></iframe></div>
                                <div class="video-card-overlay"><div class="video-card-play"><i class="bi bi-play-fill"></i></div></div>
                                <div class="video-card-title">Profil Investasi Katingan</div>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
            <div class="video-swiper-nav">
                <button class="video-nav-btn video-prev"><i class="bi bi-chevron-left"></i></button>
                <button class="video-nav-btn video-next"><i class="bi bi-chevron-right"></i></button>
            </div>
        </div>
    </div>
</section>

<!-- Berita Section -->
<section class="section">
    <div class="container">
        <div class="row align-items-end mb-5">
            <div class="col-lg-6" data-aos="fade-right">
                <div class="section-header mb-0">
                    <h2>Update Berita &<br><span class="highlight">Informasi Terkini</span></h2>
                </div>
            </div>
            <div class="col-lg-6 text-lg-end" data-aos="fade-left">
                <p class="text-muted mb-3">Menyajikan berita faktual dan insight mendalam untuk mendukung pengambilan keputusan.</p>
                <a href="{{ route('landing.berita') }}" class="section-link">
                    Jelajahi Berita Lainnya <i class="bi bi-arrow-right"></i>
                </a>
            </div>
        </div>

        @if($beritaTerbaru->count() > 0)
            <div class="row g-4">
                @foreach($beritaTerbaru->take(3) as $index => $berita)
                    <div class="col-md-4" data-aos="fade-up" data-aos-delay="{{ ($index + 1) * 100 }}">
                        <div class="news-card {{ $index == 1 ? 'featured' : '' }}">
                            <div class="news-image">
                                @if($berita->foto)
                                    <img src="{{ asset('storage/' . $berita->foto) }}" alt="{{ $berita->judul }}">
                                @else
                                    <img src="https://images.unsplash.com/photo-1504711434969-e33886168f5c?w=600&h=400&fit=crop" alt="{{ $berita->judul }}">
                                @endif
                            </div>
                            <div class="news-body">
                                <span class="news-meta">{{ $berita->created_at->format('d M Y') }}</span>
                                <h3 class="news-title">{{ $berita->judul }}</h3>
                                <p class="news-excerpt">{{ Str::limit(strip_tags($berita->konten), 100) }}</p>
                                <a href="{{ route('landing.berita.detail', $berita->slug) }}" class="news-link">Baca Selengkapnya</a>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        @else
            <div class="text-center py-5">
                <i class="bi bi-newspaper text-muted" style="font-size: 64px;"></i>
                <p class="text-muted mt-3">Belum ada berita tersedia</p>
            </div>
        @endif

        @if($beritaTerbaru->count() > 3)
            <!-- News Slider for More News -->
            <div class="mt-5" data-aos="fade-up">
                <h5 class="mb-4">Berita Lainnya</h5>
                <div class="swiper news-slider">
                    <div class="swiper-wrapper">
                        @foreach($beritaTerbaru->skip(3) as $berita)
                            <div class="swiper-slide">
                                <div class="news-card h-100">
                                    <div class="news-image" style="height: 150px;">
                                        @if($berita->foto)
                                            <img src="{{ asset('storage/' . $berita->foto) }}" alt="{{ $berita->judul }}">
                                        @else
                                            <img src="https://images.unsplash.com/photo-1504711434969-e33886168f5c?w=400&h=250&fit=crop" alt="{{ $berita->judul }}">
                                        @endif
                                    </div>
                                    <div class="news-body">
                                        <span class="news-meta">{{ $berita->created_at->format('d M Y') }}</span>
                                        <h3 class="news-title" style="font-size: 14px;">{{ Str::limit($berita->judul, 60) }}</h3>
                                        <a href="{{ route('landing.berita.detail', $berita->slug) }}" class="news-link" style="padding: 8px 16px; font-size: 12px;">Baca</a>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                    <div class="swiper-pagination mt-4"></div>
                </div>
            </div>
        @endif
    </div>
</section>

<!-- Stats Section -->
<section class="stats-section">
    <div class="container">
        <div class="row g-4 text-center">
            <div class="col-6 col-md-3" data-aos="fade-up" data-aos-delay="100">
                <div class="stat-item">
                    <div class="stat-value" data-count="13">0</div>
                    <div class="stat-label">Kecamatan</div>
                </div>
            </div>
            <div class="col-6 col-md-3" data-aos="fade-up" data-aos-delay="200">
                <div class="stat-item">
                    <div class="stat-value" data-count="154">0</div>
                    <div class="stat-label">Desa/Kelurahan</div>
                </div>
            </div>
            <div class="col-6 col-md-3" data-aos="fade-up" data-aos-delay="300">
                <div class="stat-item">
                    <div class="stat-value" data-count="180">0</div>
                    <div class="stat-label">Ribu Penduduk</div>
                </div>
            </div>
            <div class="col-6 col-md-3" data-aos="fade-up" data-aos-delay="400">
                <div class="stat-item">
                    <div class="stat-value" data-count="17800">0</div>
                    <div class="stat-label">KM² Luas Wilayah</div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Visitor Statistics Section -->
<section class="section visitor-stats-section" id="statistik-pengunjung">
    <div class="container">
        <div class="section-header text-center" data-aos="fade-up">
            <h2>Grafik Jumlah <span class="highlight">Pengunjung Website</span></h2>
            <p class="mx-auto">Statistik pengunjung website bulanan dan tahunan</p>
        </div>

        <div class="row g-4">
            <!-- Current Year Chart -->
            <div class="col-lg-8" data-aos="fade-up" data-aos-delay="100">
                <div class="visitor-chart-card main-chart">
                    <div class="chart-header">
                        <h5>TAHUN SAAT INI</h5>
                        <span class="chart-year-badge">JUMLAH PENGUNJUNG TAHUN {{ $visitorStats['current_year'] ?? date('Y') }} (ORANG)</span>
                    </div>
                    <div class="chart-container">
                        <canvas id="visitorChartCurrent"></canvas>
                    </div>
                    <div class="chart-nav">
                        <button class="chart-nav-btn" id="chartPrevYear"><i class="bi bi-chevron-left"></i></button>
                        <button class="chart-nav-btn" id="chartNextYear"><i class="bi bi-chevron-right"></i></button>
                    </div>
                </div>
            </div>

            <!-- Previous Year Chart (Smaller) -->
            <div class="col-lg-4" data-aos="fade-up" data-aos-delay="200">
                <div class="visitor-chart-card side-chart">
                    <div class="chart-header">
                        <h5>TAHUN SEBELUMNYA</h5>
                        <span class="chart-year-badge small">JUMLAH PENGUNJUNG TAHUN {{ $visitorStats['previous_year'] ?? (date('Y') - 1) }} (ORANG)</span>
                    </div>
                    <div class="chart-container small">
                        <canvas id="visitorChartPrevious"></canvas>
                    </div>
                </div>

                <!-- Visitor Stats Summary -->
                <div class="visitor-summary mt-4">
                    <div class="summary-item">
                        <i class="bi bi-people-fill"></i>
                        <div class="summary-content">
                            <span class="summary-value">{{ number_format($visitorStats['total_visitors'] ?? 0) }}</span>
                            <span class="summary-label">Total Pengunjung</span>
                        </div>
                    </div>
                    <div class="summary-item">
                        <i class="bi bi-calendar-check"></i>
                        <div class="summary-content">
                            <span class="summary-value">{{ number_format($visitorStats['today_visitors'] ?? 0) }}</span>
                            <span class="summary-label">Hari Ini</span>
                        </div>
                    </div>
                    <div class="summary-item">
                        <i class="bi bi-calendar-month"></i>
                        <div class="summary-content">
                            <span class="summary-value">{{ number_format($visitorStats['this_month_visitors'] ?? 0) }}</span>
                            <span class="summary-label">Bulan Ini</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Contact Section -->
<section class="section contact-section" id="kontak">
    <div class="container">
        <div class="row g-5">
            <div class="col-lg-5" data-aos="fade-right">
                <div class="section-header">
                    <h2>Hubungi <span class="highlight">Kami</span></h2>
                    <p>Kami siap melayani pertanyaan dan kebutuhan informasi Anda.</p>
                </div>

                <div class="contact-info">
                    <div class="contact-item">
                        <div class="contact-icon">
                            <i class="bi bi-geo-alt"></i>
                        </div>
                        <div class="contact-text">
                            <h4>Alamat</h4>
                            <p>{{ \App\Helpers\SettingHelper::address() }}</p>
                        </div>
                    </div>
                    <div class="contact-item">
                        <div class="contact-icon">
                            <i class="bi bi-telephone"></i>
                        </div>
                        <div class="contact-text">
                            <h4>Telepon</h4>
                            <p><a href="tel:{{ \App\Helpers\SettingHelper::phone() }}">{{ \App\Helpers\SettingHelper::phone() }}</a></p>
                        </div>
                    </div>
                    <div class="contact-item">
                        <div class="contact-icon">
                            <i class="bi bi-envelope"></i>
                        </div>
                        <div class="contact-text">
                            <h4>Email</h4>
                            <p><a href="mailto:{{ \App\Helpers\SettingHelper::email() }}">{{ \App\Helpers\SettingHelper::email() }}</a></p>
                        </div>
                    </div>
                    <div class="contact-item">
                        <div class="contact-icon">
                            <i class="bi bi-whatsapp"></i>
                        </div>
                        <div class="contact-text">
                            <h4>WhatsApp</h4>
                            <p><a href="https://wa.me/6281727200000">0817-2720-0000</a></p>
                        </div>
                    </div>
                </div>

                <div class="mt-4">
                    <h5 class="mb-3">Ikuti Kami</h5>
                    <div class="social-links">
                        <a href="#"><i class="bi bi-facebook"></i></a>
                        <a href="#"><i class="bi bi-instagram"></i></a>
                        <a href="#"><i class="bi bi-youtube"></i></a>
                    </div>
                </div>
            </div>
            <div class="col-lg-7" data-aos="fade-left">
                <div class="map-container">
                    @if(\App\Helpers\SettingHelper::maps())
                        {!! \App\Helpers\SettingHelper::maps() !!}
                    @else
                        <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d127672.0!2d113.4!3d-1.8!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x2e0!2sKasongan%2C%20Katingan!5e0!3m2!1sen!2sid!4v1" allowfullscreen="" loading="lazy"></iframe>
                    @endif
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Custom Video Modal -->
<div class="custom-modal-overlay" id="videoModalOverlay">
    <div class="custom-modal" id="videoModal">
        <div class="custom-modal-header">
            <button type="button" class="custom-modal-close" id="closeVideoModal">
                <i class="bi bi-x-lg"></i>
            </button>
        </div>
        <div class="custom-modal-body">
            <div class="video-container">
                <iframe id="videoFrame" src="" allowfullscreen></iframe>
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
    /* Custom Modal Styles */
    .custom-modal-overlay {
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: rgba(0, 0, 0, 0.85);
        z-index: 9999;
        display: none;
        justify-content: center;
        align-items: center;
        opacity: 0;
        transition: opacity 0.3s ease;
    }

    .custom-modal-overlay.show {
        display: flex;
        opacity: 1;
    }

    .custom-modal {
        background: #1a1a1a;
        border-radius: 16px;
        width: 90%;
        max-width: 900px;
        max-height: 90vh;
        overflow: hidden;
        transform: scale(0.9);
        transition: transform 0.3s ease;
    }

    .custom-modal-overlay.show .custom-modal {
        transform: scale(1);
    }

    .custom-modal-header {
        display: flex;
        justify-content: flex-end;
        padding: 12px 16px;
    }

    .custom-modal-close {
        background: rgba(255, 255, 255, 0.1);
        border: none;
        color: white;
        width: 40px;
        height: 40px;
        border-radius: 50%;
        cursor: pointer;
        display: flex;
        align-items: center;
        justify-content: center;
        transition: all 0.2s ease;
    }

    .custom-modal-close:hover {
        background: rgba(255, 255, 255, 0.2);
    }

    .custom-modal-body {
        padding: 0 16px 16px;
    }

    .video-container {
        position: relative;
        width: 100%;
        padding-bottom: 56.25%;
        border-radius: 12px;
        overflow: hidden;
    }

    .video-container iframe {
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        border: none;
    }
</style>
@endpush

@push('scripts')
<script>
    // Initialize Swiper
    const newsSlider = new Swiper('.news-slider', {
        slidesPerView: 1,
        spaceBetween: 20,
        pagination: {
            el: '.swiper-pagination',
            clickable: true,
        },
        breakpoints: {
            576: { slidesPerView: 2 },
            992: { slidesPerView: 4 },
        }
    });

    // Custom Modal Functions
    const videoModalOverlay = document.getElementById('videoModalOverlay');
    const videoFrame = document.getElementById('videoFrame');
    const closeVideoModal = document.getElementById('closeVideoModal');

    function openVideoModal(videoUrl) {
        if (videoUrl) {
            videoFrame.src = videoUrl;
            videoModalOverlay.classList.add('show');
            document.body.style.overflow = 'hidden';
        }
    }

    function closeModal() {
        videoModalOverlay.classList.remove('show');
        videoFrame.src = '';
        document.body.style.overflow = '';
    }

    // Video Cards Click
    document.querySelectorAll('.video-card').forEach(card => {
        card.addEventListener('click', function() {
            const videoUrl = this.dataset.video;
            openVideoModal(videoUrl);
        });
    });

    // Close modal button
    if (closeVideoModal) {
        closeVideoModal.addEventListener('click', closeModal);
    }

    // Close on overlay click
    if (videoModalOverlay) {
        videoModalOverlay.addEventListener('click', function(e) {
            if (e.target === videoModalOverlay) {
                closeModal();
            }
        });
    }

    // Close on ESC key
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape' && videoModalOverlay.classList.contains('show')) {
            closeModal();
        }
    });

    // Stacked Carousel Auto-Slide
    const stackedCarousel = document.getElementById('stackedCarousel');
    if (stackedCarousel) {
        const slides = stackedCarousel.querySelectorAll('.stacked-slide');
        const totalSlides = slides.length;
        let currentIndex = 0;
        let autoSlideInterval;

        function updateSlidePositions() {
            slides.forEach((slide, index) => {
                slide.classList.remove('active');

                // Calculate relative position from current
                let diff = index - currentIndex;

                // Handle wrapping for circular effect
                if (diff > totalSlides / 2) diff -= totalSlides;
                if (diff < -totalSlides / 2) diff += totalSlides;

                if (diff === 0) {
                    slide.setAttribute('data-position', 'active');
                    slide.classList.add('active');
                } else if (diff === -1) {
                    slide.setAttribute('data-position', 'prev-1');
                } else if (diff === -2) {
                    slide.setAttribute('data-position', 'prev-2');
                } else if (diff === 1) {
                    slide.setAttribute('data-position', 'next-1');
                } else if (diff === 2) {
                    slide.setAttribute('data-position', 'next-2');
                } else {
                    slide.setAttribute('data-position', 'hidden');
                }
            });
        }

        function nextSlide() {
            currentIndex = (currentIndex + 1) % totalSlides;
            updateSlidePositions();
        }

        function goToSlide(index) {
            currentIndex = index;
            updateSlidePositions();
            resetAutoSlide();
        }

        function resetAutoSlide() {
            clearInterval(autoSlideInterval);
            autoSlideInterval = setInterval(nextSlide, 4000);
        }

        // Initialize
        updateSlidePositions();

        // Auto slide every 4 seconds
        autoSlideInterval = setInterval(nextSlide, 4000);

        // Click on slide to make it active
        slides.forEach((slide, index) => {
            slide.addEventListener('click', () => {
                if (index !== currentIndex) {
                    goToSlide(index);
                }
            });
        });

        // Pause on hover
        stackedCarousel.addEventListener('mouseenter', () => {
            clearInterval(autoSlideInterval);
        });

        stackedCarousel.addEventListener('mouseleave', () => {
            autoSlideInterval = setInterval(nextSlide, 4000);
        });
    }

    // Video Swiper Carousel
    const videoSwiperEl = document.querySelector('.videoSwiper');
    if (videoSwiperEl) {
        let videoAutoPlayTimer = null;
        const videoSlideCount = videoSwiperEl.querySelectorAll('.swiper-slide').length;

        // Need at least 5 slides for loop with slidesPerView 2.2
        const canLoop = videoSlideCount >= 5;

        const videoSwiper = new Swiper('.videoSwiper', {
            slidesPerView: 1,
            spaceBetween: 20,
            centeredSlides: true,
            loop: canLoop,
            rewind: !canLoop, // Use rewind instead of loop when not enough slides
            speed: 600,
            breakpoints: {
                640: {
                    slidesPerView: Math.min(1.5, videoSlideCount > 1 ? 1.5 : 1),
                    spaceBetween: 25,
                },
                992: {
                    slidesPerView: Math.min(2.2, videoSlideCount > 2 ? 2.2 : videoSlideCount),
                    spaceBetween: 30,
                },
            },
            on: {
                slideChange: function() {
                    stopAllVideos();
                },
                click: function(swiper, event) {
                    const clickedSlide = event.target.closest('.swiper-slide');
                    if (clickedSlide) {
                        const slideIndex = Array.from(swiper.slides).indexOf(clickedSlide);
                        if (slideIndex === swiper.activeIndex) {
                            // Clicked on active slide - play video
                            const videoCard = clickedSlide.querySelector('.video-card');
                            if (videoCard && !videoCard.classList.contains('playing')) {
                                playVideo(videoCard);
                            }
                        } else {
                            // Clicked on side slide - go to that slide
                            swiper.slideTo(slideIndex);
                        }
                    }
                }
            }
        });

        // Navigation buttons
        document.querySelector('.video-prev')?.addEventListener('click', () => {
            stopAllVideos();
            videoSwiper.slidePrev();
        });
        document.querySelector('.video-next')?.addEventListener('click', () => {
            stopAllVideos();
            videoSwiper.slideNext();
        });

        function playVideo(videoCard) {
            stopAllVideos();

            const videoId = videoCard.dataset.videoId;
            const iframe = videoCard.querySelector('.video-card-iframe iframe');

            videoCard.classList.add('playing');
            iframe.src = `https://www.youtube.com/embed/${videoId}?autoplay=1&rel=0`;

            // Auto advance after 30 seconds
            clearTimeout(videoAutoPlayTimer);
            videoAutoPlayTimer = setTimeout(() => {
                videoSwiper.slideNext();
                setTimeout(() => {
                    const activeSlide = videoSwiper.slides[videoSwiper.activeIndex];
                    const activeCard = activeSlide?.querySelector('.video-card');
                    if (activeCard) playVideo(activeCard);
                }, 700);
            }, 30000);
        }

        function stopAllVideos() {
            clearTimeout(videoAutoPlayTimer);
            document.querySelectorAll('.video-card').forEach(card => {
                const iframe = card.querySelector('.video-card-iframe iframe');
                if (iframe) iframe.src = '';
                card.classList.remove('playing');
            });
        }

        // Auto-play first video after 2 seconds
        setTimeout(() => {
            const activeSlide = videoSwiper.slides[videoSwiper.activeIndex];
            const activeCard = activeSlide?.querySelector('.video-card');
            if (activeCard) playVideo(activeCard);
        }, 2000);
    }

    // Counter Animation
    const counters = document.querySelectorAll('.stat-value[data-count]');
    const observerOptions = { threshold: 0.5 };

    const observer = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                const counter = entry.target;
                const target = parseInt(counter.dataset.count);
                const duration = 2000;
                const step = target / (duration / 16);
                let current = 0;

                const updateCounter = () => {
                    current += step;
                    if (current < target) {
                        counter.textContent = Math.floor(current).toLocaleString();
                        requestAnimationFrame(updateCounter);
                    } else {
                        counter.textContent = target.toLocaleString();
                    }
                };

                updateCounter();
                observer.unobserve(counter);
            }
        });
    }, observerOptions);

    counters.forEach(counter => observer.observe(counter));

    // Visitor Statistics Chart
    const currentYearData = @json($visitorStats['current_year_data'] ?? []);
    const previousYearData = @json($visitorStats['previous_year_data'] ?? []);
    const currentYear = {{ $visitorStats['current_year'] ?? date('Y') }};
    const previousYear = {{ $visitorStats['previous_year'] ?? (date('Y') - 1) }};

    const monthLabels = ['Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun', 'Jul', 'Agu', 'Sep', 'Okt', 'Nov', 'Des'];

    // Convert data to array format
    function dataToArray(data) {
        return monthLabels.map((_, i) => data[i + 1] || 0);
    }

    // Chart.js configuration for current year
    const ctxCurrent = document.getElementById('visitorChartCurrent');
    if (ctxCurrent) {
        const currentData = dataToArray(currentYearData);

        new Chart(ctxCurrent, {
            type: 'line',
            data: {
                labels: monthLabels,
                datasets: [{
                    label: `Pengunjung ${currentYear}`,
                    data: currentData,
                    borderColor: '#f97316',
                    backgroundColor: 'rgba(249, 115, 22, 0.1)',
                    borderWidth: 3,
                    pointBackgroundColor: '#f97316',
                    pointBorderColor: '#fff',
                    pointBorderWidth: 2,
                    pointRadius: 6,
                    pointHoverRadius: 8,
                    tension: 0.4,
                    fill: true,
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                animation: {
                    duration: 1500,
                    easing: 'easeOutQuart'
                },
                interaction: {
                    intersect: false,
                    mode: 'index'
                },
                plugins: {
                    legend: {
                        display: false
                    },
                    tooltip: {
                        backgroundColor: 'rgba(0, 0, 0, 0.8)',
                        titleFont: { size: 14, weight: 'bold' },
                        bodyFont: { size: 13 },
                        padding: 12,
                        displayColors: false,
                        callbacks: {
                            label: function(context) {
                                return context.parsed.y.toLocaleString() + ' pengunjung';
                            }
                        }
                    },
                    datalabels: {
                        display: true
                    }
                },
                scales: {
                    x: {
                        grid: { display: false },
                        ticks: {
                            font: { size: 11 },
                            color: '#64748b'
                        }
                    },
                    y: {
                        beginAtZero: true,
                        grid: {
                            color: 'rgba(0, 0, 0, 0.05)',
                            drawBorder: false
                        },
                        ticks: {
                            font: { size: 11 },
                            color: '#64748b',
                            callback: function(value) {
                                return value.toLocaleString();
                            }
                        }
                    }
                }
            },
            plugins: [{
                afterDatasetsDraw: function(chart) {
                    const ctx = chart.ctx;
                    chart.data.datasets.forEach((dataset, i) => {
                        const meta = chart.getDatasetMeta(i);
                        meta.data.forEach((element, index) => {
                            const value = dataset.data[index];
                            if (value > 0) {
                                ctx.save();
                                ctx.font = 'bold 10px Arial';
                                ctx.fillStyle = '#1e293b';
                                ctx.textAlign = 'center';
                                ctx.textBaseline = 'bottom';
                                ctx.fillText(value.toLocaleString(), element.x, element.y - 10);
                                ctx.restore();
                            }
                        });
                    });
                }
            }]
        });
    }

    // Chart.js configuration for previous year
    const ctxPrevious = document.getElementById('visitorChartPrevious');
    if (ctxPrevious) {
        const previousData = dataToArray(previousYearData);

        new Chart(ctxPrevious, {
            type: 'line',
            data: {
                labels: monthLabels,
                datasets: [{
                    label: `Pengunjung ${previousYear}`,
                    data: previousData,
                    borderColor: '#f97316',
                    backgroundColor: 'rgba(249, 115, 22, 0.1)',
                    borderWidth: 2,
                    pointBackgroundColor: '#f97316',
                    pointBorderColor: '#fff',
                    pointBorderWidth: 1,
                    pointRadius: 4,
                    pointHoverRadius: 6,
                    tension: 0.4,
                    fill: true,
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                animation: {
                    duration: 1500,
                    easing: 'easeOutQuart'
                },
                plugins: {
                    legend: { display: false },
                    tooltip: {
                        backgroundColor: 'rgba(0, 0, 0, 0.8)',
                        padding: 8,
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
                        ticks: {
                            font: { size: 9 },
                            color: '#64748b',
                            maxRotation: 45
                        }
                    },
                    y: {
                        beginAtZero: true,
                        grid: {
                            color: 'rgba(0, 0, 0, 0.05)',
                            drawBorder: false
                        },
                        ticks: {
                            font: { size: 9 },
                            color: '#64748b',
                            callback: function(value) {
                                if (value >= 1000) return (value/1000) + 'k';
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

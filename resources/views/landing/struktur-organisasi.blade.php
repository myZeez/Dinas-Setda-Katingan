@extends('landing.layouts.app')

@section('title', 'Struktur Organisasi')

@section('content')
<!-- Page Header -->
<section class="page-header">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-lg-8">
                <nav class="breadcrumb-nav" data-aos="fade-up">
                    <a href="{{ route('landing') }}">Beranda</a>
                    <span>/</span>
                    <span>Struktur Organisasi</span>
                </nav>
                <h1 class="page-title" data-aos="fade-up" data-aos-delay="100">Struktur Organisasi</h1>
                <p class="page-subtitle" data-aos="fade-up" data-aos-delay="200">
                    Struktur organisasi Sekretariat Daerah Kabupaten Katingan
                </p>
            </div>
        </div>
    </div>
</section>

<!-- Struktur Section -->
<section class="section section-light">
    <div class="container">
        @if($unitKerjas->count() > 0)
            @foreach($unitKerjas as $index => $unit)
                <div class="unit-kerja-section mb-5" data-aos="fade-up" data-aos-delay="{{ ($index % 3 + 1) * 100 }}">
                    <div class="unit-header">
                        <h3 class="unit-title">
                            <i class="bi bi-building"></i>
                            {{ $unit->nama }}
                        </h3>
                        @if($unit->deskripsi)
                            <p class="unit-desc">{{ $unit->deskripsi }}</p>
                        @endif
                    </div>

                    @if($unit->pegawais->count() > 0)
                        @php
                            $pimpinan = $unit->pegawais->where('is_pimpinan', 1)->first();
                            $staffs = $unit->pegawais->where('is_pimpinan', 0)->sortBy('urutan');
                        @endphp

                        {{-- Pimpinan Section --}}
                        @if($pimpinan)
                            <div class="pimpinan-section mt-4 mb-4" data-aos="zoom-in">
                                <div class="pegawai-card pegawai-card-pimpinan">
                                    <div class="pegawai-photo pegawai-photo-pimpinan">
                                        @if($pimpinan->foto)
                                            <img src="{{ asset('storage/' . $pimpinan->foto) }}" alt="{{ $pimpinan->nama }}">
                                        @else
                                            <img src="https://ui-avatars.com/api/?name={{ urlencode($pimpinan->nama) }}&size=300&background=dc2626&color=fff" alt="{{ $pimpinan->nama }}">
                                        @endif
                                    </div>
                                    <div class="pegawai-info">
                                        <div class="badge-pimpinan mb-2">
                                            <i class="bi bi-star-fill"></i> Kepala Bagian
                                        </div>
                                        <h4 class="pegawai-name">{{ $pimpinan->nama }}</h4>
                                        <p class="pegawai-position">{{ $pimpinan->jabatan }}</p>
                                        @if($pimpinan->nip)
                                            <span class="pegawai-nip">NIP. {{ $pimpinan->nip }}</span>
                                        @endif
                                    </div>
                                </div>
                            </div>

                            {{-- Connector Line --}}
                            @if($staffs->count() > 0)
                                <div class="struktur-connector"></div>
                            @endif
                        @endif

                        {{-- Staff Section --}}
                        @if($staffs->count() > 0)
                            <div class="row g-4 mt-3">
                                @foreach($staffs as $pegawai)
                                    <div class="col-6 col-md-4 col-lg-3">
                                        <div class="pegawai-card">
                                            <div class="pegawai-photo">
                                                @if($pegawai->foto)
                                                    <img src="{{ asset('storage/' . $pegawai->foto) }}" alt="{{ $pegawai->nama }}">
                                                @else
                                                    <img src="https://ui-avatars.com/api/?name={{ urlencode($pegawai->nama) }}&size=200&background=dc2626&color=fff" alt="{{ $pegawai->nama }}">
                                                @endif
                                            </div>
                                            <div class="pegawai-info">
                                                <h4 class="pegawai-name">{{ $pegawai->nama }}</h4>
                                                <p class="pegawai-position">{{ $pegawai->jabatan }}</p>
                                                @if($pegawai->nip)
                                                    <span class="pegawai-nip">NIP. {{ $pegawai->nip }}</span>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @endif
                    @else
                        <div class="text-center py-4 text-muted">
                            <i class="bi bi-people" style="font-size: 40px;"></i>
                            <p class="mt-2 mb-0">Belum ada pegawai di unit ini</p>
                        </div>
                    @endif
                </div>
            @endforeach
        @else
            <div class="text-center py-5">
                <i class="bi bi-diagram-3 text-muted" style="font-size: 80px;"></i>
                <h3 class="mt-4">Struktur Organisasi Belum Tersedia</h3>
                <p class="text-muted">Informasi struktur organisasi akan segera ditampilkan</p>
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
    .unit-kerja-section {
        background: white;
        border-radius: 20px;
        padding: 30px;
        box-shadow: 0 10px 40px rgba(0,0,0,0.08);
    }

    .unit-header {
        padding-bottom: 20px;
        border-bottom: 2px solid #f1f5f9;
    }

    .unit-title {
        font-size: 20px;
        font-weight: 600;
        color: #0a1628;
        display: flex;
        align-items: center;
        gap: 12px;
    }

    .unit-title i {
        color: #dc2626;
        font-size: 24px;
    }

    .unit-desc {
        color: #64748b;
        margin: 10px 0 0;
        font-size: 14px;
    }

    /* Pimpinan Section */
    .pimpinan-section {
        display: flex;
        justify-content: center;
        align-items: center;
    }

    .pegawai-card-pimpinan {
        background: linear-gradient(135deg, #ffffff 0%, #fef2f2 100%);
        border: 2px solid #dc2626;
        max-width: 350px;
        width: 100%;
        box-shadow: 0 20px 60px rgba(220, 38, 38, 0.2);
    }

    .pegawai-card-pimpinan:hover {
        transform: translateY(-8px);
        box-shadow: 0 25px 70px rgba(220, 38, 38, 0.3);
    }

    .pegawai-photo-pimpinan {
        width: 140px;
        height: 140px;
        border-width: 5px;
        box-shadow: 0 8px 30px rgba(220, 38, 38, 0.3);
    }

    .badge-pimpinan {
        display: inline-block;
        background: linear-gradient(135deg, #dc2626, #b91c1c);
        color: white;
        padding: 6px 16px;
        border-radius: 20px;
        font-size: 12px;
        font-weight: 600;
        box-shadow: 0 4px 15px rgba(220, 38, 38, 0.3);
    }

    .badge-pimpinan i {
        margin-right: 4px;
        font-size: 11px;
    }

    /* Struktur Connector */
    .struktur-connector {
        position: relative;
        height: 60px;
        display: flex;
        justify-content: center;
        align-items: center;
        margin: 0 auto;
    }

    .struktur-connector::before {
        content: '';
        position: absolute;
        top: 0;
        left: 50%;
        transform: translateX(-50%);
        width: 2px;
        height: 30px;
        background: linear-gradient(180deg, #dc2626 0%, rgba(220, 38, 38, 0.3) 100%);
    }

    .struktur-connector::after {
        content: '';
        position: absolute;
        top: 30px;
        left: 0;
        right: 0;
        height: 2px;
        background: linear-gradient(90deg, transparent 0%, #dc2626 20%, #dc2626 80%, transparent 100%);
    }

    .pegawai-card {
        background: #f8fafc;
        border-radius: 15px;
        padding: 25px 20px;
        text-align: center;
        height: 100%;
        transition: all 0.3s ease;
    }

    .pegawai-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 15px 40px rgba(220, 38, 38, 0.15);
        background: white;
    }

    .pegawai-photo {
        width: 100px;
        height: 100px;
        border-radius: 50%;
        overflow: hidden;
        margin: 0 auto 20px;
        border: 4px solid #dc2626;
        box-shadow: 0 5px 20px rgba(220, 38, 38, 0.2);
    }

    .pegawai-photo img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }

    .pegawai-name {
        font-size: 15px;
        font-weight: 600;
        color: #0a1628;
        margin-bottom: 8px;
        line-height: 1.4;
    }

    .pegawai-position {
        font-size: 13px;
        color: #dc2626;
        font-weight: 500;
        margin-bottom: 8px;
    }

    .pegawai-nip {
        font-size: 11px;
        color: #94a3b8;
    }

    /* RESPONSIVE STYLES */
    /* TABLET (768px - 991px) */
    @media (max-width: 991px) {
        .unit-kerja-section {
            padding: 25px;
        }

        .unit-title {
            font-size: 18px;
        }

        .unit-title i {
            font-size: 22px;
        }

        .pegawai-photo-pimpinan {
            width: 120px;
            height: 120px;
        }

        .pegawai-card {
            padding: 20px 15px;
        }

        .pegawai-photo {
            width: 90px;
            height: 90px;
            margin-bottom: 15px;
        }

        .pegawai-name {
            font-size: 14px;
        }

        .pegawai-position {
            font-size: 12px;
        }
    }

    /* SMARTPHONE (< 768px) */
    @media (max-width: 767px) {
        .unit-kerja-section {
            padding: 20px;
            border-radius: 16px;
        }

        .unit-header {
            padding-bottom: 15px;
        }

        .unit-title {
            font-size: 16px;
        }

        .unit-title i {
            font-size: 20px;
        }

        .unit-desc {
            font-size: 13px;
        }

        .pegawai-card-pimpinan {
            padding: 20px 15px;
        }

        .pegawai-photo-pimpinan {
            width: 100px;
            height: 100px;
            border-width: 4px;
        }

        .badge-pimpinan {
            font-size: 11px;
            padding: 5px 12px;
        }

        .struktur-connector {
            height: 50px;
        }

        .struktur-connector::before {
            height: 25px;
        }

        .struktur-connector::after {
            top: 25px;
        }

        .pegawai-card {
            padding: 15px 12px;
            border-radius: 12px;
        }

        .pegawai-photo {
            width: 70px;
            height: 70px;
            margin-bottom: 12px;
            border-width: 3px;
        }

        .pegawai-name {
            font-size: 12px;
            margin-bottom: 6px;
        }

        .pegawai-position {
            font-size: 11px;
            margin-bottom: 6px;
        }

        .pegawai-nip {
            font-size: 10px;
        }
    }

    /* SMALL SMARTPHONE (< 480px) */
    @media (max-width: 480px) {
        .unit-kerja-section {
            padding: 15px;
        }

        .unit-title {
            font-size: 14px;
            gap: 8px;
        }

        .unit-title i {
            font-size: 18px;
        }

        .pegawai-photo-pimpinan {
            width: 80px;
            height: 80px;
        }

        .badge-pimpinan {
            font-size: 10px;
            padding: 4px 10px;
        }

        .struktur-connector {
            height: 40px;
        }

        .struktur-connector::before {
            height: 20px;
        }

        .struktur-connector::after {
            top: 20px;
        }

        .pegawai-photo {
            width: 60px;
            height: 60px;
        }

        .pegawai-name {
            font-size: 11px;
        }

        .pegawai-position {
            font-size: 10px;
        }
    }
</style>
@endpush

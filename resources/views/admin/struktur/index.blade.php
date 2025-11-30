@extends('admin.layouts.app')

@section('title', 'Struktur Organisasi')

@push('styles')
<style>
    .unit-tabs {
        display: flex;
        flex-wrap: wrap;
        gap: 8px;
        padding: 15px;
        background: #f8fafc;
        border-radius: 8px;
    }

    .unit-tab {
        padding: 8px 16px;
        border: 2px solid #e2e8f0;
        border-radius: 20px;
        background: #fff;
        font-size: 0.85rem;
        font-weight: 500;
        color: #64748b;
        cursor: pointer;
        transition: all 0.2s ease;
    }

    .unit-tab:hover {
        border-color: #dc2626;
        color: #dc2626;
    }

    .unit-tab.active {
        background: #dc2626;
        border-color: #dc2626;
        color: white;
    }

    .pegawai-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(220px, 1fr));
        gap: 20px;
    }

    .pegawai-card {
        background: #fff;
        border: 1px solid #e2e8f0;
        border-radius: 12px;
        padding: 20px;
        text-align: center;
        transition: all 0.2s ease;
    }

    .pegawai-card:hover {
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
        transform: translateY(-3px);
    }

    .pegawai-card.pimpinan {
        background: linear-gradient(135deg, #fef3c7 0%, #fde68a 100%);
        border-color: #f59e0b;
    }

    .pegawai-photo {
        width: 100px;
        height: 120px;
        margin: 0 auto 15px;
        border-radius: 8px;
        overflow: hidden;
        background: #e2e8f0;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .pegawai-photo img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }

    .pegawai-photo i {
        font-size: 3rem;
        color: #94a3b8;
    }

    .pegawai-name {
        font-weight: 600;
        font-size: 0.95rem;
        color: #1f2937;
        margin-bottom: 5px;
    }

    .pegawai-jabatan {
        font-size: 0.8rem;
        color: #dc2626;
        margin-bottom: 5px;
        font-weight: 500;
    }

    .pegawai-nip {
        font-size: 0.75rem;
        color: #6b7280;
    }

    .pegawai-badge {
        display: inline-block;
        padding: 3px 10px;
        background: #f59e0b;
        color: white;
        font-size: 0.7rem;
        border-radius: 10px;
        margin-bottom: 10px;
        font-weight: 600;
    }

    .unit-header {
        display: flex;
        align-items: center;
        gap: 15px;
        padding: 20px;
        background: linear-gradient(135deg, #dc2626 0%, #b91c1c 100%);
        color: white;
        border-radius: 12px;
        margin-bottom: 20px;
    }

    .unit-header-icon {
        width: 60px;
        height: 60px;
        background: rgba(255, 255, 255, 0.2);
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.5rem;
    }

    .unit-header-info h4 {
        margin: 0;
        font-size: 1.2rem;
        font-weight: 600;
    }

    .unit-header-info p {
        margin: 5px 0 0;
        opacity: 0.9;
        font-size: 0.9rem;
    }

    .empty-state {
        text-align: center;
        padding: 60px 20px;
        color: #6b7280;
    }

    .empty-state i {
        font-size: 4rem;
        margin-bottom: 15px;
        opacity: 0.5;
    }
</style>
@endpush

@section('content')
<div class="page-header">
    <div class="d-flex justify-content-between align-items-center flex-wrap gap-2">
        <div>
            <h1 class="page-title">Struktur Organisasi</h1>
            <p class="page-subtitle">Sekretariat Daerah Kabupaten Katingan</p>
        </div>
        <div class="d-flex gap-2">
            <a href="{{ route('admin.struktur.unit-kerja') }}" class="btn btn-outline-primary">
                <i class="bi bi-diagram-3 me-1"></i> Kelola Unit Kerja
            </a>
            <a href="{{ route('admin.struktur.pegawai') }}" class="btn btn-primary">
                <i class="bi bi-people me-1"></i> Kelola Pegawai
            </a>
        </div>
    </div>
</div>

<!-- Statistics -->
<div class="row g-4 mb-4">
    <div class="col-6 col-md-3">
        <div class="card stat-card">
            <div class="card-body">
                <div class="d-flex align-items-center">
                    <div class="stat-icon blue me-3">
                        <i class="bi bi-diagram-3"></i>
                    </div>
                    <div>
                        <h3 class="stat-number mb-0">{{ $stats['total_unit'] }}</h3>
                        <p class="stat-label mb-0">Total Unit</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-6 col-md-3">
        <div class="card stat-card">
            <div class="card-body">
                <div class="d-flex align-items-center">
                    <div class="stat-icon green me-3">
                        <i class="bi bi-people"></i>
                    </div>
                    <div>
                        <h3 class="stat-number mb-0">{{ $stats['total_pegawai'] }}</h3>
                        <p class="stat-label mb-0">Total Pegawai</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-6 col-md-3">
        <div class="card stat-card">
            <div class="card-body">
                <div class="d-flex align-items-center">
                    <div class="stat-icon orange me-3">
                        <i class="bi bi-building"></i>
                    </div>
                    <div>
                        <h3 class="stat-number mb-0">{{ $stats['bagian'] }}</h3>
                        <p class="stat-label mb-0">Bagian</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-6 col-md-3">
        <div class="card stat-card">
            <div class="card-body">
                <div class="d-flex align-items-center">
                    <div class="stat-icon purple me-3">
                        <i class="bi bi-grid"></i>
                    </div>
                    <div>
                        <h3 class="stat-number mb-0">{{ $stats['subbagian'] }}</h3>
                        <p class="stat-label mb-0">Sub Bagian</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Unit Tabs -->
<div class="card mb-4">
    <div class="card-header">
        <h5 class="mb-0">
            <i class="bi bi-building me-2"></i>Pilih Bagian
        </h5>
    </div>
    <div class="card-body">
        <div class="unit-tabs">
            <button class="unit-tab active" data-unit="all">
                <i class="bi bi-grid-3x3-gap me-1"></i> Semua Bagian
            </button>
            @foreach($bagians as $bagian)
            <button class="unit-tab" data-unit="{{ $bagian->id }}">
                {{ $bagian->singkatan ?? $bagian->nama }}
            </button>
            @endforeach
        </div>
    </div>
</div>

<!-- Pegawai List -->
<div id="pegawai-container">
    @foreach($bagians as $bagian)
    <div class="bagian-section mb-4" data-bagian-id="{{ $bagian->id }}">
        <div class="unit-header">
            <div class="unit-header-icon">
                <i class="bi bi-building"></i>
            </div>
            <div class="unit-header-info">
                <h4>{{ $bagian->nama }}</h4>
                <p><i class="bi bi-people-fill me-1"></i> {{ $bagian->pegawais->count() }} Pegawai</p>
            </div>
        </div>

        @if($bagian->pegawais->count() > 0)
        <div class="pegawai-grid">
            @foreach($bagian->pegawais as $pegawai)
            <div class="pegawai-card {{ $pegawai->is_pimpinan ? 'pimpinan' : '' }}">
                @if($pegawai->is_pimpinan)
                <span class="pegawai-badge">
                    <i class="bi bi-star-fill me-1"></i> Kepala Bagian
                </span>
                @endif
                <div class="pegawai-photo">
                    @if($pegawai->foto)
                    <img src="{{ Storage::url($pegawai->foto) }}" alt="{{ $pegawai->nama }}" onerror="this.parentElement.innerHTML='<i class=\'bi bi-person\'></i>'">
                    @else
                    <i class="bi bi-person"></i>
                    @endif
                </div>
                <div class="pegawai-name">{{ $pegawai->nama }}</div>
                <div class="pegawai-jabatan">{{ $pegawai->jabatan }}</div>
                <div class="pegawai-nip">NIP. {{ $pegawai->nip ?? '-' }}</div>
            </div>
            @endforeach
        </div>
        @else
        <div class="card">
            <div class="empty-state">
                <i class="bi bi-people"></i>
                <p class="mb-0">Belum ada data pegawai</p>
            </div>
        </div>
        @endif
    </div>
    @endforeach

    @if($bagians->count() == 0)
    <div class="card">
        <div class="empty-state">
            <i class="bi bi-building"></i>
            <h5>Belum ada data bagian</h5>
            <p class="mb-3">Tambahkan unit kerja terlebih dahulu</p>
            <a href="{{ route('admin.struktur.unit-kerja') }}" class="btn btn-primary">
                <i class="bi bi-plus-lg me-1"></i> Tambah Unit Kerja
            </a>
        </div>
    </div>
    @endif
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const tabs = document.querySelectorAll('.unit-tab');
    const sections = document.querySelectorAll('.bagian-section');

    tabs.forEach(tab => {
        tab.addEventListener('click', function() {
            // Remove active from all tabs
            tabs.forEach(t => t.classList.remove('active'));
            // Add active to clicked tab
            this.classList.add('active');

            const unitId = this.dataset.unit;

            if (unitId === 'all') {
                // Show all sections
                sections.forEach(s => s.style.display = 'block');
            } else {
                // Show only selected section
                sections.forEach(s => {
                    if (s.dataset.bagianId === unitId) {
                        s.style.display = 'block';
                    } else {
                        s.style.display = 'none';
                    }
                });
            }
        });
    });
});
</script>
@endpush

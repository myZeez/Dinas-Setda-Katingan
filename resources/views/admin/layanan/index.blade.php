@extends('admin.layouts.app')

@section('title', 'Layanan Bag. Pemerintahan')

@section('content')
<div class="page-header">
    <h1 class="page-title">Layanan Bag. Pemerintahan</h1>
    <p class="page-subtitle">Dashboard pengelolaan layanan kerja sama</p>
</div>

<!-- Statistics Cards -->
<div class="row g-4 mb-4">
    <div class="col-6 col-md-4 col-lg-3">
        <div class="card stat-card">
            <div class="card-body">
                <div class="d-flex align-items-center">
                    <div class="stat-icon blue me-3">
                        <i class="bi bi-folder"></i>
                    </div>
                    <div>
                        <h3 class="stat-number mb-0">{{ $stats['total'] }}</h3>
                        <p class="stat-label mb-0">Total Pengajuan</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-6 col-md-4 col-lg-3">
        <div class="card stat-card">
            <div class="card-body">
                <div class="d-flex align-items-center">
                    <div class="stat-icon yellow me-3">
                        <i class="bi bi-hourglass-split"></i>
                    </div>
                    <div>
                        <h3 class="stat-number mb-0">{{ $stats['menunggu_review_sp'] ?? 0 }}</h3>
                        <p class="stat-label mb-0">Menunggu Review SP</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-6 col-md-4 col-lg-3">
        <div class="card stat-card">
            <div class="card-body">
                <div class="d-flex align-items-center">
                    <div class="stat-icon cyan me-3">
                        <i class="bi bi-check2-circle"></i>
                    </div>
                    <div>
                        <h3 class="stat-number mb-0">{{ $stats['sp_disetujui'] ?? 0 }}</h3>
                        <p class="stat-label mb-0">SP Disetujui</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-6 col-md-4 col-lg-3">
        <div class="card stat-card">
            <div class="card-body">
                <div class="d-flex align-items-center">
                    <div class="stat-icon orange me-3">
                        <i class="bi bi-exclamation-triangle"></i>
                    </div>
                    <div>
                        <h3 class="stat-number mb-0">{{ $stats['sp_revisi'] ?? 0 }}</h3>
                        <p class="stat-label mb-0">SP Perlu Revisi</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row g-4 mb-4">
    <div class="col-6 col-md-4 col-lg-3">
        <div class="card stat-card">
            <div class="card-body">
                <div class="d-flex align-items-center">
                    <div class="stat-icon teal me-3">
                        <i class="bi bi-files"></i>
                    </div>
                    <div>
                        <h3 class="stat-number mb-0">{{ $stats['dokumen_lengkap'] ?? 0 }}</h3>
                        <p class="stat-label mb-0">Dokumen Lengkap</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-6 col-md-4 col-lg-3">
        <div class="card stat-card">
            <div class="card-body">
                <div class="d-flex align-items-center">
                    <div class="stat-icon purple me-3">
                        <i class="bi bi-gear"></i>
                    </div>
                    <div>
                        <h3 class="stat-number mb-0">{{ $stats['diproses'] ?? 0 }}</h3>
                        <p class="stat-label mb-0">Sedang Diproses</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-6 col-md-4 col-lg-3">
        <div class="card stat-card">
            <div class="card-body">
                <div class="d-flex align-items-center">
                    <div class="stat-icon green me-3">
                        <i class="bi bi-check-circle"></i>
                    </div>
                    <div>
                        <h3 class="stat-number mb-0">{{ $stats['selesai'] ?? 0 }}</h3>
                        <p class="stat-label mb-0">Selesai</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-6 col-md-4 col-lg-3">
        <div class="card stat-card">
            <div class="card-body">
                <div class="d-flex align-items-center">
                    <div class="stat-icon red me-3">
                        <i class="bi bi-x-circle"></i>
                    </div>
                    <div>
                        <h3 class="stat-number mb-0">{{ $stats['ditolak'] ?? 0 }}</h3>
                        <p class="stat-label mb-0">Ditolak</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Quick Actions -->
<div class="row g-4 mb-4">
    <div class="col-md-6 col-lg-4">
        <a href="{{ route('admin.layanan.pengajuan') }}" class="card text-decoration-none h-100">
            <div class="card-body p-4">
                <div class="d-flex align-items-center">
                    <div class="stat-icon blue me-3">
                        <i class="bi bi-list-ul"></i>
                    </div>
                    <div>
                        <h6 class="mb-1 text-dark">Daftar Pengajuan</h6>
                        <small class="text-muted">Lihat semua pengajuan layanan</small>
                    </div>
                    <i class="bi bi-chevron-right ms-auto text-muted"></i>
                </div>
            </div>
        </a>
    </div>
    <div class="col-md-6 col-lg-4">
        <a href="{{ route('admin.layanan.pengajuan', ['status' => 'menunggu_review_sp']) }}" class="card text-decoration-none h-100">
            <div class="card-body p-4">
                <div class="d-flex align-items-center">
                    <div class="stat-icon yellow me-3">
                        <i class="bi bi-inbox"></i>
                    </div>
                    <div>
                        <h6 class="mb-1 text-dark">Pengajuan Baru</h6>
                        <small class="text-muted">{{ $stats['menunggu_review_sp'] ?? 0 }} pengajuan menunggu review</small>
                    </div>
                    <i class="bi bi-chevron-right ms-auto text-muted"></i>
                </div>
            </div>
        </a>
    </div>
    <div class="col-md-6 col-lg-4">
        <a href="{{ route('admin.layanan.jenis') }}" class="card text-decoration-none h-100">
            <div class="card-body p-4">
                <div class="d-flex align-items-center">
                    <div class="stat-icon purple me-3">
                        <i class="bi bi-tags"></i>
                    </div>
                    <div>
                        <h6 class="mb-1 text-dark">Jenis Layanan</h6>
                        <small class="text-muted">Kelola jenis layanan</small>
                    </div>
                    <i class="bi bi-chevron-right ms-auto text-muted"></i>
                </div>
            </div>
        </a>
    </div>
</div>

<!-- Recent Pengajuan -->
<div class="card">
    <div class="card-header bg-transparent border-0 pt-4 pb-0 px-4">
        <div class="d-flex justify-content-between align-items-center">
            <div>
                <h5 class="card-title mb-1">Pengajuan Terbaru</h5>
                <p class="text-muted small mb-0">5 pengajuan terakhir</p>
            </div>
            <a href="{{ route('admin.layanan.pengajuan') }}" class="btn btn-sm btn-outline-primary">
                Lihat Semua
            </a>
        </div>
    </div>
    <div class="card-body p-4">
        @if($recentPengajuans->count() > 0)
        <div class="table-responsive">
            <table class="table table-hover align-middle">
                <thead class="bg-light">
                    <tr>
                        <th>No. Pengajuan</th>
                        <th>Pemohon</th>
                        <th>Layanan</th>
                        <th>Tanggal</th>
                        <th class="text-center">Status</th>
                        <th class="text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($recentPengajuans as $pengajuan)
                    <tr>
                        <td>
                            <span class="fw-semibold">{{ $pengajuan->nomor_pengajuan }}</span>
                        </td>
                        <td>
                            <div>
                                <p class="mb-0">{{ $pengajuan->user->nama }}</p>
                                <small class="text-muted">{{ $pengajuan->user->instansi }}</small>
                            </div>
                        </td>
                        <td>
                            <span class="badge bg-light text-dark">{{ $pengajuan->jenisLayanan->kode }}</span>
                        </td>
                        <td>
                            <small>{{ $pengajuan->created_at->format('d M Y') }}</small>
                        </td>
                        <td class="text-center">
                            <span class="badge bg-{{ $pengajuan->status_color }}">
                                {{ $pengajuan->status_label }}
                            </span>
                        </td>
                        <td class="text-center">
                            <a href="{{ route('admin.layanan.detail', $pengajuan->id) }}" class="btn btn-sm btn-outline-primary">
                                <i class="bi bi-eye"></i>
                            </a>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        @else
        <div class="text-center py-5">
            <i class="bi bi-inbox text-muted" style="font-size: 48px;"></i>
            <p class="text-muted mt-3">Belum ada pengajuan layanan</p>
        </div>
        @endif
    </div>
</div>
@endsection

@push('styles')
<style>
    .stat-icon.purple {
        background: rgba(139, 92, 246, 0.1);
        color: #8b5cf6;
    }
    .stat-icon.orange {
        background: rgba(249, 115, 22, 0.1);
        color: #f97316;
    }
    .stat-icon.cyan {
        background: rgba(6, 182, 212, 0.1);
        color: #06b6d4;
    }
    .stat-icon.teal {
        background: rgba(20, 184, 166, 0.1);
        color: #14b8a6;
    }
    .stat-icon.red {
        background: rgba(239, 68, 68, 0.1);
        color: #ef4444;
    }
</style>
@endpush

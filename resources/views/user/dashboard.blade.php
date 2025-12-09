@extends('user.layouts.app')

@section('title', 'Dashboard')

@section('content')
<div class="page-header">
    <h1 class="page-title">Dashboard</h1>
    <p class="page-subtitle">Selamat datang di Portal Layanan Sekretariat Daerah Kabupaten Katingan</p>
</div>

<!-- Stats Cards -->
<div class="row g-4 mb-4">
    <div class="col-sm-6 col-lg-3">
        <div class="card stat-card">
            <div class="d-flex justify-content-between align-items-start">
                <div>
                    <p class="stat-value">{{ $stats['total_layanan'] ?? 0 }}</p>
                    <p class="stat-label">Total Pengajuan</p>
                </div>
                <div class="stat-icon green">
                    <i class="bi bi-file-earmark-text"></i>
                </div>
            </div>
        </div>
    </div>
    <div class="col-sm-6 col-lg-3">
        <div class="card stat-card">
            <div class="d-flex justify-content-between align-items-start">
                <div>
                    <p class="stat-value">{{ $stats['layanan_proses'] ?? 0 }}</p>
                    <p class="stat-label">Sedang Diproses</p>
                </div>
                <div class="stat-icon yellow">
                    <i class="bi bi-hourglass-split"></i>
                </div>
            </div>
        </div>
    </div>
    <div class="col-sm-6 col-lg-3">
        <div class="card stat-card">
            <div class="d-flex justify-content-between align-items-start">
                <div>
                    <p class="stat-value">{{ $stats['layanan_selesai'] ?? 0 }}</p>
                    <p class="stat-label">Selesai</p>
                </div>
                <div class="stat-icon blue">
                    <i class="bi bi-check-circle"></i>
                </div>
            </div>
        </div>
    </div>
    <div class="col-sm-6 col-lg-3">
        <div class="card stat-card">
            <div class="d-flex justify-content-between align-items-start">
                <div>
                    <p class="stat-value">{{ $stats['layanan_bulan_ini'] ?? 0 }}</p>
                    <p class="stat-label">Bulan Ini</p>
                </div>
                <div class="stat-icon purple">
                    <i class="bi bi-calendar-event"></i>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row g-4">
    <!-- Quick Actions -->
    <div class="col-lg-6">
        <div class="card">
            <div class="card-header bg-transparent border-0 pt-4 pb-0 px-4">
                <h5 class="card-title mb-1">Layanan Tersedia</h5>
                <p class="text-muted small mb-0">Pilih layanan yang ingin diajukan</p>
            </div>
            <div class="card-body p-4">
                <div class="d-grid gap-2">
                    <a href="{{ route('user.layanan') }}" class="btn btn-light text-start py-3 px-3">
                        <i class="bi bi-file-earmark-plus text-success me-2"></i>
                        Layanan Bagian Pemerintahan
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Profile Summary -->
    <div class="col-lg-6">
        <div class="card">
            <div class="card-header bg-transparent border-0 pt-4 pb-0 px-4">
                <h5 class="card-title mb-1">Informasi Akun</h5>
                <p class="text-muted small mb-0">Data profil Anda</p>
            </div>
            <div class="card-body p-4">
                <table class="table table-borderless mb-0">
                    <tr>
                        <td class="text-muted" style="width: 140px;">Nama Lengkap</td>
                        <td>: <strong>{{ $user->name }}</strong></td>
                    </tr>
                    <tr>
                        <td class="text-muted">Jabatan</td>
                        <td>: {{ $user->jabatan ?? '-' }}</td>
                    </tr>
                    <tr>
                        <td class="text-muted">Instansi</td>
                        <td>: {{ $user->instansi ?? '-' }}</td>
                    </tr>
                    <tr>
                        <td class="text-muted">Biro/Bagian</td>
                        <td>: {{ $user->biro_bagian ?? '-' }}</td>
                    </tr>
                    <tr>
                        <td class="text-muted">Email</td>
                        <td>: {{ $user->email }}</td>
                    </tr>
                    <tr>
                        <td class="text-muted">No. WhatsApp</td>
                        <td>: {{ $user->no_whatsapp ?? '-' }}</td>
                    </tr>
                </table>
                <div class="mt-3">
                    <a href="{{ route('user.profile') }}" class="btn btn-sm btn-outline-primary">
                        <i class="bi bi-pencil me-1"></i> Edit Profil
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Recent Activities -->
<div class="row mt-4">
    <div class="col-12">
        <div class="card">
            <div class="card-header bg-transparent border-0 pt-4 pb-0 px-4">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h5 class="card-title mb-1">Pengajuan Terbaru</h5>
                        <p class="text-muted small mb-0">Riwayat pengajuan layanan terakhir</p>
                    </div>
                    <a href="{{ route('user.layanan.riwayat') }}" class="btn btn-sm btn-outline-primary">
                        Lihat Semua
                    </a>
                </div>
            </div>
            <div class="card-body p-4">
                @if(isset($recentPengajuan) && $recentPengajuan->count() > 0)
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead class="table-light">
                            <tr>
                                <th>No. Pengajuan</th>
                                <th>Jenis Layanan</th>
                                <th>Tanggal</th>
                                <th>Status</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($recentPengajuan as $pengajuan)
                            <tr>
                                <td><span class="badge bg-secondary">{{ $pengajuan->nomor_pengajuan }}</span></td>
                                <td>{{ $pengajuan->jenisLayanan->nama ?? '-' }}</td>
                                <td>
                                    <small>{{ $pengajuan->created_at->format('d M Y') }}</small><br>
                                    <small class="text-muted">{{ $pengajuan->created_at->format('H:i') }}</small>
                                </td>
                                <td>
                                    <span class="badge bg-{{ $pengajuan->status_color ?? 'secondary' }}">
                                        {{ $pengajuan->status_label ?? $pengajuan->status }}
                                    </span>
                                </td>
                                <td>
                                    <a href="{{ route('user.layanan.detail', $pengajuan->id) }}" class="btn btn-sm btn-outline-primary">
                                        <i class="bi bi-eye"></i> Detail
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
                    <a href="{{ route('user.layanan') }}" class="btn btn-primary btn-sm">
                        <i class="bi bi-plus-lg me-1"></i> Ajukan Layanan
                    </a>
                </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection

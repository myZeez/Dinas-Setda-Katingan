@extends('admin.layouts.app')

@section('title', 'Daftar Pengajuan Layanan')

@section('content')
<div class="page-header">
    <div class="d-flex justify-content-between align-items-center flex-wrap gap-2">
        <div>
            <h1 class="page-title">Daftar Pengajuan</h1>
            <p class="page-subtitle">Kelola semua pengajuan layanan kerja sama</p>
        </div>
        <a href="{{ route('admin.layanan') }}" class="btn btn-outline-secondary">
            <i class="bi bi-arrow-left me-1"></i> Kembali
        </a>
    </div>
</div>

<!-- Filter & Search -->
<div class="card mb-4">
    <div class="card-body p-4">
        <form method="GET" action="{{ route('admin.layanan.pengajuan') }}">
            <div class="row g-3 align-items-end">
                <div class="col-md-4">
                    <label class="form-label small">Cari</label>
                    <div class="input-group">
                        <span class="input-group-text bg-transparent">
                            <i class="bi bi-search"></i>
                        </span>
                        <input type="text" name="search" class="form-control" placeholder="No. pengajuan, nama pemohon..." value="{{ request('search') }}">
                    </div>
                </div>
                <div class="col-md-3">
                    <label class="form-label small">Jenis Layanan</label>
                    <select name="jenis" class="form-select">
                        <option value="">Semua Jenis</option>
                        @foreach($jenisLayanans as $jenis)
                        <option value="{{ $jenis->kode }}" {{ request('jenis') == $jenis->kode ? 'selected' : '' }}>
                            {{ $jenis->kode }} - {{ $jenis->nama }}
                        </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-3">
                    <label class="form-label small">Status</label>
                    <select name="status" class="form-select">
                        <option value="">Semua Status</option>
                        <option value="menunggu_review_sp" {{ request('status') == 'menunggu_review_sp' ? 'selected' : '' }}>Menunggu Review SP</option>
                        <option value="sp_disetujui" {{ request('status') == 'sp_disetujui' ? 'selected' : '' }}>SP Disetujui</option>
                        <option value="sp_revisi" {{ request('status') == 'sp_revisi' ? 'selected' : '' }}>SP Perlu Revisi</option>
                        <option value="dokumen_lengkap" {{ request('status') == 'dokumen_lengkap' ? 'selected' : '' }}>Dokumen Lengkap</option>
                        <option value="diproses" {{ request('status') == 'diproses' ? 'selected' : '' }}>Diproses</option>
                        <option value="proses_ttd" {{ request('status') == 'proses_ttd' ? 'selected' : '' }}>Proses TTD</option>
                        <option value="penjadwalan_ttd" {{ request('status') == 'penjadwalan_ttd' ? 'selected' : '' }}>Penjadwalan TTD</option>
                        <option value="selesai" {{ request('status') == 'selesai' ? 'selected' : '' }}>Selesai</option>
                        <option value="ditolak" {{ request('status') == 'ditolak' ? 'selected' : '' }}>Ditolak</option>
                    </select>
                </div>
                <div class="col-md-2">
                    <button type="submit" class="btn btn-primary w-100">
                        <i class="bi bi-funnel me-1"></i> Filter
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>

<!-- Table -->
<div class="card">
    <div class="card-body p-0">
        @if($pengajuans->count() > 0)
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="bg-light">
                    <tr>
                        <th class="ps-4">No. Pengajuan</th>
                        <th>Pemohon</th>
                        <th>Jenis</th>
                        <th>Nama Pihak</th>
                        <th>Tanggal</th>
                        <th class="text-center">Status</th>
                        <th class="text-center pe-4">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($pengajuans as $pengajuan)
                    <tr>
                        <td class="ps-4">
                            <span class="fw-semibold">{{ $pengajuan->nomor_pengajuan }}</span>
                        </td>
                        <td>
                            <div>
                                <p class="mb-0 fw-medium">{{ $pengajuan->user->nama }}</p>
                                <small class="text-muted">{{ $pengajuan->user->instansi }}</small>
                            </div>
                        </td>
                        <td>
                            <span class="badge bg-primary bg-opacity-10 text-primary">
                                {{ $pengajuan->jenisLayanan->kode }}
                            </span>
                        </td>
                        <td>{{ Str::limit($pengajuan->nama_pihak, 25) }}</td>
                        <td>
                            <small>{{ $pengajuan->created_at->format('d M Y') }}</small><br>
                            <small class="text-muted">{{ $pengajuan->created_at->format('H:i') }}</small>
                        </td>
                        <td class="text-center">
                            <span class="badge bg-{{ $pengajuan->status_color }}">
                                {{ $pengajuan->status_label }}
                            </span>
                        </td>
                        <td class="text-center pe-4">
                            <a href="{{ route('admin.layanan.detail', $pengajuan->id) }}" class="btn btn-sm btn-primary">
                                <i class="bi bi-eye me-1"></i> Detail
                            </a>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        <div class="d-flex justify-content-between align-items-center p-4 border-top">
            <small class="text-muted">
                Menampilkan {{ $pengajuans->firstItem() }} - {{ $pengajuans->lastItem() }} dari {{ $pengajuans->total() }} data
            </small>
            {{ $pengajuans->withQueryString()->links() }}
        </div>
        @else
        <div class="text-center py-5">
            <i class="bi bi-inbox text-muted" style="font-size: 48px;"></i>
            <p class="text-muted mt-3">Tidak ada pengajuan yang ditemukan</p>
        </div>
        @endif
    </div>
</div>
@endsection

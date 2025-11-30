@extends('user.layouts.app')

@section('title', 'Riwayat Layanan')

@section('content')
<div class="page-header">
    <div class="d-flex justify-content-between align-items-center flex-wrap gap-2">
        <div>
            <h1 class="page-title">Riwayat Layanan</h1>
            <p class="page-subtitle">Daftar semua pengajuan layanan Anda</p>
        </div>
        <a href="{{ route('user.layanan') }}" class="btn btn-primary">
            <i class="bi bi-plus-lg me-1"></i> Ajukan Baru
        </a>
    </div>
</div>

<div class="card">
    <div class="card-header bg-transparent border-0 pt-4 pb-0 px-4">
        <div class="row align-items-center">
            <div class="col-md-6 mb-3 mb-md-0">
                <form method="GET" action="{{ route('user.layanan.riwayat') }}">
                    <div class="input-group">
                        <span class="input-group-text bg-transparent">
                            <i class="bi bi-search"></i>
                        </span>
                        <input type="text" name="search" class="form-control" placeholder="Cari nomor pengajuan..." value="{{ request('search') }}">
                    </div>
                </form>
            </div>
            <div class="col-md-6 text-md-end">
                <form method="GET" action="{{ route('user.layanan.riwayat') }}" class="d-inline">
                    <select name="status" class="form-select" style="width: auto; display: inline-block;" onchange="this.form.submit()">
                        <option value="">Semua Status</option>
                        <option value="diajukan" {{ request('status') == 'diajukan' ? 'selected' : '' }}>Diajukan</option>
                        <option value="diproses" {{ request('status') == 'diproses' ? 'selected' : '' }}>Diproses</option>
                        <option value="koreksi" {{ request('status') == 'koreksi' ? 'selected' : '' }}>Perlu Koreksi</option>
                        <option value="proses_ttd" {{ request('status') == 'proses_ttd' ? 'selected' : '' }}>Proses TTD</option>
                        <option value="selesai" {{ request('status') == 'selesai' ? 'selected' : '' }}>Selesai</option>
                        <option value="ditolak" {{ request('status') == 'ditolak' ? 'selected' : '' }}>Ditolak</option>
                    </select>
                </form>
            </div>
        </div>
    </div>
    <div class="card-body p-4">
        @if($pengajuans->count() > 0)
        <div class="table-responsive">
            <table class="table table-hover align-middle">
                <thead class="bg-light">
                    <tr>
                        <th>No. Pengajuan</th>
                        <th>Layanan</th>
                        <th>Nama Pihak</th>
                        <th>Tanggal</th>
                        <th class="text-center">Status</th>
                        <th class="text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($pengajuans as $pengajuan)
                    <tr>
                        <td>
                            <span class="fw-semibold">{{ $pengajuan->nomor_pengajuan }}</span>
                        </td>
                        <td>
                            <div class="d-flex align-items-center">
                                <div class="rounded-circle bg-success bg-opacity-10 d-flex align-items-center justify-content-center me-2" style="width: 32px; height: 32px;">
                                    <i class="bi {{ $pengajuan->jenisLayanan->icon ?? 'bi-file-earmark-text' }} text-success" style="font-size: 14px;"></i>
                                </div>
                                <span>{{ $pengajuan->jenisLayanan->kode }}</span>
                            </div>
                        </td>
                        <td>{{ Str::limit($pengajuan->nama_pihak, 30) }}</td>
                        <td>
                            <small>{{ $pengajuan->created_at->format('d M Y') }}</small><br>
                            <small class="text-muted">{{ $pengajuan->created_at->format('H:i') }}</small>
                        </td>
                        <td class="text-center">
                            <span class="badge bg-{{ $pengajuan->status_color }}">
                                {{ $pengajuan->status_label }}
                            </span>
                        </td>
                        <td class="text-center">
                            <a href="{{ route('user.layanan.detail', $pengajuan->id) }}" class="btn btn-sm btn-outline-primary">
                                <i class="bi bi-eye me-1"></i> Detail
                            </a>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        <div class="d-flex justify-content-end mt-3">
            {{ $pengajuans->links() }}
        </div>
        @else
        <div class="text-center py-5">
            <i class="bi bi-inbox text-muted" style="font-size: 48px;"></i>
            <p class="text-muted mt-3">Belum ada riwayat pengajuan layanan</p>
            <a href="{{ route('user.layanan') }}" class="btn btn-primary btn-sm">
                <i class="bi bi-plus-lg me-1"></i> Ajukan Layanan
            </a>
        </div>
        @endif
    </div>
</div>
@endsection

@extends('admin.layouts.app')

@section('title', $kategori->nama)

@section('content')
<div class="page-header">
    <div class="d-flex justify-content-between align-items-center flex-wrap gap-2">
        <div>
            <h1 class="page-title">{{ $kategori->nama }}</h1>
            <p class="page-subtitle">{{ $kategori->deskripsi }}</p>
        </div>
        <div class="d-flex gap-2">
            <a href="{{ route('admin.informasi-publik.jenis-dokumen', $kategori->slug) }}" class="btn btn-outline-primary">
                <i class="bi bi-folder me-1"></i> Kelola Jenis Dokumen
            </a>
            <a href="{{ route('admin.informasi-publik.create', $kategori->slug) }}" class="btn btn-primary">
                <i class="bi bi-plus-lg me-1"></i> Tambah Dokumen
            </a>
        </div>
    </div>
</div>

@if(session('success'))
<div class="alert alert-success alert-dismissible fade show" role="alert">
    <i class="bi bi-check-circle me-2"></i>{{ session('success') }}
    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
</div>
@endif

<!-- Statistics -->
<div class="row g-4 mb-4">
    <div class="col-6 col-md-3">
        <div class="card stat-card">
            <div class="card-body">
                <div class="d-flex align-items-center">
                    <div class="stat-icon blue me-3">
                        <i class="bi bi-file-earmark-text"></i>
                    </div>
                    <div>
                        <h3 class="stat-number mb-0">{{ $stats['total'] }}</h3>
                        <p class="stat-label mb-0">Total Dokumen</p>
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
                        <i class="bi bi-check-circle"></i>
                    </div>
                    <div>
                        <h3 class="stat-number mb-0">{{ $stats['berlaku'] }}</h3>
                        <p class="stat-label mb-0">Berlaku</p>
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
                        <i class="bi bi-eye"></i>
                    </div>
                    <div>
                        <h3 class="stat-number mb-0">{{ number_format($stats['views']) }}</h3>
                        <p class="stat-label mb-0">Dilihat</p>
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
                        <i class="bi bi-download"></i>
                    </div>
                    <div>
                        <h3 class="stat-number mb-0">{{ number_format($stats['downloads']) }}</h3>
                        <p class="stat-label mb-0">Diunduh</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Filter & Search -->
<div class="card mb-4">
    <div class="card-body p-4">
        <form method="GET" action="{{ route('admin.informasi-publik.index', $kategori->slug) }}">
            <div class="row g-3 align-items-end">
                <div class="col-md-4">
                    <label class="form-label small">Cari Dokumen</label>
                    <div class="input-group">
                        <span class="input-group-text bg-transparent">
                            <i class="bi bi-search"></i>
                        </span>
                        <input type="text" name="search" class="form-control" placeholder="Cari judul atau nomor..." value="{{ request('search') }}">
                    </div>
                </div>
                <div class="col-md-3">
                    <label class="form-label small">Jenis Dokumen</label>
                    <select name="jenis_dokumen_id" class="form-select">
                        <option value="">Semua Jenis</option>
                        @foreach($jenisDokumens as $jenis)
                        <option value="{{ $jenis->id }}" {{ request('jenis_dokumen_id') == $jenis->id ? 'selected' : '' }}>
                            {{ $jenis->nama }}
                        </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-3">
                    <label class="form-label small">Status</label>
                    <select name="status" class="form-select">
                        <option value="">Semua Status</option>
                        <option value="berlaku" {{ request('status') == 'berlaku' ? 'selected' : '' }}>Berlaku</option>
                        <option value="tidak_berlaku" {{ request('status') == 'tidak_berlaku' ? 'selected' : '' }}>Tidak Berlaku</option>
                        <option value="terealisasi" {{ request('status') == 'terealisasi' ? 'selected' : '' }}>Terealisasi</option>
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
        @if($informasis->count() > 0)
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="bg-light">
                    <tr>
                        <th class="ps-4">No.</th>
                        <th>Dokumen</th>
                        <th>Nomor</th>
                        <th>Tgl/Bln/Tahun</th>
                        <th class="text-center">Keterangan</th>
                        <th class="text-center">Status</th>
                        <th class="text-center pe-4">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($informasis as $index => $info)
                    <tr>
                        <td class="ps-4">{{ $informasis->firstItem() + $index }}.</td>
                        <td>
                            <p class="mb-1 fw-semibold">{{ Str::limit($info->judul, 60) }}</p>
                            @if($info->jenisDokumen)
                            <small class="text-muted">
                                <i class="bi bi-folder me-1"></i>{{ $info->jenisDokumen->nama }}
                            </small>
                            @endif
                        </td>
                        <td>
                            <small>{{ $info->nomor ?? '-' }}</small>
                        </td>
                        <td>
                            <small>{{ $info->formatted_tanggal }}</small>
                        </td>
                        <td class="text-center">
                            <div class="d-flex justify-content-center gap-3">
                                <div class="text-center">
                                    <i class="bi bi-eye text-primary"></i>
                                    <div class="small text-muted">DILIHAT</div>
                                    <div class="small fw-semibold">{{ number_format($info->views) }}</div>
                                </div>
                                <div class="text-center">
                                    <i class="bi bi-download text-success"></i>
                                    <div class="small text-muted">DIUNDUH</div>
                                    <div class="small fw-semibold">{{ number_format($info->downloads) }}</div>
                                </div>
                                @if($info->file_dokumen)
                                <div class="text-center">
                                    <a href="{{ route('admin.informasi-publik.download', [$kategori->slug, $info->id, 'dokumen']) }}" class="text-danger text-decoration-none" title="Download PDF">
                                        <i class="bi bi-file-pdf fs-5"></i>
                                        <div class="small">PDF</div>
                                    </a>
                                </div>
                                @endif
                                @if($info->file_lampiran)
                                <div class="text-center">
                                    <a href="{{ route('admin.informasi-publik.download', [$kategori->slug, $info->id, 'lampiran']) }}" class="text-success text-decoration-none" title="Download Lampiran">
                                        <i class="bi bi-file-image fs-5"></i>
                                        <div class="small">{{ $info->lampiran_label ?? 'LAMPIRAN' }}</div>
                                    </a>
                                </div>
                                @endif
                            </div>
                        </td>
                        <td class="text-center">
                            <span class="badge bg-{{ $info->status_color }}">
                                {{ $info->status_label }}
                            </span>
                        </td>
                        <td class="text-center pe-4">
                            <div class="btn-group btn-group-sm">
                                <a href="{{ route('admin.informasi-publik.show', [$kategori->slug, $info->id]) }}" class="btn btn-outline-info" title="Lihat">
                                    <i class="bi bi-eye"></i>
                                </a>
                                <a href="{{ route('admin.informasi-publik.edit', [$kategori->slug, $info->id]) }}" class="btn btn-outline-primary" title="Edit">
                                    <i class="bi bi-pencil"></i>
                                </a>
                                <button type="button" class="btn btn-outline-danger" title="Hapus"
                                    onclick="confirmDelete('delete-info-{{ $info->id }}', 'Hapus Dokumen?', 'Dokumen \'{{ addslashes($info->judul) }}\' akan dihapus permanen!')">
                                    <i class="bi bi-trash"></i>
                                </button>
                                <form id="delete-info-{{ $info->id }}" action="{{ route('admin.informasi-publik.destroy', [$kategori->slug, $info->id]) }}" method="POST" class="d-none">
                                    @csrf
                                    @method('DELETE')
                                </form>
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        @if($informasis->hasPages())
        <div class="d-flex justify-content-between align-items-center p-4 border-top">
            <small class="text-muted">
                Menampilkan {{ $informasis->firstItem() }} - {{ $informasis->lastItem() }} dari {{ $informasis->total() }} dokumen
            </small>
            {{ $informasis->withQueryString()->links() }}
        </div>
        @endif
        @else
        <div class="text-center py-5">
            <i class="bi bi-file-earmark-text display-1 text-muted"></i>
            <p class="text-muted mt-3">Belum ada dokumen</p>
            <a href="{{ route('admin.informasi-publik.create', $kategori->slug) }}" class="btn btn-primary">
                <i class="bi bi-plus-lg me-1"></i> Tambah Dokumen
            </a>
        </div>
        @endif
    </div>
</div>
@endsection

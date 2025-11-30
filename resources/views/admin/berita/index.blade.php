@extends('admin.layouts.app')

@section('title', 'Kelola Berita')

@section('content')
<div class="page-header">
    <div class="d-flex justify-content-between align-items-center flex-wrap gap-2">
        <div>
            <h1 class="page-title">Kelola Berita</h1>
            <p class="page-subtitle">Kelola berita dan artikel website</p>
        </div>
        <a href="{{ route('admin.berita.create') }}" class="btn btn-primary">
            <i class="bi bi-plus-lg me-1"></i> Tambah Berita
        </a>
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
    <div class="col-md-4">
        <div class="card stat-card">
            <div class="card-body">
                <div class="d-flex align-items-center">
                    <div class="stat-icon blue me-3">
                        <i class="bi bi-newspaper"></i>
                    </div>
                    <div>
                        <h3 class="stat-number mb-0">{{ $stats['total'] }}</h3>
                        <p class="stat-label mb-0">Total Berita</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card stat-card">
            <div class="card-body">
                <div class="d-flex align-items-center">
                    <div class="stat-icon green me-3">
                        <i class="bi bi-check-circle"></i>
                    </div>
                    <div>
                        <h3 class="stat-number mb-0">{{ $stats['published'] }}</h3>
                        <p class="stat-label mb-0">Dipublikasikan</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card stat-card">
            <div class="card-body">
                <div class="d-flex align-items-center">
                    <div class="stat-icon orange me-3">
                        <i class="bi bi-file-earmark"></i>
                    </div>
                    <div>
                        <h3 class="stat-number mb-0">{{ $stats['draft'] }}</h3>
                        <p class="stat-label mb-0">Draft</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Filter & Search -->
<div class="card mb-4">
    <div class="card-body p-4">
        <form method="GET" action="{{ route('admin.berita.index') }}">
            <div class="row g-3 align-items-end">
                <div class="col-md-6">
                    <label class="form-label small">Cari Berita</label>
                    <div class="input-group">
                        <span class="input-group-text bg-transparent">
                            <i class="bi bi-search"></i>
                        </span>
                        <input type="text" name="search" class="form-control" placeholder="Cari judul atau konten..." value="{{ request('search') }}">
                    </div>
                </div>
                <div class="col-md-4">
                    <label class="form-label small">Status</label>
                    <select name="status" class="form-select">
                        <option value="">Semua Status</option>
                        <option value="published" {{ request('status') == 'published' ? 'selected' : '' }}>Dipublikasikan</option>
                        <option value="draft" {{ request('status') == 'draft' ? 'selected' : '' }}>Draft</option>
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
        @if($beritas->count() > 0)
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="bg-light">
                    <tr>
                        <th class="ps-4" style="width: 80px;">Foto</th>
                        <th>Judul</th>
                        <th>Penulis</th>
                        <th>Tanggal</th>
                        <th class="text-center">Status</th>
                        <th class="text-center">Views</th>
                        <th class="text-center pe-4">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($beritas as $berita)
                    <tr>
                        <td class="ps-4">
                            @if($berita->foto)
                            <img src="{{ Storage::url($berita->foto) }}" alt="{{ $berita->judul }}" class="rounded" style="width: 60px; height: 45px; object-fit: cover;">
                            @else
                            <div class="bg-light rounded d-flex align-items-center justify-content-center" style="width: 60px; height: 45px;">
                                <i class="bi bi-image text-muted"></i>
                            </div>
                            @endif
                        </td>
                        <td>
                            <p class="mb-0 fw-semibold">{{ Str::limit($berita->judul, 50) }}</p>
                            <small class="text-muted">{{ Str::limit(strip_tags($berita->konten), 60) }}</small>
                        </td>
                        <td>
                            <small>{{ $berita->user->nama ?? 'Admin' }}</small>
                        </td>
                        <td>
                            <small>{{ $berita->created_at->format('d M Y') }}</small><br>
                            <small class="text-muted">{{ $berita->created_at->format('H:i') }}</small>
                        </td>
                        <td class="text-center">
                            <span class="badge bg-{{ $berita->status_color }}">
                                {{ $berita->status_label }}
                            </span>
                        </td>
                        <td class="text-center">
                            <small>{{ number_format($berita->views) }}</small>
                        </td>
                        <td class="text-center pe-4">
                            <div class="btn-group btn-group-sm">
                                <a href="{{ route('admin.berita.show', $berita) }}" class="btn btn-outline-info" title="Lihat">
                                    <i class="bi bi-eye"></i>
                                </a>
                                <a href="{{ route('admin.berita.edit', $berita) }}" class="btn btn-outline-primary" title="Edit">
                                    <i class="bi bi-pencil"></i>
                                </a>
                                <button type="button" class="btn btn-outline-danger" title="Hapus"
                                    onclick="confirmDelete('delete-berita-{{ $berita->id }}', 'Hapus Berita?', 'Berita \'{{ addslashes($berita->judul) }}\' akan dihapus permanen!')">
                                    <i class="bi bi-trash"></i>
                                </button>
                                <form id="delete-berita-{{ $berita->id }}" action="{{ route('admin.berita.destroy', $berita) }}" method="POST" class="d-none">
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
        <div class="d-flex justify-content-between align-items-center p-4 border-top">
            <small class="text-muted">
                Menampilkan {{ $beritas->firstItem() ?? 0 }} - {{ $beritas->lastItem() ?? 0 }} dari {{ $beritas->total() }} data
            </small>
            {{ $beritas->withQueryString()->links() }}
        </div>
        @else
        <div class="text-center py-5">
            <i class="bi bi-newspaper text-muted" style="font-size: 48px;"></i>
            <p class="text-muted mt-3">Belum ada berita</p>
            <a href="{{ route('admin.berita.create') }}" class="btn btn-primary btn-sm">
                <i class="bi bi-plus-lg me-1"></i> Tambah Berita Pertama
            </a>
        </div>
        @endif
    </div>
</div>
@endsection

@push('styles')
<style>
    .stat-icon.orange {
        background: rgba(249, 115, 22, 0.1);
        color: #f97316;
    }
</style>
@endpush

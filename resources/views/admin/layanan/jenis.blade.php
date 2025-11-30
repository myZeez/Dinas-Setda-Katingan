@extends('admin.layouts.app')

@section('title', 'Kelola Jenis Layanan')

@section('content')
<div class="page-header">
    <div class="d-flex justify-content-between align-items-center flex-wrap gap-2">
        <div>
            <h1 class="page-title">Jenis Layanan</h1>
            <p class="page-subtitle">Kelola jenis layanan kerja sama</p>
        </div>
        <a href="{{ route('admin.layanan') }}" class="btn btn-outline-secondary">
            <i class="bi bi-arrow-left me-1"></i> Kembali
        </a>
    </div>
</div>

@if(session('success'))
<div class="alert alert-success alert-dismissible fade show" role="alert">
    <i class="bi bi-check-circle me-2"></i>{{ session('success') }}
    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
</div>
@endif

<div class="row g-4">
    @foreach($jenisLayanans as $jenis)
    <div class="col-md-6 col-lg-4">
        <div class="card h-100">
            <div class="card-body p-4">
                <div class="d-flex align-items-start justify-content-between mb-3">
                    <div class="rounded-circle bg-primary bg-opacity-10 d-flex align-items-center justify-content-center" style="width: 56px; height: 56px;">
                        <i class="bi {{ $jenis->icon ?? 'bi-file-earmark-text' }} text-primary" style="font-size: 28px;"></i>
                    </div>
                    <span class="badge {{ $jenis->is_active ? 'bg-success' : 'bg-secondary' }}">
                        {{ $jenis->is_active ? 'Aktif' : 'Nonaktif' }}
                    </span>
                </div>
                <h5 class="card-title mb-1">{{ $jenis->nama }}</h5>
                <p class="text-muted small mb-2">Kode: <span class="fw-semibold">{{ $jenis->kode }}</span></p>
                <p class="text-muted small mb-3">{{ Str::limit($jenis->deskripsi, 100) }}</p>

                <div class="d-flex justify-content-between align-items-center pt-3 border-top">
                    <small class="text-muted">
                        <i class="bi bi-folder me-1"></i> {{ $jenis->pengajuans_count }} pengajuan
                    </small>
                    <button type="button" class="btn btn-sm btn-outline-primary" data-bs-toggle="modal" data-bs-target="#editModal{{ $jenis->id }}">
                        <i class="bi bi-pencil me-1"></i> Edit
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Edit Modal -->
    <div class="modal fade" id="editModal{{ $jenis->id }}" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Edit Jenis Layanan</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <form action="{{ route('admin.layanan.update-jenis', $jenis->id) }}" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="modal-body">
                        <div class="mb-3">
                            <label class="form-label">Kode</label>
                            <input type="text" class="form-control" value="{{ $jenis->kode }}" disabled>
                            <small class="text-muted">Kode tidak dapat diubah</small>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Nama Layanan</label>
                            <input type="text" name="nama" class="form-control" value="{{ $jenis->nama }}" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Deskripsi</label>
                            <textarea name="deskripsi" class="form-control" rows="3">{{ $jenis->deskripsi }}</textarea>
                        </div>
                        <div class="mb-3">
                            <div class="form-check form-switch">
                                <input class="form-check-input" type="checkbox" name="is_active" value="1" {{ $jenis->is_active ? 'checked' : '' }}>
                                <label class="form-check-label">Layanan Aktif</label>
                            </div>
                            <small class="text-muted">Jika nonaktif, layanan tidak akan ditampilkan kepada user</small>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-primary">Simpan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    @endforeach
</div>
@endsection

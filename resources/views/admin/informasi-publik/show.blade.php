@extends('admin.layouts.app')

@section('title', 'Detail Dokumen')

@section('content')
<div class="page-header">
    <div class="d-flex justify-content-between align-items-center flex-wrap gap-2">
        <div>
            <h1 class="page-title">Detail Dokumen</h1>
            <p class="page-subtitle">{{ $kategori->nama }}</p>
        </div>
        <div class="d-flex gap-2">
            <a href="{{ route('admin.informasi-publik.edit', [$kategori->slug, $informasi->id]) }}" class="btn btn-primary">
                <i class="bi bi-pencil me-1"></i> Edit
            </a>
            <a href="{{ route('admin.informasi-publik.index', $kategori->slug) }}" class="btn btn-outline-secondary">
                <i class="bi bi-arrow-left me-1"></i> Kembali
            </a>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-lg-8">
        <div class="card mb-4">
            <div class="card-body p-4">
                <div class="d-flex justify-content-between align-items-start mb-4">
                    <div>
                        <span class="badge bg-{{ $informasi->status_color }} mb-2">{{ $informasi->status_label }}</span>
                        @if($informasi->jenisDokumen)
                        <span class="badge bg-secondary mb-2">{{ $informasi->jenisDokumen->nama }}</span>
                        @endif
                    </div>
                    @if(!$informasi->is_active)
                    <span class="badge bg-danger">Nonaktif</span>
                    @endif
                </div>

                <h4 class="fw-bold mb-3">{{ $informasi->judul }}</h4>

                <table class="table table-borderless">
                    <tr>
                        <td class="ps-0" style="width: 150px;"><strong>Nomor Dokumen</strong></td>
                        <td>: {{ $informasi->nomor ?? '-' }}</td>
                    </tr>
                    <tr>
                        <td class="ps-0"><strong>Tanggal</strong></td>
                        <td>: {{ $informasi->formatted_tanggal }}</td>
                    </tr>
                    @if($informasi->keterangan)
                    <tr>
                        <td class="ps-0"><strong>Keterangan</strong></td>
                        <td>: {{ $informasi->keterangan }}</td>
                    </tr>
                    @endif
                    <tr>
                        <td class="ps-0"><strong>Dibuat oleh</strong></td>
                        <td>: {{ $informasi->user->nama ?? 'Admin' }}</td>
                    </tr>
                    <tr>
                        <td class="ps-0"><strong>Tanggal Upload</strong></td>
                        <td>: {{ $informasi->created_at->format('d M Y H:i') }}</td>
                    </tr>
                </table>
            </div>
        </div>

        <!-- File Downloads -->
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0"><i class="bi bi-file-earmark me-2"></i>File Dokumen</h5>
            </div>
            <div class="card-body">
                <div class="row g-3">
                    @if($informasi->file_dokumen)
                    <div class="col-md-6">
                        <div class="border rounded p-3 text-center">
                            <i class="bi bi-file-pdf text-danger" style="font-size: 3rem;"></i>
                            <p class="mb-2 fw-semibold">Dokumen PDF</p>
                            <a href="{{ route('admin.informasi-publik.download', [$kategori->slug, $informasi->id, 'dokumen']) }}" class="btn btn-danger btn-sm">
                                <i class="bi bi-download me-1"></i> Download
                            </a>
                        </div>
                    </div>
                    @endif

                    @if($informasi->file_lampiran)
                    <div class="col-md-6">
                        <div class="border rounded p-3 text-center">
                            <i class="bi bi-file-image text-success" style="font-size: 3rem;"></i>
                            <p class="mb-2 fw-semibold">{{ $informasi->lampiran_label ?? 'Lampiran' }}</p>
                            <a href="{{ route('admin.informasi-publik.download', [$kategori->slug, $informasi->id, 'lampiran']) }}" class="btn btn-success btn-sm">
                                <i class="bi bi-download me-1"></i> Download
                            </a>
                        </div>
                    </div>
                    @endif

                    @if(!$informasi->file_dokumen && !$informasi->file_lampiran)
                    <div class="col-12 text-center py-4">
                        <i class="bi bi-file-earmark-x display-4 text-muted"></i>
                        <p class="text-muted mt-2">Tidak ada file yang diupload</p>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <div class="col-lg-4">
        <!-- Statistics -->
        <div class="card mb-4">
            <div class="card-header">
                <h6 class="mb-0"><i class="bi bi-bar-chart me-2"></i>Statistik</h6>
            </div>
            <div class="card-body">
                <div class="d-flex justify-content-around text-center">
                    <div>
                        <i class="bi bi-eye text-primary fs-3"></i>
                        <h4 class="mb-0 mt-2">{{ number_format($informasi->views) }}</h4>
                        <small class="text-muted">Dilihat</small>
                    </div>
                    <div>
                        <i class="bi bi-download text-success fs-3"></i>
                        <h4 class="mb-0 mt-2">{{ number_format($informasi->downloads) }}</h4>
                        <small class="text-muted">Diunduh</small>
                    </div>
                </div>
            </div>
        </div>

        <!-- Actions -->
        <div class="card">
            <div class="card-header">
                <h6 class="mb-0"><i class="bi bi-gear me-2"></i>Aksi</h6>
            </div>
            <div class="card-body">
                <div class="d-grid gap-2">
                    <a href="{{ route('admin.informasi-publik.edit', [$kategori->slug, $informasi->id]) }}" class="btn btn-primary">
                        <i class="bi bi-pencil me-1"></i> Edit Dokumen
                    </a>
                    <button type="button" class="btn btn-danger"
                        onclick="confirmDelete('delete-dokumen-form', 'Hapus Dokumen?', 'Dokumen ini akan dihapus permanen!')">
                        <i class="bi bi-trash me-1"></i> Hapus Dokumen
                    </button>
                    <form id="delete-dokumen-form" action="{{ route('admin.informasi-publik.destroy', [$kategori->slug, $informasi->id]) }}" method="POST" class="d-none">
                        @csrf
                        @method('DELETE')
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

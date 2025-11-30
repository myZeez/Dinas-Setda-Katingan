@extends('admin.layouts.app')

@section('title', 'Preview Berita')

@section('content')
<div class="page-header">
    <div class="d-flex justify-content-between align-items-center flex-wrap gap-2">
        <div>
            <h1 class="page-title">Preview Berita</h1>
            <p class="page-subtitle">Lihat tampilan berita sebelum dipublikasikan</p>
        </div>
        <div class="d-flex gap-2">
            <a href="{{ route('admin.berita.edit', $berita) }}" class="btn btn-primary">
                <i class="bi bi-pencil me-1"></i> Edit
            </a>
            <a href="{{ route('admin.berita.index') }}" class="btn btn-outline-secondary">
                <i class="bi bi-arrow-left me-1"></i> Kembali
            </a>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-lg-8">
        <!-- Article Preview -->
        <div class="card">
            <div class="card-body p-0">
                @if($berita->foto)
                <img src="{{ Storage::url($berita->foto) }}" alt="{{ $berita->judul }}" class="img-fluid w-100" style="max-height: 400px; object-fit: cover;">
                @if($berita->foto_caption)
                <p class="text-muted small px-4 pt-2 mb-0">{{ $berita->foto_caption }}</p>
                @endif
                @endif

                <div class="p-4">
                    <span class="badge bg-{{ $berita->status_color }} mb-3">
                        {{ $berita->status_label }}
                    </span>

                    <h2 class="mb-3">{{ $berita->judul }}</h2>

                    <div class="d-flex align-items-center text-muted small mb-4 pb-3 border-bottom">
                        <div class="d-flex align-items-center me-4">
                            <i class="bi bi-person-circle me-2"></i>
                            <span>{{ $berita->user->nama ?? 'Admin' }}</span>
                        </div>
                        <div class="d-flex align-items-center me-4">
                            <i class="bi bi-calendar3 me-2"></i>
                            <span>{{ $berita->formatted_date }}</span>
                        </div>
                        <div class="d-flex align-items-center">
                            <i class="bi bi-eye me-2"></i>
                            <span>{{ number_format($berita->views) }} kali dilihat</span>
                        </div>
                    </div>

                    @if($berita->ringkasan)
                    <div class="alert alert-light border mb-4">
                        <strong>Ringkasan:</strong><br>
                        {{ $berita->ringkasan }}
                    </div>
                    @endif

                    <div class="article-content">
                        {!! $berita->konten !!}
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-lg-4">
        <!-- Info Card -->
        <div class="card">
            <div class="card-header bg-transparent border-0 pt-4 pb-0 px-4">
                <h6 class="card-title mb-1">Informasi Berita</h6>
            </div>
            <div class="card-body p-4">
                <table class="table table-sm table-borderless mb-0">
                    <tr>
                        <td class="text-muted ps-0" style="width: 120px;">Status</td>
                        <td>: <span class="badge bg-{{ $berita->status_color }}">{{ $berita->status_label }}</span></td>
                    </tr>
                    <tr>
                        <td class="text-muted ps-0">Penulis</td>
                        <td>: {{ $berita->user->nama ?? 'Admin' }}</td>
                    </tr>
                    <tr>
                        <td class="text-muted ps-0">Dibuat</td>
                        <td>: {{ $berita->created_at->format('d M Y, H:i') }}</td>
                    </tr>
                    <tr>
                        <td class="text-muted ps-0">Diperbarui</td>
                        <td>: {{ $berita->updated_at->format('d M Y, H:i') }}</td>
                    </tr>
                    @if($berita->published_at)
                    <tr>
                        <td class="text-muted ps-0">Dipublikasikan</td>
                        <td>: {{ $berita->published_at->format('d M Y, H:i') }}</td>
                    </tr>
                    @endif
                    <tr>
                        <td class="text-muted ps-0">Slug URL</td>
                        <td>: <code class="small">{{ $berita->slug }}</code></td>
                    </tr>
                    <tr>
                        <td class="text-muted ps-0">Views</td>
                        <td>: {{ number_format($berita->views) }}</td>
                    </tr>
                </table>
            </div>
        </div>

        <!-- Actions Card -->
        <div class="card mt-4">
            <div class="card-header bg-transparent border-0 pt-4 pb-0 px-4">
                <h6 class="card-title mb-1">Aksi</h6>
            </div>
            <div class="card-body p-4">
                <div class="d-grid gap-2">
                    <a href="{{ route('admin.berita.edit', $berita) }}" class="btn btn-primary">
                        <i class="bi bi-pencil me-1"></i> Edit Berita
                    </a>
                    <button type="button" class="btn btn-outline-danger"
                        onclick="confirmDelete('delete-berita-form', 'Hapus Berita?', 'Berita ini akan dihapus permanen!')">
                        <i class="bi bi-trash me-1"></i> Hapus Berita
                    </button>
                    <form id="delete-berita-form" action="{{ route('admin.berita.destroy', $berita) }}" method="POST" class="d-none">
                        @csrf
                        @method('DELETE')
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
    .article-content {
        font-size: 16px;
        line-height: 1.8;
        color: #374151;
    }
    .article-content h1,
    .article-content h2,
    .article-content h3,
    .article-content h4,
    .article-content h5,
    .article-content h6 {
        margin-top: 1.5em;
        margin-bottom: 0.75em;
        font-weight: 600;
        color: #111827;
    }
    .article-content p {
        margin-bottom: 1em;
    }
    .article-content img {
        max-width: 100%;
        height: auto;
        border-radius: 8px;
        margin: 1em 0;
    }
    .article-content blockquote {
        border-left: 4px solid #2563eb;
        padding-left: 1em;
        margin: 1.5em 0;
        color: #6b7280;
        font-style: italic;
    }
    .article-content ul,
    .article-content ol {
        margin-bottom: 1em;
        padding-left: 1.5em;
    }
    .article-content li {
        margin-bottom: 0.5em;
    }
    .article-content a {
        color: #2563eb;
    }
    .article-content a:hover {
        text-decoration: underline;
    }
</style>
@endpush

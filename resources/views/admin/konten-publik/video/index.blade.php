@extends('admin.layouts.app')

@section('title', 'Kelola Video YouTube')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h1 class="h3 mb-1">Kelola Video YouTube</h1>
        <p class="text-muted mb-0">Atur video yang ditampilkan di halaman utama</p>
    </div>
    <a href="{{ route('admin.konten-publik.video.create') }}" class="btn btn-primary">
        <i class="bi bi-plus-lg me-1"></i> Tambah Video
    </a>
</div>

@if(session('success'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        <i class="bi bi-check-circle me-2"></i>{{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
@endif

<div class="card border-0 shadow-sm">
    <div class="card-body">
        @if($videos->count() > 0)
            <div class="row g-4">
                @foreach($videos as $video)
                    <div class="col-md-6 col-lg-4">
                        <div class="video-card {{ !$video->is_active ? 'inactive' : '' }}">
                            <div class="video-thumbnail" onclick="playVideo('{{ $video->youtube_id }}', '{{ $video->judul }}')">
                                <img src="{{ $video->thumbnail_url }}" alt="{{ $video->judul }}">
                                <div class="play-overlay">
                                    <i class="bi bi-play-circle-fill"></i>
                                </div>
                                @if(!$video->is_active)
                                    <span class="status-badge">Nonaktif</span>
                                @endif
                            </div>
                            <div class="video-info">
                                <h6 class="video-title">{{ $video->judul }}</h6>
                                @if($video->deskripsi)
                                    <p class="video-desc">{{ Str::limit($video->deskripsi, 60) }}</p>
                                @endif
                                <div class="video-actions">
                                    <a href="{{ route('admin.konten-publik.video.edit', $video->id) }}"
                                       class="btn btn-sm btn-outline-primary">
                                        <i class="bi bi-pencil"></i> Edit
                                    </a>
                                    <button type="button" class="btn btn-sm btn-outline-danger"
                                            onclick="confirmDelete({{ $video->id }})">
                                        <i class="bi bi-trash"></i> Hapus
                                    </button>
                                </div>
                            </div>
                            <form id="delete-form-{{ $video->id }}"
                                  action="{{ route('admin.konten-publik.video.destroy', $video->id) }}"
                                  method="POST" class="d-none">
                                @csrf
                                @method('DELETE')
                            </form>
                        </div>
                    </div>
                @endforeach
            </div>
        @else
            <div class="text-center py-5">
                <i class="bi bi-youtube text-muted" style="font-size: 64px;"></i>
                <h5 class="mt-3">Belum Ada Video</h5>
                <p class="text-muted">Tambahkan video YouTube untuk ditampilkan</p>
                <a href="{{ route('admin.konten-publik.video.create') }}" class="btn btn-primary">
                    <i class="bi bi-plus-lg me-1"></i> Tambah Video Pertama
                </a>
            </div>
        @endif
    </div>
</div>

<!-- Video Modal -->
<div class="modal fade" id="videoModal" tabindex="-1">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content bg-dark">
            <div class="modal-header border-0">
                <h5 class="modal-title text-white" id="videoModalTitle">Video</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body p-0">
                <div class="ratio ratio-16x9">
                    <iframe id="videoFrame" src="" allowfullscreen></iframe>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    .video-card {
        background: white;
        border-radius: 12px;
        overflow: hidden;
        box-shadow: 0 2px 10px rgba(0,0,0,0.08);
        transition: all 0.3s ease;
    }

    .video-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 10px 30px rgba(0,0,0,0.15);
    }

    .video-card.inactive {
        opacity: 0.7;
    }

    .video-thumbnail {
        position: relative;
        cursor: pointer;
        overflow: hidden;
    }

    .video-thumbnail img {
        width: 100%;
        aspect-ratio: 16/9;
        object-fit: cover;
        transition: transform 0.3s ease;
    }

    .video-card:hover .video-thumbnail img {
        transform: scale(1.05);
    }

    .play-overlay {
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: rgba(0,0,0,0.3);
        display: flex;
        align-items: center;
        justify-content: center;
        transition: background 0.3s ease;
    }

    .play-overlay i {
        font-size: 50px;
        color: white;
        opacity: 0.9;
        transition: transform 0.3s ease;
    }

    .video-card:hover .play-overlay {
        background: rgba(0,0,0,0.5);
    }

    .video-card:hover .play-overlay i {
        transform: scale(1.1);
    }

    .status-badge {
        position: absolute;
        top: 10px;
        right: 10px;
        background: rgba(0,0,0,0.7);
        color: white;
        padding: 4px 10px;
        border-radius: 20px;
        font-size: 12px;
    }

    .video-info {
        padding: 15px;
    }

    .video-title {
        font-weight: 600;
        margin-bottom: 5px;
        display: -webkit-box;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;
        overflow: hidden;
    }

    .video-desc {
        font-size: 13px;
        color: #666;
        margin-bottom: 12px;
    }

    .video-actions {
        display: flex;
        gap: 8px;
    }
</style>
@endsection

@push('scripts')
<script>
    function confirmDelete(id) {
        Swal.fire({
            title: 'Hapus Video?',
            text: 'Video ini akan dihapus permanen!',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#ef4444',
            cancelButtonColor: '#64748b',
            confirmButtonText: '<i class="bi bi-trash me-1"></i> Ya, Hapus!',
            cancelButtonText: 'Batal',
            reverseButtons: true
        }).then((result) => {
            if (result.isConfirmed) {
                document.getElementById('delete-form-' + id).submit();
            }
        });
    }

    function playVideo(youtubeId, title) {
        document.getElementById('videoModalTitle').textContent = title;
        document.getElementById('videoFrame').src = 'https://www.youtube.com/embed/' + youtubeId + '?autoplay=1';
        new bootstrap.Modal(document.getElementById('videoModal')).show();
    }

    document.getElementById('videoModal').addEventListener('hidden.bs.modal', function() {
        document.getElementById('videoFrame').src = '';
    });
</script>
@endpush

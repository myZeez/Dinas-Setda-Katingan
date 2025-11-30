@extends('admin.layouts.app')

@section('title', 'Edit Video YouTube')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h1 class="h3 mb-1">Edit Video YouTube</h1>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb mb-0">
                <li class="breadcrumb-item"><a href="{{ route('admin.konten-publik.video') }}">Video</a></li>
                <li class="breadcrumb-item active">Edit</li>
            </ol>
        </nav>
    </div>
</div>

<div class="row">
    <div class="col-lg-8">
        <div class="card border-0 shadow-sm">
            <div class="card-body">
                <form action="{{ route('admin.konten-publik.video.update', $video->id) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')

                    <div class="mb-3">
                        <label class="form-label">Judul Video <span class="text-danger">*</span></label>
                        <input type="text" name="judul" class="form-control @error('judul') is-invalid @enderror"
                               value="{{ old('judul', $video->judul) }}" placeholder="Masukkan judul video" required>
                        @error('judul')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label class="form-label">URL YouTube <span class="text-danger">*</span></label>
                        <input type="text" name="youtube_url" id="youtubeUrl"
                               class="form-control @error('youtube_url') is-invalid @enderror"
                               value="{{ old('youtube_url', 'https://www.youtube.com/watch?v=' . $video->youtube_id) }}"
                               placeholder="https://www.youtube.com/watch?v=xxxxx" required>
                        @error('youtube_url')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Preview -->
                    <div class="mb-3" id="videoPreview">
                        <label class="form-label">Preview Video</label>
                        <div class="ratio ratio-16x9 rounded overflow-hidden">
                            <iframe id="previewFrame" src="https://www.youtube.com/embed/{{ $video->youtube_id }}" allowfullscreen></iframe>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Deskripsi</label>
                        <textarea name="deskripsi" class="form-control @error('deskripsi') is-invalid @enderror"
                                  rows="3" placeholder="Deskripsi singkat tentang video (opsional)">{{ old('deskripsi', $video->deskripsi) }}</textarea>
                        @error('deskripsi')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Thumbnail Kustom</label>

                        @if($video->thumbnail)
                            <div class="mb-2">
                                <img src="{{ asset('storage/' . $video->thumbnail) }}" alt="Thumbnail"
                                     class="rounded" style="max-height: 100px;">
                                <p class="small text-muted mt-1 mb-0">Thumbnail kustom saat ini</p>
                            </div>
                        @endif

                        <input type="file" name="thumbnail" class="form-control @error('thumbnail') is-invalid @enderror"
                               accept="image/*">
                        <small class="text-muted">Kosongkan jika tidak ingin mengubah. Thumbnail YouTube digunakan jika tidak ada kustom.</small>
                        @error('thumbnail')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">Urutan</label>
                                <input type="number" name="urutan" class="form-control @error('urutan') is-invalid @enderror"
                                       value="{{ old('urutan', $video->urutan) }}" min="0">
                                @error('urutan')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">Status</label>
                                <div class="form-check form-switch mt-2">
                                    <input class="form-check-input" type="checkbox" name="is_active" id="is_active"
                                           {{ old('is_active', $video->is_active) ? 'checked' : '' }}>
                                    <label class="form-check-label" for="is_active">Aktif</label>
                                </div>
                            </div>
                        </div>
                    </div>

                    <hr>

                    <div class="d-flex justify-content-end gap-2">
                        <a href="{{ route('admin.konten-publik.video') }}" class="btn btn-outline-secondary">Batal</a>
                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-save me-1"></i> Simpan Perubahan
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="col-lg-4">
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-light">
                <h6 class="mb-0"><i class="bi bi-info-circle me-1"></i> Informasi</h6>
            </div>
            <div class="card-body">
                <table class="table table-sm">
                    <tr>
                        <td class="text-muted">YouTube ID</td>
                        <td><code>{{ $video->youtube_id }}</code></td>
                    </tr>
                    <tr>
                        <td class="text-muted">Dibuat</td>
                        <td>{{ $video->created_at->format('d M Y H:i') }}</td>
                    </tr>
                    <tr>
                        <td class="text-muted">Diperbarui</td>
                        <td>{{ $video->updated_at->format('d M Y H:i') }}</td>
                    </tr>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    document.getElementById('youtubeUrl').addEventListener('input', function(e) {
        const url = e.target.value;
        const youtubeId = extractYoutubeId(url);

        if (youtubeId) {
            document.getElementById('previewFrame').src = 'https://www.youtube.com/embed/' + youtubeId;
        }
    });

    function extractYoutubeId(url) {
        const patterns = [
            /(?:youtube\.com\/watch\?v=|youtu\.be\/|youtube\.com\/embed\/)([a-zA-Z0-9_-]{11})/,
            /^([a-zA-Z0-9_-]{11})$/
        ];

        for (const pattern of patterns) {
            const match = url.match(pattern);
            if (match) return match[1];
        }

        return null;
    }
</script>
@endpush

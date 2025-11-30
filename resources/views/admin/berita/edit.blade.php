@extends('admin.layouts.app')

@section('title', 'Edit Berita')

@section('content')
<div class="page-header">
    <div class="d-flex justify-content-between align-items-center flex-wrap gap-2">
        <div>
            <h1 class="page-title">Edit Berita</h1>
            <p class="page-subtitle">Perbarui berita atau artikel</p>
        </div>
        <a href="{{ route('admin.berita.index') }}" class="btn btn-outline-secondary">
            <i class="bi bi-arrow-left me-1"></i> Kembali
        </a>
    </div>
</div>

<form action="{{ route('admin.berita.update', $berita) }}" method="POST" enctype="multipart/form-data">
    @csrf
    @method('PUT')
    <div class="row">
        <div class="col-lg-8">
            <!-- Main Content -->
            <div class="card mb-4">
                <div class="card-header bg-transparent border-0 pt-4 pb-0 px-4">
                    <h5 class="card-title mb-1">Konten Berita</h5>
                </div>
                <div class="card-body p-4">
                    @if($errors->any())
                    <div class="alert alert-danger">
                        <ul class="mb-0">
                            @foreach($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                    @endif

                    <div class="mb-4">
                        <label class="form-label fw-semibold">Judul Berita <span class="text-danger">*</span></label>
                        <input type="text" name="judul" class="form-control form-control-lg" placeholder="Masukkan judul berita" value="{{ old('judul', $berita->judul) }}" required>
                    </div>

                    <div class="mb-4">
                        <label class="form-label fw-semibold">Ringkasan <small class="text-muted fw-normal">(Opsional)</small></label>
                        <textarea name="ringkasan" class="form-control" rows="3" placeholder="Tulis ringkasan singkat berita (maks 1000 karakter)" maxlength="1000">{{ old('ringkasan', $berita->ringkasan) }}</textarea>
                        <small class="text-muted">Jika dikosongkan, ringkasan akan diambil dari paragraf pertama konten</small>
                    </div>

                    <div class="mb-4">
                        <label class="form-label fw-semibold">Konten Berita <span class="text-danger">*</span></label>
                        <div id="editor-container">
                            <div id="editor">{!! old('konten', $berita->konten) !!}</div>
                        </div>
                        <input type="hidden" name="konten" id="konten-input" value="{{ old('konten', $berita->konten) }}">
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-4">
            <!-- Publish Options -->
            <div class="card mb-4">
                <div class="card-header bg-transparent border-0 pt-4 pb-0 px-4">
                    <h6 class="card-title mb-1">Publikasi</h6>
                </div>
                <div class="card-body p-4">
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Status</label>
                        <select name="status" class="form-select">
                            <option value="draft" {{ old('status', $berita->status) == 'draft' ? 'selected' : '' }}>Draft</option>
                            <option value="published" {{ old('status', $berita->status) == 'published' ? 'selected' : '' }}>Publikasikan</option>
                        </select>
                    </div>

                    <div class="mb-3">
                        <label class="form-label text-muted small">Penulis</label>
                        <p class="mb-0 fw-semibold">{{ $berita->user->nama ?? 'Admin' }}</p>
                    </div>

                    <div class="mb-3">
                        <label class="form-label text-muted small">Dibuat</label>
                        <p class="mb-0">{{ $berita->created_at->format('d F Y, H:i') }}</p>
                    </div>

                    @if($berita->published_at)
                    <div class="mb-3">
                        <label class="form-label text-muted small">Dipublikasikan</label>
                        <p class="mb-0">{{ $berita->published_at->format('d F Y, H:i') }}</p>
                    </div>
                    @endif

                    <hr>

                    <div class="d-grid gap-2">
                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-check-lg me-1"></i> Simpan Perubahan
                        </button>
                        <a href="{{ route('admin.berita.show', $berita) }}" class="btn btn-outline-secondary">
                            <i class="bi bi-eye me-1"></i> Lihat Preview
                        </a>
                    </div>
                </div>
            </div>

            <!-- Featured Image -->
            <div class="card">
                <div class="card-header bg-transparent border-0 pt-4 pb-0 px-4">
                    <h6 class="card-title mb-1">Foto Utama</h6>
                </div>
                <div class="card-body p-4">
                    <div class="mb-3">
                        <div class="upload-preview mb-3" id="preview-container" style="{{ $berita->foto ? '' : 'display: none;' }}">
                            <img src="{{ $berita->foto ? Storage::url($berita->foto) : '' }}" alt="Preview" id="preview-image" class="img-fluid rounded">
                            <button type="button" class="btn btn-sm btn-danger position-absolute top-0 end-0 m-2" onclick="removeImage()">
                                <i class="bi bi-x"></i>
                            </button>
                        </div>
                        <div class="upload-box p-4 border-2 border-dashed rounded text-center" id="upload-box" style="{{ $berita->foto ? 'display: none;' : '' }}">
                            <input type="file" name="foto" id="foto" class="d-none" accept="image/*">
                            <label for="foto" class="cursor-pointer d-block">
                                <i class="bi bi-image text-muted" style="font-size: 48px;"></i>
                                <p class="mb-1 mt-2">Klik untuk upload gambar</p>
                                <small class="text-muted">JPG, PNG, WEBP (Maks. 2MB)</small>
                            </label>
                        </div>
                    </div>

                    <div class="mb-0">
                        <label class="form-label small">Caption Foto <small class="text-muted">(Opsional)</small></label>
                        <input type="text" name="foto_caption" class="form-control form-control-sm" placeholder="Keterangan foto" value="{{ old('foto_caption', $berita->foto_caption) }}">
                    </div>
                </div>
            </div>
        </div>
    </div>
</form>
@endsection

@push('styles')
<!-- Quill Editor CSS -->
<link href="https://cdn.quilljs.com/1.3.7/quill.snow.css" rel="stylesheet">
<style>
    .border-dashed {
        border-style: dashed !important;
    }
    .upload-box {
        background: #f8fafc;
        transition: all 0.2s ease;
        cursor: pointer;
    }
    .upload-box:hover {
        background: #f1f5f9;
        border-color: #2563eb !important;
    }
    .upload-preview {
        position: relative;
    }
    .upload-preview img {
        width: 100%;
        height: auto;
        max-height: 200px;
        object-fit: cover;
    }
    .cursor-pointer {
        cursor: pointer;
    }

    /* Quill Editor Styles */
    #editor-container {
        background: #fff;
        border-radius: 8px;
    }
    #editor {
        min-height: 400px;
        font-size: 15px;
        line-height: 1.7;
    }
    .ql-toolbar {
        border-top-left-radius: 8px;
        border-top-right-radius: 8px;
        border-color: #e5e7eb !important;
        background: #f8fafc;
    }
    .ql-container {
        border-bottom-left-radius: 8px;
        border-bottom-right-radius: 8px;
        border-color: #e5e7eb !important;
    }
    .ql-editor {
        padding: 20px;
    }
</style>
@endpush

@push('scripts')
<!-- Quill Editor JS -->
<script src="https://cdn.quilljs.com/1.3.7/quill.min.js"></script>
<script>
    // Initialize Quill Editor
    var quill = new Quill('#editor', {
        theme: 'snow',
        placeholder: 'Tulis konten berita di sini...',
        modules: {
            toolbar: [
                [{ 'header': [1, 2, 3, 4, 5, 6, false] }],
                [{ 'font': [] }],
                ['bold', 'italic', 'underline', 'strike'],
                [{ 'color': [] }, { 'background': [] }],
                [{ 'list': 'ordered'}, { 'list': 'bullet' }],
                [{ 'indent': '-1'}, { 'indent': '+1' }],
                [{ 'align': [] }],
                ['blockquote', 'code-block'],
                ['link', 'image', 'video'],
                ['clean']
            ]
        }
    });

    // On form submit, copy editor content to hidden input
    document.querySelector('form').addEventListener('submit', function(e) {
        // Get content from Quill
        var content = quill.root.innerHTML;

        // Check if content is empty (Quill default empty state)
        if (content === '<p><br></p>' || content.trim() === '') {
            content = '';
        }

        document.getElementById('konten-input').value = content;

        // Debug: Check the value
        console.log('Konten yang dikirim:', content);
    });

    // Also update on text change for safety
    quill.on('text-change', function() {
        var content = quill.root.innerHTML;
        if (content === '<p><br></p>' || content.trim() === '') {
            content = '';
        }
        document.getElementById('konten-input').value = content;
    });

    // Image Preview
    document.getElementById('foto').addEventListener('change', function(e) {
        const file = e.target.files[0];
        if (file) {
            const reader = new FileReader();
            reader.onload = function(e) {
                document.getElementById('preview-image').src = e.target.result;
                document.getElementById('preview-container').style.display = 'block';
                document.getElementById('upload-box').style.display = 'none';
            }
            reader.readAsDataURL(file);
        }
    });

    function removeImage() {
        document.getElementById('foto').value = '';
        document.getElementById('preview-container').style.display = 'none';
        document.getElementById('upload-box').style.display = 'block';
    }
</script>
@endpush

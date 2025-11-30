@extends('admin.layouts.app')

@section('title', 'Edit Carousel')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h1 class="h3 mb-1">Edit Carousel</h1>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb mb-0">
                <li class="breadcrumb-item"><a href="{{ route('admin.konten-publik.carousel') }}">Carousel</a></li>
                <li class="breadcrumb-item active">Edit</li>
            </ol>
        </nav>
    </div>
</div>

<div class="row">
    <div class="col-lg-8">
        <div class="card border-0 shadow-sm">
            <div class="card-body">
                <form action="{{ route('admin.konten-publik.carousel.update', $carousel->id) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')

                    <div class="mb-3">
                        <label class="form-label">Judul <span class="text-danger">*</span></label>
                        <input type="text" name="judul" class="form-control @error('judul') is-invalid @enderror"
                               value="{{ old('judul', $carousel->judul) }}" placeholder="Masukkan judul carousel" required>
                        @error('judul')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Deskripsi</label>
                        <textarea name="deskripsi" class="form-control @error('deskripsi') is-invalid @enderror"
                                  rows="3" placeholder="Deskripsi singkat (opsional)">{{ old('deskripsi', $carousel->deskripsi) }}</textarea>
                        @error('deskripsi')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Gambar</label>

                        @if($carousel->gambar)
                            <div class="mb-2">
                                <img src="{{ asset('storage/' . $carousel->gambar) }}" alt="{{ $carousel->judul }}"
                                     class="img-fluid rounded" style="max-height: 200px;">
                                <p class="small text-muted mt-1 mb-0">Gambar saat ini</p>
                            </div>
                        @endif

                        <input type="file" name="gambar" class="form-control @error('gambar') is-invalid @enderror"
                               accept="image/*" id="gambarInput">
                        <small class="text-muted">Kosongkan jika tidak ingin mengubah gambar. Max 5MB</small>
                        @error('gambar')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror

                        <div id="imagePreview" class="mt-3 d-none">
                            <img src="" alt="Preview" class="img-fluid rounded" style="max-height: 300px;">
                            <p class="small text-muted mt-1 mb-0">Gambar baru</p>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">Link Tombol</label>
                                <input type="url" name="link" class="form-control @error('link') is-invalid @enderror"
                                       value="{{ old('link', $carousel->link) }}" placeholder="https://...">
                                @error('link')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">Teks Tombol</label>
                                <input type="text" name="tombol_text" class="form-control @error('tombol_text') is-invalid @enderror"
                                       value="{{ old('tombol_text', $carousel->tombol_text) }}" placeholder="Selengkapnya">
                                @error('tombol_text')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">Urutan</label>
                                <input type="number" name="urutan" class="form-control @error('urutan') is-invalid @enderror"
                                       value="{{ old('urutan', $carousel->urutan) }}" min="0">
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
                                           {{ old('is_active', $carousel->is_active) ? 'checked' : '' }}>
                                    <label class="form-check-label" for="is_active">Aktif</label>
                                </div>
                            </div>
                        </div>
                    </div>

                    <hr>

                    <div class="d-flex justify-content-end gap-2">
                        <a href="{{ route('admin.konten-publik.carousel') }}" class="btn btn-outline-secondary">Batal</a>
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
                        <td class="text-muted">Dibuat</td>
                        <td>{{ $carousel->created_at->format('d M Y H:i') }}</td>
                    </tr>
                    <tr>
                        <td class="text-muted">Diperbarui</td>
                        <td>{{ $carousel->updated_at->format('d M Y H:i') }}</td>
                    </tr>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    document.getElementById('gambarInput').addEventListener('change', function(e) {
        const preview = document.getElementById('imagePreview');
        const img = preview.querySelector('img');

        if (e.target.files && e.target.files[0]) {
            const reader = new FileReader();
            reader.onload = function(e) {
                img.src = e.target.result;
                preview.classList.remove('d-none');
            }
            reader.readAsDataURL(e.target.files[0]);
        }
    });
</script>
@endpush

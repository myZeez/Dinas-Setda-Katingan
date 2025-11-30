@extends('admin.layouts.app')

@section('title', 'Tambah Pemimpin Daerah')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h1 class="h3 mb-1">Tambah Pemimpin Daerah</h1>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb mb-0">
                <li class="breadcrumb-item"><a href="{{ route('admin.konten-publik.pemimpin') }}">Pemimpin Daerah</a></li>
                <li class="breadcrumb-item active">Tambah</li>
            </ol>
        </nav>
    </div>
</div>

<div class="row">
    <div class="col-lg-8">
        <div class="card border-0 shadow-sm">
            <div class="card-body">
                <form action="{{ route('admin.konten-publik.pemimpin.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf

                    <div class="mb-3">
                        <label class="form-label">Nama Lengkap <span class="text-danger">*</span></label>
                        <input type="text" name="nama" class="form-control @error('nama') is-invalid @enderror"
                               value="{{ old('nama') }}" placeholder="Contoh: Dr. H. AHMAD YANI, S.E., M.M." required>
                        @error('nama')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Jabatan <span class="text-danger">*</span></label>
                        <input type="text" name="jabatan" class="form-control @error('jabatan') is-invalid @enderror"
                               value="{{ old('jabatan') }}" placeholder="Contoh: Bupati Katingan" required>
                        @error('jabatan')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        <small class="text-muted">Contoh: Bupati, Wakil Bupati, Sekretaris Daerah</small>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Foto</label>
                        <input type="file" name="foto" class="form-control @error('foto') is-invalid @enderror"
                               accept=".png,.jpg,.jpeg,.webp" id="fotoInput">
                        <small class="text-muted d-block">Format: PNG, JPG, JPEG, WEBP. Maksimal 5MB.</small>
                        <small class="text-success d-block"><i class="bi bi-lightbulb"></i> <strong>Tips:</strong> Gunakan foto format PNG dengan background transparan untuk hasil terbaik di website.</small>
                        @error('foto')
                            <div class="invalid-feedback d-block">{{ $message }}</div>
                        @enderror

                        <div id="imagePreview" class="mt-3 d-none text-center">
                            <img src="" alt="Preview" class="rounded-circle" style="width: 150px; height: 150px; object-fit: cover;">
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Deskripsi / Biodata</label>
                        <textarea name="deskripsi" class="form-control @error('deskripsi') is-invalid @enderror"
                                  rows="4" placeholder="Informasi singkat tentang pemimpin (opsional)">{{ old('deskripsi') }}</textarea>
                        @error('deskripsi')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">Periode Jabatan</label>
                                <input type="text" name="periode" class="form-control @error('periode') is-invalid @enderror"
                                       value="{{ old('periode') }}" placeholder="Contoh: 2024 - 2029">
                                @error('periode')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">Urutan Tampil</label>
                                <input type="number" name="urutan" class="form-control @error('urutan') is-invalid @enderror"
                                       value="{{ old('urutan', 0) }}" min="0">
                                @error('urutan')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                <small class="text-muted">Semakin kecil, semakin di atas</small>
                            </div>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Status</label>
                        <div class="form-check form-switch">
                            <input class="form-check-input" type="checkbox" name="is_active" id="is_active"
                                   {{ old('is_active', true) ? 'checked' : '' }}>
                            <label class="form-check-label" for="is_active">Tampilkan di halaman publik</label>
                        </div>
                    </div>

                    <hr>

                    <div class="d-flex justify-content-end gap-2">
                        <a href="{{ route('admin.konten-publik.pemimpin') }}" class="btn btn-outline-secondary">Batal</a>
                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-save me-1"></i> Simpan
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="col-lg-4">
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-light">
                <h6 class="mb-0"><i class="bi bi-info-circle me-1"></i> Panduan</h6>
            </div>
            <div class="card-body">
                <ul class="small text-muted mb-0">
                    <li class="mb-2">Gunakan foto dengan rasio 1:1 (kotak)</li>
                    <li class="mb-2">Pastikan foto memiliki resolusi yang baik</li>
                    <li class="mb-2">Urutan menentukan posisi tampil di halaman</li>
                    <li class="mb-2">Jabatan akan ditampilkan di bawah nama</li>
                    <li>Nonaktifkan jika tidak ingin ditampilkan sementara</li>
                </ul>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    document.getElementById('fotoInput').addEventListener('change', function(e) {
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

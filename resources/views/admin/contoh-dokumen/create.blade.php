@extends('admin.layouts.app')

@section('title', 'Tambah Contoh Dokumen')

@section('content')
<div class="page-header">
    <div class="d-flex justify-content-between align-items-center flex-wrap gap-2">
        <div>
            <h1 class="page-title">Tambah Contoh Dokumen</h1>
            <p class="page-subtitle">Upload template/contoh dokumen untuk referensi user</p>
        </div>
        <a href="{{ route('admin.contoh-dokumen.index') }}" class="btn btn-outline-secondary">
            <i class="bi bi-arrow-left me-1"></i> Kembali
        </a>
    </div>
</div>

<div class="row">
    <div class="col-lg-8">
        <div class="card">
            <div class="card-body p-4">
                <form action="{{ route('admin.contoh-dokumen.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf

                    <div class="mb-4">
                        <label class="form-label fw-semibold">Nama Dokumen <span class="text-danger">*</span></label>
                        <input type="text" name="nama" class="form-control @error('nama') is-invalid @enderror"
                               value="{{ old('nama') }}" placeholder="Contoh: Contoh Form Surat Penawaran" required>
                        @error('nama')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-4">
                        <label class="form-label fw-semibold">Keterangan</label>
                        <textarea name="keterangan" class="form-control @error('keterangan') is-invalid @enderror"
                                  rows="3" placeholder="Deskripsi singkat tentang dokumen ini...">{{ old('keterangan') }}</textarea>
                        @error('keterangan')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-4">
                        <label class="form-label fw-semibold">File Dokumen <span class="text-danger">*</span></label>
                        <input type="file" name="file" class="form-control @error('file') is-invalid @enderror"
                               accept=".pdf,.doc,.docx,.xls,.xlsx" required>
                        <small class="text-muted">Format: PDF, DOC, DOCX, XLS, XLSX. Maksimal 10MB</small>
                        @error('file')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-4">
                            <label class="form-label fw-semibold">Jenis Layanan (Opsional)</label>
                            <select name="jenis_layanan_id" class="form-select @error('jenis_layanan_id') is-invalid @enderror">
                                <option value="">-- Semua Layanan --</option>
                                @foreach($jenisLayanan as $jl)
                                    <option value="{{ $jl->id }}" {{ old('jenis_layanan_id') == $jl->id ? 'selected' : '' }}>
                                        {{ $jl->kode }} - {{ $jl->nama }}
                                    </option>
                                @endforeach
                            </select>
                            <small class="text-muted">Kosongkan jika berlaku untuk semua jenis layanan</small>
                            @error('jenis_layanan_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6 mb-4">
                            <label class="form-label fw-semibold">Urutan</label>
                            <input type="number" name="urutan" class="form-control @error('urutan') is-invalid @enderror"
                                   value="{{ old('urutan', 0) }}" min="0">
                            <small class="text-muted">Angka kecil tampil lebih dulu</small>
                            @error('urutan')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="mb-4">
                        <div class="form-check form-switch">
                            <input class="form-check-input" type="checkbox" name="is_active" id="is_active" value="1"
                                   {{ old('is_active', true) ? 'checked' : '' }}>
                            <label class="form-check-label" for="is_active">Aktif (ditampilkan ke user)</label>
                        </div>
                    </div>

                    <hr>

                    <div class="d-flex gap-2">
                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-save me-1"></i> Simpan
                        </button>
                        <a href="{{ route('admin.contoh-dokumen.index') }}" class="btn btn-secondary">Batal</a>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="col-lg-4">
        <div class="card">
            <div class="card-header bg-info text-white">
                <h6 class="mb-0"><i class="bi bi-info-circle me-2"></i>Panduan</h6>
            </div>
            <div class="card-body">
                <p class="small text-muted mb-2">Contoh dokumen yang bisa diupload:</p>
                <ul class="small text-muted mb-0">
                    <li>Form Surat Penawaran</li>
                    <li>Contoh Kerangka Acuan Kerja (KAK)</li>
                    <li>Template Nota Kesepakatan</li>
                    <li>Contoh PKS/Kontrak</li>
                    <li>Template Rencana Kerja</li>
                </ul>
            </div>
        </div>
    </div>
</div>
@endsection

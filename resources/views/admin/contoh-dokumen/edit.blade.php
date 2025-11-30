@extends('admin.layouts.app')

@section('title', 'Edit Contoh Dokumen')

@section('content')
<div class="page-header">
    <div class="d-flex justify-content-between align-items-center flex-wrap gap-2">
        <div>
            <h1 class="page-title">Edit Contoh Dokumen</h1>
            <p class="page-subtitle">Perbarui informasi contoh dokumen</p>
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
                <form action="{{ route('admin.contoh-dokumen.update', $contohDokumen->id) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')

                    <div class="mb-4">
                        <label class="form-label fw-semibold">Nama Dokumen <span class="text-danger">*</span></label>
                        <input type="text" name="nama" class="form-control @error('nama') is-invalid @enderror"
                               value="{{ old('nama', $contohDokumen->nama) }}" placeholder="Contoh: Contoh Form Surat Penawaran" required>
                        @error('nama')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-4">
                        <label class="form-label fw-semibold">Keterangan</label>
                        <textarea name="keterangan" class="form-control @error('keterangan') is-invalid @enderror"
                                  rows="3" placeholder="Deskripsi singkat tentang dokumen ini...">{{ old('keterangan', $contohDokumen->keterangan) }}</textarea>
                        @error('keterangan')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-4">
                        <label class="form-label fw-semibold">File Dokumen</label>

                        {{-- File saat ini --}}
                        <div class="border rounded p-3 mb-2 bg-light">
                            <div class="d-flex align-items-center">
                                @php
                                    $ext = pathinfo($contohDokumen->file_name, PATHINFO_EXTENSION);
                                    $iconClass = match(strtolower($ext)) {
                                        'pdf' => 'bi-file-earmark-pdf text-danger',
                                        'doc', 'docx' => 'bi-file-earmark-word text-primary',
                                        'xls', 'xlsx' => 'bi-file-earmark-excel text-success',
                                        default => 'bi-file-earmark text-secondary'
                                    };
                                @endphp
                                <i class="bi {{ $iconClass }} me-2" style="font-size: 24px;"></i>
                                <div>
                                    <p class="mb-0 fw-semibold small">{{ $contohDokumen->file_name }}</p>
                                    <small class="text-muted">{{ $contohDokumen->file_size_formatted }}</small>
                                </div>
                                <a href="{{ route('admin.contoh-dokumen.preview', $contohDokumen->id) }}" target="_blank" class="btn btn-sm btn-outline-primary ms-auto">
                                    <i class="bi bi-eye"></i> Lihat
                                </a>
                            </div>
                        </div>

                        <input type="file" name="file" class="form-control @error('file') is-invalid @enderror"
                               accept=".pdf,.doc,.docx,.xls,.xlsx">
                        <small class="text-muted">Kosongkan jika tidak ingin mengganti file. Format: PDF, DOC, DOCX, XLS, XLSX. Maksimal 10MB</small>
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
                                    <option value="{{ $jl->id }}" {{ old('jenis_layanan_id', $contohDokumen->jenis_layanan_id) == $jl->id ? 'selected' : '' }}>
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
                                   value="{{ old('urutan', $contohDokumen->urutan) }}" min="0">
                            <small class="text-muted">Angka kecil tampil lebih dulu</small>
                            @error('urutan')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="mb-4">
                        <div class="form-check form-switch">
                            <input class="form-check-input" type="checkbox" name="is_active" id="is_active" value="1"
                                   {{ old('is_active', $contohDokumen->is_active) ? 'checked' : '' }}>
                            <label class="form-check-label" for="is_active">Aktif (ditampilkan ke user)</label>
                        </div>
                    </div>

                    <hr>

                    <div class="d-flex gap-2">
                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-save me-1"></i> Update
                        </button>
                        <a href="{{ route('admin.contoh-dokumen.index') }}" class="btn btn-secondary">Batal</a>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="col-lg-4">
        <div class="card">
            <div class="card-header bg-secondary text-white">
                <h6 class="mb-0"><i class="bi bi-bar-chart me-2"></i>Statistik</h6>
            </div>
            <div class="card-body">
                <div class="row text-center">
                    <div class="col-6">
                        <div class="border rounded p-3">
                            <i class="bi bi-eye text-info" style="font-size: 24px;"></i>
                            <h4 class="mb-0 mt-2">{{ number_format($contohDokumen->jumlah_dilihat) }}</h4>
                            <small class="text-muted">Dilihat</small>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="border rounded p-3">
                            <i class="bi bi-download text-success" style="font-size: 24px;"></i>
                            <h4 class="mb-0 mt-2">{{ number_format($contohDokumen->jumlah_diunduh) }}</h4>
                            <small class="text-muted">Diunduh</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

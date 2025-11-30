@extends('admin.layouts.app')

@section('title', 'Tambah Dokumen - ' . $kategori->nama)

@section('content')
<div class="page-header">
    <div class="d-flex justify-content-between align-items-center flex-wrap gap-2">
        <div>
            <h1 class="page-title">Tambah Dokumen</h1>
            <p class="page-subtitle">{{ $kategori->nama }}</p>
        </div>
        <a href="{{ route('admin.informasi-publik.index', $kategori->slug) }}" class="btn btn-outline-secondary">
            <i class="bi bi-arrow-left me-1"></i> Kembali
        </a>
    </div>
</div>

<div class="card">
    <div class="card-body p-4">
        <form action="{{ route('admin.informasi-publik.store', $kategori->slug) }}" method="POST" enctype="multipart/form-data">
            @csrf

            @if($errors->any())
            <div class="alert alert-danger">
                <ul class="mb-0">
                    @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
            @endif

            <div class="row">
                <div class="col-lg-8">
                    <div class="mb-4">
                        <label class="form-label fw-semibold">Judul Dokumen <span class="text-danger">*</span></label>
                        <input type="text" name="judul" class="form-control" placeholder="Masukkan judul dokumen" value="{{ old('judul') }}" required>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-4">
                                <label class="form-label fw-semibold">Nomor Dokumen</label>
                                <input type="text" name="nomor" class="form-control" placeholder="Contoh: 100.3.7.1/1/PEM-KTGN/I/2025" value="{{ old('nomor') }}">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-4">
                                <label class="form-label fw-semibold">Tanggal Dokumen</label>
                                <input type="date" name="tanggal" class="form-control" value="{{ old('tanggal') }}">
                            </div>
                        </div>
                    </div>

                    <div class="mb-4">
                        <label class="form-label fw-semibold">Keterangan</label>
                        <textarea name="keterangan" class="form-control" rows="3" placeholder="Keterangan tambahan (opsional)">{{ old('keterangan') }}</textarea>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-4">
                                <label class="form-label fw-semibold">File Dokumen (PDF)</label>
                                <input type="file" name="file_dokumen" class="form-control" accept=".pdf">
                                <small class="text-muted">Format: PDF, Maks: 10MB</small>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-4">
                                <label class="form-label fw-semibold">File Lampiran (Opsional)</label>
                                <input type="file" name="file_lampiran" class="form-control" accept=".pdf,.jpg,.jpeg,.png">
                                <small class="text-muted">Format: PDF, JPG, PNG. Maks: 10MB</small>
                            </div>
                        </div>
                    </div>

                    <div class="mb-4">
                        <label class="form-label fw-semibold">Label Lampiran</label>
                        <input type="text" name="lampiran_label" class="form-control" placeholder="Contoh: LAMPIRAN PETA.JPG" value="{{ old('lampiran_label') }}">
                        <small class="text-muted">Label yang ditampilkan untuk tombol download lampiran</small>
                    </div>
                </div>

                <div class="col-lg-4">
                    <div class="card bg-light border-0">
                        <div class="card-body">
                            <h6 class="fw-semibold mb-3">Pengaturan</h6>

                            <div class="mb-4">
                                <label class="form-label">Jenis Dokumen</label>
                                <select name="jenis_dokumen_id" class="form-select">
                                    <option value="">- Pilih Jenis -</option>
                                    @foreach($jenisDokumens as $jenis)
                                    <option value="{{ $jenis->id }}" {{ old('jenis_dokumen_id') == $jenis->id ? 'selected' : '' }}>
                                        {{ $jenis->nama }}
                                    </option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="mb-4">
                                <label class="form-label">Status <span class="text-danger">*</span></label>
                                <select name="status" class="form-select" required>
                                    <option value="berlaku" {{ old('status', 'berlaku') == 'berlaku' ? 'selected' : '' }}>Berlaku</option>
                                    <option value="tidak_berlaku" {{ old('status') == 'tidak_berlaku' ? 'selected' : '' }}>Tidak Berlaku</option>
                                    <option value="terealisasi" {{ old('status') == 'terealisasi' ? 'selected' : '' }}>Terealisasi</option>
                                </select>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <hr class="my-4">

            <div class="d-flex justify-content-end gap-2">
                <a href="{{ route('admin.informasi-publik.index', $kategori->slug) }}" class="btn btn-secondary">Batal</a>
                <button type="submit" class="btn btn-primary">
                    <i class="bi bi-save me-1"></i> Simpan
                </button>
            </div>
        </form>
    </div>
</div>
@endsection

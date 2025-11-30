@extends('user.layouts.app')

@section('title', 'Form Pengajuan - ' . $jenisLayanan->nama)

@section('content')
<div class="page-header">
    <h1 class="page-title">{{ $jenisLayanan->nama }}</h1>
    <p class="page-subtitle">Lengkapi data pengajuan kerja sama</p>
</div>

<!-- Progress Steps -->
<div class="card mb-4">
    <div class="card-body p-4">
        <div class="d-flex justify-content-between align-items-center">
            <div class="text-center flex-fill">
                <div class="rounded-circle bg-success text-white d-inline-flex align-items-center justify-content-center" style="width: 40px; height: 40px;">
                    <i class="bi bi-check"></i>
                </div>
                <p class="mb-0 mt-2 small text-success">Pilih Layanan</p>
            </div>
            <div class="flex-fill border-top border-success mx-2"></div>
            <div class="text-center flex-fill">
                <div class="rounded-circle bg-primary text-white d-inline-flex align-items-center justify-content-center" style="width: 40px; height: 40px;">
                    <strong>2</strong>
                </div>
                <p class="mb-0 mt-2 small fw-semibold text-primary">Isi Data</p>
            </div>
            <div class="flex-fill border-top mx-2"></div>
            <div class="text-center flex-fill">
                <div class="rounded-circle bg-light text-muted d-inline-flex align-items-center justify-content-center" style="width: 40px; height: 40px;">
                    <strong>3</strong>
                </div>
                <p class="mb-0 mt-2 small text-muted">Upload Dokumen</p>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-lg-8">
        <div class="card">
            <div class="card-header bg-transparent border-0 pt-4 pb-0 px-4">
                <div class="d-flex align-items-center">
                    <div class="rounded-circle bg-success bg-opacity-10 d-flex align-items-center justify-content-center me-3" style="width: 48px; height: 48px;">
                        <i class="bi {{ $jenisLayanan->icon ?? 'bi-file-earmark-text' }} text-success" style="font-size: 24px;"></i>
                    </div>
                    <div>
                        <h5 class="card-title mb-1">Form Pengajuan</h5>
                        <p class="text-muted small mb-0">{{ $jenisLayanan->kode }}</p>
                    </div>
                </div>
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

                <div class="alert alert-warning">
                    <i class="bi bi-exclamation-triangle me-2"></i>
                    <strong>*DITULIS DENGAN HURUF KAPITAL</strong>
                </div>

                <form action="{{ route('user.layanan.store-step2', $jenisLayanan->kode) }}" method="POST">
                    @csrf

                    <div class="mb-4">
                        <label class="form-label fw-semibold">
                            @if($jenisLayanan->kode == 'KSDD')
                                Nama Daerah Lain <span class="text-danger">*</span>
                            @elseif($jenisLayanan->kode == 'KSDPK')
                                Nama Pihak Ketiga <span class="text-danger">*</span>
                            @else
                                Nama Daerah Lain / Pihak Ketiga <span class="text-danger">*</span>
                            @endif
                        </label>
                        <input type="text" name="nama_pihak" class="form-control text-uppercase"
                               placeholder="Masukkan nama daerah/pihak ketiga"
                               value="{{ old('nama_pihak') }}" required
                               style="text-transform: uppercase;">
                    </div>

                    <div class="mb-4">
                        <label class="form-label fw-semibold">Tentang <span class="text-danger">*</span></label>
                        <textarea name="tentang" class="form-control text-uppercase" rows="4"
                                  placeholder="Jelaskan tentang kerja sama yang akan dilakukan"
                                  required style="text-transform: uppercase;">{{ old('tentang') }}</textarea>
                    </div>

                    <div class="mb-4">
                        <label class="form-label fw-semibold">Instansi Terkait yang Ingin Diajak Kerja Sama <small class="text-muted">(Jika Spesifik)</small></label>
                        <textarea name="instansi_terkait" class="form-control text-uppercase" rows="3"
                                  placeholder="Sebutkan instansi terkait jika ada (opsional)"
                                  style="text-transform: uppercase;">{{ old('instansi_terkait') }}</textarea>
                    </div>

                    <div class="d-flex justify-content-between">
                        <a href="{{ route('user.layanan') }}" class="btn btn-outline-secondary">
                            <i class="bi bi-arrow-left me-1"></i> Kembali
                        </a>
                        <button type="submit" class="btn btn-warning">
                            <i class="bi bi-arrow-right me-1"></i> Lanjut Upload Dokumen
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="col-lg-4">
        <div class="card">
            <div class="card-header bg-transparent border-0 pt-4 pb-0 px-4">
                <h6 class="card-title mb-1">Informasi Layanan</h6>
            </div>
            <div class="card-body p-4">
                <p class="small text-muted">{{ $jenisLayanan->deskripsi }}</p>
                <hr>
                <h6 class="small fw-semibold mb-2">Dokumen yang Diperlukan:</h6>
                <ul class="small text-muted mb-0">
                    <li>Surat Penawaran (PDF)</li>
                    <li>Kerangka Acuan Kerja / KAK (PDF)</li>
                    <li>Draft Naskah PKS / Nota Kesepakatan (DOC/DOCX)</li>
                </ul>
            </div>
        </div>

        <!-- Contoh Form -->
        <div class="card mt-3">
            <div class="card-header bg-transparent border-0 pt-4 pb-0 px-4">
                <h6 class="card-title mb-1">Contoh Form</h6>
                <p class="text-muted small mb-0">Download contoh dokumen</p>
            </div>
            <div class="card-body p-4">
                <div class="list-group list-group-flush">
                    <a href="#" class="list-group-item list-group-item-action d-flex align-items-center px-0">
                        <i class="bi bi-file-pdf text-danger me-2"></i>
                        <span class="small">Contoh Surat Penawaran</span>
                    </a>
                    <a href="#" class="list-group-item list-group-item-action d-flex align-items-center px-0">
                        <i class="bi bi-file-pdf text-danger me-2"></i>
                        <span class="small">Contoh KAK</span>
                    </a>
                    <a href="#" class="list-group-item list-group-item-action d-flex align-items-center px-0">
                        <i class="bi bi-file-word text-primary me-2"></i>
                        <span class="small">Contoh Draft Naskah</span>
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
    .text-uppercase::placeholder {
        text-transform: none;
    }
</style>
@endpush

@extends('user.layouts.app')

@section('title', 'Upload Dokumen - ' . $jenisLayanan->nama)

@section('content')
<div class="page-header">
    <h1 class="page-title">Upload Dokumen</h1>
    <p class="page-subtitle">Upload dokumen yang diperlukan untuk pengajuan</p>
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
                <div class="rounded-circle bg-success text-white d-inline-flex align-items-center justify-content-center" style="width: 40px; height: 40px;">
                    <i class="bi bi-check"></i>
                </div>
                <p class="mb-0 mt-2 small text-success">Isi Data</p>
            </div>
            <div class="flex-fill border-top border-success mx-2"></div>
            <div class="text-center flex-fill">
                <div class="rounded-circle bg-primary text-white d-inline-flex align-items-center justify-content-center" style="width: 40px; height: 40px;">
                    <strong>3</strong>
                </div>
                <p class="mb-0 mt-2 small fw-semibold text-primary">Upload Dokumen</p>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-lg-8">
        <div class="card">
            <div class="card-header bg-transparent border-0 pt-4 pb-0 px-4">
                <h5 class="card-title mb-1">Upload Dokumen</h5>
                <p class="text-muted small mb-0">Peraturan Menteri Dalam Negeri Nomor 22 Tahun 2020</p>
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

                <form action="{{ route('user.layanan.store', $jenisLayanan->kode) }}" method="POST" enctype="multipart/form-data">
                    @csrf

                    <!-- Surat Penawaran -->
                    <div class="mb-4">
                        <label class="form-label fw-semibold">
                            1. Surat Penawaran <span class="text-danger">*</span>
                        </label>
                        <p class="small text-muted mb-2">Upload Format PDF (Maks. 5MB)</p>
                        <div class="upload-box p-4 border-2 border-dashed rounded text-center" id="box-surat-penawaran">
                            <input type="file" name="surat_penawaran" id="surat_penawaran" class="d-none" accept=".pdf" required>
                            <label for="surat_penawaran" class="cursor-pointer d-block">
                                <i class="bi bi-file-earmark-pdf text-danger" style="font-size: 48px;"></i>
                                <p class="mb-1 mt-2 fw-semibold" id="name-surat-penawaran">Klik untuk upload PDF</p>
                                <small class="text-muted">atau drag & drop file disini</small>
                            </label>
                        </div>
                    </div>

                    <!-- Kerangka Acuan Kerja -->
                    <div class="mb-4">
                        <label class="form-label fw-semibold">
                            2. Kerangka Acuan Kerja (KAK) <span class="text-danger">*</span>
                        </label>
                        <p class="small text-muted mb-2">Upload Format PDF (Maks. 5MB)</p>
                        <div class="upload-box p-4 border-2 border-dashed rounded text-center" id="box-kak">
                            <input type="file" name="kerangka_acuan_kerja" id="kerangka_acuan_kerja" class="d-none" accept=".pdf" required>
                            <label for="kerangka_acuan_kerja" class="cursor-pointer d-block">
                                <i class="bi bi-file-earmark-pdf text-danger" style="font-size: 48px;"></i>
                                <p class="mb-1 mt-2 fw-semibold" id="name-kak">Klik untuk upload PDF</p>
                                <small class="text-muted">atau drag & drop file disini</small>
                            </label>
                        </div>
                    </div>

                    <!-- Draft Naskah -->
                    <div class="mb-4">
                        <label class="form-label fw-semibold">
                            3. Draft Naskah PKS ({{ $jenisLayanan->kode }}) atau Nota Kesepakatan Sinergi <span class="text-danger">*</span>
                        </label>
                        <p class="small text-muted mb-2">Upload Format MS. Word / DOC / DOCX (Maks. 10MB)</p>
                        <div class="upload-box p-4 border-2 border-dashed rounded text-center" id="box-draft">
                            <input type="file" name="draft_naskah" id="draft_naskah" class="d-none" accept=".doc,.docx" required>
                            <label for="draft_naskah" class="cursor-pointer d-block">
                                <i class="bi bi-file-earmark-word text-primary" style="font-size: 48px;"></i>
                                <p class="mb-1 mt-2 fw-semibold" id="name-draft">Klik untuk upload DOC/DOCX</p>
                                <small class="text-muted">atau drag & drop file disini</small>
                            </label>
                        </div>
                    </div>

                    <div class="d-flex justify-content-between">
                        <a href="{{ route('user.layanan.create', $jenisLayanan->kode) }}" class="btn btn-outline-secondary">
                            <i class="bi bi-arrow-left me-1"></i> Kembali
                        </a>
                        <button type="submit" class="btn btn-success">
                            <i class="bi bi-check-lg me-1"></i> Kirim Pengajuan
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="col-lg-4">
        <!-- Ringkasan Data -->
        <div class="card">
            <div class="card-header bg-transparent border-0 pt-4 pb-0 px-4">
                <h6 class="card-title mb-1">Ringkasan Pengajuan</h6>
            </div>
            <div class="card-body p-4">
                <table class="table table-sm table-borderless mb-0">
                    <tr>
                        <td class="text-muted small" style="width: 100px;">Layanan</td>
                        <td class="small">: {{ $jenisLayanan->nama }}</td>
                    </tr>
                    <tr>
                        <td class="text-muted small">Nama Pihak</td>
                        <td class="small">: {{ $step2Data['nama_pihak'] }}</td>
                    </tr>
                    <tr>
                        <td class="text-muted small align-top">Tentang</td>
                        <td class="small">: {{ $step2Data['tentang'] }}</td>
                    </tr>
                    @if($step2Data['instansi_terkait'])
                    <tr>
                        <td class="text-muted small align-top">Instansi</td>
                        <td class="small">: {{ $step2Data['instansi_terkait'] }}</td>
                    </tr>
                    @endif
                </table>
            </div>
        </div>

        <!-- Keterangan Status -->
        <div class="card mt-3">
            <div class="card-header bg-transparent border-0 pt-4 pb-0 px-4">
                <h6 class="card-title mb-1">Keterangan Status Dokumen</h6>
            </div>
            <div class="card-body p-4">
                <div class="d-flex align-items-center mb-2">
                    <span class="badge bg-success me-2"><i class="bi bi-check"></i></span>
                    <small>Diterima</small>
                </div>
                <div class="d-flex align-items-center mb-2">
                    <span class="badge bg-primary me-2"><i class="bi bi-gear"></i></span>
                    <small>Diproses</small>
                </div>
                <div class="d-flex align-items-center mb-2">
                    <span class="badge bg-warning me-2"><i class="bi bi-exclamation"></i></span>
                    <small>Mohon Diperbaiki / Koreksi</small>
                </div>
                <div class="d-flex align-items-center">
                    <span class="badge bg-danger me-2"><i class="bi bi-x"></i></span>
                    <small>Ditolak</small>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
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
        background: #f0fdf4;
        border-color: #16a34a !important;
    }
    .upload-box.has-file {
        background: #f0fdf4;
        border-color: #16a34a !important;
    }
    .cursor-pointer {
        cursor: pointer;
    }
</style>
@endpush

@push('scripts')
<script>
    // File input preview
    document.getElementById('surat_penawaran').addEventListener('change', function(e) {
        const fileName = e.target.files[0]?.name || 'Klik untuk upload PDF';
        document.getElementById('name-surat-penawaran').textContent = fileName;
        if(e.target.files[0]) {
            document.getElementById('box-surat-penawaran').classList.add('has-file');
        }
    });

    document.getElementById('kerangka_acuan_kerja').addEventListener('change', function(e) {
        const fileName = e.target.files[0]?.name || 'Klik untuk upload PDF';
        document.getElementById('name-kak').textContent = fileName;
        if(e.target.files[0]) {
            document.getElementById('box-kak').classList.add('has-file');
        }
    });

    document.getElementById('draft_naskah').addEventListener('change', function(e) {
        const fileName = e.target.files[0]?.name || 'Klik untuk upload DOC/DOCX';
        document.getElementById('name-draft').textContent = fileName;
        if(e.target.files[0]) {
            document.getElementById('box-draft').classList.add('has-file');
        }
    });
</script>
@endpush

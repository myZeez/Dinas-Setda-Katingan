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
                <p class="mb-0 mt-2 small fw-semibold text-primary">Isi Data & Upload</p>
            </div>
            <div class="flex-fill border-top mx-2"></div>
            <div class="text-center flex-fill">
                <div class="rounded-circle bg-light text-muted d-inline-flex align-items-center justify-content-center" style="width: 40px; height: 40px;">
                    <strong>3</strong>
                </div>
                <p class="mb-0 mt-2 small text-muted">Menunggu Review</p>
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

                <form id="formPengajuan" action="{{ route('user.layanan.store', $jenisLayanan->kode) }}" method="POST" enctype="multipart/form-data">
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

                    <hr class="my-4">

                    <h6 class="fw-semibold mb-3"><i class="bi bi-cloud-upload me-2"></i>Upload Surat Penawaran</h6>

                    <div class="mb-4">
                        <label class="form-label fw-semibold">Surat Penawaran <span class="text-danger">*</span></label>
                        <input type="file" name="surat_penawaran" class="form-control" accept=".pdf" required>
                        <small class="text-muted">Format: PDF, Maksimal 5MB</small>
                    </div>

                    <div class="alert alert-info">
                        <i class="bi bi-info-circle me-2"></i>
                        <strong>Alur Pengajuan:</strong>
                        <ol class="mb-0 mt-2">
                            <li>Upload <strong>Surat Penawaran</strong> dan submit pengajuan</li>
                            <li>Admin akan mereview Surat Penawaran Anda</li>
                            <li>Jika disetujui, Anda dapat melanjutkan upload <strong>Kerangka Acuan Kerja (KAK)</strong> dan input <strong>Link Nota Kesepakatan (Google Docs)</strong></li>
                            <li>Jika perlu revisi, Anda akan mendapat catatan dari admin untuk perbaikan</li>
                        </ol>
                    </div>

                    <div class="d-flex justify-content-between">
                        <a href="{{ route('user.layanan') }}" class="btn btn-outline-secondary">
                            <i class="bi bi-arrow-left me-1"></i> Kembali
                        </a>
                        <button type="button" class="btn btn-success" onclick="confirmSubmitPengajuan()">
                            <i class="bi bi-send me-1"></i> Submit Pengajuan
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
                <h6 class="small fw-semibold mb-2">Tahapan Dokumen:</h6>
                <div class="small text-muted">
                    <div class="d-flex align-items-start mb-2">
                        <span class="badge bg-primary me-2">1</span>
                        <div>
                            <strong>Surat Penawaran</strong> (PDF)<br>
                            <small class="text-muted">Upload sekarang</small>
                        </div>
                    </div>
                    <div class="d-flex align-items-start mb-2">
                        <span class="badge bg-secondary me-2">2</span>
                        <div>
                            <strong>Kerangka Acuan Kerja (KAK)</strong> (PDF)<br>
                            <small class="text-muted">Setelah SP disetujui</small>
                        </div>
                    </div>
                    <div class="d-flex align-items-start">
                        <span class="badge bg-secondary me-2">3</span>
                        <div>
                            <strong>Nota Kesepakatan</strong> (Link Google Docs)<br>
                            <small class="text-muted">Setelah SP disetujui</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Contoh Form -->
        <div class="card mt-3">
            <div class="card-header bg-transparent border-0 pt-4 pb-0 px-4">
                <h6 class="card-title mb-1">Contoh Dokumen</h6>
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

@push('scripts')
<script>
    function confirmSubmitPengajuan() {
        const form = document.getElementById('formPengajuan');
        const namaPihak = form.querySelector('input[name="nama_pihak"]');
        const tentang = form.querySelector('textarea[name="tentang"]');
        const suratPenawaran = form.querySelector('input[name="surat_penawaran"]');

        // Validasi
        if (!namaPihak.value.trim()) {
            Swal.fire({
                icon: 'warning',
                title: 'Data Belum Lengkap',
                text: 'Silakan isi Nama Daerah/Pihak Ketiga!',
                confirmButtonColor: '#ffc107'
            });
            return;
        }

        if (!tentang.value.trim()) {
            Swal.fire({
                icon: 'warning',
                title: 'Data Belum Lengkap',
                text: 'Silakan isi Tentang kerja sama!',
                confirmButtonColor: '#ffc107'
            });
            return;
        }

        if (!suratPenawaran.files.length) {
            Swal.fire({
                icon: 'warning',
                title: 'File Belum Dipilih',
                text: 'Silakan pilih file Surat Penawaran!',
                confirmButtonColor: '#ffc107'
            });
            return;
        }

        Swal.fire({
            title: 'Submit Pengajuan?',
            html: `
                <p>Anda akan mengajukan kerja sama dengan:</p>
                <p class="fw-semibold text-primary">${namaPihak.value.toUpperCase()}</p>
                <p class="small text-muted mb-0">Pastikan data dan dokumen yang diupload sudah benar.</p>
            `,
            icon: 'question',
            showCancelButton: true,
            confirmButtonColor: '#198754',
            cancelButtonColor: '#6c757d',
            confirmButtonText: '<i class="bi bi-send"></i> Ya, Submit',
            cancelButtonText: 'Batal'
        }).then((result) => {
            if (result.isConfirmed) {
                form.submit();
            }
        });
    }
</script>
@endpush

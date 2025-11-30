@extends('user.layouts.app')

@section('title', 'Detail Pengajuan')

@section('content')
<div class="page-header">
    <div class="d-flex justify-content-between align-items-center flex-wrap gap-2">
        <div>
            <h1 class="page-title">Detail Pengajuan</h1>
            <p class="page-subtitle">{{ $pengajuan->nomor_pengajuan }}</p>
        </div>
        <a href="{{ route('user.layanan.riwayat') }}" class="btn btn-outline-secondary">
            <i class="bi bi-arrow-left me-1"></i> Kembali
        </a>
    </div>
</div>

<div class="row">
    <div class="col-lg-8">
        <!-- Status Pengajuan -->
        <div class="card mb-4">
            <div class="card-body p-4">
                <div class="d-flex align-items-center">
                    <div class="flex-grow-1">
                        <span class="badge bg-{{ $pengajuan->status_color }} fs-6 px-3 py-2">
                            @php
                                $statusIcon = match($pengajuan->status) {
                                    'selesai' => 'check-circle',
                                    'ditolak' => 'x-circle',
                                    'sp_revisi' => 'exclamation-triangle',
                                    'kak_revisi' => 'exclamation-triangle',
                                    'sp_disetujui' => 'check2-circle',
                                    'kak_disetujui' => 'check2-circle',
                                    'menunggu_review_kak' => 'hourglass-split',
                                    default => 'clock'
                                };
                            @endphp
                            <i class="bi bi-{{ $statusIcon }} me-1"></i>
                            {{ $pengajuan->status_label }}
                        </span>
                    </div>
                </div>

                @if($pengajuan->catatan_admin && !in_array($pengajuan->status, ['selesai', 'sp_revisi', 'kak_revisi']))
                <div class="alert alert-{{ $pengajuan->status == 'ditolak' ? 'danger' : 'info' }} mt-3 mb-0">
                    <strong>Catatan Admin:</strong><br>
                    {{ $pengajuan->catatan_admin }}
                </div>
                @endif
            </div>
        </div>

        <!-- Dokumen Hasil / Surat Bukti Selesai -->
        @if($pengajuan->status == 'selesai' && $pengajuan->dokumen_hasil)
        <div class="card mb-4 border-success">
            <div class="card-header bg-success text-white">
                <h6 class="mb-0"><i class="bi bi-patch-check-fill me-2"></i>Pengajuan Selesai - Dokumen Tersedia</h6>
            </div>
            <div class="card-body p-4">
                <div class="text-center mb-4">
                    <div class="rounded-circle bg-success bg-opacity-10 d-inline-flex align-items-center justify-content-center mb-3" style="width: 80px; height: 80px;">
                        <i class="bi bi-check-circle text-success" style="font-size: 48px;"></i>
                    </div>
                    <h5 class="text-success mb-2">Selamat! Pengajuan Anda Telah Selesai</h5>
                    <p class="text-muted mb-0">Dokumen resmi/surat bukti telah tersedia untuk diunduh</p>
                </div>

                @if($pengajuan->catatan_admin)
                <div class="alert alert-success mb-4">
                    <strong><i class="bi bi-chat-quote me-1"></i> Pesan dari Admin:</strong><br>
                    {{ $pengajuan->catatan_admin }}
                </div>
                @endif

                <div class="border rounded p-3 bg-light">
                    <div class="d-flex align-items-center">
                        <i class="bi bi-file-earmark-pdf text-danger me-3" style="font-size: 48px;"></i>
                        <div class="flex-grow-1">
                            <p class="mb-1 fw-semibold">Surat Bukti / Dokumen Resmi</p>
                            <small class="text-muted">{{ basename($pengajuan->dokumen_hasil) }}</small>
                            @if($pengajuan->tanggal_selesai)
                            <br><small class="text-muted">Diterbitkan: {{ \Carbon\Carbon::parse($pengajuan->tanggal_selesai)->format('d F Y, H:i') }} WIB</small>
                            @endif
                        </div>
                        <div class="d-flex flex-column gap-2">
                            <a href="{{ Storage::url($pengajuan->dokumen_hasil) }}" target="_blank" class="btn btn-outline-success">
                                <i class="bi bi-eye me-1"></i> Lihat
                            </a>
                            <a href="{{ Storage::url($pengajuan->dokumen_hasil) }}" download class="btn btn-success">
                                <i class="bi bi-download me-1"></i> Download
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        @endif

        <!-- Notifikasi SP Revisi -->
        @if($pengajuan->status == 'sp_revisi')
        <div class="card mb-4 border-warning">
            <div class="card-header bg-warning text-dark">
                <h6 class="mb-0"><i class="bi bi-exclamation-triangle me-2"></i>Surat Penawaran Perlu Revisi</h6>
            </div>
            <div class="card-body p-4">
                @if($pengajuan->catatan_revisi_sp)
                <div class="alert alert-warning mb-3">
                    <strong>Catatan Revisi dari Admin:</strong><br>
                    {{ $pengajuan->catatan_revisi_sp }}
                </div>
                @endif

                <p class="mb-3">Silakan upload ulang Surat Penawaran yang sudah direvisi:</p>

                <form id="reuploadSPForm" action="{{ route('user.layanan.reupload-sp', $pengajuan->id) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Surat Penawaran (Revisi) <span class="text-danger">*</span></label>
                        <input type="file" name="surat_penawaran" class="form-control" accept=".pdf" required>
                        <small class="text-muted">Format: PDF, Maksimal 5MB</small>
                    </div>
                    <button type="button" class="btn btn-warning" onclick="confirmReuploadSP()">
                        <i class="bi bi-upload me-1"></i> Upload Surat Penawaran Revisi
                    </button>
                </form>
            </div>
        </div>
        @endif

        <!-- Form Upload Dokumen Lanjutan (ketika SP Disetujui) - Hanya upload KAK -->
        @if($pengajuan->status == 'sp_disetujui')
        <div class="card mb-4 border-success">
            <div class="card-header bg-success text-white">
                <h6 class="mb-0"><i class="bi bi-check-circle me-2"></i>Surat Penawaran Disetujui - Upload KAK</h6>
            </div>
            <div class="card-body p-4">
                <div class="alert alert-success mb-4">
                    <i class="bi bi-info-circle me-2"></i>
                    Selamat! Surat Penawaran Anda telah disetujui. Silakan upload <strong>Kerangka Acuan Kerja (KAK)</strong> untuk melanjutkan proses pengajuan.
                </div>

                <form id="uploadKAKForm" action="{{ route('user.layanan.upload-kak', $pengajuan->id) }}" method="POST" enctype="multipart/form-data">
                    @csrf

                    <div class="mb-4">
                        <label class="form-label fw-semibold">Kerangka Acuan Kerja (KAK) <span class="text-danger">*</span></label>
                        <input type="file" name="file_kak" class="form-control" accept=".pdf" required>
                        <small class="text-muted">Format: PDF, Maksimal 5MB</small>
                    </div>

                    <div class="alert alert-info mb-4">
                        <h6 class="alert-heading"><i class="bi bi-lightbulb me-1"></i> Tips:</h6>
                        <ul class="mb-0 small">
                            <li>KAK harus dalam format PDF dan sesuai dengan ketentuan yang berlaku</li>
                            <li>Setelah upload, KAK akan direview oleh admin</li>
                            <li>Jika disetujui, Anda akan diminta untuk melengkapi Link Nota Kesepakatan</li>
                        </ul>
                    </div>

                    <button type="button" class="btn btn-success" onclick="confirmUploadKAK()">
                        <i class="bi bi-upload me-1"></i> Upload KAK
                    </button>
                </form>
            </div>
        </div>
        @endif

        <!-- KAK Menunggu Review -->
        @if($pengajuan->status == 'menunggu_review_kak')
        <div class="card mb-4 border-info">
            <div class="card-header bg-info text-white">
                <h6 class="mb-0"><i class="bi bi-hourglass-split me-2"></i>KAK Sedang Direview</h6>
            </div>
            <div class="card-body p-4">
                <div class="text-center py-3">
                    <div class="spinner-border text-info mb-3" role="status">
                        <span class="visually-hidden">Loading...</span>
                    </div>
                    <h5 class="text-info mb-2">Kerangka Acuan Kerja Sedang Direview</h5>
                    <p class="text-muted mb-0">Admin sedang memeriksa dokumen KAK Anda. Mohon tunggu konfirmasi.</p>
                </div>
            </div>
        </div>
        @endif

        <!-- Notifikasi KAK Revisi -->
        @if($pengajuan->status == 'kak_revisi')
        <div class="card mb-4 border-warning">
            <div class="card-header bg-warning text-dark">
                <h6 class="mb-0"><i class="bi bi-exclamation-triangle me-2"></i>KAK Perlu Revisi</h6>
            </div>
            <div class="card-body p-4">
                @if($pengajuan->catatan_revisi_kak)
                <div class="alert alert-warning mb-3">
                    <strong>Catatan Revisi KAK dari Admin:</strong><br>
                    {{ $pengajuan->catatan_revisi_kak }}
                </div>
                @endif

                <p class="mb-3">Silakan upload ulang Kerangka Acuan Kerja (KAK) yang sudah direvisi:</p>

                <form id="reuploadKAKForm" action="{{ route('user.layanan.upload-kak', $pengajuan->id) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Kerangka Acuan Kerja (Revisi) <span class="text-danger">*</span></label>
                        <input type="file" name="file_kak" class="form-control" accept=".pdf" required>
                        <small class="text-muted">Format: PDF, Maksimal 5MB</small>
                    </div>
                    <button type="button" class="btn btn-warning" onclick="confirmReuploadKAK()">
                        <i class="bi bi-upload me-1"></i> Upload KAK Revisi
                    </button>
                </form>
            </div>
        </div>
        @endif

        <!-- Form Upload Nota Kesepakatan (ketika KAK Disetujui) -->
        @if($pengajuan->status == 'kak_disetujui')
        <div class="card mb-4 border-success">
            <div class="card-header bg-success text-white">
                <h6 class="mb-0"><i class="bi bi-check-circle me-2"></i>KAK Disetujui - Upload Nota Kesepakatan</h6>
            </div>
            <div class="card-body p-4">
                <div class="alert alert-success mb-4">
                    <i class="bi bi-info-circle me-2"></i>
                    Selamat! Kerangka Acuan Kerja (KAK) Anda telah disetujui. Silakan lengkapi <strong>Link Nota Kesepakatan (Google Docs)</strong> untuk menyelesaikan pengajuan.
                </div>

                <form id="uploadNotaForm" action="{{ route('user.layanan.upload-nota', $pengajuan->id) }}" method="POST">
                    @csrf

                    <div class="mb-4">
                        <label class="form-label fw-semibold">Link Nota Kesepakatan (Google Docs) <span class="text-danger">*</span></label>
                        <input type="url" name="link_nota_kesepakatan" class="form-control"
                               placeholder="https://docs.google.com/document/d/..." required>
                        <small class="text-muted">Masukkan link Google Docs yang berisi Nota Kesepakatan. Pastikan link dapat diakses oleh admin.</small>
                    </div>

                    <div class="alert alert-info mb-4">
                        <h6 class="alert-heading"><i class="bi bi-lightbulb me-1"></i> Tips:</h6>
                        <ul class="mb-0 small">
                            <li>Pastikan dokumen Google Docs sudah di-setting share ke "Anyone with the link can view"</li>
                            <li>Setelah submit, dokumen Anda akan masuk ke antrian proses</li>
                        </ul>
                    </div>

                    <button type="button" class="btn btn-success" onclick="confirmUploadNota()">
                        <i class="bi bi-send me-1"></i> Submit Nota Kesepakatan
                    </button>
                </form>
            </div>
        </div>
        @endif

        <!-- Informasi Pengajuan -->
        <div class="card mb-4">
            <div class="card-header bg-transparent border-0 pt-4 pb-0 px-4">
                <h5 class="card-title mb-1">Informasi Pengajuan</h5>
            </div>
            <div class="card-body p-4">
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label text-muted small">Jenis Layanan</label>
                        <p class="mb-0 fw-semibold">{{ $pengajuan->jenisLayanan->nama }}</p>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label text-muted small">Tanggal Pengajuan</label>
                        <p class="mb-0 fw-semibold">{{ $pengajuan->created_at->format('d F Y, H:i') }}</p>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label text-muted small">Nama Daerah/Pihak Ketiga</label>
                        <p class="mb-0">{{ $pengajuan->nama_pihak }}</p>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label text-muted small">Kode Layanan</label>
                        <p class="mb-0">{{ $pengajuan->jenisLayanan->kode }}</p>
                    </div>
                    <div class="col-12 mb-3">
                        <label class="form-label text-muted small">Tentang</label>
                        <p class="mb-0">{{ $pengajuan->tentang }}</p>
                    </div>
                    @if($pengajuan->instansi_terkait)
                    <div class="col-12">
                        <label class="form-label text-muted small">Instansi Terkait</label>
                        <p class="mb-0">{{ $pengajuan->instansi_terkait }}</p>
                    </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Dokumen -->
        <div class="card">
            <div class="card-header bg-transparent border-0 pt-4 pb-0 px-4">
                <h5 class="card-title mb-1">Dokumen Pengajuan</h5>
                <p class="text-muted small mb-0">Status dan daftar dokumen yang diupload</p>
            </div>
            <div class="card-body p-4">
                <div class="table-responsive">
                    <table class="table table-borderless align-middle">
                        <thead class="bg-light">
                            <tr>
                                <th>Dokumen</th>
                                <th class="text-center">Status</th>
                                <th class="text-center">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <!-- Surat Penawaran (dari relasi dokumens) - hanya tampilkan surat_penawaran -->
                            @foreach($pengajuan->dokumens->where('jenis_dokumen', 'surat_penawaran') as $dokumen)
                            <tr>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <i class="bi bi-file-earmark-pdf text-danger me-2" style="font-size: 24px;"></i>
                                        <div>
                                            <p class="mb-0 fw-semibold">{{ $dokumen->jenis_dokumen_label }}</p>
                                            <small class="text-muted">{{ basename($dokumen->file_path) }}</small>
                                        </div>
                                    </div>
                                </td>
                                <td class="text-center">
                                    @if($pengajuan->status == 'sp_revisi')
                                        <span class="badge bg-warning">Perlu Revisi</span>
                                    @elseif(in_array($pengajuan->status, ['sp_disetujui', 'menunggu_review_kak', 'kak_revisi', 'kak_disetujui', 'dokumen_lengkap', 'diproses', 'selesai']))
                                        <span class="badge bg-success">Disetujui</span>
                                    @else
                                        <span class="badge bg-info">Menunggu Review</span>
                                    @endif
                                </td>
                                <td class="text-center">
                                    <a href="{{ Storage::url($dokumen->file_path) }}" target="_blank" class="btn btn-outline-primary btn-sm" title="Lihat">
                                        <i class="bi bi-eye"></i> Lihat
                                    </a>
                                </td>
                            </tr>
                            @endforeach

                            <!-- KAK (dari kolom file_kak) -->
                            @if($pengajuan->file_kak)
                            <tr>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <i class="bi bi-file-earmark-pdf text-danger me-2" style="font-size: 24px;"></i>
                                        <div>
                                            <p class="mb-0 fw-semibold">Kerangka Acuan Kerja (KAK)</p>
                                            <small class="text-muted">{{ basename($pengajuan->file_kak) }}</small>
                                        </div>
                                    </div>
                                </td>
                                <td class="text-center">
                                    @if($pengajuan->status == 'menunggu_review_kak')
                                        <span class="badge bg-info">Menunggu Review</span>
                                    @elseif($pengajuan->status == 'kak_revisi')
                                        <span class="badge bg-warning">Perlu Revisi</span>
                                    @elseif(in_array($pengajuan->status, ['kak_disetujui', 'dokumen_lengkap', 'diproses', 'proses_ttd', 'penjadwalan_ttd', 'selesai']))
                                        <span class="badge bg-success">Disetujui</span>
                                    @else
                                        <span class="badge bg-secondary">Uploaded</span>
                                    @endif
                                </td>
                                <td class="text-center">
                                    <a href="{{ Storage::url($pengajuan->file_kak) }}" target="_blank" class="btn btn-outline-primary btn-sm" title="Lihat">
                                        <i class="bi bi-eye"></i> Lihat
                                    </a>
                                </td>
                            </tr>
                            @elseif(in_array($pengajuan->status, ['menunggu_review_sp', 'sp_revisi', 'sp_disetujui']))
                            <tr class="text-muted">
                                <td>
                                    <div class="d-flex align-items-center">
                                        <i class="bi bi-file-earmark text-secondary me-2" style="font-size: 24px;"></i>
                                        <div>
                                            <p class="mb-0 fw-semibold">Kerangka Acuan Kerja (KAK)</p>
                                            <small class="text-muted">Belum diupload</small>
                                        </div>
                                    </div>
                                </td>
                                <td class="text-center">
                                    @if($pengajuan->status == 'sp_disetujui')
                                        <span class="badge bg-warning">Menunggu Upload</span>
                                    @else
                                        <span class="badge bg-secondary">Menunggu SP Disetujui</span>
                                    @endif
                                </td>
                                <td class="text-center">-</td>
                            </tr>
                            @endif

                            <!-- Nota Kesepakatan (Link Google Docs) -->
                            @if($pengajuan->link_nota_kesepakatan)
                            <tr>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <i class="bi bi-link-45deg text-primary me-2" style="font-size: 24px;"></i>
                                        <div>
                                            <p class="mb-0 fw-semibold">Nota Kesepakatan</p>
                                            <small class="text-muted">Link Google Docs</small>
                                        </div>
                                    </div>
                                </td>
                                <td class="text-center">
                                    <span class="badge bg-success">Submitted</span>
                                </td>
                                <td class="text-center">
                                    <a href="{{ $pengajuan->link_nota_kesepakatan }}" target="_blank" class="btn btn-outline-primary btn-sm" title="Buka Link">
                                        <i class="bi bi-box-arrow-up-right"></i> Buka
                                    </a>
                                </td>
                            </tr>
                            @elseif(in_array($pengajuan->status, ['menunggu_review_sp', 'sp_revisi', 'sp_disetujui', 'menunggu_review_kak', 'kak_revisi']))
                            <tr class="text-muted">
                                <td>
                                    <div class="d-flex align-items-center">
                                        <i class="bi bi-link-45deg text-secondary me-2" style="font-size: 24px;"></i>
                                        <div>
                                            <p class="mb-0 fw-semibold">Nota Kesepakatan</p>
                                            <small class="text-muted">Link Google Docs - Belum diinput</small>
                                        </div>
                                    </div>
                                </td>
                                <td class="text-center">
                                    <span class="badge bg-secondary">Menunggu KAK Disetujui</span>
                                </td>
                                <td class="text-center">-</td>
                            </tr>
                            @elseif($pengajuan->status == 'kak_disetujui')
                            <tr class="text-muted">
                                <td>
                                    <div class="d-flex align-items-center">
                                        <i class="bi bi-link-45deg text-secondary me-2" style="font-size: 24px;"></i>
                                        <div>
                                            <p class="mb-0 fw-semibold">Nota Kesepakatan</p>
                                            <small class="text-muted">Link Google Docs - Belum diinput</small>
                                        </div>
                                    </div>
                                </td>
                                <td class="text-center">
                                    <span class="badge bg-warning">Menunggu Upload</span>
                                </td>
                                <td class="text-center">-</td>
                            </tr>
                            @endif
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <div class="col-lg-4">
        <!-- Informasi Pemohon -->
        <div class="card mb-4">
            <div class="card-header bg-transparent border-0 pt-4 pb-0 px-4">
                <h6 class="card-title mb-1">Informasi Pemohon</h6>
            </div>
            <div class="card-body p-4">
                <div class="d-flex align-items-center mb-3">
                    <div class="rounded-circle bg-success bg-opacity-10 d-flex align-items-center justify-content-center me-3" style="width: 48px; height: 48px;">
                        <i class="bi bi-person text-success" style="font-size: 24px;"></i>
                    </div>
                    <div>
                        <p class="mb-0 fw-semibold">{{ $pengajuan->user->nama }}</p>
                        <small class="text-muted">{{ $pengajuan->user->jabatan }}</small>
                    </div>
                </div>
                <hr>
                <table class="table table-sm table-borderless mb-0">
                    <tr>
                        <td class="text-muted small ps-0" style="width: 80px;">NIP</td>
                        <td class="small">: {{ $pengajuan->user->nip ?? '-' }}</td>
                    </tr>
                    <tr>
                        <td class="text-muted small ps-0">Instansi</td>
                        <td class="small">: {{ $pengajuan->user->instansi ?? '-' }}</td>
                    </tr>
                    <tr>
                        <td class="text-muted small ps-0">Email</td>
                        <td class="small">: {{ $pengajuan->user->email }}</td>
                    </tr>
                    <tr>
                        <td class="text-muted small ps-0">WhatsApp</td>
                        <td class="small">: {{ $pengajuan->user->no_whatsapp ?? '-' }}</td>
                    </tr>
                </table>
            </div>
        </div>

        <!-- Timeline -->
        <div class="card">
            <div class="card-header bg-transparent border-0 pt-4 pb-0 px-4">
                <h6 class="card-title mb-1">Riwayat Status</h6>
            </div>
            <div class="card-body p-4">
                <div class="timeline">
                    @foreach($pengajuan->logs->sortByDesc('created_at') as $log)
                    @php
                        $logColor = match($log->status) {
                            'selesai' => 'success',
                            'ditolak' => 'danger',
                            'sp_revisi' => 'warning',
                            'kak_revisi' => 'warning',
                            'sp_disetujui' => 'success',
                            'kak_disetujui' => 'success',
                            'dokumen_lengkap' => 'info',
                            'menunggu_review_kak' => 'info',
                            default => 'primary'
                        };
                    @endphp
                    <div class="timeline-item">
                        <div class="timeline-marker bg-{{ $logColor }}"></div>
                        <div class="timeline-content">
                            <p class="mb-0 fw-semibold small">
                                @switch($log->status)
                                    @case('diajukan')
                                    @case('menunggu_review_sp')
                                        Pengajuan Dikirim - Menunggu Review SP
                                        @break
                                    @case('sp_disetujui')
                                        Surat Penawaran Disetujui
                                        @break
                                    @case('sp_revisi')
                                        Surat Penawaran Perlu Revisi
                                        @break
                                    @case('menunggu_review_kak')
                                        KAK Diupload - Menunggu Review
                                        @break
                                    @case('kak_disetujui')
                                        KAK Disetujui
                                        @break
                                    @case('kak_revisi')
                                        KAK Perlu Revisi
                                        @break
                                    @case('dokumen_lengkap')
                                        Dokumen Lengkap - Menunggu Proses
                                        @break
                                    @case('diproses')
                                        Sedang Diproses
                                        @break
                                    @case('koreksi')
                                        Perlu Koreksi
                                        @break
                                    @case('proses_ttd')
                                        Proses Tanda Tangan
                                        @break
                                    @case('penjadwalan_ttd')
                                        Penjadwalan TTD
                                        @break
                                    @case('selesai')
                                        Selesai
                                        @break
                                    @case('ditolak')
                                        Ditolak
                                        @break
                                    @default
                                        {{ ucfirst(str_replace('_', ' ', $log->status)) }}
                                @endswitch
                            </p>
                            <small class="text-muted">{{ $log->created_at->format('d M Y, H:i') }}</small>
                            @if($log->keterangan)
                            <p class="small text-muted mb-0 mt-1">{{ $log->keterangan }}</p>
                            @endif
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
    .timeline {
        position: relative;
        padding-left: 30px;
    }
    .timeline::before {
        content: '';
        position: absolute;
        left: 8px;
        top: 0;
        bottom: 0;
        width: 2px;
        background: #e5e7eb;
    }
    .timeline-item {
        position: relative;
        padding-bottom: 20px;
    }
    .timeline-item:last-child {
        padding-bottom: 0;
    }
    .timeline-marker {
        position: absolute;
        left: -26px;
        width: 14px;
        height: 14px;
        border-radius: 50%;
        border: 2px solid #fff;
    }
    .timeline-content {
        padding-left: 5px;
    }
</style>
@endpush

@push('scripts')
<script>
    // Confirm Reupload Surat Penawaran
    function confirmReuploadSP() {
        const form = document.getElementById('reuploadSPForm');
        const fileInput = form.querySelector('input[name="surat_penawaran"]');

        if (!fileInput.files.length) {
            Swal.fire({
                icon: 'warning',
                title: 'File Belum Dipilih',
                text: 'Silakan pilih file Surat Penawaran terlebih dahulu!',
                confirmButtonColor: '#ffc107'
            });
            return;
        }

        Swal.fire({
            title: 'Upload Surat Penawaran Revisi?',
            text: 'Pastikan dokumen yang diupload sudah sesuai dengan catatan revisi dari admin.',
            icon: 'question',
            showCancelButton: true,
            confirmButtonColor: '#ffc107',
            cancelButtonColor: '#6c757d',
            confirmButtonText: '<i class="bi bi-upload"></i> Ya, Upload',
            cancelButtonText: 'Batal'
        }).then((result) => {
            if (result.isConfirmed) {
                form.submit();
            }
        });
    }

    // Confirm Upload KAK
    function confirmUploadKAK() {
        const form = document.getElementById('uploadKAKForm');
        const fileKak = form.querySelector('input[name="file_kak"]');

        if (!fileKak.files.length) {
            Swal.fire({
                icon: 'warning',
                title: 'File KAK Belum Dipilih',
                text: 'Silakan pilih file Kerangka Acuan Kerja (KAK) terlebih dahulu!',
                confirmButtonColor: '#ffc107'
            });
            return;
        }

        Swal.fire({
            title: 'Upload Kerangka Acuan Kerja?',
            html: `
                <p>KAK Anda akan dikirim untuk direview oleh admin.</p>
                <p class="mb-0 small text-muted">Pastikan dokumen sudah benar sebelum mengirim.</p>
            `,
            icon: 'question',
            showCancelButton: true,
            confirmButtonColor: '#198754',
            cancelButtonColor: '#6c757d',
            confirmButtonText: '<i class="bi bi-upload"></i> Ya, Upload',
            cancelButtonText: 'Batal'
        }).then((result) => {
            if (result.isConfirmed) {
                form.submit();
            }
        });
    }

    // Confirm Reupload KAK (Revisi)
    function confirmReuploadKAK() {
        const form = document.getElementById('reuploadKAKForm');
        const fileKak = form.querySelector('input[name="file_kak"]');

        if (!fileKak.files.length) {
            Swal.fire({
                icon: 'warning',
                title: 'File Belum Dipilih',
                text: 'Silakan pilih file KAK terlebih dahulu!',
                confirmButtonColor: '#ffc107'
            });
            return;
        }

        Swal.fire({
            title: 'Upload KAK Revisi?',
            text: 'Pastikan dokumen yang diupload sudah sesuai dengan catatan revisi dari admin.',
            icon: 'question',
            showCancelButton: true,
            confirmButtonColor: '#ffc107',
            cancelButtonColor: '#6c757d',
            confirmButtonText: '<i class="bi bi-upload"></i> Ya, Upload',
            cancelButtonText: 'Batal'
        }).then((result) => {
            if (result.isConfirmed) {
                form.submit();
            }
        });
    }

    // Confirm Upload Nota Kesepakatan
    function confirmUploadNota() {
        const form = document.getElementById('uploadNotaForm');
        const linkNota = form.querySelector('input[name="link_nota_kesepakatan"]');

        if (!linkNota.value) {
            Swal.fire({
                icon: 'warning',
                title: 'Link Nota Kesepakatan Kosong',
                text: 'Silakan masukkan link Google Docs Nota Kesepakatan!',
                confirmButtonColor: '#ffc107'
            });
            return;
        }

        Swal.fire({
            title: 'Submit Nota Kesepakatan?',
            html: `
                <p>Dengan submit Link Nota Kesepakatan, pengajuan Anda akan masuk ke antrian proses.</p>
                <p class="mb-0 small text-muted">Pastikan link dapat diakses oleh admin.</p>
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

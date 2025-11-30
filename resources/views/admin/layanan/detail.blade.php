@extends('admin.layouts.app')

@section('title', 'Detail Pengajuan')

@section('content')
<div class="page-header">
    <div class="d-flex justify-content-between align-items-center flex-wrap gap-2">
        <div>
            <h1 class="page-title">Detail Pengajuan</h1>
            <p class="page-subtitle">{{ $pengajuan->nomor_pengajuan }}</p>
        </div>
        <a href="{{ route('admin.layanan.pengajuan') }}" class="btn btn-outline-secondary">
            <i class="bi bi-arrow-left me-1"></i> Kembali
        </a>
    </div>
</div>

@if(session('success'))
<div class="alert alert-success alert-dismissible fade show" role="alert">
    <i class="bi bi-check-circle me-2"></i>{{ session('success') }}
    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
</div>
@endif

@if(session('error'))
<div class="alert alert-danger alert-dismissible fade show" role="alert">
    <i class="bi bi-exclamation-circle me-2"></i>{{ session('error') }}
    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
</div>
@endif

<div class="row">
    <div class="col-lg-8">
        <!-- Status Card -->
        <div class="card mb-4">
            <div class="card-header bg-transparent border-0 pt-4 pb-0 px-4">
                <div class="d-flex justify-content-between align-items-center">
                    <h5 class="card-title mb-0">Status Pengajuan</h5>
                    <span class="badge bg-{{ $pengajuan->status_color }} fs-6 px-3 py-2">
                        {{ $pengajuan->status_label }}
                    </span>
                </div>
            </div>
            <div class="card-body p-4">
                {{-- Review Surat Penawaran Section --}}
                @if($pengajuan->status == 'menunggu_review_sp' || $pengajuan->status == 'sp_revisi')
                <div class="alert alert-info mb-4">
                    <h6 class="alert-heading"><i class="bi bi-file-earmark-text me-2"></i>Review Surat Penawaran</h6>
                    <p class="mb-0 small">Silakan review Surat Penawaran dari pemohon. Jika disetujui, pemohon dapat melanjutkan upload KAK.</p>
                </div>

                <div class="row g-3 mb-4">
                    <div class="col-md-6">
                        <form id="approveSpForm" action="{{ route('admin.layanan.approve-sp', $pengajuan->id) }}" method="POST">
                            @csrf
                            <button type="button" class="btn btn-success w-100" onclick="confirmApproveSP()">
                                <i class="bi bi-check-circle me-1"></i> Setujui Surat Penawaran
                            </button>
                        </form>
                    </div>
                    <div class="col-md-6">
                        <button type="button" class="btn btn-warning w-100" data-bs-toggle="modal" data-bs-target="#revisiSPModal">
                            <i class="bi bi-exclamation-triangle me-1"></i> Minta Revisi
                        </button>
                    </div>
                </div>
                <hr>
                @endif

                {{-- Review KAK Section --}}
                @if($pengajuan->status == 'menunggu_review_kak' || $pengajuan->status == 'kak_revisi')
                <div class="alert alert-info mb-4">
                    <h6 class="alert-heading"><i class="bi bi-file-earmark-text me-2"></i>Review Kerangka Acuan Kerja (KAK)</h6>
                    <p class="mb-0 small">Silakan review KAK dari pemohon. Jika disetujui, pemohon dapat melanjutkan upload Link Nota Kesepakatan.</p>
                </div>

                <div class="row g-3 mb-4">
                    <div class="col-md-6">
                        <form id="approveKakForm" action="{{ route('admin.layanan.approve-kak', $pengajuan->id) }}" method="POST">
                            @csrf
                            <button type="button" class="btn btn-success w-100" onclick="confirmApproveKAK()">
                                <i class="bi bi-check-circle me-1"></i> Setujui KAK
                            </button>
                        </form>
                    </div>
                    <div class="col-md-6">
                        <button type="button" class="btn btn-warning w-100" data-bs-toggle="modal" data-bs-target="#revisiKAKModal">
                            <i class="bi bi-exclamation-triangle me-1"></i> Minta Revisi KAK
                        </button>
                    </div>
                </div>
                <hr>
                @endif

                {{-- Normal Status Update (untuk status setelah dokumen lengkap) --}}
                @if(in_array($pengajuan->status, ['dokumen_lengkap', 'diproses', 'koreksi', 'proses_ttd', 'penjadwalan_ttd']))
                <form action="{{ route('admin.layanan.update-status', $pengajuan->id) }}" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Ubah Status</label>
                            <select name="status" class="form-select">
                                <option value="diproses" {{ $pengajuan->status == 'diproses' ? 'selected' : '' }}>Diproses</option>
                                <option value="koreksi" {{ $pengajuan->status == 'koreksi' ? 'selected' : '' }}>Perlu Koreksi</option>
                                <option value="proses_ttd" {{ $pengajuan->status == 'proses_ttd' ? 'selected' : '' }}>Proses Tanda Tangan</option>
                                <option value="penjadwalan_ttd" {{ $pengajuan->status == 'penjadwalan_ttd' ? 'selected' : '' }}>Penjadwalan TTD</option>
                                <option value="selesai" {{ $pengajuan->status == 'selesai' ? 'selected' : '' }}>Selesai</option>
                                <option value="ditolak" {{ $pengajuan->status == 'ditolak' ? 'selected' : '' }}>Ditolak</option>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Catatan</label>
                            <input type="text" name="catatan" class="form-control" placeholder="Catatan untuk pemohon" value="{{ $pengajuan->catatan_admin }}">
                        </div>
                        <div class="col-12">
                            <button type="submit" class="btn btn-primary">
                                <i class="bi bi-check-lg me-1"></i> Update Status
                            </button>
                        </div>
                    </div>
                </form>
                @elseif($pengajuan->status == 'sp_disetujui')
                <div class="alert alert-success mb-0">
                    <i class="bi bi-hourglass-split me-2"></i>
                    <strong>Menunggu User Upload KAK</strong><br>
                    <small>Surat Penawaran sudah disetujui. User sedang melengkapi Kerangka Acuan Kerja (KAK).</small>
                </div>
                @elseif($pengajuan->status == 'kak_disetujui')
                <div class="alert alert-success mb-0">
                    <i class="bi bi-hourglass-split me-2"></i>
                    <strong>Menunggu User Upload Link Nota Kesepakatan</strong><br>
                    <small>KAK sudah disetujui. User sedang melengkapi Link Nota Kesepakatan.</small>
                </div>
                @elseif($pengajuan->status == 'selesai')
                <div class="alert alert-success mb-0">
                    <i class="bi bi-check-circle me-2"></i>
                    <strong>Pengajuan Selesai</strong>
                </div>
                @elseif($pengajuan->status == 'ditolak')
                <div class="alert alert-danger mb-0">
                    <i class="bi bi-x-circle me-2"></i>
                    <strong>Pengajuan Ditolak</strong>
                    @if($pengajuan->catatan_admin)
                    <br><small>Alasan: {{ $pengajuan->catatan_admin }}</small>
                    @endif
                </div>
                @endif
            </div>
        </div>

        {{-- Modal Revisi Surat Penawaran --}}
        <div class="modal fade" id="revisiSPModal" tabindex="-1" aria-labelledby="revisiSPModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="revisiSPModalLabel"><i class="bi bi-exclamation-triangle text-warning me-2"></i>Minta Revisi Surat Penawaran</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <form action="{{ route('admin.layanan.revisi-sp', $pengajuan->id) }}" method="POST">
                        @csrf
                        <div class="modal-body">
                            <div class="mb-3">
                                <label class="form-label fw-semibold">Catatan Revisi <span class="text-danger">*</span></label>
                                <textarea name="catatan_revisi_sp" id="catatanRevisiSP" class="form-control" rows="4" placeholder="Jelaskan apa yang perlu direvisi pada Surat Penawaran..." required>{{ old('catatan_revisi_sp') }}</textarea>
                                <small class="text-muted">Catatan ini akan dikirimkan ke pemohon agar mereka dapat memperbaiki dokumen</small>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                            <button type="submit" class="btn btn-warning">
                                <i class="bi bi-send me-1"></i> Kirim Permintaan Revisi
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        {{-- Modal Revisi KAK --}}
        <div class="modal fade" id="revisiKAKModal" tabindex="-1" aria-labelledby="revisiKAKModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="revisiKAKModalLabel"><i class="bi bi-exclamation-triangle text-warning me-2"></i>Minta Revisi KAK</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <form action="{{ route('admin.layanan.revisi-kak', $pengajuan->id) }}" method="POST">
                        @csrf
                        <div class="modal-body">
                            <div class="mb-3">
                                <label class="form-label fw-semibold">Catatan Revisi KAK <span class="text-danger">*</span></label>
                                <textarea name="catatan_revisi_kak" id="catatanRevisiKAK" class="form-control" rows="4" placeholder="Jelaskan apa yang perlu direvisi pada Kerangka Acuan Kerja..." required>{{ old('catatan_revisi_kak') }}</textarea>
                                <small class="text-muted">Catatan ini akan dikirimkan ke pemohon agar mereka dapat memperbaiki dokumen KAK</small>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                            <button type="submit" class="btn btn-warning">
                                <i class="bi bi-send me-1"></i> Kirim Permintaan Revisi KAK
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Informasi Pengajuan -->
        <div class="card mb-4">
            <div class="card-header bg-transparent border-0 pt-4 pb-0 px-4">
                <h5 class="card-title mb-1">Informasi Pengajuan</h5>
            </div>
            <div class="card-body p-4">
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label text-muted small">Jenis Layanan</label>
                        <p class="mb-0 fw-semibold">
                            <span class="badge bg-primary me-2">{{ $pengajuan->jenisLayanan->kode }}</span>
                            {{ $pengajuan->jenisLayanan->nama }}
                        </p>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label text-muted small">Tanggal Pengajuan</label>
                        <p class="mb-0 fw-semibold">{{ $pengajuan->created_at->format('d F Y, H:i') }} WIB</p>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label text-muted small">Nama Daerah/Pihak Ketiga</label>
                        <p class="mb-0">{{ $pengajuan->nama_pihak }}</p>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label text-muted small">Nomor Pengajuan</label>
                        <p class="mb-0 font-monospace">{{ $pengajuan->nomor_pengajuan }}</p>
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
        <div class="card mb-4">
            <div class="card-header bg-transparent border-0 pt-4 pb-0 px-4">
                <h5 class="card-title mb-1">Dokumen Pengajuan</h5>
                <p class="text-muted small mb-0">Verifikasi dan update status dokumen</p>
            </div>
            <div class="card-body p-4">
                {{-- Surat Penawaran (dari relasi dokumens) - hanya tampilkan surat_penawaran --}}
                @foreach($pengajuan->dokumens->where('jenis_dokumen', 'surat_penawaran') as $dokumen)
                <div class="border rounded p-3 mb-3">
                    <div class="row align-items-center">
                        <div class="col-md-5">
                            <div class="d-flex align-items-center">
                                <i class="bi bi-file-earmark-pdf text-danger me-3" style="font-size: 32px;"></i>
                                <div>
                                    <p class="mb-0 fw-semibold">{{ $dokumen->jenis_dokumen_label }}</p>
                                    <small class="text-muted">{{ $dokumen->nama_file }}</small>
                                    <br>
                                    <small class="text-muted">{{ $dokumen->file_size_formatted ?? '' }} • Versi {{ $dokumen->versi }}</small>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3 text-center">
                            @if($pengajuan->status == 'menunggu_review_sp')
                                <span class="badge bg-info">Menunggu Review</span>
                            @elseif($pengajuan->status == 'sp_revisi')
                                <span class="badge bg-warning">Perlu Revisi</span>
                            @elseif(in_array($pengajuan->status, ['sp_disetujui', 'menunggu_review_kak', 'kak_revisi', 'kak_disetujui', 'dokumen_lengkap', 'diproses', 'proses_ttd', 'penjadwalan_ttd', 'selesai']))
                                <span class="badge bg-success">Disetujui</span>
                            @else
                                <span class="badge bg-{{ $dokumen->status_color ?? 'secondary' }}">{{ $dokumen->status_label ?? 'Pending' }}</span>
                            @endif
                        </div>
                        <div class="col-md-4">
                            <div class="d-flex gap-2 justify-content-end">
                                <a href="{{ Storage::url($dokumen->file_path) }}" target="_blank" class="btn btn-sm btn-outline-primary">
                                    <i class="bi bi-eye"></i> Lihat
                                </a>
                                <a href="{{ route('admin.layanan.download', [$pengajuan->id, $dokumen->id]) }}" class="btn btn-sm btn-outline-secondary">
                                    <i class="bi bi-download"></i>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
                @endforeach

                {{-- KAK (dari kolom file_kak) --}}
                @if($pengajuan->file_kak)
                <div class="border rounded p-3 mb-3 {{ in_array($pengajuan->status, ['kak_disetujui', 'dokumen_lengkap', 'diproses', 'proses_ttd', 'penjadwalan_ttd', 'selesai']) ? 'border-success' : '' }}">
                    <div class="row align-items-center">
                        <div class="col-md-5">
                            <div class="d-flex align-items-center">
                                <i class="bi bi-file-earmark-pdf text-danger me-3" style="font-size: 32px;"></i>
                                <div>
                                    <p class="mb-0 fw-semibold">Kerangka Acuan Kerja (KAK)</p>
                                    <small class="text-muted">{{ basename($pengajuan->file_kak) }}</small>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3 text-center">
                            @if($pengajuan->status == 'menunggu_review_kak')
                                <span class="badge bg-info">Menunggu Review</span>
                            @elseif($pengajuan->status == 'kak_revisi')
                                <span class="badge bg-warning">Perlu Revisi</span>
                            @elseif(in_array($pengajuan->status, ['kak_disetujui', 'dokumen_lengkap', 'diproses', 'proses_ttd', 'penjadwalan_ttd', 'selesai']))
                                <span class="badge bg-success">Disetujui</span>
                            @else
                                <span class="badge bg-secondary">Uploaded</span>
                            @endif
                        </div>
                        <div class="col-md-4">
                            <div class="d-flex gap-2 justify-content-end">
                                <a href="{{ Storage::url($pengajuan->file_kak) }}" target="_blank" class="btn btn-sm btn-outline-primary">
                                    <i class="bi bi-eye"></i> Lihat
                                </a>
                                <a href="{{ Storage::url($pengajuan->file_kak) }}" download class="btn btn-sm btn-outline-secondary">
                                    <i class="bi bi-download"></i>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
                @elseif(in_array($pengajuan->status, ['menunggu_review_sp', 'sp_revisi', 'sp_disetujui']))
                <div class="border rounded p-3 mb-3 bg-light">
                    <div class="row align-items-center">
                        <div class="col-md-5">
                            <div class="d-flex align-items-center">
                                <i class="bi bi-file-earmark text-secondary me-3" style="font-size: 32px;"></i>
                                <div>
                                    <p class="mb-0 fw-semibold text-muted">Kerangka Acuan Kerja (KAK)</p>
                                    <small class="text-muted">Belum diupload</small>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3 text-center">
                            <span class="badge bg-secondary">Menunggu SP Disetujui</span>
                        </div>
                        <div class="col-md-4 text-end">
                            <span class="text-muted">-</span>
                        </div>
                    </div>
                </div>
                @endif

                {{-- Link Nota Kesepakatan --}}
                @if($pengajuan->link_nota_kesepakatan)
                <div class="border rounded p-3 mb-3 border-success">
                    <div class="row align-items-center">
                        <div class="col-md-5">
                            <div class="d-flex align-items-center">
                                <i class="bi bi-link-45deg text-primary me-3" style="font-size: 32px;"></i>
                                <div>
                                    <p class="mb-0 fw-semibold">Nota Kesepakatan</p>
                                    <small class="text-muted text-truncate d-block" style="max-width: 200px;">Link Google Docs</small>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3 text-center">
                            <span class="badge bg-success">Submitted</span>
                        </div>
                        <div class="col-md-4">
                            <div class="d-flex gap-2 justify-content-end">
                                <a href="{{ $pengajuan->link_nota_kesepakatan }}" target="_blank" class="btn btn-sm btn-outline-primary">
                                    <i class="bi bi-box-arrow-up-right"></i> Buka Link
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
                @elseif(in_array($pengajuan->status, ['menunggu_review_sp', 'sp_revisi', 'sp_disetujui', 'menunggu_review_kak', 'kak_revisi']))
                <div class="border rounded p-3 mb-3 bg-light">
                    <div class="row align-items-center">
                        <div class="col-md-5">
                            <div class="d-flex align-items-center">
                                <i class="bi bi-link-45deg text-secondary me-3" style="font-size: 32px;"></i>
                                <div>
                                    <p class="mb-0 fw-semibold text-muted">Nota Kesepakatan</p>
                                    <small class="text-muted">Link Google Docs - Belum diinput</small>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3 text-center">
                            <span class="badge bg-secondary">Menunggu KAK Disetujui</span>
                        </div>
                        <div class="col-md-4 text-end">
                            <span class="text-muted">-</span>
                        </div>
                    </div>
                </div>
                @elseif($pengajuan->status == 'kak_disetujui')
                <div class="border rounded p-3 mb-3 bg-light">
                    <div class="row align-items-center">
                        <div class="col-md-5">
                            <div class="d-flex align-items-center">
                                <i class="bi bi-link-45deg text-secondary me-3" style="font-size: 32px;"></i>
                                <div>
                                    <p class="mb-0 fw-semibold text-muted">Nota Kesepakatan</p>
                                    <small class="text-muted">Link Google Docs - Belum diinput</small>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3 text-center">
                            <span class="badge bg-warning">Menunggu User Input</span>
                        </div>
                        <div class="col-md-4 text-end">
                            <span class="text-muted">-</span>
                        </div>
                    </div>
                </div>
                @endif
            </div>
        </div>

        <!-- Upload Dokumen Hasil / Surat Bukti Selesai -->
        @if(in_array($pengajuan->status, ['dokumen_lengkap', 'diproses', 'proses_ttd', 'penjadwalan_ttd']))
        <div class="card mb-4">
            <div class="card-header bg-success text-white">
                <h5 class="card-title mb-1"><i class="bi bi-file-earmark-check me-2"></i>Selesaikan Pengajuan</h5>
                <p class="small mb-0 opacity-75">Upload dokumen hasil dan selesaikan pengajuan</p>
            </div>
            <div class="card-body p-4">
                <div class="alert alert-info mb-4">
                    <i class="bi bi-info-circle me-2"></i>
                    Upload <strong>Surat Bukti / Dokumen Resmi</strong> yang menyatakan kerja sama telah selesai. Dokumen ini akan dikirimkan ke pemohon sebagai bukti bahwa pengajuan telah diproses dan disetujui secara resmi.
                </div>

                <form id="uploadHasilForm" action="{{ route('admin.layanan.upload-hasil', $pengajuan->id) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Dokumen Hasil / Surat Bukti Selesai <span class="text-danger">*</span></label>
                        <input type="file" name="dokumen_hasil" class="form-control" accept=".pdf" required>
                        <small class="text-muted">Format: PDF, Maksimal 10MB</small>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Catatan untuk Pemohon</label>
                        <textarea name="catatan_selesai" class="form-control" rows="3" placeholder="Catatan tambahan untuk pemohon (opsional)">{{ old('catatan_selesai') }}</textarea>
                    </div>
                    <div class="d-grid">
                        <button type="button" class="btn btn-success btn-lg" onclick="confirmUploadHasil()">
                            <i class="bi bi-check-circle me-1"></i> Upload & Selesaikan Pengajuan
                        </button>
                    </div>
                </form>
            </div>
        </div>
        @endif

        @if($pengajuan->dokumen_hasil)
        <div class="card mb-4 border-success">
            <div class="card-header bg-success text-white">
                <h5 class="card-title mb-1"><i class="bi bi-patch-check me-2"></i>Dokumen Hasil</h5>
                <p class="small mb-0 opacity-75">Dokumen resmi telah dikirim ke pemohon</p>
            </div>
            <div class="card-body p-4">
                <div class="d-flex align-items-center">
                    <i class="bi bi-file-earmark-pdf text-danger me-3" style="font-size: 40px;"></i>
                    <div class="flex-grow-1">
                        <p class="mb-0 fw-semibold">Surat Bukti / Dokumen Resmi</p>
                        <small class="text-muted">{{ basename($pengajuan->dokumen_hasil) }}</small>
                        <br>
                        <span class="badge bg-success mt-1"><i class="bi bi-check me-1"></i>Telah Dikirim ke Pemohon</span>
                    </div>
                    <div class="d-flex flex-column gap-2">
                        <a href="{{ Storage::url($pengajuan->dokumen_hasil) }}" target="_blank" class="btn btn-outline-primary btn-sm">
                            <i class="bi bi-eye me-1"></i> Lihat
                        </a>
                        <a href="{{ Storage::url($pengajuan->dokumen_hasil) }}" download class="btn btn-primary btn-sm">
                            <i class="bi bi-download me-1"></i> Download
                        </a>
                    </div>
                </div>
            </div>
        </div>
        @endif
    </div>

    <div class="col-lg-4">
        <!-- Informasi Pemohon -->
        <div class="card mb-4">
            <div class="card-header bg-transparent border-0 pt-4 pb-0 px-4">
                <h6 class="card-title mb-1">Informasi Pemohon</h6>
            </div>
            <div class="card-body p-4">
                <div class="d-flex align-items-center mb-3">
                    <div class="rounded-circle bg-primary bg-opacity-10 d-flex align-items-center justify-content-center me-3" style="width: 48px; height: 48px;">
                        <i class="bi bi-person text-primary" style="font-size: 24px;"></i>
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
                        <td class="text-muted small ps-0">NIK</td>
                        <td class="small">: {{ $pengajuan->user->nik ?? '-' }}</td>
                    </tr>
                    <tr>
                        <td class="text-muted small ps-0">Instansi</td>
                        <td class="small">: {{ $pengajuan->user->instansi ?? '-' }}</td>
                    </tr>
                    <tr>
                        <td class="text-muted small ps-0">Biro/Bagian</td>
                        <td class="small">: {{ $pengajuan->user->biro_bagian ?? '-' }}</td>
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
                            'koreksi' => 'warning',
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
                            @if($log->user)
                            <br><small class="text-muted">oleh: {{ $log->user->nama }}</small>
                            @endif
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
    // Confirm Approve Surat Penawaran
    function confirmApproveSP() {
        Swal.fire({
            title: '<i class="bi bi-check-circle text-success"></i> Setujui Surat Penawaran?',
            html: 'Dengan menyetujui, pemohon dapat melanjutkan upload dokumen:<br><strong>Kerangka Acuan Kerja (KAK)</strong>',
            icon: 'question',
            showCancelButton: true,
            confirmButtonColor: '#10b981',
            cancelButtonColor: '#64748b',
            confirmButtonText: '<i class="bi bi-check-circle me-1"></i> Ya, Setujui',
            cancelButtonText: '<i class="bi bi-x me-1"></i> Batal',
            reverseButtons: true
        }).then((result) => {
            if (result.isConfirmed) {
                document.getElementById('approveSpForm').submit();
            }
        });
    }

    // Confirm Approve KAK
    function confirmApproveKAK() {
        Swal.fire({
            title: '<i class="bi bi-check-circle text-success"></i> Setujui KAK?',
            html: 'Dengan menyetujui, pemohon dapat melanjutkan upload:<br><strong>Link Nota Kesepakatan (Google Docs)</strong>',
            icon: 'question',
            showCancelButton: true,
            confirmButtonColor: '#10b981',
            cancelButtonColor: '#64748b',
            confirmButtonText: '<i class="bi bi-check-circle me-1"></i> Ya, Setujui KAK',
            cancelButtonText: '<i class="bi bi-x me-1"></i> Batal',
            reverseButtons: true
        }).then((result) => {
            if (result.isConfirmed) {
                document.getElementById('approveKakForm').submit();
            }
        });
    }

    // Confirm Upload Hasil & Selesaikan
    function confirmUploadHasil() {
        const fileInput = document.querySelector('input[name="dokumen_hasil"]');
        if (!fileInput.files.length) {
            Swal.fire({
                icon: 'warning',
                title: 'File Belum Dipilih!',
                text: 'Silakan pilih file dokumen hasil terlebih dahulu.',
                confirmButtonColor: '#f59e0b'
            });
            return;
        }

        Swal.fire({
            title: '<i class="bi bi-send-check text-success"></i> Selesaikan Pengajuan?',
            html: `
                <p>Tindakan ini akan:</p>
                <ul class="text-start">
                    <li>Mengubah status menjadi <strong>Selesai</strong></li>
                    <li>Mengirim dokumen hasil ke pemohon</li>
                    <li>Pemohon akan dapat mengunduh dokumen</li>
                </ul>
                <p class="text-muted small">Pastikan dokumen yang diupload sudah benar!</p>
            `,
            icon: 'question',
            showCancelButton: true,
            confirmButtonColor: '#10b981',
            cancelButtonColor: '#64748b',
            confirmButtonText: '<i class="bi bi-check-circle me-1"></i> Ya, Selesaikan',
            cancelButtonText: '<i class="bi bi-x me-1"></i> Batal',
            reverseButtons: true
        }).then((result) => {
            if (result.isConfirmed) {
                Swal.fire({
                    title: 'Mengupload...',
                    text: 'Mohon tunggu sebentar',
                    allowOutsideClick: false,
                    allowEscapeKey: false,
                    didOpen: () => {
                        Swal.showLoading();
                    }
                });
                document.getElementById('uploadHasilForm').submit();
            }
        });
    }

    // Confirm Revisi SP (from modal)
    document.querySelector('#revisiSPModal form')?.addEventListener('submit', function(e) {
        const catatan = document.getElementById('catatanRevisiSP').value;
        if (!catatan.trim()) {
            e.preventDefault();
            Swal.fire({
                icon: 'warning',
                title: 'Catatan Kosong!',
                text: 'Silakan isi catatan revisi untuk pemohon.',
                confirmButtonColor: '#f59e0b'
            });
        }
    });

    // Confirm Revisi KAK (from modal)
    document.querySelector('#revisiKAKModal form')?.addEventListener('submit', function(e) {
        const catatan = document.getElementById('catatanRevisiKAK').value;
        if (!catatan.trim()) {
            e.preventDefault();
            Swal.fire({
                icon: 'warning',
                title: 'Catatan Kosong!',
                text: 'Silakan isi catatan revisi KAK untuk pemohon.',
                confirmButtonColor: '#f59e0b'
            });
        }
    });

    // Fix modal textarea focus issue
    const revisiModal = document.getElementById('revisiSPModal');
    if (revisiModal) {
        revisiModal.addEventListener('shown.bs.modal', function () {
            document.getElementById('catatanRevisiSP').focus();
        });
    }

    const revisiKAKModal = document.getElementById('revisiKAKModal');
    if (revisiKAKModal) {
        revisiKAKModal.addEventListener('shown.bs.modal', function () {
            document.getElementById('catatanRevisiKAK').focus();
        });
    }
</script>
@endpush

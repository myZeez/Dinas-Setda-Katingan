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
                            <i class="bi bi-{{ $pengajuan->status == 'selesai' ? 'check-circle' : ($pengajuan->status == 'koreksi' ? 'exclamation-triangle' : ($pengajuan->status == 'ditolak' ? 'x-circle' : 'clock')) }} me-1"></i>
                            {{ $pengajuan->status_label }}
                        </span>
                    </div>
                    @if($pengajuan->status == 'selesai')
                    <a href="#" class="btn btn-success">
                        <i class="bi bi-download me-1"></i> Download Dokumen Jadi
                    </a>
                    @endif
                </div>

                @if($pengajuan->catatan_admin)
                <div class="alert alert-{{ $pengajuan->status == 'ditolak' ? 'danger' : ($pengajuan->status == 'koreksi' ? 'warning' : 'info') }} mt-3 mb-0">
                    <strong>Catatan Admin:</strong><br>
                    {{ $pengajuan->catatan_admin }}
                </div>
                @endif
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
                            @foreach($pengajuan->dokumens as $dokumen)
                            <tr>
                                <td>
                                    <div class="d-flex align-items-center">
                                        @if(in_array($dokumen->jenis_dokumen, ['surat_penawaran', 'kerangka_acuan_kerja']))
                                        <i class="bi bi-file-earmark-pdf text-danger me-2" style="font-size: 24px;"></i>
                                        @else
                                        <i class="bi bi-file-earmark-word text-primary me-2" style="font-size: 24px;"></i>
                                        @endif
                                        <div>
                                            <p class="mb-0 fw-semibold">{{ $dokumen->jenis_dokumen_label }}</p>
                                            <small class="text-muted">{{ basename($dokumen->file_path) }}</small>
                                        </div>
                                    </div>
                                </td>
                                <td class="text-center">
                                    <span class="badge bg-{{ $dokumen->status_color }}">
                                        {{ $dokumen->status_label }}
                                    </span>
                                    @if($dokumen->catatan)
                                    <br>
                                    <small class="text-muted">{{ $dokumen->catatan }}</small>
                                    @endif
                                </td>
                                <td class="text-center">
                                    <div class="btn-group btn-group-sm">
                                        <a href="{{ Storage::url($dokumen->file_path) }}" target="_blank" class="btn btn-outline-primary" title="Lihat">
                                            <i class="bi bi-eye"></i>
                                        </a>
                                        @if($dokumen->status == 'koreksi')
                                        <button type="button" class="btn btn-outline-warning" data-bs-toggle="modal" data-bs-target="#reuploadModal{{ $dokumen->id }}" title="Upload Ulang">
                                            <i class="bi bi-upload"></i>
                                        </button>
                                        @endif
                                    </div>
                                </td>
                            </tr>

                            <!-- Re-upload Modal -->
                            @if($dokumen->status == 'koreksi')
                            <div class="modal fade" id="reuploadModal{{ $dokumen->id }}" tabindex="-1">
                                <div class="modal-dialog">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title">Upload Ulang {{ $dokumen->jenis_dokumen_label }}</h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                        </div>
                                        <form action="{{ route('user.layanan.reupload', [$pengajuan->id, $dokumen->id]) }}" method="POST" enctype="multipart/form-data">
                                            @csrf
                                            <div class="modal-body">
                                                @if($dokumen->catatan)
                                                <div class="alert alert-warning">
                                                    <strong>Catatan Admin:</strong><br>
                                                    {{ $dokumen->catatan }}
                                                </div>
                                                @endif
                                                <div class="mb-3">
                                                    <label class="form-label">Pilih File Baru</label>
                                                    @if(in_array($dokumen->jenis_dokumen, ['surat_penawaran', 'kerangka_acuan_kerja']))
                                                    <input type="file" name="file" class="form-control" accept=".pdf" required>
                                                    <small class="text-muted">Format: PDF, Maks. 5MB</small>
                                                    @else
                                                    <input type="file" name="file" class="form-control" accept=".doc,.docx" required>
                                                    <small class="text-muted">Format: DOC/DOCX, Maks. 10MB</small>
                                                    @endif
                                                </div>
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                                                <button type="submit" class="btn btn-warning">Upload Ulang</button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                            @endif
                            @endforeach
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
                    <div class="timeline-item">
                        <div class="timeline-marker bg-{{ $log->status == 'selesai' ? 'success' : ($log->status == 'ditolak' ? 'danger' : ($log->status == 'koreksi' ? 'warning' : 'primary')) }}"></div>
                        <div class="timeline-content">
                            <p class="mb-0 fw-semibold small">
                                @switch($log->status)
                                    @case('diajukan')
                                        Pengajuan Dikirim
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
                                        {{ ucfirst($log->status) }}
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

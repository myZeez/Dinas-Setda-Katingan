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
                @foreach($pengajuan->dokumens as $dokumen)
                <div class="border rounded p-3 mb-3">
                    <div class="row align-items-center">
                        <div class="col-md-5">
                            <div class="d-flex align-items-center">
                                @if(in_array($dokumen->jenis_dokumen, ['surat_penawaran', 'kerangka_acuan_kerja']))
                                <i class="bi bi-file-earmark-pdf text-danger me-3" style="font-size: 32px;"></i>
                                @else
                                <i class="bi bi-file-earmark-word text-primary me-3" style="font-size: 32px;"></i>
                                @endif
                                <div>
                                    <p class="mb-0 fw-semibold">{{ $dokumen->jenis_dokumen_label }}</p>
                                    <small class="text-muted">{{ $dokumen->nama_file }}</small>
                                    <br>
                                    <small class="text-muted">{{ $dokumen->file_size_formatted }} • Versi {{ $dokumen->versi }}</small>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3 text-center">
                            <span class="badge bg-{{ $dokumen->status_color }}">{{ $dokumen->status_label }}</span>
                        </div>
                        <div class="col-md-4">
                            <div class="d-flex gap-2 justify-content-end">
                                <a href="{{ Storage::url($dokumen->file_path) }}" target="_blank" class="btn btn-sm btn-outline-primary">
                                    <i class="bi bi-eye"></i> Lihat
                                </a>
                                <a href="{{ route('admin.layanan.download', [$pengajuan->id, $dokumen->id]) }}" class="btn btn-sm btn-outline-secondary">
                                    <i class="bi bi-download"></i>
                                </a>
                                <button type="button" class="btn btn-sm btn-outline-warning" data-bs-toggle="modal" data-bs-target="#updateDokumen{{ $dokumen->id }}">
                                    <i class="bi bi-pencil"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Modal Update Dokumen -->
                <div class="modal fade" id="updateDokumen{{ $dokumen->id }}" tabindex="-1">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title">Update Status Dokumen</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                            </div>
                            <form action="{{ route('admin.layanan.update-dokumen', [$pengajuan->id, $dokumen->id]) }}" method="POST">
                                @csrf
                                @method('PUT')
                                <div class="modal-body">
                                    <p class="mb-3"><strong>{{ $dokumen->jenis_dokumen_label }}</strong></p>
                                    <div class="mb-3">
                                        <label class="form-label">Status Dokumen</label>
                                        <select name="status" class="form-select">
                                            <option value="diterima" {{ $dokumen->status == 'diterima' ? 'selected' : '' }}>✓ Diterima</option>
                                            <option value="diproses" {{ $dokumen->status == 'diproses' ? 'selected' : '' }}>⚙ Diproses</option>
                                            <option value="koreksi" {{ $dokumen->status == 'koreksi' ? 'selected' : '' }}>⚠ Mohon Diperbaiki/Koreksi</option>
                                            <option value="ditolak" {{ $dokumen->status == 'ditolak' ? 'selected' : '' }}>✗ Ditolak</option>
                                        </select>
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label">Catatan</label>
                                        <textarea name="catatan" class="form-control" rows="3" placeholder="Catatan untuk pemohon (opsional)">{{ $dokumen->catatan }}</textarea>
                                    </div>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                                    <button type="submit" class="btn btn-primary">Simpan</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
        </div>

        <!-- Upload Dokumen Hasil -->
        @if($pengajuan->status == 'proses_ttd' || $pengajuan->status == 'penjadwalan_ttd')
        <div class="card">
            <div class="card-header bg-transparent border-0 pt-4 pb-0 px-4">
                <h5 class="card-title mb-1">Upload Dokumen Hasil</h5>
                <p class="text-muted small mb-0">Upload dokumen yang telah ditandatangani</p>
            </div>
            <div class="card-body p-4">
                <form action="{{ route('admin.layanan.upload-hasil', $pengajuan->id) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="mb-3">
                        <label class="form-label">Dokumen Hasil (PDF)</label>
                        <input type="file" name="dokumen_hasil" class="form-control" accept=".pdf" required>
                        <small class="text-muted">Upload dokumen PKS/Nota Kesepakatan yang telah ditandatangani (PDF, maks 10MB)</small>
                    </div>
                    <button type="submit" class="btn btn-success">
                        <i class="bi bi-upload me-1"></i> Upload & Selesaikan
                    </button>
                </form>
            </div>
        </div>
        @endif

        @if($pengajuan->dokumen_hasil)
        <div class="card">
            <div class="card-header bg-transparent border-0 pt-4 pb-0 px-4">
                <h5 class="card-title mb-1">Dokumen Hasil</h5>
            </div>
            <div class="card-body p-4">
                <div class="d-flex align-items-center">
                    <i class="bi bi-file-earmark-pdf text-danger me-3" style="font-size: 32px;"></i>
                    <div class="flex-grow-1">
                        <p class="mb-0 fw-semibold">Dokumen PKS/Nota Kesepakatan</p>
                        <small class="text-muted">{{ basename($pengajuan->dokumen_hasil) }}</small>
                    </div>
                    <a href="{{ Storage::url($pengajuan->dokumen_hasil) }}" target="_blank" class="btn btn-primary">
                        <i class="bi bi-download me-1"></i> Download
                    </a>
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

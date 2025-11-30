@extends('user.layouts.app')

@section('title', 'Pilih Layanan')

@section('content')
<div class="page-header">
    <h1 class="page-title">Ajukan Layanan</h1>
    <p class="page-subtitle">Pilih jenis layanan yang ingin Anda ajukan</p>
</div>

<!-- Progress Steps -->
<div class="card mb-4">
    <div class="card-body p-4">
        <div class="d-flex justify-content-between align-items-center">
            <div class="text-center flex-fill">
                <div class="rounded-circle bg-primary text-white d-inline-flex align-items-center justify-content-center" style="width: 40px; height: 40px;">
                    <strong>1</strong>
                </div>
                <p class="mb-0 mt-2 small fw-semibold text-primary">Pilih Layanan</p>
            </div>
            <div class="flex-fill border-top mx-2"></div>
            <div class="text-center flex-fill">
                <div class="rounded-circle bg-light text-muted d-inline-flex align-items-center justify-content-center" style="width: 40px; height: 40px;">
                    <strong>2</strong>
                </div>
                <p class="mb-0 mt-2 small text-muted">Isi Data</p>
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

<!-- Pilihan Layanan -->
<div class="row g-4">
    @forelse($jenisLayanan as $layanan)
    <div class="col-md-6 col-lg-4">
        <div class="card h-100 border-0 shadow-sm">
            <div class="card-body p-4 text-center">
                <div class="rounded-circle bg-success bg-opacity-10 d-inline-flex align-items-center justify-content-center mb-3" style="width: 80px; height: 80px;">
                    <i class="bi {{ $layanan->icon ?? 'bi-file-earmark-text' }} text-success" style="font-size: 36px;"></i>
                </div>
                <h5 class="card-title mb-2">{{ $layanan->nama }}</h5>
                <p class="text-muted small mb-4">{{ $layanan->deskripsi }}</p>
                <a href="{{ route('user.layanan.create', $layanan->kode) }}" class="btn btn-primary w-100">
                    <i class="bi bi-arrow-right me-1"></i> Pilih Layanan
                </a>
            </div>
        </div>
    </div>
    @empty
    <div class="col-12">
        <div class="card">
            <div class="card-body p-5 text-center">
                <i class="bi bi-inbox text-muted" style="font-size: 48px;"></i>
                <p class="text-muted mt-3">Belum ada layanan yang tersedia</p>
            </div>
        </div>
    </div>
    @endforelse
</div>

<!-- Info Box -->
<div class="card mt-4 border-0" style="background: linear-gradient(135deg, #166534 0%, #14532d 100%);">
    <div class="card-body p-4 text-white">
        <div class="row align-items-center">
            <div class="col-auto">
                <i class="bi bi-info-circle" style="font-size: 32px;"></i>
            </div>
            <div class="col">
                <h6 class="mb-1">Informasi Penting</h6>
                <p class="mb-0 small opacity-75">Pastikan Anda telah menyiapkan dokumen yang diperlukan sebelum mengajukan layanan. Dokumen yang perlu disiapkan: Surat Penawaran (PDF), Kerangka Acuan Kerja/KAK (PDF), dan Draft Naskah PKS atau Nota Kesepakatan (DOC/DOCX).</p>
            </div>
        </div>
    </div>
</div>

<div class="card mt-4">
    <div class="card-header bg-transparent border-0 pt-4 pb-0 px-4">
        <h5 class="card-title mb-1">Prosedur Pengajuan Layanan</h5>
        <p class="text-muted small mb-0">Langkah-langkah mengajukan layanan</p>
    </div>
    <div class="card-body p-4">
        <div class="row">
            <div class="col-md-3 mb-3 mb-md-0">
                <div class="text-center">
                    <div class="stat-icon green mx-auto mb-3">
                        <i class="bi bi-1-circle"></i>
                    </div>
                    <h6>Pilih Layanan</h6>
                    <small class="text-muted">Pilih jenis layanan yang dibutuhkan</small>
                </div>
            </div>
            <div class="col-md-3 mb-3 mb-md-0">
                <div class="text-center">
                    <div class="stat-icon yellow mx-auto mb-3">
                        <i class="bi bi-2-circle"></i>
                    </div>
                    <h6>Isi Formulir</h6>
                    <small class="text-muted">Lengkapi data dan upload dokumen</small>
                </div>
            </div>
            <div class="col-md-3 mb-3 mb-md-0">
                <div class="text-center">
                    <div class="stat-icon blue mx-auto mb-3">
                        <i class="bi bi-3-circle"></i>
                    </div>
                    <h6>Verifikasi</h6>
                    <small class="text-muted">Pengajuan diverifikasi petugas</small>
                </div>
            </div>
            <div class="col-md-3">
                <div class="text-center">
                    <div class="stat-icon green mx-auto mb-3">
                        <i class="bi bi-4-circle"></i>
                    </div>
                    <h6>Selesai</h6>
                    <small class="text-muted">Dokumen siap diambil/dikirim</small>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Contoh Dokumen -->
@if($contohDokumen->count() > 0)
<div class="card mt-4">
    <div class="card-header bg-transparent border-0 pt-4 pb-0 px-4">
        <div class="d-flex justify-content-between align-items-center">
            <div>
                <h5 class="card-title mb-1"><i class="bi bi-file-earmark-arrow-down me-2 text-primary"></i>Contoh Dokumen</h5>
                <p class="text-muted small mb-0">Download template/contoh dokumen sebagai referensi pengajuan</p>
            </div>
        </div>
    </div>
    <div class="card-body p-4">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="bg-light">
                    <tr>
                        <th style="width: 40px;">No.</th>
                        <th>Dokumen</th>
                        <th>Keterangan</th>
                        <th class="text-center">Dilihat</th>
                        <th class="text-center">Diunduh</th>
                        <th class="text-center">Unduh</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($contohDokumen as $index => $dokumen)
                    <tr>
                        <td>{{ $index + 1 }}.</td>
                        <td>
                            <div class="d-flex align-items-center">
                                @php
                                    $ext = pathinfo($dokumen->file_name, PATHINFO_EXTENSION);
                                    $iconClass = match(strtolower($ext)) {
                                        'pdf' => 'bi-file-earmark-pdf text-danger',
                                        'doc', 'docx' => 'bi-file-earmark-word text-primary',
                                        'xls', 'xlsx' => 'bi-file-earmark-excel text-success',
                                        default => 'bi-file-earmark text-secondary'
                                    };
                                @endphp
                                <i class="bi {{ $iconClass }} me-2" style="font-size: 20px;"></i>
                                <span class="fw-semibold">{{ $dokumen->nama }}</span>
                            </div>
                        </td>
                        <td>
                            <small class="text-muted">{{ $dokumen->keterangan ?? '-' }}</small>
                        </td>
                        <td class="text-center">
                            <small class="text-muted"><i class="bi bi-eye me-1"></i>{{ number_format($dokumen->jumlah_dilihat) }}</small>
                        </td>
                        <td class="text-center">
                            <small class="text-muted"><i class="bi bi-download me-1"></i>{{ number_format($dokumen->jumlah_diunduh) }}</small>
                        </td>
                        <td class="text-center">
                            <a href="{{ route('user.contoh-dokumen.download', $dokumen->id) }}" class="btn btn-sm btn-success" title="Download">
                                <i class="bi bi-download"></i>
                            </a>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
@endif
@endsection

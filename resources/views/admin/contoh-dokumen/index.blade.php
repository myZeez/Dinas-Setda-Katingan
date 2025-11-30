@extends('admin.layouts.app')

@section('title', 'Kelola Contoh Dokumen')

@section('content')
<div class="page-header">
    <div class="d-flex justify-content-between align-items-center flex-wrap gap-2">
        <div>
            <h1 class="page-title">Contoh Dokumen</h1>
            <p class="page-subtitle">Kelola template/contoh dokumen untuk referensi pengajuan</p>
        </div>
        <a href="{{ route('admin.contoh-dokumen.create') }}" class="btn btn-primary">
            <i class="bi bi-plus-lg me-1"></i> Tambah Contoh Dokumen
        </a>
    </div>
</div>

<div class="card">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="bg-light">
                    <tr>
                        <th class="ps-4" style="width: 50px;">No</th>
                        <th>Nama Dokumen</th>
                        <th>Jenis Layanan</th>
                        <th class="text-center">Ukuran</th>
                        <th class="text-center">Dilihat</th>
                        <th class="text-center">Diunduh</th>
                        <th class="text-center">Status</th>
                        <th class="text-center pe-4">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($contohDokumen as $index => $dokumen)
                    <tr>
                        <td class="ps-4">{{ $index + 1 }}</td>
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
                                <i class="bi {{ $iconClass }} me-2" style="font-size: 24px;"></i>
                                <div>
                                    <p class="mb-0 fw-semibold">{{ $dokumen->nama }}</p>
                                    <small class="text-muted">{{ $dokumen->file_name }}</small>
                                </div>
                            </div>
                        </td>
                        <td>
                            @if($dokumen->jenisLayanan)
                                <span class="badge bg-primary">{{ $dokumen->jenisLayanan->kode }}</span>
                            @else
                                <span class="text-muted">Semua Layanan</span>
                            @endif
                        </td>
                        <td class="text-center">{{ $dokumen->file_size_formatted }}</td>
                        <td class="text-center">
                            <span class="badge bg-info">{{ number_format($dokumen->jumlah_dilihat) }}</span>
                        </td>
                        <td class="text-center">
                            <span class="badge bg-success">{{ number_format($dokumen->jumlah_diunduh) }}</span>
                        </td>
                        <td class="text-center">
                            @if($dokumen->is_active)
                                <span class="badge bg-success">Aktif</span>
                            @else
                                <span class="badge bg-secondary">Nonaktif</span>
                            @endif
                        </td>
                        <td class="text-center pe-4">
                            <div class="btn-group">
                                <a href="{{ route('admin.contoh-dokumen.preview', $dokumen->id) }}" target="_blank" class="btn btn-sm btn-outline-info" title="Lihat">
                                    <i class="bi bi-eye"></i>
                                </a>
                                <a href="{{ route('admin.contoh-dokumen.download', $dokumen->id) }}" class="btn btn-sm btn-outline-success" title="Download">
                                    <i class="bi bi-download"></i>
                                </a>
                                <a href="{{ route('admin.contoh-dokumen.edit', $dokumen->id) }}" class="btn btn-sm btn-outline-primary" title="Edit">
                                    <i class="bi bi-pencil"></i>
                                </a>
                                <form action="{{ route('admin.contoh-dokumen.destroy', $dokumen->id) }}" method="POST" class="d-inline delete-form">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-outline-danger" title="Hapus">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="8" class="text-center py-5">
                            <div class="text-muted">
                                <i class="bi bi-folder2-open" style="font-size: 48px;"></i>
                                <p class="mt-2 mb-0">Belum ada contoh dokumen</p>
                                <a href="{{ route('admin.contoh-dokumen.create') }}" class="btn btn-primary mt-3">
                                    <i class="bi bi-plus-lg me-1"></i> Tambah Contoh Dokumen
                                </a>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    document.querySelectorAll('.delete-form').forEach(form => {
        form.addEventListener('submit', function(e) {
            e.preventDefault();
            Swal.fire({
                title: 'Hapus Contoh Dokumen?',
                text: 'Data yang dihapus tidak dapat dikembalikan!',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#dc3545',
                cancelButtonColor: '#6c757d',
                confirmButtonText: 'Ya, Hapus!',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed) {
                    form.submit();
                }
            });
        });
    });
</script>
@endpush

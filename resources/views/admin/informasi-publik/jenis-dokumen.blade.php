@extends('admin.layouts.app')

@section('title', 'Jenis Dokumen - ' . $kategori->nama)

@section('content')
<div class="page-header">
    <div class="d-flex justify-content-between align-items-center flex-wrap gap-2">
        <div>
            <h1 class="page-title">Jenis Dokumen</h1>
            <p class="page-subtitle">{{ $kategori->nama }}</p>
        </div>
        <div class="d-flex gap-2">
            <a href="{{ route('admin.informasi-publik.index', $kategori->slug) }}" class="btn btn-outline-secondary">
                <i class="bi bi-arrow-left me-1"></i> Kembali
            </a>
            <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addModal">
                <i class="bi bi-plus-lg me-1"></i> Tambah Jenis
            </button>
        </div>
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

<div class="card">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="bg-light">
                    <tr>
                        <th class="ps-4">No</th>
                        <th>Nama Jenis Dokumen</th>
                        <th class="text-center">Jumlah Dokumen</th>
                        <th class="text-center">Urutan</th>
                        <th class="text-center">Status</th>
                        <th class="text-center pe-4">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($jenisDokumens as $index => $jenis)
                    <tr>
                        <td class="ps-4">{{ $index + 1 }}</td>
                        <td>
                            <span class="fw-semibold">{{ $jenis->nama }}</span>
                        </td>
                        <td class="text-center">
                            <span class="badge bg-primary">{{ $jenis->informasi_publiks_count }} dokumen</span>
                        </td>
                        <td class="text-center">{{ $jenis->urutan }}</td>
                        <td class="text-center">
                            @if($jenis->is_active)
                            <span class="badge bg-success">Aktif</span>
                            @else
                            <span class="badge bg-secondary">Nonaktif</span>
                            @endif
                        </td>
                        <td class="text-center pe-4">
                            <div class="btn-group btn-group-sm">
                                <button type="button" class="btn btn-outline-primary" data-bs-toggle="modal" data-bs-target="#editModal{{ $jenis->id }}" title="Edit">
                                    <i class="bi bi-pencil"></i>
                                </button>
                                @if($jenis->informasi_publiks_count == 0)
                                <button type="button" class="btn btn-outline-danger" title="Hapus"
                                    onclick="confirmDelete('delete-jenis-{{ $jenis->id }}', 'Hapus Jenis Dokumen?', 'Jenis dokumen \'{{ addslashes($jenis->nama) }}\' akan dihapus!')">
                                    <i class="bi bi-trash"></i>
                                </button>
                                <form id="delete-jenis-{{ $jenis->id }}" action="{{ route('admin.informasi-publik.jenis-dokumen.destroy', [$kategori->slug, $jenis->id]) }}" method="POST" class="d-none">
                                    @csrf
                                    @method('DELETE')
                                </form>
                                @else
                                <button type="button" class="btn btn-outline-danger" title="Tidak dapat dihapus"
                                    onclick="Swal.fire({icon: 'warning', title: 'Tidak Dapat Dihapus', text: 'Jenis dokumen ini memiliki {{ $jenis->informasi_publiks_count }} dokumen terkait!', confirmButtonColor: '#f59e0b'})">
                                    <i class="bi bi-trash"></i>
                                </button>
                                @endif
                            </div>
                        </td>
                    </tr>

                    <!-- Edit Modal -->
                    <div class="modal fade" id="editModal{{ $jenis->id }}" tabindex="-1">
                        <div class="modal-dialog">
                            <div class="modal-content">
                                <form action="{{ route('admin.informasi-publik.jenis-dokumen.update', [$kategori->slug, $jenis->id]) }}" method="POST">
                                    @csrf
                                    @method('PUT')
                                    <div class="modal-header">
                                        <h5 class="modal-title">Edit Jenis Dokumen</h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                    </div>
                                    <div class="modal-body">
                                        <div class="mb-3">
                                            <label class="form-label">Nama Jenis <span class="text-danger">*</span></label>
                                            <input type="text" name="nama" class="form-control" value="{{ $jenis->nama }}" required>
                                        </div>
                                        <div class="mb-3">
                                            <label class="form-label">Urutan</label>
                                            <input type="number" name="urutan" class="form-control" value="{{ $jenis->urutan }}">
                                        </div>
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" name="is_active" id="isActive{{ $jenis->id }}" {{ $jenis->is_active ? 'checked' : '' }}>
                                            <label class="form-check-label" for="isActive{{ $jenis->id }}">Aktif</label>
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
                    @empty
                    <tr>
                        <td colspan="6" class="text-center py-5">
                            <i class="bi bi-folder display-4 text-muted"></i>
                            <p class="text-muted mt-3">Belum ada jenis dokumen</p>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Add Modal -->
<div class="modal fade" id="addModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <form action="{{ route('admin.informasi-publik.jenis-dokumen.store', $kategori->slug) }}" method="POST">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title">Tambah Jenis Dokumen</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Nama Jenis <span class="text-danger">*</span></label>
                        <input type="text" name="nama" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Urutan</label>
                        <input type="number" name="urutan" class="form-control" value="0">
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
@endsection

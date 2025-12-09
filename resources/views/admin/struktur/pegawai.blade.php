@extends('admin.layouts.app')

@section('title', 'Kelola Pegawai')

@section('content')
<div class="page-header">
    <div class="d-flex justify-content-between align-items-center flex-wrap gap-2">
        <div>
            <h1 class="page-title">Kelola Pegawai</h1>
            <p class="page-subtitle">Kelola data pegawai Sekretariat Daerah</p>
        </div>
        <div class="d-flex gap-2">
            <a href="{{ route('admin.struktur.index') }}" class="btn btn-outline-secondary">
                <i class="bi bi-arrow-left me-1"></i> Kembali
            </a>
            <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addModal">
                <i class="bi bi-plus-lg me-1"></i> Tambah Pegawai
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

<!-- Filter -->
<div class="card mb-4">
    <div class="card-body p-4">
        <form method="GET" action="{{ route('admin.struktur.pegawai') }}">
            <div class="row g-3 align-items-end">
                <div class="col-md-5">
                    <label class="form-label small">Cari Pegawai</label>
                    <div class="input-group">
                        <span class="input-group-text bg-transparent">
                            <i class="bi bi-search"></i>
                        </span>
                        <input type="text" name="search" class="form-control" placeholder="Cari nama, NIP, atau jabatan..." value="{{ request('search') }}">
                    </div>
                </div>
                <div class="col-md-5">
                    <label class="form-label small">Unit Kerja</label>
                    <select name="unit_kerja_id" class="form-select">
                        <option value="">Semua Unit Kerja</option>
                        @foreach($unitKerjas as $unit)
                        <option value="{{ $unit->id }}" {{ request('unit_kerja_id') == $unit->id ? 'selected' : '' }}>
                            {{ $unit->nama }}
                        </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-2">
                    <button type="submit" class="btn btn-primary w-100">
                        <i class="bi bi-funnel me-1"></i> Filter
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>

<!-- Table -->
<div class="card">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="bg-light">
                    <tr>
                        <th class="ps-4" style="width: 60px;">Foto</th>
                        <th>Nama / NIP</th>
                        <th>Jabatan</th>
                        <th>Unit Kerja</th>
                        <th class="text-center">Pimpinan</th>
                        <th class="text-center">Status</th>
                        <th class="text-center pe-4">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($pegawais as $pegawai)
                    <tr>
                        <td class="ps-4">
                            @if($pegawai->foto)
                            <img src="{{ Storage::url($pegawai->foto) }}" alt="{{ $pegawai->nama }}" class="rounded" style="width: 45px; height: 55px; object-fit: cover;">
                            @else
                            <div class="bg-light rounded d-flex align-items-center justify-content-center" style="width: 45px; height: 55px;">
                                <i class="bi bi-person text-muted"></i>
                            </div>
                            @endif
                        </td>
                        <td>
                            <p class="mb-0 fw-semibold">{{ $pegawai->nama }}</p>
                            <small class="text-muted">NIP. {{ $pegawai->nip ?? '-' }}</small>
                        </td>
                        <td>{{ $pegawai->jabatan }}</td>
                        <td>
                            <small>{{ $pegawai->unitKerja->nama ?? '-' }}</small>
                        </td>
                        <td class="text-center">
                            @if($pegawai->is_pimpinan)
                            <span class="badge bg-warning text-dark">
                                <i class="bi bi-star-fill me-1"></i> Ya
                            </span>
                            @else
                            <span class="text-muted">-</span>
                            @endif
                        </td>
                        <td class="text-center">
                            @if($pegawai->is_active)
                            <span class="badge bg-success">Aktif</span>
                            @else
                            <span class="badge bg-secondary">Nonaktif</span>
                            @endif
                        </td>
                        <td class="text-center pe-4">
                            <div class="btn-group btn-group-sm">
                                <button type="button" class="btn btn-outline-primary" data-bs-toggle="modal" data-bs-target="#editModal{{ $pegawai->id }}" title="Edit">
                                    <i class="bi bi-pencil"></i>
                                </button>
                                <button type="button" class="btn btn-outline-danger" title="Hapus"
                                    onclick="confirmDelete('delete-pegawai-{{ $pegawai->id }}', 'Hapus Data Pegawai?', 'Data pegawai \'{{ addslashes($pegawai->nama) }}\' akan dihapus!')">
                                    <i class="bi bi-trash"></i>
                                </button>
                                <form id="delete-pegawai-{{ $pegawai->id }}" action="{{ route('admin.struktur.pegawai.destroy', $pegawai->id) }}" method="POST" class="d-none">
                                    @csrf
                                    @method('DELETE')
                                </form>
                            </div>
                        </td>
                    </tr>

                    <!-- Edit Modal -->
                    <div class="modal fade" id="editModal{{ $pegawai->id }}" tabindex="-1">
                        <div class="modal-dialog">
                            <div class="modal-content">
                                <form action="{{ route('admin.struktur.pegawai.update', $pegawai->id) }}" method="POST" enctype="multipart/form-data">
                                    @csrf
                                    @method('PUT')
                                    <div class="modal-header">
                                        <h5 class="modal-title">Edit Pegawai</h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                    </div>
                                    <div class="modal-body">
                                        <div class="mb-3">
                                            <label class="form-label">Nama Lengkap <span class="text-danger">*</span></label>
                                            <input type="text" name="nama" class="form-control" value="{{ $pegawai->nama }}" required>
                                        </div>
                                        <div class="mb-3">
                                            <label class="form-label">NIP</label>
                                            <input type="text" name="nip" class="form-control" value="{{ $pegawai->nip }}">
                                        </div>
                                        <div class="mb-3">
                                            <label class="form-label">Jabatan <span class="text-danger">*</span></label>
                                            <input type="text" name="jabatan" class="form-control" value="{{ $pegawai->jabatan }}" required>
                                        </div>
                                        <div class="mb-3">
                                            <label class="form-label">Golongan</label>
                                            <input type="text" name="golongan" class="form-control" value="{{ $pegawai->golongan }}">
                                        </div>
                                        <div class="mb-3">
                                            <label class="form-label">Unit Kerja <span class="text-danger">*</span></label>
                                            <select name="unit_kerja_id" class="form-select" required>
                                                @foreach($unitKerjas as $unit)
                                                <option value="{{ $unit->id }}" {{ $pegawai->unit_kerja_id == $unit->id ? 'selected' : '' }}>
                                                    {{ $unit->nama }}
                                                </option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="mb-3">
                                            <label class="form-label">Foto</label>
                                            @if($pegawai->foto)
                                            <div class="mb-2">
                                                <img src="{{ Storage::url($pegawai->foto) }}" alt="{{ $pegawai->nama }}" class="rounded" style="height: 80px;">
                                            </div>
                                            @endif
                                            <input type="file" name="foto" class="form-control" accept="image/*">
                                            <small class="text-muted">Format: JPG, PNG. Max: 2MB</small>
                                        </div>
                                        <div class="mb-3">
                                            <label class="form-label">Urutan</label>
                                            <input type="number" name="urutan" class="form-control" value="{{ $pegawai->urutan }}">
                                        </div>
                                        <div class="d-flex gap-3">
                                            <div class="form-check">
                                                <input class="form-check-input" type="checkbox" name="is_pimpinan" id="isPimpinan{{ $pegawai->id }}" value="1" {{ $pegawai->is_pimpinan ? 'checked' : '' }}>
                                                <label class="form-check-label" for="isPimpinan{{ $pegawai->id }}">Kepala Unit</label>
                                            </div>
                                            <div class="form-check">
                                                <input class="form-check-input" type="checkbox" name="is_active" id="isActivePegawai{{ $pegawai->id }}" value="1" {{ $pegawai->is_active ? 'checked' : '' }}>
                                                <label class="form-check-label" for="isActivePegawai{{ $pegawai->id }}">Aktif</label>
                                            </div>
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
                        <td colspan="7" class="text-center py-5">
                            <i class="bi bi-people display-4 text-muted"></i>
                            <p class="text-muted mt-3">Belum ada data pegawai</p>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($pegawais->hasPages())
        <div class="d-flex justify-content-between align-items-center p-4 border-top">
            <small class="text-muted">
                Menampilkan {{ $pegawais->firstItem() }} - {{ $pegawais->lastItem() }} dari {{ $pegawais->total() }} pegawai
            </small>
            {{ $pegawais->withQueryString()->links() }}
        </div>
        @endif
    </div>
</div>

<!-- Add Modal -->
<div class="modal fade" id="addModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <form action="{{ route('admin.struktur.pegawai.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title">Tambah Pegawai</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Nama Lengkap <span class="text-danger">*</span></label>
                        <input type="text" name="nama" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">NIP</label>
                        <input type="text" name="nip" class="form-control">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Jabatan <span class="text-danger">*</span></label>
                        <input type="text" name="jabatan" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Golongan</label>
                        <input type="text" name="golongan" class="form-control">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Unit Kerja <span class="text-danger">*</span></label>
                        <select name="unit_kerja_id" class="form-select" required>
                            <option value="">- Pilih Unit Kerja -</option>
                            @foreach($unitKerjas as $unit)
                            <option value="{{ $unit->id }}">{{ $unit->nama }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Foto</label>
                        <input type="file" name="foto" class="form-control" accept="image/*">
                        <small class="text-muted">Format: JPG, PNG. Max: 2MB</small>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Urutan</label>
                        <input type="number" name="urutan" class="form-control" value="0">
                        <small class="text-muted">Jika Kepala Unit dicentang, urutan otomatis menjadi 0 (paling atas)</small>
                    </div>
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" name="is_pimpinan" id="isPimpinanNew" value="1">
                        <label class="form-check-label" for="isPimpinanNew">Kepala Unit (Pimpinan)</label>
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

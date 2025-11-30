@extends('admin.layouts.app')

@section('title', 'Kelola Unit Kerja')

@section('content')
<div class="page-header">
    <div class="d-flex justify-content-between align-items-center flex-wrap gap-2">
        <div>
            <h1 class="page-title">Kelola Unit Kerja</h1>
            <p class="page-subtitle">Kelola data bagian dan sub bagian</p>
        </div>
        <div class="d-flex gap-2">
            <a href="{{ route('admin.struktur.index') }}" class="btn btn-outline-secondary">
                <i class="bi bi-arrow-left me-1"></i> Kembali
            </a>
            <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addModal">
                <i class="bi bi-plus-lg me-1"></i> Tambah Unit Kerja
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
                        <th>Nama Unit Kerja</th>
                        <th>Singkatan</th>
                        <th>Level</th>
                        <th>Parent</th>
                        <th class="text-center">Urutan</th>
                        <th class="text-center">Status</th>
                        <th class="text-center pe-4">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($unitKerjas as $index => $unit)
                    <tr>
                        <td class="ps-4">{{ $index + 1 }}</td>
                        <td>
                            <span class="fw-semibold">{{ $unit->nama }}</span>
                        </td>
                        <td>{{ $unit->singkatan ?? '-' }}</td>
                        <td>
                            @php
                                $levelColors = [
                                    'sekda' => 'primary',
                                    'asisten' => 'danger',
                                    'bagian' => 'warning',
                                    'subbagian' => 'secondary',
                                ];
                            @endphp
                            <span class="badge bg-{{ $levelColors[$unit->level] ?? 'secondary' }}">
                                {{ ucfirst($unit->level) }}
                            </span>
                        </td>
                        <td>{{ $unit->parent->nama ?? '-' }}</td>
                        <td class="text-center">{{ $unit->urutan }}</td>
                        <td class="text-center">
                            @if($unit->is_active)
                            <span class="badge bg-success">Aktif</span>
                            @else
                            <span class="badge bg-secondary">Nonaktif</span>
                            @endif
                        </td>
                        <td class="text-center pe-4">
                            <div class="btn-group btn-group-sm">
                                <button type="button" class="btn btn-outline-primary" data-bs-toggle="modal" data-bs-target="#editModal{{ $unit->id }}" title="Edit">
                                    <i class="bi bi-pencil"></i>
                                </button>
                                <button type="button" class="btn btn-outline-danger" title="Hapus"
                                    onclick="confirmDelete('delete-unit-{{ $unit->id }}', 'Hapus Unit Kerja?', 'Unit kerja \'{{ addslashes($unit->nama) }}\' akan dihapus!')">
                                    <i class="bi bi-trash"></i>
                                </button>
                                <form id="delete-unit-{{ $unit->id }}" action="{{ route('admin.struktur.unit-kerja.destroy', $unit->id) }}" method="POST" class="d-none">
                                    @csrf
                                    @method('DELETE')
                                </form>
                            </div>
                        </td>
                    </tr>

                    <!-- Edit Modal -->
                    <div class="modal fade" id="editModal{{ $unit->id }}" tabindex="-1">
                        <div class="modal-dialog">
                            <div class="modal-content">
                                <form action="{{ route('admin.struktur.unit-kerja.update', $unit->id) }}" method="POST">
                                    @csrf
                                    @method('PUT')
                                    <div class="modal-header">
                                        <h5 class="modal-title">Edit Unit Kerja</h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                    </div>
                                    <div class="modal-body">
                                        <div class="mb-3">
                                            <label class="form-label">Nama Unit Kerja <span class="text-danger">*</span></label>
                                            <input type="text" name="nama" class="form-control" value="{{ $unit->nama }}" required>
                                        </div>
                                        <div class="mb-3">
                                            <label class="form-label">Singkatan</label>
                                            <input type="text" name="singkatan" class="form-control" value="{{ $unit->singkatan }}">
                                        </div>
                                        <div class="mb-3">
                                            <label class="form-label">Level <span class="text-danger">*</span></label>
                                            <select name="level" class="form-select" required>
                                                <option value="sekda" {{ $unit->level == 'sekda' ? 'selected' : '' }}>Sekretaris Daerah</option>
                                                <option value="asisten" {{ $unit->level == 'asisten' ? 'selected' : '' }}>Asisten</option>
                                                <option value="bagian" {{ $unit->level == 'bagian' ? 'selected' : '' }}>Bagian</option>
                                                <option value="subbagian" {{ $unit->level == 'subbagian' ? 'selected' : '' }}>Sub Bagian</option>
                                            </select>
                                        </div>
                                        <div class="mb-3">
                                            <label class="form-label">Parent Unit</label>
                                            <select name="parent_id" class="form-select">
                                                <option value="">- Tidak Ada -</option>
                                                @foreach($parents as $parent)
                                                <option value="{{ $parent->id }}" {{ $unit->parent_id == $parent->id ? 'selected' : '' }}>
                                                    {{ $parent->nama }}
                                                </option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="mb-3">
                                            <label class="form-label">Urutan</label>
                                            <input type="number" name="urutan" class="form-control" value="{{ $unit->urutan }}">
                                        </div>
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" name="is_active" id="isActive{{ $unit->id }}" {{ $unit->is_active ? 'checked' : '' }}>
                                            <label class="form-check-label" for="isActive{{ $unit->id }}">Aktif</label>
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
                        <td colspan="8" class="text-center py-5">
                            <i class="bi bi-diagram-3 display-4 text-muted"></i>
                            <p class="text-muted mt-3">Belum ada data unit kerja</p>
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
            <form action="{{ route('admin.struktur.unit-kerja.store') }}" method="POST">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title">Tambah Unit Kerja</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Nama Unit Kerja <span class="text-danger">*</span></label>
                        <input type="text" name="nama" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Singkatan</label>
                        <input type="text" name="singkatan" class="form-control">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Level <span class="text-danger">*</span></label>
                        <select name="level" class="form-select" required>
                            <option value="">- Pilih Level -</option>
                            <option value="sekda">Sekretaris Daerah</option>
                            <option value="asisten">Asisten</option>
                            <option value="bagian">Bagian</option>
                            <option value="subbagian">Sub Bagian</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Parent Unit</label>
                        <select name="parent_id" class="form-select">
                            <option value="">- Tidak Ada -</option>
                            @foreach($parents as $parent)
                            <option value="{{ $parent->id }}">{{ $parent->nama }}</option>
                            @endforeach
                        </select>
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

@extends('admin.layouts.app')

@section('title', 'Kelola User Masyarakat')

@section('content')
<div class="content-wrapper">
    <div class="page-header">
        <h1 class="page-title">Kelola User Masyarakat</h1>
        <p class="page-subtitle">Kelola akun pengguna layanan masyarakat</p>
    </div>

    <!-- Stats Cards -->
    <div class="row mb-4">
        <div class="col-md-3">
            <div class="card stat-card">
                <div class="d-flex align-items-center">
                    <div class="stat-icon blue">
                        <i class="bi bi-people-fill"></i>
                    </div>
                    <div class="ms-3">
                        <div class="stat-value">{{ $stats['total'] }}</div>
                        <div class="stat-label">Total User</div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card stat-card">
                <div class="d-flex align-items-center">
                    <div class="stat-icon green">
                        <i class="bi bi-check-circle-fill"></i>
                    </div>
                    <div class="ms-3">
                        <div class="stat-value">{{ $stats['active'] }}</div>
                        <div class="stat-label">Aktif</div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card stat-card">
                <div class="d-flex align-items-center">
                    <div class="stat-icon red">
                        <i class="bi bi-x-circle-fill"></i>
                    </div>
                    <div class="ms-3">
                        <div class="stat-value">{{ $stats['inactive'] }}</div>
                        <div class="stat-label">Nonaktif</div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card stat-card">
                <div class="d-flex align-items-center">
                    <div class="stat-icon orange">
                        <i class="bi bi-person-plus-fill"></i>
                    </div>
                    <div class="ms-3">
                        <div class="stat-value">{{ $stats['today'] }}</div>
                        <div class="stat-label">Daftar Hari Ini</div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Filters -->
    <div class="card mb-4">
        <div class="card-body">
            <form action="{{ route('admin.users.user') }}" method="GET" class="row g-3">
                <div class="col-md-3">
                    <label class="form-label">Status</label>
                    <select name="status" class="form-select">
                        <option value="">Semua Status</option>
                        <option value="1" {{ request('status') === '1' ? 'selected' : '' }}>Aktif</option>
                        <option value="0" {{ request('status') === '0' ? 'selected' : '' }}>Nonaktif</option>
                    </select>
                </div>
                <div class="col-md-5">
                    <label class="form-label">Cari</label>
                    <input type="text" name="search" class="form-control" placeholder="Nama, email, NIK, instansi..." value="{{ request('search') }}">
                </div>
                <div class="col-md-4 d-flex align-items-end gap-2">
                    <button type="submit" class="btn btn-primary">
                        <i class="bi bi-search"></i> Cari
                    </button>
                    <a href="{{ route('admin.users.user') }}" class="btn btn-outline-secondary">
                        <i class="bi bi-x-lg"></i>
                    </a>
                </div>
            </form>
        </div>
    </div>

    <!-- Alert Messages -->
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

    <!-- Users Table -->
    <div class="card">
        <div class="card-header">
            <h5 class="mb-0"><i class="bi bi-people me-2"></i>Daftar User Masyarakat</h5>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover mb-0">
                    <thead class="table-light">
                        <tr>
                            <th width="50">#</th>
                            <th>Nama</th>
                            <th>Email</th>
                            <th>NIK</th>
                            <th>Instansi</th>
                            <th width="100">Status</th>
                            <th width="130">Terdaftar</th>
                            <th width="120">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($users as $index => $user)
                            <tr>
                                <td>{{ $users->firstItem() + $index }}</td>
                                <td>
                                    <div class="fw-medium">{{ $user->name }}</div>
                                    @if($user->jabatan)
                                        <small class="text-muted">{{ $user->jabatan }}</small>
                                    @endif
                                </td>
                                <td>{{ $user->email }}</td>
                                <td>{{ $user->nik ?? '-' }}</td>
                                <td>{{ $user->instansi ?? '-' }}</td>
                                <td>
                                    @if($user->is_active)
                                        <span class="badge bg-success">Aktif</span>
                                    @else
                                        <span class="badge bg-danger">Nonaktif</span>
                                    @endif
                                </td>
                                <td>{{ $user->created_at->format('d/m/Y') }}</td>
                                <td>
                                    <div class="btn-group btn-group-sm">
                                        <button type="button" class="btn btn-outline-info" data-bs-toggle="modal" data-bs-target="#detailModal{{ $user->id }}" title="Detail">
                                            <i class="bi bi-eye"></i>
                                        </button>
                                        <button type="button" class="btn btn-outline-{{ $user->is_active ? 'warning' : 'success' }}" title="{{ $user->is_active ? 'Nonaktifkan' : 'Aktifkan' }}"
                                            onclick="confirmAction('toggle-status-{{ $user->id }}', '{{ $user->is_active ? 'Nonaktifkan' : 'Aktifkan' }} User?', 'User {{ addslashes($user->name) }} akan di{{ $user->is_active ? 'nonaktifkan' : 'aktifkan' }}', 'question', 'Ya, Lanjutkan', '{{ $user->is_active ? '#f59e0b' : '#22c55e' }}')">
                                            <i class="bi bi-{{ $user->is_active ? 'pause' : 'play' }}"></i>
                                        </button>
                                        <form id="toggle-status-{{ $user->id }}" action="{{ route('admin.users.toggle-status', $user->id) }}" method="POST" class="d-none">
                                            @csrf
                                            @method('PATCH')
                                        </form>
                                        <button type="button" class="btn btn-outline-danger" title="Hapus"
                                            onclick="confirmDelete('delete-user-{{ $user->id }}', 'Hapus User?', 'User {{ addslashes($user->name) }} dan semua data pengajuannya akan dihapus permanen!')">
                                            <i class="bi bi-trash"></i>
                                        </button>
                                        <form id="delete-user-{{ $user->id }}" action="{{ route('admin.users.user.destroy', $user->id) }}" method="POST" class="d-none">
                                            @csrf
                                            @method('DELETE')
                                        </form>
                                    </div>

                                    <!-- Detail Modal -->
                                    <div class="modal fade" id="detailModal{{ $user->id }}" tabindex="-1">
                                        <div class="modal-dialog">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h5 class="modal-title">Detail User</h5>
                                                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                                </div>
                                                <div class="modal-body">
                                                    <table class="table table-sm">
                                                        <tr>
                                                            <td class="text-muted" width="140">Nama</td>
                                                            <td class="fw-medium">{{ $user->name }}</td>
                                                        </tr>
                                                        <tr>
                                                            <td class="text-muted">Email</td>
                                                            <td>{{ $user->email }}</td>
                                                        </tr>
                                                        <tr>
                                                            <td class="text-muted">NIK</td>
                                                            <td>{{ $user->nik ?? '-' }}</td>
                                                        </tr>
                                                        <tr>
                                                            <td class="text-muted">NIP</td>
                                                            <td>{{ $user->nip ?? '-' }}</td>
                                                        </tr>
                                                        <tr>
                                                            <td class="text-muted">Jabatan</td>
                                                            <td>{{ $user->jabatan ?? '-' }}</td>
                                                        </tr>
                                                        <tr>
                                                            <td class="text-muted">No. WhatsApp</td>
                                                            <td>{{ $user->no_whatsapp ?? '-' }}</td>
                                                        </tr>
                                                        <tr>
                                                            <td class="text-muted">Jenis Kelamin</td>
                                                            <td>{{ $user->jenis_kelamin ?? '-' }}</td>
                                                        </tr>
                                                        <tr>
                                                            <td class="text-muted">Instansi</td>
                                                            <td>{{ $user->instansi ?? '-' }}</td>
                                                        </tr>
                                                        <tr>
                                                            <td class="text-muted">Biro/Bagian</td>
                                                            <td>{{ $user->biro_bagian ?? '-' }}</td>
                                                        </tr>
                                                        <tr>
                                                            <td class="text-muted">Status</td>
                                                            <td>
                                                                @if($user->is_active)
                                                                    <span class="badge bg-success">Aktif</span>
                                                                @else
                                                                    <span class="badge bg-danger">Nonaktif</span>
                                                                @endif
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <td class="text-muted">Terdaftar</td>
                                                            <td>{{ $user->created_at->format('d F Y, H:i') }}</td>
                                                        </tr>
                                                    </table>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8" class="text-center py-5">
                                    <div class="text-muted">
                                        <i class="bi bi-inbox display-4 d-block mb-3"></i>
                                        <p>Belum ada user terdaftar</p>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
        @if($users->hasPages())
            <div class="card-footer">
                {{ $users->withQueryString()->links() }}
            </div>
        @endif
    </div>
</div>
@endsection

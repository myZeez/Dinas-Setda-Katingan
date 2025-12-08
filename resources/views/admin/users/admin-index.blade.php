@extends('admin.layouts.app')

@section('title', 'Kelola Admin')

@section('content')
<div class="content-wrapper">
    <div class="page-header d-flex justify-content-between align-items-center">
        <div>
            <h1 class="page-title">Kelola Admin</h1>
            <p class="page-subtitle">Kelola akun administrator sistem</p>
        </div>
        <a href="{{ route('admin.users.admin.create') }}" class="btn btn-primary">
            <i class="bi bi-plus-lg me-1"></i> Tambah Admin
        </a>
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
                        <div class="stat-label">Total Admin</div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card stat-card">
                <div class="d-flex align-items-center">
                    <div class="stat-icon orange">
                        <i class="bi bi-shield-fill-check"></i>
                    </div>
                    <div class="ms-3">
                        <div class="stat-value">{{ $stats['super_admin'] }}</div>
                        <div class="stat-label">Super Admin</div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card stat-card">
                <div class="d-flex align-items-center">
                    <div class="stat-icon green">
                        <i class="bi bi-person-badge"></i>
                    </div>
                    <div class="ms-3">
                        <div class="stat-value">{{ $stats['admin'] }}</div>
                        <div class="stat-label">Admin Biasa</div>
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
    </div>

    <!-- Filters -->
    <div class="card mb-4">
        <div class="card-body">
            <form action="{{ route('admin.users.admin') }}" method="GET" class="row g-3">
                <div class="col-md-3">
                    <label class="form-label">Role</label>
                    <select name="role" class="form-select">
                        <option value="">Semua Role</option>
                        <option value="super_admin" {{ request('role') == 'super_admin' ? 'selected' : '' }}>Super Admin</option>
                        <option value="admin" {{ request('role') == 'admin' ? 'selected' : '' }}>Admin Biasa</option>
                    </select>
                </div>
                <div class="col-md-3">
                    <label class="form-label">Status</label>
                    <select name="status" class="form-select">
                        <option value="">Semua Status</option>
                        <option value="1" {{ request('status') === '1' ? 'selected' : '' }}>Aktif</option>
                        <option value="0" {{ request('status') === '0' ? 'selected' : '' }}>Nonaktif</option>
                    </select>
                </div>
                <div class="col-md-4">
                    <label class="form-label">Cari</label>
                    <input type="text" name="search" class="form-control" placeholder="Nama, email, NIP..." value="{{ request('search') }}">
                </div>
                <div class="col-md-2 d-flex align-items-end gap-2">
                    <button type="submit" class="btn btn-primary">
                        <i class="bi bi-search"></i> Cari
                    </button>
                    <a href="{{ route('admin.users.admin') }}" class="btn btn-outline-secondary">
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
            <h5 class="mb-0"><i class="bi bi-shield-fill me-2"></i>Daftar Administrator</h5>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover mb-0">
                    <thead class="table-light">
                        <tr>
                            <th width="60">Foto</th>
                            <th>Nama</th>
                            <th>Email</th>
                            <th>NIP</th>
                            <th width="120">Role</th>
                            <th width="100">Status</th>
                            <th width="150">Terdaftar</th>
                            <th width="120">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($users as $user)
                            <tr>
                                <td>
                                    @if($user->foto)
                                        <img src="{{ asset('storage/' . $user->foto) }}" alt="Foto" class="rounded-circle" width="40" height="40" style="object-fit: cover;">
                                    @else
                                        <div class="rounded-circle bg-primary d-flex align-items-center justify-content-center text-white fw-bold" style="width: 40px; height: 40px;">
                                            {{ strtoupper(substr($user->name, 0, 1)) }}
                                        </div>
                                    @endif
                                </td>
                                <td>
                                    <div class="fw-medium">{{ $user->name }}</div>
                                    @if($user->jabatan)
                                        <small class="text-muted">{{ $user->jabatan }}</small>
                                    @endif
                                </td>
                                <td>{{ $user->email }}</td>
                                <td>{{ $user->nip ?? '-' }}</td>
                                <td>
                                    @if($user->role === 'super_admin')
                                        <span class="badge bg-warning text-dark">
                                            <i class="bi bi-shield-fill-check me-1"></i>Super Admin
                                        </span>
                                    @else
                                        <span class="badge bg-info">
                                            <i class="bi bi-person-badge me-1"></i>Admin
                                        </span>
                                    @endif
                                </td>
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
                                        <a href="{{ route('admin.users.admin.edit', $user->id) }}" class="btn btn-outline-primary" title="Edit">
                                            <i class="bi bi-pencil"></i>
                                        </a>
                                        @if($user->id !== auth('admin')->id())
                                            <button type="button" class="btn btn-outline-{{ $user->is_active ? 'warning' : 'success' }}" title="{{ $user->is_active ? 'Nonaktifkan' : 'Aktifkan' }}"
                                                onclick="confirmAction('toggle-admin-{{ $user->id }}', '{{ $user->is_active ? 'Nonaktifkan' : 'Aktifkan' }} Admin?', 'Admin {{ addslashes($user->name) }} akan di{{ $user->is_active ? 'nonaktifkan' : 'aktifkan' }}', 'question', 'Ya, Lanjutkan', '{{ $user->is_active ? '#f59e0b' : '#22c55e' }}')">
                                                <i class="bi bi-{{ $user->is_active ? 'pause' : 'play' }}"></i>
                                            </button>
                                            <form id="toggle-admin-{{ $user->id }}" action="{{ route('admin.users.toggle-status', $user->id) }}" method="POST" class="d-none">
                                                @csrf
                                                @method('PATCH')
                                            </form>
                                            <button type="button" class="btn btn-outline-danger" title="Hapus"
                                                onclick="confirmDelete('delete-admin-{{ $user->id }}', 'Hapus Admin?', 'Admin {{ addslashes($user->name) }} akan dihapus permanen!')">
                                                <i class="bi bi-trash"></i>
                                            </button>
                                            <form id="delete-admin-{{ $user->id }}" action="{{ route('admin.users.admin.destroy', $user->id) }}" method="POST" class="d-none">
                                                @csrf
                                                @method('DELETE')
                                            </form>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8" class="text-center py-5">
                                    <div class="text-muted">
                                        <i class="bi bi-inbox display-4 d-block mb-3"></i>
                                        <p>Belum ada data admin</p>
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

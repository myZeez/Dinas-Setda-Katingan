@extends('admin.layouts.app')

@section('title', 'Passport Monitor')

@section('content')
<div class="content-wrapper">
    <div class="page-header d-flex justify-content-between align-items-center">
        <div>
            <h1 class="page-title">Passport Monitor</h1>
            <p class="page-subtitle">Pantau dan kelola token autentikasi API</p>
        </div>
        <div>
            <form action="{{ route('admin.passport.revoke-expired') }}" method="POST" class="d-inline">
                @csrf
                <button type="submit" class="btn btn-warning me-2" onclick="return confirm('Cabut semua token yang sudah kadaluarsa?')">
                    <i class="bi bi-clock-history me-1"></i> Cabut Token Kadaluarsa
                </button>
            </form>
            <form action="{{ route('admin.passport.cleanup') }}" method="POST" class="d-inline">
                @csrf
                <button type="submit" class="btn btn-danger" onclick="return confirm('Hapus semua token yang sudah dicabut? Data ini tidak bisa dikembalikan!')">
                    <i class="bi bi-trash me-1"></i> Bersihkan Token
                </button>
            </form>
        </div>
    </div>

    @if(session('success'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
    @endif

    <!-- Stats Cards -->
    <div class="row mb-4">
        <div class="col-md-2">
            <div class="card stat-card">
                <div class="d-flex align-items-center">
                    <div class="stat-icon blue">
                        <i class="bi bi-key"></i>
                    </div>
                    <div class="ms-3">
                        <div class="stat-value">{{ number_format($stats['total_tokens']) }}</div>
                        <div class="stat-label">Total Token</div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-2">
            <div class="card stat-card">
                <div class="d-flex align-items-center">
                    <div class="stat-icon green">
                        <i class="bi bi-check-circle"></i>
                    </div>
                    <div class="ms-3">
                        <div class="stat-value">{{ number_format($stats['active_tokens']) }}</div>
                        <div class="stat-label">Token Aktif</div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-2">
            <div class="card stat-card">
                <div class="d-flex align-items-center">
                    <div class="stat-icon red">
                        <i class="bi bi-x-circle"></i>
                    </div>
                    <div class="ms-3">
                        <div class="stat-value">{{ number_format($stats['revoked_tokens']) }}</div>
                        <div class="stat-label">Token Dicabut</div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-2">
            <div class="card stat-card">
                <div class="d-flex align-items-center">
                    <div class="stat-icon orange">
                        <i class="bi bi-hourglass-split"></i>
                    </div>
                    <div class="ms-3">
                        <div class="stat-value">{{ number_format($stats['expired_tokens']) }}</div>
                        <div class="stat-label">Token Kadaluarsa</div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-2">
            <div class="card stat-card">
                <div class="d-flex align-items-center">
                    <div class="stat-icon purple">
                        <i class="bi bi-hdd-network"></i>
                    </div>
                    <div class="ms-3">
                        <div class="stat-value">{{ number_format($stats['total_clients']) }}</div>
                        <div class="stat-label">OAuth Clients</div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-2">
            <div class="card stat-card">
                <div class="d-flex align-items-center">
                    <div class="stat-icon cyan">
                        <i class="bi bi-person-badge"></i>
                    </div>
                    <div class="ms-3">
                        <div class="stat-value">{{ number_format($stats['personal_clients']) }}</div>
                        <div class="stat-label">Personal Client</div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <!-- Users with Tokens -->
        <div class="col-md-4 mb-4">
            <div class="card h-100">
                <div class="card-header">
                    <h5 class="mb-0"><i class="bi bi-people me-2"></i>Pengguna dengan Token Terbanyak</h5>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover mb-0">
                            <thead>
                                <tr>
                                    <th>Pengguna</th>
                                    <th class="text-center">Aktif</th>
                                    <th class="text-center">Total</th>
                                    <th></th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($usersWithTokens as $userToken)
                                <tr>
                                    <td>
                                        <div class="fw-semibold">{{ $userToken->name }}</div>
                                        <small class="text-muted">{{ $userToken->email }}</small>
                                        <span class="badge bg-{{ $userToken->role == 'super_admin' ? 'danger' : ($userToken->role == 'admin' ? 'primary' : 'success') }} ms-1">
                                            {{ ucfirst(str_replace('_', ' ', $userToken->role)) }}
                                        </span>
                                    </td>
                                    <td class="text-center">
                                        <span class="badge bg-success">{{ $userToken->active_tokens }}</span>
                                    </td>
                                    <td class="text-center">
                                        <span class="badge bg-secondary">{{ $userToken->total_tokens }}</span>
                                    </td>
                                    <td>
                                        <a href="{{ route('admin.passport.user-tokens', $userToken->id) }}" class="btn btn-sm btn-outline-primary" title="Lihat Detail">
                                            <i class="bi bi-eye"></i>
                                        </a>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="4" class="text-center text-muted py-4">
                                        Belum ada pengguna dengan token
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <!-- OAuth Clients -->
        <div class="col-md-8 mb-4">
            <div class="card h-100">
                <div class="card-header">
                    <h5 class="mb-0"><i class="bi bi-hdd-network me-2"></i>OAuth Clients</h5>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover mb-0">
                            <thead>
                                <tr>
                                    <th>Nama Client</th>
                                    <th>Tipe</th>
                                    <th>Redirect URI</th>
                                    <th>Dibuat</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($clients as $client)
                                <tr>
                                    <td>
                                        <div class="fw-semibold">{{ $client->name }}</div>
                                        <small class="text-muted font-monospace">{{ Str::limit($client->id, 20) }}</small>
                                    </td>
                                    <td>
                                        @php
                                            $grantTypes = $client->grant_types ?? '';
                                        @endphp
                                        @if(str_contains($grantTypes, 'personal_access'))
                                            <span class="badge bg-info">Personal Access</span>
                                        @elseif(str_contains($grantTypes, 'password'))
                                            <span class="badge bg-warning">Password Grant</span>
                                        @elseif(str_contains($grantTypes, 'authorization_code'))
                                            <span class="badge bg-primary">Authorization Code</span>
                                        @else
                                            <span class="badge bg-secondary">{{ $grantTypes ?: 'N/A' }}</span>
                                        @endif
                                    </td>
                                    <td>
                                        <small class="text-muted">{{ $client->redirect_uris ?: '-' }}</small>
                                    </td>
                                    <td>
                                        <small>{{ \Carbon\Carbon::parse($client->created_at)->format('d M Y H:i') }}</small>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="4" class="text-center text-muted py-4">
                                        Belum ada OAuth Client
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Filters -->
    <div class="card mb-4">
        <div class="card-body">
            <form action="{{ route('admin.passport.index') }}" method="GET" class="row g-3">
                <div class="col-md-3">
                    <label class="form-label">Status Token</label>
                    <select name="status" class="form-select">
                        <option value="">Semua Status</option>
                        <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>Aktif</option>
                        <option value="revoked" {{ request('status') == 'revoked' ? 'selected' : '' }}>Dicabut</option>
                        <option value="expired" {{ request('status') == 'expired' ? 'selected' : '' }}>Kadaluarsa</option>
                    </select>
                </div>
                <div class="col-md-3">
                    <label class="form-label">Role Pengguna</label>
                    <select name="role" class="form-select">
                        <option value="">Semua Role</option>
                        <option value="super_admin" {{ request('role') == 'super_admin' ? 'selected' : '' }}>Super Admin</option>
                        <option value="admin" {{ request('role') == 'admin' ? 'selected' : '' }}>Admin</option>
                        <option value="user" {{ request('role') == 'user' ? 'selected' : '' }}>User</option>
                    </select>
                </div>
                <div class="col-md-4">
                    <label class="form-label">Cari Pengguna</label>
                    <input type="text" name="search" class="form-control" placeholder="Nama atau email..." value="{{ request('search') }}">
                </div>
                <div class="col-md-2 d-flex align-items-end">
                    <button type="submit" class="btn btn-primary w-100">
                        <i class="bi bi-search me-1"></i> Filter
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Tokens Table -->
    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h5 class="mb-0"><i class="bi bi-key me-2"></i>Daftar Token</h5>
            <span class="badge bg-secondary">{{ $tokens->total() }} token</span>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover mb-0">
                    <thead>
                        <tr>
                            <th>Pengguna</th>
                            <th>Token Name</th>
                            <th>Status</th>
                            <th>Dibuat</th>
                            <th>Kadaluarsa</th>
                            <th class="text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($tokens as $token)
                        <tr>
                            <td>
                                <div class="fw-semibold">{{ $token->user_name }}</div>
                                <small class="text-muted">{{ $token->user_email }}</small>
                                <span class="badge bg-{{ $token->user_role == 'super_admin' ? 'danger' : ($token->user_role == 'admin' ? 'primary' : 'success') }} ms-1">
                                    {{ ucfirst(str_replace('_', ' ', $token->user_role)) }}
                                </span>
                            </td>
                            <td>
                                <span class="font-monospace">{{ $token->name ?: 'N/A' }}</span>
                            </td>
                            <td>
                                @if($token->revoked)
                                    <span class="badge bg-danger">Dicabut</span>
                                @elseif(\Carbon\Carbon::parse($token->expires_at)->isPast())
                                    <span class="badge bg-warning">Kadaluarsa</span>
                                @else
                                    <span class="badge bg-success">Aktif</span>
                                @endif
                            </td>
                            <td>
                                <small>{{ \Carbon\Carbon::parse($token->created_at)->format('d M Y H:i') }}</small>
                            </td>
                            <td>
                                <small>{{ \Carbon\Carbon::parse($token->expires_at)->format('d M Y H:i') }}</small>
                                <br>
                                <small class="text-muted">
                                    @if(\Carbon\Carbon::parse($token->expires_at)->isPast())
                                        {{ \Carbon\Carbon::parse($token->expires_at)->diffForHumans() }}
                                    @else
                                        {{ \Carbon\Carbon::parse($token->expires_at)->diffForHumans() }}
                                    @endif
                                </small>
                            </td>
                            <td class="text-center">
                                @if(!$token->revoked)
                                <form action="{{ route('admin.passport.revoke', $token->id) }}" method="POST" class="d-inline">
                                    @csrf
                                    <button type="submit" class="btn btn-sm btn-outline-danger" onclick="return confirm('Cabut token ini?')" title="Cabut Token">
                                        <i class="bi bi-x-circle"></i>
                                    </button>
                                </form>
                                @else
                                <span class="text-muted">-</span>
                                @endif
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6" class="text-center text-muted py-4">
                                <i class="bi bi-key display-6 d-block mb-2"></i>
                                Belum ada token yang dibuat
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
        @if($tokens->hasPages())
        <div class="card-footer">
            {{ $tokens->links() }}
        </div>
        @endif
    </div>
</div>

<style>
.stat-card {
    padding: 1.25rem;
    border-radius: 10px;
}
.stat-icon {
    width: 50px;
    height: 50px;
    border-radius: 10px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1.5rem;
}
.stat-icon.blue { background: rgba(13, 110, 253, 0.1); color: #0d6efd; }
.stat-icon.green { background: rgba(25, 135, 84, 0.1); color: #198754; }
.stat-icon.red { background: rgba(220, 53, 69, 0.1); color: #dc3545; }
.stat-icon.orange { background: rgba(253, 126, 20, 0.1); color: #fd7e14; }
.stat-icon.purple { background: rgba(111, 66, 193, 0.1); color: #6f42c1; }
.stat-icon.cyan { background: rgba(13, 202, 240, 0.1); color: #0dcaf0; }
.stat-value { font-size: 1.5rem; font-weight: 700; }
.stat-label { font-size: 0.8rem; color: #6c757d; }
.font-monospace { font-family: monospace; font-size: 0.85rem; }
</style>
@endsection

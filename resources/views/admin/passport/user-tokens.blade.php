@extends('admin.layouts.app')

@section('title', 'Token Pengguna - ' . $user->name)

@section('content')
<div class="content-wrapper">
    <div class="page-header d-flex justify-content-between align-items-center">
        <div>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-2">
                    <li class="breadcrumb-item"><a href="{{ route('admin.passport.index') }}">Passport Monitor</a></li>
                    <li class="breadcrumb-item active">Token Pengguna</li>
                </ol>
            </nav>
            <h1 class="page-title">Token untuk {{ $user->name }}</h1>
            <p class="page-subtitle">{{ $user->email }} - <span class="badge bg-{{ $user->role == 'super_admin' ? 'danger' : ($user->role == 'admin' ? 'primary' : 'success') }}">{{ ucfirst(str_replace('_', ' ', $user->role)) }}</span></p>
        </div>
        <div>
            <form action="{{ route('admin.passport.revoke-user', $user->id) }}" method="POST" class="d-inline">
                @csrf
                <button type="submit" class="btn btn-danger" onclick="return confirm('Cabut SEMUA token untuk pengguna ini?')">
                    <i class="bi bi-x-circle me-1"></i> Cabut Semua Token
                </button>
            </form>
            <a href="{{ route('admin.passport.index') }}" class="btn btn-outline-secondary ms-2">
                <i class="bi bi-arrow-left me-1"></i> Kembali
            </a>
        </div>
    </div>

    @if(session('success'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
    @endif

    <!-- User Info Card -->
    <div class="row mb-4">
        <div class="col-md-4">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="avatar-lg bg-primary text-white rounded-circle d-flex align-items-center justify-content-center me-3" style="width: 60px; height: 60px; font-size: 1.5rem;">
                            {{ strtoupper(substr($user->name, 0, 2)) }}
                        </div>
                        <div>
                            <h5 class="mb-1">{{ $user->name }}</h5>
                            <p class="text-muted mb-0">{{ $user->email }}</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-8">
            <div class="row h-100">
                <div class="col-md-4">
                    <div class="card h-100">
                        <div class="card-body d-flex align-items-center">
                            <div class="stat-icon blue me-3">
                                <i class="bi bi-key"></i>
                            </div>
                            <div>
                                <div class="stat-value">{{ $tokens->count() }}</div>
                                <div class="stat-label">Total Token</div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card h-100">
                        <div class="card-body d-flex align-items-center">
                            <div class="stat-icon green me-3">
                                <i class="bi bi-check-circle"></i>
                            </div>
                            <div>
                                <div class="stat-value">{{ $tokens->where('revoked', false)->filter(function($t) { return \Carbon\Carbon::parse($t->expires_at)->isFuture(); })->count() }}</div>
                                <div class="stat-label">Token Aktif</div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card h-100">
                        <div class="card-body d-flex align-items-center">
                            <div class="stat-icon red me-3">
                                <i class="bi bi-x-circle"></i>
                            </div>
                            <div>
                                <div class="stat-value">{{ $tokens->where('revoked', true)->count() }}</div>
                                <div class="stat-label">Token Dicabut</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Tokens Table -->
    <div class="card">
        <div class="card-header">
            <h5 class="mb-0"><i class="bi bi-key me-2"></i>Daftar Token</h5>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover mb-0">
                    <thead>
                        <tr>
                            <th>Token ID</th>
                            <th>Nama Token</th>
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
                                <span class="font-monospace text-muted">{{ Str::limit($token->id, 25) }}</span>
                            </td>
                            <td>
                                <span class="fw-semibold">{{ $token->name ?: 'N/A' }}</span>
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
                                {{ \Carbon\Carbon::parse($token->created_at)->format('d M Y H:i') }}
                                <br>
                                <small class="text-muted">{{ \Carbon\Carbon::parse($token->created_at)->diffForHumans() }}</small>
                            </td>
                            <td>
                                {{ \Carbon\Carbon::parse($token->expires_at)->format('d M Y H:i') }}
                                <br>
                                <small class="text-muted">{{ \Carbon\Carbon::parse($token->expires_at)->diffForHumans() }}</small>
                            </td>
                            <td class="text-center">
                                @if(!$token->revoked)
                                <form action="{{ route('admin.passport.revoke', $token->id) }}" method="POST" class="d-inline">
                                    @csrf
                                    <button type="submit" class="btn btn-sm btn-outline-danger" onclick="return confirm('Cabut token ini?')" title="Cabut Token">
                                        <i class="bi bi-x-circle"></i> Cabut
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
                                Pengguna ini belum memiliki token
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<style>
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
.stat-value { font-size: 1.5rem; font-weight: 700; }
.stat-label { font-size: 0.8rem; color: #6c757d; }
.font-monospace { font-family: monospace; font-size: 0.85rem; }
</style>
@endsection

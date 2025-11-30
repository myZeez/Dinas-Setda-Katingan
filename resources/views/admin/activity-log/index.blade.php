@extends('admin.layouts.app')

@section('title', 'Log Aktivitas')

@section('content')
<div class="content-wrapper">
    <div class="page-header d-flex justify-content-between align-items-center">
        <div>
            <h1 class="page-title">Log Aktivitas</h1>
            <p class="page-subtitle">Pantau semua aktivitas pengguna dalam sistem</p>
        </div>
    </div>

    <!-- Stats Cards -->
    <div class="row mb-4">
        <div class="col-md-3">
            <div class="card stat-card">
                <div class="d-flex align-items-center">
                    <div class="stat-icon blue">
                        <i class="bi bi-activity"></i>
                    </div>
                    <div class="ms-3">
                        <div class="stat-value">{{ number_format($stats['total']) }}</div>
                        <div class="stat-label">Total Log</div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card stat-card">
                <div class="d-flex align-items-center">
                    <div class="stat-icon green">
                        <i class="bi bi-calendar-check"></i>
                    </div>
                    <div class="ms-3">
                        <div class="stat-value">{{ number_format($stats['today']) }}</div>
                        <div class="stat-label">Aktivitas Hari Ini</div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card stat-card">
                <div class="d-flex align-items-center">
                    <div class="stat-icon orange">
                        <i class="bi bi-box-arrow-in-right"></i>
                    </div>
                    <div class="ms-3">
                        <div class="stat-value">{{ number_format($stats['logins']) }}</div>
                        <div class="stat-label">Login Hari Ini</div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card stat-card">
                <div class="d-flex align-items-center">
                    <div class="stat-icon red">
                        <i class="bi bi-pencil-square"></i>
                    </div>
                    <div class="ms-3">
                        <div class="stat-value">{{ number_format($stats['changes']) }}</div>
                        <div class="stat-label">Perubahan Hari Ini</div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Filters -->
    <div class="card mb-4">
        <div class="card-body">
            <form action="{{ route('admin.activity-log.index') }}" method="GET" class="row g-3">
                <div class="col-md-2">
                    <label class="form-label">Aksi</label>
                    <select name="action" class="form-select">
                        <option value="">Semua Aksi</option>
                        @foreach($actions as $action)
                            <option value="{{ $action }}" {{ request('action') == $action ? 'selected' : '' }}>
                                {{ ucfirst($action) }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-2">
                    <label class="form-label">Modul</label>
                    <select name="module" class="form-select">
                        <option value="">Semua Modul</option>
                        @foreach($modules as $module)
                            <option value="{{ $module }}" {{ request('module') == $module ? 'selected' : '' }}>
                                {{ ucfirst(str_replace('_', ' ', $module)) }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-2">
                    <label class="form-label">Dari Tanggal</label>
                    <input type="date" name="date_from" class="form-control" value="{{ request('date_from') }}">
                </div>
                <div class="col-md-2">
                    <label class="form-label">Sampai Tanggal</label>
                    <input type="date" name="date_to" class="form-control" value="{{ request('date_to') }}">
                </div>
                <div class="col-md-2">
                    <label class="form-label">Cari</label>
                    <input type="text" name="search" class="form-control" placeholder="Nama, deskripsi..." value="{{ request('search') }}">
                </div>
                <div class="col-md-2 d-flex align-items-end gap-2">
                    <button type="submit" class="btn btn-primary">
                        <i class="bi bi-search"></i> Filter
                    </button>
                    <a href="{{ route('admin.activity-log.index') }}" class="btn btn-outline-secondary">
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

    <!-- Logs Table -->
    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h5 class="mb-0"><i class="bi bi-clock-history me-2"></i>Riwayat Aktivitas</h5>
            <button type="button" class="btn btn-outline-danger btn-sm" data-bs-toggle="modal" data-bs-target="#clearModal">
                <i class="bi bi-trash me-1"></i> Bersihkan Log Lama
            </button>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover mb-0">
                    <thead class="table-light">
                        <tr>
                            <th width="160">Waktu</th>
                            <th width="150">Pengguna</th>
                            <th width="100">Aksi</th>
                            <th width="120">Modul</th>
                            <th>Deskripsi</th>
                            <th width="120">IP Address</th>
                            <th width="80">Detail</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($logs as $log)
                            <tr>
                                <td>
                                    <small class="text-muted">{{ $log->created_at->format('d/m/Y') }}</small><br>
                                    <span>{{ $log->created_at->format('H:i:s') }}</span>
                                </td>
                                <td>
                                    <div class="fw-medium">{{ $log->user_name }}</div>
                                    <small class="text-muted">{{ ucfirst(str_replace('_', ' ', $log->user_role)) }}</small>
                                </td>
                                <td>
                                    <span class="badge bg-{{ $log->action_color }}">
                                        {{ $log->action_label }}
                                    </span>
                                </td>
                                <td>{{ $log->module_label }}</td>
                                <td>
                                    <span class="text-truncate d-inline-block" style="max-width: 300px;" title="{{ $log->description }}">
                                        {{ $log->description }}
                                    </span>
                                </td>
                                <td><code>{{ $log->ip_address }}</code></td>
                                <td>
                                    <button type="button" class="btn btn-sm btn-outline-primary"
                                            data-bs-toggle="modal"
                                            data-bs-target="#detailModal{{ $log->id }}">
                                        <i class="bi bi-eye"></i>
                                    </button>
                                </td>
                            </tr>

                            <!-- Detail Modal -->
                            <div class="modal fade" id="detailModal{{ $log->id }}" tabindex="-1">
                                <div class="modal-dialog modal-lg">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title">Detail Log Aktivitas</h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                        </div>
                                        <div class="modal-body">
                                            <div class="row mb-3">
                                                <div class="col-md-6">
                                                    <label class="form-label text-muted">Waktu</label>
                                                    <p class="fw-medium">{{ $log->created_at->format('d F Y, H:i:s') }}</p>
                                                </div>
                                                <div class="col-md-6">
                                                    <label class="form-label text-muted">Pengguna</label>
                                                    <p class="fw-medium">{{ $log->user_name }} ({{ ucfirst(str_replace('_', ' ', $log->user_role)) }})</p>
                                                </div>
                                            </div>
                                            <div class="row mb-3">
                                                <div class="col-md-6">
                                                    <label class="form-label text-muted">Aksi</label>
                                                    <p><span class="badge bg-{{ $log->action_color }}">{{ $log->action_label }}</span></p>
                                                </div>
                                                <div class="col-md-6">
                                                    <label class="form-label text-muted">Modul</label>
                                                    <p class="fw-medium">{{ $log->module_label }}</p>
                                                </div>
                                            </div>
                                            <div class="mb-3">
                                                <label class="form-label text-muted">Deskripsi</label>
                                                <p class="fw-medium">{{ $log->description }}</p>
                                            </div>
                                            <div class="row mb-3">
                                                <div class="col-md-6">
                                                    <label class="form-label text-muted">IP Address</label>
                                                    <p><code>{{ $log->ip_address }}</code></p>
                                                </div>
                                                <div class="col-md-6">
                                                    <label class="form-label text-muted">User Agent</label>
                                                    <p class="small text-muted">{{ Str::limit($log->user_agent, 100) }}</p>
                                                </div>
                                            </div>
                                            @if($log->old_data || $log->new_data)
                                                <div class="row">
                                                    @if($log->old_data)
                                                        <div class="col-md-6">
                                                            <label class="form-label text-muted">Data Lama</label>
                                                            <pre class="bg-light p-2 rounded small" style="max-height: 200px; overflow: auto;">{{ json_encode($log->old_data, JSON_PRETTY_PRINT) }}</pre>
                                                        </div>
                                                    @endif
                                                    @if($log->new_data)
                                                        <div class="col-md-6">
                                                            <label class="form-label text-muted">Data Baru</label>
                                                            <pre class="bg-light p-2 rounded small" style="max-height: 200px; overflow: auto;">{{ json_encode($log->new_data, JSON_PRETTY_PRINT) }}</pre>
                                                        </div>
                                                    @endif
                                                </div>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @empty
                            <tr>
                                <td colspan="7" class="text-center py-5">
                                    <div class="text-muted">
                                        <i class="bi bi-inbox display-4 d-block mb-3"></i>
                                        <p>Belum ada log aktivitas</p>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
        @if($logs->hasPages())
            <div class="card-footer">
                {{ $logs->withQueryString()->links() }}
            </div>
        @endif
    </div>
</div>

<!-- Clear Modal -->
<div class="modal fade" id="clearModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <form action="{{ route('admin.activity-log.clear') }}" method="POST">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title">Bersihkan Log Lama</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <p>Hapus log aktivitas yang lebih lama dari:</p>
                    <select name="days" class="form-select">
                        <option value="7">7 hari yang lalu</option>
                        <option value="14">14 hari yang lalu</option>
                        <option value="30" selected>30 hari yang lalu</option>
                        <option value="60">60 hari yang lalu</option>
                        <option value="90">90 hari yang lalu</option>
                    </select>
                    <div class="alert alert-warning mt-3 mb-0">
                        <i class="bi bi-exclamation-triangle me-1"></i>
                        Data yang dihapus tidak dapat dikembalikan.
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-danger">
                        <i class="bi bi-trash me-1"></i> Hapus Log
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

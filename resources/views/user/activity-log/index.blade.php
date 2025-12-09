@extends('user.layouts.app')

@section('title', 'Riwayat Aktivitas Saya')

@section('content')
<div class="content-wrapper">
    <div class="page-header d-flex justify-content-between align-items-center">
        <div>
            <h1 class="page-title">Riwayat Aktivitas Saya</h1>
            <p class="page-subtitle">Pantau semua aktivitas Anda dalam sistem</p>
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
                        <div class="stat-label">Total Aktivitas</div>
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
                    <div class="stat-icon purple">
                        <i class="bi bi-pencil-square"></i>
                    </div>
                    <div class="ms-3">
                        <div class="stat-value">{{ number_format($stats['activities']) }}</div>
                        <div class="stat-label">Aktivitas Lainnya Hari Ini</div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Filters -->
    <div class="card mb-4">
        <div class="card-body">
            <form action="{{ route('user.activity-log.index') }}" method="GET" class="row g-3">
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
                    <input type="text" name="search" class="form-control" placeholder="Deskripsi, IP..." value="{{ request('search') }}">
                </div>
                <div class="col-md-2 d-flex align-items-end gap-2">
                    <button type="submit" class="btn btn-primary">
                        <i class="bi bi-search"></i> Filter
                    </button>
                    <a href="{{ route('user.activity-log.index') }}" class="btn btn-outline-secondary">
                        <i class="bi bi-x-lg"></i>
                    </a>
                </div>
            </form>
        </div>
    </div>

    <!-- Logs Table -->
    <div class="card">
        <div class="card-header">
            <h5 class="mb-0"><i class="bi bi-clock-history me-2"></i>Riwayat Aktivitas</h5>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover mb-0">
                    <thead class="table-light">
                        <tr>
                            <th width="160">Waktu</th>
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
                                    <span class="badge bg-{{ $log->action_color }}">
                                        {{ $log->action_label }}
                                    </span>
                                </td>
                                <td>{{ $log->module_label }}</td>
                                <td>
                                    <span class="text-truncate d-inline-block" style="max-width: 350px;" title="{{ $log->description }}">
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
                                            <h5 class="modal-title">Detail Aktivitas</h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                        </div>
                                        <div class="modal-body">
                                            <div class="row mb-3">
                                                <div class="col-md-6">
                                                    <label class="form-label text-muted">Waktu</label>
                                                    <p class="fw-medium">{{ $log->created_at->format('d F Y, H:i:s') }}</p>
                                                </div>
                                                <div class="col-md-6">
                                                    <label class="form-label text-muted">Aksi</label>
                                                    <p><span class="badge bg-{{ $log->action_color }}">{{ $log->action_label }}</span></p>
                                                </div>
                                            </div>
                                            <div class="row mb-3">
                                                <div class="col-md-6">
                                                    <label class="form-label text-muted">Modul</label>
                                                    <p class="fw-medium">{{ $log->module_label }}</p>
                                                </div>
                                                <div class="col-md-6">
                                                    <label class="form-label text-muted">IP Address</label>
                                                    <p class="fw-medium"><code>{{ $log->ip_address }}</code></p>
                                                </div>
                                            </div>
                                            <div class="row mb-3">
                                                <div class="col-12">
                                                    <label class="form-label text-muted">Deskripsi</label>
                                                    <p class="fw-medium">{{ $log->description }}</p>
                                                </div>
                                            </div>
                                            <div class="row mb-3">
                                                <div class="col-12">
                                                    <label class="form-label text-muted">User Agent</label>
                                                    <p class="text-muted small">{{ $log->user_agent }}</p>
                                                </div>
                                            </div>
                                            @if($log->old_data || $log->new_data)
                                                <div class="row">
                                                    @if($log->old_data)
                                                        <div class="col-md-6">
                                                            <label class="form-label text-muted">Data Lama</label>
                                                            <pre class="bg-light p-2 rounded small">{{ json_encode($log->old_data, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE) }}</pre>
                                                        </div>
                                                    @endif
                                                    @if($log->new_data)
                                                        <div class="col-md-6">
                                                            <label class="form-label text-muted">Data Baru</label>
                                                            <pre class="bg-light p-2 rounded small">{{ json_encode($log->new_data, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE) }}</pre>
                                                        </div>
                                                    @endif
                                                </div>
                                            @endif
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center py-4">
                                    <i class="bi bi-inbox text-muted" style="font-size: 3rem;"></i>
                                    <p class="text-muted mt-2">Belum ada aktivitas</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
        @if($logs->hasPages())
            <div class="card-footer">
                {{ $logs->links() }}
            </div>
        @endif
    </div>
</div>

<style>
.stat-card {
    border: none;
    box-shadow: 0 2px 4px rgba(0,0,0,0.05);
    padding: 1.25rem;
    border-radius: 8px;
    transition: all 0.3s;
}

.stat-card:hover {
    box-shadow: 0 4px 8px rgba(0,0,0,0.1);
    transform: translateY(-2px);
}

.stat-icon {
    width: 48px;
    height: 48px;
    border-radius: 8px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1.5rem;
    color: white;
}

.stat-icon.blue {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
}

.stat-icon.green {
    background: linear-gradient(135deg, #84fab0 0%, #8fd3f4 100%);
}

.stat-icon.orange {
    background: linear-gradient(135deg, #fa709a 0%, #fee140 100%);
}

.stat-icon.purple {
    background: linear-gradient(135deg, #a8edea 0%, #fed6e3 100%);
}

.stat-value {
    font-size: 1.75rem;
    font-weight: 700;
    color: #2d3748;
    line-height: 1;
}

.stat-label {
    font-size: 0.875rem;
    color: #718096;
    margin-top: 0.25rem;
}
</style>
@endsection

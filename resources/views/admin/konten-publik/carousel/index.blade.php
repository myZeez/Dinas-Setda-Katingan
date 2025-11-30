@extends('admin.layouts.app')

@section('title', 'Kelola Carousel')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h1 class="h3 mb-1">Kelola Carousel</h1>
        <p class="text-muted mb-0">Atur gambar slider untuk halaman utama website</p>
    </div>
    <a href="{{ route('admin.konten-publik.carousel.create') }}" class="btn btn-primary">
        <i class="bi bi-plus-lg me-1"></i> Tambah Carousel
    </a>
</div>

@if(session('success'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        <i class="bi bi-check-circle me-2"></i>{{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
@endif

<div class="card border-0 shadow-sm">
    <div class="card-body p-0">
        @if($carousels->count() > 0)
            <!-- Preview Stack Carousel -->
            <div class="p-4 bg-light border-bottom">
                <h6 class="text-muted mb-3">
                    <i class="bi bi-eye me-1"></i> Preview Carousel ({{ $carousels->where('is_active', true)->count() }} aktif)
                </h6>
                <div class="carousel-stack-preview">
                    @foreach($carousels->where('is_active', true)->take(5) as $index => $carousel)
                        <div class="stack-card" style="--index: {{ $index }}; --total: {{ min($carousels->where('is_active', true)->count(), 5) }};">
                            <img src="{{ asset('storage/' . $carousel->gambar) }}" alt="{{ $carousel->judul }}">
                            <div class="stack-overlay">
                                <span class="stack-title">{{ $carousel->judul }}</span>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>

            <!-- Carousel List -->
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="bg-light">
                        <tr>
                            <th width="60" class="text-center">#</th>
                            <th width="120">Gambar</th>
                            <th>Judul</th>
                            <th width="100" class="text-center">Urutan</th>
                            <th width="100" class="text-center">Status</th>
                            <th width="150" class="text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody id="sortable-carousel">
                        @foreach($carousels as $carousel)
                            <tr data-id="{{ $carousel->id }}">
                                <td class="text-center">
                                    <i class="bi bi-grip-vertical text-muted drag-handle" style="cursor: grab;"></i>
                                </td>
                                <td>
                                    <img src="{{ asset('storage/' . $carousel->gambar) }}"
                                         alt="{{ $carousel->judul }}"
                                         class="rounded"
                                         style="width: 100px; height: 60px; object-fit: cover;">
                                </td>
                                <td>
                                    <h6 class="mb-1">{{ $carousel->judul }}</h6>
                                    @if($carousel->deskripsi)
                                        <small class="text-muted">{{ Str::limit($carousel->deskripsi, 50) }}</small>
                                    @endif
                                </td>
                                <td class="text-center">
                                    <span class="badge bg-secondary">{{ $carousel->urutan }}</span>
                                </td>
                                <td class="text-center">
                                    @if($carousel->is_active)
                                        <span class="badge bg-success">Aktif</span>
                                    @else
                                        <span class="badge bg-secondary">Nonaktif</span>
                                    @endif
                                </td>
                                <td class="text-center">
                                    <div class="btn-group btn-group-sm">
                                        <a href="{{ route('admin.konten-publik.carousel.edit', $carousel->id) }}"
                                           class="btn btn-outline-primary" title="Edit">
                                            <i class="bi bi-pencil"></i>
                                        </a>
                                        <button type="button" class="btn btn-outline-danger"
                                                onclick="confirmDelete({{ $carousel->id }})" title="Hapus">
                                            <i class="bi bi-trash"></i>
                                        </button>
                                    </div>
                                    <form id="delete-form-{{ $carousel->id }}"
                                          action="{{ route('admin.konten-publik.carousel.destroy', $carousel->id) }}"
                                          method="POST" class="d-none">
                                        @csrf
                                        @method('DELETE')
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @else
            <div class="text-center py-5">
                <i class="bi bi-images text-muted" style="font-size: 64px;"></i>
                <h5 class="mt-3">Belum Ada Carousel</h5>
                <p class="text-muted">Tambahkan carousel untuk ditampilkan di halaman utama</p>
                <a href="{{ route('admin.konten-publik.carousel.create') }}" class="btn btn-primary">
                    <i class="bi bi-plus-lg me-1"></i> Tambah Carousel Pertama
                </a>
            </div>
        @endif
    </div>
</div>

<style>
    .carousel-stack-preview {
        position: relative;
        width: 100%;
        max-width: 600px;
        height: 300px;
        margin: 0 auto;
        perspective: 1000px;
    }

    .stack-card {
        position: absolute;
        width: 100%;
        height: 100%;
        border-radius: 16px;
        overflow: hidden;
        box-shadow: 0 10px 40px rgba(0,0,0,0.2);
        transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
        transform-origin: center bottom;
        --offset: calc(var(--index) * 15px);
        --scale: calc(1 - (var(--index) * 0.05));
        --rotate: calc(var(--index) * -2deg);
        transform: translateY(calc(var(--offset) * -1)) scale(var(--scale)) rotateX(var(--rotate));
        z-index: calc(var(--total) - var(--index));
    }

    .stack-card:hover {
        transform: translateY(calc(var(--offset) * -1 - 10px)) scale(calc(var(--scale) + 0.02)) rotateX(0deg);
    }

    .stack-card img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }

    .stack-overlay {
        position: absolute;
        bottom: 0;
        left: 0;
        right: 0;
        padding: 20px;
        background: linear-gradient(transparent, rgba(0,0,0,0.8));
    }

    .stack-title {
        color: white;
        font-weight: 600;
        font-size: 14px;
    }

    .drag-handle:hover {
        color: var(--bs-primary) !important;
    }
</style>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/sortablejs@1.15.0/Sortable.min.js"></script>
<script>
    function confirmDelete(id) {
        Swal.fire({
            title: 'Hapus Carousel?',
            text: 'Carousel ini akan dihapus permanen!',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#ef4444',
            cancelButtonColor: '#64748b',
            confirmButtonText: '<i class="bi bi-trash me-1"></i> Ya, Hapus!',
            cancelButtonText: 'Batal',
            reverseButtons: true
        }).then((result) => {
            if (result.isConfirmed) {
                document.getElementById('delete-form-' + id).submit();
            }
        });
    }

    // Sortable for reordering
    const sortable = new Sortable(document.getElementById('sortable-carousel'), {
        handle: '.drag-handle',
        animation: 150,
        onEnd: function(evt) {
            const orders = [];
            document.querySelectorAll('#sortable-carousel tr').forEach((row, index) => {
                orders.push({
                    id: row.dataset.id,
                    urutan: index + 1
                });
            });

            fetch('{{ route("admin.konten-publik.carousel.update-order") }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify({ orders: orders })
            }).then(response => {
                if (response.ok) {
                    // Update urutan badges
                    document.querySelectorAll('#sortable-carousel tr').forEach((row, index) => {
                        row.querySelector('.badge.bg-secondary').textContent = index + 1;
                    });
                }
            });
        }
    });
</script>
@endpush

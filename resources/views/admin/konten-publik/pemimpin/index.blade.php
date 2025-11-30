@extends('admin.layouts.app')

@section('title', 'Kelola Pemimpin Daerah')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h1 class="h3 mb-1">Kelola Pemimpin Daerah</h1>
        <p class="text-muted mb-0">Atur posisi tampilan di halaman publik dengan drag & drop</p>
    </div>
    <div class="d-flex gap-2">
        <button type="button" class="btn btn-outline-secondary" id="resetPositions">
            <i class="bi bi-arrow-counterclockwise me-1"></i> Reset Posisi
        </button>
        <a href="{{ route('admin.konten-publik.pemimpin.create') }}" class="btn btn-primary">
            <i class="bi bi-plus-lg me-1"></i> Tambah Pemimpin
        </a>
    </div>
</div>

@if(session('success'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        <i class="bi bi-check-circle me-2"></i>{{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
@endif

<div class="alert alert-info alert-dismissible fade show" role="alert">
    <i class="bi bi-arrows-move me-2"></i>
    <strong>Tips:</strong> Drag & drop card ke slot manapun dalam grid 5x3. Posisi akan tersimpan otomatis dan tampil sama di halaman publik.
    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
</div>

<div class="card border-0 shadow-sm">
    <div class="card-header bg-white py-3">
        <div class="d-flex justify-content-between align-items-center">
            <span class="text-muted"><i class="bi bi-grid-3x3 me-2"></i>Grid Layout 5x3 (15 slot)</span>
            <span class="badge bg-primary">{{ $pemimpins->count() }} Pemimpin Aktif</span>
        </div>
    </div>
    <div class="card-body p-4">
        <div class="grid-container" id="gridContainer">
            @php
                // Create position map from database
                $positionMap = [];
                foreach($pemimpins as $p) {
                    $pos = $p->grid_position ?? $p->urutan;
                    $positionMap[$pos] = $p;
                }
            @endphp

            @for($i = 1; $i <= 15; $i++)
                <div class="grid-slot" data-position="{{ $i }}">
                    <span class="slot-number">{{ $i }}</span>
                    @if(isset($positionMap[$i]))
                        @php $pemimpin = $positionMap[$i]; @endphp
                        <div class="pemimpin-card" data-id="{{ $pemimpin->id }}" draggable="true">
                            <div class="card-drag-handle">
                                <i class="bi bi-grip-vertical"></i>
                            </div>
                            <div class="pemimpin-photo">
                                @if($pemimpin->foto)
                                    <img src="{{ asset('storage/' . $pemimpin->foto) }}" alt="{{ $pemimpin->nama }}">
                                @else
                                    <img src="https://ui-avatars.com/api/?name={{ urlencode($pemimpin->nama) }}&size=200&background=dc2626&color=fff" alt="{{ $pemimpin->nama }}">
                                @endif
                                @if(!$pemimpin->is_active)
                                    <span class="status-badge">OFF</span>
                                @endif
                            </div>
                            <div class="pemimpin-info">
                                <h6 class="pemimpin-name">{{ $pemimpin->nama }}</h6>
                                <p class="pemimpin-jabatan">{{ $pemimpin->jabatan }}</p>
                            </div>
                            <div class="pemimpin-actions">
                                <a href="{{ route('admin.konten-publik.pemimpin.edit', $pemimpin->id) }}"
                                   class="btn btn-sm btn-light" title="Edit">
                                    <i class="bi bi-pencil"></i>
                                </a>
                                <button type="button" class="btn btn-sm btn-light text-danger"
                                        onclick="confirmDelete({{ $pemimpin->id }})" title="Hapus">
                                    <i class="bi bi-trash"></i>
                                </button>
                            </div>
                            <form id="delete-form-{{ $pemimpin->id }}"
                                  action="{{ route('admin.konten-publik.pemimpin.destroy', $pemimpin->id) }}"
                                  method="POST" class="d-none">
                                @csrf
                                @method('DELETE')
                            </form>
                        </div>
                    @endif
                </div>
            @endfor
        </div>

        @if($pemimpins->count() == 0)
        <div class="text-center py-5">
            <i class="bi bi-person-badge text-muted" style="font-size: 64px;"></i>
            <h5 class="mt-3">Belum Ada Pemimpin Daerah</h5>
            <p class="text-muted">Tambahkan informasi pemimpin daerah untuk ditampilkan</p>
            <a href="{{ route('admin.konten-publik.pemimpin.create') }}" class="btn btn-primary">
                <i class="bi bi-plus-lg me-1"></i> Tambah Pemimpin Pertama
            </a>
        </div>
        @endif
    </div>
</div>

<!-- Preview Section -->
<div class="card border-0 shadow-sm mt-4">
    <div class="card-header bg-white py-3">
        <i class="bi bi-eye me-2"></i>Preview Tampilan di Halaman Publik
    </div>
    <div class="card-body bg-light">
        <div class="preview-grid" id="previewGrid">
            @for($i = 1; $i <= 15; $i++)
                <div class="preview-slot" data-position="{{ $i }}">
                    @if(isset($positionMap[$i]))
                        @php $pemimpin = $positionMap[$i]; @endphp
                        <div class="preview-card">
                            <div class="preview-photo">
                                @if($pemimpin->foto)
                                    <img src="{{ asset('storage/' . $pemimpin->foto) }}" alt="{{ $pemimpin->nama }}">
                                @else
                                    <img src="https://ui-avatars.com/api/?name={{ urlencode($pemimpin->nama) }}&size=200&background=dc2626&color=fff" alt="{{ $pemimpin->nama }}">
                                @endif
                            </div>
                            <h6 class="preview-name">{{ $pemimpin->nama }}</h6>
                            <small class="preview-jabatan">{{ $pemimpin->jabatan }}</small>
                        </div>
                    @endif
                </div>
            @endfor
        </div>
    </div>
</div>

<!-- Toast Notification -->
<div class="position-fixed bottom-0 end-0 p-3" style="z-index: 1100">
    <div id="saveToast" class="toast align-items-center text-bg-success border-0" role="alert">
        <div class="d-flex">
            <div class="toast-body">
                <i class="bi bi-check-circle me-2"></i>Posisi berhasil disimpan!
            </div>
            <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast"></button>
        </div>
    </div>
</div>

<style>
    .grid-container {
        display: grid;
        grid-template-columns: repeat(5, 1fr);
        gap: 16px;
        padding: 10px;
    }

    .grid-slot {
        position: relative;
        aspect-ratio: 3/4;
        background: #f8f9fa;
        border: 2px dashed #dee2e6;
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        transition: all 0.3s ease;
        min-height: 200px;
    }

    .grid-slot:hover {
        border-color: #0d6efd;
        background: #e7f1ff;
    }

    .grid-slot.drag-over {
        border-color: #0d6efd;
        background: #cfe2ff;
        transform: scale(1.02);
    }

    .slot-number {
        position: absolute;
        top: 8px;
        left: 8px;
        width: 24px;
        height: 24px;
        background: #dee2e6;
        color: #6c757d;
        border-radius: 50%;
        font-size: 11px;
        font-weight: 600;
        display: flex;
        align-items: center;
        justify-content: center;
        z-index: 1;
    }

    .grid-slot:has(.pemimpin-card) .slot-number {
        background: #0d6efd;
        color: white;
    }

    .pemimpin-card {
        position: absolute;
        top: 8px;
        left: 8px;
        right: 8px;
        bottom: 8px;
        background: white;
        border-radius: 10px;
        box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        cursor: grab;
        display: flex;
        flex-direction: column;
        align-items: center;
        padding: 12px 8px;
        transition: all 0.2s ease;
        overflow: hidden;
    }

    .pemimpin-card:hover {
        box-shadow: 0 8px 25px rgba(0,0,0,0.15);
        transform: translateY(-2px);
    }

    .pemimpin-card.dragging {
        opacity: 0.5;
        cursor: grabbing;
    }

    .pemimpin-card.drag-ghost {
        opacity: 0.8;
        transform: rotate(3deg) scale(1.05);
        box-shadow: 0 15px 40px rgba(0,0,0,0.2);
    }

    .card-drag-handle {
        position: absolute;
        top: 4px;
        right: 4px;
        color: #ccc;
        font-size: 14px;
        opacity: 0;
        transition: opacity 0.2s;
    }

    .pemimpin-card:hover .card-drag-handle {
        opacity: 1;
    }

    .pemimpin-photo {
        position: relative;
        width: 70px;
        height: 70px;
        margin-bottom: 8px;
        flex-shrink: 0;
    }

    .pemimpin-photo img {
        width: 100%;
        height: 100%;
        border-radius: 50%;
        object-fit: cover;
        border: 3px solid #f0f0f0;
    }

    .status-badge {
        position: absolute;
        bottom: 0;
        right: 0;
        background: #dc3545;
        color: white;
        font-size: 8px;
        padding: 2px 5px;
        border-radius: 8px;
    }

    .pemimpin-info {
        text-align: center;
        flex-grow: 1;
        overflow: hidden;
        width: 100%;
    }

    .pemimpin-name {
        font-size: 12px;
        font-weight: 600;
        margin-bottom: 2px;
        color: #333;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
    }

    .pemimpin-jabatan {
        font-size: 10px;
        color: #dc3545;
        margin: 0;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
    }

    .pemimpin-actions {
        display: flex;
        gap: 4px;
        margin-top: 8px;
    }

    .pemimpin-actions .btn {
        padding: 4px 8px;
        font-size: 11px;
    }

    /* Preview Grid */
    .preview-grid {
        display: grid;
        grid-template-columns: repeat(5, 1fr);
        gap: 20px;
        padding: 20px;
    }

    .preview-slot {
        min-height: 120px;
    }

    .preview-card {
        text-align: center;
        padding: 15px 10px;
    }

    .preview-photo {
        width: 80px;
        height: 80px;
        margin: 0 auto 10px;
        border-radius: 50%;
        overflow: hidden;
        background: #dc3545;
        padding: 3px;
    }

    .preview-photo img {
        width: 100%;
        height: 100%;
        border-radius: 50%;
        object-fit: cover;
        filter: grayscale(100%);
    }

    .preview-name {
        font-size: 12px;
        font-weight: 600;
        margin-bottom: 2px;
    }

    .preview-jabatan {
        font-size: 10px;
        color: #666;
    }

    /* Responsive */
    @media (max-width: 1200px) {
        .grid-container, .preview-grid {
            grid-template-columns: repeat(4, 1fr);
        }
    }

    @media (max-width: 992px) {
        .grid-container, .preview-grid {
            grid-template-columns: repeat(3, 1fr);
        }
    }

    @media (max-width: 768px) {
        .grid-container, .preview-grid {
            grid-template-columns: repeat(2, 1fr);
        }
    }
</style>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const gridContainer = document.getElementById('gridContainer');
    const slots = document.querySelectorAll('.grid-slot');
    let draggedCard = null;
    let sourceSlot = null;

    // Make cards draggable
    document.querySelectorAll('.pemimpin-card').forEach(card => {
        card.addEventListener('dragstart', handleDragStart);
        card.addEventListener('dragend', handleDragEnd);
    });

    // Setup drop zones
    slots.forEach(slot => {
        slot.addEventListener('dragover', handleDragOver);
        slot.addEventListener('dragleave', handleDragLeave);
        slot.addEventListener('drop', handleDrop);
    });

    function handleDragStart(e) {
        draggedCard = this;
        sourceSlot = this.closest('.grid-slot');
        this.classList.add('dragging');
        e.dataTransfer.effectAllowed = 'move';
        e.dataTransfer.setData('text/plain', this.dataset.id);

        // Add visual feedback after a short delay
        setTimeout(() => {
            this.classList.add('drag-ghost');
        }, 0);
    }

    function handleDragEnd(e) {
        this.classList.remove('dragging', 'drag-ghost');
        slots.forEach(slot => slot.classList.remove('drag-over'));
        draggedCard = null;
        sourceSlot = null;
    }

    function handleDragOver(e) {
        e.preventDefault();
        e.dataTransfer.dropEffect = 'move';
        this.classList.add('drag-over');
    }

    function handleDragLeave(e) {
        this.classList.remove('drag-over');
    }

    function handleDrop(e) {
        e.preventDefault();
        this.classList.remove('drag-over');

        if (!draggedCard) return;

        const targetSlot = this;
        const existingCard = targetSlot.querySelector('.pemimpin-card');

        if (existingCard && existingCard !== draggedCard) {
            // Swap cards
            sourceSlot.appendChild(existingCard);
        }

        targetSlot.appendChild(draggedCard);

        // Save new positions
        savePositions();
    }

    function savePositions() {
        const positions = [];

        slots.forEach(slot => {
            const card = slot.querySelector('.pemimpin-card');
            if (card) {
                positions.push({
                    id: card.dataset.id,
                    position: slot.dataset.position
                });
            }
        });

        fetch('{{ route("admin.konten-publik.pemimpin.reorder") }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            body: JSON.stringify({ positions: positions })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                // Show toast
                const toast = new bootstrap.Toast(document.getElementById('saveToast'));
                toast.show();

                // Update preview
                updatePreview();
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Gagal menyimpan posisi. Silakan coba lagi.');
        });
    }

    function updatePreview() {
        const previewSlots = document.querySelectorAll('.preview-slot');

        previewSlots.forEach(previewSlot => {
            const position = previewSlot.dataset.position;
            const gridSlot = document.querySelector(`.grid-slot[data-position="${position}"]`);
            const card = gridSlot.querySelector('.pemimpin-card');

            // Clear preview slot
            previewSlot.innerHTML = '';

            if (card) {
                const img = card.querySelector('.pemimpin-photo img');
                const name = card.querySelector('.pemimpin-name').textContent;
                const jabatan = card.querySelector('.pemimpin-jabatan').textContent;

                previewSlot.innerHTML = `
                    <div class="preview-card">
                        <div class="preview-photo">
                            <img src="${img.src}" alt="${name}">
                        </div>
                        <h6 class="preview-name">${name}</h6>
                        <small class="preview-jabatan">${jabatan}</small>
                    </div>
                `;
            }
        });
    }

    // Reset positions button
    document.getElementById('resetPositions').addEventListener('click', function() {
        Swal.fire({
            title: 'Reset Posisi?',
            text: 'Semua posisi akan dikembalikan ke urutan default (1-15)',
            icon: 'question',
            showCancelButton: true,
            confirmButtonColor: '#2563eb',
            cancelButtonColor: '#64748b',
            confirmButtonText: 'Ya, Reset',
            cancelButtonText: 'Batal'
        }).then((result) => {
            if (result.isConfirmed) {
                const cards = Array.from(document.querySelectorAll('.pemimpin-card'));

                cards.forEach((card, index) => {
                    const slot = slots[index];
                    if (slot) {
                        slot.appendChild(card);
                    }
                });

                savePositions();
            }
        });
    });
});

function confirmDelete(id) {
    Swal.fire({
        title: 'Hapus Pemimpin Daerah?',
        text: 'Data pemimpin daerah ini akan dihapus permanen!',
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
</script>
@endpush

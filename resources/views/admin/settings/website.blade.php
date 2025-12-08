@extends('admin.layouts.app')

@section('title', 'Pengaturan Website')

@section('content')
<div class="content-wrapper">
    <div class="page-header">
        <h1 class="page-title">Pengaturan Website</h1>
        <p class="page-subtitle">Kelola informasi dan konfigurasi website</p>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="bi bi-check-circle me-2"></i>{{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    @if($errors->any())
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <ul class="mb-0">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <form action="{{ route('admin.settings.website.update') }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')

        <div class="row">
            <div class="col-lg-8">
                <!-- Informasi Umum -->
                <div class="card mb-4">
                    <div class="card-header d-flex align-items-center gap-2">
                        <i class="bi bi-globe text-primary"></i>
                        <h5 class="mb-0">Informasi Umum</h5>
                    </div>
                    <div class="card-body">
                        <div class="mb-3">
                            <label for="site_name" class="form-label">Nama Website <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="site_name" name="site_name"
                                value="{{ old('site_name', $settings['site_name'] ?? '') }}" required>
                        </div>
                        <div class="mb-3">
                            <label for="site_tagline" class="form-label">Tagline</label>
                            <input type="text" class="form-control" id="site_tagline" name="site_tagline"
                                value="{{ old('site_tagline', $settings['site_tagline'] ?? '') }}"
                                placeholder="Contoh: Melayani dengan Sepenuh Hati">
                        </div>
                        <div class="mb-3">
                            <label for="site_description" class="form-label">Deskripsi Website</label>
                            <textarea class="form-control" id="site_description" name="site_description" rows="3"
                                placeholder="Deskripsi singkat tentang website">{{ old('site_description', $settings['site_description'] ?? '') }}</textarea>
                            <div class="form-text">Digunakan untuk SEO dan meta description</div>
                        </div>
                    </div>
                </div>

                <!-- Kontak -->
                <div class="card mb-4">
                    <div class="card-header d-flex align-items-center gap-2">
                        <i class="bi bi-telephone text-success"></i>
                        <h5 class="mb-0">Informasi Kontak</h5>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="site_email" class="form-label">Email</label>
                                <div class="input-group">
                                    <span class="input-group-text"><i class="bi bi-envelope"></i></span>
                                    <input type="email" class="form-control" id="site_email" name="site_email"
                                        value="{{ old('site_email', $settings['site_email'] ?? '') }}"
                                        placeholder="email@domain.go.id">
                                </div>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="site_phone" class="form-label">Telepon</label>
                                <div class="input-group">
                                    <span class="input-group-text"><i class="bi bi-telephone"></i></span>
                                    <input type="text" class="form-control" id="site_phone" name="site_phone"
                                        value="{{ old('site_phone', $settings['site_phone'] ?? '') }}"
                                        placeholder="(0537) 12345">
                                </div>
                            </div>
                        </div>
                        <div class="mb-3">
                            <label for="site_address" class="form-label">Alamat</label>
                            <textarea class="form-control" id="site_address" name="site_address" rows="2"
                                placeholder="Alamat lengkap kantor">{{ old('site_address', $settings['site_address'] ?? '') }}</textarea>
                        </div>
                        <div class="mb-3">
                            <label for="maps_embed" class="form-label">Google Maps Embed</label>
                            <textarea class="form-control" id="maps_embed" name="maps_embed" rows="3"
                                placeholder='<iframe src="https://www.google.com/maps/embed?..." ...></iframe>'>{{ old('maps_embed', $settings['maps_embed'] ?? '') }}</textarea>
                            <div class="form-text">Salin kode embed dari Google Maps</div>
                        </div>
                    </div>
                </div>

                <!-- Media Sosial -->
                <div class="card mb-4">
                    <div class="card-header d-flex align-items-center gap-2">
                        <i class="bi bi-share text-info"></i>
                        <h5 class="mb-0">Media Sosial</h5>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="social_facebook" class="form-label">Facebook</label>
                                <div class="input-group">
                                    <span class="input-group-text"><i class="bi bi-facebook"></i></span>
                                    <input type="url" class="form-control" id="social_facebook" name="social_facebook"
                                        value="{{ old('social_facebook', $settings['social_facebook'] ?? '') }}"
                                        placeholder="https://facebook.com/...">
                                </div>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="social_instagram" class="form-label">Instagram</label>
                                <div class="input-group">
                                    <span class="input-group-text"><i class="bi bi-instagram"></i></span>
                                    <input type="url" class="form-control" id="social_instagram" name="social_instagram"
                                        value="{{ old('social_instagram', $settings['social_instagram'] ?? '') }}"
                                        placeholder="https://instagram.com/...">
                                </div>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="social_twitter" class="form-label">Twitter / X</label>
                                <div class="input-group">
                                    <span class="input-group-text"><i class="bi bi-twitter-x"></i></span>
                                    <input type="url" class="form-control" id="social_twitter" name="social_twitter"
                                        value="{{ old('social_twitter', $settings['social_twitter'] ?? '') }}"
                                        placeholder="https://twitter.com/...">
                                </div>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="social_youtube" class="form-label">YouTube</label>
                                <div class="input-group">
                                    <span class="input-group-text"><i class="bi bi-youtube"></i></span>
                                    <input type="url" class="form-control" id="social_youtube" name="social_youtube"
                                        value="{{ old('social_youtube', $settings['social_youtube'] ?? '') }}"
                                        placeholder="https://youtube.com/...">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Visi & Misi -->
                <div class="card mb-4">
                    <div class="card-header d-flex align-items-center gap-2">
                        <i class="bi bi-bullseye text-danger"></i>
                        <h5 class="mb-0">Visi & Misi</h5>
                    </div>
                    <div class="card-body">
                        <div class="mb-3">
                            <label for="visi" class="form-label">Visi</label>
                            <textarea class="form-control" id="visi" name="visi" rows="3"
                                placeholder='Contoh: "Terwujudnya Kabupaten Katingan yang Maju, Sejahtera, Berkeadilan dan Berakhlak Mulia"'>{{ old('visi', $settings['visi'] ?? '') }}</textarea>
                            <div class="form-text">Visi Kabupaten Katingan</div>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Misi</label>
                            <div id="misi-container">
                                @php
                                    $misiList = old('misi', $settings['misi'] ?? []);
                                    if (empty($misiList)) {
                                        $misiList = [''];
                                    }
                                @endphp
                                @foreach($misiList as $index => $misi)
                                <div class="misi-item mb-2">
                                    <div class="input-group">
                                        <span class="input-group-text">{{ $index + 1 }}</span>
                                        <input type="text" class="form-control" name="misi[]"
                                            value="{{ $misi }}"
                                            placeholder="Masukkan misi ke-{{ $index + 1 }}">
                                        <button type="button" class="btn btn-outline-danger btn-remove-misi" onclick="removeMisi(this)">
                                            <i class="bi bi-trash"></i>
                                        </button>
                                    </div>
                                </div>
                                @endforeach
                            </div>
                            <button type="button" class="btn btn-sm btn-outline-primary mt-2" onclick="addMisi()">
                                <i class="bi bi-plus-circle me-1"></i> Tambah Misi
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Sidebar -->
            <div class="col-lg-4">
                <!-- Logo & Favicon -->
                <div class="card mb-4">
                    <div class="card-header d-flex align-items-center gap-2">
                        <i class="bi bi-image text-warning"></i>
                        <h5 class="mb-0">Logo & Favicon</h5>
                    </div>
                    <div class="card-body">
                        <div class="mb-4">
                            <label class="form-label">Logo Website</label>
                            <div class="logo-preview-wrapper mb-2">
                                @if(!empty($settings['site_logo']))
                                    <img src="{{ asset('storage/' . $settings['site_logo']) }}" alt="Logo" class="logo-preview" id="logoPreview">
                                @else
                                    <div class="logo-placeholder" id="logoPlaceholder">
                                        <i class="bi bi-image"></i>
                                        <span>Belum ada logo</span>
                                    </div>
                                    <img src="" alt="Logo" class="logo-preview d-none" id="logoPreview">
                                @endif
                            </div>
                            <input type="file" class="form-control" id="site_logo" name="site_logo" accept="image/*">
                            <div class="form-text">Format: JPG, PNG, SVG. Maks: 2MB</div>
                        </div>

                        <div>
                            <label class="form-label">Favicon</label>
                            <div class="favicon-preview-wrapper mb-2">
                                @if(!empty($settings['site_favicon']))
                                    <img src="{{ asset('storage/' . $settings['site_favicon']) }}" alt="Favicon" class="favicon-preview" id="faviconPreview">
                                @else
                                    <div class="favicon-placeholder" id="faviconPlaceholder">
                                        <i class="bi bi-app"></i>
                                    </div>
                                    <img src="" alt="Favicon" class="favicon-preview d-none" id="faviconPreview">
                                @endif
                            </div>
                            <input type="file" class="form-control" id="site_favicon" name="site_favicon" accept=".ico,.png">
                            <div class="form-text">Format: ICO, PNG. Maks: 1MB</div>
                        </div>
                    </div>
                </div>

                <!-- Save Button -->
                <div class="card">
                    <div class="card-body">
                        <button type="submit" class="btn btn-primary w-100">
                            <i class="bi bi-check-lg me-1"></i> Simpan Pengaturan
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>
@endsection

@push('styles')
<style>
    .logo-preview-wrapper {
        width: 100%;
        height: 120px;
        border: 2px dashed #e2e8f0;
        border-radius: 8px;
        display: flex;
        align-items: center;
        justify-content: center;
        background: #f8fafc;
        overflow: hidden;
    }

    .logo-preview {
        max-width: 100%;
        max-height: 100%;
        object-fit: contain;
    }

    .logo-placeholder {
        text-align: center;
        color: #94a3b8;
    }

    .logo-placeholder i {
        font-size: 36px;
        display: block;
        margin-bottom: 8px;
    }

    .logo-placeholder span {
        font-size: 13px;
    }

    .favicon-preview-wrapper {
        width: 64px;
        height: 64px;
        border: 2px dashed #e2e8f0;
        border-radius: 8px;
        display: flex;
        align-items: center;
        justify-content: center;
        background: #f8fafc;
        overflow: hidden;
    }

    .favicon-preview {
        width: 32px;
        height: 32px;
        object-fit: contain;
    }

    .favicon-placeholder {
        color: #94a3b8;
        font-size: 24px;
    }
</style>
@endpush

@push('scripts')
<script>
    // Preview logo
    document.getElementById('site_logo').addEventListener('change', function(e) {
        const file = e.target.files[0];
        if (file) {
            const reader = new FileReader();
            reader.onload = function(e) {
                const preview = document.getElementById('logoPreview');
                const placeholder = document.getElementById('logoPlaceholder');

                preview.src = e.target.result;
                preview.classList.remove('d-none');

                if (placeholder) {
                    placeholder.classList.add('d-none');
                }
            }
            reader.readAsDataURL(file);
        }
    });

    // Preview favicon
    document.getElementById('site_favicon').addEventListener('change', function(e) {
        const file = e.target.files[0];
        if (file) {
            const reader = new FileReader();
            reader.onload = function(e) {
                const preview = document.getElementById('faviconPreview');
                const placeholder = document.getElementById('faviconPlaceholder');

                preview.src = e.target.result;
                preview.classList.remove('d-none');

                if (placeholder) {
                    placeholder.classList.add('d-none');
                }
            }
            reader.readAsDataURL(file);
        }
    });

    // Add new misi field
    function addMisi() {
        const container = document.getElementById('misi-container');
        const count = container.querySelectorAll('.misi-item').length + 1;

        const newItem = document.createElement('div');
        newItem.className = 'misi-item mb-2';
        newItem.innerHTML = `
            <div class="input-group">
                <span class="input-group-text">${count}</span>
                <input type="text" class="form-control" name="misi[]"
                    placeholder="Masukkan misi ke-${count}">
                <button type="button" class="btn btn-outline-danger btn-remove-misi" onclick="removeMisi(this)">
                    <i class="bi bi-trash"></i>
                </button>
            </div>
        `;

        container.appendChild(newItem);
        updateMisiNumbers();
    }

    // Remove misi field
    function removeMisi(button) {
        const container = document.getElementById('misi-container');
        const items = container.querySelectorAll('.misi-item');

        // Don't allow removing if only one item left
        if (items.length <= 1) {
            alert('Minimal harus ada 1 misi');
            return;
        }

        button.closest('.misi-item').remove();
        updateMisiNumbers();
    }

    // Update misi numbers
    function updateMisiNumbers() {
        const container = document.getElementById('misi-container');
        const items = container.querySelectorAll('.misi-item');

        items.forEach((item, index) => {
            const number = item.querySelector('.input-group-text');
            const input = item.querySelector('input');

            number.textContent = index + 1;
            input.placeholder = `Masukkan misi ke-${index + 1}`;
        });
    }
</script>
@endpush

@extends('admin.layouts.app')

@section('title', 'Profil Admin')

@section('content')
<div class="content-wrapper">
    <div class="page-header">
        <h1 class="page-title">Profil Admin</h1>
        <p class="page-subtitle">Kelola informasi profil dan keamanan akun Anda</p>
    </div>

    <div class="row">
        <!-- Profile Info -->
        <div class="col-lg-8">
            <div class="card mb-4">
                <div class="card-header d-flex align-items-center gap-2">
                    <i class="bi bi-person-circle text-primary"></i>
                    <h5 class="mb-0">Informasi Profil</h5>
                </div>
                <div class="card-body">
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

                    <form action="{{ route('admin.settings.profile.update') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')

                        <div class="row mb-4">
                            <div class="col-md-3 text-center">
                                <div class="profile-photo-wrapper mb-3">
                                    @if($user->foto)
                                        <img src="{{ asset('storage/' . $user->foto) }}" alt="Foto Profil" class="profile-photo" id="previewPhoto">
                                    @else
                                        <div class="profile-photo-placeholder" id="placeholderPhoto">
                                            {{ strtoupper(substr($user->name, 0, 2)) }}
                                        </div>
                                        <img src="" alt="Foto Profil" class="profile-photo d-none" id="previewPhoto">
                                    @endif
                                </div>
                                <input type="file" name="foto" id="fotoInput" class="d-none" accept="image/*">
                                <button type="button" class="btn btn-outline-primary btn-sm" onclick="document.getElementById('fotoInput').click()">
                                    <i class="bi bi-camera me-1"></i> Ubah Foto
                                </button>
                                <p class="text-muted small mt-2 mb-0">JPG, PNG. Maks 2MB</p>
                            </div>
                            <div class="col-md-9">
                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label for="name" class="form-label">Nama Lengkap <span class="text-danger">*</span></label>
                                        <input type="text" class="form-control" id="name" name="name" value="{{ old('name', $user->name) }}" required>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label for="email" class="form-label">Email <span class="text-danger">*</span></label>
                                        <input type="email" class="form-control" id="email" name="email" value="{{ old('email', $user->email) }}" required>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label for="phone" class="form-label">Nomor Telepon</label>
                                        <input type="text" class="form-control" id="phone" name="phone" value="{{ old('phone', $user->phone) }}" placeholder="08xxxxxxxxxx">
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">Role</label>
                                        <input type="text" class="form-control" value="{{ ucfirst($user->role) }}" readonly disabled>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="d-flex justify-content-end">
                            <button type="submit" class="btn btn-primary">
                                <i class="bi bi-check-lg me-1"></i> Simpan Perubahan
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Change Password -->
            <div class="card">
                <div class="card-header d-flex align-items-center gap-2">
                    <i class="bi bi-shield-lock text-warning"></i>
                    <h5 class="mb-0">Ubah Password</h5>
                </div>
                <div class="card-body">
                    <form action="{{ route('admin.settings.password.update') }}" method="POST">
                        @csrf
                        @method('PUT')

                        <div class="row">
                            <div class="col-md-4 mb-3">
                                <label for="current_password" class="form-label">Password Saat Ini <span class="text-danger">*</span></label>
                                <div class="input-group">
                                    <input type="password" class="form-control" id="current_password" name="current_password" required>
                                    <button class="btn btn-outline-secondary" type="button" onclick="togglePassword('current_password')">
                                        <i class="bi bi-eye"></i>
                                    </button>
                                </div>
                            </div>
                            <div class="col-md-4 mb-3">
                                <label for="password" class="form-label">Password Baru <span class="text-danger">*</span></label>
                                <div class="input-group">
                                    <input type="password" class="form-control" id="password" name="password" required minlength="8">
                                    <button class="btn btn-outline-secondary" type="button" onclick="togglePassword('password')">
                                        <i class="bi bi-eye"></i>
                                    </button>
                                </div>
                            </div>
                            <div class="col-md-4 mb-3">
                                <label for="password_confirmation" class="form-label">Konfirmasi Password <span class="text-danger">*</span></label>
                                <div class="input-group">
                                    <input type="password" class="form-control" id="password_confirmation" name="password_confirmation" required>
                                    <button class="btn btn-outline-secondary" type="button" onclick="togglePassword('password_confirmation')">
                                        <i class="bi bi-eye"></i>
                                    </button>
                                </div>
                            </div>
                        </div>

                        <div class="alert alert-info small mb-3">
                            <i class="bi bi-info-circle me-1"></i>
                            Password minimal 8 karakter. Gunakan kombinasi huruf, angka, dan simbol untuk keamanan yang lebih baik.
                        </div>

                        <div class="d-flex justify-content-end">
                            <button type="submit" class="btn btn-warning">
                                <i class="bi bi-key me-1"></i> Ubah Password
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Sidebar Info -->
        <div class="col-lg-4">
            <div class="card mb-4">
                <div class="card-header d-flex align-items-center gap-2">
                    <i class="bi bi-info-circle text-info"></i>
                    <h5 class="mb-0">Info Akun</h5>
                </div>
                <div class="card-body">
                    <div class="info-item mb-3">
                        <small class="text-muted d-block">ID Pengguna</small>
                        <span class="fw-medium">#{{ $user->id }}</span>
                    </div>
                    <div class="info-item mb-3">
                        <small class="text-muted d-block">Status Akun</small>
                        <span class="badge bg-success">Aktif</span>
                    </div>
                    <div class="info-item mb-3">
                        <small class="text-muted d-block">Terdaftar Sejak</small>
                        <span class="fw-medium">{{ $user->created_at->format('d F Y') }}</span>
                    </div>
                    <div class="info-item">
                        <small class="text-muted d-block">Terakhir Diperbarui</small>
                        <span class="fw-medium">{{ $user->updated_at->format('d F Y, H:i') }}</span>
                    </div>
                </div>
            </div>

            <div class="card">
                <div class="card-header d-flex align-items-center gap-2">
                    <i class="bi bi-clock-history text-secondary"></i>
                    <h5 class="mb-0">Aktivitas Login</h5>
                </div>
                <div class="card-body">
                    <div class="info-item mb-3">
                        <small class="text-muted d-block">Login Terakhir</small>
                        <span class="fw-medium">{{ now()->format('d F Y, H:i') }}</span>
                    </div>
                    <div class="info-item">
                        <small class="text-muted d-block">IP Address</small>
                        <span class="fw-medium">{{ request()->ip() }}</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
    .profile-photo-wrapper {
        width: 120px;
        height: 120px;
        margin: 0 auto;
        position: relative;
    }

    .profile-photo {
        width: 120px;
        height: 120px;
        border-radius: 50%;
        object-fit: cover;
        border: 4px solid #e2e8f0;
    }

    .profile-photo-placeholder {
        width: 120px;
        height: 120px;
        border-radius: 50%;
        background: linear-gradient(135deg, var(--primary-color), var(--primary-dark));
        display: flex;
        align-items: center;
        justify-content: center;
        color: #fff;
        font-size: 36px;
        font-weight: 700;
        border: 4px solid #e2e8f0;
    }

    .info-item {
        padding: 10px 0;
        border-bottom: 1px solid #f1f5f9;
    }

    .info-item:last-child {
        border-bottom: none;
        padding-bottom: 0;
    }
</style>
@endpush

@push('scripts')
<script>
    // Preview foto
    document.getElementById('fotoInput').addEventListener('change', function(e) {
        const file = e.target.files[0];
        if (file) {
            const reader = new FileReader();
            reader.onload = function(e) {
                const preview = document.getElementById('previewPhoto');
                const placeholder = document.getElementById('placeholderPhoto');

                preview.src = e.target.result;
                preview.classList.remove('d-none');

                if (placeholder) {
                    placeholder.classList.add('d-none');
                }
            }
            reader.readAsDataURL(file);
        }
    });

    // Toggle password visibility
    function togglePassword(inputId) {
        const input = document.getElementById(inputId);
        const icon = input.nextElementSibling.querySelector('i');

        if (input.type === 'password') {
            input.type = 'text';
            icon.classList.remove('bi-eye');
            icon.classList.add('bi-eye-slash');
        } else {
            input.type = 'password';
            icon.classList.remove('bi-eye-slash');
            icon.classList.add('bi-eye');
        }
    }
</script>
@endpush

@extends('admin.layouts.app')

@section('title', 'Edit Admin')

@section('content')
<div class="content-wrapper">
    <div class="page-header d-flex justify-content-between align-items-center">
        <div>
            <h1 class="page-title">Edit Admin</h1>
            <p class="page-subtitle">Ubah data administrator</p>
        </div>
        <a href="{{ route('admin.users.admin') }}" class="btn btn-outline-secondary">
            <i class="bi bi-arrow-left me-1"></i> Kembali
        </a>
    </div>

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

    <form action="{{ route('admin.users.admin.update', $user->id) }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')

        <div class="row">
            <div class="col-lg-8">
                <!-- Info Dasar -->
                <div class="card mb-4">
                    <div class="card-header">
                        <h5 class="mb-0"><i class="bi bi-person me-2"></i>Informasi Dasar</h5>
                    </div>
                    <div class="card-body">
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
                                <label for="nip" class="form-label">NIP</label>
                                <input type="text" class="form-control" id="nip" name="nip" value="{{ old('nip', $user->nip) }}">
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="jabatan" class="form-label">Jabatan</label>
                                <input type="text" class="form-control" id="jabatan" name="jabatan" value="{{ old('jabatan', $user->jabatan) }}">
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="phone" class="form-label">Nomor Telepon</label>
                                <input type="text" class="form-control" id="phone" name="phone" value="{{ old('phone', $user->phone) }}" placeholder="08xxxxxxxxxx">
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Password -->
                <div class="card mb-4">
                    <div class="card-header">
                        <h5 class="mb-0"><i class="bi bi-key me-2"></i>Ubah Password</h5>
                    </div>
                    <div class="card-body">
                        <div class="alert alert-info mb-3">
                            <i class="bi bi-info-circle me-1"></i>
                            Kosongkan jika tidak ingin mengubah password.
                        </div>
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="password" class="form-label">Password Baru</label>
                                <div class="input-group">
                                    <input type="password" class="form-control" id="password" name="password" minlength="8">
                                    <button class="btn btn-outline-secondary" type="button" onclick="togglePassword('password')">
                                        <i class="bi bi-eye"></i>
                                    </button>
                                </div>
                                <div class="form-text">Minimal 8 karakter</div>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="password_confirmation" class="form-label">Konfirmasi Password Baru</label>
                                <div class="input-group">
                                    <input type="password" class="form-control" id="password_confirmation" name="password_confirmation">
                                    <button class="btn btn-outline-secondary" type="button" onclick="togglePassword('password_confirmation')">
                                        <i class="bi bi-eye"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-lg-4">
                <!-- Role & Status -->
                <div class="card mb-4">
                    <div class="card-header">
                        <h5 class="mb-0"><i class="bi bi-shield me-2"></i>Role & Status</h5>
                    </div>
                    <div class="card-body">
                        <div class="mb-3">
                            <label for="role" class="form-label">Role <span class="text-danger">*</span></label>
                            <select class="form-select" id="role" name="role" required {{ $user->id === auth('admin')->id() ? 'disabled' : '' }}>
                                <option value="admin" {{ old('role', $user->role) == 'admin' ? 'selected' : '' }}>Admin Biasa</option>
                                <option value="super_admin" {{ old('role', $user->role) == 'super_admin' ? 'selected' : '' }}>Super Admin</option>
                            </select>
                            @if($user->id === auth('admin')->id())
                                <input type="hidden" name="role" value="{{ $user->role }}">
                                <div class="form-text text-warning">Tidak dapat mengubah role sendiri</div>
                            @else
                                <div class="form-text">
                                    <strong>Super Admin:</strong> Akses penuh ke semua fitur<br>
                                    <strong>Admin Biasa:</strong> Akses terbatas
                                </div>
                            @endif
                        </div>
                        <div class="mb-3">
                            <div class="form-check form-switch">
                                <input class="form-check-input" type="checkbox" id="is_active" name="is_active" {{ $user->is_active ? 'checked' : '' }} {{ $user->id === auth('admin')->id() ? 'disabled' : '' }}>
                                <label class="form-check-label" for="is_active">Aktif</label>
                            </div>
                            @if($user->id === auth('admin')->id())
                                <div class="form-text text-warning">Tidak dapat mengubah status sendiri</div>
                            @endif
                        </div>
                    </div>
                </div>

                <!-- Foto -->
                <div class="card mb-4">
                    <div class="card-header">
                        <h5 class="mb-0"><i class="bi bi-camera me-2"></i>Foto Profil</h5>
                    </div>
                    <div class="card-body text-center">
                        <div class="mb-3">
                            <div id="photoPreview">
                                @if($user->foto)
                                    <img src="{{ asset('storage/' . $user->foto) }}" class="rounded-circle" style="width: 120px; height: 120px; object-fit: cover;">
                                @else
                                    <div class="rounded-circle bg-primary d-flex align-items-center justify-content-center mx-auto text-white fw-bold" style="width: 120px; height: 120px; font-size: 48px;">
                                        {{ strtoupper(substr($user->name, 0, 1)) }}
                                    </div>
                                @endif
                            </div>
                        </div>
                        <input type="file" class="form-control" id="foto" name="foto" accept="image/*" onchange="previewPhoto(this)">
                        <div class="form-text">JPG, PNG. Maks: 2MB</div>
                    </div>
                </div>

                <!-- Info -->
                <div class="card mb-4">
                    <div class="card-header">
                        <h5 class="mb-0"><i class="bi bi-info-circle me-2"></i>Info Akun</h5>
                    </div>
                    <div class="card-body">
                        <div class="mb-2">
                            <small class="text-muted">ID Pengguna</small>
                            <div class="fw-medium">#{{ $user->id }}</div>
                        </div>
                        <div class="mb-2">
                            <small class="text-muted">Terdaftar</small>
                            <div class="fw-medium">{{ $user->created_at->format('d F Y, H:i') }}</div>
                        </div>
                        <div>
                            <small class="text-muted">Terakhir Diperbarui</small>
                            <div class="fw-medium">{{ $user->updated_at->format('d F Y, H:i') }}</div>
                        </div>
                    </div>
                </div>

                <!-- Submit -->
                <div class="card">
                    <div class="card-body">
                        <button type="submit" class="btn btn-primary w-100">
                            <i class="bi bi-check-lg me-1"></i> Simpan Perubahan
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>
@endsection

@push('scripts')
<script>
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

    function previewPhoto(input) {
        const preview = document.getElementById('photoPreview');
        if (input.files && input.files[0]) {
            const reader = new FileReader();
            reader.onload = function(e) {
                preview.innerHTML = `<img src="${e.target.result}" class="rounded-circle" style="width: 120px; height: 120px; object-fit: cover;">`;
            }
            reader.readAsDataURL(input.files[0]);
        }
    }
</script>
@endpush

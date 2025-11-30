@extends('user.layouts.app')

@section('title', 'Profil Saya')

@section('content')
<div class="page-header">
    <h1 class="page-title">Profil Saya</h1>
    <p class="page-subtitle">Kelola informasi akun Anda</p>
</div>

<div class="row g-4">
    <!-- Profile Info -->
    <div class="col-lg-8">
        <div class="card">
            <div class="card-header bg-transparent border-0 pt-4 pb-0 px-4">
                <h5 class="card-title mb-1">Informasi Profil</h5>
                <p class="text-muted small mb-0">Perbarui data diri Anda</p>
            </div>
            <div class="card-body p-4">
                @if($errors->any() && !$errors->has('current_password'))
                    <div class="alert alert-danger">
                        <ul class="mb-0">
                            @foreach($errors->all() as $error)
                                @if(!in_array($error, ['Password saat ini tidak sesuai.']))
                                    <li>{{ $error }}</li>
                                @endif
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form action="{{ route('user.profile.update') }}" method="POST">
                    @csrf
                    @method('PUT')

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Nama Lengkap & Gelar <span class="text-danger">*</span></label>
                            <input type="text" name="name" class="form-control" value="{{ old('name', $user->name) }}" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Jabatan <span class="text-danger">*</span></label>
                            <input type="text" name="jabatan" class="form-control" value="{{ old('jabatan', $user->jabatan) }}" required>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">NIP / NIPPPK</label>
                            <input type="text" name="nip" class="form-control" value="{{ old('nip', $user->nip) }}">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">NIK</label>
                            <input type="text" class="form-control" value="{{ $user->nik }}" disabled>
                            <small class="text-muted">NIK tidak dapat diubah</small>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Email</label>
                            <input type="email" class="form-control" value="{{ $user->email }}" disabled>
                            <small class="text-muted">Email tidak dapat diubah</small>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">No. WhatsApp <span class="text-danger">*</span></label>
                            <input type="text" name="no_whatsapp" class="form-control" value="{{ old('no_whatsapp', $user->no_whatsapp) }}" required>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Jenis Kelamin <span class="text-danger">*</span></label>
                            <select name="jenis_kelamin" class="form-select" required>
                                <option value="Laki-laki" {{ old('jenis_kelamin', $user->jenis_kelamin) == 'Laki-laki' ? 'selected' : '' }}>Laki-laki</option>
                                <option value="Perempuan" {{ old('jenis_kelamin', $user->jenis_kelamin) == 'Perempuan' ? 'selected' : '' }}>Perempuan</option>
                            </select>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Instansi / Lembaga <span class="text-danger">*</span></label>
                            <input type="text" name="instansi" class="form-control" value="{{ old('instansi', $user->instansi) }}" required>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Biro / Bagian / Bidang / Seksi <span class="text-danger">*</span></label>
                        <input type="text" name="biro_bagian" class="form-control" value="{{ old('biro_bagian', $user->biro_bagian) }}" required>
                    </div>

                    <button type="submit" class="btn btn-primary">
                        <i class="bi bi-check-lg me-1"></i> Simpan Perubahan
                    </button>
                </form>
            </div>
        </div>
    </div>

    <!-- Change Password -->
    <div class="col-lg-4">
        <div class="card">
            <div class="card-header bg-transparent border-0 pt-4 pb-0 px-4">
                <h5 class="card-title mb-1">Ubah Password</h5>
                <p class="text-muted small mb-0">Perbarui password akun</p>
            </div>
            <div class="card-body p-4">
                @if($errors->has('current_password'))
                    <div class="alert alert-danger">
                        {{ $errors->first('current_password') }}
                    </div>
                @endif

                <form action="{{ route('user.profile.password') }}" method="POST">
                    @csrf
                    @method('PUT')

                    <div class="mb-3">
                        <label class="form-label">Password Saat Ini</label>
                        <input type="password" name="current_password" class="form-control" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Password Baru</label>
                        <input type="password" name="password" class="form-control" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Konfirmasi Password</label>
                        <input type="password" name="password_confirmation" class="form-control" required>
                    </div>

                    <button type="submit" class="btn btn-warning w-100">
                        <i class="bi bi-lock me-1"></i> Ubah Password
                    </button>
                </form>
            </div>
        </div>

        <!-- Account Info -->
        <div class="card mt-4">
            <div class="card-body p-4">
                <h6 class="mb-3">Informasi Akun</h6>
                <p class="small text-muted mb-2">
                    <i class="bi bi-calendar me-2"></i>
                    Terdaftar: {{ $user->created_at->format('d M Y') }}
                </p>
                <p class="small text-muted mb-0">
                    <i class="bi bi-shield-check me-2"></i>
                    Status: <span class="badge bg-success">Aktif</span>
                </p>
            </div>
        </div>
    </div>
</div>
@endsection

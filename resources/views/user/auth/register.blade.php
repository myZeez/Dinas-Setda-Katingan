<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Daftar Akun Layanan - {{ \App\Helpers\SettingHelper::siteName() }}</title>

    <!-- Favicon -->
    <link rel="icon" type="image/x-icon" href="{{ \App\Helpers\SettingHelper::favicon() }}">

    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Bootstrap Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css" rel="stylesheet">

    <!-- SweetAlert2 -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <style>
        :root {
            --primary-color: #16a34a;
            --primary-dark: #15803d;
            --secondary-color: #fbbf24;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Inter', sans-serif;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            background: linear-gradient(135deg, #f0fdf4 0%, #dcfce7 100%);
            padding: 20px;
        }

        .register-container {
            width: 100%;
            max-width: 480px;
        }

        .register-card {
            background: #fff;
            border-radius: 20px;
            box-shadow: 0 10px 40px rgba(0,0,0,0.1);
            overflow: hidden;
        }

        .register-header {
            text-align: center;
            padding: 30px 30px 20px;
            border-bottom: 1px solid #f0f0f0;
        }

        .register-header .icon-wrapper {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            margin-bottom: 15px;
        }

        .register-header .icon-wrapper .icon {
            width: 50px;
            height: 50px;
            background: linear-gradient(135deg, var(--primary-color), var(--primary-dark));
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .register-header .icon-wrapper .icon i {
            font-size: 24px;
            color: #fff;
        }

        .register-header h4 {
            font-size: 18px;
            font-weight: 700;
            color: #1e293b;
            margin-bottom: 5px;
        }

        .register-header p {
            font-size: 13px;
            color: #64748b;
            margin: 0;
        }

        .register-body {
            padding: 30px;
        }

        .form-group {
            margin-bottom: 16px;
        }

        .form-label {
            font-size: 13px;
            font-weight: 500;
            color: #374151;
            margin-bottom: 6px;
        }

        .form-label .required {
            color: #ef4444;
        }

        .form-control, .form-select {
            padding: 12px 16px;
            border: 2px solid #e5e7eb;
            border-radius: 10px;
            font-size: 14px;
            transition: all 0.2s ease;
        }

        .form-control:focus, .form-select:focus {
            border-color: var(--primary-color);
            box-shadow: 0 0 0 3px rgba(22, 163, 74, 0.1);
        }

        .form-control::placeholder {
            color: #9ca3af;
        }

        .btn-register {
            width: 100%;
            padding: 14px;
            font-size: 15px;
            font-weight: 600;
            border-radius: 10px;
            background: var(--secondary-color);
            border: none;
            color: #78350f;
            transition: all 0.3s ease;
            margin-top: 10px;
        }

        .btn-register:hover {
            background: #f59e0b;
            transform: translateY(-2px);
            box-shadow: 0 8px 20px rgba(251, 191, 36, 0.4);
        }

        .info-text {
            background: #fef3c7;
            border-radius: 8px;
            padding: 12px 15px;
            font-size: 12px;
            color: #92400e;
            margin-top: 20px;
        }

        .info-text strong {
            color: #ef4444;
        }

        .divider {
            text-align: center;
            margin: 25px 0 15px;
            position: relative;
        }

        .divider::before {
            content: '';
            position: absolute;
            left: 0;
            top: 50%;
            width: 100%;
            height: 1px;
            background: #e5e7eb;
        }

        .divider span {
            background: #fff;
            padding: 0 15px;
            font-size: 13px;
            color: #9ca3af;
            position: relative;
        }

        .btn-login {
            width: 100%;
            padding: 14px;
            font-size: 15px;
            font-weight: 600;
            border-radius: 10px;
            background: #fff;
            border: 2px solid var(--primary-color);
            color: var(--primary-color);
            transition: all 0.3s ease;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
        }

        .btn-login:hover {
            background: var(--primary-color);
            color: #fff;
        }

        .alert {
            border-radius: 10px;
            font-size: 13px;
            margin-bottom: 20px;
        }

        .back-link {
            text-align: center;
            margin-top: 20px;
        }

        .back-link a {
            font-size: 13px;
            color: #6b7280;
            text-decoration: none;
        }

        .back-link a:hover {
            color: var(--primary-color);
        }

        .is-invalid {
            border-color: #ef4444 !important;
        }

        .invalid-feedback {
            font-size: 12px;
            color: #ef4444;
        }
    </style>
</head>
<body>
    <div class="register-container">
        <div class="register-card">
            <div class="register-header">
                <div class="icon-wrapper">
                    <div class="icon">
                        <i class="bi bi-person"></i>
                    </div>
                    <div class="icon">
                        <i class="bi bi-person"></i>
                    </div>
                </div>
                <h4>DAFTAR AKUN LAYANAN</h4>
                <p>Sekretariat Daerah Kabupaten Katingan<br>Bagian Pemerintahan</p>
            </div>

            <div class="register-body">
                @if($errors->any())
                    <div class="alert alert-danger">
                        <ul class="mb-0">
                            @foreach($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form action="{{ route('user.register.submit') }}" method="POST">
                    @csrf

                    <div class="form-group">
                        <label class="form-label">Nama Lengkap & Gelar <span class="required">*</span></label>
                        <input type="text" name="name" class="form-control @error('name') is-invalid @enderror" placeholder="Masukkan nama lengkap" value="{{ old('name') }}" required>
                    </div>

                    <div class="form-group">
                        <label class="form-label">Jabatan <span class="required">*</span></label>
                        <input type="text" name="jabatan" class="form-control @error('jabatan') is-invalid @enderror" placeholder="Masukkan jabatan" value="{{ old('jabatan') }}" required>
                    </div>

                    <div class="form-group">
                        <label class="form-label">NIP / NIPPPK</label>
                        <input type="text" name="nip" class="form-control @error('nip') is-invalid @enderror" placeholder="Masukkan NIP/NIPPPK (opsional)" value="{{ old('nip') }}">
                    </div>

                    <div class="form-group">
                        <label class="form-label">NIK <span class="required">*</span></label>
                        <input type="text" name="nik" class="form-control @error('nik') is-invalid @enderror" placeholder="Masukkan NIK" value="{{ old('nik') }}" required maxlength="16">
                    </div>

                    <div class="form-group">
                        <label class="form-label">Email Aktif <span class="required">*</span></label>
                        <input type="email" name="email" class="form-control @error('email') is-invalid @enderror" placeholder="Masukkan email aktif" value="{{ old('email') }}" required>
                    </div>

                    <div class="form-group">
                        <label class="form-label">No. Whatsapp <span class="required">*</span></label>
                        <input type="text" name="no_whatsapp" class="form-control @error('no_whatsapp') is-invalid @enderror" placeholder="Contoh: 08123456789" value="{{ old('no_whatsapp') }}" required>
                    </div>

                    <div class="form-group">
                        <label class="form-label">Jenis Kelamin <span class="required">*</span></label>
                        <select name="jenis_kelamin" class="form-select @error('jenis_kelamin') is-invalid @enderror" required>
                            <option value="">-- Pilih Jenis Kelamin --</option>
                            <option value="Laki-laki" {{ old('jenis_kelamin') == 'Laki-laki' ? 'selected' : '' }}>Laki-laki</option>
                            <option value="Perempuan" {{ old('jenis_kelamin') == 'Perempuan' ? 'selected' : '' }}>Perempuan</option>
                        </select>
                    </div>

                    <div class="form-group">
                        <label class="form-label">Instansi / Lembaga / Perusahaan <span class="required">*</span></label>
                        <input type="text" name="instansi" class="form-control @error('instansi') is-invalid @enderror" placeholder="Masukkan nama instansi" value="{{ old('instansi') }}" required>
                    </div>

                    <div class="form-group">
                        <label class="form-label">Biro / Bagian / Bidang / Seksi <span class="required">*</span></label>
                        <input type="text" name="biro_bagian" class="form-control @error('biro_bagian') is-invalid @enderror" placeholder="Masukkan biro/bagian/bidang/seksi" value="{{ old('biro_bagian') }}" required>
                    </div>

                    <div class="form-group">
                        <label class="form-label">Password <span class="required">*</span></label>
                        <input type="password" name="password" class="form-control @error('password') is-invalid @enderror" placeholder="Minimal 6 karakter" required>
                    </div>

                    <div class="form-group">
                        <label class="form-label">Konfirmasi Password <span class="required">*</span></label>
                        <input type="password" name="password_confirmation" class="form-control" placeholder="Ulangi password" required>
                    </div>

                    <div class="info-text">
                        <strong>* wajib diisi</strong><br>
                        pastikan No. Whatsapp & Email sudah benar untuk informasi progress layanan!
                    </div>

                    <button type="submit" class="btn btn-register">
                        DAFTAR
                    </button>
                </form>

                <div class="divider">
                    <span>Sudah punya akun?</span>
                </div>

                <a href="{{ route('user.login') }}" class="btn btn-login">
                    <i class="bi bi-arrow-right"></i>
                    MASUK
                </a>
            </div>
        </div>

        <div class="back-link">
            <a href="{{ url('/') }}">
                <i class="bi bi-arrow-left me-1"></i>
                Kembali ke Beranda
            </a>
        </div>
    </div>

    <script>
        // SweetAlert for session messages
        @if(session('success'))
        Swal.fire({
            icon: 'success',
            title: 'Berhasil!',
            text: '{{ session('success') }}',
            confirmButtonColor: '#16a34a'
        });
        @endif

        @if(session('error'))
        Swal.fire({
            icon: 'error',
            title: 'Gagal!',
            text: '{{ session('error') }}',
            confirmButtonColor: '#ef4444'
        });
        @endif

        @if($errors->any())
        Swal.fire({
            icon: 'error',
            title: 'Pendaftaran Gagal!',
            html: `@foreach($errors->all() as $error)<p class="mb-1">{{ $error }}</p>@endforeach`,
            confirmButtonColor: '#ef4444'
        });
        @endif
    </script>
</body>
</html>

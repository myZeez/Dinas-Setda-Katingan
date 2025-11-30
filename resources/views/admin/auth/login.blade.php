<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Admin {{ \App\Helpers\SettingHelper::siteName() }}</title>

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
            --primary-color: #2563eb;
            --primary-dark: #1d4ed8;
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
            background: linear-gradient(135deg, #1e293b 0%, #0f172a 100%);
        }

        .login-wrapper {
            display: flex;
            width: 100%;
            min-height: 100vh;
        }

        .login-left {
            flex: 1;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            padding: 40px;
            color: #fff;
            position: relative;
            overflow: hidden;
        }

        .login-left::before {
            content: '';
            position: absolute;
            width: 500px;
            height: 500px;
            background: rgba(37, 99, 235, 0.1);
            border-radius: 50%;
            top: -200px;
            left: -200px;
        }

        .login-left::after {
            content: '';
            position: absolute;
            width: 300px;
            height: 300px;
            background: rgba(37, 99, 235, 0.1);
            border-radius: 50%;
            bottom: -100px;
            right: -100px;
        }

        .brand-content {
            text-align: center;
            z-index: 1;
        }

        .brand-logo {
            width: 120px;
            height: 120px;
            margin-bottom: 24px;
        }

        .brand-content h1 {
            font-size: 28px;
            font-weight: 700;
            margin-bottom: 8px;
        }

        .brand-content h2 {
            font-size: 18px;
            font-weight: 500;
            opacity: 0.9;
            margin-bottom: 16px;
        }

        .brand-content p {
            font-size: 14px;
            opacity: 0.7;
            max-width: 400px;
        }

        .login-right {
            flex: 1;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 40px;
            background: #fff;
            border-radius: 40px 0 0 40px;
        }

        .login-form-wrapper {
            width: 100%;
            max-width: 400px;
        }

        .login-header {
            margin-bottom: 32px;
        }

        .login-header h3 {
            font-size: 24px;
            font-weight: 700;
            color: #1e293b;
            margin-bottom: 8px;
        }

        .login-header p {
            font-size: 14px;
            color: #64748b;
        }

        .form-floating {
            margin-bottom: 16px;
        }

        .form-floating .form-control {
            border-radius: 10px;
            border: 2px solid #e2e8f0;
            padding: 16px;
            height: 56px;
            font-size: 14px;
        }

        .form-floating .form-control:focus {
            border-color: var(--primary-color);
            box-shadow: 0 0 0 4px rgba(37, 99, 235, 0.1);
        }

        .form-floating label {
            padding: 16px;
            color: #64748b;
        }

        .input-group-text {
            background: transparent;
            border: 2px solid #e2e8f0;
            border-right: none;
            border-radius: 10px 0 0 10px;
            color: #64748b;
        }

        .input-group .form-control {
            border-left: none;
            border-radius: 0 10px 10px 0;
        }

        .input-group:focus-within .input-group-text {
            border-color: var(--primary-color);
        }

        .input-group:focus-within .form-control {
            border-color: var(--primary-color);
        }

        .form-check {
            margin: 16px 0;
        }

        .form-check-input:checked {
            background-color: var(--primary-color);
            border-color: var(--primary-color);
        }

        .btn-login {
            width: 100%;
            padding: 14px;
            font-size: 15px;
            font-weight: 600;
            border-radius: 10px;
            background: var(--primary-color);
            border: none;
            transition: all 0.3s ease;
        }

        .btn-login:hover {
            background: var(--primary-dark);
            transform: translateY(-2px);
            box-shadow: 0 8px 20px rgba(37, 99, 235, 0.4);
        }

        .alert {
            border-radius: 10px;
            font-size: 14px;
        }

        .password-toggle {
            position: absolute;
            right: 16px;
            top: 50%;
            transform: translateY(-50%);
            background: none;
            border: none;
            color: #64748b;
            cursor: pointer;
            z-index: 10;
        }

        .password-wrapper {
            position: relative;
        }

        @media (max-width: 991px) {
            .login-left {
                display: none;
            }

            .login-right {
                border-radius: 0;
            }
        }

        @media (max-width: 576px) {
            .login-right {
                padding: 24px;
            }
        }
    </style>
</head>
<body>
    <div class="login-wrapper">
        <!-- Left Side -->
        <div class="login-left">
            <div class="brand-content">
                <img src="{{ \App\Helpers\SettingHelper::logo() }}" alt="Logo Katingan" class="brand-logo" onerror="this.style.display='none'">
                <h1>SEKRETARIAT DAERAH</h1>
                <h2>Kabupaten Katingan</h2>
                <p>{{ \App\Helpers\SettingHelper::description() }}</p>
            </div>
        </div>

        <!-- Right Side - Login Form -->
        <div class="login-right">
            <div class="login-form-wrapper">
                <div class="login-header">
                    <h3>Selamat Datang! 👋</h3>
                    <p>Silakan login untuk mengakses panel administrator</p>
                </div>

                <form action="{{ route('admin.login.submit') }}" method="POST">
                    @csrf

                    <div class="mb-3">
                        <label class="form-label">Email</label>
                        <div class="input-group">
                            <span class="input-group-text">
                                <i class="bi bi-envelope"></i>
                            </span>
                            <input type="email" name="email" class="form-control" placeholder="Masukkan email" value="{{ old('email') }}" required>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Password</label>
                        <div class="input-group password-wrapper">
                            <span class="input-group-text">
                                <i class="bi bi-lock"></i>
                            </span>
                            <input type="password" name="password" id="password" class="form-control" placeholder="Masukkan password" required>
                            <button type="button" class="password-toggle" onclick="togglePassword()">
                                <i class="bi bi-eye" id="toggleIcon"></i>
                            </button>
                        </div>
                    </div>

                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" name="remember" id="remember">
                        <label class="form-check-label" for="remember">
                            Ingat saya
                        </label>
                    </div>

                    <button type="submit" class="btn btn-primary btn-login">
                        <i class="bi bi-box-arrow-in-right me-2"></i>
                        Masuk
                    </button>
                </form>

                <div class="text-center mt-4">
                    <a href="{{ url('/') }}" class="text-decoration-none" style="color: var(--primary-color);">
                        <i class="bi bi-arrow-left me-1"></i>
                        Kembali ke Website
                    </a>
                </div>
            </div>
        </div>
    </div>

    <script>
        function togglePassword() {
            const password = document.getElementById('password');
            const toggleIcon = document.getElementById('toggleIcon');

            if (password.type === 'password') {
                password.type = 'text';
                toggleIcon.classList.remove('bi-eye');
                toggleIcon.classList.add('bi-eye-slash');
            } else {
                password.type = 'password';
                toggleIcon.classList.remove('bi-eye-slash');
                toggleIcon.classList.add('bi-eye');
            }
        }

        // SweetAlert for session messages
        @if(session('success'))
        Swal.fire({
            icon: 'success',
            title: 'Berhasil!',
            text: '{{ session('success') }}',
            confirmButtonColor: '#2563eb'
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
            title: 'Login Gagal!',
            html: `@foreach($errors->all() as $error)<p class="mb-1">{{ $error }}</p>@endforeach`,
            confirmButtonColor: '#ef4444'
        });
        @endif
    </script>
</body>
</html>

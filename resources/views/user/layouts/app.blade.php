<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="description" content="{{ \App\Helpers\SettingHelper::description() }}">
    <title>@yield('title', 'Dashboard') - Layanan {{ \App\Helpers\SettingHelper::siteName() }}</title>

    <!-- Favicon -->
    <link rel="icon" type="image/x-icon" href="{{ \App\Helpers\SettingHelper::favicon() }}">
    <link rel="shortcut icon" href="{{ \App\Helpers\SettingHelper::favicon() }}">

    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Bootstrap Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css" rel="stylesheet">

    <!-- SweetAlert2 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css" rel="stylesheet">

    <style>
        :root {
            --primary-color: #16a34a;
            --primary-dark: #15803d;
            --primary-light: #dcfce7;
            --secondary-color: #fbbf24;
            --success-color: #10b981;
            --warning-color: #f59e0b;
            --danger-color: #ef4444;
            --sidebar-width: 260px;
            --header-height: 65px;
            --bg-light: #f0fdf4;
            --bg-card: #ffffff;
            --text-primary: #1e293b;
            --text-secondary: #64748b;
            --border-color: #e2e8f0;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Inter', sans-serif;
            background-color: var(--bg-light);
            color: var(--text-primary);
            overflow-x: hidden;
        }

        /* Sidebar Styles */
        .sidebar {
            position: fixed;
            top: 0;
            left: 0;
            width: var(--sidebar-width);
            height: 100vh;
            background: linear-gradient(180deg, #166534 0%, #14532d 100%);
            z-index: 1000;
            transition: transform 0.3s ease;
            overflow-y: auto;
        }

        .sidebar-brand {
            padding: 20px;
            display: flex;
            align-items: center;
            gap: 12px;
            border-bottom: 1px solid rgba(255,255,255,0.1);
        }

        .sidebar-brand img {
            width: 45px;
            height: 45px;
            object-fit: contain;
        }

        .sidebar-brand-text {
            color: #fff;
        }

        .sidebar-brand-text h5 {
            font-size: 14px;
            font-weight: 600;
            margin-bottom: 2px;
        }

        .sidebar-brand-text span {
            font-size: 11px;
            color: rgba(255,255,255,0.6);
        }

        .sidebar-menu {
            padding: 20px 0;
        }

        .menu-label {
            padding: 10px 20px;
            font-size: 11px;
            text-transform: uppercase;
            letter-spacing: 1px;
            color: rgba(255,255,255,0.4);
            font-weight: 600;
        }

        .nav-item {
            margin: 2px 12px;
        }

        .nav-link {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 12px 16px;
            color: rgba(255,255,255,0.7);
            text-decoration: none;
            border-radius: 8px;
            transition: all 0.2s ease;
            font-size: 14px;
            font-weight: 500;
        }

        .nav-link:hover {
            background: rgba(255,255,255,0.1);
            color: #fff;
        }

        .nav-link.active {
            background: var(--secondary-color);
            color: #78350f;
            box-shadow: 0 4px 12px rgba(251, 191, 36, 0.4);
        }

        .nav-link i {
            font-size: 18px;
            width: 24px;
            text-align: center;
        }

        /* Main Content */
        .main-content {
            margin-left: var(--sidebar-width);
            min-height: 100vh;
        }

        /* Header */
        .header {
            height: var(--header-height);
            background: var(--bg-card);
            border-bottom: 1px solid var(--border-color);
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 0 24px;
            position: sticky;
            top: 0;
            z-index: 100;
        }

        .header-left {
            display: flex;
            align-items: center;
            gap: 16px;
        }

        .sidebar-toggle {
            display: none;
            background: none;
            border: none;
            font-size: 24px;
            color: var(--text-primary);
            cursor: pointer;
        }

        .header-right {
            display: flex;
            align-items: center;
            gap: 16px;
        }

        .header-icon {
            width: 40px;
            height: 40px;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 8px;
            background: var(--bg-light);
            color: var(--text-secondary);
            cursor: pointer;
            transition: all 0.2s ease;
            position: relative;
        }

        .header-icon:hover {
            background: var(--primary-color);
            color: #fff;
        }

        .header-icon .badge {
            position: absolute;
            top: -4px;
            right: -4px;
            width: 18px;
            height: 18px;
            background: var(--danger-color);
            color: #fff;
            font-size: 10px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .user-dropdown {
            display: flex;
            align-items: center;
            gap: 10px;
            cursor: pointer;
            padding: 6px 12px;
            border-radius: 8px;
            transition: background 0.2s ease;
        }

        .user-dropdown:hover {
            background: var(--bg-light);
        }

        .user-avatar {
            width: 36px;
            height: 36px;
            border-radius: 8px;
            background: linear-gradient(135deg, var(--primary-color), var(--primary-dark));
            display: flex;
            align-items: center;
            justify-content: center;
            color: #fff;
            font-weight: 600;
            font-size: 14px;
        }

        .user-info h6 {
            font-size: 13px;
            font-weight: 600;
            margin-bottom: 0;
        }

        .user-info span {
            font-size: 11px;
            color: var(--text-secondary);
        }

        /* Content Area */
        .content-wrapper {
            padding: 24px;
        }

        .page-header {
            margin-bottom: 24px;
        }

        .page-title {
            font-size: 24px;
            font-weight: 700;
            color: var(--text-primary);
            margin-bottom: 4px;
        }

        .page-subtitle {
            font-size: 14px;
            color: var(--text-secondary);
        }

        /* Cards */
        .card {
            background: var(--bg-card);
            border: none;
            border-radius: 12px;
            box-shadow: 0 1px 3px rgba(0,0,0,0.05);
            transition: all 0.2s ease;
        }

        .card:hover {
            box-shadow: 0 4px 12px rgba(0,0,0,0.08);
        }

        .stat-card {
            padding: 24px;
        }

        .stat-card .stat-icon {
            width: 48px;
            height: 48px;
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 22px;
        }

        .stat-card .stat-icon.green {
            background: rgba(22, 163, 74, 0.1);
            color: var(--primary-color);
        }

        .stat-card .stat-icon.yellow {
            background: rgba(251, 191, 36, 0.1);
            color: var(--secondary-color);
        }

        .stat-card .stat-icon.blue {
            background: rgba(59, 130, 246, 0.1);
            color: #3b82f6;
        }

        .stat-value {
            font-size: 28px;
            font-weight: 700;
            color: var(--text-primary);
            margin: 12px 0 4px;
        }

        .stat-label {
            font-size: 13px;
            color: var(--text-secondary);
        }

        /* Responsive */
        @media (max-width: 991px) {
            .sidebar {
                transform: translateX(-100%);
            }

            .sidebar.active {
                transform: translateX(0);
            }

            .main-content {
                margin-left: 0;
            }

            .sidebar-toggle {
                display: block;
            }
        }

        @media (max-width: 576px) {
            .user-info {
                display: none;
            }

            .content-wrapper {
                padding: 16px;
            }
        }

        /* Overlay */
        .sidebar-overlay {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0,0,0,0.5);
            z-index: 999;
        }

        .sidebar-overlay.active {
            display: block;
        }

        /* Dropdown Menu */
        .dropdown-menu {
            border: none;
            box-shadow: 0 10px 40px rgba(0,0,0,0.1);
            border-radius: 12px;
            padding: 8px;
        }

        .dropdown-item {
            padding: 10px 16px;
            border-radius: 8px;
            font-size: 14px;
        }

        .dropdown-item:hover {
            background: var(--bg-light);
        }

        .dropdown-item i {
            margin-right: 10px;
            color: var(--text-secondary);
        }

        /* Button Styles */
        .btn {
            font-weight: 500;
            padding: 10px 20px;
            border-radius: 8px;
            font-size: 14px;
            transition: all 0.2s ease;
        }

        .btn-primary {
            background: var(--primary-color);
            border-color: var(--primary-color);
        }

        .btn-primary:hover {
            background: var(--primary-dark);
            border-color: var(--primary-dark);
            transform: translateY(-1px);
            box-shadow: 0 4px 12px rgba(22, 163, 74, 0.4);
        }

        /* Form Styles */
        .form-control, .form-select {
            padding: 10px 16px;
            border-radius: 8px;
            border: 1px solid var(--border-color);
            font-size: 14px;
        }

        .form-control:focus, .form-select:focus {
            border-color: var(--primary-color);
            box-shadow: 0 0 0 3px rgba(22, 163, 74, 0.1);
        }

        .form-label {
            font-weight: 500;
            font-size: 14px;
            margin-bottom: 6px;
        }

        /* Alert */
        .alert {
            border-radius: 10px;
        }
    </style>
    @stack('styles')
</head>
<body>
    <!-- Sidebar -->
    <aside class="sidebar" id="sidebar">
        <div class="sidebar-brand">
            <img src="{{ \App\Helpers\SettingHelper::logo() }}" alt="Logo" onerror="this.style.display='none'">
            <div class="sidebar-brand-text">
                <h5>LAYANAN SETDA</h5>
                <span>Kabupaten Katingan</span>
            </div>
        </div>
        <nav class="sidebar-menu">
            <div class="menu-label">Menu Utama</div>
            <ul class="nav flex-column">
                <li class="nav-item">
                    <a href="{{ route('user.dashboard') }}" class="nav-link {{ request()->routeIs('user.dashboard') ? 'active' : '' }}">
                        <i class="bi bi-grid-1x2-fill"></i>
                        <span>Dashboard</span>
                    </a>
                </li>
            </ul>

            <div class="menu-label">Layanan</div>
            <ul class="nav flex-column">
                <li class="nav-item">
                    <a href="{{ route('user.layanan') }}" class="nav-link {{ request()->routeIs('user.layanan') ? 'active' : '' }}">
                        <i class="bi bi-file-earmark-plus"></i>
                        <span>Ajukan Layanan</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{ route('user.layanan.riwayat') }}" class="nav-link {{ request()->routeIs('user.layanan.riwayat') ? 'active' : '' }}">
                        <i class="bi bi-clock-history"></i>
                        <span>Riwayat Layanan</span>
                    </a>
                </li>
            </ul>

            <div class="menu-label">Akun</div>
            <ul class="nav flex-column">
                <li class="nav-item">
                    <a href="{{ route('user.profile') }}" class="nav-link {{ request()->routeIs('user.profile') ? 'active' : '' }}">
                        <i class="bi bi-person-circle"></i>
                        <span>Profil Saya</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{ route('user.activity-log.index') }}" class="nav-link {{ request()->routeIs('user.activity-log.*') ? 'active' : '' }}">
                        <i class="bi bi-activity"></i>
                        <span>Riwayat Aktivitas</span>
                    </a>
                </li>
            </ul>
        </nav>
    </aside>

    <!-- Overlay -->
    <div class="sidebar-overlay" id="sidebarOverlay"></div>

    <!-- Main Content -->
    <main class="main-content">
        <!-- Header -->
        <header class="header">
            <div class="header-left">
                <button class="sidebar-toggle" id="sidebarToggle">
                    <i class="bi bi-list"></i>
                </button>
                <div>
                    <h6 class="mb-0 fw-semibold">Selamat Datang!</h6>
                    <small class="text-muted">{{ Auth::guard('user')->user()->name }}</small>
                </div>
            </div>
            <div class="header-right">
                <div class="header-icon">
                    <i class="bi bi-bell"></i>
                </div>
                <div class="dropdown">
                    <div class="user-dropdown" data-bs-toggle="dropdown">
                        <div class="user-avatar">
                            {{ substr(Auth::guard('user')->user()->name ?? 'U', 0, 1) }}
                        </div>
                        <div class="user-info">
                            <h6>{{ Auth::guard('user')->user()->name ?? 'User' }}</h6>
                            <span>{{ Auth::guard('user')->user()->instansi ?? '-' }}</span>
                        </div>
                        <i class="bi bi-chevron-down ms-2"></i>
                    </div>
                    <ul class="dropdown-menu dropdown-menu-end">
                        <li><a class="dropdown-item" href="{{ route('user.profile') }}"><i class="bi bi-person"></i> Profil Saya</a></li>
                        <li><hr class="dropdown-divider"></li>
                        <li>
                            <form action="{{ route('user.logout') }}" method="POST">
                                @csrf
                                <button type="submit" class="dropdown-item text-danger">
                                    <i class="bi bi-box-arrow-right"></i> Keluar
                                </button>
                            </form>
                        </li>
                    </ul>
                </div>
            </div>
        </header>

        <!-- Content -->
        <div class="content-wrapper">
            @yield('content')
        </div>
    </main>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

    <!-- SweetAlert2 JS -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script>
        // Sidebar Toggle
        const sidebar = document.getElementById('sidebar');
        const sidebarToggle = document.getElementById('sidebarToggle');
        const sidebarOverlay = document.getElementById('sidebarOverlay');

        sidebarToggle.addEventListener('click', () => {
            sidebar.classList.toggle('active');
            sidebarOverlay.classList.toggle('active');
        });

        sidebarOverlay.addEventListener('click', () => {
            sidebar.classList.remove('active');
            sidebarOverlay.classList.remove('active');
        });

        // SweetAlert2 Notifications
        @if(session('success'))
        Swal.fire({
            icon: 'success',
            title: 'Berhasil!',
            text: '{{ session('success') }}',
            showConfirmButton: false,
            timer: 3000,
            timerProgressBar: true,
            toast: true,
            position: 'top-end'
        });
        @endif

        @if(session('error'))
        Swal.fire({
            icon: 'error',
            title: 'Gagal!',
            text: '{{ session('error') }}',
            showConfirmButton: true,
            confirmButtonColor: '#ef4444'
        });
        @endif

        @if(session('warning'))
        Swal.fire({
            icon: 'warning',
            title: 'Perhatian!',
            text: '{{ session('warning') }}',
            showConfirmButton: true,
            confirmButtonColor: '#f59e0b'
        });
        @endif

        @if(session('info'))
        Swal.fire({
            icon: 'info',
            title: 'Informasi',
            text: '{{ session('info') }}',
            showConfirmButton: false,
            timer: 3000,
            timerProgressBar: true,
            toast: true,
            position: 'top-end'
        });
        @endif

        // Custom Confirm Action Function
        function confirmAction(formId, title, text, icon = 'question', confirmText = 'Ya, Lanjutkan', confirmColor = '#16a34a') {
            Swal.fire({
                title: title,
                text: text,
                icon: icon,
                showCancelButton: true,
                confirmButtonColor: confirmColor,
                cancelButtonColor: '#64748b',
                confirmButtonText: confirmText,
                cancelButtonText: 'Batal',
                reverseButtons: true
            }).then((result) => {
                if (result.isConfirmed) {
                    document.getElementById(formId).submit();
                }
            });
        }

        // Toast notification function
        function showToast(icon, title, text = '') {
            Swal.fire({
                icon: icon,
                title: title,
                text: text,
                showConfirmButton: false,
                timer: 3000,
                timerProgressBar: true,
                toast: true,
                position: 'top-end'
            });
        }
    </script>
    @stack('scripts')
</body>
</html>

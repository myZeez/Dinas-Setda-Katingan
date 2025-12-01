<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="description" content="{{ \App\Helpers\SettingHelper::description() }}">
    <title>@yield('title', 'Admin') - {{ \App\Helpers\SettingHelper::siteName() }}</title>

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
            --primary-color: #2563eb;
            --primary-dark: #1d4ed8;
            --secondary-color: #64748b;
            --success-color: #10b981;
            --warning-color: #f59e0b;
            --danger-color: #ef4444;
            --sidebar-width: 260px;
            --header-height: 65px;
            --bg-light: #f8fafc;
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
            background: linear-gradient(180deg, #1e293b 0%, #0f172a 100%);
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
            background: var(--primary-color);
            color: #fff;
            box-shadow: 0 4px 12px rgba(37, 99, 235, 0.4);
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

        .search-box {
            position: relative;
        }

        .search-box input {
            width: 300px;
            padding: 10px 16px 10px 42px;
            border: 1px solid var(--border-color);
            border-radius: 8px;
            font-size: 14px;
            transition: all 0.2s ease;
            background: var(--bg-light);
        }

        .search-box input:focus {
            outline: none;
            border-color: var(--primary-color);
            box-shadow: 0 0 0 3px rgba(37, 99, 235, 0.1);
        }

        .search-box i {
            position: absolute;
            left: 14px;
            top: 50%;
            transform: translateY(-50%);
            color: var(--text-secondary);
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

        .stat-card .stat-icon.blue {
            background: rgba(37, 99, 235, 0.1);
            color: var(--primary-color);
        }

        .stat-card .stat-icon.green {
            background: rgba(16, 185, 129, 0.1);
            color: var(--success-color);
        }

        .stat-card .stat-icon.orange {
            background: rgba(245, 158, 11, 0.1);
            color: var(--warning-color);
        }

        .stat-card .stat-icon.red {
            background: rgba(239, 68, 68, 0.1);
            color: var(--danger-color);
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

        /* Visitor Statistics Cards */
        .visitor-stat-card {
            display: flex;
            align-items: center;
            gap: 16px;
            padding: 20px;
            background: #f8fafc;
            border-radius: 12px;
            transition: all 0.3s ease;
        }

        .visitor-stat-card:hover {
            background: #f1f5f9;
            transform: translateY(-2px);
        }

        .visitor-stat-icon {
            width: 56px;
            height: 56px;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #fff;
            font-size: 24px;
            flex-shrink: 0;
        }

        .visitor-stat-info {
            display: flex;
            flex-direction: column;
        }

        .visitor-stat-value {
            font-size: 24px;
            font-weight: 700;
            color: var(--text-primary);
            line-height: 1.2;
        }

        .visitor-stat-label {
            font-size: 13px;
            color: var(--text-secondary);
            margin-top: 4px;
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

            .search-box input {
                width: 200px;
            }
        }

        @media (max-width: 576px) {
            .search-box {
                display: none;
            }

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

        /* Table Styles */
        .table {
            margin-bottom: 0;
        }

        .table th {
            font-weight: 600;
            font-size: 12px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            color: var(--text-secondary);
            border-bottom: 2px solid var(--border-color);
            padding: 12px 16px;
        }

        .table td {
            padding: 16px;
            vertical-align: middle;
            border-color: var(--border-color);
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
            box-shadow: 0 4px 12px rgba(37, 99, 235, 0.4);
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
            box-shadow: 0 0 0 3px rgba(37, 99, 235, 0.1);
        }

        .form-label {
            font-weight: 500;
            font-size: 14px;
            margin-bottom: 6px;
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
                <h5>SETDA KATINGAN</h5>
                <span>Panel Administrator</span>
            </div>
        </div>
        <nav class="sidebar-menu">
            <div class="menu-label">Menu Utama</div>
            <ul class="nav flex-column">
                <li class="nav-item">
                    <a href="{{ route('admin.dashboard') }}" class="nav-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                        <i class="bi bi-grid-1x2-fill"></i>
                        <span>Dashboard</span>
                    </a>
                </li>
            </ul>

            <div class="menu-label">Menu Layanan</div>
            <ul class="nav flex-column">
                <li class="nav-item">
                    <a href="{{ route('admin.layanan') }}" class="nav-link {{ request()->routeIs('admin.layanan*') ? 'active' : '' }}">
                        <i class="bi bi-building-gear"></i>
                        <span>Layanan Bag. Pemerintahan</span>
                    </a>
                </li>
            </ul>

            <div class="menu-label">Konten</div>
            <ul class="nav flex-column">
                <li class="nav-item">
                    <a href="{{ route('admin.berita.index') }}" class="nav-link {{ request()->routeIs('admin.berita.*') ? 'active' : '' }}">
                        <i class="bi bi-newspaper"></i>
                        <span>Berita</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{ route('admin.struktur.index') }}" class="nav-link {{ request()->routeIs('admin.struktur.*') ? 'active' : '' }}">
                        <i class="bi bi-diagram-3"></i>
                        <span>Struktur Organisasi</span>
                    </a>
                </li>
            </ul>

            <div class="menu-label">Konten Publik</div>
            <ul class="nav flex-column">
                <li class="nav-item">
                    <a href="{{ route('admin.konten-publik.carousel') }}" class="nav-link {{ request()->routeIs('admin.konten-publik.carousel*') ? 'active' : '' }}">
                        <i class="bi bi-images"></i>
                        <span>Carousel</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{ route('admin.konten-publik.video') }}" class="nav-link {{ request()->routeIs('admin.konten-publik.video*') ? 'active' : '' }}">
                        <i class="bi bi-youtube"></i>
                        <span>Video YouTube</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{ route('admin.konten-publik.pemimpin') }}" class="nav-link {{ request()->routeIs('admin.konten-publik.pemimpin*') ? 'active' : '' }}">
                        <i class="bi bi-person-badge"></i>
                        <span>Pemimpin Daerah</span>
                    </a>
                </li>
            </ul>

            <div class="menu-label">Informasi</div>
            <ul class="nav flex-column">
                <li class="nav-item">
                    <a href="{{ route('admin.informasi-publik.index', 'informasi-publik-bagian-pemerintahan') }}" class="nav-link {{ request()->is('admin/informasi/informasi-publik-bagian-pemerintahan*') ? 'active' : '' }}">
                        <i class="bi bi-info-circle"></i>
                        <span>Informasi Publik Bag. Pemerintahan</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{ route('admin.informasi-publik.index', 'informasi-kewilayahan') }}" class="nav-link {{ request()->is('admin/informasi/informasi-kewilayahan*') ? 'active' : '' }}">
                        <i class="bi bi-geo-alt"></i>
                        <span>Informasi Kewilayahan</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{ route('admin.informasi-publik.index', 'informasi-kerja-sama') }}" class="nav-link {{ request()->is('admin/informasi/informasi-kerja-sama*') ? 'active' : '' }}">
                        <i class="bi bi-handshake"></i>
                        <span>Informasi Kerja Sama</span>
                    </a>
                </li>
            </ul>

            <div class="menu-label">Dokumen</div>
            <ul class="nav flex-column">
                <li class="nav-item">
                    <a href="{{ route('admin.contoh-dokumen.index') }}" class="nav-link {{ request()->routeIs('admin.contoh-dokumen.*') ? 'active' : '' }}">
                        <i class="bi bi-file-earmark-arrow-down"></i>
                        <span>Contoh Dokumen</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="#" class="nav-link {{ request()->routeIs('admin.kontrak.*') ? 'active' : '' }}">
                        <i class="bi bi-file-earmark-text"></i>
                        <span>Kontrak</span>
                    </a>
                </li>
            </ul>

            @if(auth()->user()->role === 'super_admin')
            <div class="menu-label">Manajemen</div>
            <ul class="nav flex-column">
                <li class="nav-item">
                    <a href="{{ route('admin.users.admin') }}" class="nav-link {{ request()->routeIs('admin.users.admin*') ? 'active' : '' }}">
                        <i class="bi bi-shield-fill"></i>
                        <span>Kelola Admin</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{ route('admin.users.user') }}" class="nav-link {{ request()->routeIs('admin.users.user*') ? 'active' : '' }}">
                        <i class="bi bi-people-fill"></i>
                        <span>Kelola User</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{ route('admin.activity-log.index') }}" class="nav-link {{ request()->routeIs('admin.activity-log.*') ? 'active' : '' }}">
                        <i class="bi bi-clock-history"></i>
                        <span>Log Aktivitas</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{ route('admin.passport.index') }}" class="nav-link {{ request()->routeIs('admin.passport.*') ? 'active' : '' }}">
                        <i class="bi bi-key-fill"></i>
                        <span>Passport Monitor</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="/api/documentation" class="nav-link" target="_blank">
                        <i class="bi bi-file-earmark-code"></i>
                        <span>Dokumentasi API</span>
                    </a>
                </li>
            </ul>
            @endif

            <div class="menu-label">Pengaturan</div>
            <ul class="nav flex-column">
                <li class="nav-item">
                    <a href="{{ route('admin.settings.website') }}" class="nav-link {{ request()->routeIs('admin.settings.website*') ? 'active' : '' }}">
                        <i class="bi bi-gear"></i>
                        <span>Pengaturan Website</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{ route('admin.settings.profile') }}" class="nav-link {{ request()->routeIs('admin.settings.profile*') ? 'active' : '' }}">
                        <i class="bi bi-person-circle"></i>
                        <span>Profil Admin</span>
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
                <div class="search-box">
                    <i class="bi bi-search"></i>
                    <input type="text" placeholder="Cari menu atau data...">
                </div>
            </div>
            <div class="header-right">
                <div class="header-icon">
                    <i class="bi bi-bell"></i>
                    <span class="badge">3</span>
                </div>
                <div class="dropdown">
                    <div class="user-dropdown" data-bs-toggle="dropdown">
                        <div class="user-avatar">
                            {{ substr(Auth::user()->name ?? 'A', 0, 1) }}
                        </div>
                        <div class="user-info">
                            <h6>{{ Auth::user()->name ?? 'Administrator' }}</h6>
                            <span>Admin</span>
                        </div>
                        <i class="bi bi-chevron-down ms-2"></i>
                    </div>
                    <ul class="dropdown-menu dropdown-menu-end">
                        <li><a class="dropdown-item" href="#"><i class="bi bi-person"></i> Profil Saya</a></li>
                        <li><a class="dropdown-item" href="#"><i class="bi bi-gear"></i> Pengaturan</a></li>
                        <li><hr class="dropdown-divider"></li>
                        <li>
                            <form action="{{ route('admin.logout') }}" method="POST">
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

        // Custom Confirm Delete Function
        function confirmDelete(formId, title = 'Hapus Data?', text = 'Data yang dihapus tidak dapat dikembalikan!') {
            Swal.fire({
                title: title,
                text: text,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#ef4444',
                cancelButtonColor: '#64748b',
                confirmButtonText: '<i class="bi bi-trash me-1"></i> Ya, Hapus!',
                cancelButtonText: '<i class="bi bi-x me-1"></i> Batal',
                reverseButtons: true
            }).then((result) => {
                if (result.isConfirmed) {
                    document.getElementById(formId).submit();
                }
            });
        }

        // Custom Confirm Action Function
        function confirmAction(formId, title, text, icon = 'question', confirmText = 'Ya, Lanjutkan', confirmColor = '#2563eb') {
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

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name', 'Sistem Penggajian Karyawan') }}</title>

    <!-- Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Bootstrap Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">

    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">

    <style>
        :root {
            --sidebar-width: 280px;
            --header-height: 70px;
            --primary-color: #4f46e5;
            --secondary-color: #6366f1;
            --dark-bg: #0f172a;
            --sidebar-bg: #1e293b;
            --hover-bg: rgba(255, 255, 255, 0.08);
            --text-muted: #94a3b8;
            --border-color: #e2e8f0;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Inter', system-ui, -apple-system, sans-serif;
            background: #f8fafc;
            overflow-x: hidden;
        }

        /* ============ SIDEBAR ============ */
        .sidebar {
            position: fixed;
            top: 0;
            left: 0;
            height: 100vh;
            width: var(--sidebar-width);
            background: linear-gradient(180deg, var(--dark-bg) 0%, var(--sidebar-bg) 100%);
            padding: 24px 16px;
            z-index: 1000;
            overflow-y: auto;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            box-shadow: 4px 0 24px rgba(0, 0, 0, 0.12);
        }

        .sidebar::-webkit-scrollbar {
            width: 6px;
        }

        .sidebar::-webkit-scrollbar-track {
            background: rgba(255, 255, 255, 0.05);
        }

        .sidebar::-webkit-scrollbar-thumb {
            background: rgba(255, 255, 255, 0.2);
            border-radius: 3px;
        }

        .sidebar-brand {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 12px 16px;
            margin-bottom: 32px;
            color: #fff;
            text-decoration: none;
            transition: all 0.3s ease;
        }

        .sidebar-brand:hover {
            transform: translateX(4px);
        }

        .sidebar-brand-icon {
            width: 42px;
            height: 42px;
            background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 20px;
            font-weight: 700;
            box-shadow: 0 4px 12px rgba(79, 70, 229, 0.4);
        }

        .sidebar-brand-text {
            font-size: 18px;
            font-weight: 700;
            letter-spacing: -0.5px;
        }

        .sidebar-brand-text small {
            display: block;
            font-size: 11px;
            font-weight: 400;
            color: var(--text-muted);
            margin-top: 2px;
        }

        /* Menu Section */
        .menu-section {
            margin-bottom: 24px;
        }

        .menu-section-title {
            font-size: 11px;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 1px;
            color: var(--text-muted);
            padding: 0 16px;
            margin-bottom: 8px;
        }

        .sidebar-nav {
            list-style: none;
        }

        .nav-item {
            margin-bottom: 4px;
        }

        .nav-link {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 12px 16px;
            color: rgba(255, 255, 255, 0.7);
            text-decoration: none;
            border-radius: 12px;
            font-size: 14px;
            font-weight: 500;
            transition: all 0.2s ease;
            position: relative;
            overflow: hidden;
        }

        .nav-link::before {
            content: '';
            position: absolute;
            left: 0;
            top: 0;
            height: 100%;
            width: 3px;
            background: var(--primary-color);
            transform: scaleY(0);
            transition: transform 0.2s ease;
        }

        .nav-link:hover {
            background: var(--hover-bg);
            color: #fff;
            transform: translateX(4px);
        }

        .nav-link.active {
            background: linear-gradient(90deg, rgba(79, 70, 229, 0.2), rgba(99, 102, 241, 0.1));
            color: #fff;
            font-weight: 600;
        }

        .nav-link.active::before {
            transform: scaleY(1);
        }

        .nav-link i {
            font-size: 18px;
            width: 20px;
            text-align: center;
        }

        .nav-badge {
            margin-left: auto;
            padding: 2px 8px;
            background: rgba(239, 68, 68, 0.9);
            color: #fff;
            font-size: 11px;
            font-weight: 600;
            border-radius: 12px;
        }

        /* ============ MAIN CONTENT ============ */
        .main-content {
            margin-left: var(--sidebar-width);
            min-height: 100vh;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }

        /* Header */
        .header {
            height: var(--header-height);
            background: #fff;
            border-bottom: 1px solid var(--border-color);
            padding: 0 32px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            position: sticky;
            top: 0;
            z-index: 999;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.05);
        }

        .header-left {
            display: flex;
            align-items: center;
            gap: 20px;
        }

        .sidebar-toggle {
            display: none;
            background: none;
            border: none;
            font-size: 24px;
            color: #64748b;
            cursor: pointer;
            padding: 8px;
            border-radius: 8px;
            transition: all 0.2s ease;
        }

        .sidebar-toggle:hover {
            background: #f1f5f9;
            color: var(--primary-color);
        }

        .search-box {
            position: relative;
            width: 320px;
        }

        .search-box input {
            width: 100%;
            padding: 10px 16px 10px 42px;
            border: 1px solid var(--border-color);
            border-radius: 12px;
            font-size: 14px;
            transition: all 0.2s ease;
            background: #f8fafc;
        }

        .search-box input:focus {
            outline: none;
            border-color: var(--primary-color);
            background: #fff;
            box-shadow: 0 0 0 4px rgba(79, 70, 229, 0.1);
        }

        .search-box i {
            position: absolute;
            left: 14px;
            top: 50%;
            transform: translateY(-50%);
            color: #94a3b8;
        }

        .header-right {
            display: flex;
            align-items: center;
            gap: 16px;
        }

        .header-icon {
            position: relative;
            width: 40px;
            height: 40px;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 10px;
            color: #64748b;
            cursor: pointer;
            transition: all 0.2s ease;
            background: #f8fafc;
        }

        .header-icon:hover {
            background: #f1f5f9;
            color: var(--primary-color);
        }

        .header-icon .badge {
            position: absolute;
            top: -4px;
            right: -4px;
            width: 18px;
            height: 18px;
            background: #ef4444;
            color: #fff;
            font-size: 10px;
            font-weight: 600;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            border: 2px solid #fff;
        }

        .user-menu {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 8px 12px;
            border-radius: 12px;
            cursor: pointer;
            transition: all 0.2s ease;
        }

        .user-menu:hover {
            background: #f8fafc;
        }

        .user-avatar {
            width: 40px;
            height: 40px;
            border-radius: 10px;
            background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
            color: #fff;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 600;
            font-size: 16px;
        }

        .user-info {
            display: flex;
            flex-direction: column;
        }

        .user-name {
            font-size: 14px;
            font-weight: 600;
            color: #1e293b;
            line-height: 1.2;
        }

        .user-role {
            font-size: 12px;
            color: #64748b;
        }

        /* Content Area */
        .content-area {
            padding: 24px 32px;
            max-width: 100%;
        }

        /* Alerts */
        .alert {
            border-radius: 12px;
            border: none;
            padding: 16px 20px;
            display: flex;
            align-items: center;
            gap: 12px;
            font-size: 14px;
            font-weight: 500;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08);
            animation: slideInDown 0.3s ease;
        }

        @keyframes slideInDown {
            from {
                transform: translateY(-20px);
                opacity: 0;
            }
            to {
                transform: translateY(0);
                opacity: 1;
            }
        }

        .alert-success {
            background: linear-gradient(135deg, #ecfdf5, #d1fae5);
            color: #065f46;
        }

        .alert-danger {
            background: linear-gradient(135deg, #fef2f2, #fee2e2);
            color: #991b1b;
        }

        .alert-warning {
            background: linear-gradient(135deg, #fffbeb, #fef3c7);
            color: #92400e;
        }

        .alert i {
            font-size: 20px;
        }

        /* Dropdown Menu */
        .dropdown-menu {
            border: none;
            border-radius: 12px;
            box-shadow: 0 10px 40px rgba(0, 0, 0, 0.15);
            padding: 8px;
            min-width: 200px;
        }

        .dropdown-item {
            padding: 10px 14px;
            border-radius: 8px;
            font-size: 14px;
            font-weight: 500;
            transition: all 0.2s ease;
        }

        .dropdown-item:hover {
            background: #f1f5f9;
            color: var(--primary-color);
        }

        .dropdown-item i {
            width: 20px;
            text-align: center;
        }

        .dropdown-divider {
            margin: 8px 0;
            opacity: 0.1;
        }

        /* Mobile Sidebar */
        .sidebar-overlay {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.5);
            z-index: 999;
            opacity: 0;
            transition: opacity 0.3s ease;
        }

        .sidebar-overlay.active {
            opacity: 1;
        }

        /* Mobile Responsive */
        @media (max-width: 991px) {
            .sidebar {
                transform: translateX(-100%);
            }

            .sidebar.active {
                transform: translateX(0);
            }

            .sidebar-overlay {
                display: block;
            }

            .main-content {
                margin-left: 0;
            }

            .sidebar-toggle {
                display: block;
            }

            .search-box {
                display: none;
            }

            .header {
                padding: 0 20px;
            }

            .content-area {
                padding: 20px;
            }

            .user-info {
                display: none;
            }
        }

        @media (max-width: 575px) {
            .header-icon:not(:last-child) {
                display: none;
            }
        }

        /* Card Enhancements */
        .card {
            border: none;
            border-radius: 16px;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.08);
            transition: all 0.3s ease;
        }

        .card:hover {
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.12);
        }

        /* Scrollbar Styling */
        ::-webkit-scrollbar {
            width: 8px;
            height: 8px;
        }

        ::-webkit-scrollbar-track {
            background: #f1f5f9;
        }

        ::-webkit-scrollbar-thumb {
            background: #cbd5e1;
            border-radius: 4px;
        }

        ::-webkit-scrollbar-thumb:hover {
            background: #94a3b8;
        }
    </style>

    @stack('styles')
</head>

<body>

<!-- Sidebar Overlay (Mobile) -->
<div class="sidebar-overlay" id="sidebarOverlay" onclick="toggleSidebar()"></div>

<!-- Sidebar -->
<aside class="sidebar" id="sidebar">
    <!-- Brand -->
    <a href="/dashboard" class="sidebar-brand">
        <div class="sidebar-brand-icon">
            <i class="bi bi-wallet2"></i>
        </div>
        <div class="sidebar-brand-text">
            Sistem Karyawan
            <small></small>
        </div>
    </a>

    <!-- Main Menu -->
    <div class="menu-section">
        <div class="menu-section-title">Menu Utama</div>
        <ul class="sidebar-nav">
            <li class="nav-item">
                <a class="nav-link {{ request()->path() == 'dashboard' ? 'active' : '' }}" href="/dashboard">
                    <i class="bi bi-speedometer2"></i>
                    <span>Dashboard</span>
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link {{ request()->routeIs('karyawan.*') ? 'active' : '' }}" href="/karyawan">
                    <i class="bi bi-people"></i>
                    <span>Karyawan</span>
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link {{ request()->routeIs('attendance.*') ? 'active' : '' }}" href="/attendance">
                    <i class="bi bi-calendar-check"></i>
                    <span>Kehadiran</span>
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link {{ request()->routeIs('absen.*') ? 'active' : '' }}" href="{{ route('absen.import') }}">
                    <i class="bi bi-upload"></i>
                    <span>Import Absensi</span>
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link {{ request()->routeIs('absen.process*') ? 'active' : '' }}" href="{{ route('absen.process') }}">
                    <i class="bi bi-cogs"></i>
                    <span>Proses Absensi</span>
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link {{ request()->routeIs('payroll.*') ? 'active' : '' }}" href="/payroll">
                    <i class="bi bi-cash-stack"></i>
                    <span>Penggajian</span>
                </a>
            </li>
        </ul>
    </div>

    <!-- Management -->
    <div class="menu-section">
        <div class="menu-section-title">Manajemen</div>
        <ul class="sidebar-nav">
            <li class="nav-item">
                <a class="nav-link {{ request()->routeIs('allowance.*') ? 'active' : '' }}" href="/allowance-config">
                    <i class="bi bi-wallet2"></i>
                    <span>Tunjangan</span>
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link {{ request()->routeIs('leave.*') ? 'active' : '' }}" href="/leave">
                    <i class="bi bi-file-earmark-text"></i>
                    <span>Cuti</span>
                    @if(\App\Models\Leave::whereNull('approved_at')->count() > 0)
                        <span class="nav-badge">{{ \App\Models\Leave::whereNull('approved_at')->count() }}</span>
                    @endif
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link {{ request()->routeIs('overtime-permit.*') ? 'active' : '' }}" href="/overtime-permit">
                    <i class="bi bi-clock-history"></i>
                    <span>Lembur</span>
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link {{ request()->routeIs('work-calendars.*') ? 'active' : '' }}" href="/work-calendars">
                    <i class="bi bi-calendar-event"></i>
                    <span>Kalender Kerja</span>
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link {{ request()->routeIs('holiday-compensation.*') ? 'active' : '' }}" href="/holiday-compensation/report">
                    <i class="bi bi-gift"></i>
                    <span>Kompensasi</span>
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link {{ request()->routeIs('points.*') ? 'active' : '' }}" href="/points">
                    <i class="bi bi-star"></i>
                    <span>Poin Karyawan</span>
                </a>
            </li>
        </ul>
    </div>

    <!-- Settings -->
    <div class="menu-section">
        <div class="menu-section-title">Pengaturan</div>
        <ul class="sidebar-nav">
            <li class="nav-item">
                <a class="nav-link" href="/settings">
                    <i class="bi bi-gear"></i>
                    <span>Pengaturan</span>
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="/help">
                    <i class="bi bi-question-circle"></i>
                    <span>Bantuan</span>
                </a>
            </li>
        </ul>
    </div>
</aside>

<!-- Main Content -->
<div class="main-content">
    
    <!-- Header -->
    <header class="header">
        <div class="header-left">
            <button class="sidebar-toggle" onclick="toggleSidebar()">
                <i class="bi bi-list"></i>
            </button>
            
            <div class="search-box">
                <i class="bi bi-search"></i>
                <input type="text" placeholder="Cari karyawan, laporan, atau menu...">
            </div>
        </div>

        <div class="header-right">
            <!-- Notifications -->
            <div class="header-icon" data-bs-toggle="dropdown">
                <i class="bi bi-bell"></i>
                <span class="badge">3</span>
            </div>
            <ul class="dropdown-menu dropdown-menu-end">
                <li><h6 class="dropdown-header">Notifikasi</h6></li>
                <li><a class="dropdown-item" href="#"><i class="bi bi-info-circle me-2"></i>3 Pengajuan cuti baru</a></li>
                <li><a class="dropdown-item" href="#"><i class="bi bi-clock me-2"></i>Periode gaji akan berakhir</a></li>
                <li><hr class="dropdown-divider"></li>
                <li><a class="dropdown-item text-center text-primary" href="#">Lihat Semua</a></li>
            </ul>

            <!-- Messages -->
            <div class="header-icon">
                <i class="bi bi-chat-dots"></i>
            </div>

            <!-- User Menu -->
            <div class="user-menu" data-bs-toggle="dropdown">
                <div class="user-avatar">
                    {{ strtoupper(substr(auth()->user()->name ?? 'U', 0, 1)) }}
                </div>
                <div class="user-info">
                    <div class="user-name">{{ auth()->user()->name ?? 'User' }}</div>
                    <div class="user-role">Administrator</div>
                </div>
                <i class="bi bi-chevron-down" style="color: #94a3b8; font-size: 12px;"></i>
            </div>
            <ul class="dropdown-menu dropdown-menu-end">
                <li><a class="dropdown-item" href="/profile"><i class="bi bi-person me-2"></i>Profil Saya</a></li>
                <li><a class="dropdown-item" href="/settings"><i class="bi bi-gear me-2"></i>Pengaturan</a></li>
                <li><hr class="dropdown-divider"></li>
                <li>
                    <form action="/logout" method="POST">
                        @csrf
                        <button class="dropdown-item text-danger" type="submit">
                            <i class="bi bi-box-arrow-right me-2"></i>Logout
                        </button>
                    </form>
                </li>
            </ul>
        </div>
    </header>

    <!-- Content Area -->
    <div class="content-area">
        
        @if ($message = session('success'))
            <div class="alert alert-success alert-dismissible fade show mb-4">
                <i class="bi bi-check-circle-fill"></i>
                <span>{{ $message }}</span>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        @if ($message = session('error'))
            <div class="alert alert-danger alert-dismissible fade show mb-4">
                <i class="bi bi-exclamation-triangle-fill"></i>
                <span>{{ $message }}</span>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        @if ($message = session('warning'))
            <div class="alert alert-warning alert-dismissible fade show mb-4">
                <i class="bi bi-exclamation-circle-fill"></i>
                <span>{{ $message }}</span>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        @yield('content')

    </div>

</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

<script>
// Sidebar Toggle
function toggleSidebar() {
    const sidebar = document.getElementById('sidebar');
    const overlay = document.getElementById('sidebarOverlay');
    
    sidebar.classList.toggle('active');
    overlay.classList.toggle('active');
}

// Close sidebar when clicking outside on mobile
document.addEventListener('click', function(e) {
    const sidebar = document.getElementById('sidebar');
    const toggle = document.querySelector('.sidebar-toggle');
    
    if (window.innerWidth <= 991) {
        if (!sidebar.contains(e.target) && !toggle.contains(e.target)) {
            sidebar.classList.remove('active');
            document.getElementById('sidebarOverlay').classList.remove('active');
        }
    }
});

// Auto-hide alerts after 5 seconds
document.addEventListener('DOMContentLoaded', function() {
    const alerts = document.querySelectorAll('.alert');
    alerts.forEach(function(alert) {
        setTimeout(function() {
            const bsAlert = new bootstrap.Alert(alert);
            bsAlert.close();
        }, 5000);
    });
});

// Search functionality (basic example)
const searchInput = document.querySelector('.search-box input');
if (searchInput) {
    searchInput.addEventListener('input', function(e) {
        // Implement your search logic here
        console.log('Searching for:', e.target.value);
    });
}
</script>

@stack('scripts')

</body>
</html>
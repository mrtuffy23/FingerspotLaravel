<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ config('app.name', 'Sistem Penggajian Karyawan') }}</title>
    <link rel="stylesheet" href="{{ asset('css/work-calendar.css') }}">

    <!-- Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">

    <style>
        body {
            background: linear-gradient(to bottom right, #eef2f7, #f9fafb);
            min-height: 100vh;
        }

        /* Navbar Modern */
        .navbar-custom {
            backdrop-filter: blur(10px);
            background: rgba(33, 37, 41, 0.85) !important;
            padding: 12px 20px;
            border-bottom: 1px solid rgba(255,255,255,0.1);
        }

        .navbar-brand {
            font-weight: 700;
            font-size: 20px;
            letter-spacing: 0.5px;
        }

        .nav-link {
            padding: 8px 14px !important;
            font-weight: 500;
            transition: 0.2s;
            border-radius: 8px;
        }

        .nav-link:hover {
            background: rgba(255,255,255,0.1);
        }

        .nav-link.active {
            background: #0d6efd;
            color: white !important;
            border-radius: 8px;
        }

        /* Main Content */
        .main-content {
            padding: 30px;
            animation: fade 0.3s ease-in-out;
        }

        @keyframes fade {
            from { opacity: 0; transform: translateY(10px); }
            to   { opacity: 1; transform: translateY(0); }
        }

        /* Alerts */
        .alert {
            border-radius: 10px;
            box-shadow: 0px 3px 10px rgba(0,0,0,0.05);
        }

    </style>
</head>

<body>

<nav class="navbar navbar-expand-lg navbar-dark navbar-custom shadow-sm">
    <div class="container-fluid">

        <a class="navbar-brand d-flex align-items-center" href="/dashboard">
            <i class="bi bi-speedometer2 me-2"></i> Penggajian System
        </a>

        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="navbarNav">

            <ul class="navbar-nav me-auto">

                <li class="nav-item">
                    <a class="nav-link {{ request()->path() == 'dashboard' ? 'active' : '' }}" href="/dashboard">
                        <i class="bi bi-house-door me-1"></i> Dashboard
                    </a>
                </li>

                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('karyawan.*') ? 'active' : '' }}" href="/karyawan">
                        <i class="bi bi-people me-1"></i> Karyawan
                    </a>
                </li>

                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('attendance.*') ? 'active' : '' }}" href="/attendance">
                        <i class="bi bi-calendar-check me-1"></i> Kehadiran
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('payroll.*') ? 'active' : '' }}" href="/payroll">
                        <i class="bi bi-cash-stack me-1"></i> Penggajian
                    </a>
                </li>

                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('leave.*') ? 'active' : '' }}" href="/leave">
                        <i class="bi bi-file-earmark-text me-1"></i> Manajemen Cuti
                    </a>
                </li>

                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('overtime-permit.*') ? 'active' : '' }}" href="/overtime-permit">
                        <i class="bi bi-clock-history me-1"></i> Izin Lembur
                    </a>
                </li>

                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('work-calendars.*') ? 'active' : '' }}" href="/work-calendars">
                        <i class="bi bi-calendar-event me-1"></i> Kalender Kerja
                    </a>
                </li>

                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('holiday-compensation.*') ? 'active' : '' }}" href="/holiday-compensation/report">
                        <i class="bi bi-gift me-1"></i> Kompensasi Libur
                    </a>
                </li>

            </ul>

            <!-- User Dropdown -->
            <ul class="navbar-nav ms-auto">
                <li class="nav-item dropdown">

                    <a class="nav-link dropdown-toggle d-flex align-items-center" href="#" id="userDropdown"
                       role="button" data-bs-toggle="dropdown">

                        <i class="bi bi-person-circle me-1"></i>
                        {{ auth()->user()->name ?? 'User' }}
                    </a>

                    <ul class="dropdown-menu dropdown-menu-end shadow-sm">

                        <li>
                            <a class="dropdown-item" href="/profile">
                                <i class="bi bi-gear me-2"></i> Profile
                            </a>
                        </li>

                        <li><hr class="dropdown-divider"></li>

                        <li>
                            <form action="/logout" method="POST">
                                @csrf
                                <button type="submit" class="dropdown-item">
                                    <i class="bi bi-box-arrow-right me-2"></i> Logout
                                </button>
                            </form>
                        </li>

                    </ul>

                </li>
            </ul>

        </div>
    </div>
</nav>

<!-- Main Content -->
<div class="container-fluid">
    <div class="main-content">

        @if ($message = session('success'))
            <div class="alert alert-success alert-dismissible fade show">
                <i class="bi bi-check-circle me-2"></i> {{ $message }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        @if ($message = session('error'))
            <div class="alert alert-danger alert-dismissible fade show">
                <i class="bi bi-exclamation-triangle me-2"></i> {{ $message }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        @yield('content')

    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>

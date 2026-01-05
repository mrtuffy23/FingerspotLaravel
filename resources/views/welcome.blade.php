<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ config('app.name', 'Sistem Penggajian') }}</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">

    <style>
        body {
            background: #f8fafc;
            font-family: system-ui, -apple-system, BlinkMacSystemFont, "Segoe UI", sans-serif;
        }

        /* HERO */
        .hero {
            min-height: 90vh;
            background: linear-gradient(135deg, #0f172a, #1e293b);
            color: #fff;
            display: flex;
            align-items: center;
        }

        .hero-badge {
            background: rgba(255,255,255,.1);
            border-radius: 999px;
            padding: 6px 14px;
            font-size: 14px;
            display: inline-block;
            margin-bottom: 20px;
        }

        /* FEATURE */
        .feature {
            background: #fff;
            border-radius: 20px;
            padding: 30px;
            height: 100%;
            box-shadow: 0 15px 40px rgba(0,0,0,.06);
            transition: .3s ease;
        }

        .feature:hover {
            transform: translateY(-6px);
            box-shadow: 0 25px 60px rgba(0,0,0,.12);
        }

        .feature i {
            font-size: 28px;
            color: #2563eb;
        }

        /* CTA */
        .cta {
            background: linear-gradient(135deg, #2563eb, #1d4ed8);
            color: #fff;
            border-radius: 30px;
        }

        footer {
            background: #fff;
        }
    </style>
</head>

<body>

<!-- HERO -->
<section class="hero">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-lg-6">
                <span class="hero-badge">
                     Sistem HR & Payroll Terintegrasi
                </span>
                <h1 class="display-5 fw-bold mb-3">
                    Kelola SDM & Gaji <br> Lebih Cepat & Akurat
                </h1>
                <p class="lead text-white-50 mb-4">
                    Platform modern untuk manajemen karyawan, absensi fingerprint,
                    cuti, dan penggajian otomatis dalam satu sistem.
                </p>

                @auth
                    <a href="{{ route('dashboard') }}" class="btn btn-light btn-lg rounded-pill px-4">
                        Masuk Dashboard
                    </a>
                @else
                    <a href="/login" class="btn btn-light btn-lg rounded-pill px-4 me-2">
                        Login
                    </a>
                    <a href="/register" class="btn btn-outline-light btn-lg rounded-pill px-4">
                        Daftar
                    </a>
                @endauth
            </div>

            <div class="col-lg-6 text-center d-none d-lg-block">
                <i class="bi bi-building display-1 opacity-50"></i>
            </div>
        </div>
    </div>
</section>

<!-- FEATURES -->
<section class="py-5">
    <div class="container">
        <div class="text-center mb-5">
            <h2 class="fw-bold">Semua Kebutuhan HR dalam Satu Sistem</h2>
            <p class="text-muted">
                Dirancang untuk perusahaan modern & instansi
            </p>
        </div>

        <div class="row g-4">
            <div class="col-md-4">
                <div class="feature">
                    <i class="bi bi-people"></i>
                    <h5 class="fw-semibold mt-3">Manajemen Karyawan</h5>
                    <p class="text-muted mb-0">
                        Data karyawan, jabatan, dan departemen terstruktur.
                    </p>
                </div>
            </div>

            <div class="col-md-4">
                <div class="feature">
                    <i class="bi bi-fingerprint"></i>
                    <h5 class="fw-semibold mt-3">Absensi Otomatis</h5>
                    <p class="text-muted mb-0">
                        Integrasi mesin fingerprint & rekap otomatis.
                    </p>
                </div>
            </div>

            <div class="col-md-4">
                <div class="feature">
                    <i class="bi bi-cash-coin"></i>
                    <h5 class="fw-semibold mt-3">Penggajian Cerdas</h5>
                    <p class="text-muted mb-0">
                        Hitung gaji, lembur, tunjangan & potongan akurat.
                    </p>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- CTA -->
<section class="py-5">
    <div class="container">
        <div class="cta p-5 text-center">
            <h3 class="fw-bold mb-3">
                Siap Digitalisasi Sistem HR Anda?
            </h3>
            <p class="opacity-75 mb-4">
                Mulai gunakan sistem penggajian yang cepat, aman, dan profesional.
            </p>

            @guest
                <a href="/register" class="btn btn-light btn-lg rounded-pill px-5">
                    Mulai Sekarang
                </a>
            @endguest
        </div>
    </div>
</section>

<!-- FOOTER -->
<footer class="py-4 border-top">
    <div class="container text-center">
        <small class="text-muted">
            © 2025 Sistem Penggajian • Version 1.0.0
        </small>
    </div>
</footer>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>

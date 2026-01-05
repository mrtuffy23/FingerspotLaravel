<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Sistem Penggajian</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">

    <style>
        body {
            min-height: 100vh;
            background: linear-gradient(135deg, #0f172a, #1e293b);
            display: flex;
            align-items: center;
            font-family: system-ui, -apple-system, "Segoe UI", sans-serif;
        }

        .login-card {
            border: none;
            border-radius: 24px;
            box-shadow: 0 30px 80px rgba(0,0,0,.35);
        }

        .form-control {
            padding: 14px 16px;
            border-radius: 14px;
        }

        .btn-login {
            padding: 12px;
            border-radius: 999px;
            font-weight: 600;
        }

        .badge-demo {
            background: #e0f2fe;
            color: #0369a1;
            border-radius: 999px;
            padding: 6px 14px;
            font-size: 13px;
        }
    </style>
</head>
<body>

<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-6 col-lg-5 col-xl-4">
            <div class="card login-card">
                <div class="card-body p-4 p-md-5">

                    <div class="text-center mb-4">
                        <i class="bi bi-building-check fs-1 text-primary"></i>
                        <h3 class="fw-bold mt-3 mb-1">Masuk ke Sistem</h3>
                        <p class="text-muted mb-0">Sistem Penggajian & Absensi</p>
                    </div>

                    <div class="text-center mb-4">
                        <span class="badge-demo">
                            Demo Mode Aktif
                        </span>
                    </div>

                    <form action="{{ route('login.store') }}" method="POST">
                        @csrf

                        <div class="mb-3">
                            <label class="form-label small">Email</label>
                            <input type="email"
                                   class="form-control"
                                   name="email"
                                   placeholder="admin@example.com"
                                   required>
                        </div>

                        <div class="mb-4">
                            <label class="form-label small">Password</label>
                            <input type="password"
                                   class="form-control"
                                   name="password"
                                   placeholder="password"
                                   required>
                        </div>

                        <button type="submit" class="btn btn-primary btn-login w-100">
                            <i class="bi bi-box-arrow-in-right me-1"></i>
                            Login
                        </button>
                    </form>

                    <div class="text-center text-muted small mt-4">
                        <div>Demo Akun:</div>
                        <div><strong>admin@example.com</strong></div>
                        <div><strong>password</strong></div>
                    </div>

                    <div class="text-center mt-4">
                        <a href="/" class="text-decoration-none small">
                            ← Kembali ke Beranda
                        </a>
                    </div>

                </div>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>404 - Halaman Tidak Ditemukan</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">

    <style>
        body {
            min-height: 100vh;
            background: linear-gradient(135deg, #0f172a, #1e293b);
            display: flex;
            align-items: center;
            justify-content: center;
            color: #fff;
            font-family: system-ui, -apple-system, "Segoe UI", sans-serif;
        }

        .error-card {
            background: #ffffff;
            color: #0f172a;
            border-radius: 28px;
            padding: 48px 40px;
            max-width: 420px;
            width: 100%;
            box-shadow: 0 40px 100px rgba(0,0,0,.35);
            text-align: center;
        }

        .error-code {
            font-size: 96px;
            font-weight: 800;
            line-height: 1;
            background: linear-gradient(135deg, #2563eb, #1d4ed8);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }

        .btn-home {
            border-radius: 999px;
            padding: 12px 28px;
            font-weight: 600;
        }
    </style>
</head>
<body>

<div class="error-card">
    <div class="error-code">404</div>
    <h3 class="fw-bold mt-2 mb-2">Halaman Tidak Ditemukan</h3>
    <p class="text-muted mb-4">
        Halaman yang Anda cari tidak tersedia atau sudah dipindahkan.
    </p>

    <a href="{{ url('/') }}" class="btn btn-primary btn-home">
        <i class="bi bi-house-door-fill me-1"></i>
        Kembali ke Beranda
    </a>
</div>

</body>
</html>

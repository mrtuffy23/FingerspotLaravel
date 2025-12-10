<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ config('app.name', 'Sistem Penggajian') }}</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

    <style>
        body {
            min-height: 100vh;
            margin: 0;
            padding: 0;
            background: linear-gradient(135deg, #4e54c8, #8f94fb);
            display: flex;
            align-items: center;
            justify-content: center;
            overflow: hidden;
            font-family: 'Inter', sans-serif;
        }

        /* background floating bubbles */
        .blob {
            position: absolute;
            border-radius: 50%;
            filter: blur(70px);
            opacity: 0.55;
            animation: float 12s ease-in-out infinite;
        }

        .blob.blue {
            width: 350px;
            height: 350px;
            background: #6a82fb;
            top: -80px;
            left: -80px;
        }

        .blob.pink {
            width: 400px;
            height: 400px;
            background: #fc5c7d;
            bottom: -120px;
            right: -120px;
            animation-delay: 3s;
        }

        @keyframes float {
            0% { transform: translateY(0px) rotate(0deg); }
            50% { transform: translateY(-25px) rotate(5deg); }
            100% { transform: translateY(0px) rotate(0deg); }
        }

        .glass-card {
            max-width: 700px;
            width: 100%;
            padding: 40px;
            border-radius: 20px;
            background: rgba(255,255,255,0.15);
            backdrop-filter: blur(20px);
            box-shadow: 0 25px 50px rgba(0,0,0,0.25);
            color: white;
            animation: fadeIn 0.8s ease;
            position: relative;
            z-index: 10;
        }

        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(20px); }
            to   { opacity: 1; transform: translateY(0); }
        }

        h1 {
            font-size: 2.2rem;
            font-weight: 700;
            margin-bottom: 15px;
        }

        p {
            font-size: 1.05rem;
            line-height: 1.6;
        }

        ul li {
            margin-bottom: 6px;
            font-size: 1rem;
        }

        .btn-modern {
            padding: 12px 30px;
            font-size: 16px;
            border-radius: 10px;
            transition: 0.3s;
            font-weight: 600;
        }

        .btn-modern:hover {
            transform: translateY(-3px);
            box-shadow: 0 10px 20px rgba(255,255,255,0.25);
        }

        .btn-primary {
            background: linear-gradient(135deg, #6a82fb, #fc5c7d);
            border: none;
        }

        .btn-outline-primary {
            border-color: white;
            color: white;
        }

        .btn-outline-primary:hover {
            background: rgba(255,255,255,0.15);
        }

        hr {
            border-color: rgba(255,255,255,0.3);
        }

        small {
            color: #f1f1f1;
        }
    </style>
</head>

<body>

    <!-- Background Decorations -->
    <div class="blob blue"></div>
    <div class="blob pink"></div>

    <div class="glass-card">
        <h1>Sistem SDM & Penggajian</h1>

        <p>
            Selamat datang di platform modern untuk manajemen SDM, penggajian,
            dan kehadiran. Dirancang dengan teknologi terkini untuk memastikan
            proses HR berjalan efisien dan akurat.
        </p>

        <h5 class="mt-4 fw-bold">Fitur Utama:</h5>
        <ul>
            <li>Manajemen Data Karyawan</li>
            <li>Perekaman & Pelacakan Absensi Otomatis</li>
            <li>Perhitungan Penggajian Akurat</li>
            <li>Manajemen Cuti & Perizinan</li>
            <li>Laporan HR & Analisis Real-time</li>
        </ul>

        <div class="d-flex gap-2 flex-wrap mt-4">
            @auth
                <a href="{{ route('dashboard') }}" class="btn btn-primary btn-modern">Go to Dashboard</a>
            @else
                <a href="/login" class="btn btn-primary btn-modern">Login</a>
                <a href="/register" class="btn btn-outline-primary btn-modern">Register</a>
            @endauth
        </div>

        <hr class="my-4">

        <small>
            Version 1.0.0 • Last Updated: December 6, 2025  
        </small>
    </div>

</body>
</html>

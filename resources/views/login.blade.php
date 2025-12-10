<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Sistem Penggajian & Absensi</title>

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

        /* Background Animated Shapes */
        .circle {
            position: absolute;
            border-radius: 50%;
            background: rgba(255,255,255,0.12);
            animation: float 8s ease-in-out infinite;
            backdrop-filter: blur(5px);
        }

        .circle.small { width: 100px; height: 100px; top: 10%; left: 15%; }
        .circle.medium { width: 160px; height: 160px; bottom: 10%; right: 10%; }
        .circle.large { width: 250px; height: 250px; top: 60%; left: -5%; }

        @keyframes float {
            0% { transform: translateY(0px); }
            50% { transform: translateY(-20px); }
            100% { transform: translateY(0px); }
        }

        .login-card {
            width: 100%;
            max-width: 420px;
            padding: 35px;
            border-radius: 18px;
            background: rgba(255, 255, 255, 0.15);
            box-shadow: 0 20px 40px rgba(0,0,0,0.2);
            backdrop-filter: blur(18px);
            color: white;
            position: relative;
            animation: fadeIn 0.7s ease-in-out;
        }

        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(15px); }
            to   { opacity: 1; transform: translateY(0); }
        }

        .login-card h2 {
            font-weight: 700;
            margin-bottom: 25px;
            text-align: center;
            letter-spacing: 0.5px;
        }

        .form-label {
            font-size: 14px;
            color: #e5e5e5;
        }

        .form-control {
            background: rgba(255,255,255,0.2);
            border: none;
            height: 48px;
            color: white;
        }

        .form-control:focus {
            background: rgba(255,255,255,0.28);
            color: white;
            border: none;
            box-shadow: 0 0 0 0.2rem rgba(255,255,255,0.3);
        }

        ::placeholder { color: rgba(255,255,255,0.7); }

        .btn-login {
            width: 100%;
            height: 48px;
            background: linear-gradient(135deg, #6a82fb, #fc5c7d);
            border: none;
            border-radius: 8px;
            font-weight: 600;
            font-size: 16px;
            color: white;
            margin-top: 15px;
            transition: 0.3s;
        }

        .btn-login:hover {
            opacity: 0.9;
            transform: scale(1.02);
        }

        .demo-box {
            background: rgba(255,255,255,0.12);
            padding: 15px;
            border-radius: 10px;
            margin-bottom: 15px;
            color: #f0f0f0;
            border-left: 4px solid rgba(255,255,255,0.4);
            font-size: 14px;
        }

        .bottom-links {
            text-align: center;
            margin-top: 20px;
        }

        .bottom-links a {
            color: #fff;
            text-decoration: underline;
        }
    </style>
</head>
<body>

    <!-- Background animated circles -->
    <div class="circle small"></div>
    <div class="circle medium"></div>
    <div class="circle large"></div>

    <div class="login-card">
        <h2>Masuk ke Sistem</h2>

        <div class="demo-box">
            <strong>Demo Mode</strong><br>
            Klik tombol login untuk langsung masuk.
        </div>

        <form action="{{ route('login.store') }}" method="POST">
            @csrf

            <label class="form-label">Email</label>
            <input type="email" class="form-control" name="email" placeholder="admin@example.com" required>

            <label class="form-label mt-3">Password</label>
            <input type="password" class="form-control" name="password" placeholder="password" required>

            <button type="submit" class="btn btn-login">Login</button>
        </form>

        <div class="bottom-links">
            <p class="mt-3 mb-1 small">Demo Credentials:<br>
                <strong>admin@example.com</strong><br>
                <strong>password</strong>
            </p>
            <a href="/">← Kembali ke Beranda</a>
        </div>
    </div>

</body>
</html>

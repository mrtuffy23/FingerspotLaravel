<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>404 - Not Found</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .error-container {
            text-align: center;
            background: white;
            padding: 60px 40px;
            border-radius: 10px;
            box-shadow: 0 20px 60px rgba(0,0,0,0.3);
            max-width: 500px;
            width: 100%;
        }
        .error-code {
            font-size: 100px;
            font-weight: bold;
            color: #667eea;
            margin: 0;
        }
        .error-title {
            font-size: 24px;
            color: #333;
            margin: 20px 0;
        }
        .btn-home {
            display: inline-block;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 12px 30px;
            border-radius: 5px;
            text-decoration: none;
            margin-top: 20px;
        }
        .btn-home:hover {
            color: white;
            text-decoration: none;
        }
    </style>
</head>
<body>
    <div class="error-container">
        <p class="error-code">404</p>
        <h1 class="error-title">❌ Page Not Found</h1>
        <p class="text-muted">The page you're looking for doesn't exist.</p>
        <a href="/" class="btn-home">🏠 Go to Home</a>
    </div>
</body>
</html>

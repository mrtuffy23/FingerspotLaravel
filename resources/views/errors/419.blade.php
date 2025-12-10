<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>419 - Page Expired</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, "Helvetica Neue", Arial, sans-serif;
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
            line-height: 1;
        }
        .error-title {
            font-size: 24px;
            color: #333;
            margin: 20px 0;
            font-weight: 600;
        }
        .error-message {
            color: #666;
            margin: 20px 0;
            font-size: 16px;
        }
        .btn-home {
            display: inline-block;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 12px 30px;
            border-radius: 5px;
            text-decoration: none;
            margin-top: 20px;
            font-weight: 500;
            transition: transform 0.2s;
        }
        .btn-home:hover {
            transform: translateY(-2px);
            color: white;
            text-decoration: none;
        }
    </style>
</head>
<body>
    <div class="error-container">
        <p class="error-code">419</p>
        <h1 class="error-title">⏱️ Page Expired</h1>
        <p class="error-message">
            Your session has expired or is invalid.<br>
            Please refresh the page or go back to the home page.
        </p>
        <a href="/" class="btn-home">🏠 Go to Home</a>
        <br>
        <a href="/login" style="color: #667eea; text-decoration: none; display: inline-block; margin-top: 15px;">🔐 Back to Login</a>
    </div>
</body>
</html>

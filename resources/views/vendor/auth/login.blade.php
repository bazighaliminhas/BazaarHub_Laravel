<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Vendor Login — BazaarHub</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    @vite(['resources/sass/app.scss', 'resources/js/app.js'])
    <style>
        * { font-family: 'Poppins', sans-serif; }
        body {
            background: linear-gradient(135deg, #1a1f2e 0%, #2C3E50 100%);
            min-height: 100vh;
            display: flex; align-items: center; justify-content: center;
        }
        .login-card {
            background: white;
            border-radius: 24px;
            padding: 44px 40px;
            width: 100%;
            max-width: 440px;
            box-shadow: 0 30px 80px rgba(0,0,0,0.3);
        }
        .brand-logo {
            font-size: 22px; font-weight: 700; color: #2C3E50;
            text-decoration: none; display: block; margin-bottom: 8px;
        }
        .brand-logo span { color: #FF6B35; }
        .form-control {
            border: 2px solid #f0f0f0; border-radius: 12px;
            padding: 12px 16px; font-size: 14px; transition: all 0.2s;
        }
        .form-control:focus {
            border-color: #FF6B35;
            box-shadow: 0 0 0 3px rgba(255,107,53,0.1);
        }
        .input-group-text {
            background: #f8f9fa; border: 2px solid #f0f0f0;
            border-right: none; border-radius: 12px 0 0 12px;
        }
        .input-group .form-control { border-left: none; border-radius: 0 12px 12px 0; }
        .btn-vendor-login {
            background: linear-gradient(135deg, #FF6B35, #FF8C42);
            color: white; border: none; border-radius: 12px;
            padding: 13px; font-size: 15px; font-weight: 600;
            width: 100%; transition: all 0.3s;
            box-shadow: 0 6px 20px rgba(255,107,53,0.35);
        }
        .btn-vendor-login:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 30px rgba(255,107,53,0.5);
            color: white;
        }
        .demo-box {
            background: #f8f9fa; border-radius: 12px;
            padding: 14px 16px; margin-top: 20px;
            border-left: 3px solid #FF6B35;
        }
    </style>
</head>
<body>
<div class="login-card">
    <a href="{{ route('home') }}" class="brand-logo">🛒 Bazaar<span>Hub</span></a>
    <p style="color:#888; font-size:13px; margin-bottom:28px;">Vendor Management Portal</p>

    <h4 class="fw-bold mb-1" style="color:#2C3E50;">Welcome Back! 👋</h4>
    <p class="text-muted mb-4" style="font-size:14px;">Sign in to manage your store</p>

    @if($errors->any())
    <div class="alert rounded-3 border-0 mb-3" style="background:#fdecea; color:#e74c3c; font-size:14px;">
        <i class="bi bi-exclamation-circle me-2"></i>{{ $errors->first() }}
    </div>
    @endif

    <form action="{{ route('vendor.login.post') }}" method="POST">
        @csrf
        <div class="mb-3">
            <label class="form-label fw-semibold" style="font-size:14px;">Email Address</label>
            <div class="input-group">
                <span class="input-group-text"><i class="bi bi-envelope" style="color:#FF6B35;"></i></span>
                <input type="email" name="email" value="{{ old('email') }}"
                       class="form-control" placeholder="vendor@email.com" required>
            </div>
        </div>

        <div class="mb-4">
            <label class="form-label fw-semibold" style="font-size:14px;">Password</label>
            <div class="input-group">
                <span class="input-group-text"><i class="bi bi-lock" style="color:#FF6B35;"></i></span>
                <input type="password" name="password"
                       class="form-control" placeholder="Your password" required>
            </div>
        </div>

        <div class="form-check mb-4">
            <input class="form-check-input" type="checkbox" name="remember" id="remember">
            <label class="form-check-label text-muted" for="remember" style="font-size:13px;">
                Keep me signed in
            </label>
        </div>

        <button type="submit" class="btn-vendor-login">
            <i class="bi bi-shop me-2"></i>Login to Dashboard
        </button>
    </form>

    {{-- Demo Credentials --}}
    <div class="demo-box">
        <p class="fw-semibold mb-2" style="font-size:13px; color:#2C3E50;">
            🧪 Demo Vendor Credentials:
        </p>
        <div style="font-size:12px; color:#666; line-height:1.8;">
            <div><strong>Email:</strong> ali@vendor.com</div>
            <div><strong>Password:</strong> vendor123</div>
        </div>
    </div>

    <p class="text-center mt-4 mb-0" style="font-size:13px; color:#888;">
        <a href="{{ route('home') }}" style="color:#FF6B35; text-decoration:none;">
            ← Back to Store
        </a>
    </p>
</div>
</body>
</html>

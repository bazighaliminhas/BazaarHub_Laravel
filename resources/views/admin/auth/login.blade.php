<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Login — BazaarHub</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    @vite(['resources/sass/app.scss', 'resources/js/app.js'])
    <style>
        * { font-family: 'Poppins', sans-serif; }
        body {
            background: linear-gradient(135deg, #0f1623 0%, #1a2332 100%);
            min-height: 100vh;
            display: flex; align-items: center; justify-content: center;
        }
        .login-wrap {
            background: white; border-radius: 24px;
            padding: 44px 40px; width: 100%; max-width: 440px;
            box-shadow: 0 40px 100px rgba(0,0,0,0.4);
        }
        .admin-tag {
            background: linear-gradient(135deg, #FF6B35, #FF8C42);
            color: white; font-size: 10px; font-weight: 700;
            padding: 4px 12px; border-radius: 20px;
            letter-spacing: 1px; text-transform: uppercase;
            display: inline-block; margin-bottom: 20px;
        }
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
        .input-group .form-control {
            border-left: none; border-radius: 0 12px 12px 0;
        }
        .btn-admin-login {
            background: linear-gradient(135deg, #0f1623, #1a2332);
            color: white; border: none; border-radius: 12px;
            padding: 13px; font-size: 15px; font-weight: 600;
            width: 100%; transition: all 0.3s;
        }
        .btn-admin-login:hover {
            background: linear-gradient(135deg, #FF6B35, #FF8C42);
            color: white; transform: translateY(-2px);
            box-shadow: 0 10px 30px rgba(255,107,53,0.4);
        }
        .demo-box {
            background: #f8f9fa; border-radius: 12px;
            padding: 14px 16px; margin-top: 20px;
            border-left: 3px solid #0f1623;
        }
    </style>
</head>
<body>
<div class="login-wrap">
    <a href="{{ route('home') }}"
       style="font-size:20px;font-weight:700;color:#1a1f2e;text-decoration:none;">
        🛒 Bazaar<span style="color:#FF6B35;">Hub</span>
    </a>
    <div class="admin-tag mt-2">Super Admin Portal</div>

    <h4 class="fw-bold mb-1" style="color:#1a1f2e;">Admin Access 🔐</h4>
    <p class="text-muted mb-4" style="font-size:14px;">
        Restricted area — authorized personnel only
    </p>

    @if($errors->any())
    <div class="alert border-0 rounded-3 mb-3"
         style="background:#fdecea; color:#e74c3c; font-size:14px;">
        <i class="bi bi-shield-exclamation me-2"></i>{{ $errors->first() }}
    </div>
    @endif

    <form action="{{ route('admin.login.post') }}" method="POST">
        @csrf
        <div class="mb-3">
            <label class="form-label fw-semibold" style="font-size:14px;">Admin Email</label>
            <div class="input-group">
                <span class="input-group-text">
                    <i class="bi bi-shield-lock" style="color:#FF6B35;"></i>
                </span>
                <input type="email" name="email" value="{{ old('email') }}"
                       class="form-control" placeholder="admin@bazaarhub.pk" required>
            </div>
        </div>
        <div class="mb-4">
            <label class="form-label fw-semibold" style="font-size:14px;">Password</label>
            <div class="input-group">
                <span class="input-group-text">
                    <i class="bi bi-key" style="color:#FF6B35;"></i>
                </span>
                <input type="password" name="password"
                       class="form-control" placeholder="Admin password" required>
            </div>
        </div>

        <button type="submit" class="btn-admin-login">
            <i class="bi bi-shield-check me-2"></i>Access Admin Panel
        </button>
    </form>

    <div class="demo-box">
        <p class="fw-semibold mb-2" style="font-size:13px; color:#1a1f2e;">
            🧪 Demo Admin Credentials:
        </p>
        <div style="font-size:12px; color:#666; line-height:1.8;">
            <div><strong>Email:</strong> admin@bazaarhub.pk</div>
            <div><strong>Password:</strong> admin123</div>
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

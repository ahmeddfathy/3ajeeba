<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>تسجيل الدخول — عجيبة</title>
    <link href="https://fonts.googleapis.com/css2?family=Cairo:wght@400;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }

        html, body {
            scrollbar-width: none;
            -ms-overflow-style: none;
        }

        html::-webkit-scrollbar,
        body::-webkit-scrollbar {
            display: none;
        }

        body {
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            background: #F8F1EA;
            font-family: 'Cairo', 'Segoe UI', Tahoma, sans-serif;
            padding: 1rem;
        }

        .login-card {
            background: #fff;
            border-radius: 24px;
            padding: 2.5rem 2rem;
            width: 100%;
            max-width: 420px;
            box-shadow: 0 25px 60px rgba(0,0,0,0.25);
        }

        .login-logo {
            text-align: center;
            margin-bottom: 1.75rem;
        }

        .login-logo .logo-icon {
            width: 72px;
            height: 72px;
            border-radius: 18px;
            overflow: hidden;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            margin-bottom: 0.75rem;
            box-shadow: 0 8px 20px rgba(0,0,0,0.1);
            background: #fff;
        }

        .login-logo .logo-icon img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            display: block;
        }

        .login-logo h1 {
            font-size: 1.4rem;
            font-weight: 800;
            color: #1e293b;
        }

        .login-logo p {
            font-size: 0.85rem;
            color: #64748b;
            margin-top: 2px;
        }

        .alert-error {
            background: #fef2f2;
            border: 1px solid #fecaca;
            color: #dc2626;
            border-radius: 10px;
            padding: 0.75rem 1rem;
            font-size: 0.85rem;
            margin-bottom: 1.25rem;
        }

        .alert-success {
            background: #f0fdf4;
            border: 1px solid #bbf7d0;
            color: #16a34a;
            border-radius: 10px;
            padding: 0.75rem 1rem;
            font-size: 0.85rem;
            margin-bottom: 1.25rem;
        }

        .form-group {
            margin-bottom: 1.1rem;
        }

        .form-group label {
            display: block;
            font-size: 0.875rem;
            font-weight: 600;
            color: #334155;
            margin-bottom: 0.4rem;
        }

        .input-wrap {
            position: relative;
        }

        .input-wrap i {
            position: absolute;
            right: 14px;
            top: 50%;
            transform: translateY(-50%);
            color: #94a3b8;
            font-size: 1rem;
        }

        .form-group input {
            width: 100%;
            padding: 0.7rem 2.5rem 0.7rem 1rem;
            border: 1.5px solid #e2e8f0;
            border-radius: 12px;
            font-size: 0.95rem;
            color: #1e293b;
            background: #f8fafc;
            outline: none;
            transition: border-color 0.2s, box-shadow 0.2s;
            direction: ltr;
            text-align: right;
        }

        .form-group input:focus {
            border-color: #94a3b8;
            box-shadow: 0 0 0 3px rgba(148,163,184,0.12);
            background: #fff;
        }

        .remember-row {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            margin-bottom: 1.5rem;
        }

        .remember-row input[type="checkbox"] {
            width: 16px;
            height: 16px;
            accent-color: #64748b;
            cursor: pointer;
        }

        .remember-row label {
            font-size: 0.875rem;
            color: #6b7280;
            cursor: pointer;
        }

        .btn-login {
            width: 100%;
            padding: 0.85rem;
            background: linear-gradient(135deg, #B88468 0%, #A36F50 50%, #76503C 100%);
            color: #fff;
            border: none;
            border-radius: 12px;
            font-size: 1rem;
            font-weight: 700;
            cursor: pointer;
            transition: transform 0.2s, box-shadow 0.2s;
            box-shadow: 0 4px 15px rgba(163,111,80,0.35);
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 0.5rem;
        }

        .btn-login:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(163,111,80,0.45);
        }

        .forgot-link {
            display: block;
            text-align: center;
            margin-top: 1rem;
            font-size: 0.85rem;
            color: #64748b;
            text-decoration: none;
        }

        .forgot-link:hover {
            color: #1e293b;
            text-decoration: underline;
        }

        .divider {
            height: 1px;
            background: #e2e8f0;
            margin: 1.5rem 0;
        }

        .back-link {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 0.4rem;
            font-size: 0.85rem;
            color: #64748b;
            text-decoration: none;
        }

        .back-link:hover { color: #1e293b; }
    </style>
</head>
<body>

<div class="login-card">

    <div class="login-logo">
        <div class="logo-icon">
            <img src="{{ asset('assets/brand/logo.jpeg') }}" alt="عجيبة">
        </div>
        <h1>عجيبة</h1>
        <p>لوحة التحكم</p>
    </div>

    {{-- Validation Errors --}}
    @if ($errors->any())
        <div class="alert-error">
            <i class="bi bi-exclamation-circle-fill"></i>
            {{ $errors->first() }}
        </div>
    @endif

    {{-- Session Status --}}
    @if (session('status'))
        <div class="alert-success">
            <i class="bi bi-check-circle-fill"></i>
            {{ session('status') }}
        </div>
    @endif

    <form method="POST" action="{{ route('login') }}">
        @csrf

        <div class="form-group">
            <label for="email">البريد الإلكتروني</label>
            <div class="input-wrap">
                <i class="bi bi-envelope-fill"></i>
                <input id="email" type="email" name="email" value="{{ old('email') }}" required autofocus autocomplete="username" placeholder="example@email.com">
            </div>
        </div>

        <div class="form-group">
            <label for="password">كلمة المرور</label>
            <div class="input-wrap">
                <i class="bi bi-lock-fill"></i>
                <input id="password" type="password" name="password" required autocomplete="current-password" placeholder="••••••••">
            </div>
        </div>

        <div class="remember-row">
            <input type="checkbox" id="remember_me" name="remember">
            <label for="remember_me">تذكرني</label>
        </div>

        <button type="submit" class="btn-login">
            <i class="bi bi-box-arrow-in-right"></i>
            تسجيل الدخول
        </button>

    </form>

    <div class="divider"></div>

    <a href="{{ url('/') }}" class="back-link">
        <i class="bi bi-arrow-right"></i>
        العودة للموقع
    </a>

</div>

</body>
</html>

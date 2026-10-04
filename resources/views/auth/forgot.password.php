<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Lupa Password · RadneXt</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body {
            font-family: 'Inter', system-ui, sans-serif;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
            background: #0f172a;
            position: relative;
        }
        .bg-gradient {
            position: fixed; inset: 0; z-index: 0;
            background: linear-gradient(135deg, #0f172a 0%, #1e1b4b 50%, #0f172a 100%);
        }
        .card {
            position: relative;
            z-index: 2;
            background: rgba(15, 23, 42, 0.75);
            backdrop-filter: blur(24px);
            border: 1px solid rgba(255, 255, 255, 0.1);
            border-radius: 24px;
            padding: 40px 36px;
            max-width: 440px;
            width: 100%;
            box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.6);
        }
        .icon-head {
            width: 64px; height: 64px;
            margin: 0 auto 20px;
            background: rgba(59, 130, 246, 0.15);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #60a5fa;
            font-size: 24px;
        }
        h1 {
            color: #fff;
            font-size: 1.4rem;
            text-align: center;
            margin-bottom: 8px;
        }
        .subtitle {
            color: #94a3b8;
            text-align: center;
            font-size: 0.85rem;
            margin-bottom: 28px;
            line-height: 1.5;
        }
        .form-label {
            display: block;
            color: #cbd5e1;
            font-size: 0.78rem;
            font-weight: 600;
            margin-bottom: 8px;
            text-transform: uppercase;
            letter-spacing: 0.3px;
        }
        .form-input {
            width: 100%;
            background: rgba(255, 255, 255, 0.05);
            border: 1.5px solid rgba(255, 255, 255, 0.1);
            border-radius: 12px;
            padding: 14px 16px;
            color: #fff;
            font-size: 0.9rem;
            font-family: inherit;
            outline: none;
            transition: all 0.3s;
        }
        .form-input:focus {
            border-color: #3b82f6;
            background: rgba(59, 130, 246, 0.08);
            box-shadow: 0 0 0 4px rgba(59, 130, 246, 0.15);
        }
        .form-input::placeholder { color: #64748b; }
        .btn {
            width: 100%;
            padding: 14px;
            border: none;
            border-radius: 12px;
            background: linear-gradient(135deg, #3b82f6 0%, #2563eb 100%);
            color: #fff;
            font-size: 0.92rem;
            font-weight: 700;
            cursor: pointer;
            transition: all 0.3s;
            margin-top: 8px;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
        }
        .btn:hover { transform: translateY(-2px); box-shadow: 0 10px 24px -6px rgba(59,130,246,.6); }
        .back-link {
            display: block;
            text-align: center;
            margin-top: 20px;
            color: #60a5fa;
            font-size: 0.82rem;
            text-decoration: none;
            font-weight: 500;
        }
        .back-link:hover { color: #93c5fd; }
        .alert {
            padding: 12px 16px;
            border-radius: 10px;
            font-size: 0.82rem;
            margin-bottom: 20px;
            display: flex;
            align-items: center;
            gap: 10px;
        }
        .alert-success {
            background: rgba(34, 197, 94, 0.12);
            border: 1px solid rgba(34, 197, 94, 0.3);
            color: #86efac;
        }
        .alert-error {
            background: rgba(239, 68, 68, 0.12);
            border: 1px solid rgba(239, 68, 68, 0.3);
            color: #fca5a5;
        }
    </style>
</head>
<body>
    <div class="bg-gradient"></div>

    <div class="card">
        <div class="icon-head">
            <i class="fas fa-key"></i>
        </div>

        <h1>Lupa Password?</h1>
        <p class="subtitle">
            Masukkan email terdaftar. Admin IT akan menghubungi kamu untuk proses reset password.
        </p>

        @if(session('status'))
            <div class="alert alert-success">
                <i class="fas fa-check-circle"></i>
                <span>{{ session('status') }}</span>
            </div>
        @endif

        @if($errors->any())
            <div class="alert alert-error">
                <i class="fas fa-exclamation-circle"></i>
                <span>{{ $errors->first() }}</span>
            </div>
        @endif

        <form method="POST" action="{{ route('password.email') }}">
            @csrf
            <div style="margin-bottom: 16px;">
                <label class="form-label" for="forgotEmail">Email Terdaftar</label>
                <input type="email" name="email" id="forgotEmail" class="form-input"
                       placeholder="nama@radnet.com" value="{{ old('email') }}" required autofocus>
            </div>
            <button type="submit" class="btn">
                <i class="fas fa-paper-plane"></i>
                Kirim Permintaan
            </button>
        </form>

        <a href="{{ route('login') }}" class="back-link">
            <i class="fas fa-arrow-left"></i> Kembali ke Login
        </a>
    </div>
</body>
</html>
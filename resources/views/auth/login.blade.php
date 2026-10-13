<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Login · RadneXt</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body {
            font-family: 'Inter', system-ui, sans-serif;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
            overflow: hidden;
            position: relative;
            background: #0f172a;
        }

        /* ============================================================
           ANIMATED BACKGROUND
        ============================================================ */
        .bg-gradient {
            position: fixed;
            inset: 0;
            z-index: 0;
            background: linear-gradient(135deg, #0f172a 0%, #1e1b4b 50%, #0f172a 100%);
            background-size: 400% 400%;
            animation: gradientSwipe 15s ease infinite;
        }

        @keyframes gradientSwipe {
            0% { background-position: 0% 50%; }
            50% { background-position: 100% 50%; }
            100% { background-position: 0% 50%; }
        }

        /* Glowing orbs */
        .bg-gradient::before,
        .bg-gradient::after {
            content: '';
            position: absolute;
            border-radius: 50%;
            filter: blur(80px);
            opacity: 0.4;
        }

        .bg-gradient::before {
            width: 500px; height: 500px;
            background: radial-gradient(circle, #3b82f6 0%, transparent 70%);
            top: -15%; left: -10%;
            animation: floatOrb 8s ease-in-out infinite;
        }

        .bg-gradient::after {
            width: 400px; height: 400px;
            background: radial-gradient(circle, #8b5cf6 0%, transparent 70%);
            bottom: -15%; right: -10%;
            animation: floatOrb 10s ease-in-out infinite reverse;
        }

        @keyframes floatOrb {
            0%, 100% { transform: translate(0, 0); }
            50% { transform: translate(40px, -40px); }
        }

        /* Floating particles */
        .particles {
            position: fixed;
            inset: 0;
            z-index: 1;
            overflow: hidden;
            pointer-events: none;
        }

        .particle {
            position: absolute;
            background: rgba(96, 165, 250, 0.5);
            border-radius: 50%;
            animation: floatParticle linear infinite;
        }

        @keyframes floatParticle {
            0% { transform: translateY(100vh) translateX(0); opacity: 0; }
            10% { opacity: 0.8; }
            90% { opacity: 0.8; }
            100% { transform: translateY(-20vh) translateX(50px); opacity: 0; }
        }

        /* ============================================================
           LOGIN CARD
        ============================================================ */
        .login-wrap {
            position: relative;
            z-index: 2;
            width: 100%;
            max-width: 440px;
            animation: slideUp 0.6s cubic-bezier(0.4, 0, 0.2, 1);
        }

        @keyframes slideUp {
            from { opacity: 0; transform: translateY(30px); }
            to { opacity: 1; transform: translateY(0); }
        }

        .login-card {
            background: rgba(15, 23, 42, 0.75);
            backdrop-filter: blur(24px);
            -webkit-backdrop-filter: blur(24px);
            border: 1px solid rgba(255, 255, 255, 0.1);
            border-radius: 24px;
            padding: 40px 36px;
            box-shadow:
                0 25px 50px -12px rgba(0, 0, 0, 0.6),
                inset 0 1px 0 rgba(255, 255, 255, 0.05);
            position: relative;
            overflow: hidden;
        }

        .login-card::before {
            content: '';
            position: absolute;
            top: 0; left: 0; right: 0;
            height: 2px;
            background: linear-gradient(90deg, transparent, #3b82f6, #8b5cf6, transparent);
            animation: shimmer 3s linear infinite;
        }

        @keyframes shimmer {
            0% { transform: translateX(-100%); }
            100% { transform: translateX(100%); }
        }

        /* Logo */
        .login-logo {
            display: flex;
            justify-content: center;
            margin-bottom: 24px;
        }

        .login-logo img {
            max-width: 220px;
            height: auto;
            filter: drop-shadow(0 12px 28px rgba(59, 130, 246, 0.55));
            animation: float 4s ease-in-out infinite;
        }

        @keyframes float {
            0%, 100% { transform: translateY(0); }
            50% { transform: translateY(-6px); }
        }

        .login-subtitle {
            text-align: center;
            color: #94a3b8;
            font-size: 0.85rem;
            margin-bottom: 30px;
        }

        /* Big watermark logo behind the card */
        .bg-logo {
            position: fixed;
            inset: 0;
            z-index: 1;
            display: flex;
            align-items: center;
            justify-content: center;
            pointer-events: none;
        }

        .bg-logo img {
            width: 100%;
            max-width: 800px;
            height: auto;
            opacity: 0.12;
            animation: bgLogoFloat 6s ease-in-out infinite;
        }

        @keyframes bgLogoFloat {
            0%, 100% { transform: translateY(0) scale(1); }
            50% { transform: translateY(-10px) scale(1.05); }
        }

        /* Form */
        .form-group {
            margin-bottom: 20px;
        }

        .form-label {
            display: block;
            color: #cbd5e1;
            font-size: 0.78rem;
            font-weight: 600;
            margin-bottom: 8px;
            letter-spacing: 0.3px;
            text-transform: uppercase;
        }

        .input-wrap {
            position: relative;
        }

.input-wrap > i {
    position: absolute;
    left: 16px;
    top: 50%;
    transform: translateY(-50%);
    color: #64748b;
    font-size: 15px;
    transition: color 0.3s ease;
}

.toggle-password i {
    position: static;
    transform: none;
    left: auto;
    top: auto;
    color: inherit;
    font-size: 14px;
}

        .form-input {
            width: 100%;
            background: rgba(255, 255, 255, 0.05);
            border: 1.5px solid rgba(255, 255, 255, 0.1);
            border-radius: 12px;
            padding: 14px 16px 14px 44px;
            color: #fff;
            font-size: 0.9rem;
            font-family: inherit;
            transition: all 0.3s ease;
            outline: none;
        }

        .form-input::placeholder {
            color: #64748b;
        }

        .form-input:focus {
            border-color: #3b82f6;
            background: rgba(59, 130, 246, 0.08);
            box-shadow: 0 0 0 4px rgba(59, 130, 246, 0.15);
        }

        .form-input:focus + i,
        .input-wrap:focus-within i {
            color: #3b82f6;
        }

        .toggle-password {
            position: absolute;
            right: 16px;
            top: 50%;
            transform: translateY(-50%);
            background: none;
            border: none;
            color: #64748b;
            cursor: pointer;
            font-size: 14px;
            padding: 4px;
            transition: color 0.3s ease;
        }

        .toggle-password:hover {
            color: #3b82f6;
        }

        /* Error */
        .error-box {
            background: rgba(239, 68, 68, 0.12);
            border: 1px solid rgba(239, 68, 68, 0.3);
            color: #fca5a5;
            padding: 12px 16px;
            border-radius: 10px;
            font-size: 0.82rem;
            margin-bottom: 20px;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        /* Remember & forgot */
        .form-extra {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 24px;
        }

        .checkbox-wrap {
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .checkbox-wrap input[type="checkbox"] {
            width: 16px;
            height: 16px;
            accent-color: #3b82f6;
            cursor: pointer;
        }

        .checkbox-wrap label {
            color: #cbd5e1;
            font-size: 0.82rem;
            cursor: pointer;
        }

        .forgot-link {
            color: #60a5fa;
            font-size: 0.82rem;
            text-decoration: none;
            font-weight: 500;
            transition: color 0.2s;
        }

        .forgot-link:hover {
            color: #93c5fd;
        }

        /* Button */
        .btn-login {
            width: 100%;
            padding: 15px;
            border: none;
            border-radius: 12px;
            background: linear-gradient(135deg, #3b82f6 0%, #2563eb 100%);
            color: #fff;
            font-size: 0.95rem;
            font-weight: 700;
            font-family: inherit;
            cursor: pointer;
            transition: all 0.3s ease;
            position: relative;
            overflow: hidden;
            letter-spacing: 0.3px;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 10px;
            box-shadow: 0 8px 20px -6px rgba(59, 130, 246, 0.5);
        }

        .btn-login::before {
            content: '';
            position: absolute;
            top: 0; left: -100%;
            width: 100%; height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255,255,255,0.25), transparent);
            transition: left 0.6s ease;
        }

        .btn-login:hover::before {
            left: 100%;
        }

        .btn-login:hover {
            transform: translateY(-2px);
            box-shadow: 0 12px 28px -6px rgba(59, 130, 246, 0.7);
        }

        .btn-login:active {
            transform: translateY(0);
        }

        .btn-login:disabled {
            opacity: 0.7;
            cursor: not-allowed;
            transform: none;
        }

        /* Footer */
        .login-footer {
            text-align: center;
            margin-top: 28px;
            padding-top: 20px;
            border-top: 1px solid rgba(255, 255, 255, 0.08);
            color: #64748b;
            font-size: 0.75rem;
        }

        /* Responsive */
        @media (max-width: 480px) {
            .login-card { padding: 32px 24px; }
        }
    </style>
</head>
<body>

    {{-- Animated Background --}}
    <div class="bg-gradient"></div>
    <div class="particles" id="particles"></div>

    {{-- Big Watermark Logo (Background) --}}
    <div class="bg-logo">
        <img src="{{ asset('admin-assets/images/radnext-logo.png') }}" alt="RadneXt">
    </div>

    {{-- Login Card --}}
    <div class="login-wrap">
        <div class="login-card">

            <div class="login-logo">
                <img src="{{ asset('admin-assets/images/radnext-logo.png') }}" alt="RadneXt">
            </div>

            <p class="login-subtitle">Network Monitoring System</p>

            @if ($errors->any())
                <div class="error-box">
                    <i class="fas fa-exclamation-circle"></i>
                    <span>{{ $errors->first() }}</span>
                </div>
            @endif

            <form action="{{ route('login') }}" method="POST" id="loginForm">
                @csrf

                <div class="form-group">
                    <label class="form-label">Email</label>
                    <div class="input-wrap">
                        <i class="fas fa-envelope"></i>
                        <input
                            type="email"
                            name="email"
                            class="form-input"
                            placeholder="nama@radnet.com"
                            value="{{ old('email') }}"
                            required
                            autofocus>
                    </div>
                </div>

                <div class="form-group">
                    <label class="form-label">Password</label>
                    <div class="input-wrap">
                        <i class="fas fa-lock"></i>
                        <input
                            type="password"
                            name="password"
                            id="passwordInput"
                            class="form-input"
                            placeholder="Masukkan password"
                            required>
                        <button type="button" class="toggle-password" onclick="togglePassword()">
                            <i class="fas fa-eye" id="eyeIcon"></i>
                        </button>
                    </div>
                </div>

                <div class="form-extra">
                    <div class="checkbox-wrap">
                        <input type="checkbox" name="remember" id="remember">
                        <label for="remember">Ingat saya</label>
                    </div>
                </div>

                <button type="submit" class="btn-login" id="btnLogin">
                    <i class="fas fa-sign-in-alt"></i>
                    <span>Masuk</span>
                </button>
            </form>

            <div class="login-footer">
                &copy; {{ date('Y') }} PT Radnet Digital Indonesia
            </div>

        </div>
    </div>

    <script>
        // ============================================================
        // PARTICLES
        // ============================================================
        (function createParticles() {
            const container = document.getElementById('particles');
            const count = 30;
            for (let i = 0; i < count; i++) {
                const p = document.createElement('div');
                p.className = 'particle';
                const size = Math.random() * 4 + 2;
                p.style.width = size + 'px';
                p.style.height = size + 'px';
                p.style.left = Math.random() * 100 + '%';
                p.style.animationDuration = (Math.random() * 15 + 10) + 's';
                p.style.animationDelay = (Math.random() * 10) + 's';
                container.appendChild(p);
            }
        })();

        // ============================================================
        // TOGGLE PASSWORD
        // ============================================================
        function togglePassword() {
            const input = document.getElementById('passwordInput');
            const icon = document.getElementById('eyeIcon');
            if (input.type === 'password') {
                input.type = 'text';
                icon.classList.remove('fa-eye');
                icon.classList.add('fa-eye-slash');
            } else {
                input.type = 'password';
                icon.classList.add('fa-eye');
                icon.classList.remove('fa-eye-slash');
            }
        }

        // ============================================================
        // LOADING STATE
        // ============================================================
        document.getElementById('loginForm').addEventListener('submit', function () {
            const btn = document.getElementById('btnLogin');
            btn.disabled = true;
            btn.innerHTML = '<i class="fas fa-spinner fa-spin"><\/i><span>Memproses...<\/span>';
        });
    </script>

</body>
</html>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Login</title>
    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
    <link href="https://fonts.googleapis.com/css2?family=Inter:opsz,wght@14..32,300;14..32,400;14..32,500;14..32,600;14..32,700&display=swap" rel="stylesheet" />
    <style>
        /* ====== Sama seperti sebelumnya, hanya disisipkan di sini ====== */
        *,
        *::before,
        *::after {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Inter', sans-serif;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            background: #f0f4f9;
            padding: 1.5rem;
        }

        .auth-card {
            background: #ffffff;
            border-radius: 2rem;
            box-shadow: 0 20px 60px rgba(0, 20, 40, 0.10), 0 8px 24px rgba(0, 20, 40, 0.06);
            max-width: 460px;
            width: 100%;
            padding: 2.5rem 2.25rem 2.25rem;
            transition: box-shadow 0.3s;
        }

        .auth-card:hover {
            box-shadow: 0 28px 72px rgba(0, 20, 40, 0.14), 0 8px 28px rgba(0, 20, 40, 0.07);
        }

        .tabs {
            display: flex;
            gap: 0.5rem;
            margin-bottom: 2rem;
            background: #f1f5f9;
            border-radius: 1.25rem;
            padding: 0.375rem;
            position: relative;
        }

        .tab-btn {
            flex: 1;
            border: none;
            background: transparent;
            padding: 0.7rem 0.25rem;
            border-radius: 1rem;
            font-size: 0.95rem;
            font-weight: 600;
            color: #64748b;
            cursor: pointer;
            transition: color 0.25s, background 0.25s;
            position: relative;
            z-index: 1;
            letter-spacing: 0.01em;
        }

        .tab-btn.active {
            color: #0b1e33;
            background: #ffffff;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.04);
        }

        .tab-btn:not(.active):hover {
            color: #1e293b;
        }

        .auth-title {
            font-size: 1.75rem;
            font-weight: 700;
            color: #0b1e33;
            letter-spacing: -0.02em;
            margin-bottom: 0.2rem;
        }

        .auth-sub {
            font-size: 0.95rem;
            color: #64748b;
            margin-bottom: 1.75rem;
            line-height: 1.5;
            min-height: 1.6rem;
        }

        .form-group {
            margin-bottom: 1.25rem;
        }

        .form-group label {
            display: block;
            font-size: 0.8rem;
            font-weight: 600;
            color: #1e293b;
            letter-spacing: 0.02em;
            text-transform: uppercase;
            margin-bottom: 0.4rem;
        }

        .input-wrap {
            position: relative;
            display: flex;
            align-items: center;
            background: #f8fafc;
            border-radius: 0.9rem;
            border: 1.5px solid #e9edf2;
            transition: border-color 0.2s, box-shadow 0.2s, background 0.2s;
        }

        .input-wrap:focus-within {
            border-color: #1e4b6b;
            background: #ffffff;
            box-shadow: 0 0 0 4px rgba(30, 75, 107, 0.08);
        }

        .input-wrap.error {
            border-color: #d0314e;
            background: #fff8f9;
        }

        .input-wrap.error:focus-within {
            box-shadow: 0 0 0 4px rgba(208, 49, 78, 0.10);
        }

        .input-wrap input {
            width: 100%;
            border: none;
            background: transparent;
            padding: 0.85rem 1rem;
            font-size: 0.95rem;
            font-weight: 500;
            color: #0b1e33;
            outline: none;
            font-family: 'Inter', sans-serif;
        }

        .input-wrap input::placeholder {
            color: #94a3b8;
            font-weight: 400;
        }

        .input-wrap .toggle-pw {
            background: none;
            border: none;
            padding: 0 1rem 0 0.25rem;
            font-size: 0.85rem;
            font-weight: 600;
            color: #64748b;
            cursor: pointer;
            transition: color 0.2s;
            letter-spacing: 0.01em;
            flex-shrink: 0;
        }

        .input-wrap .toggle-pw:hover {
            color: #1e293b;
        }

        .error-message {
            font-size: 0.78rem;
            font-weight: 500;
            color: #d0314e;
            margin-top: 0.35rem;
            min-height: 1.2rem;
            transition: opacity 0.2s;
        }

        .row-extra {
            display: flex;
            align-items: center;
            justify-content: space-between;
            flex-wrap: wrap;
            gap: 0.5rem 0.75rem;
            margin: 0.25rem 0 1.5rem;
        }

        .checkbox-wrap {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            font-size: 0.88rem;
            font-weight: 500;
            color: #1e293b;
            cursor: pointer;
        }

        .checkbox-wrap input[type="checkbox"] {
            width: 1.1rem;
            height: 1.1rem;
            accent-color: #1e4b6b;
            border-radius: 0.25rem;
            cursor: pointer;
            flex-shrink: 0;
        }

        .link-forgot {
            font-size: 0.85rem;
            font-weight: 600;
            color: #1e4b6b;
            text-decoration: none;
            transition: color 0.2s;
            border-bottom: 1.5px solid transparent;
        }

        .link-forgot:hover {
            color: #0b2c42;
            border-bottom-color: #1e4b6b;
        }

        .btn-submit {
            width: 100%;
            padding: 0.9rem;
            border: none;
            border-radius: 0.9rem;
            background: #0b1e33;
            color: #ffffff;
            font-size: 1rem;
            font-weight: 600;
            font-family: 'Inter', sans-serif;
            cursor: pointer;
            transition: background 0.25s, transform 0.15s, box-shadow 0.25s;
            letter-spacing: 0.01em;
            margin-top: 0.25rem;
        }

        .btn-submit:hover {
            background: #142f47;
            box-shadow: 0 8px 24px rgba(11, 30, 51, 0.20);
            transform: translateY(-1px);
        }

        .btn-submit:active {
            transform: translateY(0);
            box-shadow: 0 4px 12px rgba(11, 30, 51, 0.12);
        }

        .btn-submit:disabled {
            opacity: 0.6;
            cursor: not-allowed;
            transform: none;
            box-shadow: none;
        }

        .divider {
            display: flex;
            align-items: center;
            gap: 1rem;
            margin: 1.75rem 0 1.5rem;
        }

        .divider::before,
        .divider::after {
            content: '';
            flex: 1;
            height: 1.5px;
            background: #e2e8f0;
        }

        .divider span {
            font-size: 0.75rem;
            font-weight: 600;
            color: #94a3b8;
            letter-spacing: 0.04em;
            text-transform: uppercase;
        }

        .social-row {
            display: flex;
            gap: 0.75rem;
            margin-bottom: 1.75rem;
        }

        .btn-social {
            flex: 1;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 0.6rem;
            padding: 0.7rem 0.5rem;
            border: 1.5px solid #e9edf2;
            border-radius: 0.9rem;
            background: #ffffff;
            font-size: 0.88rem;
            font-weight: 600;
            color: #1e293b;
            cursor: pointer;
            transition: background 0.2s, border-color 0.2s, box-shadow 0.2s;
            font-family: 'Inter', sans-serif;
        }

        .btn-social:hover {
            background: #f8fafc;
            border-color: #cbd5e1;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.04);
        }

        .btn-social svg {
            width: 1.2rem;
            height: 1.2rem;
            flex-shrink: 0;
            fill: currentColor;
        }

        .switch-action {
            text-align: center;
            font-size: 0.92rem;
            color: #475569;
            margin-top: 0.25rem;
        }

        .switch-action .switch-link {
            font-weight: 600;
            color: #1e4b6b;
            background: none;
            border: none;
            font-size: inherit;
            font-family: inherit;
            cursor: pointer;
            padding: 0 0.1rem;
            transition: color 0.2s, border-bottom 0.2s;
            border-bottom: 1.5px solid transparent;
        }

        .switch-action .switch-link:hover {
            color: #0b2c42;
            border-bottom-color: #1e4b6b;
        }

        .panel {
            display: none;
            animation: fadeSlide 0.3s ease-out;
        }

        .panel.active {
            display: block;
        }

        @keyframes fadeSlide {
            0% {
                opacity: 0;
                transform: translateY(10px);
            }
            100% {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .success-banner {
            display: none;
            background: #e6f1ec;
            border-radius: 0.9rem;
            padding: 0.9rem 1.2rem;
            margin-bottom: 1.25rem;
            color: #0b3b2e;
            font-weight: 500;
            font-size: 0.92rem;
            border-left: 4px solid #1f7b5e;
        }

        .success-banner.show {
            display: block;
            animation: fadeSlide 0.3s ease-out;
        }

        @media (max-width: 480px) {
            .auth-card {
                padding: 1.75rem 1.25rem 1.5rem;
                border-radius: 1.5rem;
            }
            .auth-title {
                font-size: 1.5rem;
            }
            .social-row {
                flex-direction: column;
            }
            .row-extra {
                flex-direction: column;
                align-items: flex-start;
            }
            .tab-btn {
                font-size: 0.85rem;
                padding: 0.6rem 0.2rem;
            }
        }
    </style>
</head>
<body>

    <div class="auth-card" role="main" aria-labelledby="auth-title">

        {{-- Tabs --}}
        <div class="tabs" role="tablist">
            <button class="tab-btn active" data-tab="login" role="tab" aria-selected="true" aria-controls="panel-login" id="tab-login">Masuk</button>
            <button class="tab-btn" data-tab="register" role="tab" aria-selected="false" aria-controls="panel-register" id="tab-register">Daftar</button>
        </div>

        {{-- Title --}}
        <h1 class="auth-title" id="auth-title">Login</h1>
        <p class="auth-sub" id="auth-sub">Silakan masukkan kredensial Anda.</p>

        {{-- Success banner (dari session) --}}
        @if (session('success'))
            <div class="success-banner show" id="successBanner">{{ session('success') }}</div>
        @else
            <div class="success-banner" id="successBanner"></div>
        @endif

        {{-- ========== LOGIN PANEL ========== --}}
        <div class="panel active" id="panel-login" role="tabpanel" aria-labelledby="tab-login">
            <form method="POST" action="{{ route('login') }}" id="loginForm" novalidate>
                @csrf
                {{-- Email --}}
                <div class="form-group">
                    <label for="loginEmail">Email</label>
                    <div class="input-wrap @error('email') error @enderror" id="loginEmailWrap">
                        <input type="email" id="loginEmail" name="email" placeholder="nama@email.com" value="{{ old('email') }}" required />
                    </div>
                    <div class="error-message" id="loginEmailError">
                        @error('email') {{ $message }} @enderror
                    </div>
                </div>

                {{-- Password --}}
                <div class="form-group">
                    <label for="loginPassword">Password</label>
                    <div class="input-wrap @error('password') error @enderror" id="loginPwWrap">
                        <input type="password" id="loginPassword" name="password" placeholder="Minimal 6 karakter" required />
                        <button type="button" class="toggle-pw" aria-label="Tampilkan password" data-target="loginPassword">Tampilkan</button>
                    </div>
                    <div class="error-message" id="loginPwError">
                        @error('password') {{ $message }} @enderror
                    </div>
                </div>

                {{-- Extra row --}}
                <div class="row-extra">
                    <label class="checkbox-wrap">
                        <input type="checkbox" name="remember" {{ old('remember') ? 'checked' : '' }} />
                        Ingat Saya
                    </label>
                    <a href="#" class="link-forgot" id="forgotLink">Lupa Password?</a>
                </div>

                <button type="submit" class="btn-submit" id="loginSubmit">Login</button>
            </form>
        </div>

        {{-- ========== REGISTER PANEL ========== --}}
        <div class="panel" id="panel-register" role="tabpanel" aria-labelledby="tab-register">
            <form method="POST" action="{{ route('register') }}" id="registerForm" novalidate>
                @csrf
                {{-- Nama Lengkap --}}
                <div class="form-group">
                    <label for="regName">Nama Lengkap</label>
                    <div class="input-wrap @error('name') error @enderror" id="regNameWrap">
                        <input type="text" id="regName" name="name" placeholder="Nama Anda" value="{{ old('name') }}" required />
                    </div>
                    <div class="error-message" id="regNameError">
                        @error('name') {{ $message }} @enderror
                    </div>
                </div>

                {{-- Email --}}
                <div class="form-group">
                    <label for="regEmail">Email</label>
                    <div class="input-wrap @error('email') error @enderror" id="regEmailWrap">
                        <input type="email" id="regEmail" name="email" placeholder="nama@email.com" value="{{ old('email') }}" required />
                    </div>
                    <div class="error-message" id="regEmailError">
                        @error('email') {{ $message }} @enderror
                    </div>
                </div>

                {{-- Password --}}
                <div class="form-group">
                    <label for="regPassword">Password</label>
                    <div class="input-wrap @error('password') error @enderror" id="regPwWrap">
                        <input type="password" id="regPassword" name="password" placeholder="Minimal 6 karakter" required />
                        <button type="button" class="toggle-pw" aria-label="Tampilkan password" data-target="regPassword">Tampilkan</button>
                    </div>
                    <div class="error-message" id="regPwError">
                        @error('password') {{ $message }} @enderror
                    </div>
                </div>

                {{-- Konfirmasi Password --}}
                <div class="form-group register-fields open" id="confirmGroup">
                    <label for="regConfirm">Konfirmasi Password</label>
                    <div class="input-wrap @error('password_confirmation') error @enderror" id="regConfirmWrap">
                        <input type="password" id="regConfirm" name="password_confirmation" placeholder="Ketik ulang password" required />
                        <button type="button" class="toggle-pw" aria-label="Tampilkan password" data-target="regConfirm">Tampilkan</button>
                    </div>
                    <div class="error-message" id="regConfirmError">
                        @error('password_confirmation') {{ $message }} @enderror
                    </div>
                </div>

                <button type="submit" class="btn-submit" id="registerSubmit">Daftar</button>
            </form>
        </div>

        {{-- Divider --}}
        <div class="divider">
            <span>atau</span>
        </div>

        {{-- Social --}}
        <div class="social-row">
            <button class="btn-social" id="googleBtn" aria-label="Login dengan Google">
                <svg viewBox="0 0 48 48"><path d="M43.611 20.083H42V20H24v8h11.303c-1.649 4.657-6.08 8-11.303 8-6.627 0-12-5.373-12-12s5.373-12 12-12c3.059 0 5.842 1.154 7.961 3.039l5.657-5.657C34.046 6.053 29.268 4 24 4 12.955 4 4 12.955 4 24s8.955 20 20 20 20-8.955 20-20c0-1.341-.138-2.65-.389-3.917z" fill="#FFC107"/><path d="M6.306 14.691l6.571 4.819C14.655 15.108 18.961 12 24 12c3.059 0 5.842 1.154 7.961 3.039l5.657-5.657C34.046 6.053 29.268 4 24 4 16.318 4 9.656 8.337 6.306 14.691z" fill="#FF3D00"/><path d="M24 44c5.166 0 9.86-1.977 13.409-5.192l-6.19-5.238A11.91 11.91 0 0 1 24 36c-5.202 0-9.619-3.317-11.283-7.946l-6.522 5.025C9.505 39.556 16.227 44 24 44z" fill="#4CAF50"/><path d="M43.611 20.083H42V20H24v8h11.303a12.04 12.04 0 0 1-4.087 5.571l.003-.002 6.19 5.238C36.971 39.205 44 34 44 24c0-1.341-.138-2.65-.389-3.917z" fill="#1976D2"/></svg>
                <span>Google</span>
            </button>
            <button class="btn-social" id="facebookBtn" aria-label="Login dengan Facebook">
                <svg viewBox="0 0 48 48"><path d="M48 24C48 10.745 37.255 0 24 0S0 10.745 0 24c0 11.979 8.776 21.908 20.25 23.709v-16.77h-6.094V24h6.094v-5.288c0-6.014 3.583-9.338 9.064-9.338 2.625 0 5.37.469 5.37.469v5.902h-3.025c-2.98 0-3.909 1.849-3.909 3.746V24h6.651l-1.063 6.939h-5.588v16.77C39.224 45.908 48 35.979 48 24z" fill="#1877F2"/></svg>
                <span>Facebook</span>
            </button>
        </div>

        {{-- Switch action --}}
        <div class="switch-action">
            <span id="switchText">Belum punya akun?</span>
            <button class="switch-link" id="switchBtn" aria-label="Beralih ke pendaftaran">Daftar</button>
        </div>
    </div>

    <script>
        (function() {
            'use strict';

            // DOM refs
            const tabs = document.querySelectorAll('.tab-btn');
            const panels = {
                login: document.getElementById('panel-login'),
                register: document.getElementById('panel-register'),
            };
            const title = document.getElementById('auth-title');
            const sub = document.getElementById('auth-sub');
            const switchBtn = document.getElementById('switchBtn');
            const switchText = document.getElementById('switchText');

            // Toggle password visibility
            document.querySelectorAll('.toggle-pw').forEach(btn => {
                btn.addEventListener('click', function() {
                    const targetId = this.getAttribute('data-target');
                    const input = document.getElementById(targetId);
                    if (!input) return;
                    const type = input.getAttribute('type') === 'password' ? 'text' : 'password';
                    input.setAttribute('type', type);
                    this.textContent = type === 'password' ? 'Tampilkan' : 'Sembunyi';
                });
            });

            // Tab switching
            function setActiveTab(tabId) {
                tabs.forEach(t => {
                    const isActive = t.getAttribute('data-tab') === tabId;
                    t.classList.toggle('active', isActive);
                    t.setAttribute('aria-selected', isActive);
                });
                Object.keys(panels).forEach(key => {
                    panels[key].classList.toggle('active', key === tabId);
                });

                if (tabId === 'login') {
                    title.textContent = 'Login';
                    sub.textContent = 'Silakan masukkan kredensial Anda.';
                    switchBtn.textContent = 'Daftar';
                    switchText.textContent = 'Belum punya akun?';
                } else {
                    title.textContent = 'Daftar';
                    sub.textContent = 'Buat akun baru untuk memulai.';
                    switchBtn.textContent = 'Masuk';
                    switchText.textContent = 'Sudah punya akun?';
                }

                // Sembunyikan success banner jika ada
                document.getElementById('successBanner')?.classList.remove('show');
            }

            tabs.forEach(tab => {
                tab.addEventListener('click', function() {
                    setActiveTab(this.getAttribute('data-tab'));
                });
            });

            switchBtn.addEventListener('click', function() {
                const current = document.querySelector('.tab-btn.active');
                if (current) {
                    const next = current.getAttribute('data-tab') === 'login' ? 'register' : 'login';
                    setActiveTab(next);
                    tabs.forEach(t => {
                        const isActive = t.getAttribute('data-tab') === next;
                        t.classList.toggle('active', isActive);
                        t.setAttribute('aria-selected', isActive);
                    });
                }
            });

            // Forgot password demo
            document.getElementById('forgotLink').addEventListener('click', function(e) {
                e.preventDefault();
                alert('Fitur reset password akan segera hadir. Silakan hubungi admin.');
            });

            // Social buttons demo
            document.getElementById('googleBtn').addEventListener('click', function() {
                alert('Fitur login dengan Google akan segera hadir.');
            });
            document.getElementById('facebookBtn').addEventListener('click', function() {
                alert('Fitur login dengan Facebook akan segera hadir.');
            });

            // --- Validasi real-time (opsional) ---
            const loginEmail = document.getElementById('loginEmail');
            const loginPassword = document.getElementById('loginPassword');
            const loginEmailError = document.getElementById('loginEmailError');
            const loginPwError = document.getElementById('loginPwError');
            const loginEmailWrap = document.getElementById('loginEmailWrap');
            const loginPwWrap = document.getElementById('loginPwWrap');

            function isValidEmail(email) {
                return /^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(email);
            }
            function isValidPassword(pw) {
                return pw.length >= 6;
            }

            loginEmail.addEventListener('blur', function() {
                const val = this.value.trim();
                if (val && !isValidEmail(val)) {
                    loginEmailWrap.classList.add('error');
                    loginEmailError.textContent = 'Email tidak valid.';
                } else {
                    loginEmailWrap.classList.remove('error');
                    loginEmailError.textContent = '';
                }
            });
            loginEmail.addEventListener('input', function() {
                if (loginEmailWrap.classList.contains('error')) {
                    const val = this.value.trim();
                    if (val && isValidEmail(val)) {
                        loginEmailWrap.classList.remove('error');
                        loginEmailError.textContent = '';
                    }
                }
            });

            loginPassword.addEventListener('blur', function() {
                const val = this.value.trim();
                if (val && !isValidPassword(val)) {
                    loginPwWrap.classList.add('error');
                    loginPwError.textContent = 'Minimal 6 karakter.';
                } else {
                    loginPwWrap.classList.remove('error');
                    loginPwError.textContent = '';
                }
            });
            loginPassword.addEventListener('input', function() {
                if (loginPwWrap.classList.contains('error')) {
                    const val = this.value.trim();
                    if (val && isValidPassword(val)) {
                        loginPwWrap.classList.remove('error');
                        loginPwError.textContent = '';
                    }
                }
            });

            // Register fields
            const regName = document.getElementById('regName');
            const regEmail = document.getElementById('regEmail');
            const regPassword = document.getElementById('regPassword');
            const regConfirm = document.getElementById('regConfirm');
            const regNameError = document.getElementById('regNameError');
            const regEmailError = document.getElementById('regEmailError');
            const regPwError = document.getElementById('regPwError');
            const regConfirmError = document.getElementById('regConfirmError');
            const regNameWrap = document.getElementById('regNameWrap');
            const regEmailWrap = document.getElementById('regEmailWrap');
            const regPwWrap = document.getElementById('regPwWrap');
            const regConfirmWrap = document.getElementById('regConfirmWrap');

            function isValidName(name) {
                return name.trim().length >= 2;
            }

            regName.addEventListener('blur', function() {
                const val = this.value.trim();
                if (val && !isValidName(val)) {
                    regNameWrap.classList.add('error');
                    regNameError.textContent = 'Minimal 2 karakter.';
                } else {
                    regNameWrap.classList.remove('error');
                    regNameError.textContent = '';
                }
            });
            regName.addEventListener('input', function() {
                if (regNameWrap.classList.contains('error')) {
                    const val = this.value.trim();
                    if (val && isValidName(val)) {
                        regNameWrap.classList.remove('error');
                        regNameError.textContent = '';
                    }
                }
            });

            regEmail.addEventListener('blur', function() {
                const val = this.value.trim();
                if (val && !isValidEmail(val)) {
                    regEmailWrap.classList.add('error');
                    regEmailError.textContent = 'Email tidak valid.';
                } else {
                    regEmailWrap.classList.remove('error');
                    regEmailError.textContent = '';
                }
            });
            regEmail.addEventListener('input', function() {
                if (regEmailWrap.classList.contains('error')) {
                    const val = this.value.trim();
                    if (val && isValidEmail(val)) {
                        regEmailWrap.classList.remove('error');
                        regEmailError.textContent = '';
                    }
                }
            });

            regPassword.addEventListener('blur', function() {
                const val = this.value.trim();
                if (val && !isValidPassword(val)) {
                    regPwWrap.classList.add('error');
                    regPwError.textContent = 'Minimal 6 karakter.';
                } else {
                    regPwWrap.classList.remove('error');
                    regPwError.textContent = '';
                }
                checkConfirmMatch();
            });
            regPassword.addEventListener('input', function() {
                if (regPwWrap.classList.contains('error')) {
                    const val = this.value.trim();
                    if (val && isValidPassword(val)) {
                        regPwWrap.classList.remove('error');
                        regPwError.textContent = '';
                    }
                }
                checkConfirmMatch();
            });

            regConfirm.addEventListener('blur', checkConfirmMatch);
            regConfirm.addEventListener('input', checkConfirmMatch);

            function checkConfirmMatch() {
                const pw = regPassword.value.trim();
                const confirm = regConfirm.value.trim();
                if (confirm && pw !== confirm) {
                    regConfirmWrap.classList.add('error');
                    regConfirmError.textContent = 'Password tidak cocok.';
                } else if (confirm && pw === confirm && isValidPassword(pw)) {
                    regConfirmWrap.classList.remove('error');
                    regConfirmError.textContent = '';
                } else if (confirm && !pw) {
                    regConfirmWrap.classList.add('error');
                    regConfirmError.textContent = 'Isi password terlebih dahulu.';
                } else {
                    regConfirmWrap.classList.remove('error');
                    regConfirmError.textContent = '';
                }
            }

            // Reset error saat pindah tab
            tabs.forEach(tab => {
                tab.addEventListener('click', function() {
                    document.querySelectorAll('.input-wrap.error').forEach(el => el.classList.remove('error'));
                    document.querySelectorAll('.error-message').forEach(el => el.textContent = '');
                });
            });

            // --- Set active tab jika ada error dari server ---
            @if ($errors->has('email') || $errors->has('password'))
                setActiveTab('login');
                tabs.forEach(t => {
                    const isActive = t.getAttribute('data-tab') === 'login';
                    t.classList.toggle('active', isActive);
                    t.setAttribute('aria-selected', isActive);
                });
            @elseif ($errors->any())
                setActiveTab('register');
                tabs.forEach(t => {
                    const isActive = t.getAttribute('data-tab') === 'register';
                    t.classList.toggle('active', isActive);
                    t.setAttribute('aria-selected', isActive);
                });
            @endif   // <-- INI YANG DITAMBAHKAN

        })();
    </script>

</body>
</html>
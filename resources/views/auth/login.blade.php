<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login — UTBK Tracker</title>
    <link rel="icon" type="image/svg+xml" href="{{ asset('images/utbk-tracker-logo.svg') }}">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <script src="https://unpkg.com/lucide@latest"></script>

    <style>
        :root {
            --bg-canvas: #f8fafc;
            --card-bg: rgba(255, 255, 255, 0.94);
            --card-border: rgba(226, 232, 240, 0.85);
            --text-main: #0f172a;
            --text-muted: #64748b;
            --primary-deep: #1e3a8a;
            --primary: #2563eb;
            --primary-hover: #1d4ed8;
            --primary-light: #eff6ff;
            --input-bg: #ffffff;
            --input-border: #cbd5e1;
            --input-focus-border: #2563eb;
            --input-focus-glow: rgba(37, 99, 235, 0.14);
            --danger-bg: #fef2f2;
            --danger-text: #991b1b;
            --danger-border: #fecaca;
            --shadow-glass: 0 25px 50px -12px rgba(30, 58, 138, 0.12);
            --radius-card: 24px;
            --radius-input: 12px;
        }

        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }

        html, body {
            width: 100%;
            height: 100%;
            font-family: 'Plus Jakarta Sans', -apple-system, BlinkMacSystemFont, sans-serif;
            background-color: var(--bg-canvas);
            color: var(--text-main);
            overflow-x: hidden;
            -webkit-font-smoothing: antialiased;
        }

        .page-wrapper {
            min-height: 100vh;
            width: 100%;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 1.5rem;
            position: relative;
            background: 
                radial-gradient(circle at 15% 15%, rgba(59, 130, 246, 0.08) 0%, transparent 40%),
                radial-gradient(circle at 85% 85%, rgba(30, 58, 138, 0.06) 0%, transparent 45%),
                radial-gradient(circle at 50% 50%, rgba(224, 242, 254, 0.5) 0%, transparent 80%),
                #f8fafc;
        }

        .ambient-shape {
            position: absolute;
            border-radius: 50%;
            filter: blur(60px);
            z-index: 0;
            pointer-events: none;
        }

        .shape-1 {
            width: 320px;
            height: 320px;
            top: 10%;
            left: 15%;
            background: rgba(96, 165, 250, 0.15);
        }

        .shape-2 {
            width: 380px;
            height: 380px;
            bottom: 10%;
            right: 15%;
            background: rgba(30, 58, 138, 0.08);
        }

        .login-container {
            width: 100%;
            max-width: 440px;
            position: relative;
            z-index: 10;
            animation: cardFadeIn 0.35s cubic-bezier(0.16, 1, 0.3, 1);
        }

        @keyframes cardFadeIn {
            from {
                opacity: 0;
                transform: translateY(12px) scale(0.98);
            }
            to {
                opacity: 1;
                transform: translateY(0) scale(1);
            }
        }

        .brand-header {
            text-align: center;
            margin-bottom: 1.75rem;
        }

        .logo-wrapper {
            width: 64px;
            height: 64px;
            margin: 0 auto 1rem auto;
            border-radius: 18px;
            background: #ffffff;
            padding: 8px;
            box-shadow: 0 10px 25px -5px rgba(37, 99, 235, 0.25), 0 0 0 1px rgba(226, 232, 240, 0.8);
            display: flex;
            align-items: center;
            justify-content: center;
            transition: transform 0.2s ease;
        }

        .logo-wrapper:hover {
            transform: translateY(-2px);
        }

        .logo-wrapper img {
            width: 100%;
            height: 100%;
            object-fit: contain;
        }

        .brand-title {
            font-size: 1.75rem;
            font-weight: 800;
            color: var(--primary-deep);
            letter-spacing: -0.03em;
            line-height: 1.2;
            margin-bottom: 0.35rem;
        }

        .brand-tagline {
            font-size: 0.9375rem;
            color: var(--text-muted);
            font-weight: 600;
        }

        .login-card {
            background-color: var(--card-bg);
            backdrop-filter: blur(20px);
            -webkit-backdrop-filter: blur(20px);
            border: 1px solid var(--card-border);
            border-radius: var(--radius-card);
            box-shadow: var(--shadow-glass);
            padding: 2.25rem 2rem;
        }

        .alert-error {
            background-color: var(--danger-bg);
            border: 1px solid var(--danger-border);
            color: var(--danger-text);
            padding: 0.875rem 1rem;
            border-radius: var(--radius-input);
            font-size: 0.875rem;
            font-weight: 600;
            margin-bottom: 1.25rem;
            display: flex;
            align-items: center;
            gap: 0.625rem;
            animation: alertSlideIn 0.25s ease;
        }

        @keyframes alertSlideIn {
            from { opacity: 0; transform: translateY(-6px); }
            to { opacity: 1; transform: translateY(0); }
        }

        .form-group {
            margin-bottom: 1.25rem;
        }

        .form-label {
            display: block;
            font-size: 0.875rem;
            font-weight: 700;
            color: var(--text-main);
            margin-bottom: 0.5rem;
        }

        .input-wrapper {
            position: relative;
            display: flex;
            align-items: center;
        }

        .form-input {
            width: 100%;
            height: 48px;
            padding: 0 1rem;
            font-family: inherit;
            font-size: 0.9375rem;
            color: var(--text-main);
            background-color: var(--input-bg);
            border: 1.5px solid var(--input-border);
            border-radius: var(--radius-input);
            outline: none;
            transition: border-color 0.2s ease, box-shadow 0.2s ease;
        }

        .form-input::placeholder {
            color: #94a3b8;
        }

        .form-input:focus {
            border-color: var(--input-focus-border);
            box-shadow: 0 0 0 4px var(--input-focus-glow);
        }

        .password-toggle-btn {
            position: absolute;
            right: 12px;
            background: none;
            border: none;
            padding: 6px;
            cursor: pointer;
            color: var(--text-muted);
            border-radius: 6px;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: color 0.2s ease, background-color 0.2s ease;
        }

        .password-toggle-btn:hover {
            color: var(--primary);
            background-color: var(--primary-light);
        }

        .password-toggle-btn:focus-visible {
            outline: 2px solid var(--primary);
        }

        .form-options {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 1.5rem;
        }

        .remember-checkbox {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            font-size: 0.875rem;
            color: var(--text-muted);
            cursor: pointer;
            user-select: none;
            font-weight: 600;
        }

        .remember-checkbox input[type="checkbox"] {
            width: 16px;
            height: 16px;
            accent-color: var(--primary);
            cursor: pointer;
            border-radius: 4px;
        }

        .btn-submit {
            width: 100%;
            height: 50px;
            background: linear-gradient(135deg, #1d4ed8, #2563eb);
            color: #ffffff;
            border: none;
            border-radius: var(--radius-input);
            font-family: inherit;
            font-size: 0.95rem;
            font-weight: 800;
            letter-spacing: 0.02em;
            cursor: pointer;
            box-shadow: 0 4px 14px rgba(37, 99, 235, 0.35);
            transition: background 0.2s ease, transform 0.1s ease, box-shadow 0.2s ease;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 0.5rem;
        }

        .btn-submit:hover {
            background: linear-gradient(135deg, #1e40af, #1d4ed8);
            box-shadow: 0 6px 20px rgba(37, 99, 235, 0.45);
        }

        .btn-submit:active {
            transform: scale(0.99);
        }

        .btn-submit:disabled {
            opacity: 0.75;
            cursor: not-allowed;
            transform: none;
        }

        .spinner {
            width: 18px;
            height: 18px;
            border: 2px solid rgba(255, 255, 255, 0.3);
            border-radius: 50%;
            border-top-color: #ffffff;
            animation: spin 0.8s linear infinite;
        }

        @keyframes spin {
            to { transform: rotate(360deg); }
        }

        .card-footer-text {
            text-align: center;
            margin-top: 1.5rem;
            font-size: 0.8125rem;
            font-weight: 700;
            color: #94a3b8;
            letter-spacing: 0.05em;
            text-transform: uppercase;
        }

        @media (max-width: 480px) {
            .page-wrapper {
                padding: 1rem;
            }
            .login-card {
                padding: 1.75rem 1.25rem;
                border-radius: 20px;
            }
            .brand-title {
                font-size: 1.5rem;
            }
        }
    </style>
</head>
<body>
    <div class="page-wrapper">
        <div class="ambient-shape shape-1"></div>
        <div class="ambient-shape shape-2"></div>

        <div class="login-container">
            <div class="brand-header">
                <div class="logo-wrapper">
                    <img src="{{ asset('images/utbk-tracker-logo.svg') }}" alt="UTBK Tracker Logo">
                </div>
                <h1 class="brand-title">UTBK Tracker</h1>
                <p class="brand-tagline">Your progress, tracked.</p>
            </div>

            <div class="login-card">
                @if ($errors->any())
                    <div class="alert-error" role="alert">
                        <i data-lucide="alert-circle" style="width: 18px; height: 18px;"></i>
                        <span>
                            @if ($errors->has('login'))
                                {{ $errors->first('login') }}
                            @elseif ($errors->has('username'))
                                {{ $errors->first('username') }}
                            @elseif ($errors->has('password'))
                                {{ $errors->first('password') }}
                            @else
                                Username atau password salah.
                            @endif
                        </span>
                    </div>
                @endif

                <form action="{{ route('login') }}" method="POST" id="loginForm">
                    @csrf

                    <div class="form-group">
                        <label for="username" class="form-label">Username</label>
                        <div class="input-wrapper">
                            <input 
                                type="text" 
                                id="username" 
                                name="username" 
                                class="form-input" 
                                placeholder="Enter your username" 
                                value="{{ old('username') }}"
                                required 
                                autofocus 
                                autocomplete="username"
                            >
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="password" class="form-label">Password</label>
                        <div class="input-wrapper">
                            <input 
                                type="password" 
                                id="password" 
                                name="password" 
                                class="form-input" 
                                placeholder="Enter your password" 
                                style="padding-right: 44px;"
                                required 
                                autocomplete="current-password"
                            >
                            <button 
                                type="button" 
                                class="password-toggle-btn" 
                                id="togglePasswordBtn" 
                                aria-label="Tampilkan password" 
                                title="Tampilkan password"
                            >
                                <i data-lucide="eye" id="eyeIcon" style="width: 18px; height: 18px;"></i>
                                <i data-lucide="eye-off" id="eyeOffIcon" style="width: 18px; height: 18px; display: none;"></i>
                            </button>
                        </div>
                    </div>

                    <div class="form-options">
                        <label class="remember-checkbox">
                            <input type="checkbox" name="remember" id="remember" {{ old('remember') ? 'checked' : '' }}>
                            <span>Remember me</span>
                        </label>
                    </div>

                    <button type="submit" class="btn-submit" id="btnSubmit">
                        <i data-lucide="log-in" style="width: 18px; height: 18px;"></i>
                        <span id="btnText">LOGIN</span>
                        <div id="btnSpinner" class="spinner" style="display: none;"></div>
                    </button>
                </form>

                <div class="card-footer-text">
                    Private Study Platform
                </div>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', () => {
            if (typeof lucide !== 'undefined') {
                lucide.createIcons();
            }

            const passwordInput = document.getElementById('password');
            const togglePasswordBtn = document.getElementById('togglePasswordBtn');
            const eyeIcon = document.getElementById('eyeIcon');
            const eyeOffIcon = document.getElementById('eyeOffIcon');

            if (togglePasswordBtn && passwordInput) {
                togglePasswordBtn.addEventListener('click', () => {
                    const isPassword = passwordInput.getAttribute('type') === 'password';
                    passwordInput.setAttribute('type', isPassword ? 'text' : 'password');
                    
                    if (isPassword) {
                        eyeIcon.style.display = 'none';
                        eyeOffIcon.style.display = 'inline-block';
                        togglePasswordBtn.setAttribute('aria-label', 'Sembunyikan password');
                        togglePasswordBtn.setAttribute('title', 'Sembunyikan password');
                    } else {
                        eyeIcon.style.display = 'inline-block';
                        eyeOffIcon.style.display = 'none';
                        togglePasswordBtn.setAttribute('aria-label', 'Tampilkan password');
                        togglePasswordBtn.setAttribute('title', 'Tampilkan password');
                    }
                });
            }

            const loginForm = document.getElementById('loginForm');
            const btnSubmit = document.getElementById('btnSubmit');
            const btnText = document.getElementById('btnText');
            const btnSpinner = document.getElementById('btnSpinner');

            if (loginForm && btnSubmit) {
                loginForm.addEventListener('submit', () => {
                    btnSubmit.disabled = true;
                    btnText.textContent = 'Signing in...';
                    btnSpinner.style.display = 'inline-block';
                });
            }
        });
    </script>
</body>
</html>

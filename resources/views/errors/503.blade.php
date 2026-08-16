<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>503 — Sedang Dalam Pemeliharaan | UTBK Tracker</title>
    <link rel="icon" type="image/svg+xml" href="{{ asset('images/utbk-tracker-logo.svg') }}">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        :root {
            --bg-canvas: #f8fafc;
            --card-bg: rgba(255, 255, 255, 0.94);
            --card-border: rgba(226, 232, 240, 0.9);
            --text-main: #0f172a;
            --text-muted: #64748b;
            --primary-deep: #1e3a8a;
            --primary: #4f46e5;
            --shadow-glass: 0 25px 50px -12px rgba(30, 58, 138, 0.12);
            --radius-card: 24px;
        }

        * { box-sizing: border-box; margin: 0; padding: 0; }

        body {
            font-family: 'Plus Jakarta Sans', -apple-system, sans-serif;
            background-color: var(--bg-canvas);
            color: var(--text-main);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 1.5rem;
            background: 
                radial-gradient(circle at 15% 15%, rgba(245, 158, 11, 0.08) 0%, transparent 45%),
                radial-gradient(circle at 85% 85%, rgba(30, 58, 138, 0.06) 0%, transparent 50%),
                #f8fafc;
        }

        .error-card {
            background-color: var(--card-bg);
            backdrop-filter: blur(20px);
            border: 1px solid var(--card-border);
            border-radius: var(--radius-card);
            box-shadow: var(--shadow-glass);
            padding: 3rem 2.5rem;
            max-width: 500px;
            width: 100%;
            text-align: center;
        }

        .error-code {
            font-size: 4rem;
            font-weight: 800;
            background: linear-gradient(135deg, #f59e0b, #d97706);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            line-height: 1;
            margin-bottom: 0.75rem;
            letter-spacing: -0.04em;
        }

        .error-title {
            font-size: 1.5rem;
            font-weight: 800;
            color: var(--text-main);
            margin-bottom: 0.75rem;
        }

        .error-desc {
            font-size: 0.95rem;
            color: var(--text-muted);
            line-height: 1.6;
            margin-bottom: 2rem;
        }

        .btn-logout {
            background: linear-gradient(135deg, #ef4444, #dc2626);
            color: #ffffff;
            border: none;
            padding: 0.875rem 2rem;
            border-radius: 12px;
            font-family: inherit;
            font-size: 0.95rem;
            font-weight: 700;
            cursor: pointer;
            box-shadow: 0 4px 14px rgba(239, 68, 68, 0.35);
            transition: all 0.2s ease;
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
        }

        .btn-logout:hover {
            background: linear-gradient(135deg, #dc2626, #b91c1c);
            transform: translateY(-2px);
        }
    </style>
</head>
<body>
    <div class="error-card">
        <div style="font-size: 3.5rem; margin-bottom: 0.5rem;">🛠️</div>
        <div class="error-code">503</div>
        <h1 class="error-title">Website Sedang Perbaikan</h1>
        <p class="error-desc">
            {{ $exception->getMessage() ?: 'Sistem UTBK Tracker sedang menjalani pemeliharaan berkala untuk peningkatan kualitas layanan. Silakan kembali beberapa saat lagi.' }}
        </p>
        @auth
            <form action="{{ route('logout') }}" method="POST">
                @csrf
                <button type="submit" class="btn-logout">
                    🚪 Logout Sekarang
                </button>
            </form>
        @else
            <a href="{{ route('login') }}" class="btn-logout" style="background: linear-gradient(135deg, #4f46e5, #3730a3); box-shadow: 0 4px 14px rgba(79, 70, 229, 0.35); text-decoration: none;">
                🔑 Ke Halaman Login
            </a>
        @endauth
    </div>
</body>
</html>

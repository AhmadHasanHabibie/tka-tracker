<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>419 — Sesi Kadaluarsa | UTBK Tracker</title>
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
                radial-gradient(circle at 15% 15%, rgba(59, 130, 246, 0.08) 0%, transparent 45%),
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
            font-size: 5rem;
            font-weight: 800;
            background: linear-gradient(135deg, #2563eb, #1d4ed8);
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

        .btn-home {
            background: linear-gradient(135deg, #4f46e5, #3730a3);
            color: #ffffff;
            text-decoration: none;
            padding: 0.875rem 2rem;
            border-radius: 12px;
            font-weight: 700;
            font-size: 0.95rem;
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            box-shadow: 0 4px 14px rgba(79, 70, 229, 0.35);
            transition: all 0.2s ease;
        }

        .btn-home:hover {
            background: linear-gradient(135deg, #4338ca, #312e81);
            transform: translateY(-2px);
        }
    </style>
</head>
<body>
    <div class="error-card">
        <div style="font-size: 3.5rem; margin-bottom: 0.5rem;">⏳</div>
        <div class="error-code">419</div>
        <h1 class="error-title">Sesi Halaman Kadaluarsa</h1>
        <p class="error-desc">
            Sesi keamanan formulir Anda telah kadaluarsa karena terlalu lama tidak aktif. Silakan muat ulang halaman.
        </p>
        <a href="{{ url()->previous() }}" class="btn-home">
            🔄 Muat Ulang Halaman
        </a>
    </div>
</body>
</html>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sedang Dalam Pemeliharaan — UTBK Tracker</title>
    <link rel="icon" type="image/svg+xml" href="{{ asset('images/utbk-tracker-logo.svg') }}">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        :root {
            --bg-canvas: #f8fafc;
            --card-bg: rgba(255, 255, 255, 0.92);
            --card-border: rgba(226, 232, 240, 0.9);
            --text-main: #0f172a;
            --text-muted: #64748b;
            --primary-deep: #1e3a8a;
            --primary: #2563eb;
            --danger: #ef4444;
            --danger-hover: #dc2626;
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
                radial-gradient(circle at 15% 15%, rgba(59, 130, 246, 0.1) 0%, transparent 45%),
                radial-gradient(circle at 85% 85%, rgba(30, 58, 138, 0.08) 0%, transparent 50%),
                #f8fafc;
        }

        .maintenance-card {
            background-color: var(--card-bg);
            backdrop-filter: blur(20px);
            border: 1px solid var(--card-border);
            border-radius: var(--radius-card);
            box-shadow: var(--shadow-glass);
            padding: 3rem 2.5rem;
            max-width: 520px;
            width: 100%;
            text-align: center;
        }

        .maintenance-icon {
            font-size: 4rem;
            margin-bottom: 1.25rem;
            display: inline-block;
            animation: pulse 2s infinite ease-in-out;
        }

        @keyframes pulse {
            0%, 100% { transform: scale(1); }
            50% { transform: scale(1.08); }
        }

        .title {
            font-size: 1.75rem;
            font-weight: 800;
            color: var(--primary-deep);
            letter-spacing: -0.03em;
            margin-bottom: 0.75rem;
            line-height: 1.3;
        }

        .description {
            font-size: 0.95rem;
            color: var(--text-muted);
            line-height: 1.6;
            margin-bottom: 2rem;
        }

        .status-badge {
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            background: #fef3c7;
            color: #92400e;
            padding: 0.5rem 1rem;
            border-radius: 20px;
            font-size: 0.85rem;
            font-weight: 700;
            margin-bottom: 1.75rem;
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
            box-shadow: 0 4px 14px rgba(239, 68, 68, 0.3);
            transition: all 0.2s ease;
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
        }

        .btn-logout:hover {
            background: linear-gradient(135deg, #dc2626, #b91c1c);
            box-shadow: 0 6px 18px rgba(239, 68, 68, 0.4);
            transform: translateY(-1px);
        }
    </style>
</head>
<body>
    <div class="maintenance-card">
        <div class="maintenance-icon">🛠️</div>
        
        <div class="status-badge">
            <span style="width: 8px; height: 8px; border-radius: 50%; background: #f59e0b; display: inline-block;"></span>
            Mode Pemeliharaan Aktif
        </div>

        <h1 class="title">Website Sedang Perbaikan</h1>
        
        <p class="description">
            {{ $message ?? 'Sistem UTBK Tracker sedang menjalani pemeliharaan berkala untuk peningkatan kualitas layanan. Silakan kembali beberapa saat lagi.' }}
        </p>

        @auth
            <form action="{{ route('logout') }}" method="POST">
                @csrf
                <button type="submit" class="btn-logout">
                    🚪 Logout Sekarang
                </button>
            </form>
        @else
            <div style="font-size: 0.8125rem; color: var(--text-muted); font-weight: 600;">
                UTBK Tracker &copy; {{ date('Y') }}
            </div>
        @endauth
    </div>
</body>
</html>

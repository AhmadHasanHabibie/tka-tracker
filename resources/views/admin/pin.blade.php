<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Verifikasi PIN Admin — UTBK Tracker</title>
    <link rel="icon" type="image/svg+xml" href="{{ asset('images/utbk-tracker-logo.svg') }}">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <script src="https://unpkg.com/lucide@latest"></script>
    <style>
        :root {
            --bg-canvas: #f8fafc;
            --card-bg: rgba(255, 255, 255, 0.96);
            --card-border: rgba(226, 232, 240, 0.85);
            --text-main: #0f172a;
            --text-muted: #64748b;
            --primary-deep: #1e3a8a;
            --primary: #2563eb;
            --primary-hover: #1d4ed8;
            --danger-bg: #fef2f2;
            --danger-text: #991b1b;
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
                radial-gradient(circle at 15% 15%, rgba(59, 130, 246, 0.08) 0%, transparent 40%),
                radial-gradient(circle at 85% 85%, rgba(30, 58, 138, 0.06) 0%, transparent 45%),
                #f8fafc;
        }

        .pin-card {
            background-color: var(--card-bg);
            backdrop-filter: blur(20px);
            border: 1px solid var(--card-border);
            border-radius: var(--radius-card);
            box-shadow: var(--shadow-glass);
            padding: 2.5rem 2rem;
            max-width: 420px;
            width: 100%;
            text-align: center;
        }

        .pin-icon {
            width: 60px;
            height: 60px;
            background: #eff6ff;
            color: var(--primary);
            border-radius: 18px;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 1.25rem auto;
            box-shadow: 0 8px 20px rgba(37, 99, 235, 0.15);
        }

        .pin-title {
            font-size: 1.45rem;
            font-weight: 800;
            color: var(--primary-deep);
            margin-bottom: 0.35rem;
        }

        .pin-subtitle {
            font-size: 0.875rem;
            color: var(--text-muted);
            margin-bottom: 1.75rem;
            line-height: 1.5;
        }

        .pin-input {
            width: 100%;
            height: 52px;
            text-align: center;
            font-size: 1.5rem;
            font-weight: 800;
            letter-spacing: 0.35em;
            color: var(--primary-deep);
            background: #ffffff;
            border: 2px solid #cbd5e1;
            border-radius: 14px;
            outline: none;
            margin-bottom: 1.5rem;
            transition: border-color 0.2s ease, box-shadow 0.2s ease;
        }

        .pin-input:focus {
            border-color: var(--primary);
            box-shadow: 0 0 0 4px rgba(59, 130, 246, 0.18);
        }

        .btn-submit {
            width: 100%;
            height: 48px;
            background: linear-gradient(135deg, #1d4ed8, #2563eb);
            color: #ffffff;
            border: none;
            border-radius: 12px;
            font-size: 0.95rem;
            font-weight: 700;
            cursor: pointer;
            box-shadow: 0 4px 14px rgba(37, 99, 235, 0.35);
            transition: all 0.2s ease;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 0.5rem;
        }

        .btn-submit:hover {
            background: linear-gradient(135deg, #1e40af, #1d4ed8);
            box-shadow: 0 6px 20px rgba(37, 99, 235, 0.45);
        }

        .alert-error {
            background-color: var(--danger-bg);
            color: var(--danger-text);
            padding: 0.75rem 1rem;
            border-radius: 10px;
            font-size: 0.85rem;
            font-weight: 600;
            margin-bottom: 1.25rem;
        }
    </style>
</head>
<body>
    <div class="pin-card">
        <div class="pin-icon">
            <i data-lucide="shield-check" style="width: 28px; height: 28px;"></i>
        </div>
        <h1 class="pin-title">Verifikasi PIN Admin</h1>
        <p class="pin-subtitle">Masukkan PIN Keamanan Admin untuk melanjutkan ke Dashboard Administrator.</p>

        @if ($errors->any())
            <div class="alert-error">
                {{ $errors->first() }}
            </div>
        @endif

        <form action="{{ route('admin.pin.verify') }}" method="POST">
            @csrf
            <input 
                type="password" 
                name="pin" 
                class="pin-input" 
                placeholder="••••••" 
                maxlength="6" 
                autofocus 
                required
            >
            <button type="submit" class="btn-submit">
                <i data-lucide="key-round" style="width: 18px; height: 18px;"></i>
                Verifikasi PIN
            </button>
        </form>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', () => {
            if (typeof lucide !== 'undefined') {
                lucide.createIcons();
            }
        });
    </script>
</body>
</html>

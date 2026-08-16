<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>UTBK & TKA Study Tracker</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <script src="https://unpkg.com/lucide@latest"></script>
    <style>
        :root {
            --bg-body: #f8fafc;
            --sidebar-bg: #ffffff;
            --card-bg: #ffffff;
            --border-color: #e2e8f0;
            --border-hover: #cbd5e1;

            --primary-deep: #1e3a8a;
            --primary: #2563eb;
            --primary-hover: #1d4ed8;
            --primary-light: #eff6ff;
            --primary-border: #bfdbfe;
            --primary-text: #1e40af;

            --text-main: #0f172a;
            --text-body: #334155;
            --text-muted: #64748b;

            --success: #10b981;
            --success-light: #ecfdf5;
            --success-text: #047857;

            --warning: #f59e0b;
            --warning-light: #fffbeb;
            --warning-text: #b45309;

            --danger: #ef4444;
            --danger-light: #fef2f2;
            --danger-text: #b91c1c;

            --radius: 16px;
            --radius-sm: 10px;
            --sidebar-width: 260px;

            --shadow-sm: 0 1px 3px rgba(15, 23, 42, 0.04), 0 1px 2px rgba(15, 23, 42, 0.02);
            --shadow-card: 0 4px 20px -2px rgba(15, 23, 42, 0.05);
            --shadow-hover: 0 10px 25px -5px rgba(37, 99, 235, 0.12);
            --transition-smooth: all 0.2s ease-in-out;
        }

        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }

        body {
            font-family: 'Plus Jakarta Sans', -apple-system, BlinkMacSystemFont, sans-serif;
            background-color: var(--bg-body);
            color: var(--text-main);
            min-height: 100vh;
            display: flex;
            -webkit-font-smoothing: antialiased;
        }

        /* Lucide Global Icons Styling */
        .lucide {
            stroke-width: 2.2px;
            vertical-align: middle;
            display: inline-block;
            flex-shrink: 0;
        }

        /* Sidebar Styling */
        .sidebar {
            width: var(--sidebar-width);
            background-color: var(--sidebar-bg);
            border-right: 1px solid var(--border-color);
            display: flex;
            flex-direction: column;
            position: fixed;
            top: 0;
            bottom: 0;
            left: 0;
            z-index: 100;
            box-shadow: 2px 0 12px rgba(15, 23, 42, 0.03);
        }

        .sidebar-brand {
            padding: 1.5rem;
            display: flex;
            align-items: center;
            gap: 0.875rem;
            border-bottom: 1px solid var(--border-color);
        }

        .brand-icon {
            width: 40px;
            height: 40px;
            background: linear-gradient(135deg, #1e3a8a, #2563eb);
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #ffffff;
            box-shadow: 0 6px 16px rgba(37, 99, 235, 0.25);
        }

        .brand-text {
            font-size: 1.2rem;
            font-weight: 800;
            color: var(--primary-deep);
            letter-spacing: -0.03em;
        }

        .sidebar-nav {
            padding: 1.25rem 1rem;
            display: flex;
            flex-direction: column;
            gap: 0.35rem;
            flex: 1;
            overflow-y: auto;
        }

        .nav-item {
            display: flex;
            align-items: center;
            gap: 0.875rem;
            padding: 0.75rem 1.1rem;
            color: var(--text-muted);
            text-decoration: none;
            font-weight: 600;
            font-size: 0.925rem;
            border-radius: var(--radius-sm);
            transition: var(--transition-smooth);
        }

        .nav-item .lucide {
            width: 19px;
            height: 19px;
            color: var(--text-muted);
            transition: var(--transition-smooth);
        }

        .nav-item:hover {
            color: var(--primary);
            background-color: var(--primary-light);
        }

        .nav-item:hover .lucide {
            color: var(--primary);
        }

        .nav-item.active {
            color: var(--primary-text);
            background-color: var(--primary-light);
            font-weight: 700;
        }

        .nav-item.active .lucide {
            color: var(--primary);
        }

        .sidebar-footer {
            padding: 1.25rem 1.5rem;
            border-top: 1px solid var(--border-color);
            font-size: 0.8rem;
            color: var(--text-muted);
            text-align: center;
            font-weight: 600;
        }

        /* Main Wrapper */
        .main-wrapper {
            margin-left: var(--sidebar-width);
            flex: 1;
            display: flex;
            flex-direction: column;
            min-height: 100vh;
        }

        .top-navbar {
            height: 72px;
            background-color: rgba(255, 255, 255, 0.9);
            backdrop-filter: blur(12px);
            border-bottom: 1px solid var(--border-color);
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 0 2.5rem;
            position: sticky;
            top: 0;
            z-index: 90;
        }

        .page-header-title {
            font-size: 1.3rem;
            font-weight: 800;
            color: var(--primary-deep);
            letter-spacing: -0.02em;
        }

        /* Profile Dropdown Component */
        .profile-dropdown-container {
            position: relative;
            display: inline-block;
        }

        .profile-avatar-btn {
            width: 42px;
            height: 42px;
            border-radius: 50%;
            background: #ffffff;
            border: 2.5px solid var(--primary-light);
            padding: 0;
            cursor: pointer;
            overflow: hidden;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: var(--transition-smooth);
            box-shadow: 0 4px 12px rgba(37, 99, 235, 0.12);
        }

        .profile-avatar-btn:hover,
        .profile-avatar-btn:focus,
        .profile-avatar-btn.active {
            border-color: var(--primary);
            box-shadow: 0 0 0 4px rgba(37, 99, 235, 0.18);
            transform: scale(1.04);
            outline: none;
        }

        .profile-avatar-img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            border-radius: 50%;
        }

        .profile-avatar-placeholder {
            width: 100%;
            height: 100%;
            background: linear-gradient(135deg, #1e3a8a, #2563eb);
            color: #ffffff;
            font-weight: 800;
            font-size: 1.1rem;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 50%;
        }

        .profile-dropdown-menu {
            position: absolute;
            top: calc(100% + 10px);
            right: 0;
            width: 220px;
            background: #ffffff;
            border: 1px solid var(--border-color);
            border-radius: var(--radius);
            box-shadow: 0 16px 36px -6px rgba(15, 23, 42, 0.16);
            padding: 0.5rem;
            z-index: 1000;
            display: none;
            opacity: 0;
            transform: translateY(-8px);
            transition: opacity 0.2s ease, transform 0.2s ease;
        }

        .profile-dropdown-menu.show {
            display: block;
            opacity: 1;
            transform: translateY(0);
        }

        .dropdown-user-info {
            padding: 0.75rem 0.85rem;
        }

        .dropdown-user-name {
            font-weight: 800;
            font-size: 0.925rem;
            color: var(--primary-deep);
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        .dropdown-user-username {
            font-size: 0.8rem;
            color: var(--text-muted);
            font-weight: 600;
            margin-top: 0.1rem;
        }

        .dropdown-divider {
            height: 1px;
            background-color: var(--border-color);
            margin: 0.35rem 0;
        }

        .dropdown-item {
            display: flex;
            align-items: center;
            gap: 0.65rem;
            width: 100%;
            padding: 0.65rem 0.85rem;
            font-size: 0.875rem;
            font-weight: 700;
            color: var(--text-main);
            text-decoration: none;
            background: none;
            border: none;
            border-radius: var(--radius-sm);
            cursor: pointer;
            transition: var(--transition-smooth);
            text-align: left;
            font-family: inherit;
        }

        .dropdown-item .lucide {
            width: 16px;
            height: 16px;
            color: var(--text-muted);
            transition: var(--transition-smooth);
        }

        .dropdown-item:hover {
            background-color: var(--primary-light);
            color: var(--primary-text);
        }

        .dropdown-item:hover .lucide {
            color: var(--primary);
        }

        .dropdown-item-logout {
            color: var(--danger-text);
        }

        .dropdown-item-logout .lucide {
            color: var(--danger-text);
        }

        .dropdown-item-logout:hover {
            background-color: var(--danger-light);
            color: var(--danger-text);
        }

        .dropdown-item-logout:hover .lucide {
            color: var(--danger-text);
        }

        .content-body {
            padding: 2.5rem;
            max-width: 1200px;
            width: 100%;
            margin: 0 auto;
            flex: 1;
        }

        /* Toast Notification System */
        .toast-container {
            position: fixed;
            top: 24px;
            right: 24px;
            z-index: 9999;
            display: flex;
            align-items: center;
            gap: 0.875rem;
            background-color: #ffffff;
            border: 1px solid #10b981;
            border-left: 5px solid #10b981;
            border-radius: var(--radius-sm);
            padding: 0.875rem 1.25rem;
            box-shadow: 0 12px 30px -5px rgba(16, 185, 129, 0.2);
            color: #065f46;
            font-weight: 600;
            font-size: 0.9rem;
            animation: toastSlideIn 0.3s cubic-bezier(0.16, 1, 0.3, 1) forwards;
            transition: opacity 0.3s ease, transform 0.3s ease;
        }

        @keyframes toastSlideIn {
            from {
                opacity: 0;
                transform: translateX(60px);
            }
            to {
                opacity: 1;
                transform: translateX(0);
            }
        }

        .toast-container.toast-hiding {
            opacity: 0;
            transform: translateY(-20px);
        }

        .toast-icon {
            width: 26px;
            height: 26px;
            background-color: #d1fae5;
            color: #10b981;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 800;
            font-size: 0.85rem;
            flex-shrink: 0;
        }

        .toast-close {
            background: none;
            border: none;
            color: #9ca3af;
            cursor: pointer;
            font-size: 1.1rem;
            padding: 0 0.25rem;
            line-height: 1;
            transition: color 0.2s ease;
        }

        .toast-close:hover {
            color: var(--text-main);
        }

        /* Buttons UI System */
        .btn {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 0.5rem;
            padding: 0.65rem 1.25rem;
            font-size: 0.9rem;
            font-weight: 700;
            border-radius: var(--radius-sm);
            text-decoration: none;
            border: 1px solid transparent;
            cursor: pointer;
            transition: var(--transition-smooth);
            font-family: inherit;
        }

        .btn .lucide {
            width: 17px;
            height: 17px;
        }

        .btn-primary {
            background: linear-gradient(135deg, #1d4ed8, #2563eb);
            color: #ffffff;
            box-shadow: 0 4px 14px rgba(37, 99, 235, 0.28);
        }

        .btn-primary:hover {
            background: linear-gradient(135deg, #1e40af, #1d4ed8);
            box-shadow: 0 6px 18px rgba(37, 99, 235, 0.35);
        }

        .btn-secondary {
            background-color: #ffffff;
            color: #334155;
            border-color: var(--border-color);
            box-shadow: var(--shadow-sm);
        }

        .btn-secondary:hover {
            background-color: #f8fafc;
            border-color: var(--border-hover);
            color: var(--text-main);
        }

        .btn-success {
            background: linear-gradient(135deg, #10b981, #059669);
            color: #ffffff;
            box-shadow: 0 4px 12px rgba(16, 185, 129, 0.25);
        }

        .btn-success:hover {
            background: linear-gradient(135deg, #059669, #047857);
        }

        .btn-danger {
            background-color: var(--danger-light);
            color: var(--danger-text);
            border-color: #fca5a5;
        }

        .btn-danger:hover {
            background-color: var(--danger);
            color: #ffffff;
            border-color: var(--danger);
        }

        .btn-sm {
            padding: 0.4rem 0.8rem;
            font-size: 0.825rem;
            border-radius: 8px;
        }

        .btn-sm .lucide {
            width: 15px;
            height: 15px;
        }

        /* Card System */
        .card {
            background-color: var(--card-bg);
            border: 1px solid var(--border-color);
            border-radius: var(--radius);
            padding: 1.75rem;
            margin-bottom: 1.75rem;
            box-shadow: var(--shadow-card);
            transition: var(--transition-smooth);
        }

        /* Form Elements System */
        .form-group {
            margin-bottom: 1.35rem;
        }

        .form-label {
            display: block;
            font-size: 0.875rem;
            font-weight: 700;
            color: var(--text-main);
            margin-bottom: 0.5rem;
        }

        .form-control, .form-select {
            width: 100%;
            padding: 0.75rem 1rem;
            background-color: #ffffff;
            border: 1.5px solid var(--border-color);
            border-radius: var(--radius-sm);
            color: var(--text-main);
            font-family: inherit;
            font-size: 0.925rem;
            transition: var(--transition-smooth);
            box-shadow: var(--shadow-sm);
        }

        .form-control:focus, .form-select:focus {
            outline: none;
            border-color: var(--primary);
            box-shadow: 0 0 0 3.5px rgba(37, 99, 235, 0.12);
        }

        textarea.form-control {
            resize: vertical;
            min-height: 100px;
        }

        .error-text {
            color: var(--danger);
            font-size: 0.825rem;
            margin-top: 0.375rem;
            font-weight: 600;
        }

        .badge-subject {
            display: inline-flex;
            align-items: center;
            gap: 0.35rem;
            background-color: var(--primary-light);
            color: var(--primary-text);
            font-size: 0.75rem;
            font-weight: 700;
            padding: 0.3rem 0.75rem;
            border-radius: 999px;
            letter-spacing: 0.02em;
        }

        /* SweetAlert2 Custom Styling */
        .swal2-custom-popup {
            font-family: 'Plus Jakarta Sans', -apple-system, sans-serif !important;
            border-radius: 24px !important;
            padding: 2.25rem 2rem !important;
            background: rgba(255, 255, 255, 0.96) !important;
            backdrop-filter: blur(20px) !important;
            border: 1px solid rgba(226, 232, 240, 0.9) !important;
            box-shadow: 0 25px 50px -12px rgba(30, 58, 138, 0.22) !important;
        }
        .swal2-custom-title {
            color: #1e3a8a !important;
            font-weight: 800 !important;
            font-size: 1.45rem !important;
            letter-spacing: -0.02em !important;
        }
        .swal2-custom-confirm-btn {
            background: linear-gradient(135deg, #ef4444, #dc2626) !important;
            border-radius: 12px !important;
            font-weight: 700 !important;
            font-size: 0.9rem !important;
            padding: 0.75rem 1.6rem !important;
            box-shadow: 0 4px 14px rgba(239, 68, 68, 0.35) !important;
            border: none !important;
        }
        .swal2-custom-confirm-btn:hover {
            background: linear-gradient(135deg, #dc2626, #b91c1c) !important;
        }
        .swal2-custom-cancel-btn {
            background: #64748b !important;
            border-radius: 12px !important;
            font-weight: 700 !important;
            font-size: 0.9rem !important;
            padding: 0.75rem 1.6rem !important;
            border: none !important;
        }
        .swal2-custom-cancel-btn:hover {
            background: #475569 !important;
        }

        @media (max-width: 768px) {
            .sidebar {
                width: 70px;
            }
            .brand-text, .nav-text, .sidebar-footer, .sidebar-section-title {
                display: none;
            }
            .sidebar-brand {
                justify-content: center;
                padding: 1.25rem 0;
            }
            .nav-item {
                justify-content: center;
                padding: 0.875rem;
            }
            .main-wrapper {
                margin-left: 70px;
            }
            .top-navbar {
                padding: 0 1.25rem;
            }
            .content-body {
                padding: 1.25rem;
            }
        }
    </style>
</head>
<body>

    <!-- Custom Notification Toast -->
    @if(session('success'))
        <div id="toast-notification" class="toast-container">
            <div class="toast-icon">✓</div>
            <div>{{ session('success') }}</div>
            <button class="toast-close" onclick="closeToast()">✕</button>
        </div>
    @endif

    <!-- Sidebar Navigation -->
    <aside class="sidebar">
        <div class="sidebar-brand">
            <div class="brand-icon">
                <i data-lucide="graduation-cap" style="width: 22px; height: 22px;"></i>
            </div>
            <span class="brand-text">UTBK Tracker</span>
        </div>

        <nav class="sidebar-nav">
            <a href="{{ route('dashboard') }}" class="nav-item {{ request()->routeIs('dashboard') ? 'active' : '' }}">
                <i data-lucide="layout-dashboard"></i>
                <span class="nav-text">Dashboard</span>
            </a>
            <a href="{{ route('goal.index') }}" class="nav-item {{ request()->routeIs('goal.*') ? 'active' : '' }}">
                <i data-lucide="target"></i>
                <span class="nav-text">Tujuan Impian</span>
            </a>
            <a href="{{ route('materi.index') }}" class="nav-item {{ request()->routeIs('materi.*') ? 'active' : '' }}">
                <i data-lucide="book-open"></i>
                <span class="nav-text">Materi Belajar</span>
            </a>
            <a href="{{ route('todolist.index') }}" class="nav-item {{ request()->routeIs('todolist.*') ? 'active' : '' }}">
                <i data-lucide="check-square"></i>
                <span class="nav-text">To-Do List</span>
            </a>
            <a href="{{ route('utbk.index') }}" class="nav-item {{ request()->routeIs('utbk.*') ? 'active' : '' }}">
                <i data-lucide="line-chart"></i>
                <span class="nav-text">UTBK</span>
            </a>
            <a href="{{ route('tka.index') }}" class="nav-item {{ request()->routeIs('tka.*') ? 'active' : '' }}">
                <i data-lucide="graduation-cap"></i>
                <span class="nav-text">TKA Analisis</span>
            </a>
            <a href="{{ route('study-pet.index') }}" class="nav-item {{ request()->routeIs('study-pet.*') ? 'active' : '' }}">
                <i data-lucide="sparkles"></i>
                <span class="nav-text">Study Pet</span>
            </a>
            <a href="{{ route('profile.edit') }}" class="nav-item {{ request()->routeIs('profile.*') ? 'active' : '' }}">
                <i data-lucide="user"></i>
                <span class="nav-text">Profil Pengguna</span>
            </a>

            @if (Auth::user()?->isAdmin())
            <div class="sidebar-section-title" style="font-size: 0.725rem; font-weight: 800; color: var(--text-muted); text-transform: uppercase; padding: 1rem 1.1rem 0.35rem 1.1rem; letter-spacing: 0.06em;">
                Administrator
            </div>
            <a href="{{ route('admin.dashboard') }}" class="nav-item {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                <i data-lucide="shield"></i>
                <span class="nav-text">Dashboard Admin</span>
            </a>
            <a href="{{ route('admin.activities') }}" class="nav-item {{ request()->routeIs('admin.activities') ? 'active' : '' }}">
                <i data-lucide="history"></i>
                <span class="nav-text">Cek Aktivitas</span>
            </a>
            @endif
        </nav>

        <div class="sidebar-footer">
            Personal UTBK & TKA v1.0
        </div>
    </aside>

    <!-- Main Content Wrapper -->
    <div class="main-wrapper">
        <header class="top-navbar">
            <div class="page-header-title">
                @yield('title', 'Dashboard UTBK Tracker')
            </div>
            <div style="display: flex; align-items: center; gap: 1.25rem;">
                <div style="font-size: 0.85rem; color: var(--text-muted); font-weight: 600; display: flex; align-items: center; gap: 0.35rem;">
                    <i data-lucide="target" style="width: 16px; height: 16px; color: var(--primary);"></i>
                    Target UTBK & TKA
                </div>

                @auth
                <!-- Profile Avatar Dropdown Button -->
                <div class="profile-dropdown-container">
                    <button type="button" class="profile-avatar-btn" id="profileDropdownBtn" aria-expanded="false" title="Menu Profil">
                        @if (Auth::user()->avatar)
                            <img src="{{ asset('storage/' . Auth::user()->avatar) }}" alt="Foto Profil {{ Auth::user()->name }}" class="profile-avatar-img">
                        @else
                            <div class="profile-avatar-placeholder">
                                {{ strtoupper(substr(Auth::user()->username ?? Auth::user()->name, 0, 1)) }}
                            </div>
                        @endif
                    </button>

                    <!-- Dropdown Popup Menu -->
                    <div class="profile-dropdown-menu" id="profileDropdownMenu">
                        <div class="dropdown-user-info">
                            <div class="dropdown-user-name">{{ Auth::user()->name }}</div>
                            <div class="dropdown-user-username">&#64;{{ Auth::user()->username }}</div>
                        </div>
                        <div class="dropdown-divider"></div>
                        <a href="{{ route('profile.edit') }}" class="dropdown-item">
                            <i data-lucide="user"></i>
                            Atur Profil
                        </a>
                        <form action="{{ route('logout') }}" method="POST" style="margin: 0;">
                            @csrf
                            <button type="submit" class="dropdown-item dropdown-item-logout">
                                <i data-lucide="log-out"></i>
                                Logout
                            </button>
                        </form>
                    </div>
                </div>
                @endauth
            </div>
        </header>

        <main class="content-body">
            @yield('content')
        </main>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        function closeToast() {
            const toast = document.getElementById('toast-notification');
            if (toast) {
                toast.classList.add('toast-hiding');
                setTimeout(() => { toast.remove(); }, 300);
            }
        }

        document.addEventListener('DOMContentLoaded', () => {
            // Initialize Lucide Icons
            if (typeof lucide !== 'undefined') {
                lucide.createIcons();
            }

            const toast = document.getElementById('toast-notification');
            if (toast) {
                setTimeout(() => { closeToast(); }, 4000);
            }

            // Profile Dropdown Toggle Logic
            const profileBtn = document.getElementById('profileDropdownBtn');
            const profileMenu = document.getElementById('profileDropdownMenu');

            if (profileBtn && profileMenu) {
                profileBtn.addEventListener('click', (e) => {
                    e.stopPropagation();
                    const isShown = profileMenu.classList.contains('show');
                    if (isShown) {
                        profileMenu.classList.remove('show');
                        profileBtn.classList.remove('active');
                    } else {
                        profileMenu.classList.add('show');
                        profileBtn.classList.add('active');
                    }
                });

                document.addEventListener('click', (e) => {
                    if (!profileMenu.contains(e.target) && !profileBtn.contains(e.target)) {
                        profileMenu.classList.remove('show');
                        profileBtn.classList.remove('active');
                    }
                });
            }

            // Global Confirmation Interceptor for forms with data-confirm or .form-confirm
            document.addEventListener('submit', function (e) {
                const form = e.target;

                if (form.classList.contains('form-confirm') || form.hasAttribute('data-confirm-text') || form.hasAttribute('data-confirm')) {
                    e.preventDefault();

                    const title = form.getAttribute('data-confirm-title') || 'Konfirmasi Hapus Data';
                    const text = form.getAttribute('data-confirm-text') || form.getAttribute('data-confirm') || 'Apakah Anda yakin ingin menghapus data ini? Tindakan ini tidak dapat dibatalkan.';
                    const confirmBtnText = form.getAttribute('data-confirm-btn') || 'Ya, Hapus Data';
                    const cancelBtnText = form.getAttribute('data-cancel-btn') || 'Batal';

                    Swal.fire({
                        title: title,
                        text: text,
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonColor: '#dc2626',
                        cancelButtonColor: '#64748b',
                        confirmButtonText: confirmBtnText,
                        cancelButtonText: cancelBtnText,
                        customClass: {
                            popup: 'swal2-custom-popup',
                            title: 'swal2-custom-title',
                            confirmButton: 'swal2-custom-confirm-btn',
                            cancelButton: 'swal2-custom-cancel-btn'
                        },
                        reverseButtons: true
                    }).then((result) => {
                        if (result.isConfirmed) {
                            form.submit();
                        }
                    });
                }
            }, true);
        });
    </script>
</body>
</html>

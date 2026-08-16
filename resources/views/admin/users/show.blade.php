@extends('layouts.app')

@section('title', 'Detail Pengguna — Admin UTBK Tracker')

@section('content')
<style>
    .user-detail-card {
        background: #ffffff;
        border-radius: var(--radius);
        border: 1px solid var(--border-color);
        padding: 2rem;
        box-shadow: var(--shadow-card);
        margin-bottom: 1.5rem;
    }

    .user-detail-header {
        display: flex;
        align-items: center;
        gap: 1.5rem;
        flex-wrap: wrap;
        padding-bottom: 1.5rem;
        border-bottom: 1px solid var(--border-color);
        margin-bottom: 1.5rem;
    }

    .user-avatar-lg {
        width: 72px;
        height: 72px;
        border-radius: 50%;
        background: linear-gradient(135deg, #1e3a8a, #2563eb);
        color: #ffffff;
        font-weight: 800;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.8rem;
        box-shadow: 0 6px 18px rgba(37, 99, 235, 0.2);
        overflow: hidden;
        flex-shrink: 0;
    }

    .user-avatar-lg img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }

    .user-main-info {
        flex: 1;
    }

    .user-main-name {
        font-size: 1.35rem;
        font-weight: 800;
        color: var(--primary-deep);
        line-height: 1.2;
    }

    .user-main-username {
        font-size: 0.925rem;
        color: var(--text-muted);
        font-weight: 600;
        margin-top: 0.2rem;
    }

    .stats-summary-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(180px, 1fr));
        gap: 1.25rem;
        margin-top: 1.5rem;
    }

    .stat-box {
        background-color: #f8fafc;
        border: 1px solid var(--border-color);
        border-radius: var(--radius-sm);
        padding: 1.25rem;
        display: flex;
        align-items: center;
        gap: 1rem;
    }

    .stat-box-icon {
        width: 44px;
        height: 44px;
        border-radius: 12px;
        background-color: var(--primary-light);
        color: var(--primary);
        display: flex;
        align-items: center;
        justify-content: center;
        flex-shrink: 0;
    }

    .stat-box-val {
        font-size: 1.35rem;
        font-weight: 800;
        color: var(--text-main);
        line-height: 1.1;
    }

    .stat-box-lbl {
        font-size: 0.775rem;
        color: var(--text-muted);
        font-weight: 700;
        margin-top: 0.2rem;
        text-transform: uppercase;
        letter-spacing: 0.03em;
    }

    .info-list-grid {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 1rem;
        margin-top: 1.5rem;
    }

    .info-item {
        padding: 0.85rem 1rem;
        background: #ffffff;
        border: 1px solid var(--border-color);
        border-radius: var(--radius-sm);
    }

    .info-item-lbl {
        font-size: 0.775rem;
        color: var(--text-muted);
        font-weight: 700;
        text-transform: uppercase;
    }

    .info-item-val {
        font-size: 0.95rem;
        font-weight: 700;
        color: var(--text-main);
        margin-top: 0.25rem;
    }

    @media (max-width: 640px) {
        .info-list-grid {
            grid-template-columns: 1fr;
        }
    }
</style>

<div style="margin-bottom: 1.5rem; display: flex; align-items: center; justify-content: space-between; flex-wrap: wrap; gap: 1rem;">
    <a href="{{ route('admin.users.index') }}" class="btn btn-secondary btn-sm" style="display: inline-flex; align-items: center; gap: 0.35rem;">
        <i data-lucide="arrow-left"></i>
        Kembali ke Daftar Pengguna
    </a>

    <div style="display: flex; gap: 0.5rem;">
        <a href="{{ route('admin.users.edit', $user->id) }}" class="btn btn-secondary btn-sm">
            <i data-lucide="edit"></i>
            Edit Pengguna
        </a>
        @if(!$user->isAdmin() && $user->id !== auth()->id())
            <form action="{{ route('admin.users.destroy', $user->id) }}" method="POST" 
                  class="form-confirm" 
                  data-confirm-title="Hapus Akun Pengguna"
                  data-confirm-text="Apakah Anda yakin ingin menghapus akun @'{{ $user->username }}'? Tindakan ini akan menghapus data yang terkait."
                  data-confirm-btn="Ya, Hapus Pengguna">
                @csrf
                @method('DELETE')
                <button type="submit" class="btn btn-danger btn-sm">
                    <i data-lucide="trash-2"></i>
                    Hapus Pengguna
                </button>
            </form>
        @endif
    </div>
</div>

<div class="user-detail-card">
    <div class="user-detail-header">
        <div class="user-avatar-lg">
            @if($user->avatar)
                <img src="{{ asset('storage/' . $user->avatar) }}" alt="{{ $user->name }}">
            @else
                {{ strtoupper(substr($user->username ?? $user->name, 0, 1)) }}
            @endif
        </div>
        <div class="user-main-info">
            <div class="user-main-name">{{ $user->name }}</div>
            <div class="user-main-username">&#64;{{ $user->username }}</div>
        </div>
        <div>
            @if($user->isAdmin())
                <span class="badge-role badge-role-admin" style="padding: 0.4rem 1rem; font-size: 0.85rem;">
                    <i data-lucide="shield" style="width: 15px; height: 15px;"></i>
                    Administrator
                </span>
            @else
                <span class="badge-role badge-role-siswa" style="padding: 0.4rem 1rem; font-size: 0.85rem;">
                    <i data-lucide="user" style="width: 15px; height: 15px;"></i>
                    Siswa PEJUANG UTBK
                </span>
            @endif
        </div>
    </div>

    <!-- Info List Grid -->
    <div class="info-list-grid">
        <div class="info-item">
            <div class="info-item-lbl">Alamat Email</div>
            <div class="info-item-val">{{ $user->email }}</div>
        </div>
        <div class="info-item">
            <div class="info-item-lbl">Tanggal Terdaftar</div>
            <div class="info-item-val">{{ $user->created_at ? $user->created_at->format('d F Y H:i') : '-' }}</div>
        </div>
    </div>

    <!-- Activity & Data Statistics -->
    <div style="margin-top: 2rem;">
        <h3 style="font-size: 1.05rem; font-weight: 800; color: var(--primary-deep); margin-bottom: 0.75rem;">
            Ringkasan Aktivitas Data Pengguna
        </h3>

        <div class="stats-summary-grid">
            <div class="stat-box">
                <div class="stat-box-icon">
                    <i data-lucide="line-chart"></i>
                </div>
                <div>
                    <div class="stat-box-val">{{ $user->utbk_tryouts_count ?? 0 }}</div>
                    <div class="stat-box-lbl">Tryout UTBK</div>
                </div>
            </div>

            <div class="stat-box">
                <div class="stat-box-icon">
                    <i data-lucide="graduation-cap"></i>
                </div>
                <div>
                    <div class="stat-box-val">{{ $user->tka_tryouts_count ?? 0 }}</div>
                    <div class="stat-box-lbl">Tryout TKA</div>
                </div>
            </div>

            <div class="stat-box">
                <div class="stat-box-icon">
                    <i data-lucide="book-open"></i>
                </div>
                <div>
                    <div class="stat-box-val">{{ $user->materis_count ?? 0 }}</div>
                    <div class="stat-box-lbl">Materi Belajar</div>
                </div>
            </div>

            <div class="stat-box">
                <div class="stat-box-icon">
                    <i data-lucide="check-square"></i>
                </div>
                <div>
                    <div class="stat-box-val">{{ $user->todo_tasks_count ?? 0 }}</div>
                    <div class="stat-box-lbl">To-Do Tasks</div>
                </div>
            </div>

            <div class="stat-box">
                <div class="stat-box-icon">
                    <i data-lucide="target"></i>
                </div>
                <div>
                    <div class="stat-box-val">{{ $user->goals_count ?? 0 }}</div>
                    <div class="stat-box-lbl">Tujuan Impian</div>
                </div>
            </div>

            <div class="stat-box">
                <div class="stat-box-icon">
                    <i data-lucide="zap"></i>
                </div>
                <div>
                    <div class="stat-box-val">{{ $user->study_xp_logs_count ?? 0 }}</div>
                    <div class="stat-box-lbl">Log Study XP</div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

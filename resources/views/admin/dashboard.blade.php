@extends('layouts.app')

@section('title', 'Dashboard Administrator — UTBK Tracker')

@section('content')
<style>
    .admin-stats-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
        gap: 1.25rem;
        margin-bottom: 2rem;
    }

    .stat-card {
        background: #ffffff;
        border-radius: var(--radius);
        border: 1px solid var(--border-color);
        padding: 1.5rem;
        box-shadow: var(--shadow-card);
        display: flex;
        align-items: center;
        gap: 1.25rem;
        transition: var(--transition-smooth);
    }

    .stat-card:hover {
        transform: translateY(-2px);
        box-shadow: 0 10px 22px -5px rgba(15, 23, 42, 0.08);
    }

    .stat-icon {
        width: 52px;
        height: 52px;
        border-radius: 14px;
        background: var(--primary-light);
        color: var(--primary-text);
        display: flex;
        align-items: center;
        justify-content: center;
        flex-shrink: 0;
    }

    .stat-value {
        font-size: 1.75rem;
        font-weight: 800;
        color: var(--text-main);
        line-height: 1.1;
    }

    .stat-label {
        font-size: 0.825rem;
        color: var(--text-muted);
        font-weight: 700;
        margin-top: 0.25rem;
        text-transform: uppercase;
        letter-spacing: 0.03em;
    }

    .admin-controls-grid {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 1.5rem;
        margin-bottom: 2rem;
    }

    .control-card {
        background: #ffffff;
        border-radius: var(--radius);
        border: 1px solid var(--border-color);
        padding: 1.75rem;
        box-shadow: var(--shadow-card);
    }

    .control-title {
        font-size: 1.1rem;
        font-weight: 800;
        color: var(--primary-deep);
        margin-bottom: 0.75rem;
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }

    .control-desc {
        font-size: 0.875rem;
        color: var(--text-muted);
        line-height: 1.5;
        margin-bottom: 1.25rem;
    }

    .btn-maintenance {
        background: linear-gradient(135deg, #f59e0b, #d97706);
        color: #ffffff;
        border: none;
        padding: 0.75rem 1.5rem;
        border-radius: var(--radius-sm);
        font-weight: 700;
        font-size: 0.9rem;
        cursor: pointer;
        transition: var(--transition-smooth);
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        box-shadow: 0 4px 12px rgba(245, 158, 11, 0.25);
    }

    .btn-maintenance:hover {
        background: linear-gradient(135deg, #d97706, #b45309);
    }

    .btn-maintenance-active {
        background: linear-gradient(135deg, #ef4444, #dc2626);
        box-shadow: 0 4px 12px rgba(239, 68, 68, 0.3);
    }

    .btn-maintenance-active:hover {
        background: linear-gradient(135deg, #dc2626, #b91c1c);
    }

    .btn-backup {
        background: linear-gradient(135deg, #10b981, #059669);
        color: #ffffff;
        border: none;
        padding: 0.75rem 1.5rem;
        border-radius: var(--radius-sm);
        font-weight: 700;
        font-size: 0.9rem;
        cursor: pointer;
        transition: var(--transition-smooth);
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        box-shadow: 0 4px 12px rgba(16, 185, 129, 0.25);
    }

    .btn-backup:hover {
        background: linear-gradient(135deg, #059669, #047857);
    }

    .table-card {
        background: #ffffff;
        border-radius: var(--radius);
        border: 1px solid var(--border-color);
        padding: 1.75rem;
        box-shadow: var(--shadow-card);
    }

    .activity-table {
        width: 100%;
        border-collapse: collapse;
        margin-top: 1rem;
    }

    .activity-table th, .activity-table td {
        padding: 0.875rem 1rem;
        text-align: left;
        border-bottom: 1px solid var(--border-color);
        font-size: 0.875rem;
    }

    .activity-table th {
        background: #f8fafc;
        color: var(--text-muted);
        font-weight: 800;
        text-transform: uppercase;
        font-size: 0.75rem;
        letter-spacing: 0.05em;
    }

    @media (max-width: 850px) {
        .admin-controls-grid {
            grid-template-columns: 1fr;
        }
    }
</style>

<!-- Top Statistics Grid -->
<div class="admin-stats-grid">
    <a href="{{ route('admin.users.index') }}" class="stat-card" style="text-decoration: none; color: inherit;">
        <div class="stat-icon">
            <i data-lucide="users" style="width: 22px; height: 22px;"></i>
        </div>
        <div style="flex: 1;">
            <div class="stat-value">{{ $totalUsers }}</div>
            <div class="stat-label">Total Pengguna</div>
        </div>
        <i data-lucide="chevron-right" style="width: 18px; height: 18px; color: var(--text-muted);"></i>
    </a>
    <div class="stat-card">
        <div class="stat-icon">
            <i data-lucide="line-chart" style="width: 22px; height: 22px;"></i>
        </div>
        <div>
            <div class="stat-value">{{ $totalUtbk }}</div>
            <div class="stat-label">Tryout UTBK</div>
        </div>
    </div>
    <div class="stat-card">
        <div class="stat-icon">
            <i data-lucide="graduation-cap" style="width: 22px; height: 22px;"></i>
        </div>
        <div>
            <div class="stat-value">{{ $totalTka }}</div>
            <div class="stat-label">Tryout TKA</div>
        </div>
    </div>
    <div class="stat-card">
        <div class="stat-icon">
            <i data-lucide="history" style="width: 22px; height: 22px;"></i>
        </div>
        <div>
            <div class="stat-value">{{ $totalActivities }}</div>
            <div class="stat-label">Log Aktivitas</div>
        </div>
    </div>
</div>

<!-- Control Cards: Maintenance & Backup -->
<div class="admin-controls-grid">
    <!-- Maintenance Card -->
    <div class="control-card">
        <div class="control-title">
            <i data-lucide="wrench" style="width: 18px; height: 18px; color: var(--primary);"></i>
            Pengaturan Mode Maintenance
            <span style="font-size: 0.725rem; padding: 0.25rem 0.6rem; border-radius: 12px; font-weight: 800; background: {{ $isMaintenanceActive ? '#fee2e2' : '#d1fae5' }}; color: {{ $isMaintenanceActive ? '#991b1b' : '#065f46' }}; margin-left: auto;">
                {{ $isMaintenanceActive ? 'AKTIF (Siswa Dibatasi)' : 'NON-AKTIF (Website Normal)' }}
            </span>
        </div>
        <div class="control-desc">
            Aktifkan fitur maintenance ketika terdapat kendala teknis atau perbaikan sistem. Saat aktif, pengguna siswa tidak dapat mengakses aplikasi dan akan melihat halaman pemeliharaan.
        </div>
        <form action="{{ route('admin.maintenance.toggle') }}" method="POST">
            @csrf
            <button type="submit" class="btn-maintenance {{ $isMaintenanceActive ? 'btn-maintenance-active' : '' }}">
                <i data-lucide="power"></i>
                {{ $isMaintenanceActive ? 'Matikan Maintenance Mode' : 'Aktifkan Maintenance Mode' }}
            </button>
        </form>
    </div>

    <!-- Backup Card -->
    <div class="control-card">
        <div class="control-title">
            <i data-lucide="database" style="width: 18px; height: 18px; color: var(--primary);"></i>
            Backup Database System (phpMyAdmin / SQL)
        </div>
        <div class="control-desc">
            Unduh file backup database lengkap berformat <strong>.SQL</strong> yang siap di-import langsung ke phpMyAdmin atau MySQL / SQLite database manager.
        </div>
        <a href="{{ route('admin.backup.download') }}" class="btn-backup">
            <i data-lucide="download"></i>
            Download Backup Database (.sql)
        </a>
    </div>
</div>

<!-- Recent Activities Table -->
<div class="table-card">
    <div style="display: flex; align-items: center; justify-content: space-between;">
        <div style="font-size: 1.1rem; font-weight: 800; color: var(--primary-deep); display: flex; align-items: center; gap: 0.5rem;">
            <i data-lucide="history" style="width: 18px; height: 18px; color: var(--primary);"></i>
            Riwayat Aktivitas Terbaru
        </div>
        <a href="{{ route('admin.activities') }}" style="font-size: 0.85rem; font-weight: 700; color: var(--primary); text-decoration: none; display: flex; align-items: center; gap: 0.25rem;">
            Lihat Semua Aktivitas
            <i data-lucide="arrow-right" style="width: 14px; height: 14px;"></i>
        </a>
    </div>

    <table class="activity-table">
        <thead>
            <tr>
                <th>Waktu</th>
                <th>Pengguna</th>
                <th>Aksi</th>
                <th>Keterangan</th>
                <th>IP Address</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($recentActivities as $act)
                <tr>
                    <td style="color: var(--text-muted); font-weight: 600;">
                        {{ $act->created_at->format('d M Y H:i:s') }}
                    </td>
                    <td style="font-weight: 700; color: var(--text-main);">
                        &#64;{{ $act->username ?? 'Guest' }}
                    </td>
                    <td>
                        <span style="font-weight: 700; color: var(--primary-text);">{{ $act->action }}</span>
                    </td>
                    <td style="color: var(--text-muted);">
                        {{ $act->description ?? '-' }}
                    </td>
                    <td style="font-family: monospace; color: var(--text-muted);">
                        {{ $act->ip_address ?? '127.0.0.1' }}
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="5" style="text-align: center; color: var(--text-muted); padding: 1.5rem;">
                        Belum ada riwayat aktivitas tercatat.
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>
@endsection

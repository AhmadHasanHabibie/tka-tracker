@extends('layouts.app')

@section('title', 'Cek Riwayat Aktivitas — Admin')

@section('content')
<style>
    .card-container {
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

    .pagination-wrapper {
        margin-top: 1.25rem;
        display: flex;
        justify-content: center;
    }
</style>

<div class="card-container">
    <div style="display: flex; align-items: center; justify-content: space-between; margin-bottom: 1rem;">
        <h2 style="font-size: 1.25rem; font-weight: 800; color: var(--primary-deep); display: flex; align-items: center; gap: 0.5rem;">
            <i data-lucide="history" style="width: 20px; height: 20px; color: var(--primary);"></i>
            Seluruh Riwayat Aktivitas Pengguna
        </h2>
        <a href="{{ route('admin.dashboard') }}" class="btn btn-secondary btn-sm">
            <i data-lucide="arrow-left"></i>
            Kembali ke Dashboard Admin
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
            @forelse ($activities as $act)
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

    <div class="pagination-wrapper">
        {{ $activities->links() }}
    </div>
</div>
@endsection

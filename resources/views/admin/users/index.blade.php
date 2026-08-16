@extends('layouts.app')

@section('title', 'Manajemen Pengguna — UTBK Tracker')

@section('content')
<style>
    .users-header {
        display: flex;
        align-items: center;
        justify-content: space-between;
        flex-wrap: wrap;
        gap: 1rem;
        margin-bottom: 1.5rem;
    }

    .users-title-group h1 {
        font-size: 1.5rem;
        font-weight: 800;
        color: var(--primary-deep);
        letter-spacing: -0.02em;
    }

    .users-title-group p {
        font-size: 0.875rem;
        color: var(--text-muted);
        margin-top: 0.25rem;
    }

    .filter-card {
        background: #ffffff;
        border-radius: var(--radius);
        border: 1px solid var(--border-color);
        padding: 1.25rem;
        margin-bottom: 1.5rem;
        box-shadow: var(--shadow-card);
    }

    .filter-form {
        display: flex;
        gap: 1rem;
        flex-wrap: wrap;
        align-items: center;
    }

    .search-input-wrapper {
        flex: 1;
        min-width: 240px;
        position: relative;
    }

    .search-input-wrapper .lucide {
        position: absolute;
        left: 1rem;
        top: 50%;
        transform: translateY(-50%);
        color: var(--text-muted);
    }

    .search-input-wrapper input {
        padding-left: 2.75rem;
    }

    .filter-select {
        width: 180px;
    }

    .user-table-card {
        background: #ffffff;
        border-radius: var(--radius);
        border: 1px solid var(--border-color);
        box-shadow: var(--shadow-card);
        overflow: hidden;
    }

    .users-table {
        width: 100%;
        border-collapse: collapse;
    }

    .users-table th, .users-table td {
        padding: 1rem 1.25rem;
        text-align: left;
        border-bottom: 1px solid var(--border-color);
        font-size: 0.875rem;
    }

    .users-table th {
        background: #f8fafc;
        color: var(--text-muted);
        font-weight: 800;
        text-transform: uppercase;
        font-size: 0.75rem;
        letter-spacing: 0.05em;
    }

    .users-table tbody tr:hover {
        background-color: #f8fafc;
    }

    .user-profile-cell {
        display: flex;
        align-items: center;
        gap: 0.875rem;
    }

    .user-avatar-sm {
        width: 40px;
        height: 40px;
        border-radius: 50%;
        background: linear-gradient(135deg, #1e3a8a, #2563eb);
        color: #ffffff;
        font-weight: 800;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 0.95rem;
        flex-shrink: 0;
        overflow: hidden;
    }

    .user-avatar-sm img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }

    .user-name-info {
        display: flex;
        flex-direction: column;
    }

    .user-display-name {
        font-weight: 700;
        color: var(--text-main);
        line-height: 1.2;
    }

    .user-username-handle {
        font-size: 0.8rem;
        color: var(--text-muted);
        font-weight: 600;
        margin-top: 0.15rem;
    }

    .badge-role {
        display: inline-flex;
        align-items: center;
        gap: 0.35rem;
        padding: 0.3rem 0.75rem;
        border-radius: 999px;
        font-size: 0.75rem;
        font-weight: 700;
        letter-spacing: 0.02em;
    }

    .badge-role-admin {
        background-color: #eff6ff;
        color: #1e40af;
        border: 1px solid #bfdbfe;
    }

    .badge-role-siswa {
        background-color: #ecfdf5;
        color: #047857;
        border: 1px solid #a7f3d0;
    }

    .actions-cell {
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }

    .pagination-wrapper {
        padding: 1.25rem;
        display: flex;
        justify-content: border-between;
        align-items: center;
        border-top: 1px solid var(--border-color);
        background-color: #ffffff;
    }
</style>

<div class="users-header">
    <div class="users-title-group">
        <h1>Manajemen Pengguna</h1>
        <p>Kelola dan pantau seluruh akun pengguna/siswa pada sistem UTBK Tracker.</p>
    </div>
    <div>
        <a href="{{ route('admin.users.create') }}" class="btn btn-primary">
            <i data-lucide="user-plus"></i>
            Tambah Pengguna Baru
        </a>
    </div>
</div>

<!-- Alert Error Global jika ada -->
@if(session('error'))
    <div style="background-color: var(--danger-light); color: var(--danger-text); border: 1px solid #fca5a5; padding: 1rem 1.25rem; border-radius: var(--radius-sm); margin-bottom: 1.5rem; font-weight: 600; display: flex; align-items: center; gap: 0.75rem;">
        <i data-lucide="alert-circle" style="width: 20px; height: 20px;"></i>
        <div>{{ session('error') }}</div>
    </div>
@endif

<!-- Search & Filter Card -->
<div class="filter-card">
    <form action="{{ route('admin.users.index') }}" method="GET" class="filter-form">
        <div class="search-input-wrapper">
            <i data-lucide="search" style="width: 18px; height: 18px;"></i>
            <input type="text" name="q" value="{{ request('q') }}" class="form-control" placeholder="Cari nama, username, atau email...">
        </div>

        <div class="filter-select">
            <select name="role" class="form-select" onchange="this.form.submit()">
                <option value="">Semua Role</option>
                <option value="siswa" {{ request('role') === 'siswa' ? 'selected' : '' }}>Siswa</option>
                <option value="admin" {{ request('role') === 'admin' ? 'selected' : '' }}>Administrator</option>
            </select>
        </div>

        <button type="submit" class="btn btn-secondary">
            <i data-lucide="filter"></i>
            Filter
        </button>

        @if(request('q') || request('role'))
            <a href="{{ route('admin.users.index') }}" class="btn btn-secondary" style="color: var(--danger-text);">
                <i data-lucide="rotate-ccw"></i>
                Reset
            </a>
        @endif
    </form>
</div>

<!-- Users Table Card -->
<div class="user-table-card">
    <div class="table-responsive-wrapper">
        <table class="users-table">
            <thead>
                <tr>
                    <th style="width: 60px;">No</th>
                    <th>Pengguna</th>
                    <th>Email</th>
                    <th>Role</th>
                    <th>Bergabung</th>
                    <th style="width: 200px; text-align: right;">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($users as $index => $user)
                    <tr>
                        <td style="color: var(--text-muted); font-weight: 600;">
                            {{ $users->firstItem() + $index }}
                        </td>
                        <td>
                            <div class="user-profile-cell">
                                <div class="user-avatar-sm">
                                    @if($user->avatar)
                                        <img src="{{ asset('storage/' . $user->avatar) }}" alt="{{ $user->name }}">
                                    @else
                                        {{ strtoupper(substr($user->username ?? $user->name, 0, 1)) }}
                                    @endif
                                </div>
                                <div class="user-name-info">
                                    <div class="user-display-name">{{ $user->name }}</div>
                                    <div class="user-username-handle">&#64;{{ $user->username }}</div>
                                </div>
                            </div>
                        </td>
                        <td style="color: var(--text-body); font-weight: 500;">
                            {{ $user->email }}
                        </td>
                        <td>
                            @if($user->isAdmin())
                                <span class="badge-role badge-role-admin">
                                    <i data-lucide="shield" style="width: 13px; height: 13px;"></i>
                                    Admin
                                </span>
                            @else
                                <span class="badge-role badge-role-siswa">
                                    <i data-lucide="user" style="width: 13px; height: 13px;"></i>
                                    Siswa
                                </span>
                            @endif
                        </td>
                        <td style="color: var(--text-muted); font-weight: 500;">
                            {{ $user->created_at ? $user->created_at->format('d M Y') : '-' }}
                        </td>
                        <td>
                            <div class="actions-cell" style="justify-content: flex-end;">
                                <a href="{{ route('admin.users.show', $user->id) }}" class="btn btn-sm btn-secondary" title="Detail Pengguna">
                                    <i data-lucide="eye"></i>
                                </a>
                                <a href="{{ route('admin.users.edit', $user->id) }}" class="btn btn-sm btn-secondary" title="Edit Pengguna">
                                    <i data-lucide="edit"></i>
                                </a>
                                
                                @if(!$user->isAdmin() && $user->id !== auth()->id())
                                    <form action="{{ route('admin.users.destroy', $user->id) }}" method="POST" 
                                          class="form-confirm" 
                                          data-confirm-title="Hapus Akun Pengguna"
                                          data-confirm-text="Apakah Anda yakin ingin menghapus akun @'{{ $user->username }}' ({{ $user->name }})? Tindakan ini akan menghapus data yang terkait."
                                          data-confirm-btn="Ya, Hapus Pengguna"
                                          style="display: inline;">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-danger" title="Hapus Pengguna">
                                            <i data-lucide="trash-2"></i>
                                        </button>
                                    </form>
                                @endif
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" style="text-align: center; color: var(--text-muted); padding: 3rem 1.5rem;">
                            <div style="display: flex; flex-direction: column; align-items: center; gap: 0.75rem;">
                                <i data-lucide="users-round" style="width: 48px; height: 48px; color: #cbd5e1;"></i>
                                <div style="font-size: 1rem; font-weight: 700; color: var(--text-main);">Tidak ada pengguna ditemukan</div>
                                <div style="font-size: 0.875rem;">Coba sesuaikan kata kunci pencarian atau filter role Anda.</div>
                            </div>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    @if($users->hasPages())
        <div class="pagination-wrapper">
            {{ $users->links() }}
        </div>
    @endif
</div>
@endsection

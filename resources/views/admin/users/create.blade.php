@extends('layouts.app')

@section('title', 'Tambah Pengguna Baru — Admin UTBK Tracker')

@section('content')
<style>
    .form-container-card {
        max-width: 680px;
        margin: 0 auto;
        background: #ffffff;
        border-radius: var(--radius);
        border: 1px solid var(--border-color);
        padding: 2rem;
        box-shadow: var(--shadow-card);
    }

    .form-card-header {
        display: flex;
        align-items: center;
        justify-content: space-between;
        margin-bottom: 1.75rem;
        padding-bottom: 1rem;
        border-bottom: 1px solid var(--border-color);
    }

    .form-card-title {
        font-size: 1.25rem;
        font-weight: 800;
        color: var(--primary-deep);
        display: flex;
        align-items: center;
        gap: 0.65rem;
    }

    .form-grid-2 {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 1.25rem;
    }

    @media (max-width: 640px) {
        .form-grid-2 {
            grid-template-columns: 1fr;
        }
        .form-container-card {
            padding: 1.25rem;
        }
    }
</style>

<div style="margin-bottom: 1.5rem;">
    <a href="{{ route('admin.users.index') }}" class="btn btn-secondary btn-sm" style="display: inline-flex; align-items: center; gap: 0.35rem;">
        <i data-lucide="arrow-left"></i>
        Kembali ke Daftar Pengguna
    </a>
</div>

<div class="form-container-card">
    <div class="form-card-header">
        <div class="form-card-title">
            <i data-lucide="user-plus" style="width: 22px; height: 22px; color: var(--primary);"></i>
            Tambah Akun Siswa / User Baru
        </div>
    </div>

    <form action="{{ route('admin.users.store') }}" method="POST">
        @csrf

        <!-- Nama Lengkap -->
        <div class="form-group">
            <label for="name" class="form-label">Nama Lengkap <span style="color: var(--danger);">*</span></label>
            <input type="text" id="name" name="name" class="form-control @error('name') is-invalid @enderror" 
                   value="{{ old('name') }}" placeholder="Contoh: Ahmad Habibie" required autofocus>
            @error('name')
                <div class="error-text">{{ $message }}</div>
            @enderror
        </div>

        <div class="form-grid-2">
            <!-- Username -->
            <div class="form-group">
                <label for="username" class="form-label">Username <span style="color: var(--danger);">*</span></label>
                <input type="text" id="username" name="username" class="form-control @error('username') is-invalid @enderror" 
                       value="{{ old('username') }}" placeholder="Contoh: ahmad_h" required>
                @error('username')
                    <div class="error-text">{{ $message }}</div>
                @enderror
            </div>

            <!-- Email -->
            <div class="form-group">
                <label for="email" class="form-label">Alamat Email <span style="color: var(--danger);">*</span></label>
                <input type="email" id="email" name="email" class="form-control @error('email') is-invalid @enderror" 
                       value="{{ old('email') }}" placeholder="siswa@example.com" required>
                @error('email')
                    <div class="error-text">{{ $message }}</div>
                @enderror
            </div>
        </div>

        <div class="form-grid-2">
            <!-- Password -->
            <div class="form-group">
                <label for="password" class="form-label">Password <span style="color: var(--danger);">*</span></label>
                <input type="password" id="password" name="password" class="form-control @error('password') is-invalid @enderror" 
                       placeholder="Minimal 8 karakter" required>
                @error('password')
                    <div class="error-text">{{ $message }}</div>
                @enderror
            </div>

            <!-- Konfirmasi Password -->
            <div class="form-group">
                <label for="password_confirmation" class="form-label">Konfirmasi Password <span style="color: var(--danger);">*</span></label>
                <input type="password" id="password_confirmation" name="password_confirmation" class="form-control" 
                       placeholder="Ulangi password" required>
            </div>
        </div>

        <!-- Role Option -->
        <div class="form-group">
            <label for="role" class="form-label">Role Pengguna <span style="color: var(--danger);">*</span></label>
            <select id="role" name="role" class="form-select @error('role') is-invalid @enderror" required>
                <option value="siswa" {{ old('role', 'siswa') === 'siswa' ? 'selected' : '' }}>Siswa (Default)</option>
                <option value="admin" {{ old('role') === 'admin' ? 'selected' : '' }}>Administrator</option>
            </select>
            <small style="color: var(--text-muted); font-size: 0.8rem; margin-top: 0.25rem; display: block;">
                Secara default, akun baru dikategorikan sebagai akun Siswa.
            </small>
            @error('role')
                <div class="error-text">{{ $message }}</div>
            @enderror
        </div>

        <div style="display: flex; gap: 1rem; justify-content: flex-end; margin-top: 2rem;">
            <a href="{{ route('admin.users.index') }}" class="btn btn-secondary">
                Batal
            </a>
            <button type="submit" class="btn btn-primary">
                <i data-lucide="check"></i>
                Simpan Akun Baru
            </button>
        </div>
    </form>
</div>
@endsection

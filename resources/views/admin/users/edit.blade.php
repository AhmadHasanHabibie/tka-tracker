@extends('layouts.app')

@section('title', 'Edit Pengguna — Admin UTBK Tracker')

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

    .password-help-box {
        background-color: #f8fafc;
        border: 1px dashed var(--border-color);
        padding: 0.75rem 1rem;
        border-radius: var(--radius-sm);
        margin-bottom: 1rem;
        font-size: 0.825rem;
        color: var(--text-muted);
        display: flex;
        align-items: center;
        gap: 0.5rem;
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
            <i data-lucide="user-cog" style="width: 22px; height: 22px; color: var(--primary);"></i>
            Edit Akun Pengguna: {{ $user->name }}
        </div>
    </div>

    <form action="{{ route('admin.users.update', $user->id) }}" method="POST">
        @csrf
        @method('PUT')

        <!-- Nama Lengkap -->
        <div class="form-group">
            <label for="name" class="form-label">Nama Lengkap <span style="color: var(--danger);">*</span></label>
            <input type="text" id="name" name="name" class="form-control @error('name') is-invalid @enderror" 
                   value="{{ old('name', $user->name) }}" placeholder="Nama Lengkap" required>
            @error('name')
                <div class="error-text">{{ $message }}</div>
            @enderror
        </div>

        <div class="form-grid-2">
            <!-- Username -->
            <div class="form-group">
                <label for="username" class="form-label">Username <span style="color: var(--danger);">*</span></label>
                <input type="text" id="username" name="username" class="form-control @error('username') is-invalid @enderror" 
                       value="{{ old('username', $user->username) }}" placeholder="Username" required>
                @error('username')
                    <div class="error-text">{{ $message }}</div>
                @enderror
            </div>

            <!-- Email -->
            <div class="form-group">
                <label for="email" class="form-label">Alamat Email <span style="color: var(--danger);">*</span></label>
                <input type="email" id="email" name="email" class="form-control @error('email') is-invalid @enderror" 
                       value="{{ old('email', $user->email) }}" placeholder="Email" required>
                @error('email')
                    <div class="error-text">{{ $message }}</div>
                @enderror
            </div>
        </div>

        <div class="password-help-box">
            <i data-lucide="info" style="width: 18px; height: 18px; color: var(--primary); flex-shrink: 0;"></i>
            <span>Kosongkan kolom password jika tidak ingin mengubah password pengguna.</span>
        </div>

        <div class="form-grid-2">
            <!-- Password Baru -->
            <div class="form-group">
                <label for="password" class="form-label">Password Baru (Opsional)</label>
                <input type="password" id="password" name="password" class="form-control @error('password') is-invalid @enderror" 
                       placeholder="Minimal 8 karakter">
                @error('password')
                    <div class="error-text">{{ $message }}</div>
                @enderror
            </div>

            <!-- Konfirmasi Password Baru -->
            <div class="form-group">
                <label for="password_confirmation" class="form-label">Konfirmasi Password Baru</label>
                <input type="password" id="password_confirmation" name="password_confirmation" class="form-control" 
                       placeholder="Ulangi password baru">
            </div>
        </div>

        <!-- Role Option -->
        <div class="form-group">
            <label for="role" class="form-label">Role Pengguna <span style="color: var(--danger);">*</span></label>
            @if($user->email === 'admin@utbktracker.local')
                <input type="hidden" name="role" value="admin">
                <input type="text" class="form-control" value="Administrator Utama (Permanen)" disabled readonly>
            @else
                <select id="role" name="role" class="form-select @error('role') is-invalid @enderror" required>
                    <option value="siswa" {{ old('role', $user->role) === 'siswa' ? 'selected' : '' }}>Siswa</option>
                    <option value="admin" {{ old('role', $user->role) === 'admin' ? 'selected' : '' }}>Administrator</option>
                </select>
            @endif
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
                Simpan Perubahan
            </button>
        </div>
    </form>
</div>
@endsection

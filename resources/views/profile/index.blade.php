@extends('layouts.app')

@section('title', 'Profil Pengguna — UTBK Tracker')

@section('content')
<style>
    .profile-grid {
        display: grid;
        grid-template-columns: 320px 1fr;
        gap: 1.75rem;
    }

    .profile-card {
        background: #ffffff;
        border-radius: var(--radius);
        border: 1px solid var(--border-color);
        padding: 1.75rem;
        box-shadow: var(--shadow-card);
        margin-bottom: 1.5rem;
    }

    .profile-card-title {
        font-size: 1.15rem;
        font-weight: 800;
        color: var(--primary-deep);
        margin-bottom: 1.25rem;
        display: flex;
        align-items: center;
        gap: 0.5rem;
        padding-bottom: 0.75rem;
        border-bottom: 1px solid var(--border-color);
    }

    .avatar-preview-container {
        text-align: center;
    }

    .avatar-wrapper {
        width: 120px;
        height: 120px;
        border-radius: 50%;
        margin: 0 auto 1.25rem auto;
        overflow: hidden;
        border: 4px solid var(--primary-light);
        box-shadow: 0 8px 20px rgba(37, 99, 235, 0.15);
        position: relative;
        background: linear-gradient(135deg, #1e3a8a, #2563eb);
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .avatar-wrapper img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }

    .avatar-initials {
        font-size: 2.75rem;
        font-weight: 800;
        color: #ffffff;
        text-transform: uppercase;
    }

    .form-group {
        margin-bottom: 1.25rem;
    }

    .form-label {
        display: block;
        font-size: 0.875rem;
        font-weight: 700;
        color: var(--text-main);
        margin-bottom: 0.5rem;
    }

    .form-control {
        width: 100%;
        padding: 0.75rem 1rem;
        border-radius: var(--radius-sm);
        border: 1.5px solid var(--border-color);
        font-family: inherit;
        font-size: 0.925rem;
        color: var(--text-main);
        outline: none;
        transition: var(--transition-smooth);
        box-shadow: var(--shadow-sm);
    }

    .form-control:focus {
        border-color: var(--primary);
        box-shadow: 0 0 0 3.5px rgba(37, 99, 235, 0.12);
    }

    .btn-primary-custom {
        background: linear-gradient(135deg, #1d4ed8, #2563eb);
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
        box-shadow: 0 4px 14px rgba(37, 99, 235, 0.28);
    }

    .btn-primary-custom:hover {
        background: linear-gradient(135deg, #1e40af, #1d4ed8);
        box-shadow: 0 6px 18px rgba(37, 99, 235, 0.35);
    }

    .error-feedback {
        font-size: 0.8rem;
        color: var(--danger);
        margin-top: 0.35rem;
        font-weight: 600;
    }

    @media (max-width: 900px) {
        .profile-grid {
            grid-template-columns: 1fr;
        }
    }
</style>

<div class="profile-grid">
    <!-- Column 1: Avatar Management -->
    <div>
        <div class="profile-card">
            <div class="profile-card-title">
                <i data-lucide="camera" style="width: 18px; height: 18px; color: var(--primary);"></i>
                Foto Profil
            </div>

            <form action="{{ route('profile.update-avatar') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="avatar-preview-container">
                    <div class="avatar-wrapper" id="avatarPreviewWrapper">
                        @if ($user->avatar)
                            <img src="{{ asset('storage/' . $user->avatar) }}" alt="Avatar {{ $user->name }}" id="avatarImg">
                        @else
                            <span class="avatar-initials" id="avatarInitials">{{ strtoupper(substr($user->username ?? $user->name, 0, 1)) }}</span>
                        @endif
                    </div>

                    <div style="font-weight: 800; font-size: 1.1rem; color: var(--text-main); margin-bottom: 0.25rem;">
                        {{ $user->name }}
                    </div>
                    <div style="font-size: 0.85rem; color: var(--text-muted); font-weight: 600; margin-bottom: 1.25rem;">
                        &#64;{{ $user->username }}
                    </div>

                    <div class="form-group" style="text-align: left;">
                        <label for="avatar" class="form-label">Pilih Foto Baru</label>
                        <input type="file" name="avatar" id="avatarInput" accept="image/*" class="form-control" style="padding: 0.5rem;">
                        @error('avatar')
                            <div class="error-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <button type="submit" class="btn-primary-custom" style="width: 100%; justify-content: center;">
                        <i data-lucide="save"></i>
                        Simpan Foto
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Column 2: User Info & Password Forms -->
    <div>
        <!-- Account Info Form -->
        <div class="profile-card">
            <div class="profile-card-title">
                <i data-lucide="user" style="width: 18px; height: 18px; color: var(--primary);"></i>
                Informasi Akun
            </div>

            <form action="{{ route('profile.update-info') }}" method="POST">
                @csrf
                @method('PUT')

                <div class="form-group">
                    <label for="name" class="form-label">Nama Lengkap</label>
                    <input type="text" name="name" id="name" class="form-control" value="{{ old('name', $user->name) }}" required>
                    @error('name')
                        <div class="error-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="username" class="form-label">Username Baru</label>
                    <input type="text" name="username" id="username" class="form-control" value="{{ old('username', $user->username) }}" required>
                    <div style="font-size: 0.775rem; color: var(--text-muted); margin-top: 0.35rem;">
                        Username digunakan untuk login ke dalam website.
                    </div>
                    @error('username')
                        <div class="error-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <button type="submit" class="btn-primary-custom">
                    <i data-lucide="save"></i>
                    Simpan Perubahan
                </button>
            </form>
        </div>

        <!-- Change Password Form -->
        <div class="profile-card">
            <div class="profile-card-title">
                <i data-lucide="key-round" style="width: 18px; height: 18px; color: var(--primary);"></i>
                Ganti Password
            </div>

            <form action="{{ route('profile.update-password') }}" method="POST">
                @csrf
                @method('PUT')

                <div class="form-group">
                    <label for="current_password" class="form-label">Password Saat Ini</label>
                    <input type="password" name="current_password" id="current_password" class="form-control" placeholder="••••••••" required>
                    @error('current_password')
                        <div class="error-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="password" class="form-label">Password Baru</label>
                    <input type="password" name="password" id="password" class="form-control" placeholder="Minimal 6 karakter" required>
                    @error('password')
                        <div class="error-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="password_confirmation" class="form-label">Konfirmasi Password Baru</label>
                    <input type="password" name="password_confirmation" id="password_confirmation" class="form-control" placeholder="Ulangi password baru" required>
                </div>

                <button type="submit" class="btn-primary-custom">
                    <i data-lucide="key-round"></i>
                    Ubah Password
                </button>
            </form>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', () => {
        const avatarInput = document.getElementById('avatarInput');
        const avatarPreviewWrapper = document.getElementById('avatarPreviewWrapper');

        if (avatarInput && avatarPreviewWrapper) {
            avatarInput.addEventListener('change', (e) => {
                const file = e.target.files[0];
                if (file) {
                    const reader = new FileReader();
                    reader.onload = (evt) => {
                        avatarPreviewWrapper.innerHTML = `<img src="${evt.target.result}" alt="Preview Avatar">`;
                    };
                    reader.readAsDataURL(file);
                }
            });
        }
    });
</script>
@endsection

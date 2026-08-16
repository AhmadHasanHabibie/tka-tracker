@extends('layouts.app')

@section('title', 'Tujuan & Target PTN Impian')

@section('content')
<style>
    .goal-grid {
        display: grid;
        grid-template-columns: 1fr 360px;
        gap: 2rem;
        align-items: start;
    }

    .goal-preview-card {
        background: linear-gradient(135deg, #1e3a8a, #2563eb);
        border-radius: var(--radius);
        padding: 2rem;
        color: #ffffff;
        text-align: center;
        box-shadow: var(--shadow-card);
    }

    .goal-preview-img-box {
        width: 130px;
        height: 130px;
        border-radius: 18px;
        overflow: hidden;
        margin: 0 auto 1.25rem auto;
        border: 4px solid rgba(255, 255, 255, 0.4);
        box-shadow: 0 10px 25px rgba(0, 0, 0, 0.2);
        background-color: #ffffff;
    }

    .goal-preview-img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }

    .goal-preview-univ {
        font-size: 1.3rem;
        font-weight: 800;
        margin-bottom: 0.35rem;
    }

    .goal-preview-prodi {
        font-size: 0.95rem;
        opacity: 0.9;
        font-weight: 600;
        margin-bottom: 1rem;
    }

    .goal-preview-score {
        display: inline-flex;
        align-items: center;
        gap: 0.35rem;
        background: rgba(255, 255, 255, 0.2);
        backdrop-filter: blur(8px);
        padding: 0.4rem 1rem;
        border-radius: 999px;
        font-size: 0.825rem;
        font-weight: 700;
    }

    @media (max-width: 900px) {
        .goal-grid {
            grid-template-columns: 1fr;
        }
    }
</style>

<div class="goal-grid">
    <!-- Form Edit Tujuan -->
    <div class="card">
        <h3 style="font-size: 1.25rem; font-weight: 800; margin-bottom: 1.5rem; color: var(--primary-deep); display: flex; align-items: center; gap: 0.5rem;">
            <i data-lucide="target" style="width: 22px; height: 22px; color: var(--primary);"></i>
            Curahkan Target Impian Kamu
        </h3>

        <form action="{{ route('goal.store') }}" method="POST" enctype="multipart/form-data">
            @csrf

            <div class="form-group">
                <label for="university_name" class="form-label">Target Universitas / PTN Impian <span style="color: var(--danger);">*</span></label>
                <input type="text" name="university_name" id="university_name" class="form-control" placeholder="Misal: Universitas Indonesia, ITB, UGM..." value="{{ old('university_name', $goal->university_name ?? '') }}" required>
                @error('university_name')
                    <div class="error-text">{{ $message }}</div>
                @enderror
            </div>

            <div class="form-group">
                <label for="study_program" class="form-label">Target Program Studi (Prodi) <span style="color: var(--danger);">*</span></label>
                <input type="text" name="study_program" id="study_program" class="form-control" placeholder="Misal: Teknik Informatika, Kedokteran, Manajemen..." value="{{ old('study_program', $goal->study_program ?? '') }}" required>
                @error('study_program')
                    <div class="error-text">{{ $message }}</div>
                @enderror
            </div>

            <div class="form-group">
                <label for="target_score" class="form-label">Target Skor UTBK & Kata Motivasi <span style="color: var(--text-muted); font-size: 0.8rem; font-weight: 500;">(opsional)</span></label>
                <input type="text" name="target_score" id="target_score" class="form-control" placeholder="Misal: Target Skor: 750+ | Pantang menyerah demi masa depan!" value="{{ old('target_score', $goal->target_score ?? '') }}">
                @error('target_score')
                    <div class="error-text">{{ $message }}</div>
                @enderror
            </div>

            <div class="form-group">
                <label for="photo" class="form-label">Foto / Banner Impian Kamu <span style="color: var(--text-muted); font-size: 0.8rem; font-weight: 500;">(opsional)</span></label>
                <input type="file" name="photo" id="photo" class="form-control" accept="image/*">
                <div style="font-size: 0.8rem; color: var(--text-muted); margin-top: 0.35rem;">
                    Upload foto gedung kampus impian, logo PTN, atau foto motivasimu (Format: JPG, PNG, WEBP, Maks. 4MB).
                </div>
                @error('photo')
                    <div class="error-text">{{ $message }}</div>
                @enderror
            </div>

            <div style="display: flex; justify-content: flex-end; gap: 0.75rem; margin-top: 1.75rem;">
                <button type="submit" class="btn btn-primary" style="padding: 0.75rem 2rem;">
                    <i data-lucide="save"></i>
                    Simpan Impian Kamu
                </button>
            </div>
        </form>
    </div>

    <!-- Live Preview Card -->
    <div>
        <div style="font-weight: 800; font-size: 0.95rem; color: var(--primary-deep); margin-bottom: 0.875rem;">
            Tampilan Banner Dashboard:
        </div>
        <div class="goal-preview-card">
            <div class="goal-preview-img-box">
                <img src="{{ $goal ? $goal->photo_url : 'https://images.unsplash.com/photo-1541339907198-e08756dedf3f?auto=format&fit=crop&w=1200&q=80' }}" alt="Foto Target" class="goal-preview-img">
            </div>
            <div class="goal-preview-univ">{{ $goal->university_name ?? 'Universitas Impian' }}</div>
            <div class="goal-preview-prodi">{{ $goal->study_program ?? 'Program Studi Impian' }}</div>
            <div class="goal-preview-score">
                <i data-lucide="sparkles" style="width: 14px; height: 14px;"></i>
                {{ $goal->target_score ?? 'Target Skor: 700+' }}
            </div>
        </div>
    </div>
</div>
@endsection

@extends('layouts.app')

@section('title', 'Tambah Hasil Tryout UTBK')

@section('content')
<div class="card" style="max-width: 720px; margin: 0 auto;">
    <h2 style="font-size: 1.35rem; font-weight: 800; margin-bottom: 0.25rem; color: var(--primary-deep); display: flex; align-items: center; gap: 0.5rem;">
        <i data-lucide="plus-circle" style="width: 22px; height: 22px; color: var(--primary);"></i>
        Tambah Hasil Tryout UTBK
    </h2>
    <p style="font-size: 0.875rem; color: var(--text-muted); margin-bottom: 1.75rem;">
        Masukkan nilai asli yang kamu dapatkan dari platform tryout (Zenius, Ruangguru, Pahamify, dll).
    </p>

    <form action="{{ route('utbk.store') }}" method="POST">
        @csrf

        <!-- Section 1: Informasi Tryout -->
        <div style="margin-bottom: 2rem;">
            <h3 style="font-size: 1.05rem; font-weight: 800; color: var(--primary-deep); margin-bottom: 1rem; border-bottom: 2px solid var(--primary-light); padding-bottom: 0.4rem; display: flex; align-items: center; gap: 0.4rem;">
                <i data-lucide="info" style="width: 18px; height: 18px; color: var(--primary);"></i>
                Informasi Tryout
            </h3>

            <div class="form-group">
                <label for="name" class="form-label">Nama Tryout <span style="color: var(--danger);">*</span></label>
                <input type="text" name="name" id="name" class="form-control" placeholder="Contoh: Tryout Zenius #1" value="{{ old('name') }}" required>
                @error('name')
                    <div class="error-text">{{ $message }}</div>
                @enderror
            </div>

            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1rem;">
                <div class="form-group">
                    <label for="date" class="form-label">Tanggal Pelaksanaan <span style="color: var(--danger);">*</span></label>
                    <input type="date" name="date" id="date" class="form-control" value="{{ old('date', date('Y-m-d')) }}" required>
                    @error('date')
                        <div class="error-text">{{ $message }}</div>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="overall_score" class="form-label">Nilai Keseluruhan <span style="color: var(--danger);">*</span></label>
                    <input type="number" step="0.01" name="overall_score" id="overall_score" class="form-control" placeholder="Contoh: 614.57" value="{{ old('overall_score') }}" min="0" max="1000" required>
                    @error('overall_score')
                        <div class="error-text">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            <div class="form-group">
                <label for="notes" class="form-label">Catatan <span style="color: var(--text-muted); font-size: 0.8rem; font-weight: 500;">(opsional)</span></label>
                <textarea name="notes" id="notes" class="form-control" placeholder="Catatan evaluasi atau kendala saat pengerjaan tryout...">{{ old('notes') }}</textarea>
                @error('notes')
                    <div class="error-text">{{ $message }}</div>
                @enderror
            </div>
        </div>

        <!-- Section 2: Nilai Subtes -->
        <div style="margin-bottom: 2rem;">
            <h3 style="font-size: 1.05rem; font-weight: 800; color: var(--primary-deep); margin-bottom: 1rem; border-bottom: 2px solid var(--primary-light); padding-bottom: 0.4rem; display: flex; align-items: center; gap: 0.4rem;">
                <i data-lucide="bar-chart-2" style="width: 18px; height: 18px; color: var(--primary);"></i>
                Nilai 7 Subtes UTBK
            </h3>
            <p style="font-size: 0.825rem; color: var(--text-muted); margin-bottom: 1.25rem;">
                Seluruh 7 nilai subtes wajib diisi sesuai hasil resmi tryout kamu.
            </p>

            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1.25rem;">
                @foreach($subtests as $key => $label)
                    <div class="form-group" style="margin-bottom: 0.5rem;">
                        <label for="subtest_{{ $key }}" class="form-label">{{ $label }} <span style="color: var(--danger);">*</span></label>
                        <input type="number" step="0.01" name="subtest_scores[{{ $key }}]" id="subtest_{{ $key }}" class="form-control" placeholder="e.g. 500" value="{{ old('subtest_scores.' . $key) }}" min="0" max="1000" required>
                        @error('subtest_scores.' . $key)
                            <div class="error-text">{{ $message }}</div>
                        @enderror
                    </div>
                @endforeach
            </div>
        </div>

        <div style="display: flex; justify-content: flex-end; gap: 0.75rem; margin-top: 2rem;">
            <a href="{{ route('utbk.index') }}" class="btn btn-secondary">
                <i data-lucide="arrow-left"></i>
                Batal
            </a>
            <button type="submit" class="btn btn-primary" style="padding: 0.75rem 2rem;">
                <i data-lucide="save"></i>
                Simpan Hasil Tryout
            </button>
        </div>
    </form>
</div>
@endsection

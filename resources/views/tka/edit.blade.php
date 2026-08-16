@extends('layouts.app')

@section('title', 'Edit Hasil TKA')

@section('content')
<div class="card" style="max-width: 720px; margin: 0 auto;">
    <h2 style="font-size: 1.35rem; font-weight: 800; margin-bottom: 0.25rem; color: var(--primary-deep); display: flex; align-items: center; gap: 0.5rem;">
        <i data-lucide="pencil" style="width: 22px; height: 22px; color: var(--primary);"></i>
        Edit Hasil TKA
    </h2>
    <p style="font-size: 0.875rem; color: var(--text-muted); margin-bottom: 1.75rem;">
        Ubah nama tryout, tanggal, nilai mata pelajaran wajib, atau pilihan.
    </p>

    <form action="{{ route('tka.update', $tkaTryout) }}" method="POST">
        @csrf
        @method('PUT')

        <!-- Section 1: Informasi Tryout -->
        <div style="margin-bottom: 2rem;">
            <h3 style="font-size: 1.05rem; font-weight: 800; color: var(--primary-deep); margin-bottom: 1rem; border-bottom: 2px solid var(--primary-light); padding-bottom: 0.4rem; display: flex; align-items: center; gap: 0.4rem;">
                <i data-lucide="info" style="width: 18px; height: 18px; color: var(--primary);"></i>
                Informasi Tryout
            </h3>

            <div class="form-group">
                <label for="name" class="form-label">Nama Tryout <span style="color: var(--danger);">*</span></label>
                <input type="text" name="name" id="name" class="form-control" value="{{ old('name', $tkaTryout->name) }}" required>
                @error('name')
                    <div class="error-text">{{ $message }}</div>
                @enderror
            </div>

            <div class="form-group">
                <label for="date" class="form-label">Tanggal Pelaksanaan <span style="color: var(--danger);">*</span></label>
                <input type="date" name="date" id="date" class="form-control" value="{{ old('date', $tkaTryout->date ? $tkaTryout->date->format('Y-m-d') : '') }}" required>
                @error('date')
                    <div class="error-text">{{ $message }}</div>
                @enderror
            </div>

            <div class="form-group">
                <label for="notes" class="form-label">Catatan <span style="color: var(--text-muted); font-size: 0.8rem; font-weight: 500;">(opsional)</span></label>
                <textarea name="notes" id="notes" class="form-control">{{ old('notes', $tkaTryout->notes) }}</textarea>
                @error('notes')
                    <div class="error-text">{{ $message }}</div>
                @enderror
            </div>
        </div>

        <!-- Section 2: Mata Pelajaran Wajib -->
        <div style="margin-bottom: 2rem;">
            <h3 style="font-size: 1.05rem; font-weight: 800; color: var(--primary-deep); margin-bottom: 1rem; border-bottom: 2px solid var(--primary-light); padding-bottom: 0.4rem; display: flex; align-items: center; gap: 0.4rem;">
                <i data-lucide="book-open" style="width: 18px; height: 18px; color: var(--primary);"></i>
                Mata Pelajaran Wajib
            </h3>

            <div style="display: grid; grid-template-columns: 1fr 1fr 1fr; gap: 1rem;">
                @foreach($mandatory as $mSubject)
                    @php
                        $mScoreObj = $tkaTryout->subjectScores->firstWhere('subject_name', $mSubject->name);
                        $currentScore = $mScoreObj ? $mScoreObj->score : '';
                    @endphp
                    <div class="form-group" style="margin-bottom: 0.5rem;">
                        <label for="mandatory_{{ $mSubject->id }}" class="form-label">{{ $mSubject->name }} <span style="color: var(--danger);">*</span></label>
                        <input type="number" step="0.01" name="mandatory_scores[{{ $mSubject->id }}]" id="mandatory_{{ $mSubject->id }}" class="form-control" value="{{ old('mandatory_scores.' . $mSubject->id, $currentScore) }}" min="0" max="100" required>
                        @error('mandatory_scores.' . $mSubject->id)
                            <div class="error-text">{{ $message }}</div>
                        @enderror
                    </div>
                @endforeach
            </div>
        </div>

        <!-- Section 3: Mata Pelajaran Pilihan -->
        <div style="margin-bottom: 2rem;">
            <h3 style="font-size: 1.05rem; font-weight: 800; color: var(--primary-deep); margin-bottom: 1rem; border-bottom: 2px solid var(--primary-light); padding-bottom: 0.4rem; display: flex; align-items: center; gap: 0.4rem;">
                <i data-lucide="target" style="width: 18px; height: 18px; color: var(--primary);"></i>
                Mata Pelajaran Pilihan
            </h3>

            <!-- Pilihan 1 -->
            <div style="display: grid; grid-template-columns: 2fr 1fr; gap: 1rem; margin-bottom: 1.25rem; background-color: #f8fafc; padding: 1.25rem; border-radius: 12px; border: 1px solid var(--border-color);">
                <div class="form-group" style="margin-bottom: 0;">
                    <label for="choice_1_id" class="form-label">Mata Pelajaran Pilihan 1 <span style="color: var(--danger);">*</span></label>
                    <select name="choice_1_id" id="choice_1_id" class="form-select" required>
                        <option value="">[ Pilih Mata Pelajaran ]</option>
                        @foreach($choice as $cSubject)
                            <option value="{{ $cSubject->id }}" {{ old('choice_1_id', $choice1 ? $choice1->tka_subject_id : '') == $cSubject->id ? 'selected' : '' }}>
                                {{ $cSubject->name }}
                            </option>
                        @endforeach
                    </select>
                    @error('choice_1_id')
                        <div class="error-text">{{ $message }}</div>
                    @enderror
                </div>

                <div class="form-group" style="margin-bottom: 0;">
                    <label for="choice_1_score" class="form-label">Nilai <span style="color: var(--danger);">*</span></label>
                    <input type="number" step="0.01" name="choice_1_score" id="choice_1_score" class="form-control" value="{{ old('choice_1_score', $choice1 ? $choice1->score : '') }}" min="0" max="100" required>
                    @error('choice_1_score')
                        <div class="error-text">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            <!-- Pilihan 2 -->
            <div style="display: grid; grid-template-columns: 2fr 1fr; gap: 1rem; background-color: #f8fafc; padding: 1.25rem; border-radius: 12px; border: 1px solid var(--border-color);">
                <div class="form-group" style="margin-bottom: 0;">
                    <label for="choice_2_id" class="form-label">Mata Pelajaran Pilihan 2 <span style="color: var(--danger);">*</span></label>
                    <select name="choice_2_id" id="choice_2_id" class="form-select" required>
                        <option value="">[ Pilih Mata Pelajaran ]</option>
                        @foreach($choice as $cSubject)
                            <option value="{{ $cSubject->id }}" {{ old('choice_2_id', $choice2 ? $choice2->tka_subject_id : '') == $cSubject->id ? 'selected' : '' }}>
                                {{ $cSubject->name }}
                            </option>
                        @endforeach
                    </select>
                    @error('choice_2_id')
                        <div class="error-text">{{ $message }}</div>
                    @enderror
                </div>

                <div class="form-group" style="margin-bottom: 0;">
                    <label for="choice_2_score" class="form-label">Nilai <span style="color: var(--danger);">*</span></label>
                    <input type="number" step="0.01" name="choice_2_score" id="choice_2_score" class="form-control" value="{{ old('choice_2_score', $choice2 ? $choice2->score : '') }}" min="0" max="100" required>
                    @error('choice_2_score')
                        <div class="error-text">{{ $message }}</div>
                    @enderror
                </div>
            </div>
        </div>

        <div style="display: flex; justify-content: flex-end; gap: 0.75rem; margin-top: 2rem;">
            <a href="{{ route('tka.index') }}" class="btn btn-secondary">
                <i data-lucide="arrow-left"></i>
                Batal
            </a>
            <button type="submit" class="btn btn-primary" style="padding: 0.75rem 2rem;">
                <i data-lucide="save"></i>
                Simpan Perubahan
            </button>
        </div>
    </form>
</div>
@endsection

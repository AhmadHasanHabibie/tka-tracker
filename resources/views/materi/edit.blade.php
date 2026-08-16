@extends('layouts.app')

@section('title', 'Edit Materi Belajar')

@section('content')
<div class="card" style="max-width: 600px; margin: 0 auto;">
    <h3 style="font-size: 1.25rem; font-weight: 800; margin-bottom: 1.5rem; color: var(--primary-deep); display: flex; align-items: center; gap: 0.5rem;">
        <i data-lucide="pencil" style="width: 20px; height: 20px; color: var(--primary);"></i>
        Edit Materi Belajar
    </h3>

    <form action="{{ route('materi.update', $materi) }}" method="POST">
        @csrf
        @method('PUT')

        <div class="form-group">
            <label for="subject_name" class="form-label">Mata Pelajaran <span style="color: var(--danger);">*</span></label>
            <input type="text" name="subject_name" id="subject_name" class="form-control" list="subject_list" value="{{ old('subject_name', $materi->subject->name) }}" required autocomplete="off">
            <datalist id="subject_list">
                @foreach($subjects as $subject)
                    <option value="{{ $subject->name }}">
                @endforeach
            </datalist>
            @error('subject_name')
                <div class="error-text">{{ $message }}</div>
            @enderror
        </div>

        <div class="form-group">
            <label for="title" class="form-label">Nama Materi <span style="color: var(--danger);">*</span></label>
            <input type="text" name="title" id="title" class="form-control" value="{{ old('title', $materi->title) }}" required>
            @error('title')
                <div class="error-text">{{ $message }}</div>
            @enderror
        </div>

        <div class="form-group">
            <label for="description" class="form-label">Catatan / Deskripsi <span style="color: var(--text-muted); font-size: 0.8rem; font-weight: 500;">(opsional)</span></label>
            <textarea name="description" id="description" class="form-control">{{ old('description', $materi->description) }}</textarea>
            @error('description')
                <div class="error-text">{{ $message }}</div>
            @enderror
        </div>

        <div style="display: flex; justify-content: flex-end; gap: 0.75rem; margin-top: 1.75rem;">
            <a href="{{ route('materi.index') }}" class="btn btn-secondary">
                <i data-lucide="arrow-left"></i>
                Batal
            </a>
            <button type="submit" class="btn btn-primary">
                <i data-lucide="save"></i>
                Simpan Perubahan
            </button>
        </div>
    </form>
</div>
@endsection

@extends('layouts.app')

@section('title', 'Edit To-Do Item')

@section('content')
<div class="card" style="max-width: 600px; margin: 0 auto;">
    <h3 style="font-size: 1.25rem; font-weight: 800; margin-bottom: 1.5rem; color: var(--primary-deep); display: flex; align-items: center; gap: 0.5rem;">
        <i data-lucide="pencil" style="width: 20px; height: 20px; color: var(--primary);"></i>
        Edit To-Do Item
    </h3>

    <form action="{{ route('todolist.update', $todoTask) }}" method="POST">
        @csrf
        @method('PUT')

        <div class="form-group">
            <label for="materi_id" class="form-label">Materi Belajar <span style="color: var(--danger);">*</span></label>
            <select name="materi_id" id="materi_id" class="form-select" required>
                @foreach($materis as $m)
                    <option value="{{ $m->id }}" {{ old('materi_id', $todoTask->materi_id) == $m->id ? 'selected' : '' }}>
                        [{{ $m->subject->name }}] {{ $m->title }}
                    </option>
                @endforeach
            </select>
            @error('materi_id')
                <div class="error-text">{{ $message }}</div>
            @enderror
        </div>

        <div class="form-group">
            <label for="title" class="form-label">Kegiatan / Target Soal <span style="color: var(--danger);">*</span></label>
            <input type="text" name="title" id="title" class="form-control" value="{{ old('title', $todoTask->title) }}" required>
            @error('title')
                <div class="error-text">{{ $message }}</div>
            @enderror
        </div>

        <div class="form-group">
            <label for="due_date" class="form-label">Target Tanggal Selesai <span style="color: var(--text-muted); font-size: 0.8rem; font-weight: 500;">(opsional)</span></label>
            <input type="date" name="due_date" id="due_date" class="form-control" value="{{ old('due_date', $todoTask->due_date ? $todoTask->due_date->format('Y-m-d') : '') }}">
            @error('due_date')
                <div class="error-text">{{ $message }}</div>
            @enderror
        </div>

        <div class="form-group">
            <label for="status" class="form-label">Status Task <span style="color: var(--danger);">*</span></label>
            <select name="status" id="status" class="form-select" required>
                <option value="pending" {{ old('status', $todoTask->status) == 'pending' ? 'selected' : '' }}>Belum Selesai (Pending)</option>
                <option value="completed" {{ old('status', $todoTask->status) == 'completed' ? 'selected' : '' }}>Selesai (Completed)</option>
            </select>
            @error('status')
                <div class="error-text">{{ $message }}</div>
            @enderror
        </div>

        <div style="display: flex; justify-content: flex-end; gap: 0.75rem; margin-top: 1.75rem;">
            <a href="{{ route('todolist.index', ['materi_id' => $todoTask->materi_id]) }}" class="btn btn-secondary">
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

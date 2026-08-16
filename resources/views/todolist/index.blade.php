@extends('layouts.app')

@section('title', 'Daftar To-Do Belajar')

@section('content')
<style>
    .todo-wrapper {
        display: grid;
        grid-template-columns: 380px 1fr;
        gap: 1.75rem;
    }

    .todo-list {
        display: flex;
        flex-direction: column;
        gap: 0.875rem;
    }

    .todo-item {
        background-color: var(--card-bg);
        border: 1px solid var(--border-color);
        border-radius: var(--radius);
        padding: 1.15rem 1.35rem;
        display: flex;
        justify-content: space-between;
        align-items: center;
        gap: 1rem;
        box-shadow: var(--shadow-sm);
        transition: var(--transition-smooth);
    }

    .todo-item:hover {
        border-color: var(--border-hover);
        transform: translateY(-2px);
        box-shadow: var(--shadow-card);
    }

    .todo-item.completed {
        background-color: #f8fafc;
        border-color: #e2e8f0;
    }

    .todo-item.completed .todo-title {
        text-decoration: line-through;
        color: var(--text-muted);
    }

    .todo-title {
        font-weight: 700;
        font-size: 0.975rem;
        color: var(--text-main);
        line-height: 1.35;
    }

    .todo-meta {
        font-size: 0.825rem;
        color: var(--text-muted);
        margin-top: 0.35rem;
        display: flex;
        align-items: center;
        gap: 0.5rem;
        flex-wrap: wrap;
    }

    .materi-selector {
        background-color: var(--card-bg);
        border: 1px solid var(--border-color);
        border-radius: var(--radius);
        padding: 1.15rem 1.5rem;
        margin-bottom: 1.75rem;
        box-shadow: var(--shadow-sm);
    }

    .status-toggle-btn {
        background: none;
        border: none;
        cursor: pointer;
        line-height: 1;
        transition: transform 0.2s ease;
        padding: 0.1rem;
        display: flex;
        align-items: center;
    }

    .status-toggle-btn:hover {
        transform: scale(1.15);
    }

    @media (max-width: 900px) {
        .todo-wrapper {
            grid-template-columns: 1fr;
        }
    }
</style>

<!-- Bilah Filter & Pilih Materi -->
<div class="materi-selector">
    <form action="{{ route('todolist.index') }}" method="GET" style="display: flex; gap: 1rem; align-items: center; flex-wrap: wrap;">
        <label for="materi_id_filter" style="font-weight: 800; font-size: 0.95rem; white-space: nowrap; color: var(--primary-deep); display: flex; align-items: center; gap: 0.4rem;">
            <i data-lucide="filter" style="width: 16px; height: 16px; color: var(--primary);"></i>
            Pilih Materi Belajar:
        </label>
        <select name="materi_id" id="materi_id_filter" class="form-select" style="max-width: 420px;" onchange="this.form.submit()">
            <option value="">-- Tampilkan Seluruh Materi --</option>
            @foreach($materis as $m)
                <option value="{{ $m->id }}" {{ $selectedMateriId == $m->id ? 'selected' : '' }}>
                    [{{ $m->subject->name }}] {{ $m->title }}
                </option>
            @endforeach
        </select>
        @if($selectedMateriId)
            <a href="{{ route('todolist.index') }}" class="btn btn-secondary btn-sm">Tampilkan Semua</a>
        @endif
    </form>
</div>

<div class="todo-wrapper">
    <!-- Form Tambah Task To-Do -->
    <div>
        <div class="card">
            <h3 style="font-size: 1.15rem; font-weight: 800; margin-bottom: 1.35rem; color: var(--primary-deep); display: flex; align-items: center; gap: 0.5rem;">
                <i data-lucide="plus-circle" style="width: 20px; height: 20px; color: var(--primary);"></i>
                Tambah Kegiatan To-Do
            </h3>

            <form action="{{ route('todolist.store') }}" method="POST">
                @csrf

                <div class="form-group">
                    <label for="materi_id" class="form-label">Materi Belajar <span style="color: var(--danger);">*</span></label>
                    <select name="materi_id" id="materi_id" class="form-select" required>
                        <option value="" disabled {{ !$selectedMateriId ? 'selected' : '' }}>Pilih Materi Belajar...</option>
                        @foreach($materis as $m)
                            <option value="{{ $m->id }}" {{ (old('materi_id', $selectedMateriId) == $m->id) ? 'selected' : '' }}>
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
                    <input type="text" name="title" id="title" class="form-control" placeholder="Misal: Kerjakan 15 soal latihan UTBK" value="{{ old('title') }}" required>
                    @error('title')
                        <div class="error-text">{{ $message }}</div>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="due_date" class="form-label">Target Tanggal Selesai <span style="color: var(--text-muted); font-size: 0.8rem; font-weight: 500;">(opsional)</span></label>
                    <input type="date" name="due_date" id="due_date" class="form-control" value="{{ old('due_date') }}">
                    @error('due_date')
                        <div class="error-text">{{ $message }}</div>
                    @enderror
                </div>

                <button type="submit" class="btn btn-primary" style="width: 100%;">
                    <i data-lucide="plus"></i>
                    Tambah Ke To-Do List
                </button>
            </form>
        </div>
    </div>

    <!-- Lista Task To-Do -->
    <div>
        @if($selectedMateri)
            <div class="card" style="border-left: 5px solid var(--primary); margin-bottom: 1.5rem;">
                <div style="display: flex; justify-content: space-between; align-items: center; gap: 1rem;">
                    <div>
                        <span class="badge-subject">{{ $selectedMateri->subject->name }}</span>
                        <h2 style="font-size: 1.35rem; font-weight: 800; margin-top: 0.35rem; color: var(--text-main);">{{ $selectedMateri->title }}</h2>
                        @if($selectedMateri->description)
                            <p style="font-size: 0.875rem; color: var(--text-muted); margin-top: 0.25rem;">{{ $selectedMateri->description }}</p>
                        @endif
                    </div>
                    <div style="text-align: right; flex-shrink: 0;">
                        <span style="font-size: 1.5rem; font-weight: 800; color: var(--primary);">{{ $selectedMateri->progress_percent }}%</span>
                        <div style="font-size: 0.75rem; color: var(--text-muted); font-weight: 700;">Progress Selesai</div>
                    </div>
                </div>
            </div>
        @endif

        <div class="card">
            <h3 style="font-size: 1.15rem; font-weight: 800; margin-bottom: 1.35rem; color: var(--primary-deep); display: flex; align-items: center; gap: 0.5rem;">
                <i data-lucide="check-square" style="width: 20px; height: 20px; color: var(--primary);"></i>
                Daftar Checklist To-Do ({{ $todoTasks->count() }})
            </h3>

            <div class="todo-list">
                @forelse($todoTasks as $task)
                    <div class="todo-item {{ $task->status === 'completed' ? 'completed' : '' }}">
                        <div style="display: flex; align-items: flex-start; gap: 0.875rem;">
                            <form action="{{ route('todolist.complete', $task) }}" method="POST" style="margin-top: 0.15rem;">
                                @csrf
                                @method('PATCH')
                                <button type="submit" class="status-toggle-btn" title="Klik untuk mengubah status (Selesai/Belum)">
                                    @if($task->status === 'completed')
                                        <i data-lucide="check-circle-2" style="width: 20px; height: 20px; color: var(--success);"></i>
                                    @else
                                        <i data-lucide="circle" style="width: 20px; height: 20px; color: var(--border-hover);"></i>
                                    @endif
                                </button>
                            </form>
                            <div>
                                <div class="todo-title">{{ $task->title }}</div>
                                <div class="todo-meta">
                                    <span class="badge-subject" style="font-size: 0.7rem; padding: 0.15rem 0.5rem;">
                                        {{ $task->materi->subject->name }} &bull; {{ $task->materi->title }}
                                    </span>
                                    @if($task->due_date)
                                        <span style="color: var(--warning-text); font-weight: 700; display: inline-flex; align-items: center; gap: 0.25rem;">
                                            <i data-lucide="calendar" style="width: 13px; height: 13px;"></i>
                                            Target: {{ $task->due_date->format('d M Y') }}
                                        </span>
                                    @endif
                                </div>
                            </div>
                        </div>

                        <div style="display: flex; gap: 0.5rem; flex-shrink: 0;">
                            <a href="{{ route('todolist.edit', $task) }}" class="btn btn-secondary btn-sm">
                                <i data-lucide="pencil"></i>
                                Edit
                            </a>
                            <form action="{{ route('todolist.destroy', $task) }}" method="POST" style="display: inline;" class="form-confirm" data-confirm-title="Hapus Task To-Do?" data-confirm-text="Apakah Anda yakin ingin menghapus task {{ $task->title }}?">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger btn-sm">
                                    <i data-lucide="trash-2"></i>
                                    Hapus
                                </button>
                            </form>
                        </div>
                    </div>
                @empty
                    <div style="text-align: center; color: var(--text-muted); padding: 3rem 1rem;">
                        <i data-lucide="check-square" style="width: 40px; height: 40px; color: var(--border-hover); margin-bottom: 0.75rem;"></i>
                        <div style="font-weight: 700; font-size: 1rem; color: var(--text-main); margin-bottom: 0.35rem;">Belum ada to-do task</div>
                        Tambahkan kegiatan baru pada formulir di sebelah kiri!
                    </div>
                @endforelse
            </div>
        </div>
    </div>
</div>
@endsection

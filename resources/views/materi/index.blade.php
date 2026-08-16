@extends('layouts.app')

@section('title', 'Kelola Materi Belajar')

@section('content')
<style>
    .materi-container {
        display: grid;
        grid-template-columns: 380px 1fr;
        gap: 1.75rem;
    }

    .table-materi {
        width: 100%;
        border-collapse: collapse;
    }

    .table-materi th, .table-materi td {
        padding: 1.1rem 1rem;
        border-bottom: 1px solid var(--border-color);
        text-align: left;
    }

    .table-materi th {
        font-size: 0.775rem;
        text-transform: uppercase;
        color: var(--text-muted);
        font-weight: 800;
        letter-spacing: 0.05em;
        background-color: #f8fafc;
    }

    .table-materi tr:hover td {
        background-color: #f8fafc;
    }

    @media (max-width: 900px) {
        .materi-container {
            grid-template-columns: 1fr;
        }
    }
</style>

<div class="materi-container">
    <!-- Form Tambah Materi -->
    <div>
        <div class="card">
            <h3 style="font-size: 1.15rem; font-weight: 800; margin-bottom: 1.35rem; color: var(--primary-deep); display: flex; align-items: center; gap: 0.5rem;">
                <i data-lucide="plus-circle" style="width: 20px; height: 20px; color: var(--primary);"></i>
                Tambah Materi Baru
            </h3>

            <form action="{{ route('materi.store') }}" method="POST">
                @csrf

                <div class="form-group">
                    <label for="subject_name" class="form-label">Mata Pelajaran <span style="color: var(--danger);">*</span></label>
                    <input type="text" name="subject_name" id="subject_name" class="form-control" list="subject_list" placeholder="Ketik bebas (misal: TKA Matematika, Fisika...)" value="{{ old('subject_name') }}" required autocomplete="off">
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
                    <input type="text" name="title" id="title" class="form-control" placeholder="Ketik nama materi (misal: Persamaan Linear)" value="{{ old('title') }}" required>
                    @error('title')
                        <div class="error-text">{{ $message }}</div>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="description" class="form-label">Catatan / Deskripsi <span style="color: var(--text-muted); font-size: 0.8rem; font-weight: 500;">(opsional)</span></label>
                    <textarea name="description" id="description" class="form-control" placeholder="Tambahkan rincian atau catatan singkat materi...">{{ old('description') }}</textarea>
                    @error('description')
                        <div class="error-text">{{ $message }}</div>
                    @enderror
                </div>

                <button type="submit" class="btn btn-primary" style="width: 100%;">
                    <i data-lucide="save"></i>
                    Simpan Materi
                </button>
            </form>
        </div>
    </div>

    <!-- Tabel Daftar Materi -->
    <div>
        <div class="card">
            <h3 style="font-size: 1.15rem; font-weight: 800; margin-bottom: 1.35rem; color: var(--primary-deep); display: flex; align-items: center; gap: 0.5rem;">
                <i data-lucide="book-open" style="width: 20px; height: 20px; color: var(--primary);"></i>
                Daftar Materi Belajar ({{ $materis->count() }})
            </h3>

            @if($materis->count() > 0)
                <div style="overflow-x: auto;">
                    <table class="table-materi">
                        <thead>
                            <tr>
                                <th>Materi & Mata Pelajaran</th>
                                <th>Progress Task</th>
                                <th style="text-align: right;">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($materis as $materi)
                                <tr>
                                    <td>
                                        <span class="badge-subject" style="margin-bottom: 0.35rem;">
                                            <i data-lucide="book" style="width: 12px; height: 12px;"></i>
                                            {{ $materi->subject->name }}
                                        </span>
                                        <div style="font-weight: 800; font-size: 1rem; color: var(--text-main);">
                                            {{ $materi->title }}
                                        </div>
                                        @if($materi->description)
                                            <div style="font-size: 0.85rem; color: var(--text-muted); margin-top: 0.25rem;">
                                                {{ $materi->description }}
                                            </div>
                                        @endif
                                    </td>
                                    <td>
                                        <span style="font-weight: 700; font-size: 0.875rem; color: var(--text-main);">
                                            {{ $materi->todoTasks->where('status', 'completed')->count() }} / {{ $materi->todoTasks->count() }} Selesai
                                        </span>
                                    </td>
                                    <td style="text-align: right; white-space: nowrap;">
                                        <a href="{{ route('todolist.index', ['materi_id' => $materi->id]) }}" class="btn btn-primary btn-sm" title="Buka To-Do List">
                                            <i data-lucide="check-square"></i>
                                            To-Do List
                                        </a>
                                        <a href="{{ route('materi.edit', $materi) }}" class="btn btn-secondary btn-sm">
                                            <i data-lucide="pencil"></i>
                                            Edit
                                        </a>
                                        <form action="{{ route('materi.destroy', $materi) }}" method="POST" style="display: inline;" class="form-confirm" data-confirm-title="Hapus Materi Belajar?" data-confirm-text="Apakah Anda yakin ingin menghapus materi {{ $materi->title }}? Seluruh to-do task di dalamnya juga akan terhapus.">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-danger btn-sm">
                                                <i data-lucide="trash-2"></i>
                                                Hapus
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <div style="text-align: center; color: var(--text-muted); padding: 3rem 1rem;">
                    <i data-lucide="book-open" style="width: 40px; height: 40px; color: var(--border-hover); margin-bottom: 0.75rem;"></i>
                    <div style="font-weight: 700; font-size: 1rem; color: var(--text-main); margin-bottom: 0.35rem;">Belum ada materi belajar</div>
                    Isi nama mata pelajaran dan nama materi pada formulir di sebelah kiri lalu klik <strong>Simpan Materi</strong>.
                </div>
            @endif
        </div>
    </div>
</div>
@endsection

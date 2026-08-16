@extends('layouts.app')

@section('title', 'Detail Hasil TKA')

@section('content')
<div class="card" style="max-width: 680px; margin: 0 auto;">
    <div style="margin-bottom: 1.5rem; border-bottom: 1px solid var(--border-color); padding-bottom: 1rem;">
        <span style="font-size: 0.825rem; font-weight: 800; color: var(--primary); text-transform: uppercase; letter-spacing: 0.05em;">
            Detail Hasil Tryout TKA
        </span>
        <h1 style="font-size: 1.5rem; font-weight: 800; color: var(--primary-deep); margin-top: 0.25rem;">
            {{ $tkaTryout->name }}
        </h1>
        <div style="font-size: 0.875rem; color: var(--text-muted); margin-top: 0.25rem; display: flex; align-items: center; gap: 0.35rem;">
            <i data-lucide="calendar" style="width: 14px; height: 14px;"></i>
            Pelaksanaan: {{ $tkaTryout->date->format('d F Y') }}
        </div>
    </div>

    <!-- 1. Mata Pelajaran Wajib -->
    <h3 style="font-size: 1.05rem; font-weight: 800; color: var(--primary-deep); margin-bottom: 1rem; display: flex; align-items: center; gap: 0.4rem;">
        <i data-lucide="book-open" style="width: 18px; height: 18px; color: var(--primary);"></i>
        Mata Pelajaran Wajib
    </h3>
    <div style="display: flex; flex-direction: column; gap: 0.75rem; margin-bottom: 2rem;">
        @foreach($tkaTryout->subjectScores->where('subject_type', 'mandatory') as $ss)
            <div style="display: flex; justify-content: space-between; align-items: center; padding: 0.85rem 1.1rem; background-color: #f8fafc; border: 1px solid var(--border-color); border-radius: 12px;">
                <span style="font-weight: 700; font-size: 0.925rem; color: var(--text-main);">{{ $ss->subject_name }}</span>
                <span style="font-weight: 800; font-size: 1.05rem; color: var(--primary-deep);">{{ number_format($ss->score, 2) }}</span>
            </div>
        @endforeach
    </div>

    <!-- 2. Mata Pelajaran Pilihan -->
    <h3 style="font-size: 1.05rem; font-weight: 800; color: var(--primary-deep); margin-bottom: 1rem; display: flex; align-items: center; gap: 0.4rem;">
        <i data-lucide="target" style="width: 18px; height: 18px; color: var(--primary);"></i>
        Mata Pelajaran Pilihan
    </h3>
    <div style="display: flex; flex-direction: column; gap: 0.75rem; margin-bottom: 2rem;">
        @foreach($tkaTryout->subjectScores->where('subject_type', 'choice') as $ss)
            <div style="display: flex; justify-content: space-between; align-items: center; padding: 0.85rem 1.1rem; background-color: #f8fafc; border: 1px solid var(--border-color); border-radius: 12px;">
                <span style="font-weight: 700; font-size: 0.925rem; color: var(--text-main);">{{ $ss->subject_name }}</span>
                <span style="font-weight: 800; font-size: 1.05rem; color: var(--primary-deep);">{{ number_format($ss->score, 2) }}</span>
            </div>
        @endforeach
    </div>

    @if($tkaTryout->notes)
        <div style="margin-bottom: 2rem; padding: 1rem 1.25rem; background-color: #f8fafc; border: 1px solid var(--border-color); border-radius: 12px;">
            <div style="font-size: 0.825rem; font-weight: 700; color: var(--text-muted); margin-bottom: 0.25rem; display: flex; align-items: center; gap: 0.35rem;">
                <i data-lucide="file-text" style="width: 14px; height: 14px;"></i>
                Catatan Pengerjaan:
            </div>
            <div style="font-size: 0.925rem; color: var(--text-main);">
                {{ $tkaTryout->notes }}
            </div>
        </div>
    @endif

    <div style="display: flex; justify-content: space-between; align-items: center; border-top: 1px solid var(--border-color); padding-top: 1.5rem;">
        <a href="{{ route('tka.index') }}" class="btn btn-secondary">
            <i data-lucide="arrow-left"></i>
            Kembali ke TKA Analisis
        </a>
        <div style="display: flex; gap: 0.75rem;">
            <a href="{{ route('tka.edit', $tkaTryout) }}" class="btn btn-secondary">
                <i data-lucide="pencil"></i>
                Edit
            </a>
            <form action="{{ route('tka.destroy', $tkaTryout) }}" method="POST" style="display: inline;" class="form-confirm" data-confirm-title="Hapus Hasil TKA?" data-confirm-text="Hapus hasil TKA {{ $tkaTryout->name }}? Semua nilai mata pelajaran dalam hasil ini juga akan dihapus.">
                @csrf
                @method('DELETE')
                <button type="submit" class="btn btn-danger">
                    <i data-lucide="trash-2"></i>
                    Hapus
                </button>
            </form>
        </div>
    </div>
</div>
@endsection

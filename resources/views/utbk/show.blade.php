@extends('layouts.app')

@section('title', 'Detail Hasil Tryout')

@section('content')
<div class="card" style="max-width: 680px; margin: 0 auto;">
    <div style="display: flex; justify-content: space-between; align-items: flex-start; margin-bottom: 1.5rem;">
        <div>
            <span style="font-size: 0.825rem; font-weight: 800; color: var(--primary); text-transform: uppercase; letter-spacing: 0.05em;">
                Detail Hasil Tryout UTBK
            </span>
            <h1 style="font-size: 1.5rem; font-weight: 800; color: var(--primary-deep); margin-top: 0.25rem;">
                {{ $utbkTryout->name }}
            </h1>
            <div style="font-size: 0.875rem; color: var(--text-muted); margin-top: 0.25rem; display: flex; align-items: center; gap: 0.35rem;">
                <i data-lucide="calendar" style="width: 14px; height: 14px;"></i>
                Pelaksanaan: {{ $utbkTryout->date->format('d F Y') }}
            </div>
        </div>
        <div style="text-align: right;">
            <div style="font-size: 0.775rem; color: var(--text-muted); font-weight: 800; text-transform: uppercase; letter-spacing: 0.04em;">
                NILAI KESELURUHAN
            </div>
            <div style="font-size: 2.2rem; font-weight: 800; color: var(--primary); line-height: 1; margin-top: 0.2rem;">
                {{ number_format($utbkTryout->overall_score, 2) }}
            </div>
        </div>
    </div>

    <!-- 7 Subtest Scores Breakdown -->
    <h3 style="font-size: 1.05rem; font-weight: 800; color: var(--primary-deep); margin-bottom: 1rem; border-bottom: 2px solid var(--border-color); padding-bottom: 0.5rem; display: flex; align-items: center; gap: 0.4rem;">
        <i data-lucide="bar-chart-2" style="width: 18px; height: 18px; color: var(--primary);"></i>
        Rincian Nilai 7 Subtes
    </h3>

    <div style="display: flex; flex-direction: column; gap: 0.75rem; margin-bottom: 2rem;">
        @foreach($subtests as $key => $label)
            @php
                $subScoreObj = $utbkTryout->subtestScores->firstWhere('subtest', $key);
                $val = $subScoreObj ? $subScoreObj->score : 0;
            @endphp
            <div style="display: flex; justify-content: space-between; align-items: center; padding: 0.85rem 1.1rem; background-color: #f8fafc; border: 1px solid var(--border-color); border-radius: 12px;">
                <span style="font-weight: 700; font-size: 0.925rem; color: var(--text-main);">{{ $label }}</span>
                <span style="font-weight: 800; font-size: 1.05rem; color: var(--primary-deep);">{{ number_format($val, 2) }}</span>
            </div>
        @endforeach
    </div>

    @if($utbkTryout->notes)
        <div style="margin-bottom: 2rem; padding: 1rem 1.25rem; background-color: #f8fafc; border: 1px solid var(--border-color); border-radius: 12px;">
            <div style="font-size: 0.825rem; font-weight: 700; color: var(--text-muted); margin-bottom: 0.25rem; display: flex; align-items: center; gap: 0.35rem;">
                <i data-lucide="file-text" style="width: 14px; height: 14px;"></i>
                Catatan Pengerjaan:
            </div>
            <div style="font-size: 0.925rem; color: var(--text-main);">
                {{ $utbkTryout->notes }}
            </div>
        </div>
    @endif

    <div style="display: flex; justify-content: space-between; align-items: center; border-top: 1px solid var(--border-color); padding-top: 1.5rem;">
        <a href="{{ route('utbk.index') }}" class="btn btn-secondary">
            <i data-lucide="arrow-left"></i>
            Kembali ke UTBK Tracker
        </a>
        <div style="display: flex; gap: 0.75rem;">
            <a href="{{ route('utbk.edit', $utbkTryout) }}" class="btn btn-secondary">
                <i data-lucide="pencil"></i>
                Edit
            </a>
            <form action="{{ route('utbk.destroy', $utbkTryout) }}" method="POST" style="display: inline;" class="form-confirm" data-confirm-title="Hapus Hasil Tryout UTBK?" data-confirm-text="Hapus hasil tryout {{ $utbkTryout->name }}? Data nilai dan seluruh nilai subtes dari tryout ini akan dihapus.">
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

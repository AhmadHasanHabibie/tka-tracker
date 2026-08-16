@extends('layouts.app')

@section('title', 'Dashboard — UTBK Tracker')

@section('content')
<style>
    .motivation-banner {
        background: linear-gradient(135deg, #1e3a8a, #2563eb);
        border-radius: var(--radius);
        padding: 2.25rem;
        color: #ffffff;
        margin-bottom: 2.25rem;
        box-shadow: 0 12px 28px -5px rgba(37, 99, 235, 0.25);
        display: flex;
        align-items: center;
        gap: 2rem;
        position: relative;
        overflow: hidden;
    }

    .motivation-banner::after {
        content: '';
        position: absolute;
        right: -40px;
        bottom: -40px;
        width: 220px;
        height: 220px;
        background: rgba(255, 255, 255, 0.08);
        border-radius: 50%;
        pointer-events: none;
    }

    .motivation-img-box {
        width: 130px;
        height: 130px;
        border-radius: 18px;
        overflow: hidden;
        flex-shrink: 0;
        border: 3px solid rgba(255, 255, 255, 0.3);
        box-shadow: 0 8px 20px rgba(0, 0, 0, 0.15);
        background-color: #ffffff;
    }

    .motivation-img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }

    .motivation-content {
        flex: 1;
    }

    .motivation-badge {
        display: inline-flex;
        align-items: center;
        gap: 0.4rem;
        background: rgba(255, 255, 255, 0.2);
        backdrop-filter: blur(8px);
        color: #ffffff;
        font-size: 0.75rem;
        font-weight: 800;
        padding: 0.35rem 0.85rem;
        border-radius: 999px;
        letter-spacing: 0.05em;
        margin-bottom: 0.75rem;
    }

    .motivation-title {
        font-size: 1.6rem;
        font-weight: 800;
        line-height: 1.25;
        margin-bottom: 0.5rem;
        letter-spacing: -0.02em;
    }

    .motivation-sub {
        font-size: 0.95rem;
        color: rgba(255, 255, 255, 0.9);
        line-height: 1.5;
        font-weight: 500;
        margin-bottom: 1.25rem;
    }

    .metrics-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
        gap: 1.25rem;
        margin-bottom: 2.25rem;
    }

    .metric-card {
        background-color: var(--card-bg);
        border: 1px solid var(--border-color);
        border-radius: var(--radius);
        padding: 1.35rem 1.5rem;
        display: flex;
        align-items: center;
        gap: 1.15rem;
        box-shadow: var(--shadow-card);
        transition: var(--transition-smooth);
    }

    .metric-card:hover {
        transform: translateY(-2px);
        border-color: var(--border-hover);
        box-shadow: 0 10px 22px -5px rgba(15, 23, 42, 0.08);
    }

    .metric-icon {
        width: 48px;
        height: 48px;
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        flex-shrink: 0;
    }

    .metric-icon.blue { background-color: var(--primary-light); color: var(--primary-text); }
    .metric-icon.purple { background-color: #f3e8ff; color: #7e22ce; }
    .metric-icon.green { background-color: #ecfdf5; color: #047857; }
    .metric-icon.amber { background-color: #fffbeb; color: #b45309; }

    .metric-val {
        font-size: 1.65rem;
        font-weight: 800;
        line-height: 1.2;
        color: var(--text-main);
    }

    .metric-lbl {
        font-size: 0.85rem;
        color: var(--text-muted);
        font-weight: 600;
    }

    .section-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 1.5rem;
    }

    .section-header h2 {
        font-size: 1.3rem;
        font-weight: 800;
        color: var(--primary-deep);
        letter-spacing: -0.02em;
    }

    .materi-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(320px, 1fr));
        gap: 1.5rem;
    }

    .materi-card {
        background-color: var(--card-bg);
        border: 1px solid var(--border-color);
        border-radius: var(--radius);
        padding: 1.75rem;
        display: flex;
        flex-direction: column;
        justify-content: space-between;
        gap: 1.25rem;
        text-decoration: none;
        color: inherit;
        box-shadow: var(--shadow-card);
        transition: var(--transition-smooth);
        position: relative;
        overflow: hidden;
    }

    .materi-card::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        height: 3px;
        background: linear-gradient(90deg, #1e3a8a, #2563eb);
        opacity: 0;
        transition: opacity 0.2s ease;
    }

    .materi-card:hover {
        transform: translateY(-3px);
        border-color: var(--border-hover);
        box-shadow: var(--shadow-hover);
    }

    .materi-card:hover::before {
        opacity: 1;
    }

    .materi-card-header {
        display: flex;
        justify-content: space-between;
        align-items: flex-start;
        gap: 0.75rem;
    }

    .materi-title {
        font-size: 1.15rem;
        font-weight: 800;
        color: var(--text-main);
        margin-top: 0.65rem;
        line-height: 1.35;
        letter-spacing: -0.01em;
    }

    .materi-desc {
        font-size: 0.875rem;
        color: var(--text-muted);
        line-height: 1.5;
        display: -webkit-box;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;
        overflow: hidden;
    }

    .progress-bar-bg {
        background-color: #f1f5f9;
        border-radius: 999px;
        height: 8px;
        width: 100%;
        overflow: hidden;
        margin-top: 0.6rem;
    }

    .progress-bar-fill {
        height: 100%;
        background: linear-gradient(90deg, #2563eb, #10b981);
        border-radius: 999px;
        transition: width 0.4s ease;
    }

    .progress-meta {
        display: flex;
        justify-content: space-between;
        font-size: 0.825rem;
        color: var(--text-muted);
        font-weight: 700;
    }

    .empty-card-banner {
        background-color: var(--card-bg);
        border: 2px dashed var(--border-color);
        border-radius: var(--radius);
        padding: 3.5rem 2rem;
        text-align: center;
        box-shadow: var(--shadow-card);
    }

    .empty-icon {
        margin-bottom: 1rem;
        display: inline-block;
    }

    .empty-title {
        font-size: 1.25rem;
        font-weight: 800;
        color: var(--text-main);
        margin-bottom: 0.5rem;
    }

    .empty-subtitle {
        color: var(--text-muted);
        font-size: 0.925rem;
        max-width: 480px;
        margin: 0 auto 1.5rem auto;
        line-height: 1.6;
    }

    @media (max-width: 768px) {
        .motivation-banner {
            flex-direction: column;
            text-align: center;
            padding: 1.75rem;
        }
        .motivation-img-box {
            width: 110px;
            height: 110px;
        }
    }
</style>

<!-- Banner Motivasi Impian (Tujuan UTBK) -->
@if($goal)
    <div class="motivation-banner">
        <div class="motivation-img-box">
            <img src="{{ $goal->photo_url }}" alt="{{ $goal->university_name }}" class="motivation-img">
        </div>
        <div class="motivation-content">
            <span class="motivation-badge">
                <i data-lucide="target" style="width: 14px; height: 14px;"></i>
                TARGET UTBK & PTN IMPIAN
            </span>
            <h1 class="motivation-title">{{ $goal->university_name }} — {{ $goal->study_program }}</h1>
            @if($goal->target_score)
                <p class="motivation-sub">Target Skor: {{ $goal->target_score }}</p>
            @else
                <p class="motivation-sub">Fokus, konsisten, dan selesaikan materi belajar hari ini demi impianmu!</p>
            @endif
            <a href="{{ route('goal.index') }}" class="btn" style="background-color: #ffffff; color: var(--primary-deep); font-weight: 800; border-radius: 999px;">
                <i data-lucide="pencil" style="width: 16px; height: 16px;"></i>
                Kelola Tujuan Impian
            </a>
        </div>
    </div>
@else
    <div class="motivation-banner" style="background: linear-gradient(135deg, #1e3a8a, #2563eb);">
        <div class="motivation-content" style="text-align: center; max-width: 650px; margin: 0 auto;">
            <span class="motivation-badge">
                <i data-lucide="sparkles" style="width: 14px; height: 14px;"></i>
                SEMANGAT UTBK & TKA
            </span>
            <h1 class="motivation-title">Gantungkan Cita-Citamu Setinggi Langit!</h1>
            <p class="motivation-sub">Kamu belum menentukan target PTN & prodi impianmu. Tentukan tujuanmu sekarang agar makin bersemangat belajar!</p>
            <a href="{{ route('goal.index') }}" class="btn" style="background-color: #ffffff; color: var(--primary-deep); font-weight: 800; border-radius: 999px;">
                <i data-lucide="target" style="width: 16px; height: 16px;"></i>
                Atur Tujuan Impian Kamu Sekarang
            </a>
        </div>
    </div>
@endif

<!-- Rincian Statistik Ringkas -->
<div class="metrics-grid">
    <div class="metric-card">
        <div class="metric-icon blue">
            <i data-lucide="book-open" style="width: 22px; height: 22px;"></i>
        </div>
        <div>
            <div class="metric-val">{{ $totalMateri }}</div>
            <div class="metric-lbl">Total Materi</div>
        </div>
    </div>
    <div class="metric-card">
        <div class="metric-icon purple">
            <i data-lucide="list-todo" style="width: 22px; height: 22px;"></i>
        </div>
        <div>
            <div class="metric-val">{{ $totalTodos }}</div>
            <div class="metric-lbl">Total Task</div>
        </div>
    </div>
    <div class="metric-card">
        <div class="metric-icon green">
            <i data-lucide="check-circle-2" style="width: 22px; height: 22px;"></i>
        </div>
        <div>
            <div class="metric-val">{{ $completedTodos }}</div>
            <div class="metric-lbl">Task Selesai</div>
        </div>
    </div>
    <div class="metric-card">
        <div class="metric-icon amber">
            <i data-lucide="pie-chart" style="width: 22px; height: 22px;"></i>
        </div>
        <div>
            <div class="metric-val">{{ $overallProgress }}%</div>
            <div class="metric-lbl">Progress Belajar</div>
        </div>
    </div>
</div>

<!-- Judul Bagian Card Materi -->
<div class="section-header">
    <h2>Materi Belajar Kamu</h2>
    <a href="{{ route('materi.index') }}" class="btn btn-primary btn-sm">
        <i data-lucide="plus"></i>
        Tambah Materi
    </a>
</div>

<!-- Grid Card Materi ATAU Banner Kosong -->
@if($materis->count() > 0)
    <div class="materi-grid">
        @foreach($materis as $materi)
            <a href="{{ route('todolist.index', ['materi_id' => $materi->id]) }}" class="materi-card">
                <div>
                    <div class="materi-card-header">
                        <span class="badge-subject">{{ $materi->subject->name }}</span>
                        <span style="font-size: 0.825rem; color: var(--primary); font-weight: 700; display: inline-flex; align-items: center; gap: 0.25rem;">
                            Buka To-Do
                            <i data-lucide="arrow-right" style="width: 14px; height: 14px;"></i>
                        </span>
                    </div>
                    <div class="materi-title">{{ $materi->title }}</div>
                    @if($materi->description)
                        <div class="materi-desc" style="margin-top: 0.5rem;">{{ $materi->description }}</div>
                    @endif
                </div>

                <div>
                    <div class="progress-meta">
                        <span>Progress Belajar</span>
                        <span>{{ $materi->todoTasks->where('status', 'completed')->count() }} / {{ $materi->todoTasks->count() }} Task ({{ $materi->progress_percent }}%)</span>
                    </div>
                    <div class="progress-bar-bg">
                        <div class="progress-bar-fill" style="width: {{ $materi->progress_percent }}%;"></div>
                    </div>
                </div>
            </a>
        @endforeach
    </div>
@else
    <div class="empty-card-banner">
        <div class="empty-icon">
            <i data-lucide="book-open" style="width: 44px; height: 44px; color: var(--primary);"></i>
        </div>
        <div class="empty-title">Belum Ada Materi Belajar</div>
        <div class="empty-subtitle">
            Daftar materi belajar kamu masih kosong. Yuk buat materi belajar pertamamu terlebih dahulu di menu <strong>Materi Belajar</strong>!
        </div>
        <a href="{{ route('materi.index') }}" class="btn btn-primary">
            <i data-lucide="plus"></i>
            Buat Materi Pertama Sekarang
        </a>
    </div>
@endif

@endsection

@extends('layouts.app')

@section('title', 'Study Pet')

@section('content')
<style>
    .header-bar {
        margin-bottom: 2rem;
    }

    .header-bar h1 {
        font-size: 1.5rem;
        font-weight: 800;
        color: var(--primary-deep);
        letter-spacing: -0.02em;
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }

    .header-bar p {
        color: var(--text-muted);
        font-size: 0.925rem;
        margin-top: 0.25rem;
    }

    /* Pet Hero Card */
    .pet-hero-card {
        background: linear-gradient(135deg, #ffffff 0%, #f8fafc 100%);
        border: 1px solid var(--border-color);
        border-radius: var(--radius);
        padding: 2.5rem 2rem;
        text-align: center;
        box-shadow: var(--shadow-card);
        margin-bottom: 2rem;
        position: relative;
        overflow: hidden;
    }

    .pet-avatar-wrapper {
        width: 120px;
        height: 120px;
        margin: 0 auto 1.25rem auto;
        background: radial-gradient(circle, rgba(37, 99, 235, 0.15) 0%, rgba(255, 255, 255, 0) 70%);
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 5rem;
        box-shadow: 0 10px 25px -5px rgba(37, 99, 235, 0.2);
        animation: floatPet 3.5s ease-in-out infinite;
    }

    @keyframes floatPet {
        0%, 100% { transform: translateY(0px) scale(1); }
        50% { transform: translateY(-8px) scale(1.04); }
    }

    .pet-level-badge {
        display: inline-block;
        background-color: var(--primary);
        color: white;
        font-size: 0.8rem;
        font-weight: 800;
        padding: 0.35rem 0.9rem;
        border-radius: 20px;
        text-transform: uppercase;
        letter-spacing: 0.05em;
        margin-bottom: 0.5rem;
    }

    .pet-name {
        font-size: 1.6rem;
        font-weight: 800;
        color: var(--primary-deep);
        margin-bottom: 0.25rem;
    }

    .xp-total-subtitle {
        font-size: 1rem;
        font-weight: 700;
        color: var(--primary);
        margin-bottom: 1.25rem;
        display: inline-flex;
        align-items: center;
        gap: 0.35rem;
    }

    .xp-bar-container {
        max-width: 480px;
        margin: 0 auto;
    }

    .xp-label-row {
        display: flex;
        justify-content: space-between;
        font-size: 0.85rem;
        font-weight: 700;
        color: var(--text-muted);
        margin-bottom: 0.4rem;
    }

    .xp-progress-track {
        height: 14px;
        background-color: #e2e8f0;
        border-radius: 10px;
        overflow: hidden;
        position: relative;
    }

    .xp-progress-fill {
        height: 100%;
        background: linear-gradient(90deg, #1e3a8a 0%, #2563eb 100%);
        border-radius: 10px;
        transition: width 0.8s cubic-bezier(0.4, 0, 0.2, 1);
    }

    .xp-needed-text {
        font-size: 0.825rem;
        color: var(--text-muted);
        font-weight: 600;
        margin-top: 0.5rem;
    }

    /* Stats Grid */
    .stats-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
        gap: 1.25rem;
        margin-bottom: 2rem;
    }

    .stat-card {
        background-color: var(--card-bg);
        border: 1px solid var(--border-color);
        border-radius: var(--radius);
        padding: 1.25rem 1.5rem;
        box-shadow: var(--shadow-card);
        display: flex;
        align-items: center;
        gap: 1rem;
        transition: var(--transition-smooth);
    }

    .stat-card:hover {
        transform: translateY(-2px);
        box-shadow: 0 10px 20px -3px rgba(15, 23, 42, 0.06);
    }

    .stat-icon-box {
        width: 48px;
        height: 48px;
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .stat-val {
        font-size: 1.4rem;
        font-weight: 800;
        color: var(--text-main);
        line-height: 1.1;
    }

    .stat-lbl {
        font-size: 0.8rem;
        color: var(--text-muted);
        font-weight: 700;
        margin-top: 0.2rem;
    }

    /* Calendar Grid */
    .calendar-card {
        background-color: var(--card-bg);
        border: 1px solid var(--border-color);
        border-radius: var(--radius);
        padding: 1.5rem;
        box-shadow: var(--shadow-card);
        margin-bottom: 2rem;
    }

    .calendar-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 1.25rem;
    }

    .calendar-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(42px, 1fr));
        gap: 0.6rem;
    }

    .cal-day-box {
        aspect-ratio: 1;
        border-radius: 10px;
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        font-size: 0.825rem;
        font-weight: 700;
        border: 1px solid var(--border-color);
        background-color: #f8fafc;
        color: var(--text-muted);
        transition: var(--transition-smooth);
    }

    .cal-day-box.active {
        background-color: #ecfdf5;
        border-color: #a7f3d0;
        color: #047857;
        box-shadow: 0 2px 6px rgba(16, 185, 129, 0.15);
    }

    .cal-day-box.today {
        border: 2px solid var(--primary);
    }

    .cal-dot {
        width: 6px;
        height: 6px;
        border-radius: 50%;
        margin-top: 2px;
    }

    .cal-day-box.active .cal-dot {
        background-color: #10b981;
    }

    .cal-day-box:not(.active) .cal-dot {
        background-color: #cbd5e1;
    }

    /* Today's Activity & History */
    .grid-2col {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 1.75rem;
        margin-bottom: 2rem;
    }

    .activity-item {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 0.85rem 1rem;
        background-color: #f8fafc;
        border: 1px solid var(--border-color);
        border-radius: 10px;
        margin-bottom: 0.6rem;
    }

    .activity-item:last-child {
        margin-bottom: 0;
    }

    .xp-chip {
        background-color: var(--primary-light);
        color: var(--primary-text);
        font-weight: 800;
        font-size: 0.8rem;
        padding: 0.25rem 0.6rem;
        border-radius: 6px;
    }

    @media (max-width: 900px) {
        .grid-2col {
            grid-template-columns: 1fr;
        }
    }
</style>

<!-- Header Bar -->
<div class="header-bar">
    <h1>
        <i data-lucide="sparkles" style="width: 24px; height: 24px; color: var(--primary);"></i>
        Study Pet
    </h1>
    <p>Bangun konsistensi belajarmu sedikit demi sedikit.</p>
</div>

<!-- PET HERO CARD -->
<div class="pet-hero-card">
    <div class="pet-avatar-wrapper">
        {{ $currentLevelInfo['icon'] }}
    </div>

    <div>
        <span class="pet-level-badge">Level {{ $currentLevelNum }}</span>
        <h2 class="pet-name">{{ $currentLevelInfo['name'] }}</h2>
        <div class="xp-total-subtitle">
            <i data-lucide="sparkles" style="width: 16px; height: 16px; color: var(--primary);"></i>
            {{ $totalXP }} XP
        </div>
    </div>

    <div class="xp-bar-container">
        <div class="xp-label-row">
            <span>Level Progress</span>
            <span>
                <strong>{{ $totalXP }}</strong>
                @if($nextLevelInfo)
                    / {{ $nextLevelInfo['min_xp'] }} XP
                @else
                    XP (Level Maksimum)
                @endif
            </span>
        </div>
        <div class="xp-progress-track">
            <div class="xp-progress-fill" style="width: {{ $progressPercent }}%;"></div>
        </div>
        <div class="xp-needed-text">
            @if($nextLevelInfo)
                {{ $xpNeededForNext }} XP lagi menuju Level {{ $currentLevelNum + 1 }} ({{ $nextLevelInfo['name'] }})
            @else
                Level maksimum telah tercapai!
            @endif
        </div>
    </div>
</div>

<!-- STATS GRID -->
<div class="stats-grid">
    <div class="stat-card">
        <div class="stat-icon-box" style="background-color: #fff7ed; color: #ea580c;">
            <i data-lucide="flame" style="width: 22px; height: 22px;"></i>
        </div>
        <div>
            <div class="stat-val">{{ $currentStreak }} <span style="font-size: 0.9rem; font-weight: 600;">hari</span></div>
            <div class="stat-lbl">Current Streak</div>
        </div>
    </div>

    <div class="stat-card">
        <div class="stat-icon-box" style="background-color: #fefce8; color: #ca8a04;">
            <i data-lucide="trophy" style="width: 22px; height: 22px;"></i>
        </div>
        <div>
            <div class="stat-val">{{ $bestStreak }} <span style="font-size: 0.9rem; font-weight: 600;">hari</span></div>
            <div class="stat-lbl">Best Streak</div>
        </div>
    </div>

    <div class="stat-card">
        <div class="stat-icon-box" style="background-color: var(--primary-light); color: var(--primary-text);">
            <i data-lucide="sparkles" style="width: 22px; height: 22px;"></i>
        </div>
        <div>
            <div class="stat-val">{{ $totalXP }}</div>
            <div class="stat-lbl">Total XP</div>
        </div>
    </div>

    <div class="stat-card">
        <div class="stat-icon-box" style="background-color: #ecfdf5; color: #047857;">
            <i data-lucide="book-open" style="width: 22px; height: 22px;"></i>
        </div>
        <div>
            <div class="stat-val">{{ $todayActivitiesCount }}</div>
            <div class="stat-lbl">Aktivitas Hari Ini</div>
        </div>
    </div>
</div>

<!-- 2 COLUMN LAYOUT: TODAY'S ACTIVITY & CALENDAR -->
<div class="grid-2col">
    <!-- TODAY'S ACTIVITY -->
    <div class="card">
        <h3 style="font-size: 1.15rem; font-weight: 800; margin-bottom: 1rem; color: var(--primary-deep); display: flex; align-items: center; justify-content: space-between;">
            <span style="display: flex; align-items: center; gap: 0.4rem;">
                <i data-lucide="check-square" style="width: 18px; height: 18px; color: var(--primary);"></i>
                Aktivitas Hari Ini
            </span>
            @if($todayXpTotal > 0)
                <span class="xp-chip">+{{ $todayXpTotal }} XP</span>
            @endif
        </h3>

        @if($todayXpLogs->count() > 0)
            <div style="margin-bottom: 1rem;">
                @foreach($todayXpLogs as $log)
                    <div class="activity-item">
                        <div style="display: flex; align-items: center; gap: 0.6rem;">
                            <i data-lucide="check-circle-2" style="width: 18px; height: 18px; color: #10b981;"></i>
                            <div>
                                <span style="font-weight: 700; font-size: 0.9rem; color: var(--text-main);">
                                    {{ $log->source === 'login' ? 'Login Pertama Hari Ini' : 'Menyelesaikan To-Do' }}
                                </span>
                                @if($log->description && $log->source !== 'login')
                                    <div style="font-size: 0.8rem; color: var(--text-muted);">
                                        "{{ $log->description }}"
                                    </div>
                                @endif
                            </div>
                        </div>
                        <span style="font-weight: 800; font-size: 0.85rem; color: var(--primary);">+{{ $log->xp }} XP</span>
                    </div>
                @endforeach
            </div>
            <div style="padding: 0.75rem 1rem; background-color: #ecfdf5; border: 1px solid #a7f3d0; border-radius: 10px; color: #047857; font-size: 0.875rem; font-weight: 700; text-align: center;">
                Belajar hari ini tercatat! Kamu sudah menjaga streak kamu!
            </div>
        @else
            <div style="text-align: center; padding: 2rem 1rem; background-color: #f8fafc; border: 1px dashed var(--border-color); border-radius: 12px;">
                <div style="margin-bottom: 0.5rem; display: inline-block;">
                    <i data-lucide="sparkles" style="width: 36px; height: 36px; color: var(--border-hover);"></i>
                </div>
                <div style="font-weight: 700; color: var(--text-main); font-size: 0.95rem;">Belum ada aktivitas hari ini</div>
                <p style="font-size: 0.825rem; color: var(--text-muted); margin-top: 0.25rem;">
                    Selesaikan satu To-Do List untuk menjaga streak & menambah +10 XP!
                </p>
                <a href="{{ route('todolist.index') }}" class="btn btn-primary btn-sm" style="margin-top: 0.75rem;">
                    <i data-lucide="check-square"></i>
                    Buka To-Do List
                </a>
            </div>
        @endif
    </div>

    <!-- CALENDAR ACTIVITY GRID -->
    <div class="calendar-card" style="margin-bottom: 0;">
        <div class="calendar-header">
            <h3 style="font-size: 1.15rem; font-weight: 800; color: var(--primary-deep); display: flex; align-items: center; gap: 0.4rem;">
                <i data-lucide="calendar" style="width: 18px; height: 18px; color: var(--primary);"></i>
                Activity Calendar ({{ $now->format('F Y') }})
            </h3>
            <div style="display: flex; gap: 0.75rem; font-size: 0.775rem; font-weight: 700;">
                <span style="display: flex; align-items: center; gap: 0.3rem;"><span style="width: 8px; height: 8px; border-radius: 50%; background: #10b981;"></span> Active</span>
                <span style="display: flex; align-items: center; gap: 0.3rem;"><span style="width: 8px; height: 8px; border-radius: 50%; background: #cbd5e1;"></span> Inactive</span>
            </div>
        </div>

        <div class="calendar-grid">
            @foreach($calendarGrid as $dayItem)
                <div class="cal-day-box {{ $dayItem['is_active'] ? 'active' : '' }} {{ $dayItem['is_today'] ? 'today' : '' }}">
                    <span>{{ $dayItem['day'] }}</span>
                    <span class="cal-dot"></span>
                </div>
            @endforeach
        </div>
    </div>
</div>

<!-- XP HISTORY LIST -->
<div class="card">
    <h3 style="font-size: 1.15rem; font-weight: 800; margin-bottom: 1.25rem; color: var(--primary-deep); display: flex; align-items: center; gap: 0.5rem;">
        <i data-lucide="history" style="width: 20px; height: 20px; color: var(--primary);"></i>
        Riwayat XP Terbaru
    </h3>

    @if($recentXpLogs->count() > 0)
        <div style="display: flex; flex-direction: column; gap: 0.6rem;">
            @foreach($recentXpLogs as $log)
                <div class="activity-item">
                    <div style="display: flex; align-items: center; gap: 0.75rem;">
                        <i data-lucide="sparkles" style="width: 18px; height: 18px; color: var(--primary);"></i>
                        <div>
                            <div style="font-weight: 700; font-size: 0.925rem; color: var(--text-main);">
                                {{ $log->source === 'login' ? 'Login Pertama Hari Ini' : 'Menyelesaikan To-Do' }}
                            </div>
                            @if($log->description && $log->source !== 'login')
                                <div style="font-size: 0.8rem; color: var(--text-muted);">
                                    "{{ $log->description }}"
                                </div>
                            @endif
                            <div style="font-size: 0.75rem; color: var(--text-muted); margin-top: 0.15rem; display: flex; align-items: center; gap: 0.25rem;">
                                <i data-lucide="calendar" style="width: 12px; height: 12px;"></i>
                                {{ $log->activity_date->format('d M Y') }}
                            </div>
                        </div>
                    </div>
                    <span class="xp-chip">+{{ $log->xp }} XP</span>
                </div>
            @endforeach
        </div>
    @else
        <div style="text-align: center; padding: 2rem; color: var(--text-muted); font-size: 0.9rem;">
            Belum ada riwayat XP tercatat.
        </div>
    @endif
</div>
@endsection

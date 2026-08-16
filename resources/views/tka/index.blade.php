@extends('layouts.app')

@section('title', 'TKA Analisis')

@section('content')
<style>
    .header-bar {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 2rem;
        flex-wrap: wrap;
        gap: 1rem;
    }

    .header-bar h1 {
        font-size: 1.5rem;
        font-weight: 800;
        color: var(--primary-deep);
        letter-spacing: -0.02em;
    }

    .header-bar p {
        color: var(--text-muted);
        font-size: 0.925rem;
        margin-top: 0.25rem;
    }

    .summary-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
        gap: 1.25rem;
        margin-bottom: 2.25rem;
    }

    .summary-card {
        background-color: var(--card-bg);
        border: 1px solid var(--border-color);
        border-radius: var(--radius);
        padding: 1.35rem 1.5rem;
        box-shadow: var(--shadow-card);
        transition: var(--transition-smooth);
        min-width: 0;
    }

    .summary-card:hover {
        transform: translateY(-2px);
        box-shadow: 0 12px 24px -4px rgba(15, 23, 42, 0.08);
    }

    .summary-val {
        font-size: 1.45rem;
        font-weight: 800;
        color: var(--text-main);
        line-height: 1.2;
        word-wrap: break-word;
    }

    .summary-lbl {
        font-size: 0.8rem;
        color: var(--text-muted);
        font-weight: 800;
        margin-top: 0.25rem;
        text-transform: uppercase;
        letter-spacing: 0.04em;
    }

    .summary-meta {
        font-size: 0.8rem;
        color: var(--text-muted);
        margin-top: 0.35rem;
        display: flex;
        align-items: center;
        gap: 0.3rem;
    }

    .chart-container-box {
        background-color: var(--card-bg);
        border: 1px solid var(--border-color);
        border-radius: var(--radius);
        padding: 1.75rem;
        box-shadow: var(--shadow-card);
        margin-bottom: 2rem;
        min-width: 0;
    }

    .chart-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 1.5rem;
        flex-wrap: wrap;
        gap: 1rem;
    }

    .chart-title {
        font-size: 1.15rem;
        font-weight: 800;
        color: var(--primary-deep);
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }

    .chart-canvas-box {
        position: relative;
        width: 100%;
        height: 340px;
    }

    .grid-2col {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 1.75rem;
        margin-bottom: 2rem;
    }

    .table-history {
        width: 100%;
        border-collapse: collapse;
    }

    .table-history th, .table-history td {
        padding: 1.1rem 1rem;
        border-bottom: 1px solid var(--border-color);
        text-align: left;
    }

    .table-history th {
        font-size: 0.775rem;
        text-transform: uppercase;
        color: var(--text-muted);
        font-weight: 800;
        letter-spacing: 0.05em;
        background-color: #f8fafc;
    }

    .table-history tr:hover td {
        background-color: #f8fafc;
    }

    .notice-box {
        background-color: #f0f9ff;
        border: 1px solid #bae6fd;
        color: #0369a1;
        padding: 0.875rem 1.25rem;
        border-radius: var(--radius-sm);
        font-size: 0.875rem;
        font-weight: 600;
        margin-bottom: 1.25rem;
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }

    .badge-sub {
        display: inline-block;
        padding: 0.25rem 0.55rem;
        font-size: 0.775rem;
        font-weight: 700;
        border-radius: 6px;
        background-color: #f1f5f9;
        color: #475569;
        margin-right: 0.3rem;
        margin-bottom: 0.3rem;
    }

    @media (max-width: 900px) {
        .grid-2col {
            grid-template-columns: 1fr;
        }
        .header-bar {
            flex-direction: column;
            align-items: flex-start;
            gap: 1rem;
        }
    }

    @media (max-width: 768px) {
        .chart-container-box {
            padding: 1.25rem;
        }
        .chart-canvas-box {
            height: 260px;
        }
        .summary-grid {
            grid-template-columns: repeat(2, 1fr);
            gap: 0.85rem;
        }
    }

    @media (max-width: 480px) {
        .summary-grid {
            grid-template-columns: repeat(2, 1fr);
            gap: 0.75rem;
        }
        .summary-card {
            padding: 1rem;
        }
        .summary-val {
            font-size: 1.2rem;
        }
    }
</style>

<!-- Header Bar -->
<div class="header-bar">
    <div>
        <h1>TKA Analisis</h1>
        <p>Pantau perkembangan nilai TKA kamu dari setiap tryout.</p>
    </div>
    <a href="{{ route('tka.create') }}" class="btn btn-primary">
        <i data-lucide="plus"></i>
        Tambah Hasil TKA
    </a>
</div>

@if($totalTryouts > 0)
    <!-- Summary Cards -->
    <div class="summary-grid">
        <div class="summary-card">
            <div class="summary-val" style="color: var(--primary);">{{ $latestTryout->name }}</div>
            <div class="summary-lbl">Tryout Terakhir</div>
            <div class="summary-meta">
                <i data-lucide="calendar" style="width: 13px; height: 13px;"></i>
                {{ $latestTryout->date->format('d M Y') }}
            </div>
        </div>
        <div class="summary-card">
            <div class="summary-val">{{ $totalTryouts }}</div>
            <div class="summary-lbl">Jumlah Tryout</div>
            <div class="summary-meta">Total Hasil TKA</div>
        </div>
        <div class="summary-card">
            @if($highestSubject)
                <div class="summary-val" style="color: #10b981;">{{ number_format($highestSubject['score'], 2) }}</div>
                <div class="summary-lbl">Nilai Tertinggi</div>
                <div class="summary-meta">
                    <i data-lucide="trophy" style="width: 14px; height: 14px; color: #10b981;"></i>
                    {{ $highestSubject['name'] }}
                </div>
            @else
                <div class="summary-val">-</div>
                <div class="summary-lbl">Nilai Tertinggi</div>
                <div class="summary-meta">-</div>
            @endif
        </div>
        <div class="summary-card">
            @if($lowestSubject)
                <div class="summary-val" style="color: #ef4444;">{{ number_format($lowestSubject['score'], 2) }}</div>
                <div class="summary-lbl">Nilai Terendah</div>
                <div class="summary-meta">
                    <i data-lucide="alert-circle" style="width: 14px; height: 14px; color: #ef4444;"></i>
                    {{ $lowestSubject['name'] }}
                </div>
            @else
                <div class="summary-val">-</div>
                <div class="summary-lbl">Nilai Terendah</div>
                <div class="summary-meta">-</div>
            @endif
        </div>
    </div>

    @if($totalTryouts === 1)
        <div class="notice-box">
            <i data-lucide="info" style="width: 18px; height: 18px;"></i>
            Baru ada 1 hasil TKA. Tambahkan hasil TKA berikutnya untuk melihat perkembangan dari waktu ke waktu.
        </div>
    @endif

    <!-- GRAPH 1 — Perkembangan Nilai TKA -->
    <div class="chart-container-box">
        <div class="chart-header">
            <div class="chart-title">
                <i data-lucide="line-chart" style="width: 20px; height: 20px; color: var(--primary);"></i>
                Perkembangan Nilai TKA
            </div>
            <div style="display: flex; align-items: center; gap: 0.5rem;">
                <label for="tkaSubjectFilter" style="font-size: 0.875rem; font-weight: 700; color: var(--text-muted);">Mata Pelajaran:</label>
                <select id="tkaSubjectFilter" class="form-select" style="padding: 0.4rem 0.8rem; font-size: 0.875rem; width: auto;" onchange="updateTKATrendChart(this.value)">
                    <option value="ALL">-- Semua Mapel --</option>
                    @foreach($allSubjectNames as $sName)
                        <option value="{{ $sName }}">{{ $sName }}</option>
                    @endforeach
                </select>
            </div>
        </div>
        <div class="chart-canvas-box">
            <canvas id="tkaTrendChart"></canvas>
        </div>
    </div>

    <!-- 2 Column Section: GRAPH 2 & Subtes Terendah / Perubahan -->
    <div class="grid-2col">
        <!-- GRAPH 2 — Nilai TKA Terbaru (Horizontal Bar Chart) -->
        <div class="chart-container-box" style="margin-bottom: 0;">
            <div class="chart-header">
                <div class="chart-title">
                    <i data-lucide="bar-chart-3" style="width: 20px; height: 20px; color: var(--primary);"></i>
                    Nilai TKA Terbaru ({{ $latestTryout->name }})
                </div>
            </div>
            <div class="chart-canvas-box" style="height: 320px;">
                <canvas id="tkaLatestBarChart"></canvas>
            </div>
        </div>

        <!-- Subtes yang Perlu Diperhatikan & Perubahan & Trend Highlight -->
        <div>
            <!-- Areas to Watch -->
            <div class="card" style="margin-bottom: 1.5rem;">
                <h3 style="font-size: 1.15rem; font-weight: 800; margin-bottom: 1rem; color: var(--primary-deep); display: flex; align-items: center; gap: 0.5rem;">
                    <i data-lucide="alert-circle" style="width: 18px; height: 18px; color: var(--warning);"></i>
                    Perlu Diperhatikan
                </h3>
                <p style="font-size: 0.825rem; color: var(--text-muted); margin-bottom: 1rem;">
                    Nilai terendah pada hasil TKA terbaru:
                </p>
                <div style="display: flex; flex-direction: column; gap: 0.65rem;">
                    @foreach($areasToWatch as $item)
                        <div style="display: flex; justify-content: space-between; align-items: center; padding: 0.7rem 0.9rem; background-color: #f8fafc; border: 1px solid var(--border-color); border-radius: 10px;">
                            <span style="font-weight: 700; font-size: 0.875rem; color: var(--text-main);">{{ $item['name'] }}</span>
                            <span style="font-weight: 800; font-size: 0.95rem; color: var(--primary);">{{ number_format($item['score'], 2) }}</span>
                        </div>
                    @endforeach
                </div>
            </div>

            <!-- Highlights: Peningkatan & Penurunan Terbesar -->
            @if($previousTryout)
                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1rem; margin-bottom: 1.5rem;">
                    @if($biggestIncrease)
                        <div style="background-color: #ecfdf5; border: 1px solid #a7f3d0; padding: 1rem; border-radius: 12px;">
                            <div style="font-size: 0.775rem; font-weight: 800; color: #047857; text-transform: uppercase; display: flex; align-items: center; gap: 0.3rem;">
                                <i data-lucide="trending-up" style="width: 15px; height: 15px;"></i>
                                Peningkatan Terbesar
                            </div>
                            <div style="font-weight: 800; font-size: 0.95rem; color: #065f46; margin-top: 0.25rem;">{{ $biggestIncrease['name'] }}</div>
                            <div style="font-size: 0.825rem; color: #047857; margin-top: 0.2rem;">
                                {{ number_format($biggestIncrease['previous'], 2) }} → {{ number_format($biggestIncrease['current'], 2) }}
                                <strong style="font-size: 0.9rem;">(+{{ number_format($biggestIncrease['diff'], 2) }})</strong>
                            </div>
                        </div>
                    @endif

                    @if($biggestDecrease)
                        <div style="background-color: #fef2f2; border: 1px solid #fecaca; padding: 1rem; border-radius: 12px;">
                            <div style="font-size: 0.775rem; font-weight: 800; color: #b91c1c; text-transform: uppercase; display: flex; align-items: center; gap: 0.3rem;">
                                <i data-lucide="trending-down" style="width: 15px; height: 15px;"></i>
                                Penurunan Terbesar
                            </div>
                            <div style="font-weight: 800; font-size: 0.95rem; color: #991b1b; margin-top: 0.25rem;">{{ $biggestDecrease['name'] }}</div>
                            <div style="font-size: 0.825rem; color: #b91c1c; margin-top: 0.2rem;">
                                {{ number_format($biggestDecrease['previous'], 2) }} → {{ number_format($biggestDecrease['current'], 2) }}
                                <strong style="font-size: 0.9rem;">({{ number_format($biggestDecrease['diff'], 2) }})</strong>
                            </div>
                        </div>
                    @endif
                </div>
            @endif

            <!-- Perubahan dari Tryout Sebelumnya -->
            @if($previousTryout && count($subjectChanges) > 0)
                <div class="card" style="margin-bottom: 0;">
                    <h3 style="font-size: 1.15rem; font-weight: 800; margin-bottom: 1rem; color: var(--primary-deep); display: flex; align-items: center; gap: 0.5rem;">
                        <i data-lucide="trending-up" style="width: 18px; height: 18px; color: var(--primary);"></i>
                        Perubahan dari Tryout Sebelumnya
                    </h3>
                    <div style="display: flex; flex-direction: column; gap: 0.5rem;">
                        @foreach($subjectChanges as $change)
                            <div style="display: flex; justify-content: space-between; align-items: center; font-size: 0.85rem; padding: 0.35rem 0; border-bottom: 1px dashed var(--border-color);">
                                <span style="font-weight: 600; color: var(--text-main);">{{ $change['name'] }}</span>
                                <div style="font-weight: 700; display: flex; align-items: center; gap: 0.5rem;">
                                    <span style="color: var(--text-muted); font-size: 0.8rem;">{{ number_format($change['previous'], 2) }} → {{ number_format($change['current'], 2) }}</span>
                                    <span style="color: {{ $change['diff'] > 0 ? '#10b981' : ($change['diff'] < 0 ? '#ef4444' : 'var(--text-muted)') }}; font-weight: 800; min-width: 55px; text-align: right;">
                                        {{ $change['diff'] > 0 ? '+' . number_format($change['diff'], 2) : number_format($change['diff'], 2) }}
                                    </span>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif
        </div>
    </div>

    <!-- Table Riwayat TKA -->
    <div class="card">
        <h3 style="font-size: 1.15rem; font-weight: 800; margin-bottom: 1.25rem; color: var(--primary-deep); display: flex; align-items: center; gap: 0.5rem;">
            <i data-lucide="history" style="width: 20px; height: 20px; color: var(--primary);"></i>
            Riwayat Hasil TKA
        </h3>

        <div class="table-responsive-wrapper">
            <table class="table-history">
                <thead>
                    <tr>
                        <th>Nama Tryout</th>
                        <th>Tanggal</th>
                        <th>Rincian Nilai Mata Pelajaran</th>
                        <th style="text-align: right;">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($historyTryouts as $to)
                        <tr>
                            <td style="width: 25%;">
                                <div style="font-weight: 800; font-size: 1rem; color: var(--text-main);">
                                    {{ $to->name }}
                                </div>
                                @if($to->notes)
                                    <div style="font-size: 0.8rem; color: var(--text-muted); margin-top: 0.2rem;">
                                        {{ $to->notes }}
                                    </div>
                                @endif
                            </td>
                            <td style="font-weight: 600; font-size: 0.875rem; color: var(--text-muted); width: 18%;">
                                <span style="display: inline-flex; align-items: center; gap: 0.25rem;">
                                    <i data-lucide="calendar" style="width: 14px; height: 14px;"></i>
                                    {{ $to->date->format('d M Y') }}
                                </span>
                            </td>
                            <td>
                                <div style="display: flex; flex-wrap: wrap;">
                                    @foreach($to->subjectScores as $ss)
                                        <span class="badge-sub">
                                            {{ $ss->subject_name }}: <strong style="color: var(--primary);">{{ number_format($ss->score, 2) }}</strong>
                                        </span>
                                    @endforeach
                                </div>
                            </td>
                            <td style="text-align: right; white-space: nowrap; width: 20%;">
                                <a href="{{ route('tka.show', $to) }}" class="btn btn-secondary btn-sm">
                                    <i data-lucide="eye"></i>
                                    Detail
                                </a>
                                <a href="{{ route('tka.edit', $to) }}" class="btn btn-secondary btn-sm">
                                    <i data-lucide="pencil"></i>
                                    Edit
                                </a>
                                <form action="{{ route('tka.destroy', $to) }}" method="POST" style="display: inline;" class="form-confirm" data-confirm-title="Hapus Hasil TKA?" data-confirm-text="Hapus hasil TKA {{ $to->name }}? Semua nilai mata pelajaran dalam hasil ini juga akan dihapus.">
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
    </div>

@else
    <!-- EMPTY STATE -->
    <div class="card" style="text-align: center; padding: 4rem 2rem; border: 2px dashed var(--border-color);">
        <div style="margin-bottom: 1rem; display: inline-block;">
            <i data-lucide="graduation-cap" style="width: 44px; height: 44px; color: var(--primary);"></i>
        </div>
        <h2 style="font-size: 1.35rem; font-weight: 800; margin-bottom: 0.5rem; color: var(--text-main);">
            Belum ada hasil TKA
        </h2>
        <p style="color: var(--text-muted); font-size: 0.925rem; max-width: 480px; margin: 0 auto 1.75rem auto; line-height: 1.6;">
            Tambahkan hasil tryout pertama kamu untuk mulai melihat perkembangan nilai.
        </p>
        <a href="{{ route('tka.create') }}" class="btn btn-primary">
            <i data-lucide="plus"></i>
            Tambah Hasil TKA Pertama
        </a>
    </div>
@endif

<!-- Chart.js Scripts -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
@if($totalTryouts > 0)
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const labels = {!! json_encode($chartLabels) !!};
        const subjectTrends = {!! json_encode($subjectTrends) !!};
        const allSubjects = {!! json_encode($allSubjectNames) !!};
        const colors = ['#2563eb', '#10b981', '#f59e0b', '#ef4444', '#8b5cf6', '#06b6d4', '#ec4899', '#3b82f6', '#14b8a6'];

        // 1. TKA Trend Line Chart
        const ctx1 = document.getElementById('tkaTrendChart').getContext('2d');

        function generateDatasets(selectedSubject) {
            let datasets = [];
            let colorIdx = 0;

            allSubjects.forEach(sName => {
                if (selectedSubject === 'ALL' || selectedSubject === sName) {
                    const color = colors[colorIdx % colors.length];
                    datasets.push({
                        label: sName,
                        data: subjectTrends[sName],
                        borderColor: color,
                        borderWidth: selectedSubject === 'ALL' ? 2 : 3.5,
                        backgroundColor: color,
                        tension: 0.3,
                        spanGaps: true,
                        pointRadius: selectedSubject === 'ALL' ? 4 : 6,
                        pointHoverRadius: 8
                    });
                }
                colorIdx++;
            });
            return datasets;
        }

        window.tkaChart = new Chart(ctx1, {
            type: 'line',
            data: {
                labels: labels,
                datasets: generateDatasets('ALL')
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        display: true,
                        position: 'bottom',
                        labels: { font: { family: 'Plus Jakarta Sans', size: 11, weight: '600' }, boxWidth: 12 }
                    },
                    tooltip: {
                        backgroundColor: '#0f172a',
                        titleFont: { family: 'Plus Jakarta Sans', size: 13, weight: 'bold' },
                        bodyFont: { family: 'Plus Jakarta Sans', size: 12 },
                        padding: 10,
                        cornerRadius: 8
                    }
                },
                scales: {
                    x: { grid: { display: false }, ticks: { font: { family: 'Plus Jakarta Sans', size: 12, weight: '600' }, color: '#64748b' } },
                    y: { min: 0, max: 100, grid: { color: '#e2e8f0' }, ticks: { stepSize: 20, font: { family: 'Plus Jakarta Sans', size: 12, weight: '600' }, color: '#64748b' } }
                }
            }
        });

        window.updateTKATrendChart = function(subjectName) {
            window.tkaChart.data.datasets = generateDatasets(subjectName);
            window.tkaChart.update();
        };

        // 2. Latest TKA Subject Bar Chart
        const latestScoresList = {!! json_encode($latestSubjectScores) !!};
        const barLabels = latestScoresList.map(i => i.name);
        const barData = latestScoresList.map(i => i.score);

        const ctx2 = document.getElementById('tkaLatestBarChart').getContext('2d');
        new Chart(ctx2, {
            type: 'bar',
            data: {
                labels: barLabels,
                datasets: [{
                    label: 'Nilai Mapel',
                    data: barData,
                    backgroundColor: 'rgba(37, 99, 235, 0.85)',
                    borderRadius: 6,
                    borderSkipped: false
                }]
            },
            options: {
                indexAxis: 'y',
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: { display: false },
                    tooltip: {
                        backgroundColor: '#0f172a',
                        titleFont: { family: 'Plus Jakarta Sans', size: 13, weight: 'bold' },
                        bodyFont: { family: 'Plus Jakarta Sans', size: 12 },
                        padding: 10,
                        cornerRadius: 8
                    }
                },
                scales: {
                    x: { min: 0, max: 100, grid: { color: '#e2e8f0' }, ticks: { stepSize: 20, font: { family: 'Plus Jakarta Sans', size: 11, weight: '600' }, color: '#64748b' } },
                    y: { grid: { display: false }, ticks: { font: { family: 'Plus Jakarta Sans', size: 11, weight: '600' }, color: '#475569' } }
                }
            }
        });
    });
</script>
@endif
@endsection

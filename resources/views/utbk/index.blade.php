@extends('layouts.app')

@section('title', 'UTBK Score Tracker')

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
        font-size: 1.75rem;
        font-weight: 800;
        color: var(--text-main);
        line-height: 1.2;
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
            font-size: 1.35rem;
        }
    }
</style>

<!-- Header Bar -->
<div class="header-bar">
    <div>
        <h1>UTBK Score Tracker</h1>
        <p>Pantau perkembangan hasil tryout UTBK kamu dari waktu ke waktu.</p>
    </div>
    <a href="{{ route('utbk.create') }}" class="btn btn-primary">
        <i data-lucide="plus"></i>
        Tambah Hasil Tryout
    </a>
</div>

@if($totalTryouts > 0)
    <!-- Summary Cards -->
    <div class="summary-grid">
        <div class="summary-card">
            <div class="summary-val" style="color: var(--primary);">{{ number_format($latestScore, 2) }}</div>
            <div class="summary-lbl">Nilai Terakhir</div>
            <div class="summary-meta">Tryout terbaru ({{ $latestTryout->name }})</div>
        </div>
        <div class="summary-card">
            <div class="summary-val" style="color: #10b981;">{{ number_format($highestScore, 2) }}</div>
            <div class="summary-lbl">Nilai Tertinggi</div>
            <div class="summary-meta">Best Score</div>
        </div>
        <div class="summary-card">
            @if($scoreChange !== null)
                <div class="summary-val" style="color: {{ $scoreChange >= 0 ? '#10b981' : '#ef4444' }}; display: flex; align-items: center; gap: 0.25rem;">
                    @if($scoreChange >= 0)
                        <i data-lucide="trending-up" style="width: 20px; height: 20px;"></i>
                        +{{ number_format($scoreChange, 2) }}
                    @else
                        <i data-lucide="trending-down" style="width: 20px; height: 20px;"></i>
                        {{ number_format($scoreChange, 2) }}
                    @endif
                </div>
                <div class="summary-lbl">Perubahan Terakhir</div>
                <div class="summary-meta">dari tryout sebelumnya</div>
            @else
                <div class="summary-val" style="color: var(--text-muted);">-</div>
                <div class="summary-lbl">Perubahan Terakhir</div>
                <div class="summary-meta">butuh 2+ tryout</div>
            @endif
        </div>
        <div class="summary-card">
            <div class="summary-val">{{ $totalTryouts }}</div>
            <div class="summary-lbl">Jumlah Tryout</div>
            <div class="summary-meta">Total Tryout Tercatat</div>
        </div>
    </div>

    @if($totalTryouts === 1)
        <div class="notice-box">
            <i data-lucide="info" style="width: 18px; height: 18px;"></i>
            Baru ada 1 hasil tryout. Tambahkan tryout berikutnya untuk melihat grafik perkembangan & tren perbandingan!
        </div>
    @endif

    <!-- GRAPH 1 — Perkembangan Nilai Keseluruhan -->
    <div class="chart-container-box">
        <div class="chart-header">
            <div class="chart-title">
                <i data-lucide="line-chart" style="width: 20px; height: 20px; color: var(--primary);"></i>
                Perkembangan Nilai Keseluruhan
            </div>
        </div>
        <div class="chart-canvas-box">
            <canvas id="overallChart"></canvas>
        </div>
    </div>

    <!-- GRAPH 2 — Perkembangan 7 Subtes -->
    <div class="chart-container-box">
        <div class="chart-header">
            <div class="chart-title">
                <i data-lucide="bar-chart-2" style="width: 20px; height: 20px; color: var(--primary);"></i>
                Perkembangan Nilai per Subtes
            </div>
            <div style="display: flex; align-items: center; gap: 0.5rem;">
                <label for="subtestSelectFilter" style="font-size: 0.875rem; font-weight: 700; color: var(--text-muted);">Subtes:</label>
                <select id="subtestSelectFilter" class="form-select" style="padding: 0.4rem 0.8rem; font-size: 0.875rem; width: auto;" onchange="updateSubtestChart(this.value)">
                    <option value="ALL">-- Semua Subtes --</option>
                    @foreach($subtests as $key => $label)
                        <option value="{{ $key }}">{{ $label }}</option>
                    @endforeach
                </select>
            </div>
        </div>
        <div class="chart-canvas-box" style="height: 360px;">
            <canvas id="subtestTrendChart"></canvas>
        </div>
    </div>

    <!-- 2 Column Section: GRAPH 3 & Subtes Terendah / Perubahan -->
    <div class="grid-2col">
        <!-- GRAPH 3 — Nilai Subtes Terbaru -->
        <div class="chart-container-box" style="margin-bottom: 0;">
            <div class="chart-header">
                <div class="chart-title">
                    <i data-lucide="bar-chart-3" style="width: 20px; height: 20px; color: var(--primary);"></i>
                    Nilai Subtes Terbaru ({{ $latestTryout->name }})
                </div>
            </div>
            <div class="chart-canvas-box" style="height: 320px;">
                <canvas id="latestSubtestBarChart"></canvas>
            </div>
        </div>

        <!-- Subtes yang Perlu Diperhatikan & Perubahan -->
        <div>
            <!-- Areas to Watch -->
            <div class="card" style="margin-bottom: 1.5rem;">
                <h3 style="font-size: 1.15rem; font-weight: 800; margin-bottom: 1rem; color: var(--primary-deep); display: flex; align-items: center; gap: 0.5rem;">
                    <i data-lucide="alert-circle" style="width: 18px; height: 18px; color: var(--warning);"></i>
                    Subtes yang Perlu Diperhatikan
                </h3>
                <p style="font-size: 0.825rem; color: var(--text-muted); margin-bottom: 1rem;">
                    3 Subtes dengan nilai terendah pada tryout terbaru:
                </p>
                <div style="display: flex; flex-direction: column; gap: 0.75rem;">
                    @foreach($areasToWatch as $key => $item)
                        <div style="display: flex; justify-content: space-between; align-items: center; padding: 0.75rem 1rem; background-color: #f8fafc; border: 1px solid var(--border-color); border-radius: 10px;">
                            <span style="font-weight: 700; font-size: 0.9rem; color: var(--text-main);">{{ $item['label'] }}</span>
                            <span style="font-weight: 800; font-size: 1rem; color: var(--primary);">{{ number_format($item['score'], 2) }}</span>
                        </div>
                    @endforeach
                </div>
            </div>

            <!-- Perubahan dari Tryout Sebelumnya -->
            @if($previousTryout && count($subtestChanges) > 0)
                <div class="card" style="margin-bottom: 0;">
                    <h3 style="font-size: 1.15rem; font-weight: 800; margin-bottom: 1rem; color: var(--primary-deep); display: flex; align-items: center; gap: 0.5rem;">
                        <i data-lucide="trending-up" style="width: 18px; height: 18px; color: var(--primary);"></i>
                        Perubahan dari Tryout Sebelumnya
                    </h3>
                    <div style="display: flex; flex-direction: column; gap: 0.6rem;">
                        @foreach($subtestChanges as $key => $change)
                            <div style="display: flex; justify-content: space-between; align-items: center; font-size: 0.875rem; padding: 0.4rem 0; border-bottom: 1px dashed var(--border-color);">
                                <span style="font-weight: 600; color: var(--text-main);">{{ $change['label'] }}</span>
                                <div style="font-weight: 700; display: flex; align-items: center; gap: 0.5rem;">
                                    <span style="color: var(--text-muted); font-size: 0.8rem;">{{ number_format($change['previous'], 0) }} → {{ number_format($change['current'], 0) }}</span>
                                    <span style="color: {{ $change['diff'] > 0 ? '#10b981' : ($change['diff'] < 0 ? '#ef4444' : 'var(--text-muted)') }}; font-weight: 800; min-width: 55px; text-align: right;">
                                        {{ $change['diff'] > 0 ? '+' . number_format($change['diff'], 0) : number_format($change['diff'], 0) }}
                                    </span>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif
        </div>
    </div>

    <!-- Table Riwayat Tryout -->
    <div class="card">
        <h3 style="font-size: 1.15rem; font-weight: 800; margin-bottom: 1.25rem; color: var(--primary-deep); display: flex; align-items: center; gap: 0.5rem;">
            <i data-lucide="history" style="width: 20px; height: 20px; color: var(--primary);"></i>
            Riwayat Tryout
        </h3>

        <div class="table-responsive-wrapper">
            <table class="table-history">
                <thead>
                    <tr>
                        <th>Nama Tryout</th>
                        <th>Tanggal</th>
                        <th>Nilai Keseluruhan</th>
                        <th style="text-align: right;">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($historyTryouts as $to)
                        <tr>
                            <td>
                                <div style="font-weight: 800; font-size: 1rem; color: var(--text-main);">
                                    {{ $to->name }}
                                </div>
                                @if($to->notes)
                                    <div style="font-size: 0.8rem; color: var(--text-muted); margin-top: 0.2rem;">
                                        {{ $to->notes }}
                                    </div>
                                @endif
                            </td>
                            <td style="font-weight: 600; font-size: 0.875rem; color: var(--text-muted);">
                                <span style="display: inline-flex; align-items: center; gap: 0.25rem;">
                                    <i data-lucide="calendar" style="width: 14px; height: 14px;"></i>
                                    {{ $to->date->format('d M Y') }}
                                </span>
                            </td>
                            <td>
                                <span style="font-weight: 800; font-size: 1.1rem; color: var(--primary);">
                                    {{ number_format($to->overall_score, 2) }}
                                </span>
                            </td>
                            <td style="text-align: right; white-space: nowrap;">
                                <a href="{{ route('utbk.show', $to) }}" class="btn btn-secondary btn-sm">
                                    <i data-lucide="eye"></i>
                                    Detail
                                </a>
                                <a href="{{ route('utbk.edit', $to) }}" class="btn btn-secondary btn-sm">
                                    <i data-lucide="pencil"></i>
                                    Edit
                                </a>
                                <form action="{{ route('utbk.destroy', $to) }}" method="POST" style="display: inline;" class="form-confirm" data-confirm-title="Hapus Hasil Tryout UTBK?" data-confirm-text="Hapus hasil tryout {{ $to->name }}? Data nilai dan seluruh nilai subtes dari tryout ini akan dihapus.">
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
            <i data-lucide="line-chart" style="width: 44px; height: 44px; color: var(--primary);"></i>
        </div>
        <h2 style="font-size: 1.35rem; font-weight: 800; margin-bottom: 0.5rem; color: var(--text-main);">
            Belum ada hasil tryout.
        </h2>
        <p style="color: var(--text-muted); font-size: 0.925rem; max-width: 480px; margin: 0 auto 1.75rem auto; line-height: 1.6;">
            Mulai catat hasil tryout UTBK kamu untuk melihat perkembangan nilai dari waktu ke waktu.
        </p>
        <a href="{{ route('utbk.create') }}" class="btn btn-primary">
            <i data-lucide="plus"></i>
            Tambah Hasil Tryout Pertama
        </a>
    </div>
@endif

<!-- Chart.js Scripts -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
@if($totalTryouts > 0)
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const labels = {!! json_encode($overallLabels) !!};
        const overallScores = {!! json_encode($overallScores) !!};

        // 1. Overall Score Chart
        const ctx1 = document.getElementById('overallChart').getContext('2d');
        const grad1 = ctx1.createLinearGradient(0, 0, 0, 320);
        grad1.addColorStop(0, 'rgba(37, 99, 235, 0.25)');
        grad1.addColorStop(1, 'rgba(37, 99, 235, 0.0)');

        new Chart(ctx1, {
            type: 'line',
            data: {
                labels: labels,
                datasets: [{
                    label: 'Nilai Keseluruhan',
                    data: overallScores,
                    borderColor: '#2563eb',
                    borderWidth: 3.5,
                    backgroundColor: grad1,
                    fill: true,
                    tension: 0.3,
                    pointBackgroundColor: '#ffffff',
                    pointBorderColor: '#2563eb',
                    pointBorderWidth: 3,
                    pointRadius: 6,
                    pointHoverRadius: 9
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: { display: false },
                    tooltip: {
                        backgroundColor: '#0f172a',
                        titleFont: { family: 'Plus Jakarta Sans', size: 14, weight: 'bold' },
                        bodyFont: { family: 'Plus Jakarta Sans', size: 13 },
                        padding: 12,
                        cornerRadius: 10
                    }
                },
                scales: {
                    x: { grid: { display: false }, ticks: { font: { family: 'Plus Jakarta Sans', size: 12, weight: '600' }, color: '#64748b' } },
                    y: { min: 0, max: 1000, grid: { color: '#e2e8f0' }, ticks: { stepSize: 200, font: { family: 'Plus Jakarta Sans', size: 12, weight: '600' }, color: '#64748b' } }
                }
            }
        });

        // 2. Subtest Trend Chart
        const subtestTrends = {!! json_encode($subtestTrends) !!};
        const subtestConfig = {!! json_encode($subtests) !!};
        const colors = ['#2563eb', '#10b981', '#f59e0b', '#ef4444', '#8b5cf6', '#06b6d4', '#ec4899'];

        const ctx2 = document.getElementById('subtestTrendChart').getContext('2d');

        function generateDatasets(selectedKey) {
            let datasets = [];
            let colorIdx = 0;

            for (const [key, label] of Object.entries(subtestConfig)) {
                if (selectedKey === 'ALL' || selectedKey === key) {
                    const color = colors[colorIdx % colors.length];
                    datasets.push({
                        label: label,
                        data: subtestTrends[key],
                        borderColor: color,
                        borderWidth: selectedKey === 'ALL' ? 2 : 3.5,
                        backgroundColor: color,
                        tension: 0.3,
                        pointRadius: selectedKey === 'ALL' ? 4 : 6,
                        pointHoverRadius: 8
                    });
                }
                colorIdx++;
            }
            return datasets;
        }

        window.subtestChart = new Chart(ctx2, {
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
                    y: { min: 0, max: 1000, grid: { color: '#e2e8f0' }, ticks: { stepSize: 200, font: { family: 'Plus Jakarta Sans', size: 12, weight: '600' }, color: '#64748b' } }
                }
            }
        });

        window.updateSubtestChart = function(key) {
            window.subtestChart.data.datasets = generateDatasets(key);
            window.subtestChart.update();
        };

        // 3. Latest Subtest Comparison Horizontal Bar Chart
        const latestScoresObj = {!! json_encode($latestSubtestScores) !!};
        const barLabels = [];
        const barData = [];
        for (const [key, item] of Object.entries(latestScoresObj)) {
            barLabels.push(item.label);
            barData.push(item.score);
        }

        const ctx3 = document.getElementById('latestSubtestBarChart').getContext('2d');
        new Chart(ctx3, {
            type: 'bar',
            data: {
                labels: barLabels,
                datasets: [{
                    label: 'Skor Subtes',
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
                    x: { min: 0, max: 1000, grid: { color: '#e2e8f0' }, ticks: { stepSize: 200, font: { family: 'Plus Jakarta Sans', size: 11, weight: '600' }, color: '#64748b' } },
                    y: { grid: { display: false }, ticks: { font: { family: 'Plus Jakarta Sans', size: 11, weight: '600' }, color: '#475569' } }
                }
            }
        });
    });
</script>
@endif
@endsection

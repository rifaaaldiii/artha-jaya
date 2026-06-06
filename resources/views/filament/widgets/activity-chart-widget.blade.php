<x-filament-widgets::widget>
    <style>
        :root {
            --ac-bg: #ffffff;
            --ac-text: #111827;
            --ac-muted: #6b7280;
            --ac-border: #e5e7eb;
            --ac-shadow: rgba(0, 0, 0, 0.08);
            --ac-filter-bg: #f3f4f6;
            --ac-filter-active-bg: #ffffff;
            --ac-filter-active-text: #111827;
            --ac-filter-text: #6b7280;
            --ac-grid: rgba(107, 114, 128, 0.15);
        }

        .dark,
        [data-theme="dark"],
        .filament-theme-dark {
            --ac-bg: #181818;
            --ac-text: #f9fafb;
            --ac-muted: #9ca3af;
            --ac-border: #374151;
            --ac-shadow: rgba(0, 0, 0, 0.35);
            --ac-filter-bg: #262626;
            --ac-filter-active-bg: #374151;
            --ac-filter-active-text: #f9fafb;
            --ac-filter-text: #9ca3af;
            --ac-grid: rgba(156, 163, 175, 0.12);
        }

        .activity-chart-card {
            background: var(--ac-bg);
            border-radius: 16px;
            border: 1px solid var(--ac-border);
            box-shadow: 0 2px 8px var(--ac-shadow), 0 1px 3px var(--ac-shadow);
            overflow: hidden;
        }

        .activity-chart-header {
            display: flex;
            align-items: flex-start;
            justify-content: space-between;
            gap: 16px;
            padding: 24px 24px 8px;
        }

        .activity-chart-title {
            margin: 0;
            font-size: 1rem;
            font-weight: 600;
            color: var(--ac-text);
        }

        .activity-chart-subtitle {
            margin: 4px 0 0;
            font-size: 0.875rem;
            color: var(--ac-muted);
        }

        .activity-chart-filters {
            display: inline-flex;
            align-items: center;
            gap: 4px;
            padding: 4px;
            border-radius: 10px;
            background: var(--ac-filter-bg);
            flex-shrink: 0;
        }

        .activity-chart-filter {
            border: none;
            background: transparent;
            color: var(--ac-filter-text);
            font-size: 0.8125rem;
            font-weight: 500;
            padding: 8px 12px;
            border-radius: 8px;
            cursor: pointer;
            transition: background 0.2s, color 0.2s;
            white-space: nowrap;
        }

        .activity-chart-filter:hover {
            color: var(--ac-filter-active-text);
        }

        .activity-chart-filter.is-active {
            background: var(--ac-filter-active-bg);
            color: var(--ac-filter-active-text);
            box-shadow: 0 1px 2px var(--ac-shadow);
        }

        .activity-chart-body {
            padding: 8px 16px 24px;
            height: 320px;
        }

        .activity-chart-legend {
            display: flex;
            align-items: center;
            gap: 20px;
            padding: 0 24px 16px;
        }

        .activity-chart-legend-item {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            font-size: 0.8125rem;
            color: var(--ac-muted);
        }

        .activity-chart-legend-dot {
            width: 10px;
            height: 10px;
            border-radius: 9999px;
        }

        .activity-chart-legend-dot.produksi {
            background: #059669;
        }

        .activity-chart-legend-dot.jasa {
            background: #dc2626;
        }

        @media (max-width: 768px) {
            .activity-chart-header {
                flex-direction: column;
                align-items: stretch;
            }

            .activity-chart-filters {
                width: 100%;
                overflow-x: auto;
            }

            .activity-chart-body {
                height: 260px;
            }
        }
    </style>

    @php
        $chartId = 'activity-chart-' . $this->getId();
    @endphp

    <div class="activity-chart-card">
        <div class="activity-chart-header">
            <div>
                <h3 class="activity-chart-title">Activity</h3>
                <p class="activity-chart-subtitle">{{ $this->subtitle }}</p>
            </div>

            <div class="activity-chart-filters">
                <button
                    type="button"
                    wire:click="setFilter('3m')"
                    @class(['activity-chart-filter', 'is-active' => $filter === '3m'])
                >
                    3 Bulan
                </button>
                <button
                    type="button"
                    wire:click="setFilter('30d')"
                    @class(['activity-chart-filter', 'is-active' => $filter === '30d'])
                >
                    30 Hari
                </button>
                <button
                    type="button"
                    wire:click="setFilter('7d')"
                    @class(['activity-chart-filter', 'is-active' => $filter === '7d'])
                >
                    7 Hari
                </button>
            </div>
        </div>

        <div class="activity-chart-legend">
            <span class="activity-chart-legend-item">
                <span class="activity-chart-legend-dot produksi"></span>
                Produksi
            </span>
            <span class="activity-chart-legend-item">
                <span class="activity-chart-legend-dot jasa"></span>
                Jasa
            </span>
        </div>

        <div class="activity-chart-body">
            <canvas id="{{ $chartId }}" wire:ignore></canvas>
        </div>
    </div>

    @once
        <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.8/dist/chart.umd.min.js"></script>
    @endonce

    @script
    <script>
        let activityChart = null;

        const getActivityThemeColors = () => ({
            grid: getComputedStyle(document.documentElement).getPropertyValue('--ac-grid').trim() || 'rgba(107, 114, 128, 0.15)',
            muted: getComputedStyle(document.documentElement).getPropertyValue('--ac-muted').trim() || '#6b7280',
        });

        const renderActivityChart = () => {
            const canvas = document.getElementById(@js($chartId));

            if (!canvas || typeof Chart === 'undefined') {
                return;
            }

            const chartData = $wire.chartData;
            const { grid, muted } = getActivityThemeColors();
            const ctx = canvas.getContext('2d');

            if (activityChart) {
                activityChart.destroy();
            }

            const produksiGradient = ctx.createLinearGradient(0, 0, 0, 280);
            produksiGradient.addColorStop(0, 'rgba(5, 150, 105, 0.35)');
            produksiGradient.addColorStop(1, 'rgba(5, 150, 105, 0)');

            const jasaGradient = ctx.createLinearGradient(0, 0, 0, 280);
            jasaGradient.addColorStop(0, 'rgba(220, 38, 38, 0.28)');
            jasaGradient.addColorStop(1, 'rgba(220, 38, 38, 0)');

            activityChart = new Chart(canvas, {
                type: 'line',
                data: {
                    labels: chartData.labels,
                    datasets: [
                        {
                            label: 'Produksi',
                            data: chartData.produksi,
                            borderColor: '#059669',
                            backgroundColor: produksiGradient,
                            fill: true,
                            tension: 0.4,
                            pointRadius: 0,
                            pointHoverRadius: 4,
                            borderWidth: 2,
                        },
                        {
                            label: 'Jasa',
                            data: chartData.jasa,
                            borderColor: '#dc2626',
                            backgroundColor: jasaGradient,
                            fill: true,
                            tension: 0.4,
                            pointRadius: 0,
                            pointHoverRadius: 4,
                            borderWidth: 2,
                        },
                    ],
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    interaction: {
                        mode: 'index',
                        intersect: false,
                    },
                    plugins: {
                        legend: {
                            display: false,
                        },
                        tooltip: {
                            backgroundColor: 'rgba(17, 24, 39, 0.92)',
                            padding: 12,
                            cornerRadius: 8,
                        },
                    },
                    scales: {
                        x: {
                            grid: {
                                display: false,
                            },
                            ticks: {
                                color: muted,
                                maxRotation: 0,
                                autoSkip: true,
                                maxTicksLimit: 8,
                            },
                            border: {
                                display: false,
                            },
                        },
                        y: {
                            beginAtZero: true,
                            grid: {
                                color: grid,
                            },
                            ticks: {
                                color: muted,
                                precision: 0,
                            },
                            border: {
                                display: false,
                            },
                        },
                    },
                },
            });
        };

        renderActivityChart();

        $wire.watch('filter', () => {
            renderActivityChart();
        });
    </script>
    @endscript
</x-filament-widgets::widget>

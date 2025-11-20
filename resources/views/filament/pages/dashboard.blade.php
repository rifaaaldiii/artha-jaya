@php
    $formatNumber = fn ($value) => number_format((int) $value, 0, ',', '.');
@endphp

<x-filament-panels::page>
    <style>
        .dashboard-container {
            margin: 0;
            padding: 0;
        }
        .dashboard-space-y-6 > * + * {
            margin-top: 1.5rem;
        }
        .dashboard-grid {
            display: grid;
            gap: 1.5rem;
            grid-template-columns: 1fr;
        }
        @media (min-width: 1024px) {
            .dashboard-grid {
                grid-template-columns: 2fr 1fr;
            }
        }
        .dashboard-main {
            grid-column: 1 / -1;
        }
        @media (min-width: 1024px) {
            .dashboard-main {
                grid-column: span 2;
            }
        }
        .dashboard-card-welcome {
            border-radius: 2rem;
            background: linear-gradient(135deg, #0891b2 0%, #06b6d4 50%, #0ea5e9 100%);
            padding: 1.5rem;
            color: #fff;
            box-shadow: 0 10px 30px 0 rgba(0,0,0,0.08);
            margin-bottom: 20px;
        }
        .dashboard-row {
            display: flex;
            flex-direction: column;
            gap: 1.5rem;
        }
        @media (min-width: 1024px) {
            .dashboard-row {
                flex-direction: row;
                align-items: center;
                justify-content: space-between;
            }
        }
        .dashboard-card-welcome-title {
            margin: 0.25em 0 0 0;
            font-size: 2rem;
            font-weight: 600;
        }
        .dashboard-card-welcome-text {
            margin-top: 0.75rem;
            font-size: 1rem;
            color: rgba(255,255,255,0.8);
            margin-bottom: 0;
        }
        .dashboard-waktu-blok {
            padding: 1rem 1.5rem;
            text-align: right;
            min-width: 200px;
        }
        .dashboard-waktu-blok p {
            margin: 0;
            color: rgba(255,255,255,0.8);
        }
        .dashboard-waktu-blok-l1 {
            font-size: 11px;
            text-transform: uppercase;
            letter-spacing: 1px;
            color: rgba(255,255,255,0.7);
            font-weight: 700;
        }
        .dashboard-waktu-blok-l2 {
            font-size: 1.125rem;
            font-weight: 600;
            color: #fff;
        }

        /* Remove hardcoded light styles for activity card, rely on Filament theme classes */
        /*
        .dashboard-activity-card {
            border-radius: 1.25rem;
            border: 1px solid rgba(229, 231, 235, 0.8);
            background: #fff;
            padding: 1.25rem;
            box-shadow: 0 1px 8px 0 rgba(0,0,0,0.03);
        }
        */
        .dashboard-activity-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
        }
        .dashboard-activity-title {
            font-size: 1.12rem;
            font-weight: 600;
            margin-bottom: 2px;
            margin-top: 2px;
            color: #18181b;
        }
        .dashboard-activity-label {
            font-size: 0.8rem;
            color: #757979;
            margin-bottom: 0.25em;
        }
        .dashboard-activity-caption {
            color: #a3a3a3;
            font-size: 12px;
            margin-left: 0.5rem;
        }
        .dashboard-activity-content {
            margin-top: 1.25rem;
        }
    </style>

    <div class="dashboard-container">
        <div class="dashboard-space-y-6">
            <div class="dashboard-grid">
                <div class="dashboard-main">
                    <div class="dashboard-card-welcome">
                        <div class="dashboard-row">
                            <div>
                                <p style="font-size:14px; color:rgba(255,255,255,0.8);margin:0 0 1px 0;">Hallo</p>
                                <h2 class="dashboard-card-welcome-title">{{ auth()->user()->name }}</h2>
                                <p class="dashboard-card-welcome-text">
                                    Monitor progress produksi, Layanan Jasa, dan kesiapan tim secara real-time.
                                </p>
                            </div>
                            <div class="dashboard-waktu-blok">
                                <p class="dashboard-waktu-blok-l2">{{ $nowJakarta->translatedFormat('l, d F Y') }}</p>
                                <p style="font-size:13px;">{{ $nowJakarta->format('H:i (T)') }}</p>
                            </div>
                        </div>
                    </div>

                    <!-- Use filament/card style classes for dark & light mode -->
                    <div
                        class="dashboard-activity-card filament-card rounded-2xl border bg-white dark:bg-gray-900 dark:border-gray-800 p-5 shadow
                        "
                        style="border-radius:1.25rem; padding:1.25rem;"
                    >
                        <div class="dashboard-activity-header">
                            <div>
                                <h3 class="dashboard-activity-title">Recent Activity</h3>
                                <p class="dashboard-activity-label">Ringkasan Produksi &amp; Layanan</p>
                            </div>
                            <span class="dashboard-activity-caption">Data real-time</span>
                        </div>
                        <div class="dashboard-activity-content">
                            @livewire(\App\Filament\Widgets\ProductOverview::class)
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-filament-panels::page>

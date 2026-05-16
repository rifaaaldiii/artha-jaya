<x-filament-panels::page>
    <style>
        :root {
            --aj-md-bg: #ffffff;
            --aj-md-surface: #f9fafb;
            --aj-md-border: #e5e7eb;
            --aj-md-text: #111827;
            --aj-md-text-muted: #6b7280;
            --aj-md-primary: #059669;
            --aj-md-primary-hover: #047857;
            --aj-md-primary-light: #d1fae5;
            --aj-md-shadow: 0 1px 3px rgba(0, 0, 0, 0.08);
            --aj-md-shadow-md: 0 4px 6px -1px rgba(0, 0, 0, 0.08);
            --aj-md-transition: all 0.2s ease;
        }

        .dark,
        [data-theme="dark"],
        .filament-theme-dark {
            --aj-md-bg: #0f172a;
            --aj-md-surface: #1e293b;
            --aj-md-border: #334155;
            --aj-md-text: #f8fafc;
            --aj-md-text-muted: #94a3b8;
            --aj-md-primary: #34d399;
            --aj-md-primary-hover: #6ee7b7;
            --aj-md-primary-light: rgba(52, 211, 153, 0.15);
            --aj-md-shadow: 0 1px 3px rgba(0, 0, 0, 0.3);
            --aj-md-shadow-md: 0 4px 6px -1px rgba(0, 0, 0, 0.3);
        }

        .aj-md-tabs-wrapper {
            background: var(--aj-md-bg);
            border-radius: 12px;
            border: 1px solid var(--aj-md-border);
            padding: 8px;
            margin-bottom: 24px;
            box-shadow: var(--aj-md-shadow);
        }

        .aj-md-tabs {
            display: flex;
            gap: 4px;
            background: var(--aj-md-surface);
            border-radius: 8px;
            padding: 4px;
        }

        .aj-md-tab {
            flex: 1;
            padding: 10px 20px;
            border-radius: 6px;
            transition: var(--aj-md-transition);
            border: none;
            cursor: pointer;
            font-weight: 500;
            font-size: 14px;
            font-family: 'Poppins', sans-serif;
            background: transparent;
            color: var(--aj-md-text-muted);
            position: relative;
            white-space: nowrap;
        }

        .aj-md-tab:hover {
            color: var(--aj-md-text);
            background: var(--aj-md-bg);
        }

        .aj-md-tab.active {
            background: var(--aj-md-primary);
            color: #ffffff;
            box-shadow: var(--aj-md-shadow-md);
        }

        .dark .aj-md-tab.active,
        [data-theme="dark"] .aj-md-tab.active,
        .filament-theme-dark .aj-md-tab.active {
            color: #0f172a;
        }

        .aj-md-content {
            animation: ajFadeSlide 0.25s ease;
        }

        @keyframes ajFadeSlide {
            from {
                opacity: 0;
                transform: translateY(8px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        @media (max-width: 1024px) {
            .aj-md-tab {
                padding: 10px 16px;
                font-size: 13px;
            }
        }

        @media (max-width: 768px) {
            .aj-md-tabs-wrapper {
                padding: 6px;
                margin-bottom: 16px;
                border-radius: 10px;
            }

            .aj-md-tabs {
                gap: 3px;
                padding: 3px;
                border-radius: 7px;
            }

            .aj-md-tab {
                padding: 8px 12px;
                font-size: 12px;
                font-weight: 600;
            }

            .aj-md-tab.active {
                box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
            }
        }

        @media (max-width: 640px) {
            .aj-md-tabs-wrapper {
                padding: 5px;
                margin-bottom: 12px;
                border-radius: 8px;
            }

            .aj-md-tabs {
                gap: 2px;
                padding: 2px;
            }

            .aj-md-tab {
                padding: 8px 10px;
                font-size: 11px;
                letter-spacing: -0.01em;
            }
        }
    </style>

    <div class="aj-md-tabs-wrapper">
        <div class="aj-md-tabs">
            @foreach($this->getTabs() as $tabKey => $tabLabel)
                <button
                    wire:click="setActiveTab('{{ $tabKey }}')"
                    class="aj-md-tab {{ $activeTab === $tabKey ? 'active' : '' }}"
                >
                    {{ $tabLabel }}
                </button>
            @endforeach
        </div>
    </div>

    <div class="aj-md-content">
        @if($activeTab === 'jenis-jasa')
            @livewire(\App\Filament\Resources\JenisJasas\Pages\ManageJenisJasas::class)
        @elseif($activeTab === 'kategori-jasa')
            @livewire(\App\Filament\Resources\KategoriJasaItems\Pages\ManageKategoriJasaItems::class)
        @elseif($activeTab === 'accessories')
            @livewire(\App\Filament\Resources\Accessoris\Pages\ManageAccessoris::class)
        @endif
    </div>
</x-filament-panels::page>

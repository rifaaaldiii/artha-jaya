<x-filament-widgets::widget>
    <style>
        :root {
            --po-bg: #ffffff;
            --po-text: #111827;
            --po-muted: #9ca3af;
            --po-border: #e5e7eb;
            --po-shadow: rgba(0, 0, 0, 0.08);
            --po-up-bg: #ecfdf5;
            --po-up-text: #059669;
            --po-down-bg: #fef2f2;
            --po-down-text: #dc2626;
        }

        .dark,
        [data-theme="dark"],
        .filament-theme-dark {
            --po-bg: oklch(0.21 0.006 285.885);
            --po-text: #f9fafb;
            --po-muted: #9ca3af;
            --po-border: #374151;
            --po-shadow: rgba(0, 0, 0, 0.35);
            --po-up-bg: rgba(5, 150, 105, 0.15);
            --po-up-text: #34d399;
            --po-down-bg: rgba(220, 38, 38, 0.15);
            --po-down-text: #f87171;
        }

        .product-overview-card {
            display: grid;
            grid-template-columns: repeat(4, minmax(0, 1fr));
            background: var(--po-bg);
            border-radius: 16px;
            box-shadow: 0 1px 1px 1px var(--po-shadow);
            overflow: hidden;
        }

        .product-overview-stat {
            position: relative;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            gap: 10px;
            padding: 28px 20px;
            text-align: center;
        }

        .product-overview-stat:not(:last-child)::after {
            content: '';
            position: absolute;
            top: 50%;
            right: 0;
            transform: translateY(-50%);
            width: 1px;
            height: 65%;
            background: var(--po-border);
        }

        .product-overview-label {
            font-size: 0.875rem;
            font-weight: 500;
            color: var(--po-muted);
            line-height: 1.2;
        }

        .product-overview-value {
            font-size: 2rem;
            font-weight: 700;
            color: var(--po-text);
            line-height: 1.1;
            letter-spacing: -0.02em;
        }

        .product-overview-trend {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            font-size: 0.875rem;
            font-weight: 600;
        }

        .product-overview-trend.is-up {
            color: var(--po-up-text);
        }

        .product-overview-trend.is-down {
            color: var(--po-down-text);
        }

        .product-overview-trend-icon {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            width: 28px;
            height: 28px;
            border-radius: 9999px;
        }

        .product-overview-trend.is-up .product-overview-trend-icon {
            background: var(--po-up-bg);
        }

        .product-overview-trend.is-down .product-overview-trend-icon {
            background: var(--po-down-bg);
        }

        .product-overview-trend-icon svg {
            width: 14px;
            height: 14px;
        }

        @media (max-width: 1024px) {
            .product-overview-card {
                grid-template-columns: repeat(2, minmax(0, 1fr));
            }

            .product-overview-stat:nth-child(2)::after,
            .product-overview-stat:nth-child(4)::after {
                display: none;
            }
            
            .product-overview-stat:nth-child(1),
            .product-overview-stat:nth-child(2) {
                border-bottom: 1px solid var(--po-border);
            }
        }
        
        @media (max-width: 640px) {

            .product-overview-value {
                font-size: 1.75rem;
            }
        }
    </style>

    <div class="product-overview-card">
        @foreach ($this->stats as $stat)
            <div class="product-overview-stat">
                <div class="product-overview-label">{{ $stat['label'] }}</div>
                <div class="product-overview-value">{{ $stat['value'] }}</div>
                <div @class([
                    'product-overview-trend',
                    'is-up' => $stat['trend']['direction'] === 'up',
                    'is-down' => $stat['trend']['direction'] === 'down',
                ])>
                    <span class="product-overview-trend-icon">
                        @if ($stat['trend']['direction'] === 'up')
                            <svg viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                                <path fill-rule="evenodd" d="M10 17a.75.75 0 0 1-.75-.75V5.612L5.29 9.77a.75.75 0 0 1-1.06-1.06l5.25-5.25a.75.75 0 0 1 1.06 0l5.25 5.25a.75.75 0 0 1-1.06 1.06l-3.96-3.96V16.25A.75.75 0 0 1 10 17Z" clip-rule="evenodd" />
                            </svg>
                        @else
                            <svg viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                                <path fill-rule="evenodd" d="M10 3a.75.75 0 0 1 .75.75v10.638l3.96-3.96a.75.75 0 1 1 1.06 1.06l-5.25 5.25a.75.75 0 0 1-1.06 0l-5.25-5.25a.75.75 0 0 1 1.06-1.06l3.96 3.96V3.75A.75.75 0 0 1 10 3Z" clip-rule="evenodd" />
                            </svg>
                        @endif
                    </span>
                    <span>{{ $stat['trend']['label'] }}</span>
                </div>
            </div>
        @endforeach
    </div>
</x-filament-widgets::widget>

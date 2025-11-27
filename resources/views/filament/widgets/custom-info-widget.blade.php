<x-filament-widgets::widget>
    <style>
        :root {
            --custom-widget-bg: #ffffff;
            --custom-widget-text: #111827;
            --custom-widget-muted: #6b7280;
            --custom-widget-shadow: rgba(0, 0, 0, 0.1);
        }

        .dark,
        [data-theme="dark"],
        .filament-theme-dark {
            --custom-widget-bg: #181818;
            --custom-widget-text: #ffffff;
            --custom-widget-muted: #9ca3af;
            --custom-widget-shadow: rgba(0, 0, 0, 0.3);
        }

        .custom-info-widget-container {
            display: flex;
            align-items: center;
            background: var(--custom-widget-bg);
            border-radius: 16px;
            padding: 30px;
            gap: 24px;
            height: 92px;
            box-shadow: 0 2px 7px var(--custom-widget-shadow), 0 1.5px 3px var(--custom-widget-shadow);
            /* Responsive tweak */
            flex-direction: row;
        }
        .custom-info-widget-title {
            font-style: italic;
            font-weight: 800;
            font-size: 1rem;
            color: var(--custom-widget-text);
        }
        .custom-info-widget-version {
            font-size: 0.75rem;
            color: var(--custom-widget-muted);
            margin-top: 4px;
        }
        .custom-info-widget-spacer {
            flex: 1;
        }
        .custom-info-widget-links {
            display: flex;
            gap: 16px;
            flex-direction: column;
            justify-content: flex-end;
            align-items: flex-end;
        }
        .custom-info-widget-link {
            display: flex;
            align-items: center;
            gap: 4px;
            font-size: 0.75rem;
            font-weight: 600;
            color: var(--custom-widget-muted);
            text-decoration: none;
            transition: color .2s;
        }
        .custom-info-widget-link.produksi:hover {
            color: #ff0000;
        }
        .custom-info-widget-link.jasa:hover {
            color: #ff0000;
        }
        .custom-info-widget-link svg {
            width: 16px;
            height: 16px;
            fill: currentColor;
        }

        /* Responsive styles for mobile */
        @media (max-width: 600px) {
            .custom-info-widget-container {
                display: flex;
                flex-direction: row;
                justify-content: space-between;
                align-items: center;
                height: auto;
                padding: 18px 30px;
                min-height: 0;
            }
            .custom-info-widget-title {
                font-size: 1rem;
            }
            .custom-info-widget-version {
                font-size: 0.7rem;
                margin-top: 2px;
            }
            .custom-info-widget-spacer {
                display: none;
            }
            .custom-info-widget-links {
                flex-direction: column;
                gap: 5px;
                width: auto;
                justify-content: flex-end;
                align-items: flex-end;
            }
            .custom-info-widget-link {
                font-size: 0.75rem;
                gap: 4px;
                padding: 0;
            }
        }
    </style>
    <div class="custom-info-widget-container">
        <div>
            <span class="custom-info-widget-title">System Artha Jaya</span>
            <div class="custom-info-widget-version">v{{ config('app.version', '1.0.0') }}</div>
        </div>
        <div class="custom-info-widget-spacer"></div>
        <div class="custom-info-widget-links">
            <a href="{{ url('admin/produksis') }}" class="custom-info-widget-link produksi">
                <!-- Factory/production/industry icon SVG -->
                <svg width="18" height="18" fill="none" viewBox="0 0 24 24"><path d="M3 21V10.705l5-3.158v3.158l5-3.158v3.158l5-3.158V21H3zm2-2h2v-2H5v2zm4 0h2v-4H9v4zm4 0h2v-6h-2v6zm4-2h-2v2h2v-2z" fill="currentColor"/></svg>
                Produksi
            </a>
            <a href="{{ url('admin/jasas') }}" class="custom-info-widget-link jasa">
                <!-- Handshake/service icon SVG -->
                <svg width="18" height="18" fill="none" viewBox="0 0 24 24"><path d="M12 17l-6-6 1.41-1.41L12 14.17l4.59-4.58L18 11l-6 6z" fill="currentColor"/><path d="M19 7V3H5v4H3v2h18V7h-2z" fill="currentColor" fill-opacity="0.6"/></svg>
                Jasa & Layanan
            </a>
        </div>
    </div>
</x-filament-widgets::widget>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Jadwal Jasa - Artha Jaya</title>
    <link rel="icon" type="image/x-icon" href="{{ asset('favicon.ico') }}">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:opsz,wght@14..32,300..600&display=swap" rel="stylesheet">
    <style>
        :root {
            --primary: #000000;
            --ink: #000000;
            --on-primary: #ffffff;
            --canvas-night: #000000;
            --canvas-light: #ffffff;
            --canvas-cream: #fbfbf5;
            --hairline: #e4e4e7;
            --shade-30: #d4d4d8;
            --shade-40: #a1a1aa;
            --shade-50: #71717a;
            --shade-60: #52525b;
            --shade-70: #3f3f46;
            --aloe: #c1fbd4;
            --pistachio: #d4f9e0;
            --rounded-xs: 4px;
            --rounded-md: 8px;
            --rounded-lg: 12px;
            --rounded-xl: 20px;
            --rounded-pill: 9999px;
            --shadow-card: 0 8px 8px rgba(0,0,0,0.04), 0 4px 4px rgba(0,0,0,0.04), 0 2px 2px rgba(0,0,0,0.04), 0 0 0 1px rgba(0,0,0,0.06);
            --shadow-modal: 0 25px 50px -12px rgba(0,0,0,0.25);
            --font-display: "Inter", Helvetica, Arial, sans-serif;
            --font-body: "Inter", Helvetica, Arial, sans-serif;
            --sidebar: 300px;
        }

        * { margin: 0; padding: 0; box-sizing: border-box; }

        html, body {
            width: 100%;
            min-height: 100%;
            -webkit-font-smoothing: antialiased;
        }

        body {
            font-family: var(--font-body);
            font-feature-settings: "ss03";
            background: var(--canvas-cream);
            color: var(--ink);
            font-size: 16px;
            font-weight: 420;
            line-height: 1.5;
        }

        a { color: inherit; text-decoration: none; }
        button { font: inherit; border: none; background: none; cursor: pointer; }

        .page {
            min-height: 100vh;
            min-height: 100dvh;
            display: flex;
            flex-direction: column;
        }

        /* ── Nav ── */
        .nav-bar {
            background: var(--canvas-night);
            color: var(--on-primary);
            height: 56px;
            padding: 0 24px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 16px;
            position: sticky;
            top: 0;
            z-index: 60;
        }

        .brand {
            display: flex;
            align-items: center;
            gap: 10px;
            min-width: 0;
        }

        .brand-logo {
            height: 32px;
            width: auto;
            display: block;
            object-fit: contain;
        }

        .brand-name {
            font-family: var(--font-display);
            font-size: 16px;
            font-weight: 500;
            letter-spacing: 0.3px;
            line-height: 1;
            white-space: nowrap;
        }

        .week-nav {
            display: flex;
            align-items: center;
            gap: 6px;
        }

        .week-nav-row {
            display: contents;
        }

        .week-label-mobile { display: none; }

        .nav-actions-mobile {
            display: none;
        }

        .btn-icon-label {
            display: none;
        }

        .week-label {
            font-size: 13px;
            font-weight: 500;
            letter-spacing: 0.2px;
            color: var(--shade-30);
            min-width: 148px;
            text-align: center;
            white-space: nowrap;
        }

        .btn-icon {
            width: 36px;
            height: 36px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            border-radius: var(--rounded-pill);
            border: 1px solid rgba(255, 255, 255, 0.28);
            color: var(--on-primary);
            flex-shrink: 0;
            transition: background .15s ease, border-color .15s ease;
        }

        .btn-icon:hover {
            background: rgba(255, 255, 255, 0.08);
            border-color: rgba(255, 255, 255, 0.5);
        }

        .btn-icon:active { opacity: 0.75; }

        .btn-pill {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 6px;
            min-height: 36px;
            padding: 8px 18px;
            border-radius: var(--rounded-pill);
            font-size: 13px;
            font-weight: 500;
            line-height: 1;
            white-space: nowrap;
            transition: background .15s ease, opacity .15s ease;
        }

        .btn-pill:active { opacity: 0.85; }

        .btn-pill-solid {
            background: var(--on-primary);
            color: var(--ink);
        }

        .btn-pill-solid:active { background: var(--shade-30); }

        .btn-pill-ghost {
            background: transparent;
            color: var(--on-primary);
            border: 1px solid rgba(255, 255, 255, 0.28);
            padding: 7px 16px;
        }

        .btn-pill-ghost:hover {
            border-color: rgba(255, 255, 255, 0.55);
            background: rgba(255, 255, 255, 0.06);
        }

        .btn-pill-primary {
            background: var(--primary);
            color: var(--on-primary);
        }

        .btn-pill-primary:active { background: var(--shade-70); }

        .btn-pill-outline-light {
            background: var(--canvas-light);
            color: var(--ink);
            border: 1px solid var(--ink);
            padding: 7px 16px;
        }

        .mobile-filter-btn { display: none; }

        body.is-schedule-fullscreen { overflow: hidden; }

        .fullscreen-bar {
            display: none;
            position: sticky;
            top: -20px;
            z-index: 10;
            align-items: center;
            justify-content: space-between;
            gap: 12px;
            margin: -30px -24px 14px;
            padding: 12px 16px;
            background: var(--canvas-night);
            color: var(--on-primary);
            border-bottom: 1px solid rgba(255, 255, 255, 0.12);
        }

        .schedule-main.fullscreen-mode .fullscreen-bar {
            display: flex;
        }

        .fullscreen-bar-title {
            font-size: 14px;
            font-weight: 500;
            letter-spacing: 0.2px;
            min-width: 0;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        .btn-exit-fullscreen {
            background: var(--on-primary);
            color: var(--ink);
            min-height: 36px;
            padding: 8px 16px;
            border-radius: var(--rounded-pill);
            font-size: 13px;
            font-weight: 500;
            white-space: nowrap;
            flex-shrink: 0;
        }

        .btn-exit-fullscreen:active { background: var(--shade-30); }

        .fullscreen-enter { display: inline; }
        .fullscreen-exit { display: none; }
        body.is-schedule-fullscreen .fullscreen-enter { display: none; }
        body.is-schedule-fullscreen .fullscreen-exit { display: inline; }

        /* ── Layout ── */
        .layout {
            flex: 1;
            display: grid;
            grid-template-columns: 280px minmax(0, 1fr);
            gap: 20px;
            padding: 20px 24px 32px;
            max-width: 1600px;
            width: 100%;
            margin: 0 auto;
        }

        /* ── Sidebar (single panel) ── */
        .sidebar {
            min-width: 0;
        }

        .sidebar-backdrop {
            display: none;
            position: fixed;
            inset: 0;
            background: rgba(0, 0, 0, 0.44);
            z-index: 79;
        }

        .sidebar-close-btn {
            display: none;
            width: 36px;
            height: 36px;
            border: 1px solid var(--hairline);
            border-radius: var(--rounded-pill);
            color: var(--shade-60);
            background: var(--canvas-light);
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
        }

        .sidebar-panel {
            background: var(--canvas-light);
            border: 1px solid var(--hairline);
            border-radius: var(--rounded-lg);
            box-shadow: var(--shadow-card);
            overflow: hidden;
        }

        .sidebar-section {
            padding: 18px;
        }

        .sidebar-section + .sidebar-section {
            border-top: 1px solid var(--hairline);
        }

        .sidebar-section-title {
            font-size: 11px;
            font-weight: 500;
            letter-spacing: 0.72px;
            text-transform: uppercase;
            color: var(--shade-50);
            margin-bottom: 12px;
        }

        .mini-cal-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 8px;
            margin-bottom: 12px;
        }

        .mini-cal-header h3 {
            font-family: var(--font-display);
            font-size: 16px;
            font-weight: 500;
            letter-spacing: 0.2px;
            line-height: 1.3;
        }

        .mini-cal-nav { display: flex; gap: 2px; }

        .mini-nav-btn {
            width: 32px;
            height: 32px;
            display: grid;
            place-items: center;
            border-radius: var(--rounded-pill);
            color: var(--shade-60);
            transition: background .15s ease;
        }

        .mini-nav-btn:hover { background: var(--shade-30); color: var(--ink); }

        .mini-day-names,
        .mini-days {
            display: grid;
            grid-template-columns: repeat(7, 1fr);
            gap: 2px;
        }

        .mini-day-names > div {
            text-align: center;
            font-size: 10px;
            font-weight: 500;
            letter-spacing: 0.4px;
            text-transform: uppercase;
            color: var(--shade-50);
            padding: 2px 0 6px;
        }

        .mini-day-empty { height: 32px; }

        .mini-day-btn {
            height: 32px;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            border-radius: var(--rounded-md);
            font-size: 12px;
            font-weight: 500;
            color: var(--ink);
            position: relative;
            transition: background .15s ease;
        }

        .mini-day-btn:hover { background: var(--pistachio); }

        .mini-day-btn.today {
            font-weight: 550;
            box-shadow: inset 0 0 0 1px var(--ink);
        }

        .mini-day-btn.selected {
            background: var(--primary);
            color: var(--on-primary);
            box-shadow: none;
        }

        .mini-day-dots {
            display: flex;
            gap: 2px;
            position: absolute;
            bottom: 2px;
        }

        .mini-day-dots .dot {
            width: 3px;
            height: 3px;
            border-radius: 50%;
            background: var(--ink);
        }

        .mini-day-btn.selected .dot { background: var(--aloe); }

        .stats-inline {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 8px;
        }

        .stat-cell {
            text-align: center;
            padding: 10px 6px;
            border-radius: var(--rounded-md);
            background: var(--canvas-cream);
            border: 1px solid var(--hairline);
        }

        .stat-cell.featured {
            background: var(--aloe);
            border-color: transparent;
        }

        .stat-cell strong {
            display: block;
            font-size: 20px;
            font-weight: 500;
            line-height: 1.2;
            letter-spacing: -0.02em;
        }

        .stat-cell span {
            display: block;
            margin-top: 2px;
            font-size: 11px;
            font-weight: 500;
            letter-spacing: 0.3px;
            color: var(--shade-50);
        }

        .stat-cell.featured span { color: var(--shade-70); }

        .filter-list {
            display: flex;
            gap: 8px;
            flex-wrap: wrap;
        }

        .filter-chip {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            min-height: 36px;
            padding: 6px 14px;
            border-radius: var(--rounded-pill);
            border: 1px solid var(--hairline);
            background: var(--canvas-light);
            cursor: pointer;
            user-select: none;
            font-size: 13px;
            font-weight: 500;
            letter-spacing: 0.2px;
            transition: background .15s ease, border-color .15s ease;
        }

        .filter-chip input { display: none; }

        .filter-chip.active {
            background: var(--aloe);
            border-color: transparent;
        }

        .filter-chip:active { transform: scale(0.98); }

        .filter-dot {
            width: 7px;
            height: 7px;
            border-radius: 50%;
            flex-shrink: 0;
            background: var(--shade-40);
        }

        .filter-dot.terjadwal { background: var(--ink); }
        .filter-dot.selesai { background: #15803d; }

        /* ── Main ── */
        .schedule-main {
            min-width: 0;
            display: flex;
            flex-direction: column;
            gap: 14px;
        }

        .schedule-main.fullscreen-mode {
            position: fixed;
            inset: 0;
            z-index: 100;
            background: var(--canvas-cream);
            padding: 20px 24px;
            overflow: auto;
        }

        .main-header {
            display: flex;
            align-items: flex-end;
            justify-content: space-between;
            gap: 12px;
            flex-wrap: wrap;
        }

        .main-header h2 {
            font-family: var(--font-display);
            font-size: 28px;
            font-weight: 500;
            letter-spacing: 0.2px;
            line-height: 1.2;
        }

        .main-header p {
            margin-top: 4px;
            font-size: 13px;
            font-weight: 500;
            color: var(--shade-50);
            letter-spacing: 0.2px;
        }

        .main-header-actions {
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .main-header-actions .btn-pill-outline-light {
            min-height: 36px;
            font-size: 13px;
        }

        .main-header-actions .btn-pill-primary {
            min-height: 36px;
            padding: 8px 18px;
            font-size: 13px;
        }

        /* ── Table card ── */
        .table-card {
            background: var(--canvas-light);
            border: 1px solid var(--hairline);
            border-radius: var(--rounded-lg);
            box-shadow: var(--shadow-card);
            overflow: hidden;
            flex: 1;
            min-height: 320px;
            display: flex;
            flex-direction: column;
        }

        .table-scroll {
            overflow: auto;
            -webkit-overflow-scrolling: touch;
            flex: 1;
        }

        .week-table {
            width: 100%;
            min-width: 920px;
            border-collapse: collapse;
        }

        .week-table thead {
            background: var(--canvas-cream);
            position: sticky;
            top: 0;
            z-index: 2;
        }

        .week-table th {
            padding: 14px 16px;
            text-align: left;
            font-size: 12px;
            font-weight: 400;
            letter-spacing: 0.72px;
            text-transform: uppercase;
            color: var(--shade-50);
            border-bottom: 1px solid var(--hairline);
            white-space: nowrap;
        }

        .week-table td {
            padding: 14px 16px;
            border-bottom: 1px solid var(--hairline);
            font-size: 14px;
            font-weight: 500;
            letter-spacing: 0.28px;
            color: var(--ink);
            vertical-align: top;
            line-height: 1.49;
            word-break: break-word;
        }

        .week-table tbody tr:last-child td { border-bottom: none; }

        .week-table tbody tr.is-today td {
            background: rgba(193, 251, 212, 0.35);
        }

        .week-table tbody tr.is-today .col-hari {
            box-shadow: inset 3px 0 0 var(--ink);
        }

        .col-hari {
            min-width: 120px;
            background: var(--canvas-cream);
        }

        .day-label {
            display: block;
            font-size: 16px;
            font-weight: 550;
            color: var(--ink);
        }

        .day-date {
            display: block;
            font-size: 13px;
            font-weight: 500;
            color: var(--shade-50);
            margin-top: 2px;
        }

        .col-branch { min-width: 72px; white-space: nowrap; }
        .col-lokasi { min-width: 140px; }
        .col-keterangan { min-width: 130px; }
        .col-catatan { min-width: 130px; }
        .col-pic { min-width: 100px; }
        .col-petugas { min-width: 120px; }
        .col-status { min-width: 110px; white-space: nowrap; }

        .pill-tag {
            display: inline-flex;
            align-items: center;
            font-size: 12px;
            font-weight: 400;
            letter-spacing: 0.72px;
            text-transform: uppercase;
            padding: 4px 12px;
            border-radius: var(--rounded-pill);
            background: var(--aloe);
            color: var(--ink);
        }

        .pill-tag-shade {
            background: var(--shade-30);
        }

        .status-badge {
            display: inline-flex;
            align-items: center;
            font-size: 12px;
            font-weight: 400;
            letter-spacing: 0.72px;
            text-transform: uppercase;
            padding: 4px 12px;
            border-radius: var(--rounded-pill);
        }

        .status-badge.status-terjadwal {
            background: var(--shade-30);
            color: var(--ink);
        }

        .status-badge.status-selesai {
            background: var(--aloe);
            color: var(--ink);
        }

        .cell-empty {
            color: var(--shade-50);
            font-style: italic;
            text-align: center;
            font-weight: 420;
        }

        .cell-value {
            display: inline;
            word-break: break-word;
        }

        .schedule-row.hidden-by-filter { display: none !important; }
        .schedule-row--placeholder { display: none; }
        .schedule-row--placeholder.visible { display: table-row; }

        /* Same-day multi-item grouping (desktop / tablet table) */
        @media (min-width: 768px) {
            .week-table tbody tr.is-continuation-day-item td.col-hari .day-label,
            .week-table tbody tr.is-continuation-day-item td.col-hari .day-date {
                visibility: hidden;
            }

            .week-table tbody tr.is-first-day-item:not(.is-last-day-item) td,
            .week-table tbody tr.is-continuation-day-item:not(.is-last-day-item) td {
                border-bottom-style: dashed;
                border-bottom-color: var(--shade-30);
            }

            .week-table tbody tr.is-first-day-item:not(.is-last-day-item) td.col-hari,
            .week-table tbody tr.is-continuation-day-item:not(.is-last-day-item) td.col-hari {
                border-bottom-color: transparent;
            }

            .week-table tbody tr.is-continuation-day-item td {
                padding-top: 10px;
            }

            .week-table tbody tr.is-first-day-item:not(.is-last-day-item) td {
                padding-bottom: 10px;
            }
        }

        .live-hint {
            display: flex;
            align-items: center;
            gap: 8px;
            font-size: 13px;
            font-weight: 500;
            color: var(--shade-50);
            padding: 0 4px;
        }

        .pulse-dot {
            width: 8px;
            height: 8px;
            background: var(--ink);
            border-radius: 50%;
            animation: pulse 2s infinite;
        }

        @keyframes pulse {
            0% { box-shadow: 0 0 0 0 rgba(0,0,0,0.25); }
            70% { box-shadow: 0 0 0 6px rgba(0,0,0,0); }
            100% { box-shadow: 0 0 0 0 rgba(0,0,0,0); }
        }

        /* ── Modal ── */
        .events-modal {
            display: none;
            position: fixed;
            inset: 0;
            z-index: 200;
            align-items: center;
            justify-content: center;
            padding: 16px;
        }

        .events-modal.active { display: flex; }

        .modal-overlay {
            position: absolute;
            inset: 0;
            background: rgba(0,0,0,0.5);
        }

        .modal-content {
            position: relative;
            background: var(--canvas-light);
            border-radius: var(--rounded-xl);
            box-shadow: var(--shadow-modal);
            width: 100%;
            max-width: 560px;
            max-height: 85vh;
            display: flex;
            flex-direction: column;
            overflow: hidden;
        }

        .modal-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 12px;
            padding: 20px 24px;
            border-bottom: 1px solid var(--hairline);
        }

        .modal-title {
            font-family: var(--font-display);
            font-size: 20px;
            font-weight: 500;
            letter-spacing: 0.3px;
        }

        .modal-close {
            width: 40px;
            height: 40px;
            display: grid;
            place-items: center;
            border-radius: var(--rounded-pill);
            color: var(--shade-60);
        }

        .modal-close:hover { background: var(--shade-30); color: var(--ink); }

        .modal-body {
            padding: 20px 24px;
            overflow-y: auto;
        }

        .modal-event-item {
            border: 1px solid var(--hairline);
            border-radius: var(--rounded-lg);
            padding: 16px;
            margin-bottom: 12px;
            background: var(--canvas-cream);
        }

        .modal-event-item.selesai { background: var(--pistachio); border-color: transparent; }

        .modal-event-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 8px;
            margin-bottom: 8px;
        }

        .modal-event-type {
            font-size: 12px;
            font-weight: 400;
            letter-spacing: 0.72px;
            text-transform: uppercase;
            color: var(--shade-50);
        }

        .modal-event-title {
            font-size: 16px;
            font-weight: 550;
            margin-bottom: 8px;
        }

        .modal-event-meta {
            font-size: 14px;
            font-weight: 500;
            color: var(--shade-60);
            margin-top: 4px;
            word-break: break-word;
        }

        /* ── Desktop wide ── */
        @media (min-width: 1440px) {
            .layout { padding: 24px 32px 40px; gap: 24px; }
            .main-header h2 { font-size: 32px; }
        }

        /* ── Tablet ── */
        @media (max-width: 1023px) {
            .layout {
                grid-template-columns: 1fr;
                padding: 16px 20px 28px;
                gap: 16px;
            }

            .sidebar-panel {
                display: grid;
                grid-template-columns: 1.2fr 1fr;
            }

            .sidebar-section:first-child {
                grid-row: 1 / span 2;
                border-top: none;
                border-right: 1px solid var(--hairline);
            }

            .sidebar-section + .sidebar-section {
                border-top: 1px solid var(--hairline);
            }

            .sidebar-section:first-child + .sidebar-section {
                border-top: none;
            }

            .main-header h2 { font-size: 24px; }
        }

        /* ── Mobile ── */
        @media (max-width: 767px) {
            .nav-bar {
                height: auto;
                min-height: auto;
                padding: 12px 14px;
                flex-direction: column;
                align-items: stretch;
                gap: 10px;
            }

            .nav-bar-top {
                display: flex;
                align-items: center;
                justify-content: space-between;
                gap: 10px;
                min-width: 0;
            }

            .nav-actions-mobile {
                display: flex;
                align-items: center;
                gap: 6px;
                flex-shrink: 0;
            }

            .week-nav {
                width: 100%;
                flex-direction: column;
                align-items: stretch;
                gap: 8px;
            }

            .week-nav-row--dates {
                display: flex;
                align-items: center;
                justify-content: center;
                gap: 8px;
                padding: 8px 10px;
                border: 1px solid rgba(255, 255, 255, 0.18);
                border-radius: var(--rounded-pill);
                background: rgba(255, 255, 255, 0.04);
            }

            .week-nav-row--tools { display: none; }

            .nav-fs-btn--mobile {
                margin-left: auto;
                flex-shrink: 0;
            }

            .nav-fs-btn--desktop { display: none; }

            .week-label { display: none; }

            .week-label-mobile {
                display: block;
                flex: 1;
                min-width: 0;
                font-size: 12px;
                font-weight: 500;
                letter-spacing: 0.2px;
                color: var(--shade-30);
                text-align: center;
                white-space: nowrap;
                overflow: hidden;
                text-overflow: ellipsis;
            }

            .mobile-filter-btn {
                display: inline-flex;
                width: 36px;
                min-height: 36px;
                padding: 0;
                justify-content: center;
            }

            .mobile-filter-btn .btn-icon-label { display: none; }

            .nav-fs-btn {
                min-height: 36px;
                padding: 0 12px;
            }

            .nav-fs-btn .btn-icon-label { display: none; }

            .btn-pill-today {
                min-height: 36px;
                padding: 8px 14px;
                font-size: 12px;
            }

            .schedule-main.fullscreen-mode {
                padding: 0;
            }

            .fullscreen-bar {
                margin: 0 0 12px;
                padding: 12px 14px;
                border-radius: 0;
            }

            .btn-exit-fullscreen {
                min-height: 34px;
                padding: 8px 14px;
                font-size: 12px;
            }

            .week-nav-today { display: none; }

            .schedule-main.fullscreen-mode .main-header,
            .schedule-main.fullscreen-mode .table-card,
            .schedule-main.fullscreen-mode .live-hint {
                margin-left: 14px;
                margin-right: 14px;
            }

            .schedule-main.fullscreen-mode .live-hint {
                margin-bottom: 14px;
            }

            .layout {
                padding: 14px 14px 24px;
                gap: 14px;
            }

            .sidebar {
                position: fixed;
                top: 0;
                right: 0;
                bottom: 0;
                width: min(88vw, 340px);
                z-index: 80;
                transform: translateX(100%);
                transition: transform .2s ease;
                padding: 12px;
                overflow-y: auto;
            }

            .sidebar.is-open {
                transform: translateX(0);
            }

            .sidebar-backdrop.is-visible {
                display: block;
            }

            .sidebar-panel {
                display: block;
                border-radius: var(--rounded-xl);
            }

            .sidebar-section:first-child {
                grid-row: auto;
                border-right: none;
            }

            .sidebar-section:first-child + .sidebar-section {
                border-top: 1px solid var(--hairline);
            }

            .sidebar-section--filter {
                padding-top: 14px;
            }

            .sidebar-section--filter .sidebar-section-title {
                display: flex;
                align-items: center;
                justify-content: space-between;
                margin-bottom: 10px;
            }

            .sidebar-close-btn {
                display: inline-flex;
            }

            .main-header {
                flex-direction: column;
                align-items: stretch;
            }

            .main-header h2 { font-size: 22px; }

            .main-header-actions {
                width: 100%;
            }

            .main-header-actions .btn-pill {
                flex: 1;
            }

            .table-card {
                border: none;
                background: transparent;
                box-shadow: none;
            }

            .table-scroll { overflow: visible; }

            .week-table { min-width: 0; }
            .week-table thead { display: none; }

            .week-table tbody tr {
                display: block;
                margin-bottom: 12px;
                border: 1px solid var(--hairline);
                border-radius: var(--rounded-lg);
                background: var(--canvas-light);
                box-shadow: var(--shadow-card);
                overflow: hidden;
            }

            .week-table tbody tr.is-today {
                background: var(--pistachio);
                border-color: transparent;
            }

            .week-table td {
                display: flex;
                justify-content: space-between;
                align-items: flex-start;
                gap: 12px;
                padding: 12px 16px;
                border-bottom: 1px solid var(--hairline);
                text-align: right;
                font-size: 14px;
            }

            .week-table td:not(.col-hari) {
                text-align: right;
            }

            .week-table .cell-value {
                flex: 1;
                min-width: 0;
                max-width: 62%;
                text-align: right;
                line-height: 1.45;
            }

            .week-table .cell-value--multiline {
                line-height: 1.5;
                max-width: 65%;
            }

            .week-table .cell-value:has(.pill-tag),
            .week-table .cell-value:has(.status-badge) {
                display: flex;
                justify-content: flex-end;
            }

            .week-table td:last-child { border-bottom: none; }

            .week-table td::before {
                content: attr(data-label);
                font-size: 12px;
                font-weight: 400;
                letter-spacing: 0.72px;
                text-transform: uppercase;
                color: var(--shade-50);
                text-align: left;
                flex-shrink: 0;
            }

            .week-table td.col-hari {
                background: transparent;
                flex-direction: column;
                align-items: flex-start;
                text-align: left;
                box-shadow: none;
            }

            .week-table tbody tr.is-continuation-day-item td.col-hari {
                display: none;
            }

            .week-table tbody tr.is-first-day-item:not(.is-last-day-item) {
                margin-bottom: 0;
                border-bottom-left-radius: 0;
                border-bottom-right-radius: 0;
            }

            .week-table tbody tr.is-continuation-day-item {
                margin-top: 0;
                margin-bottom: 12px;
                border-top: none;
                border-top-left-radius: 0;
                border-top-right-radius: 0;
                box-shadow: none;
            }

            .week-table tbody tr.is-continuation-day-item:not(.is-last-day-item) {
                margin-bottom: 0;
                border-bottom-left-radius: 0;
                border-bottom-right-radius: 0;
            }

            .week-table tbody tr.is-continuation-day-item td.col-keterangan {
                border-top: 1px dashed var(--hairline);
            }

            .week-table tbody tr.is-first-day-item:not(.is-last-day-item) td:last-child,
            .week-table tbody tr.is-continuation-day-item:not(.is-last-day-item) td:last-child {
                border-bottom: 1px dashed var(--hairline);
            }

            .week-table tbody tr.is-today td.col-hari {
                box-shadow: none;
            }

            .week-table td.col-hari::before { display: none; }

            .week-table .cell-empty {
                display: block;
                text-align: center;
                padding: 24px 16px;
            }

            .week-table .cell-empty::before { display: none; }

            .week-table tbody tr.schedule-row--placeholder { display: none; }
            .week-table tbody tr.schedule-row--placeholder.visible { display: block; }

            .events-modal.active {
                align-items: flex-end;
                padding: 0;
            }

            .modal-content {
                max-width: 100%;
                max-height: 90dvh;
                border-radius: var(--rounded-xl) var(--rounded-xl) 0 0;
            }
        }

        @media (min-width: 768px) {
            .nav-bar {
                flex-direction: row;
                align-items: center;
                height: 56px;
                padding: 0 24px;
                gap: 16px;
            }

            .nav-bar-top { display: contents; }

            .nav-actions-mobile { display: none !important; }

            .week-nav {
                flex-direction: row;
                width: auto;
                gap: 6px;
            }

            .week-nav-row { display: contents; }

            .week-label-mobile { display: none; }

            .week-label { display: block; }

            .nav-fs-btn .btn-icon-label { display: inline; }

            .week-nav-today { display: inline-flex; }

            .nav-fs-btn--mobile { display: none; }

            .nav-fs-btn--desktop { display: inline-flex; }

            .week-nav-row--tools { display: contents; }
        }

        @media (max-width: 419px) {
            .brand-logo { height: 28px; }
            .main-header h2 { font-size: 20px; }
            .week-label-mobile { font-size: 11px; }
            .btn-pill-today { padding: 8px 12px; }
        }
    </style>
</head>
<body>
<div class="page">
    <header class="nav-bar">
        <div class="nav-bar-top">
            <div class="brand">
                <img src="{{ asset('logo.png') }}" alt="Artha Jaya" class="brand-logo">
            </div>

            <div class="nav-actions-mobile">
                <button type="button" class="btn-icon mobile-filter-btn" id="openMobileSidebar" aria-label="Buka filter" title="Filter">
                    <svg width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M7 12h10M10 18h4"/></svg>
                </button>
                <a class="btn-pill btn-pill-solid btn-pill-today" href="{{ route('public.schedule', ['date' => now()->toDateString()]) }}">Hari Ini</a>
            </div>
        </div>

        <div class="week-nav">
            <div class="week-nav-row week-nav-row--dates">
                <a class="btn-icon" href="{{ route('public.schedule', ['date' => $prevWeek]) }}" aria-label="Minggu sebelumnya" title="Minggu sebelumnya">
                    <svg width="14" height="14" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
                </a>
                <div class="week-label">{{ $weekLabel }}</div>
                <div class="week-label-mobile">{{ $weekLabel }}</div>
                <a class="btn-icon" href="{{ route('public.schedule', ['date' => $nextWeek]) }}" aria-label="Minggu berikutnya" title="Minggu berikutnya">
                    <svg width="14" height="14" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                </a>
                <button type="button" class="btn-icon nav-fs-btn nav-fs-btn--mobile" id="toggleFullscreenMobile" aria-label="Mode fullscreen">
                    <span class="fullscreen-enter" aria-hidden="true">
                        <svg width="14" height="14" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 8V4m0 0h4M4 4l5 5m11-5h-4m4 0v4m0-4l-5 5M4 16v4m0 0h4m-4 0l5-5m11 5v-4m0 4h-4m4 0l-5-5"/></svg>
                    </span>
                    <span class="fullscreen-exit" aria-hidden="true">
                        <svg width="14" height="14" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 4H4v5M4 4l5 5m11-5h-5v5m5-5l-5 5M9 20H4v-5m0 5l5-5m5 5h5v-5m-5 5l5-5"/></svg>
                    </span>
                </button>
            </div>

            <div class="week-nav-row week-nav-row--tools">
                <button type="button" class="btn-pill btn-pill-ghost nav-fs-btn nav-fs-btn--desktop" id="toggleFullscreen" aria-label="Mode fullscreen">
                    <span class="fullscreen-enter" aria-hidden="true">
                        <svg width="14" height="14" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 8V4m0 0h4M4 4l5 5m11-5h-4m4 0v4m0-4l-5 5M4 16v4m0 0h4m-4 0l5-5m11 5v-4m0 4h-4m4 0l-5-5"/></svg>
                    </span>
                    <span class="fullscreen-exit" aria-hidden="true">
                        <svg width="14" height="14" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 4H4v5M4 4l5 5m11-5h-5v5m5-5l-5 5M9 20H4v-5m0 5l5-5m5 5h5v-5m-5 5l5-5"/></svg>
                    </span>
                </button>
                <a class="btn-pill btn-pill-solid btn-pill-today week-nav-today" href="{{ route('public.schedule', ['date' => now()->toDateString()]) }}">Hari Ini</a>
            </div>
        </div>
    </header>

    <div class="layout">
        <aside class="sidebar" id="filterSidebar">
            <div class="sidebar-panel">
                <div class="sidebar-section">
                    <div class="mini-cal-header">
                        <h3>{{ $monthName }}</h3>
                        <div class="mini-cal-nav">
                            <a href="{{ route('public.schedule', ['month' => $prevMonth]) }}" class="mini-nav-btn" aria-label="Bulan sebelumnya">
                                <svg width="14" height="14" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
                            </a>
                            <a href="{{ route('public.schedule', ['month' => $nextMonth]) }}" class="mini-nav-btn" aria-label="Bulan berikutnya">
                                <svg width="14" height="14" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                            </a>
                        </div>
                    </div>

                    <div class="mini-day-names">
                        @foreach (['Min', 'Sen', 'Sel', 'Rab', 'Kam', 'Jum', 'Sab'] as $day)
                            <div>{{ $day }}</div>
                        @endforeach
                    </div>

                    <div class="mini-days" id="miniCalendarDays">
                        @foreach ($calendarDays as $day)
                            @if ($day === null)
                                <div class="mini-day-empty"></div>
                            @else
                                <a
                                    href="{{ route('public.schedule', ['date' => $day['date']]) }}"
                                    class="mini-day-btn {{ $day['isSelected'] ? 'selected' : '' }} {{ $day['isToday'] ? 'today' : '' }}"
                                    data-date="{{ $day['date'] }}"
                                    data-has-schedule="{{ ($day['hasSchedule'] ?? false) ? '1' : '0' }}"
                                >
                                    {{ $day['day'] }}
                                    @if ($day['hasSchedule'] ?? false)
                                        <div class="mini-day-dots"><span class="dot"></span></div>
                                    @endif
                                </a>
                            @endif
                        @endforeach
                    </div>
                </div>

                <div class="sidebar-section">
                    <div class="sidebar-section-title">Ringkasan</div>
                    <div class="stats-inline" id="statsBar">
                        <div class="stat-cell">
                            <strong>{{ $stats['total'] }}</strong>
                            <span>Total</span>
                        </div>
                        <div class="stat-cell featured">
                            <strong>{{ $stats['terjadwal'] }}</strong>
                            <span>Terjadwal</span>
                        </div>
                        <div class="stat-cell">
                            <strong>{{ $stats['selesai'] }}</strong>
                            <span>Selesai</span>
                        </div>
                    </div>
                </div>

                <div class="sidebar-section sidebar-section--filter">
                    <div class="sidebar-section-title">
                        <span>Filter</span>
                        <button type="button" class="sidebar-close-btn" id="closeMobileSidebar" aria-label="Tutup panel filter">
                            <svg width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 6l12 12M18 6l-12 12"/></svg>
                        </button>
                    </div>
                    <div class="filter-list">
                        <label class="filter-chip active">
                            <input type="checkbox" class="status-filter" value="terjadwal" checked>
                            <span class="filter-dot terjadwal"></span>
                            <span>Terjadwal</span>
                        </label>
                        <label class="filter-chip active">
                            <input type="checkbox" class="status-filter" value="selesai" checked>
                            <span class="filter-dot selesai"></span>
                            <span>Selesai</span>
                        </label>
                    </div>
                </div>
            </div>
        </aside>
        <div class="sidebar-backdrop" id="mobileSidebarBackdrop" aria-hidden="true"></div>

        <main class="schedule-main" id="scheduleMain">
            <div class="fullscreen-bar" id="fullscreenBar">
                <span class="fullscreen-bar-title">Jadwal Mingguan · {{ $weekLabel }}</span>
                <button type="button" class="btn-exit-fullscreen" id="exitFullscreen" aria-label="Keluar fullscreen">
                    <svg width="14" height="14" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                </button>
            </div>

            <div class="main-header">
                <div>
                    <h2>Jadwal Mingguan</h2>
                    <p>{{ $weekLabel }} · {{ $monthName }}</p>
                </div>
            </div>

            <div class="table-card">
                <div class="table-scroll">
                    <table class="week-table">
                        <thead>
                            <tr>
                                <th class="col-hari">Hari / Tanggal</th>
                                <th class="col-keterangan">Keterangan</th>
                                <th class="col-branch">Branch</th>
                                <th class="col-lokasi">Lokasi</th>
                                <th class="col-catatan">Catatan</th>
                                <th class="col-pic">PIC</th>
                                <th class="col-petugas">Petugas</th>
                                <th class="col-status">Status</th>
                            </tr>
                        </thead>
                        <tbody id="weekScheduleBody">
                            @foreach ($weekDays as $day)
                                @if (count($day['items']) === 0)
                                    <tr class="schedule-row schedule-row--empty {{ $day['isToday'] ? 'is-today' : '' }}" data-date="{{ $day['date'] }}">
                                        <td class="col-hari" data-label="Hari">
                                            <span class="day-label">{{ $day['dayName'] }}</span>
                                            <span class="day-date">{{ $day['dayNumber'] }} {{ $day['monthShort'] }}</span>
                                        </td>
                                        <td colspan="7" class="cell-empty">Tidak ada jadwal</td>
                                    </tr>
                                @else
                                    @foreach ($day['items'] as $item)
                                        <tr
                                            class="schedule-row {{ $day['isToday'] ? 'is-today' : '' }} {{ $loop->first ? 'is-first-day-item' : 'is-continuation-day-item' }} {{ $loop->last ? 'is-last-day-item' : '' }}"
                                            data-date="{{ $day['date'] }}"
                                            data-status="{{ $item['status'] ?? 'terjadwal' }}"
                                        >
                                            <td class="col-hari" data-label="Hari">
                                                <span class="day-label">{{ $day['dayName'] }}</span>
                                                <span class="day-date">{{ $day['dayNumber'] }} {{ $day['monthShort'] }}</span>
                                            </td>
                                            <td class="col-keterangan" data-label="Keterangan">
                                                <span class="cell-value cell-value--multiline">{{ $item['keterangan'] ?: '—' }}</span>
                                            </td>
                                            <td class="col-branch" data-label="Branch">
                                                <span class="cell-value">
                                                    @if ($item['branch'])
                                                        <span class="pill-tag">{{ $item['branch'] }}</span>
                                                    @else
                                                        —
                                                    @endif
                                                </span>
                                            </td>
                                            <td class="col-lokasi" data-label="Lokasi">
                                                <span class="cell-value cell-value--multiline">{{ $item['location'] ?: '—' }}</span>
                                            </td>
                                            <td class="col-catatan" data-label="Catatan">
                                                <span class="cell-value cell-value--multiline">{{ $item['catatan'] ?: '—' }}</span>
                                            </td>
                                            <td class="col-pic" data-label="PIC">
                                                <span class="cell-value">{{ $item['pic'] ?: '—' }}</span>
                                            </td>
                                            <td class="col-petugas" data-label="Petugas">
                                                <span class="cell-value">{{ $item['pekerja'] ?: '—' }}</span>
                                            </td>
                                            <td class="col-status" data-label="Status">
                                                <span class="cell-value">
                                                    <span class="status-badge status-{{ $item['status'] ?? 'terjadwal' }}">
                                                        {{ $item['status_label'] ?? 'Terjadwal' }}
                                                    </span>
                                                </span>
                                            </td>
                                        </tr>
                                    @endforeach
                                    <tr class="schedule-row schedule-row--placeholder" data-date="{{ $day['date'] }}">
                                        <td class="col-hari" data-label="Hari">
                                            <span class="day-label">{{ $day['dayName'] }}</span>
                                            <span class="day-date">{{ $day['dayNumber'] }} {{ $day['monthShort'] }}</span>
                                        </td>
                                        <td colspan="7" class="cell-empty">Tidak ada jadwal (filter aktif)</td>
                                    </tr>
                                @endif
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>

            <div class="live-hint">
                <span class="pulse-dot" aria-hidden="true"></span>
                <span>Data diperbarui otomatis setiap 20 detik</span>
            </div>
        </main>
    </div>
</div>

<div class="events-modal" id="eventsModal" aria-hidden="true">
    <div class="modal-overlay" id="modalOverlay"></div>
    <div class="modal-content" role="dialog" aria-modal="true" aria-labelledby="modalDate">
        <div class="modal-header">
            <h3 class="modal-title" id="modalDate"></h3>
            <button type="button" class="modal-close" id="modalClose" aria-label="Tutup">
                <svg width="20" height="20" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
            </button>
        </div>
        <div class="modal-body" id="modalBody"></div>
    </div>
</div>

<script type="application/json" id="eventsData">@json($eventsByDate ?? [])</script>
<script>
(() => {
    function loadEventsByDate() {
        const el = document.getElementById('eventsData');
        if (!el) return {};
        try { return JSON.parse(el.textContent || '{}'); }
        catch (_) { return {}; }
    }

    window.eventsByDate = loadEventsByDate();

    const POLL_INTERVAL = 20000;
    let pollTimer = null;
    let isUpdating = false;
    let activeStatuses = ['terjadwal', 'selesai'];

    function initFilters() {
        document.querySelectorAll('.filter-chip').forEach((chip) => {
            chip.addEventListener('click', (e) => {
                e.preventDefault();
                const input = chip.querySelector('input');
                input.checked = !input.checked;
                activeStatuses = Array.from(document.querySelectorAll('.status-filter:checked')).map((el) => el.value);
                if (activeStatuses.length === 0) {
                    input.checked = true;
                    activeStatuses = [input.value];
                }
                applyFilters();
            });
        });
    }

    function applyFilters() {
        document.querySelectorAll('.filter-chip').forEach((chip) => {
            const input = chip.querySelector('input');
            chip.classList.toggle('active', input.checked);
        });

        const dates = new Set();
        document.querySelectorAll('.schedule-row[data-status]').forEach((row) => dates.add(row.dataset.date));

        dates.forEach((date) => {
            const rows = Array.from(document.querySelectorAll(`.schedule-row[data-date="${date}"][data-status]`));
            let visibleCount = 0;

            rows.forEach((row) => {
                const show = activeStatuses.includes(row.dataset.status);
                row.classList.toggle('hidden-by-filter', !show);
                if (show) visibleCount += 1;
            });

            const visibleRows = rows.filter((row) => !row.classList.contains('hidden-by-filter'));
            rows.forEach((row) => {
                row.classList.remove('is-first-day-item', 'is-continuation-day-item', 'is-last-day-item');
            });
            visibleRows.forEach((row, index) => {
                if (index === 0) row.classList.add('is-first-day-item');
                else row.classList.add('is-continuation-day-item');
                if (index === visibleRows.length - 1) row.classList.add('is-last-day-item');
            });

            const placeholder = document.querySelector(`.schedule-row--placeholder[data-date="${date}"]`);
            if (placeholder) {
                placeholder.classList.toggle('visible', rows.length > 0 && visibleCount === 0);
            }
        });

        updateMiniCalendar();
    }

    function updateMiniCalendar() {
        document.querySelectorAll('.mini-day-btn').forEach((btn) => {
            const hasSchedule = btn.dataset.hasSchedule === '1';
            const isSpecial = btn.classList.contains('selected') || btn.classList.contains('today');
            btn.style.opacity = (hasSchedule || isSpecial) ? '1' : '0.4';
        });
    }

    async function fetchScheduleData() {
        if (isUpdating || document.hidden) return;
        isUpdating = true;
        try {
            const urlParams = new URLSearchParams(window.location.search);
            const response = await fetch(window.location.pathname + '?' + urlParams.toString(), {
                headers: { 'X-Requested-With': 'XMLHttpRequest', 'Accept': 'text/html' },
            });
            if (!response.ok) return;

            const html = await response.text();
            const doc = new DOMParser().parseFromString(html, 'text/html');

            const newBody = doc.querySelector('#weekScheduleBody');
            const oldBody = document.querySelector('#weekScheduleBody');
            if (newBody && oldBody) {
                oldBody.innerHTML = newBody.innerHTML;
                applyFilters();
            }

            const newStats = doc.querySelector('#statsBar');
            const oldStats = document.querySelector('#statsBar');
            if (newStats && oldStats) oldStats.innerHTML = newStats.innerHTML;

            const newMini = doc.querySelector('#miniCalendarDays');
            const oldMini = document.querySelector('#miniCalendarDays');
            if (newMini && oldMini) {
                oldMini.innerHTML = newMini.innerHTML;
                updateMiniCalendar();
            }

            const newEvents = doc.getElementById('eventsData');
            const oldEvents = document.getElementById('eventsData');
            if (newEvents && oldEvents) {
                oldEvents.textContent = newEvents.textContent;
                window.eventsByDate = loadEventsByDate();
            }
        } catch (_) {
            // ignore
        } finally {
            isUpdating = false;
        }
    }

    function openModal(date) {
        const modal = document.getElementById('eventsModal');
        const modalDate = document.getElementById('modalDate');
        const modalBody = document.getElementById('modalBody');
        const dateObj = new Date(date + 'T00:00:00');

        modalDate.textContent = dateObj.toLocaleDateString('id-ID', {
            weekday: 'long', year: 'numeric', month: 'long', day: 'numeric',
        });

        const events = window.eventsByDate?.[date];
        if (!events || events.length === 0) {
            modalBody.innerHTML = '<p style="text-align:center;color:var(--shade-50);padding:20px;">Tidak ada jadwal pada tanggal ini</p>';
        } else {
            modalBody.innerHTML = events.map((event) => {
                const statusClass = (event.status || 'terjadwal').replace(/ /g, '-');
                const statusLabel = event.status_label || ((event.status || 'terjadwal').charAt(0).toUpperCase() + (event.status || 'terjadwal').slice(1));
                return `
                    <div class="modal-event-item ${statusClass}">
                        <div class="modal-event-header">
                            <span class="status-badge status-${statusClass}">${statusLabel}</span>
                        </div>
                        <div class="modal-event-title">${event.time || '—'} · ${event.keterangan || 'Tanpa keterangan'}</div>
                        ${event.branch ? `<div class="modal-event-meta">Branch: ${event.branch}</div>` : ''}
                        ${event.location ? `<div class="modal-event-meta">${event.location}</div>` : ''}
                        ${event.catatan ? `<div class="modal-event-meta">Catatan: ${event.catatan}</div>` : ''}
                        ${event.pekerja ? `<div class="modal-event-meta">Petugas: ${event.pekerja}</div>` : ''}
                        ${event.pic ? `<div class="modal-event-meta">PIC: ${event.pic}</div>` : ''}
                    </div>
                `;
            }).join('');
        }

        modal.classList.add('active');
        modal.setAttribute('aria-hidden', 'false');
        document.body.style.overflow = 'hidden';
    }

    function closeModal() {
        const modal = document.getElementById('eventsModal');
        modal.classList.remove('active');
        modal.setAttribute('aria-hidden', 'true');
        const main = document.getElementById('scheduleMain');
        const sidebar = document.getElementById('filterSidebar');
        if (!main?.classList.contains('fullscreen-mode') && !sidebar?.classList.contains('is-open')) {
            document.body.style.overflow = '';
        }
    }

    function initModal() {
        document.getElementById('modalClose')?.addEventListener('click', closeModal);
        document.getElementById('modalOverlay')?.addEventListener('click', closeModal);
        document.addEventListener('keydown', (e) => {
            if (e.key !== 'Escape') return;
            const main = document.getElementById('scheduleMain');
            if (main?.classList.contains('fullscreen-mode')) {
                setFullscreen(false);
                return;
            }
            closeModal();
        });
    }

    function setFullscreen(enabled) {
        const main = document.getElementById('scheduleMain');
        if (!main) return;

        main.classList.toggle('fullscreen-mode', enabled);
        document.body.classList.toggle('is-schedule-fullscreen', enabled);

        if (enabled) {
            document.body.style.overflow = 'hidden';
            return;
        }

        const sidebar = document.getElementById('filterSidebar');
        const sidebarOpen = sidebar?.classList.contains('is-open');
        document.body.style.overflow = sidebarOpen ? 'hidden' : '';
    }

    function initFullscreen() {
        const enterBtns = [
            document.getElementById('toggleFullscreen'),
            document.getElementById('toggleFullscreenMobile'),
        ].filter(Boolean);
        const exitBtn = document.getElementById('exitFullscreen');
        const main = document.getElementById('scheduleMain');
        if (!main) return;

        const toggle = () => setFullscreen(!main.classList.contains('fullscreen-mode'));

        enterBtns.forEach((btn) => btn.addEventListener('click', toggle));
        exitBtn?.addEventListener('click', () => setFullscreen(false));
    }

    function initMobileSidebar() {
        const sidebar = document.getElementById('filterSidebar');
        const openBtn = document.getElementById('openMobileSidebar');
        const closeBtn = document.getElementById('closeMobileSidebar');
        const backdrop = document.getElementById('mobileSidebarBackdrop');
        if (!sidebar || !openBtn || !closeBtn || !backdrop) return;

        const setOpen = (open) => {
            if (window.innerWidth > 767) {
                sidebar.classList.remove('is-open');
                backdrop.classList.remove('is-visible');
                backdrop.setAttribute('aria-hidden', 'true');
                const main = document.getElementById('scheduleMain');
                if (!main?.classList.contains('fullscreen-mode')) {
                    document.body.style.overflow = '';
                }
                return;
            }
            sidebar.classList.toggle('is-open', open);
            backdrop.classList.toggle('is-visible', open);
            backdrop.setAttribute('aria-hidden', open ? 'false' : 'true');
            const main = document.getElementById('scheduleMain');
            if (main?.classList.contains('fullscreen-mode')) return;
            document.body.style.overflow = open ? 'hidden' : '';
        };

        openBtn.addEventListener('click', () => setOpen(true));
        closeBtn.addEventListener('click', () => setOpen(false));
        backdrop.addEventListener('click', () => setOpen(false));

        window.addEventListener('resize', () => {
            if (window.innerWidth > 767) setOpen(false);
        });
    }

    initFilters();
    applyFilters();
    initModal();
    initFullscreen();
    initMobileSidebar();
    pollTimer = setInterval(fetchScheduleData, POLL_INTERVAL);

    document.addEventListener('visibilitychange', () => {
        if (document.hidden) {
            if (pollTimer) { clearInterval(pollTimer); pollTimer = null; }
        } else {
            pollTimer = setInterval(fetchScheduleData, POLL_INTERVAL);
            fetchScheduleData();
        }
    });
})();
</script>
</body>
</html>

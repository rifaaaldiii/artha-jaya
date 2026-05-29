<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Artha Jaya - Update Status Jasa')</title>
    <link rel="icon" type="image/x-icon" href="{{ asset('favicon.ico') }}">
    <link rel="stylesheet" href="{{ asset('css/filament-style.css') }}">
    <style>
        :root {
            --aj-primary: #22c55e;
            --aj-primary-hover: #16a34a;
            --aj-primary-light: #f0fdf4;
            --aj-success: #10B981;
            --aj-success-light: #F0FDF4;
            --aj-surface: #ffffff;
            --aj-bg: #f8fafc;
            --aj-bg-gray: #f1f5f9;
            --aj-border: #e2e8f0;
            --aj-text: #0f172a;
            --aj-text-secondary: #64748b;
            --aj-text-muted: #94a3b8;
            --aj-error: #ef4444;
            --aj-error-light: #fef2f2;
            --aj-warning: #f59e0b;
            --aj-warning-light: #fffbeb;
            --aj-shadow-sm: 0 1px 2px 0 rgba(0, 0, 0, 0.05);
            --aj-shadow: 0 1px 3px 0 rgba(0, 0, 0, 0.1), 0 1px 2px -1px rgba(0, 0, 0, 0.1);
            --aj-radius: 0.5rem;
        }
        
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        input::-webkit-outer-spin-button,
        input::-webkit-inner-spin-button {
            -webkit-appearance: none;
            margin: 0;
        }

        /* Firefox */
        input[type=number] {
            -moz-appearance: textfield;
        }
        
        body {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', 'Roboto', 'Helvetica Neue', Arial, sans-serif;
            background: var(--aj-bg);
            color: var(--aj-text);
            line-height: 1.6;
            min-height: 100vh;
            font-size: 14px;
        }
        
        /* Header */
        .page-header {
            padding: 0px 32px;
        }
                
        .header-content {
            max-width: 1200px;
        }
                
        /* Filament Header */
        .fi-header {
            margin-bottom: 8px;
        }
        
        .fi-header-title {
            font-size: 24px;
            font-weight: 600;
            color: var(--aj-text);
            margin: 0 0 4px 0;
        }
        
        .fi-header-subtitle {
            font-size: 14px;
            color: var(--aj-text-secondary);
            margin: 0;
        }
        
        .fi-header-code {
            background: var(--aj-bg-gray);
            padding: 2px 8px;
            border-radius: 4px;
            font-family: 'Courier New', monospace;
            font-size: 13px;
            font-weight: 600;
            color: var(--aj-text);
        }
                
        /* Broadcast Banner - Sticky Top */
        .broadcast-banner {
            position: sticky;
            top: 0;
            z-index: 1000;
            background: linear-gradient(90deg, #DC2626 0%, #EF4444 50%, #DC2626 100%);
            border-bottom: 3px solid #B91C1C;
            padding: 0;
            margin-bottom: 8px;
        }
        .broadcast-container {
            margin: 0 auto;
            padding: 14px 20px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 16px;
        }
                
        .broadcast-icon {
            width: 24px;
            height: 24px;
            color: #FDE68A;
            flex-shrink: 0;
        }
                
        .broadcast-text-wrapper {
            flex: 1;
            min-width: 0;
            overflow: hidden;
            position: relative;
        }
                
        .broadcast-text-wrapper::before,
        .broadcast-text-wrapper::after {
            content: '';
            position: absolute;
            top: 0;
            bottom: 0;
            width: 40px;
            z-index: 2;
            pointer-events: none;
        }
                
        .broadcast-text-wrapper::before {
            left: 0;
            background: linear-gradient(to right, #DC2626, transparent);
        }
                
        .broadcast-text-wrapper::after {
            right: 0;
            background: linear-gradient(to left, #DC2626, transparent);
        }
                
        .broadcast-text-scroll {
            display: flex;
            width: max-content;
            animation: scroll-left-infinite 20s linear infinite;
        }
                
        .broadcast-text {
            display: inline-flex;
            align-items: center;
            gap: 10px;
            font-size: 15px;
            font-weight: 600;
            color: #FFFFFF;
            white-space: nowrap;
            text-shadow: 0 2px 4px rgba(0, 0, 0, 0.2);
            letter-spacing: 0.3px;
            flex-shrink: 0;
            padding-right: 80px;
        }
                
        .broadcast-text-icon {
            width: 16px;
            height: 16px;
            color: #FDE68A;
            flex-shrink: 0;
        }
                
        @keyframes scroll-left-infinite {
            0% {
                transform: translateX(0);
            }
            100% {
                transform: translateX(-50%);
            }
        }
                
        /* Catatan Box (legacy support) */
        .catatan-box {
            background: var(--aj-warning-light);
            border-radius: var(--aj-radius);
            padding: 16px;
            display: flex;
            align-items: flex-start;
            gap: 12px;
        }
                
        .catatan-icon {
            width: 18px;
            height: 18px;
            color: var(--aj-warning);
            flex-shrink: 0;
            margin-top: 1px;
        }
                
        .catatan-content {
            flex: 1;
            min-width: 0;
        }
                
        .catatan-label {
            font-size: 12px;
            font-weight: 600;
            color: #92400e;
            margin-bottom: 6px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }
                
        .catatan-text {
            font-size: 14px;
            color: #78350f;
            line-height: 1.6;
            word-break: break-word;
        }
        
        /* Container */
        .container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 32px;
        }
        
        /* Filament-style Form */
        .fi-form {
            /* max-width: 900px; */
            margin: 0 auto;
        }
        
        /* Grid Layout for Info + Items */
        .fi-grid-layout {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 16px;
        }
        
        
        /* Filament Section */
        .fi-section {
            background: var(--aj-surface);
            border-radius: var(--aj-radius);
            box-shadow: var(--aj-shadow-sm);
            margin-bottom: 16px;
            overflow: hidden;
        }
        
        .fi-section-header {
            padding: 8px 20px;
            border-bottom: 1px solid var(--aj-border);
        }
        
        .fi-section-header-heading {
            display: flex;
            align-items: center;
            gap: 10px;
            font-size: 16px;
            font-weight: 600;
            color: var(--aj-text);
            margin: 0 0 2px 0;
        }
        
        .fi-section-header-icon {
            color: var(--aj-text-secondary);
        }
        
        .fi-section-header-description {
            font-size: 13px;
            color: var(--aj-text-secondary);
            margin: 0;
        }
        
        .fi-section-content {
            padding: 16px 20px;
        }
        
        /* Info Grid */
        .fi-info-grid {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 12px;
        }
        
        .fi-info-grid-column {
            display: flex;
            flex-direction: column;
            justify-content: space-around;
            gap: 15px;
        }
        
        .fi-info-item {
            display: flex;
            align-items: flex-start;
            gap: 12px;
        }
        
        .fi-info-icon-wrapper {
            width: 36px;
            height: 36px;
            background: var(--aj-bg-gray);
            border-radius: var(--aj-radius);
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
        }
        
        .fi-info-icon {
            width: 18px;
            height: 18px;
            color: var(--aj-text-secondary);
        }
        
        .fi-info-content {
            flex: 1;
            min-width: 0;
        }
        
        .fi-info-label {
            font-size: 12px;
            color: var(--aj-text-secondary);
            margin-bottom: 4px;
            font-weight: 500;
        }
        
        .fi-info-value {
            font-size: 14px;
            font-weight: 500;
            color: var(--aj-text);
            word-break: break-word;
            line-height: 1.5;
        }
        
        .fi-info-value-mono {
            font-family: 'SF Mono', 'Monaco', 'Cascadia Code', monospace;
            font-size: 13px;
        }
        
        /* Filament Table */
        .fi-table-wrapper {
            overflow-x: auto;
            margin-top: 8px;
        }
        
        .fi-table {
            width: 100%;
            border-collapse: collapse;
            background: var(--aj-surface);
            border-radius: 8px;
            overflow: hidden;
        }
        
        .fi-table thead {
            background: linear-gradient(135deg, #f0fdfa, #ecfeff);
            border-bottom: 2px solid var(--aj-border);
        }
        
        .fi-table-header {
            padding: 14px 16px;
            text-align: left;
            font-size: 10px;
            font-weight: 500;
            color: var(--aj-text-secondary);
            text-transform: uppercase;
            letter-spacing: 0.5px;
            white-space: nowrap;
        }
        
        .fi-table-header[style*="text-align: center"] {
            text-align: center;
        }
        
        .fi-table-row {
            border-bottom: 1px solid var(--aj-border);
            transition: background-color 0.2s ease;
        }
        
        .fi-table-row:hover {
            background-color: var(--aj-bg-gray);
        }
        
        .fi-table-row:last-child {
            border-bottom: none;
        }
        
        .fi-table-cell {
            padding: 14px 16px;
            font-size: 12px;
            font-weight: 500;
            color: var(--aj-text);
            vertical-align: middle;
            line-height: 1.5;
        }
        
        .fi-table-cell-center {
            text-align: center;
            vertical-align: middle;
        }
        
        .fi-table-row-main .fi-table-cell {
            padding: 16px;
        }
        
        .fi-table-row-accessory .fi-table-cell {
            padding: 12px 16px;
        }
        
        .fi-table-badge {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            padding: 6px 14px;
            /* background: var(--aj-bg-gray); */
            color: var(--aj-text);
            font-size: 13px;
            font-weight: 600;
            border-radius: 6px;
            min-width: 80px;
        }
        
        /* Price Tag */
        .fi-price-tag {
            display: inline-block;
            padding: 6px 14px;
            /* background: var(--aj-bg-gray); */
            color: var(--aj-text);
            font-size: 13px;
            font-weight: 600;
            border-radius: 6px;
            min-width: 120px;
            text-align: center;
        }
        
        /* Main Item Row */
        .fi-table-row-main {
            background: var(--aj-surface);
        }
        
        .fi-table-row-main:hover {
            background: var(--aj-bg-gray);
        }
        
        .fi-table-row-main td {
            padding: 16px;
            font-weight: 600;
        }
        
        /* Accessory Row */
        .fi-table-row-accessory {
            background: #fafbfc;
            border-bottom: 1px solid var(--aj-border);
        }
        
        .fi-table-row-accessory:hover {
            /* background: #f5f7fa; */
        }
        
        .fi-accessory-cell {
            color: var(--aj-text);
            font-size: 13px;
            font-weight: 500;
        }
        
        .fi-accessory-icon {
            color: var(--aj-text-muted);
            font-weight: 400;
            margin-right: 6px;
            font-size: 16px;
            display: inline-block;
            width: 16px;
            text-align: center;
        }
        
        /* Quantity Input */
        .fi-quantity-input {
            width: 100%;
            max-width: 80px;
            margin: 0 auto;
            display: block;
            padding: 6px 0;
            border: 1.5px solid var(--aj-border);
            border-radius: 5px;
            font-size: 12px;
            font-weight: 600;
            color: var(--aj-text);
            background: var(--aj-surface);
            transition: all 0.2s ease;
            text-align: center;
        }
        
        .fi-quantity-input:focus {
            outline: none;
        }
        
        .fi-table-empty {
            text-align: center;
            padding: 40px 20px;
        }
        
        .fi-table-empty svg {
            color: var(--aj-text-muted);
            margin-bottom: 8px;
        }
        
        .fi-table-empty-text {
            font-size: 14px;
            color: var(--aj-text-muted);
            font-weight: 500;
            margin: 0;
        }
        
        .jasa-items-table {
            width: 100%;
            border-collapse: collapse;
        }
        
        .jasa-items-table thead {
            /* background: var(--aj-bg); */
            background: linear-gradient(135deg, #f0fdfa, #ecfeff);
        }
        
        .jasa-items-table th {
            padding: 12px 16px;
            font-size: 12px;
            font-weight: 600;
            color: var(--aj-text-secondary);
            text-align: left;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            border-bottom: 1px solid var(--aj-border);
        }
        
        .th-qty {
            width: 80px;
            text-align: center !important;
        }
        
        .jasa-items-table td {
            padding: 12px 16px;
            font-size: 14px;
            color: var(--aj-text);
            border-bottom: 1px solid var(--aj-divider);
        }
        
        .td-qty {
            text-align: center;
            font-weight: 600;
        }
        
        .td-item {
            font-size: 12px;
            font-weight: 500;
            color: var(--aj-text);
            word-break: break-word;
            line-height: 1.5;
        }
        
        .td-empty {
            text-align: center;
            color: var(--aj-text-muted);
            padding: 24px 16px !important;
        }
        
        .jasa-items-table tbody tr:last-child td {
            border-bottom: none;
        }
        
        /* Catatan Box */
        .catatan-box {
            background: linear-gradient(135deg, #FFF7ED 0%, #FFFBEB 100%);
            border: 1px solid #FED7AA;
            border-radius: var(--aj-radius);
            padding: 16px;
            display: flex;
            align-items: flex-start;
            gap: 12px;
        }
        
        .catatan-icon {
            width: 18px;
            height: 18px;
            color: #F59E0B;
            flex-shrink: 0;
            margin-top: 1px;
        }
        
        .catatan-content {
            flex: 1;
            min-width: 0;
        }
        
        .catatan-label {
            font-size: 12px;
            font-weight: 600;
            color: #B45309;
            margin-bottom: 6px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }
        
        .catatan-text {
            font-size: 14px;
            color: #92400E;
            line-height: 1.6;
            word-break: break-word;
        }

        /* Catatan note */
        .catatan-note {
            background: linear-gradient(135deg, #FEF2F2 0%, #FEE2E2 100%);
            border: 1px solid #FCA5A5;
            border-radius: var(--aj-radius);
            padding: 16px;
            display: flex;
            align-items: flex-start;
            gap: 12px;
        }
        
        /* Broadcast Banner - Sticky Top */
        .broadcast-banner {
            position: sticky;
            top: 0;
            z-index: 1000;
            background: linear-gradient(90deg, #DC2626 0%, #EF4444 50%, #DC2626 100%);
            border-bottom: 3px solid #B91C1C;
            padding: 0;
            margin-bottom: 10px;
            animation: broadcast-glow 2s ease-in-out infinite;
        }
        .broadcast-container {
            /* max-width: 1200px; */
            margin: 0 auto;
            padding: 14px 20px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 16px;
        }
        
        .broadcast-icon {
            width: 24px;
            height: 24px;
            color: #FDE68A;
            flex-shrink: 0;
            animation: broadcast-pulse 1.5s ease-in-out infinite;
            filter: drop-shadow(0 2px 4px rgba(0, 0, 0, 0.3));
        }
        
        @keyframes broadcast-pulse {
            0%, 100% { transform: scale(1); }
            50% { transform: scale(1.15); }
        }
        
        @keyframes label-blink {
            0%, 100% { opacity: 1; }
            50% { opacity: 0.7; }
        }
        
        .broadcast-text-wrapper {
            flex: 1;
            min-width: 0;
            overflow: hidden;
            position: relative;
        }
        
        .broadcast-text-wrapper::before,
        .broadcast-text-wrapper::after {
            content: '';
            position: absolute;
            top: 0;
            bottom: 0;
            width: 40px;
            z-index: 2;
            pointer-events: none;
        }
        
        .broadcast-text-wrapper::before {
            left: 0;
            background: linear-gradient(to right, #DC2626, transparent);
        }
        
        .broadcast-text-wrapper::after {
            right: 0;
            background: linear-gradient(to left, #DC2626, transparent);
        }
        

        .broadcast-text-scroll {
            display: flex;
            width: max-content;
            animation: scroll-left-infinite 20s linear infinite;
        }
        
        .broadcast-text {
            display: inline-flex;
            align-items: center;
            gap: 10px;
            font-size: 15px;
            font-weight: 600;
            color: #FFFFFF;
            white-space: nowrap;
            text-shadow: 0 2px 4px rgba(0, 0, 0, 0.2);
            letter-spacing: 0.3px;
            flex-shrink: 0;
            padding-right: 80px;
        }
        
        .broadcast-text-icon {
            width: 16px;
            height: 16px;
            color: #FDE68A;
            flex-shrink: 0;
        }
        
        @keyframes scroll-left-infinite {
            0% {
                transform: translateX(0);
            }
            100% {
                transform: translateX(-50%);
            }
        }
        
        .catatan-icon-note {
            width: 18px;
            height: 18px;
            color: #F59E0B;
            flex-shrink: 0;
            margin-top: 1px;
        }
        
        .catatan-content-note {
            flex: 1;
            min-width: 0;
            overflow: hidden;
        }
        
        .catatan-label-note {
            font-size: 12px;
            font-weight: 600;
            color: #B45309;
            margin-bottom: 6px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }
        
        /* Scrolling container */
        .catatan-text-note-scroll {
            overflow: hidden;
            white-space: nowrap;
            position: relative;
            width: 100%;
        }
        
        .catatan-text-note {
            display: inline-block;
            font-size: 14px;
            color: #000000;
            line-height: 1.6;
            word-break: break-word;
            white-space: nowrap;
            animation: scroll-text 15s linear infinite;
            padding-right: 100px;
        }
        
        @keyframes scroll-text {
            0% {
                transform: translateX(100%);
            }
            100% {
                transform: translateX(-100%);
            }
        }
        
        /* Alert */
        .alert {
            padding: 12px 16px;
            border-radius: var(--aj-radius);
            display: flex;
            align-items: flex-start;
            gap: 10px;
        }
        
        .alert-error {
            background: var(--aj-error-light);
            border: 1px solid #FECACA;
        }
        
        .alert-warning {
            background: var(--aj-error-bg);
            border: 1px solid var(--aj-error-border);
        }
        
        .alert-icon {
            font-size: 16px;
            flex-shrink: 0;
            margin-top: 1px;
        }
        
        .alert-content strong {
            display: block;
            font-size: 13px;
            font-weight: 600;
            color: var(--aj-error);
            margin-bottom: 4px;
        }
        
        .alert-content p {
            font-size: 13px;
            color: var(--aj-error);
            line-height: 1.5;
        }
        
        /* Schedule Box */
        .schedule-box {
            background: var(--aj-primary-light);
            border: 1px solid var(--aj-primary-border);
            border-radius: var(--aj-radius);
            padding: 12px 16px;
        }
        
        .schedule-label {
            font-size: 12px;
            color: var(--aj-primary);
            font-weight: 500;
            margin-bottom: 4px;
        }
        
        .schedule-value {
            font-size: 14px;
            color: var(--aj-primary);
            font-weight: 600;
        }
        
        /* Upload Section */
        .upload-section {
            margin-bottom: 32px;
        }
        
        .section-header {
            margin-bottom: 16px;
        }
        
        .section-title {
            font-size: 15px;
            font-weight: 600;
            color: var(--aj-primary);
            margin-bottom: 4px;
        }
        
        .section-hint {
            font-size: 12px;
            color: var(--aj-text-muted);
        }
        
        .upload-area {
            border: 2px dashed var(--aj-border);
            border-radius: var(--aj-radius);
            padding: 40px 24px;
            text-align: center;
            cursor: pointer;
            transition: all 0.2s;
            background: var(--aj-bg);
        }
        
        .upload-area:hover {
            border-color: var(--aj-primary);
            background: var(--aj-primary-light);
        }
        
        .upload-area.dragover {
            border-color: var(--aj-primary);
            background: var(--aj-primary-light);
        }
        
        .upload-area input[type="file"] {
            display: none;
        }
        
        .upload-icon {
            width: 48px;
            height: 48px;
            margin: 0 auto 12px;
            color: var(--aj-primary);
        }
        
        .upload-text {
            font-size: 14px;
            color: var(--aj-text);
            margin-bottom: 4px;
            font-weight: 500;
        }
        
        .upload-divider {
            font-size: 13px;
            color: var(--aj-text-muted);
            margin-bottom: 12px;
        }
        
        .upload-button {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            background: var(--aj-primary);
            color: white;
            padding: 10px 20px;
            border-radius: var(--aj-radius);
            font-size: 14px;
            font-weight: 500;
            transition: background 0.2s;
        }
        
        .upload-button:hover {
            background: var(--aj-primary-hover);
        }
        
        /* Preview Grid */
        .preview-section {
            margin-top: 20px;
        }
        
        .preview-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 12px;
        }
        
        .preview-title {
            font-size: 13px;
            font-weight: 600;
            color: var(--aj-text);
        }
        
        .preview-count {
            font-size: 12px;
            color: var(--aj-text-muted);
        }
        
        .preview-grid {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 12px;
        }
        
        .preview-item {
            position: relative;
            aspect-ratio: 1;
            border-radius: var(--aj-radius);
            overflow: hidden;
            border: 1px solid var(--aj-border);
        }
        
        .preview-item img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }
        
        .preview-remove {
            position: absolute;
            top: 6px;
            right: 6px;
            width: 24px;
            height: 24px;
            background: rgba(0, 0, 0, 0.7);
            color: white;
            border: none;
            border-radius: 50%;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 18px;
            line-height: 1;
            transition: background 0.2s;
        }
        
        .preview-remove:hover {
            background: rgba(0, 0, 0, 0.9);
        }
        
        .preview-add {
            aspect-ratio: 1;
            border: 2px dashed var(--aj-border);
            border-radius: var(--aj-radius);
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            gap: 6px;
            cursor: pointer;
            transition: all 0.2s;
            background: var(--aj-bg);
        }
        
        .preview-add:hover {
            border-color: var(--aj-primary);
            background: var(--aj-primary-light);
        }
        
        .preview-add-icon {
            width: 28px;
            height: 28px;
            color: var(--aj-primary);
        }
        
        .preview-add-text {
            font-size: 11px;
            color: var(--aj-text-secondary);
            text-align: center;
            line-height: 1.4;
        }
        
        .preview-hint {
            margin-top: 10px;
            font-size: 12px;
            color: var(--aj-text-muted);
            display: flex;
            align-items: center;
            gap: 6px;
        }
        
        /* Form Group */
        .form-group {
            margin-bottom: 24px;
        }
        
        .form-label {
            display: block;
            font-size: 14px;
            font-weight: 600;
            color: var(--aj-primary);
            margin-bottom: 8px;
        }
        
        .form-label-hint {
            font-weight: 400;
            color: var(--aj-text-muted);
            font-size: 13px;
        }
        
        .form-textarea {
            width: 100%;
            padding: 12px;
            border: 1px solid var(--aj-border);
            border-radius: var(--aj-radius);
            font-size: 14px;
            font-family: inherit;
            resize: vertical;
            transition: border-color 0.2s;
            color: var(--aj-text);
            background: var(--aj-surface);
        }
        
        .form-textarea:focus {
            outline: none;
            border-color: var(--aj-primary);
            box-shadow: 0 0 0 3px rgba(37, 99, 235, 0.1);
        }
        
        .char-counter {
            text-align: right;
            font-size: 12px;
            color: var(--aj-text-muted);
            margin-top: 6px;
        }
        
        /* Confirmation */
        .confirmation-box {
            background: var(--aj-success-light);
            border: 1px solid var(--aj-success-border);
            border-radius: var(--aj-radius);
            padding: 16px;
            margin-bottom: 24px;
        }
        
        .confirmation-label {
            display: flex;
            align-items: flex-start;
            gap: 12px;
            cursor: pointer;
        }
        
        .confirmation-icon {
            width: 20px;
            height: 20px;
            color: var(--aj-success);
            flex-shrink: 0;
            margin-top: 1px;
        }
        
        .confirmation-text {
            flex: 1;
            font-size: 13px;
            color: var(--aj-text);
            line-height: 1.6;
        }
        
        .confirmation-checkbox {
            width: 18px;
            height: 18px;
            cursor: pointer;
            flex-shrink: 0;
            margin-top: 2px;
        }
        
        /* Buttons */
        .button-group {
            display: flex;
            gap: 12px;
        }
        
        .btn {
            padding: 12px 24px;
            border-radius: var(--aj-radius);
            font-size: 14px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.2s;
            border: none;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
        }
        
        .btn-secondary {
            background: white;
            color: var(--aj-text-secondary);
            border: 1px solid var(--aj-border);
            padding: 12px 20px;
        }
        
        .btn-secondary:hover {
            background: var(--aj-bg);
            border-color: var(--aj-text-muted);
        }
        
        .btn-primary {
            background: var(--aj-primary);
            color: white;
            flex: 1;
        }
        
        .btn-primary:hover:not(:disabled) {
            background: var(--aj-primary-hover);
        }
        
        .btn-primary:disabled {
            opacity: 0.5;
            cursor: not-allowed;
        }
        
        /* Success Page */
        .success-card {
            text-align: center;
            padding: 80px 32px;
            max-width: 600px;
            margin: 0 auto;
        }
        
        .success-icon-large {
            width: 72px;
            height: 72px;
            background: var(--aj-success);
            color: white;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 36px;
            margin: 0 auto 24px;
        }
        
        .success-title {
            font-size: 28px;
            font-weight: 700;
            color: var(--aj-text);
            margin-bottom: 16px;
        }
        
        .success-text {
            font-size: 15px;
            color: var(--aj-text-secondary);
            line-height: 1.7;
        }
        
        .success-text strong {
            color: var(--aj-text);
            font-weight: 600;
        }
        
        .success-highlight {
            color: var(--aj-success);
            font-size: 18px;
            font-weight: 700;
            display: inline-block;
            margin-top: 8px;
        }
        
        .completion-notes {
            background: var(--aj-bg);
            border: 1px solid var(--aj-border);
            border-radius: var(--aj-radius);
            padding: 16px;
            margin-top: 24px;
            text-align: left;
        }
        
        .completion-notes strong {
            display: block;
            font-size: 14px;
            font-weight: 600;
            color: var(--aj-text);
            margin-bottom: 8px;
        }
        
        .completion-notes p {
            font-size: 14px;
            color: var(--aj-text-secondary);
            line-height: 1.6;
        }

        /* ============================================
        FILAMENT-STYLE UI COMPONENTS
        Clean, minimal, professional design
        ============================================ */

        /* Items List */
        .fi-items-list {
            display: flex;
            flex-direction: column;
            gap: 12px;
        }

        .fi-item-row {
            display: flex;
            align-items: center;
            gap: 14px;
            padding: 14px 16px;
            background: var(--aj-bg-gray);
            border-radius: var(--aj-radius);
        }

        .fi-item-index {
            width: 32px;
            height: 32px;
            background: var(--aj-text-secondary);
            color: white;
            border-radius: var(--aj-radius);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 13px;
            font-weight: 600;
            flex-shrink: 0;
        }

        .fi-item-content {
            flex: 1;
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 12px;
        }

        .fi-item-name {
            font-size: 14px;
            font-weight: 500;
            color: var(--aj-text);
            flex: 1;
        }

        .fi-item-badge {
            font-size: 12px;
            font-weight: 600;
            color: var(--aj-text-secondary);
            background: var(--aj-surface);
            padding: 4px 12px;
            border-radius: var(--aj-radius);
            white-space: nowrap;
        }

        .fi-empty-state {
            text-align: center;
            padding: 40px 20px;
        }

        .fi-empty-state svg {
            color: var(--aj-text-muted);
            margin-bottom: 12px;
        }

        .fi-empty-state-text {
            font-size: 14px;
            color: var(--aj-text-muted);
            font-weight: 500;
        }

        /* Upload Area */
        .fi-upload-area {
            border: 2px dashed var(--aj-border);
            border-radius: var(--aj-radius);
            padding: 40px 24px;
            text-align: center;
            cursor: pointer;
            transition: border-color 0.15s ease;
            background: var(--aj-bg);
        }

        .fi-upload-area:hover {
            border-color: var(--aj-text-muted);
        }

        .fi-upload-area.dragover {
            border-color: var(--aj-primary);
            background: var(--aj-primary-light);
        }

        .fi-upload-area input[type="file"] {
            display: none;
        }

        .fi-upload-content {
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 16px;
        }

        .fi-upload-icon {
            width: 48px;
            height: 48px;
            color: var(--aj-text-secondary);
        }

        .fi-upload-text {
            font-size: 14px;
            color: var(--aj-text);
            margin: 0;
            font-weight: 500;
        }

        .fi-upload-divider {
            display: flex;
            align-items: center;
            gap: 12px;
            width: 100%;
            max-width: 200px;
        }

        .fi-upload-divider::before,
        .fi-upload-divider::after {
            content: '';
            flex: 1;
            height: 1px;
            background: var(--aj-border);
        }

        .fi-upload-divider span {
            font-size: 13px;
            color: var(--aj-text-muted);
            font-weight: 500;
        }

        .fi-upload-button {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            background: var(--aj-surface);
            color: var(--aj-text);
            padding: 10px 20px;
            border-radius: var(--aj-radius);
            font-size: 14px;
            font-weight: 500;
            border: 1px solid var(--aj-border);
            cursor: pointer;
            transition: all 0.15s ease;
        }

        .fi-upload-button:hover {
            background: var(--aj-bg-gray);
            border-color: var(--aj-text-muted);
        }

        /* Preview Section */
        .fi-preview-section {
            margin-top: 24px;
        }

        .fi-preview-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 12px;
        }

        .fi-preview-label {
            font-size: 14px;
            font-weight: 600;
            color: var(--aj-text);
        }

        .fi-preview-count {
            font-size: 13px;
            color: var(--aj-text-secondary);
            font-weight: 500;
        }

        .fi-preview-grid {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 12px;
        }

        .fi-preview-item {
            position: relative;
            aspect-ratio: 1;
            border-radius: var(--aj-radius);
            overflow: hidden;
            border: 1px solid var(--aj-border);
        }

        .fi-preview-item img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .fi-preview-remove {
            position: absolute;
            top: 6px;
            right: 6px;
            width: 24px;
            height: 24px;
            background: rgba(0, 0, 0, 0.7);
            color: white;
            border: none;
            border-radius: var(--aj-radius);
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 18px;
            line-height: 1;
            transition: background 0.15s ease;
        }

        .fi-preview-remove:hover {
            background: rgba(0, 0, 0, 0.9);
        }

        .fi-preview-add {
            aspect-ratio: 1;
            border: 2px dashed var(--aj-border);
            border-radius: var(--aj-radius);
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            gap: 6px;
            cursor: pointer;
            transition: all 0.15s ease;
            background: var(--aj-bg);
        }

        .fi-preview-add:hover {
            border-color: var(--aj-text-muted);
            background: var(--aj-bg-gray);
        }

        .fi-preview-add-icon {
            width: 28px;
            height: 28px;
            color: var(--aj-text-secondary);
        }

        .fi-preview-add-text {
            font-size: 11px;
            color: var(--aj-text-secondary);
            text-align: center;
            line-height: 1.4;
        }

        .fi-preview-hint {
            margin-top: 10px;
            font-size: 13px;
            color: var(--aj-text-secondary);
        }

        /* Textarea */
        .fi-textarea-wrapper {
            position: relative;
        }

        .fi-textarea {
            width: 100%;
            padding: 12px;
            border: 1px solid var(--aj-border);
            border-radius: var(--aj-radius);
            font-size: 14px;
            font-family: inherit;
            resize: vertical;
            transition: border-color 0.15s ease;
            color: var(--aj-text);
            background: var(--aj-surface);
        }

        .fi-textarea:focus {
            outline: none;
            border-color: var(--aj-text-muted);
        }

        .fi-char-counter {
            text-align: right;
            font-size: 12px;
            color: var(--aj-text-muted);
            margin-top: 6px;
        }

        /* Error Message */
        .fi-error-message {
            color: var(--aj-error);
            font-size: 14px;
            margin-top: 12px;
        }

        /* Confirmation Box */
        .fi-confirm-box {
            background: var(--aj-success-light);
            border-radius: var(--aj-radius);
            padding: 16px;
            margin-bottom: 24px;
        }

        .fi-confirm-label {
            display: flex;
            align-items: flex-start;
            gap: 12px;
            cursor: pointer;
        }

        .fi-checkbox-wrapper {
            position: relative;
            width: 20px;
            height: 20px;
            flex-shrink: 0;
            margin-top: 2px;
        }

        .fi-checkbox {
            position: absolute;
            width: 20px;
            height: 20px;
            opacity: 0;
            cursor: pointer;
            z-index: 2;
        }

        .fi-checkbox-wrapper::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 20px;
            height: 20px;
            background: var(--aj-surface);
            border: 1px solid var(--aj-border);
            border-radius: 4px;
            transition: all 0.15s ease;
        }

        .fi-checkbox:checked + .fi-checkbox-icon,
        .fi-checkbox-wrapper:has(.fi-checkbox:checked)::before {
            background: var(--aj-primary);
            border-color: var(--aj-primary);
        }

        .fi-checkbox-icon {
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            width: 14px;
            height: 14px;
            color: white;
            opacity: 0;
            transition: opacity 0.15s ease;
            pointer-events: none;
            z-index: 1;
        }

        .fi-checkbox:checked + .fi-checkbox-icon {
            opacity: 1;
        }

        .fi-confirm-text {
            flex: 1;
            font-size: 14px;
            color: var(--aj-text);
            line-height: 1.6;
        }

        /* Buttons */
        .fi-button-group {
            display: flex;
            gap: 12px;
        }

        .fi-btn {
            padding: 10px 20px;
            border-radius: var(--aj-radius);
            font-size: 14px;
            font-weight: 500;
            cursor: pointer;
            transition: all 0.15s ease;
            border: none;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
        }

        .fi-btn-secondary {
            background: var(--aj-surface);
            color: var(--aj-text-secondary);
            border: 1px solid var(--aj-border);
        }

        .fi-btn-secondary:hover {
            background: var(--aj-bg-gray);
            border-color: var(--aj-text-muted);
        }

        .fi-btn-primary {
            background: var(--aj-primary);
            color: white;
            flex: 1;
        }

        .fi-btn-primary:hover:not(:disabled) {
            background: var(--aj-primary-hover);
        }

        .fi-btn-primary:disabled {
            opacity: 0.5;
            cursor: not-allowed;
        }

        .fi-optional-badge {
            font-size: 12px;
            font-weight: 400;
            color: var(--aj-text-muted);
        }

        /* Action Buttons */
        .fi-actions {
            display: flex;
            gap: 12px;
            margin-top: 24px;
        }

        .fi-action-btn {
            flex: 1;
            padding: 12px 24px;
            border-radius: 8px;
            font-size: 15px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.15s ease;
            border: none;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            line-height: 1.4;
        }

        .fi-action-btn-secondary {
            background: #fff;
            color: #475569;
            border: 1.5px solid #cbd5e1;
        }

        .fi-action-btn-secondary:hover {
            background: #f8fafc;
            border-color: #94a3b8;
        }

        .fi-action-btn-secondary:active {
            background: #f1f5f9;
        }

        .fi-action-btn-primary {
            background: var(--aj-primary);
            color: #fff;
            box-shadow: 0 1px 3px rgba(34, 197, 94, 0.3);
        }

        .fi-action-btn-primary:hover:not(:disabled) {
            background: var(--aj-primary-hover);
            box-shadow: 0 2px 6px rgba(34, 197, 94, 0.4);
            transform: translateY(-1px);
        }

        .fi-action-btn-primary:active:not(:disabled) {
            transform: translateY(0);
        }

        .fi-action-btn-primary:disabled {
            opacity: 0.5;
            cursor: not-allowed;
        }

        /* Success Page */
        .fi-success-container {
            max-width: 600px;
            margin: 0 auto;
            text-align: center;
            padding: 60px 24px;
        }

        .fi-success-icon-wrapper {
            position: relative;
            display: inline-block;
            margin-bottom: 24px;
        }

        .fi-success-icon {
            width: 64px;
            height: 64px;
            background: var(--aj-success);
            border-radius: var(--aj-radius);
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            margin: 0 auto;
        }

        .fi-success-icon svg {
            width: 32px;
            height: 32px;
        }

        .fi-success-title {
            font-size: 24px;
            font-weight: 600;
            color: var(--aj-text);
            margin: 0 0 20px 0;
        }

        .fi-success-card {
            background: var(--aj-surface);
            border-radius: var(--aj-radius);
            box-shadow: var(--aj-shadow-sm);
            padding: 24px;
            margin-bottom: 20px;
        }

        .fi-success-text {
            font-size: 15px;
            color: var(--aj-text-secondary);
            line-height: 1.7;
            margin: 0 0 16px 0;
        }

        .fi-success-ref {
            color: var(--aj-success);
            font-weight: 600;
            font-family: 'SF Mono', 'Monaco', 'Cascadia Code', monospace;
        }

        .fi-status-badge {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            background: var(--aj-success-light);
            color: var(--aj-success);
            padding: 10px 20px;
            border-radius: var(--aj-radius);
            font-size: 14px;
            font-weight: 600;
        }

        .fi-success-footer {
            font-size: 14px;
            color: var(--aj-text-secondary);
            margin: 0;
        }

        /* Alert */
        .fi-alert {
            padding: 12px 16px;
            border-radius: var(--aj-radius);
            display: flex;
            align-items: flex-start;
            gap: 10px;
        }

        .fi-alert-danger {
            background: var(--aj-error-light);
        }

        .fi-alert-icon {
            font-size: 16px;
            flex-shrink: 0;
            margin-top: 1px;
        }

        .fi-alert-content strong {
            display: block;
            font-size: 14px;
            font-weight: 600;
            color: var(--aj-error);
            margin-bottom: 4px;
        }

        .fi-alert-content p {
            font-size: 14px;
            color: var(--aj-error);
            line-height: 1.5;
            margin: 0;
        }
        
        /* Responsive - Mobile */
        
        @media (max-width: 1024px) {
            .fi-grid-layout {
                grid-template-columns: 1fr;
            }

            .fi-info-grid {
                grid-template-columns: 2fr;
            }
        }

        @media (max-width: 768px) {
            .fi-table-header,
            .fi-table-cell {
                padding: 10px 12px;
            }
            
            .fi-table-header {
                font-size: 12px;
            }
            
            .fi-table-cell {
                font-size: 14px;
            }

            .header-subtitle {
                display: none;
            }
            .page-header {
                padding: 16px 20px;
            }
            
            .header-content {
                flex-direction: column;
                gap: 12px;
            }
            
            .header-icon-wrapper {
                gap: 12px;
            }
            
            .fi-header-title {
                font-size: 20px;
            }
            
            .fi-header-subtitle {
                font-size: 13px;
            }
            
            .fi-header-code {
                font-size: 12px;
            }
            
            .header-right {
                text-align: left;
            }
            
            .container {
                padding: 20px 16px;
            }
            
            .card-body {
                padding: 20px;
            }
            
            .info-layout {
                grid-template-columns: 1fr;
                gap: 24px;
            }
            
            .upload-area {
                padding: 32px 20px;
            }
            
            .preview-grid {
                grid-template-columns: repeat(3, 1fr);
            }
            
            .button-group {
                flex-direction: column-reverse;
            }
            
            .btn-secondary {
                width: 100%;
            }
            
            .btn-primary {
                width: 100%;
            }
            
            .success-card {
                padding: 48px 20px;
            }
            
            .success-title {
                font-size: 24px;
            }
            
            /* Jasa Items Table - Mobile */
            .jasa-items-table th,
            .jasa-items-table td {
                padding: 10px 12px;
                font-size: 13px;
            }
            
            .jasa-items-title {
                font-size: 13px;
                padding: 10px 12px;
            }
            
            /* Catatan Box - Mobile */
            .catatan-box {
                padding: 14px;
                gap: 10px;
            }
            
            .catatan-icon {
                width: 16px;
                height: 16px;
            }
            
            .catatan-label {
                font-size: 11px;
            }
            
            .catatan-text {
                font-size: 13px;
            }

            /* Catatan Note - Mobile */
            .catatan-note {
                padding: 14px;
                gap: 10px;
            }
            
            /* Broadcast Banner - Mobile */
            .broadcast-banner {
                margin-bottom: 16px;
            }
            
            .broadcast-container {
                padding: 12px 16px;
                gap: 12px;
            }
            
            .broadcast-icon {
                width: 20px;
                height: 20px;
            }
            
            .broadcast-text {
                font-size: 13px;
                animation-duration: 10s;
            }
            .page-header {
                padding: 16px 20px;
            }
            
            .header-icon-wrapper {
                gap: 12px;
            }
            
            .header-icon-circle {
                width: 40px;
                height: 40px;
            }
            
            .header-icon-circle svg {
                width: 20px;
                height: 20px;
            }
            
            .header-title {
                font-size: 18px;
            }
            
            .header-subtitle {
                font-size: 12px;
            }
            
            .container {
                padding: 20px 16px;
            }
            
            .fi-section-content {
                padding: 20px;
            }
            
            .fi-section-header {
                padding: 16px 20px;
            }
            
            .fi-info-grid {
                grid-template-columns: 1fr;
                gap: 16px;
            }
            
            .fi-upload-area {
                padding: 32px 20px;
            }
            
            .fi-preview-grid {
                grid-template-columns: repeat(3, 1fr);
            }
            
            .fi-button-group {
                flex-direction: column-reverse;
            }
            
            .fi-btn-secondary {
                width: 100%;
            }
            
            .fi-btn-primary {
                width: 100%;
            }
            
            .fi-success-container {
                padding: 40px 16px;
            }
            
            .fi-success-title {
                font-size: 20px;
            }
            
            .fi-success-icon {
                width: 56px;
                height: 56px;
            }
            
            .fi-success-icon svg {
                width: 28px;
                height: 28px;
            }
        }

        @media (max-width: 640px) {
            .fi-actions {
                flex-direction: column-reverse;
            }

            .fi-action-btn {
                width: 100%;
                padding: 14px 24px;
            }
        }

    </style>
    @stack('styles')
</head>
<body>
    <div class="page-header">
        <div class="header-content">
            @yield('header')
        </div>
    </div>
    
    <div class="container">
        @yield('content')
    </div>
    
    @stack('scripts')
</body>
</html>
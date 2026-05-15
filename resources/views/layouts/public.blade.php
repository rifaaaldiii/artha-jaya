<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Artha Jaya - Update Status Jasa')</title>
    <link rel="icon" type="image/x-icon" href="{{ asset('favicon.ico') }}">
    <style>
        :root {
            --aj-primary: #22c55e;
            --aj-primary-hover: #1D4ED8;
            --aj-primary-light: #EFF6FF;
            --aj-primary-border: #DBEAFE;
            --aj-success: #10B981;
            --aj-success-light: #F0FDF4;
            --aj-success-border: #BBF7D0;
            --aj-surface: #ffffff;
            --aj-bg: #F8FAFC;
            --aj-border: #E2E8F0;
            --aj-divider: #F1F5F9;
            --aj-text: #0F172A;
            --aj-text-secondary: #64748B;
            --aj-text-muted: #94A3B8;
            --aj-error: #EF4444;
            --aj-error-light: #FEF2F2;
            --aj-error-bg: #FFF1F2;
            --aj-error-border: #FECDD3;
            --aj-warning: #F59E0B;
            --aj-shadow: 0 1px 3px 0 rgba(0, 0, 0, 0.1);
            --aj-radius: 8px;
        }
        
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
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
            background: var(--aj-surface);
            border-bottom: 1px solid var(--aj-border);
            padding: 20px 32px;
        }
        
        .header-content {
            max-width: 1200px;
            margin: 0 auto;
        }
        
        /* New Header Icon Wrapper */
        .header-icon-wrapper {
            display: flex;
            align-items: center;
            gap: 16px;
        }
        
        .header-icon-circle {
            width: 48px;
            height: 48px;
            background: linear-gradient(135deg, var(--aj-primary) 0%, var(--aj-primary-hover) 100%);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            flex-shrink: 0;
            box-shadow: 0 2px 8px rgba(37, 99, 235, 0.3);
        }
        
        .header-content-wrapper {
            flex: 1;
        }
        
        .header-left {
            flex: 1;
        }
        
        .header-title {
            font-size: 20px;
            font-weight: 700;
            color: var(--aj-text);
            margin-bottom: 4px;
        }
        
        .header-subtitle {
            font-size: 13px;
            color: var(--aj-text-secondary);
            line-height: 1.5;
        }
        
        .header-subtitle strong {
            color: var(--aj-primary);
            font-weight: 600;
        }
        
        .header-right {
            text-align: right;
            flex-shrink: 0;
        }
        
        .header-id {
            font-size: 12px;
            color: var(--aj-text-muted);
            margin-bottom: 2px;
            font-weight: 500;
        }
        
        .header-id-value {
            font-size: 15px;
            font-weight: 600;
            color: var(--aj-text);
        }
        
        /* Container */
        .container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 32px;
        }
        
        /* Card */
        .card {
            background: var(--aj-surface);
            border-radius: var(--aj-radius);
            box-shadow: var(--aj-shadow);
            overflow: hidden;
        }
        
        .card-body {
            padding: 32px;
        }
        
        /* Info Section - Desktop Two Column Layout */
        .info-section {
            margin-bottom: 32px;
        }
        
        .info-section-title {
            display: flex;
            align-items: center;
            gap: 8px;
            font-size: 15px;
            font-weight: 600;
            color: var(--aj-primary);
            margin-bottom: 20px;
        }
        
        .info-layout {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 32px;
        }
        
        .info-column {
            display: flex;
            flex-direction: column;
            gap: 16px;
        }
        
        .info-item {
            display: flex;
            align-items: flex-start;
            gap: 10px;
            border-bottom: 1px solid var(--aj-text-muted);
            padding-bottom: 12px;
        }
        
        .info-icon {
            width: 18px;
            height: 18px;
            color: var(--aj-text-muted);
            flex-shrink: 0;
            margin-top: 1px;
        }
        
        .info-content {
            flex: 1;
            min-width: 0;
        }
        
        .info-label {
            font-size: 12px;
            color: var(--aj-text-secondary);
            margin-bottom: 2px;
            font-weight: 500;
        }
        
        .info-value {
            font-size: 14px;
            font-weight: 500;
            color: var(--aj-text);
            word-break: break-word;
            line-height: 1.5;
        }
        
        /* Jasa Items Table */
        .jasa-items-section {
            overflow: hidden;
            border-bottom: 0.5px solid var(--aj-border);
        }
        
        .jasa-items-title {
            display: flex;
            align-items: center;
            gap: 8px;
            font-size: 14px;
            font-weight: 600;
            color: var(--aj-text);
            padding: 12px 16px;
            border-bottom: 1px solid var(--aj-border);
        }
        
        .jasa-items-table-wrapper {
            overflow-x: auto;
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
        
        /* Responsive - Mobile */
        @media (max-width: 768px) {
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

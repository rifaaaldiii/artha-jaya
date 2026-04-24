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
            --aj-primary: #059669;
            --aj-primary-dark: #047857;
            --aj-primary-light: #d1fae5;
            --aj-success: #22c55e;
            --aj-surface: #ffffff;
            --aj-bg: #f8fafc;
            --aj-border: #e5e7eb;
            --aj-divider: #f3f4f6;
            --aj-text: #111827;
            --aj-text-secondary: #6b7280;
            --aj-text-muted: #9ca3af;
            --aj-error: #dc2626;
            --aj-error-light: #fee2e2;
            --aj-warning: #f59e0b;
            --aj-shadow-sm: 0 1px 2px 0 rgba(0, 0, 0, 0.05);
            --aj-shadow: 0 1px 3px 0 rgba(0, 0, 0, 0.1), 0 1px 2px -1px rgba(0, 0, 0, 0.1);
            --aj-shadow-md: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -2px rgba(0, 0, 0, 0.1);
            --aj-shadow-lg: 0 10px 15px -3px rgba(0, 0, 0, 0.1), 0 4px 6px -4px rgba(0, 0, 0, 0.1);
        }
        
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, 'Helvetica Neue', Arial, sans-serif;
            background: var(--aj-bg);
            color: var(--aj-text);
            line-height: 1.6;
            min-height: 100vh;
        }
        
        .container {
            max-width: 900px;
            margin: 0 auto;
            padding: 40px 20px;
        }
        
        /* Header */
        .header {
            background: var(--aj-surface);
            border-bottom: 1px solid var(--aj-border);
            padding: 20px 0;
            margin-bottom: 40px;
            box-shadow: var(--aj-shadow-sm);
        }
        
        .header-content {
            max-width: 900px;
            margin: 0 auto;
            padding: 0 20px;
            display: flex;
            align-items: center;
            gap: 16px;
        }
        
        .logo {
            width: 48px;
            height: 48px;
            background: linear-gradient(135deg, var(--aj-primary) 0%, var(--aj-success) 100%);
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 24px;
            font-weight: bold;
        }
        
        .header-text h1 {
            font-size: 20px;
            font-weight: 700;
            color: var(--aj-text);
            margin-bottom: 2px;
        }
        
        .header-text p {
            font-size: 13px;
            color: var(--aj-text-secondary);
        }
        
        /* Card */
        .card {
            background: var(--aj-surface);
            border-radius: 12px;
            border: 1px solid var(--aj-border);
            box-shadow: var(--aj-shadow);
            overflow: hidden;
        }
        
        .card-header {
            background: linear-gradient(135deg, var(--aj-primary-light) 0%, #ecfdf5 100%);
            border-bottom: 1px solid var(--aj-border);
            padding: 24px 32px;
        }
        
        .card-header h2 {
            font-size: 20px;
            font-weight: 700;
            color: var(--aj-text);
            margin-bottom: 4px;
        }
        
        .card-header p {
            font-size: 14px;
            color: var(--aj-text-secondary);
        }
        
        .card-body {
            padding: 32px;
        }
        
        /* Alert */
        .alert {
            padding: 16px 20px;
            border-radius: 8px;
            margin-bottom: 24px;
            display: flex;
            align-items: flex-start;
            gap: 12px;
            font-size: 14px;
        }
        
        .alert-icon {
            font-size: 20px;
            flex-shrink: 0;
        }
        
        .alert-error {
            background: var(--aj-error-light);
            border: 1px solid #fecaca;
            color: var(--aj-error);
        }
        
        .alert-success {
            background: var(--aj-primary-light);
            border: 1px solid #a7f3d0;
            color: var(--aj-primary-dark);
        }
        
        /* Form */
        .form-group {
            margin-bottom: 28px;
        }
        
        .form-group label {
            display: block;
            font-size: 14px;
            font-weight: 600;
            color: var(--aj-text);
            margin-bottom: 8px;
        }
        
        .required {
            color: var(--aj-error);
            margin-left: 2px;
        }
        
        .form-hint {
            font-size: 13px;
            color: var(--aj-text-secondary);
            margin-top: 6px;
            font-weight: 400;
        }
        
        /* Upload Area */
        .upload-area {
            border: 2px dashed var(--aj-border);
            border-radius: 8px;
            padding: 48px 24px;
            text-align: center;
            cursor: pointer;
            transition: all 0.2s;
            background: var(--aj-bg);
        }
        
        .upload-area:hover {
            border-color: var(--aj-primary);
            background: var(--aj-primary-light);
        }
        
        .upload-area input[type="file"] {
            display: none;
        }
        
        .upload-icon {
            width: 56px;
            height: 56px;
            margin: 0 auto 16px;
            background: var(--aj-surface);
            border: 2px solid var(--aj-border);
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        
        .upload-icon svg {
            width: 28px;
            height: 28px;
            color: var(--aj-text-secondary);
        }
        
        .upload-area:hover .upload-icon {
            border-color: var(--aj-primary);
            background: var(--aj-primary-light);
        }
        
        .upload-area:hover .upload-icon svg {
            color: var(--aj-primary);
        }
        
        .upload-placeholder p {
            color: var(--aj-text);
            font-weight: 600;
            margin-bottom: 4px;
        }
        
        .upload-placeholder small {
            color: var(--aj-text-secondary);
            font-size: 13px;
        }
        
        /* Image Preview */
        .image-preview {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(140px, 1fr));
            gap: 16px;
            margin-top: 20px;
        }
        
        .preview-item {
            position: relative;
            border-radius: 8px;
            overflow: hidden;
            aspect-ratio: 1;
            border: 1px solid var(--aj-border);
            box-shadow: var(--aj-shadow-sm);
        }
        
        .preview-item img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }
        
        .preview-remove {
            position: absolute;
            top: 8px;
            right: 8px;
            background: rgba(220, 38, 38, 0.95);
            color: white;
            border: 2px solid white;
            border-radius: 50%;
            width: 28px;
            height: 28px;
            cursor: pointer;
            font-size: 18px;
            line-height: 1;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: transform 0.2s;
        }
        
        .preview-remove:hover {
            transform: scale(1.1);
        }
        
        .file-count {
            display: block;
            margin-top: 12px;
            font-size: 13px;
            color: var(--aj-text-secondary);
            font-weight: 500;
        }
        
        /* Textarea */
        textarea {
            width: 100%;
            padding: 12px 16px;
            border: 1px solid var(--aj-border);
            border-radius: 8px;
            font-size: 14px;
            font-family: inherit;
            color: var(--aj-text);
            background: var(--aj-surface);
            resize: vertical;
            transition: all 0.2s;
        }
        
        textarea:focus {
            outline: none;
            border-color: var(--aj-primary);
            box-shadow: 0 0 0 3px rgba(5, 150, 105, 0.1);
        }
        
        textarea::placeholder {
            color: var(--aj-text-muted);
        }
        
        /* Checkbox */
        .checkbox-label {
            display: flex;
            align-items: flex-start;
            gap: 12px;
            cursor: pointer;
            padding: 16px;
            background: var(--aj-bg);
            border: 1px solid var(--aj-border);
            border-radius: 8px;
            transition: all 0.2s;
        }
        
        .checkbox-label:hover {
            background: var(--aj-primary-light);
            border-color: var(--aj-primary);
        }
        
        .checkbox-label input[type="checkbox"] {
            width: 20px;
            height: 20px;
            margin-top: 2px;
            cursor: pointer;
            accent-color: var(--aj-primary);
            flex-shrink: 0;
        }
        
        .checkbox-label span {
            font-size: 14px;
            color: var(--aj-text);
            line-height: 1.6;
        }
        
        /* Button */
        .btn-submit {
            width: 100%;
            padding: 14px 24px;
            background: linear-gradient(135deg, var(--aj-primary) 0%, var(--aj-primary-dark) 100%);
            color: white;
            border: none;
            border-radius: 8px;
            font-size: 15px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.2s;
            box-shadow: var(--aj-shadow-md);
        }
        
        .btn-submit:hover:not(:disabled) {
            transform: translateY(-2px);
            box-shadow: 0 8px 16px rgba(5, 150, 105, 0.3);
        }
        
        .btn-submit:active:not(:disabled) {
            transform: translateY(0);
        }
        
        .btn-submit:disabled {
            opacity: 0.5;
            cursor: not-allowed;
            box-shadow: var(--aj-shadow-sm);
        }
        
        /* Success Page */
        .success-card {
            text-align: center;
            padding: 64px 40px;
        }
        
        .success-icon {
            width: 96px;
            height: 96px;
            background: linear-gradient(135deg, var(--aj-success) 0%, var(--aj-primary) 100%);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 28px;
            font-size: 56px;
            color: white;
            box-shadow: 0 8px 24px rgba(34, 197, 94, 0.3);
        }
        
        .success-card h1 {
            font-size: 28px;
            font-weight: 700;
            margin-bottom: 12px;
            color: var(--aj-text);
        }
        
        .success-card p {
            font-size: 15px;
            color: var(--aj-text-secondary);
            margin-bottom: 12px;
            line-height: 1.7;
        }
        
        .success-card strong {
            color: var(--aj-text);
            font-weight: 600;
        }
        
        .completion-notes {
            background: var(--aj-bg);
            border: 1px solid var(--aj-border);
            border-radius: 8px;
            padding: 20px;
            margin-top: 32px;
            text-align: left;
        }
        
        .completion-notes strong {
            font-size: 14px;
            color: var(--aj-text);
            display: block;
            margin-bottom: 8px;
        }
        
        .completion-notes p {
            font-size: 14px;
            color: var(--aj-text-secondary);
            margin: 0;
        }
        
        .btn-home {
            display: inline-block;
            margin-top: 32px;
            padding: 14px 32px;
            background: var(--aj-primary);
            color: white;
            text-decoration: none;
            border-radius: 8px;
            font-weight: 600;
            font-size: 15px;
            transition: all 0.2s;
            box-shadow: var(--aj-shadow-md);
        }
        
        .btn-home:hover {
            background: var(--aj-primary-dark);
            transform: translateY(-2px);
            box-shadow: 0 8px 16px rgba(5, 150, 105, 0.3);
        }
        
        /* Responsive */
        @media (max-width: 768px) {
            .container {
                padding: 20px 16px;
            }
            
            .card-body {
                padding: 24px 20px;
            }
            
            .card-header {
                padding: 20px;
            }
            
            .success-card {
                padding: 48px 24px;
            }
            
            .success-icon {
                width: 80px;
                height: 80px;
                font-size: 48px;
            }
            
            .success-card h1 {
                font-size: 24px;
            }
            
            .image-preview {
                grid-template-columns: repeat(auto-fill, minmax(120px, 1fr));
                gap: 12px;
            }
        }
    </style>
    @stack('styles')
</head>
<body>
    <div class="header">
        <div class="header-content">
            <div class="logo">AJ</div>
            <div class="header-text">
                <h1>Artha Jaya</h1>
                <p>@yield('subtitle', 'Sistem Manajemen Jasa & Produksi')</p>
            </div>
        </div>
    </div>
    
    <div class="container">
        @yield('content')
    </div>
    
    @stack('scripts')
</body>
</html>

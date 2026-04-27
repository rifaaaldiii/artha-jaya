<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Akses Tidak Valid | Artha Jaya Mas</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        :root {
            --primary: #10b981;
            --primary-dark: #059669;
            --primary-light: #34d399;
            --accent: #06b6d4;
            --error: #ef4444;
            --error-dark: #dc2626;
            --dark: #0f172a;
            --gray-900: #1e293b;
            --gray-700: #334155;
            --gray-500: #64748b;
            --gray-300: #cbd5e1;
            --gray-100: #f1f5f9;
            --white: #ffffff;
        }

        body {
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', sans-serif;
            background: var(--gray-100);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
            position: relative;
            overflow: hidden;
        }

        /* Subtle Background Grid */
        body::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background-image: 
                linear-gradient(rgba(239, 68, 68, 0.03) 1px, transparent 1px),
                linear-gradient(90deg, rgba(239, 68, 68, 0.03) 1px, transparent 1px);
            background-size: 60px 60px;
            z-index: 0;
        }

        /* Gradient Orbs */
        .gradient-orb {
            position: absolute;
            border-radius: 50%;
            filter: blur(80px);
            opacity: 0.15;
            z-index: 0;
            animation: float 20s ease-in-out infinite;
        }

        .gradient-orb-1 {
            width: 400px;
            height: 400px;
            background: var(--error);
            top: -100px;
            right: -100px;
            animation-delay: 0s;
        }

        .gradient-orb-2 {
            width: 300px;
            height: 300px;
            background: var(--accent);
            bottom: -100px;
            left: -100px;
            animation-delay: -10s;
        }

        @keyframes float {
            0%, 100% { transform: translate(0, 0) scale(1); }
            33% { transform: translate(30px, -30px) scale(1.1); }
            66% { transform: translate(-20px, 20px) scale(0.9); }
        }

        .container {
            text-align: center;
            max-width: 800px;
            width: 100%;
            position: relative;
            z-index: 1;
        }

        .error-card {
            border-radius: 20px;
            padding: 60px 50px;
            animation: slideUp 0.6s cubic-bezier(0.16, 1, 0.3, 1);
            position: relative;
            overflow: hidden;
        }

        @keyframes slideUp {
            from {
                opacity: 0;
                transform: translateY(40px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        /* Error Icon */
        .error-icon {
            width: 100px;
            height: 100px;
            margin: 0 auto 30px;
            background: linear-gradient(135deg, var(--error) 0%, var(--error-dark) 100%);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            box-shadow: 0 10px 30px rgba(239, 68, 68, 0.3);
            animation: pulse 2s ease-in-out infinite;
        }

        @keyframes pulse {
            0%, 100% { transform: scale(1); }
            50% { transform: scale(1.05); }
        }

        .error-icon svg {
            width: 50px;
            height: 50px;
            color: white;
        }

        /* Animated Line */
        .animated-line {
            width: 80px;
            height: 4px;
            background: linear-gradient(90deg, var(--error) 0%);
            border-radius: 2px;
            margin: 30px auto;
            position: relative;
            overflow: hidden;
        }

        .animated-line::after {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.8), transparent);
            animation: shimmer 2s ease-in-out infinite;
        }

        @keyframes shimmer {
            0% { left: -100%; }
            100% { left: 100%; }
        }

        /* Typography */
        .error-title {
            font-size: 28px;
            font-weight: 700;
            color: var(--gray-900);
            margin-bottom: 12px;
            letter-spacing: -0.5px;
        }

        .error-subtitle {
            font-size: 16px;
            color: var(--gray-500);
            line-height: 1.6;
            margin-bottom: 40px;
            max-width: 500px;
            margin-left: auto;
            margin-right: auto;
        }

        /* Icon */
        .icon {
            width: 18px;
            height: 18px;
            display: inline-block;
            vertical-align: middle;
        }

        /* Footer Text */
        .footer-text {
            margin-top: 40px;
            padding-top: 30px;
            border-top: 1px solid var(--gray-100);
            font-size: 13px;
            color: var(--gray-500);
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 6px;
        }

        .status-dot {
            width: 6px;
            height: 6px;
            background: var(--primary);
            border-radius: 50%;
            display: inline-block;
            animation: pulse-dot 2s ease-in-out infinite;
        }

        @keyframes pulse-dot {
            0%, 100% { opacity: 1; transform: scale(1); }
            50% { opacity: 0.5; transform: scale(0.8); }
        }

        /* Responsive */
        @media (max-width: 640px) {
            .error-card {
                padding: 50px 30px;
            }

            .error-icon {
                width: 80px;
                height: 80px;
            }

            .error-icon svg {
                width: 40px;
                height: 40px;
            }

            .error-title {
                font-size: 24px;
            }

            .error-subtitle {
                font-size: 15px;
            }

        }

        /* Dark Mode Support */
        @media (prefers-color-scheme: dark) {
            :root {
                --dark: #f8fafc;
                --gray-900: #f1f5f9;
                --gray-700: #cbd5e1;
                --gray-500: #94a3b8;
                --gray-300: #334155;
                --gray-100: #1e293b;
                --white: #0f172a;
            }

            body {
                background: var(--gray-100);
            }

            .error-card {
                box-shadow: 
                    0 0 0 1px rgba(255, 255, 255, 0.05),
                    0 4px 6px -1px rgba(0, 0, 0, 0.3),
                    0 20px 40px -8px rgba(0, 0, 0, 0.4);
            }

            .gradient-orb {
                opacity: 0.1;
            }
        }
    </style>
</head>
<body>
    <!-- Background Elements -->
    <div class="gradient-orb gradient-orb-1"></div>
    <div class="gradient-orb gradient-orb-2"></div>

    <div class="container">
        <div class="error-card">
            <!-- Error Icon -->
            <div class="error-icon">
                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
                </svg>
            </div>

            <!-- Animated Line -->
            <div class="animated-line"></div>

            <!-- Error Message -->
            <h1 class="error-title">Akses Tidak Valid</h1>
            <p class="error-subtitle">
                {{ $message ?? 'Link yang Anda akses tidak valid atau sudah tidak berlaku. Silakan periksa kembali link yang Anda gunakan.' }}
            </p>

            <!-- Action Buttons -->
            {{-- <div class="button-container">
                <a href="/admin" class="btn btn-primary">
                    <svg class="icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path>
                    </svg>
                    <span>Dashboard</span>
                </a>
                <a href="javascript:history.back()" class="btn btn-ghost">
                    <svg class="icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 15l-3-3m0 0l3-3m-6 6h12"></path>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 15l-3-3m0 0l3-3m-6 6h12" transform="rotate(180 12 12)"></path>
                    </svg>
                    <span>Kembali</span>
                </a>
            </div> --}}

            <!-- Footer -->
            <div class="footer-text">
                <span class="status-dot"></span>
                <span>System Status: Operational</span>
            </div>
        </div>
    </div>
</body>
</html>

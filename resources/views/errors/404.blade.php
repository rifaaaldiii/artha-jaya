<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>404 - Halaman Tidak Ditemukan | System Artha Jaya</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        :root {
            --primary: #10b981;
            --primary-dark: #059669;
            --primary-light: #d1fae5;
            --secondary: #64748b;
            --dark: #0f172a;
            --light: #f8fafc;
            --border: #e2e8f0;
        }

        body {
            font-family: 'Poppins', -apple-system, BlinkMacSystemFont, 'Segoe UI', sans-serif;
            background: var(--light);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
            position: relative;
            overflow: hidden;
        }

        /* Background Pattern */
        body::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: 
                radial-gradient(circle at 20% 50%, rgba(16, 185, 129, 0.1) 0%, transparent 50%),
                radial-gradient(circle at 80% 80%, rgba(16, 185, 129, 0.08) 0%, transparent 50%),
                radial-gradient(circle at 40% 20%, rgba(16, 185, 129, 0.06) 0%, transparent 40%);
            z-index: 0;
        }

        .container {
            text-align: center;
            max-width: 700px;
            width: 100%;
            position: relative;
            z-index: 1;
        }

        .error-card {
            background: white;
            border-radius: 24px;
            padding: 60px 50px;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
            border: 1px solid var(--border);
            animation: fadeInUp 0.6s ease-out;
        }

        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(30px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        /* Logo Section */
        .logo-section {
            margin-bottom: 40px;
        }

        .logo {
            font-size: 24px;
            font-weight: 700;
            color: var(--primary);
            display: inline-flex;
            align-items: center;
            gap: 10px;
        }

        /* Error Code */
        .error-code-container {
            position: relative;
            margin: 40px 0;
        }

        .error-code {
            font-size: 140px;
            font-weight: 800;
            color: var(--primary);
            line-height: 1;
            position: relative;
            display: inline-block;
            background: linear-gradient(135deg, var(--primary) 0%, var(--primary-dark) 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }

        .error-code-bg {
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            font-size: 180px;
            font-weight: 800;
            color: var(--primary-light);
            opacity: 0.3;
            z-index: -1;
        }

        .error-icon {
            font-size: 60px;
            margin: 20px 0;
            animation: bounce 2s ease-in-out infinite;
        }

        @keyframes bounce {
            0%, 100% {
                transform: translateY(0);
            }
            50% {
                transform: translateY(-15px);
            }
        }

        /* Typography */
        .error-title {
            font-size: 32px;
            font-weight: 700;
            color: var(--dark);
            margin-bottom: 16px;
            line-height: 1.2;
        }

        .error-message {
            font-size: 16px;
            color: var(--secondary);
            line-height: 1.7;
            margin-bottom: 40px;
            max-width: 500px;
            margin-left: auto;
            margin-right: auto;
        }

        /* Buttons */
        .button-group {
            display: flex;
            gap: 16px;
            justify-content: center;
            flex-wrap: wrap;
            margin-bottom: 30px;
        }

        .btn {
            padding: 16px 32px;
            border-radius: 12px;
            font-size: 15px;
            font-weight: 600;
            text-decoration: none;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            cursor: pointer;
            border: none;
            display: inline-flex;
            align-items: center;
            gap: 10px;
            font-family: 'Poppins', sans-serif;
        }

        .btn-primary {
            background: linear-gradient(135deg, var(--primary) 0%, var(--primary-dark) 100%);
            color: white;
            box-shadow: 0 4px 12px rgba(16, 185, 129, 0.3);
        }

        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 20px rgba(16, 185, 129, 0.4);
        }

        .btn-primary:active {
            transform: translateY(0);
        }

        .btn-secondary {
            background: white;
            color: var(--secondary);
            border: 2px solid var(--border);
        }

        .btn-secondary:hover {
            border-color: var(--primary);
            color: var(--primary);
            background: var(--primary-light);
            transform: translateY(-2px);
        }

        /* Responsive */
        @media (max-width: 640px) {
            .error-card {
                padding: 40px 30px;
            }

            .error-code {
                font-size: 100px;
            }

            .error-code-bg {
                font-size: 130px;
            }

            .error-title {
                font-size: 26px;
            }

            .error-icon {
                font-size: 50px;
            }

            .button-group {
                flex-direction: column;
            }

            .btn {
                width: 100%;
                justify-content: center;
            }

            .logo {
                font-size: 20px;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="error-card">
            <!-- Error Code -->
            <div class="error-code-container">
                <div class="error-code-bg">404</div>
                <div class="error-code">404</div>
            </div>

            <!-- Error Icon -->
            <div class="error-icon">🔍</div>

            <!-- Error Message -->
            <h1 class="error-title">Halaman Tidak Ditemukan</h1>
            <p class="error-message">
                Maaf, halaman yang Anda cari tidak ditemukan atau telah dipindahkan. 
                Silakan periksa kembali URL atau kembali ke dashboard.
            </p>

            <!-- Action Buttons -->
            <div class="button-group">
                <a href="/admin" class="btn btn-primary">
                    <span>🏠</span>
                    <span>Kembali ke Dashboard</span>
                </a>
            </div>
        </div>
    </div>
</body>
</html>

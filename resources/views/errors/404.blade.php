<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>404 - Halaman Tidak Ditemukan</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', sans-serif;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
        }

        .container {
            text-align: center;
            max-width: 600px;
            width: 100%;
        }

        .error-card {
            background: white;
            border-radius: 20px;
            padding: 60px 40px;
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.3);
            animation: slideUp 0.6s ease-out;
        }

        @keyframes slideUp {
            from {
                opacity: 0;
                transform: translateY(30px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .error-icon {
            font-size: 120px;
            margin-bottom: 20px;
            animation: float 3s ease-in-out infinite;
        }

        @keyframes float {
            0%, 100% {
                transform: translateY(0px);
            }
            50% {
                transform: translateY(-20px);
            }
        }

        .error-code {
            font-size: 72px;
            font-weight: 800;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
            margin-bottom: 10px;
            line-height: 1;
        }

        .error-title {
            font-size: 28px;
            font-weight: 700;
            color: #1e293b;
            margin-bottom: 15px;
        }

        .error-message {
            font-size: 16px;
            color: #64748b;
            line-height: 1.6;
            margin-bottom: 40px;
        }

        .button-group {
            display: flex;
            gap: 15px;
            justify-content: center;
            flex-wrap: wrap;
        }

        .btn {
            padding: 16px 32px;
            border-radius: 12px;
            font-size: 16px;
            font-weight: 600;
            text-decoration: none;
            transition: all 0.3s ease;
            cursor: pointer;
            border: none;
            display: inline-flex;
            align-items: center;
            gap: 8px;
        }

        .btn-primary {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            box-shadow: 0 4px 15px rgba(102, 126, 234, 0.4);
        }

        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(102, 126, 234, 0.6);
        }

        .btn-secondary {
            background: #f1f5f9;
            color: #475569;
            border: 2px solid #e2e8f0;
        }

        .btn-secondary:hover {
            background: #e2e8f0;
            transform: translateY(-2px);
        }

        .info-box {
            margin-top: 30px;
            padding: 20px;
            background: #f8fafc;
            border-radius: 12px;
            border-left: 4px solid #667eea;
        }

        .info-text {
            font-size: 14px;
            color: #64748b;
            line-height: 1.6;
        }

        .info-text strong {
            color: #1e293b;
        }

        .countdown {
            font-size: 14px;
            color: #94a3b8;
            margin-top: 20px;
        }

        @media (max-width: 640px) {
            .error-card {
                padding: 40px 25px;
            }

            .error-code {
                font-size: 56px;
            }

            .error-title {
                font-size: 24px;
            }

            .error-icon {
                font-size: 80px;
            }

            .button-group {
                flex-direction: column;
            }

            .btn {
                width: 100%;
                justify-content: center;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="error-card">
            <div class="error-icon">🔍</div>
            <div class="error-code">404</div>
            <h1 class="error-title">Halaman Tidak Ditemukan</h1>
            <p class="error-message">
                Maaf, halaman yang Anda cari tidak ditemukan atau telah dipindahkan. 
                Silakan periksa kembali URL atau kembali ke dashboard.
            </p>

            <div class="button-group">
                <a href="/admin" class="btn btn-primary">
                    <span>🏠</span>
                    <span>Kembali ke Dashboard</span>
                </a>
                <button onclick="history.back()" class="btn btn-secondary">
                    <span>←</span>
                    <span>Halaman Sebelumnya</span>
                </button>
            </div>

            <div class="info-box">
                <p class="info-text">
                    <strong>💡 Tips:</strong> Jika Anda mengklik link dari halaman sebelumnya, 
                    kemungkinan link tersebut sudah tidak valid. Anda bisa kembali ke dashboard 
                    atau menggunakan navigasi menu.
                </p>
            </div>

            <div class="countdown">
                Auto redirect ke dashboard dalam <span id="countdown">10</span> detik
            </div>
        </div>
    </div>

    <script>
        // Countdown timer
        let seconds = 10;
        const countdownElement = document.getElementById('countdown');
        
        const timer = setInterval(() => {
            seconds--;
            countdownElement.textContent = seconds;
            
            if (seconds <= 0) {
                clearInterval(timer);
                window.location.href = '/admin';
            }
        }, 1000);

        // Stop timer if user interacts
        document.addEventListener('click', () => {
            clearInterval(timer);
            document.querySelector('.countdown').style.display = 'none';
        });
    </script>
</body>
</html>

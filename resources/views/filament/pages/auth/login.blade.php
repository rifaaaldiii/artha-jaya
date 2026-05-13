<div class="login-container">
    <!-- Full Screen Background -->
    <div class="login-background">
        <img src="{{ asset('1778679552_6a047f0090194.png') }}" alt="Background" class="login-background-image">
        <!-- Overlay for better text readability -->
        <div class="login-background-overlay"></div>
    </div>
    
    <!-- Main Content Container -->
    <div class="login-content-wrapper">
        <div class="login-content-container">
            <!-- Apply scale only on desktop, normal on mobile -->
            <div class="login-scale-transform">
                <div class="login-grid-layout">
                    
                    <!-- Left Side - Branding & Illustration -->
                    <div class="login-branding-section">
                        <!-- Logo -->
                        <div class="login-logo-wrapper">
                            <img src="{{ asset('logo.png') }}" alt="Artha Jaya Logo" class="login-logo">
                        </div>
                        
                        <!-- Headline -->
                        <div class="login-headline-section">
                            <h2 class="login-headline">
                                Sistem Stepnosing<br>
                                Jasa & Layanan
                            </h2>
                            
                            <!-- Decorative Line -->
                            <div class="login-decorative-line">
                                <div class="login-line-green"></div>
                                <div class="login-line-red"></div>
                            </div>
                            
                            <p class="login-description">
                                Sistem manajemen untuk penginputan jasa & layanan<br>
                                serta produksi stepnosing yang terintegrasi.
                            </p>
                        </div>
                    </div>
                    
                    <!-- Right Side - Login Form -->
                    <div class="login-form-section" style="border: 1;">
                        <div class="login-form-card">
                            <!-- Form Header -->
                            <div class="login-form-header">
                                <h3 class="login-form-title">Selamat Datang</h3>
                                <p class="login-form-subtitle">Silakan masuk untuk melanjutkan</p>
                            </div>
                            
                            <!-- Login Form -->
                            <form wire:submit="authenticate" class="login-form">
                                {{ $this->form }}

                                <!-- Submit Button -->
                                <button 
                                    type="submit" 
                                    wire:loading.attr="disabled"
                                    class="login-submit-button"
                                >
                                    <span wire:loading.remove>Masuk</span>
                                    <span wire:loading class="login-loading-wrapper">
                                        <span>Memproses...</span>
                                        <svg class="animate-spin login-loading-spinner" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                        </svg>
                                    </span>
                                </button>
                            </form>
                            
                            <!-- Footer -->
                            <div class="login-footer">
                                <p class="login-copyright">
                                    &copy; {{ date('Y') }} PT. Artha Jaya Mas. All rights reserved.
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <style>
        @import url('https://fonts.googleapis.com/css2?family=Roboto:wght@300;400;500;600;700&display=swap');

        /* Reset */
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        html, body {
            margin: 0;
            padding: 0;
            overflow: hidden;
            font-family: 'Roboto', sans-serif;
        }

        /* Main Container */
        .login-container {
            width: 100vw;
            height: 100vh;
            overflow: hidden;
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            margin: 0;
            padding: 0;
        }

        /* Background */
        .login-background {
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            margin: 0;
            padding: 0;
        }

        .login-background-image {
            width: 100%;
            height: 100%;
            object-fit: cover;
            display: block;
            margin: 0;
            padding: 0;
        }

        .login-background-overlay {
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            margin: 0;
            padding: 0;
            /* background: linear-gradient(to right, rgba(245,245,240,0.95), rgba(245,245,240,0.90), rgba(245,245,240,0.80)); */
        }

        /* Content Wrapper */
        .login-content-wrapper {
            position: relative;
            z-index: 10;
            width: 100%;
            height: 100%;
            display: flex;
            justify-content: center;
            padding: 0;
            margin: 0;
        }

        .login-content-container {
            width: 100%;
            max-width: 80rem;
            margin: 0 auto;
            padding: 1rem;
        }

        .login-scale-transform {
            transform: scale(0.9);
            transform-origin: center;
            margin-top: 10px;
        }
        
        @media (min-width: 1024px) {
            .login-scale-transform {
                transform: scale(0.8);
                margin-top: 40px;
            }
        }

        /* Grid Layout */
        .login-grid-layout {
            display: grid;
            grid-template-columns: 1fr;
            gap: 2rem;
            align-items: center;
            justify-content: space-between;
        }
        
        @media (min-width: 1024px) {
            .login-grid-layout {
                justify-content: space-between;
                grid-template-columns: 1fr 1fr;
                gap: 3rem;
            }
        }

        /* Branding Section */
        .login-branding-section {
            display: flex;
            flex-direction: column;
            align-items: center;
            margin-top: -1rem;
        }
        
        @media (min-width: 1024px) {
            .login-branding-section {
                gap: 2rem;
                margin-top: -240px;
                align-items: start;
            }
        }

        .login-logo-wrapper {
            display: flex;
            align-items: center;
            gap: 1.75rem;
            margin-bottom: 10px;
        }
        
        @media (min-width: 1024px) {
            .login-logo-wrapper {
                gap: 0.75rem;
            }
        }

        .login-logo {
            height: 3rem;
            width: auto;
        }

        @media (min-width: 1024px) {
            .login-logo {
                height: 3.5rem;
                width: auto;
            }
        }

        .login-headline-section {
            display: flex;
            flex-direction: column;
            gap: 1rem;
            text-align: center;
        }

        @media (min-width: 1024px) {
            .login-headline-section {
                text-align: start;
            }
        }

        .login-headline {
            font-size: 24px;
            font-weight: 700;
            color: #1B3A2D;
            line-height: 1.2;
            margin: 0;
            font-family: 'Roboto', sans-serif;
        }
        
        @media (min-width: 1024px) {
            .login-headline {
                font-size: 3rem;
            }
        }

        .login-decorative-line {
            display: flex;
            gap: 0.25rem;
            justify-content: center;
        }
        
        @media (min-width: 1024px) {
            .login-decorative-line {
                justify-content: start;
            }
        }

        .login-line-green {
            height: 0.25rem;
            width: 4rem;
            background-color: #1B5E3B;
            border-radius: 9999px;
        }

        .login-line-red {
            height: 0.25rem;
            width: 2rem;
            background-color: #C62828;
            border-radius: 9999px;
        }

        .login-description {
            color: #5A5A5A;
            font-size: 12px;
            line-height: 1.625;
            max-width: 28rem;
            margin: 0;
            font-family: 'Roboto', sans-serif;
        }
        
        @media (min-width: 1024px) {
            .login-description {
                font-size: 1rem;
            }
        }

        /* Form Section */
        .login-form-section {
            display: flex;
            justify-content: center;
        }
        
        @media (min-width: 1024px) {
            .login-form-section {
                justify-content: end;
            }
        }

        .login-form-card {
            width: 100%;
            max-width: 28rem;
            background: white;
            border-radius: 1rem;
            box-shadow: 0 20px 25px -5px rgba(0,0,0,0.1), 0 10px 10px -5px rgba(0,0,0,0.04);
            padding: 1.5rem;
        }
        
        @media (min-width: 1024px) {
            .login-form-card {
                padding: 2rem;
            }
        }

        .login-form-header {
            text-align: center;
            margin-bottom: 2rem;
        }

        .login-form-title {
            font-size: 1.5rem;
            font-weight: 700;
            color: #1B3A2D;
            margin: 0 0 0.5rem 0;
            font-family: 'Roboto', sans-serif;
        }
        
        @media (min-width: 1024px) {
            .login-form-title {
                font-size: 1.875rem;
            }
        }

        .login-form-subtitle {
            color: #5A5A5A;
            font-size: 0.875rem;
            margin: 0;
            font-family: 'Roboto', sans-serif;
        }

        .login-form {
            display: flex;
            flex-direction: column;
            gap: 1.25rem;
        }

        .login-submit-button {
            width: 100%;
            background-color: #1B5E3B;
            color: white;
            font-weight: 600;
            padding: 0.75rem 1rem;
            border-radius: 0.5rem;
            border: none;
            cursor: pointer;
            transition: all 0.2s;
            box-shadow: 0 4px 6px -1px rgba(0,0,0,0.1);
            font-size: 0.875rem;
            font-family: 'Roboto', sans-serif;
        }

        .login-submit-button:hover {
            background-color: #145230;
        }

        .login-submit-button:disabled {
            opacity: 0.5;
            cursor: not-allowed;
        }

        .login-loading-wrapper {
            position: relative;
            display: inline-flex;
            align-items: center;
            justify-content: center;
        }

        .login-loading-spinner {
            height: 1.25rem;
            width: 1.25rem;
            color: white;
            position: absolute;
            left: 27px;
            top: 3px;
            z-index: 10;
        }

        .login-footer {
            margin-top: 1.5rem;
            text-align: center;
        }

        .login-copyright {
            font-size: 0.75rem;
            color: #5A5A5A;
            margin: 0;
        }

        /* Filament Form Styles */
        .fi-fo-field-wrp {
            margin-bottom: 0 !important;
            padding: 0 !important;
        }

        .fi-fo-field-wrp-error-message {
            color: #C62828;
            font-size: 0.75rem;
            margin-top: 0.25rem;
            font-weight: 500;
        }

        .fi-fo-field-wrp-error-message svg {
            width: 0.875rem;
            height: 0.875rem;
            display: inline-block;
            vertical-align: middle;
            margin-right: 0.25rem;
        }

        .fi-fo-field-wrp label {
            font-size: 0.75rem;
            font-weight: 600;
            color: #1B3A2D;
            margin-bottom: 0.25rem;
            font-family: 'Roboto', sans-serif;
        }

        .fi-grid-col {
            margin-top: 15px;
        }

        .fi-fo-field-label-content {
            font-weight: 600;
        }

        .fi-fo-field-label-required-mark {
            color: red;
        }

        .fi-input {
            border-radius: 0.5rem !important;
            border: 1px solid #1B5E3B !important;
            padding: 0.5rem 0.75rem !important;
            font-size: 0.875rem;
            transition: all 0.2s;
            width: 100%;
            margin-top: 10px;
            font-family: 'Roboto', sans-serif !important;
        }

        .fi-input:focus {
            border-color: #1B5E3B !important;
            box-shadow: 0 0 0 2px rgba(27, 94, 59, 0.1) !important;
            outline: none !important;
        }

        .fi-input-wrp {
            border: none !important;
            box-shadow: none !important;
            padding: 0 !important;
            margin: 0 !important;
            position: relative !important;
        }

        .fi-input-wrp-actions {
            position: relative;
        }

        .fi-input-wrp-actions button{
            height: 25px;
            width: 25px;
            padding: 0 !important;
            margin: 0 !important;
            position: absolute;
            z-index: 20 !important;
            right: 0.5rem !important;
            transform: translateY(-120%) !important;
            border: none !important;
            stroke: none !important;
        }

        .fi-input-wrp-actions button:hover {
            border: none !important;
            stroke: none !important;
        }
    </style>
</div>
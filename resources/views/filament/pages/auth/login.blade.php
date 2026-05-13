<div class="w-full h-screen overflow-hidden relative">
        <!-- Full Screen Background -->
        <div class="absolute inset-0">
            <img src="{{ asset('background.png') }}" alt="Background" class="w-full h-full object-cover">
            <!-- Overlay for better text readability -->
            <div class="absolute bg-gradient-to-r from-[#F5F5F0]/95 via-[#F5F5F0]/90 to-[#F5F5F0]/80"></div>
        </div>
        
        <!-- Main Content Container -->
        <div class="relative z-10 w-full h-full flex justify-center px-4 sm:px-6 lg:px-8 py-4 sm:py-6">
            <div class="w-full max-w-8xl mx-auto">
                <!-- Apply scale only on desktop, normal on mobile -->
                <div class="transform scale-90 sm:scale-95 lg:scale-80 origin-center">
                    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 lg:gap-12 items-center">
                        
                        <!-- Left Side - Branding & Illustration -->
                        <div class="flex flex-col items-center lg:items-start space-y-4 sm:space-y-6 lg:space-y-8 lg:order-1 sm:-mt-50">
                            <!-- Logo -->
                            <div class="flex items-center gap-3">
                                <img src="{{ asset('logo.png') }}" alt="Artha Jaya Logo" class="h-12 sm:h-12 lg:h-14 w-auto">
                            </div>
                            
                            <!-- Headline -->
                            <div class="space-y-3 sm:space-y-4 text-center lg:text-left">
                                <h2 class="text-2xl sm:text-3xl lg:text-4xl xl:text-5xl font-bold text-[#1B3A2D] leading-tight">
                                    System Management<br>
                                    Jasa & Layanan
                                </h2>
                                
                                <!-- Decorative Line -->
                                <div class="flex gap-1 justify-center lg:justify-start">
                                    <div class="h-1 w-12 sm:w-16 bg-[#1B5E3B] rounded-full"></div>
                                    <div class="h-1 w-6 sm:w-8 bg-[#C62828] rounded-full"></div>
                                </div>
                                
                                <p class="text-[#5A5A5A] text-xs sm:text-sm lg:text-base leading-relaxed max-w-md">
                                    Sistem manajemen untuk penginputan jasa & layanan<br>
                                    serta produksi stepnosing yang terintegrasi.
                                </p>
                            </div>
                        </div>
                        
                        <!-- Right Side - Login Form -->
                        <div class="flex justify-center order-1 lg:order-2">
                            <div class="w-full max-w-sm sm:max-w-md bg-white rounded-2xl shadow-xl p-5 sm:p-6 lg:p-8">
                                <!-- Form Header -->
                                <div class="text-center mb-5 sm:mb-6 lg:mb-8">
                                    <h3 class="text-lg sm:text-2xl lg:text-3xl font-bold text-[#1B3A2D] mb-1 sm:mb-2">Selamat Datang</h3>
                                    <p class="text-[#5A5A5A] text-xs sm:text-sm">Silakan masuk untuk melanjutkan</p>
                                </div>
                                
                                <!-- Login Form -->
                                <form wire:submit="authenticate" class="space-y-3 sm:space-y-4 lg:space-y-5">
                                    {{ $this->form }}

                                    <!-- Submit Button -->
                                    <button 
                                        type="submit" 
                                        wire:loading.attr="disabled"
                                        class="w-full bg-[#1B5E3B] hover:bg-[#145230] text-white font-semibold py-2 sm:py-2.5 lg:py-3 px-4 rounded-lg transition duration-200 focus:outline-none focus:ring-2 focus:ring-[#1B5E3B] focus:ring-offset-2 shadow-md hover:shadow-lg text-xs sm:text-sm disabled:opacity-50 disabled:cursor-not-allowed relative"
                                    >
                                        <span wire:loading.remove>Masuk</span>
                                        <span wire:loading class="relative inline-flex items-center justify-center">
                                            <span>Memproses...</span>
                                            <svg class="animate-spin h-5 w-5 text-white absolute left-[27px] sm:left-[32px] top-[3px]" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" style="z-index: 10;">
                                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                            </svg>
                                        </span>
                                    </button>
                                </form>
                                
                                <style>
                                    /* Style form field wrappers */
                                    .fi-fo-field-wrp {
                                        margin-bottom: 0;
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
                                    
                                    /* Style labels */
                                    .fi-fo-field-wrp label {
                                        font-size: 0.75rem;
                                        font-weight: 600;
                                        color: #1B3A2D;
                                        margin-bottom: 0.25rem;
                                    }
                                    
                                    /* Style input fields */
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
                                        border-radius: 0.5rem;
                                        border: 1px solid #1B5E3B;
                                        padding: 0.5rem 0.75rem;
                                        font-size: 0.875rem;
                                        transition: all 0.2s;
                                        width: 100%;
                                        margin-top: 10px;
                                    }
                                    
                                    .fi-input:focus {
                                        border-color: #1B5E3B;
                                        box-shadow: 0 0 0 2px rgba(27, 94, 59, 0.1);
                                        outline: none;
                                    }
                                    
                                    /* Style input wrapper */
                                    .fi-input-wrp {
                                        border: none;
                                        box-shadow: none;
                                        padding: 0;
                                    }
                                </style>
                                
                                <!-- Footer -->
                                <div class="mt-6 text-center">
                                    <p class="text-xs text-[#5A5A5A]">
                                        &copy; {{ date('Y') }} Artha Jaya. All rights reserved.
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
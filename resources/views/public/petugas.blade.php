@extends('layouts.public')

@section('title', 'Portal Petugas - Artha Jaya')

@push('styles')
<style>
    .petugas-container {
        max-width: 480px;
        margin: 0 auto;
    }

    .fi-section-header {
        display: flex;
        flex-direction: column;
        align-items: center;
        gap: 10px;
        padding: 30px 0;
    }

    .otp-wrapper {
        display: flex;
        flex-direction: column;
        align-items: center;
        gap: 8px;
    }

    .otp-group {
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 8px;
    }

    .otp-digit {
        width: 48px;
        height: 56px;
        padding: 0;
        border: 2px solid var(--aj-border);
        border-radius: var(--aj-radius);
        font-size: 24px;
        font-weight: 600;
        font-family: 'SF Mono', 'Monaco', 'Cascadia Code', monospace;
        color: var(--aj-text);
        background: var(--aj-surface);
        text-align: center;
        transition: border-color 0.15s ease, box-shadow 0.15s ease;
        caret-color: var(--aj-primary);
    }

    .otp-digit:focus {
        outline: none;
        border-color: var(--aj-primary);
        box-shadow: 0 0 0 3px rgba(34, 197, 94, 0.15);
    }

    .otp-digit.has-error {
        border-color: var(--aj-error);
    }

    .otp-digit.has-error:focus {
        box-shadow: 0 0 0 3px rgba(239, 68, 68, 0.15);
    }

    .otp-separator {
        font-size: 24px;
        font-weight: 600;
        color: var(--aj-text-muted);
        user-select: none;
        padding: 0 2px;
        line-height: 1;
    }

    .otp-hint {
        font-size: 13px;
        color: var(--aj-text-secondary);
        text-align: center;
    }

    .otp-form.is-submitting .otp-digit {
        opacity: 0.6;
        pointer-events: none;
    }

    .otp-form.is-submitting .fi-action-btn {
        opacity: 0.6;
        pointer-events: none;
    }

    @media (max-width: 768px) {
        .otp-digit {
            width: 44px;
            height: 52px;
            font-size: 22px;
        }

        .otp-group {
            gap: 6px;
        }
    }

    @media (max-width: 380px) {
        .otp-digit {
            width: 40px;
            height: 48px;
            font-size: 20px;
        }

        .otp-separator {
            font-size: 20px;
        }
    }
</style>
@endpush

@section('header')
    <div class="fi-header">
        <h1 class="fi-header-title">Portal Petugas</h1>
        <p class="fi-header-subtitle">Masukkan kode token yang diterima via WhatsApp</p>
    </div>
@endsection

@section('content')
    @php
        $hasTokenError = $errors->has('token');
        $oldToken = preg_replace('/\D/', '', old('token', ''));
        $oldDigits = str_pad(substr($oldToken, 0, 6), 6, ' ', STR_PAD_RIGHT);
    @endphp

    <div class="petugas-container">
        <div class="fi-section">
            <div class="fi-section-header">
                <svg class="fi-section-header-icon" xmlns="http://www.w3.org/2000/svg" width="50" height="50" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <rect x="3" y="11" width="18" height="11" rx="2" ry="2"></rect>
                    <path d="M7 11V7a5 5 0 0 1 10 0v4"></path>
                </svg>
                <h3 class="fi-section-header-heading">
                    Verifikasi Kode Token
                </h3>
                <p class="fi-section-header-description">Masukkan 6 digit kode dari pesan WhatsApp</p>
            </div>
            <div class="fi-section-content">
                <form
                    id="otp-form"
                    class="otp-form"
                    action="{{ route('petugas.verify') }}"
                    method="POST"
                    autocomplete="off"
                >
                    @csrf
                    <input type="hidden" id="token" name="token" value="{{ $oldToken }}">

                    <div class="form-group" style="margin-bottom: 0;">
                        <label class="form-label" for="otp-1">Kode Token</label>

                        <div class="otp-wrapper">
                            <div class="otp-group" id="otp-group">
                                @for ($i = 0; $i < 6; $i++)
                                    @if ($i === 3)
                                        <span class="otp-separator" aria-hidden="true">-</span>
                                    @endif
                                    <input
                                        type="text"
                                        id="otp-{{ $i + 1 }}"
                                        class="otp-digit{{ $hasTokenError ? ' has-error' : '' }}"
                                        inputmode="numeric"
                                        pattern="[0-9]*"
                                        maxlength="1"
                                        autocomplete="one-time-code"
                                        data-index="{{ $i }}"
                                        value="{{ $oldDigits[$i] !== ' ' ? $oldDigits[$i] : '' }}"
                                        aria-label="Digit {{ $i + 1 }}"
                                        @if ($i === 0) autofocus @endif
                                    >
                                @endfor
                            </div>
                        </div>

                        @error('token')
                            <p class="fi-error-message" id="token-error">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="fi-actions" style="margin-top: 20px;">
                        <button type="submit" class="fi-action-btn fi-action-btn-primary" id="verify-btn">
                            Verifikasi
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
<script>
(function () {
    const form = document.getElementById('otp-form');
    const hiddenInput = document.getElementById('token');
    const inputs = Array.from(document.querySelectorAll('.otp-digit'));
    const hasServerError = @json($hasTokenError);

    let autoSubmitEnabled = !hasServerError;
    let lastSubmittedToken = hasServerError ? hiddenInput.value : '';

    function getTokenValue() {
        return inputs.map(function (input) {
            return input.value.replace(/\D/g, '');
        }).join('');
    }

    function syncHiddenInput() {
        hiddenInput.value = getTokenValue();
    }

    function isComplete() {
        return getTokenValue().length === 6;
    }

    function focusInput(index) {
        if (index >= 0 && index < inputs.length) {
            inputs[index].focus();
            inputs[index].select();
        }
    }

    function clearErrors() {
        inputs.forEach(function (input) {
            input.classList.remove('has-error');
        });
        var errorEl = document.getElementById('token-error');
        if (errorEl) {
            errorEl.remove();
        }
    }

    function submitForm() {
        syncHiddenInput();
        form.classList.add('is-submitting');
        form.submit();
    }

    function tryAutoSubmit() {
        if (!autoSubmitEnabled || !isComplete()) {
            return;
        }

        var token = getTokenValue();
        if (token === lastSubmittedToken) {
            return;
        }

        lastSubmittedToken = token;
        submitForm();
    }

    inputs.forEach(function (input, index) {
        input.addEventListener('input', function () {
            autoSubmitEnabled = true;
            clearErrors();

            var digit = input.value.replace(/\D/g, '').slice(-1);
            input.value = digit;

            syncHiddenInput();

            if (digit && index < inputs.length - 1) {
                focusInput(index + 1);
            }

            tryAutoSubmit();
        });

        input.addEventListener('keydown', function (event) {
            if (event.key === 'Backspace') {
                if (!input.value && index > 0) {
                    event.preventDefault();
                    focusInput(index - 1);
                }
                autoSubmitEnabled = true;
                return;
            }

            if (event.key === 'ArrowLeft' && index > 0) {
                event.preventDefault();
                focusInput(index - 1);
                return;
            }

            if (event.key === 'ArrowRight' && index < inputs.length - 1) {
                event.preventDefault();
                focusInput(index + 1);
                return;
            }

            if (event.key === 'Enter' && isComplete()) {
                event.preventDefault();
                autoSubmitEnabled = true;
                tryAutoSubmit();
            }
        });

        input.addEventListener('paste', function (event) {
            event.preventDefault();
            autoSubmitEnabled = true;
            clearErrors();

            var pasted = (event.clipboardData || window.clipboardData)
                .getData('text')
                .replace(/\D/g, '')
                .slice(0, 6);

            if (!pasted) {
                return;
            }

            pasted.split('').forEach(function (char, i) {
                if (inputs[i]) {
                    inputs[i].value = char;
                }
            });

            syncHiddenInput();
            focusInput(Math.min(pasted.length, inputs.length - 1));
            tryAutoSubmit();
        });
    });

    form.addEventListener('submit', function () {
        syncHiddenInput();
        form.classList.add('is-submitting');
    });

    syncHiddenInput();

    if (hasServerError && isComplete()) {
        focusInput(inputs.length - 1);
    }
})();
</script>
@endpush

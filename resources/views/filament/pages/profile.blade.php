<style>
    .profile-page-container {
        display: grid;
        grid-template-columns: 1fr;
        gap: 24px;
    }

    @media (min-width: 1024px) {
        .profile-page-container {
            grid-template-columns: 320px 1fr;
            gap: 32px;
        }
    }

    .profile-section {
        border: 1px solid #e5e7eb;
        border-radius: 12px;
        padding: 32px 24px;
        background: #ffffff;
        height: fit-content;
    }

    .dark .profile-section {
        border-color: #374151;
        background: #111827;
    }

    .profile-header {
        display: flex;
        flex-direction: column;
        align-items: center;
        gap: 20px;
        text-align: center;
    }

    .profile-image-container {
        flex-shrink: 0;
        position: relative;
    }

    .profile-image {
        height: 140px;
        width: 140px;
        border-radius: 50%;
        object-fit: cover;
        border: 4px solid #f3f4f6;
    }

    .dark .profile-image {
        border-color: #374151;
    }

    .profile-image-placeholder {
        display: flex;
        height: 140px;
        width: 140px;
        align-items: center;
        justify-content: center;
        border-radius: 50%;
        background: #000;
    }

    .dark .profile-image-placeholder {
        background: #000;
    }

    .profile-image-placeholder svg {
        height: 64px;
        width: 64px;
        color: #ffffff;
    }

    .profile-info {
        display: flex;
        flex-direction: column;
        align-items: center;
        gap: 8px;
        width: 100%;
    }

    .profile-name {
        font-size: 22px;
        font-weight: 600;
        letter-spacing: -0.025em;
        color: #111827;
        margin: 0;
        line-height: 1.3;
    }

    .dark .profile-name {
        color: #f9fafb;
    }

    .profile-role {
        font-size: 14px;
        color: #6b7280;
        margin: 0;
        font-weight: 500;
    }

    .dark .profile-role {
        color: #9ca3af;
    }

    .profile-email {
        font-size: 13px;
        color: #9ca3af;
        margin: 0;
        word-break: break-word;
        max-width: 100%;
    }

    .dark .profile-email {
        color: #6b7280;
    }

    .profile-actions {
        display: flex;
        align-items: center;
        gap: 12px;
        margin-top: 12px;
        width: 100%;
        justify-content: center;
    }

    .section-heading {
        font-size: 20px;
        font-weight: 600;
        letter-spacing: -0.025em;
        color: #111827;
        margin: 0 0 28px 0;
        padding-bottom: 16px;
        border-bottom: 2px solid #e5e7eb;
    }

    .dark .section-heading {
        color: #f9fafb;
        border-bottom-color: #374151;
    }

    .info-grid {
        display: grid;
        grid-template-columns: 1fr;
        gap: 0;
    }

    @media (min-width: 768px) {
        .info-grid {
            grid-template-columns: 1fr 1fr;
            gap: 0 32px;
        }
    }

    .info-item {
        display: flex;
        flex-direction: column;
        gap: 8px;
        padding: 16px 0;
        border-bottom: 1px solid #f3f4f6;
    }

    .dark .info-item {
        border-bottom-color: #1f2937;
    }

    .info-item:last-child {
        border-bottom: none;
        padding-bottom: 0;
    }

    @media (min-width: 768px) {
        .info-item:nth-last-child(-n+2) {
            border-bottom: none;
            padding-bottom: 0;
        }
    }

    .info-label {
        font-size: 13px;
        font-weight: 600;
        color: #6b7280;
        display: flex;
        align-items: center;
        gap: 8px;
        text-transform: uppercase;
        letter-spacing: 0.05em;
    }

    .dark .info-label {
        color: #9ca3af;
    }

    .info-label svg {
        width: 16px;
        height: 16px;
        flex-shrink: 0;
        color: #9ca3af;
    }

    .dark .info-label svg {
        color: #6b7280;
    }

    .info-value {
        font-size: 15px;
        color: #111827;
        font-weight: 500;
        line-height: 1.5;
        word-break: break-word;
    }

    .dark .info-value {
        color: #f9fafb;
    }
</style>

<x-filament-panels::page>
    <div class="profile-page-container">
        {{-- Left Column - Profile Card --}}
        <div class="profile-section">
            <div class="profile-header">
                {{-- Profile Image --}}
                <div class="profile-image-container">
                    @if($this->getProfileImageUrl())
                        <img 
                            src="{{ $this->getProfileImageUrl() }}" 
                            alt="{{ $user->name }}"
                            class="profile-image"
                        />
                    @else
                        <div class="profile-image-placeholder">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                            </svg>
                        </div>
                    @endif
                </div>

                {{-- Profile Info --}}
                <div class="profile-info">
                    <h2 class="profile-name">
                        {{ $user->name }}
                    </h2>
                    <p class="profile-role">
                        {{ ucfirst(str_replace('_', ' ', $user->role)) }}
                    </p>
                    <p class="profile-email">
                        {{ $user->email }}
                    </p>

                    <div class="profile-actions">
                        <a href="{{ route('filament.admin.pages.edit-profile') }}">
                            <x-filament::button
                                tag="span"
                                icon="heroicon-o-pencil"
                                size="sm"
                            >
                                Edit Profile
                            </x-filament::button>
                        </a>
                    </div>
                </div>
            </div>
        </div>

        {{-- Right Column - Account Information --}}
        <div class="profile-section">
            <h3 class="section-heading">
                Account Information
            </h3>

            <div class="info-grid">
                {{-- Name --}}
                <div class="info-item">
                    <label class="info-label">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                        </svg>
                        Name
                    </label>
                    <div class="info-value">
                        {{ $user->name }}
                    </div>
                </div>

                {{-- Username --}}
                <div class="info-item">
                    <label class="info-label">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V8a2 2 0 00-2-2h-5m-4 0V5a2 2 0 114 0v1m-4 0a2 2 0 104 0m-5 8a2 2 0 100-4 2 2 0 000 4zm0 0c1.306 0 2.417.835 2.83 2M9 14a3.001 3.001 0 00-2.83 2M15 11h3m-3 4h2" />
                        </svg>
                        Username
                    </label>
                    <div class="info-value">
                        {{ $user->username }}
                    </div>
                </div>

                {{-- Email --}}
                <div class="info-item">
                    <label class="info-label">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                        </svg>
                        Email
                    </label>
                    <div class="info-value">
                        {{ $user->email }}
                    </div>
                </div>

                {{-- Contact --}}
                <div class="info-item">
                    <label class="info-label">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z" />
                        </svg>
                        Contact
                    </label>
                    <div class="info-value">
                        {{ $user->kontak ?? '-' }}
                    </div>
                </div>

                {{-- Role --}}
                <div class="info-item">
                    <label class="info-label">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
                        </svg>
                        Role
                    </label>
                    <div class="info-value">
                        {{ ucfirst(str_replace('_', ' ', $user->role)) }}
                    </div>
                </div>

                {{-- Branch --}}
                <div class="info-item">
                    <label class="info-label">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                        </svg>
                        Branch
                    </label>
                    <div class="info-value">
                        {{ $user->branch ?? '-' }}
                    </div>
                </div>

                {{-- Created At --}}
                <div class="info-item">
                    <label class="info-label">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                        </svg>
                        Account Created
                    </label>
                    <div class="info-value">
                        {{ $user->createdAt ? $user->createdAt->format('d M Y, H:i') : '-' }}
                    </div>
                </div>

                {{-- Updated At --}}
                <div class="info-item">
                    <label class="info-label">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                        </svg>
                        Last Updated
                    </label>
                    <div class="info-value">
                        {{ $user->UpdateAt ? $user->UpdateAt->format('d M Y, H:i') : '-' }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-filament-panels::page>

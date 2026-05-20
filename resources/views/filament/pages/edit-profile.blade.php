<x-filament-panels::page>
    <style>
        /* .form-section {
            margin-top: 24px;
        } */
        
        .form-section.active {
            display: block;
        }
        
        .form-actions {
            display: flex;
            justify-content: flex-end;
            gap: 12px;
            margin-top: 24px;
        }
        .fi-header {
            padding-top: 25px;
        }

        /* Breadcrumbs Styling */
        .fi-breadcrumbs {
            margin-top: -95px;
        }

        .fi-breadcrumbs-list {
            display: flex;
            align-items: center;
            gap: 8px;
            list-style: none;
            padding: 0;
            margin: 0;
        }

        .fi-breadcrumbs-item {
            display: flex;
            align-items: center;
            gap: 8px;
            font-size: 14px;
        }

        .fi-breadcrumbs-item a {
            color: #6b7280;
            text-decoration: none;
            transition: color 0.2s;
        }

        .fi-breadcrumbs-item a:hover {
            color: #111827;
        }

        .dark .fi-breadcrumbs-item a {
            color: #9ca3af;
        }

        .dark .fi-breadcrumbs-item a:hover {
            color: #f9fafb;
        }

        .fi-breadcrumbs-item[aria-current="page"] {
            color: #111827;
            font-weight: 500;
        }

        .dark .fi-breadcrumbs-item[aria-current="page"] {
            color: #f9fafb;
        }

        .fi-breadcrumbs-separator {
            color: #d1d5db;
        }

        .dark .fi-breadcrumbs-separator {
            color: #4b5563;
        }
    </style>

    {{-- Breadcrumbs --}}
    <div class="breadcrumbs-wrapper">
        <nav class="fi-breadcrumbs" aria-label="Breadcrumb">
            <ol class="fi-breadcrumbs-list">
                <li class="fi-breadcrumbs-item">
                    <a href="{{ route('filament.admin.pages.profile') }}">
                        Profile
                    </a>
                </li>
                <li class="fi-breadcrumbs-separator" aria-hidden="true">
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="m9 18 6-6-6-6"/>
                    </svg>
                </li>
                <li class="fi-breadcrumbs-item" aria-current="page">
                    Edit Profile
                </li>
            </ol>
        </nav>
    </div>

    <div class="profile-container" x-data="{ showForm: false }">
    {{-- Edit Form Section --}}
        <div class="form-section">
            {{ $this->schema }}

            <div class="form-actions">
                <x-filament::button color="secondary">
                    <x-filament::icon
                        style="width: 20px; height: 20px;"
                    />
                    Batal
                </x-filament::button>
                <x-filament::button wire:click="save" color="primary">
                    <x-filament::icon
                        icon="heroicon-o-check-circle"
                        style="width: 20px; height: 20px;"
                    />
                    Simpan Perubahan
                </x-filament::button>
            </div>
        </div>
    </div>

    <x-filament-actions::modals />

    @script
    <script>
        $wire.on('focus-field', (event) => {
            setTimeout(() => {
                const field = event.field;
                const input = document.querySelector(`[name="${field}"]`);
                if (input) {
                    input.focus();
                    input.select();
                }
            }, 100);
        });
    </script>
    @endscript
</x-filament-panels::page>

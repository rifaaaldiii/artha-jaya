<?php

namespace App\Filament\Pages;

use App\Models\User;
use Filament\Pages\Page;
use Filament\Support\Icons\Heroicon;
use Illuminate\Support\Facades\Auth;
use BackedEnum;

class Profile extends Page
{
    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedUserCircle;

    protected static ?int $navigationSort = 9999;

    public ?User $user = null;

    public function mount(): void
    {
        $this->user = Auth::user();
    }

    public static function getNavigationLabel(): string
    {
        return 'My Profile';
    }

    public static function shouldRegisterNavigation(): bool
    {
        return true;
    }

    public static function getNavigationGroup(): ?string
    {
        return 'System';
    }

    public function getProfileImageUrl(): ?string
    {
        return $this->user->profile_image_url;
    }

    public function editProfile(): void
    {
        $this->redirectRoute('filament.admin.pages.edit-profile');
    }

    public function getView(): string
    {
        return 'filament.pages.profile';
    }
}

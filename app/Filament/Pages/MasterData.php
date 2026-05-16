<?php

namespace App\Filament\Pages;

use App\Filament\Resources\Accessoris\Pages\ManageAccessoris;
use App\Filament\Resources\JenisJasas\Pages\ManageJenisJasas;
use App\Filament\Resources\KategoriJasaItems\Pages\ManageKategoriJasaItems;
use BackedEnum;
use Filament\Pages\Page;
use Illuminate\Contracts\Support\Htmlable;
use Illuminate\Support\Facades\Auth;

class MasterData extends Page
{
    protected string $view = 'filament.pages.master-data';

    protected static ?string $navigationLabel = 'Master Data';

    protected static ?int $navigationSort = 4;

    public ?string $activeTab = 'kategori-jasa';

    public static function getNavigationGroup(): ?string
    {
        return 'Jasa & Layanan';
    }

    public function mount(): void
    {
        $this->activeTab = request()->query('tab', 'kategori-jasa');
    }

    public function getTabs(): array
    {
        return [
            'kategori-jasa' => 'Kategori Jasa',
            'jenis-jasa' => 'Jenis Jasa',
            'accessories' => 'Accessories',
        ];
    }

    public function setActiveTab(string $tab): void
    {
        $this->activeTab = $tab;
    }

    public function getActiveTab(): string
    {
        return $this->activeTab;
    }

    public function getLivewireComponent(): string
    {
        return match ($this->activeTab) {
            'jenis-jasa' => ManageJenisJasas::class,
            'kategori-jasa' => ManageKategoriJasaItems::class,
            'accessories' => ManageAccessoris::class,
            default => ManageJenisJasas::class,
        };
    }

    public static function canAccess(): bool
    {
        $user = Auth::user();
        return $user && in_array($user->role, ['administrator', 'superadmin'], true);
    }
}

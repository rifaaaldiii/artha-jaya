<?php

namespace App\Filament\Resources\Pelanggans;

use App\Filament\Resources\Pelanggans\Pages\ListPelanggans;
use App\Filament\Resources\Pelanggans\Schemas\PelangganForm;
use App\Filament\Resources\Pelanggans\Tables\PelanggansTable;
use App\Models\Pelanggan;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use Illuminate\Support\Facades\Auth;

class PelangganResource extends Resource
{
    protected static ?string $model = Pelanggan::class;

    public static function getNavigationLabel(): string
    {
        return 'Customers';
    }

    public static function getNavigationGroup(): ?string
    {
        return 'System';
    }

    protected static ?int $navigationSort = 50;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedUsers;

    public static function getLabel(): ?string
    {
        return 'Customer';
    }

    public static function getPluralLabel(): ?string
    {
        return 'Customers';
    }

    public static function form(Schema $schema): Schema
    {
        return PelangganForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return PelanggansTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListPelanggans::route('/'),
        ];
    }

    public static function shouldRegisterNavigation(): bool
    {
        $user = Auth::user();

        return in_array($user->role, ['administrator', 'superadmin', 'admin_toko'], true);
    }

    public static function canCreate(): bool
    {
        $user = Auth::user();

        return in_array($user->role, ['administrator', 'superadmin', 'admin_toko'], true);
    }

    public static function canEdit(Model $record): bool
    {
        $user = Auth::user();

        return in_array($user->role, ['administrator', 'superadmin', 'admin_toko'], true);
    }

    public static function canDelete(Model $record): bool
    {
        $user = Auth::user();

        return in_array($user->role, ['administrator', 'superadmin'], true);
    }

    public static function canDeleteAny(): bool
    {
        $user = Auth::user();

        return in_array($user->role, ['administrator', 'superadmin'], true);
    }
}

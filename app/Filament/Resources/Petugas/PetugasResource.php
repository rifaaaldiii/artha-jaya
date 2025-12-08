<?php

namespace App\Filament\Resources\Petugas;

use App\Filament\Resources\Petugas\Pages\ListPetugas;
use App\Filament\Resources\Petugas\Schemas\PetugasForm;
use App\Filament\Resources\Petugas\Tables\PetugasTable;
use App\Models\Petugas;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Tables\Table;
use Illuminate\Support\Facades\Auth;

class PetugasResource extends Resource
{
    protected static ?string $model = Petugas::class;

    public static function getNavigationLabel(): string
    {
        return 'Petugas';
    }
    public static function getLabel(): ?string
    {
        return 'Petugas';
    }

    public static function getPluralLabel(): ?string
    {
        return 'Tabel Petugas';
    }

    public static function form(Schema $schema): Schema
    {
        return PetugasForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return PetugasTable::configure($table);
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
            'index' => ListPetugas::route('/'),
        ];
    }

    public static function getNavigationGroup(): ?string
    {
        return 'Jasa & Layanan';
    }

    public static function shouldRegisterNavigation(): bool
    {
        $user = Auth::user();

        return in_array($user->role, ['administrator', 'admin_toko'], true);
    }

    protected static ?int $navigationSort = 4;
}

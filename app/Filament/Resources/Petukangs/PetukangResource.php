<?php

namespace App\Filament\Resources\Petukangs;

use App\Filament\Resources\Petukangs\Pages\ListPetukangs;
use App\Filament\Resources\Petukangs\Schemas\PetukangForm;
use App\Filament\Resources\Petukangs\Tables\PetukangsTable;
use App\Models\Petukang;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Tables\Table;
use Illuminate\Support\Facades\Auth;

class PetukangResource extends Resource
{
    protected static ?string $model = Petukang::class;

    public static function getNavigationLabel(): string
    {
        return 'Petukang';
    }
    public static function getLabel(): ?string
    {
        return 'Petukang';
    }

    public static function getPluralLabel(): ?string
    {
        return 'Tabel Petukang';
    }

    public static function form(Schema $schema): Schema
    {
        return PetukangForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return PetukangsTable::configure($table);
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
            'index' => ListPetukangs::route('/'),
        ];
    }

    public static function getNavigationGroup(): ?string
    {
        return 'Management';
    }

    public static function shouldRegisterNavigation(): bool
    {
        $user = Auth::user();

        return in_array($user->role, ['administrator', 'admin_toko'], true);
    }
}

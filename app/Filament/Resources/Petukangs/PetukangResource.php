<?php

namespace App\Filament\Resources\Petukangs;

use App\Filament\Resources\Petukangs\Pages\CreatePetukang;
use App\Filament\Resources\Petukangs\Pages\EditPetukang;
use App\Filament\Resources\Petukangs\Pages\ListPetukangs;
use App\Filament\Resources\Petukangs\Schemas\PetukangForm;
use App\Filament\Resources\Petukangs\Tables\PetukangsTable;
use App\Models\Petukang;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class PetukangResource extends Resource
{
    protected static ?string $model = Petukang::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedWrenchScrewdriver;

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
            'create' => CreatePetukang::route('/create'),
            'edit' => EditPetukang::route('/{record}/edit'),
        ];
    }

    public static function getNavigationGroup(): ?string
    {
        return 'Management';
    }
}

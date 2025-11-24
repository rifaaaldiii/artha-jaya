<?php

namespace App\Filament\Resources\Teams;

use App\Filament\Resources\Teams\Pages\ListTeams;
use App\Filament\Resources\Teams\Schemas\TeamForm;
use App\Filament\Resources\Teams\Tables\TeamsTable;
use App\Models\Team;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Tables\Table;
use Illuminate\Support\Facades\Auth;

class TeamResource extends Resource
{
    protected static ?string $model = Team::class;

    public static function getNavigationLabel(): string
    {
        return 'Team';
    }
    public static function getLabel(): ?string
    {
        return 'Team';
    }

    public static function getPluralLabel(): ?string
    {
        return 'Tabel Team';
    }

    public static function form(Schema $schema): Schema
    {
        return TeamForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return TeamsTable::configure($table);
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
            'index' => ListTeams::route('/'),
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

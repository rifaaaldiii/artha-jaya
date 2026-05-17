<?php

namespace App\Filament\Resources\Accessoris;

use App\Filament\Resources\Accessoris\Pages;
use App\Filament\Resources\Accessoris\Schemas\AccessoriForm;
use App\Filament\Resources\Accessoris\Tables\AccessorisTable;
use App\Models\Accessori;
use UnitEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class AccessoriResource extends Resource
{
    protected static ?string $model = Accessori::class;

    protected static ?string $navigationLabel = 'Accessories';

    protected static ?int $navigationSort = 5;

    public static function getLabel(): ?string
    {
        return 'Accessori';
    }

    public static function getPluralLabel(): ?string
    {
        return 'Accessories';
    }

    public static function getNavigationGroup(): ?string
    {
        return 'Jasa & Layanan';
    }

    public static function form(Schema $schema): Schema
    {
        return AccessoriForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return AccessorisTable::configure($table);
    }

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()->with('jenisJasa');
    }

    public static function getRelations(): array
    {
        return [];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ManageAccessoris::route('/'),
        ];
    }

    protected static function canManage(): bool
    {
        $user = Auth::user();
    
        return $user && in_array($user->role, ['administrator', 'superadmin'], true);
    }

    public static function shouldRegisterNavigation(): bool
    {
        return false;
    }

    public static function canCreate(): bool
    {
        return static::canManage();
    }

    public static function canEdit(Model $record): bool
    {
        return static::canManage();
    }

    public static function canDelete(Model $record): bool
    {
        return static::canManage();
    }

    public static function canDeleteAny(): bool
    {
        return static::canManage();
    }
}

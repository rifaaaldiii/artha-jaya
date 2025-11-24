<?php

namespace App\Filament\Resources\JenisJasas;

use App\Filament\Resources\JenisJasas\Pages;
use App\Filament\Resources\JenisJasas\Schemas\JenisJasaForm;
use App\Filament\Resources\JenisJasas\Tables\JenisJasasTable;
use App\Models\JenisJasa;
use UnitEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class JenisJasaResource extends Resource
{
    protected static ?string $model = JenisJasa::class;

    protected static ?string $navigationLabel = 'Jenis Jasa';

    protected static UnitEnum|string|null $navigationGroup = 'Management';

    protected static ?int $navigationSort = 50;

    public static function form(Schema $schema): Schema
    {
        return JenisJasaForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return JenisJasasTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ManageJenisJasas::route('/'),
        ];
    }

    protected static function canManage(): bool
    {
        $user = Auth::user();

        return $user && in_array($user->role, ['administrator', 'admin_toko'], true);
    }

    public static function shouldRegisterNavigation(): bool
    {
        return static::canManage();
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


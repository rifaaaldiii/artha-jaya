<?php

namespace App\Filament\Resources\Jasas;

use App\Filament\Resources\Jasas\Pages\CreateJasa;
use App\Filament\Resources\Jasas\Pages\EditJasa;
use App\Filament\Resources\Jasas\Pages\ListJasas;
use App\Filament\Resources\Jasas\Schemas\JasaForm;
use App\Filament\Resources\Jasas\Tables\JasasTable;
use App\Models\jasa;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Tables\Table;
use Illuminate\Support\Facades\Auth;

class JasaResource extends Resource
{
    protected static ?string $model = jasa::class;
    protected static ?string $title = 'Jasa Input';

    public static function getNavigationLabel(): string
    {
        return 'Jasa & Layanan Input';
    }

    public static function form(Schema $schema): Schema
    {
        return JasaForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return JasasTable::configure($table);
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
            'index' => ListJasas::route('/'),
            'create' => CreateJasa::route('/create'),
            'edit' => EditJasa::route('/{record}/edit'),
        ];
    }

    public static function getNavigationGroup(): ?string
    {
        return 'Jasa & Layanan';
    }

    public static function shouldRegisterNavigation(): bool
    {
        $user = Auth::user();

        return in_array($user->role, ['administrator', 'admin_toko', 'kepala_teknisi_lapangan', 'petugas'], true);
    }
}

<?php

namespace App\Filament\Resources\Accessoris\Schemas;

use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class AccessoriForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema->components([
            TextInput::make('itemcode')
                ->label('Item Code')
                ->required()
                ->unique(ignoreRecord: true)
                ->maxLength(255),
            TextInput::make('nama')
                ->label('Nama')
                ->required()
                ->maxLength(255),
            TextInput::make('harga')
                ->label('Harga')
                ->required()
                ->numeric()
                ->prefix('Rp'),
            TextInput::make('uom')
                ->label('UOM')
                ->required()
                ->default('pcs')
                ->maxLength(255),
        ]);
    }
}

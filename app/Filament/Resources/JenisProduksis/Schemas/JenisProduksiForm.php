<?php

namespace App\Filament\Resources\JenisProduksis\Schemas;

use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class JenisProduksiForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema->components([
            TextInput::make('nama')
                ->label('Nama Jenis Produksi')
                ->required()
                ->unique(ignoreRecord: true)
                ->maxLength(255),
            Textarea::make('deskripsi')
                ->label('Deskripsi')
                ->rows(3)
                ->nullable()
                ->columnSpanFull(),
        ]);
    }
}


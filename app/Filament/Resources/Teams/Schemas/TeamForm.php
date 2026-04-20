<?php

namespace App\Filament\Resources\Teams\Schemas;

use Filament\Schemas\Schema;
use Filament\Forms\Components\TextInput;

class TeamForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make("nama")
                    ->label("Nama Team")
                    ->required()
                    ->unique(ignoreRecord: true),
                TextInput::make("order")
                    ->label("Urutan Tampilan")
                    ->numeric()
                    ->default(0)
                    ->helperText("Semakin kecil angka, semakin tinggi posisinya"),
            ]);
    }
}

<?php

namespace App\Filament\Resources\Teams\Schemas;

use Filament\Schemas\Schema;
use Filament\Forms\Components\Select;
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
                Select::make("status")->label("Status")->required()->options([
                    "ready"=> "Ready",
                    "busy"=> "Busy",
                ]),
            ]);
    }
}

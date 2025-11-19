<?php

namespace App\Filament\Resources\Petukangs\Schemas;

use Filament\Schemas\Schema;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;

class PetukangForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
        ->components([
            TextInput::make("nama")->label("Nama")->required(),
            TextInput::make("kontak")->label("Kontak")->required(),
            Select::make("status")->label("Status")->required()->options([
                "ready"=> "Ready",
                "busy"=> "Busy",
            ]),
            
            Select::make('team_id')
                ->label('Team')
                ->relationship(
                    name: 'team',
                    titleAttribute: 'nama',
                )
                ->searchable()
                ->preload()
                ->required(),
        ]);
    }
}

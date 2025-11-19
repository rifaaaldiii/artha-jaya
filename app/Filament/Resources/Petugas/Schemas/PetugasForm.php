<?php

namespace App\Filament\Resources\Petugas\Schemas;

use Filament\Schemas\Schema;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;

class PetugasForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make("nama")->label("Nama")->required()->unique(ignoreRecord: true),
                TextInput::make("kontak")->label("Kontak")->required(),
                Select::make("status")->label("Status")->required()->options([
                    "ready"=> "Ready",
                    "busy"=> "Busy",
                ]),
            ]);
    }
}

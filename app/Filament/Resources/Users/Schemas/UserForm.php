<?php

namespace App\Filament\Resources\Users\Schemas;

use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Hash;

class UserForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make("name")->label("Nama")->required(),
                TextInput::make("email")
                    ->label("Email")
                    ->required()
                    ->unique(ignoreRecord: true),
                Select::make("role")->label("Role")->options([
                    "administrator"=> "Administrator",
                    "admin_toko"=> "Admin Toko",
                    "admin_gudang"=> "Admin gudang",
                    "kepala_teknisi_lapangan"=> "Kepala Teknisi Lapangan",
                    "kepala_teknisi_gudang"=> "Kepala Teknisi Gudang",
                    "petukang"=> "Petukang",
                    "petugas"=> "Petugas",
                ]),
                TextInput::make('password')
                    ->label('Password')
                    ->password()
                    ->revealable()
                    ->required(fn (?Model $record) => $record === null)
                    ->dehydrated(fn ($state) => filled($state))
                    ->dehydrateStateUsing(fn ($state) => Hash::make($state)),
            ]);
    }
}

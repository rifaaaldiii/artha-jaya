<?php

namespace App\Filament\Resources\Users\Schemas;

use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;

class UserForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make("name")->label("Nama")->required(),
                TextInput::make("username")
                    ->label("Username")
                    ->required()
                    ->unique(ignoreRecord: true),
                TextInput::make("email")
                    ->label("Email")
                    ->required()
                    ->unique(ignoreRecord: true),
                TextInput::make("kontak")
                    ->label("Kontak")
                    ->tel()
                    ->placeholder("Contoh: 08123456789")
                    ->nullable(),
                Select::make("role")
                    ->label("Role")
                    ->options([
                        "administrator"=> "Administrator",
                        "superadmin"=> "Superadmin",
                        "admin_toko"=> "Admin Toko",
                        "kepala_lapangan"=> "Kepala Lapangan",
                    ])
                    ->required(),
                Select::make("branch")
                    ->label("Branch")
                    ->options([
                        'AJC' => 'AJC',
                        'AJP' => 'AJP',
                        'AJK' => 'AJK',
                        'AJR' => 'AJR',
                    ])
                    ->searchable()
                    ->preload()
                    ->nullable(),
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

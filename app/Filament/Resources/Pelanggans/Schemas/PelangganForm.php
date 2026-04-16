<?php

namespace App\Filament\Resources\Pelanggans\Schemas;

use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Schemas\Schema;
use Carbon\Carbon;

class PelangganForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('nama')
                    ->label('Nama Customer')
                    ->required()
                    ->maxLength(255)
                    ->placeholder('Masukkan nama customer'),

                TextInput::make('kontak')
                    ->label('Kontak')
                    ->tel()
                    ->required()
                    ->maxLength(50)
                    ->placeholder('Contoh: 08123456789'),

                Textarea::make('alamat')
                    ->label('Alamat')
                    ->required()
                    ->rows(3)
                    ->placeholder('Masukkan alamat lengkap customer'),

                TextInput::make('createdAt')
                    ->default(fn ($record = null) =>
                        $record?->createdAt
                            ? Carbon::parse($record->createdAt)->setTimezone('Asia/Bangkok')->toDateTimeString()
                            : Carbon::now('Asia/Bangkok')->toDateTimeString()
                    )
                    ->hidden()
                    ->dehydrated(fn ($state) => filled($state)),

                TextInput::make('UpdateAt')
                    ->default(fn ($record = null) =>
                        $record?->UpdateAt
                            ? Carbon::parse($record->UpdateAt)->setTimezone('Asia/Bangkok')->toDateTimeString()
                            : null
                    )
                    ->hidden()
                    ->dehydrated(fn ($state) => filled($state)),
            ]);
    }
}

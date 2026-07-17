<?php

namespace App\Filament\Resources\Schedules\Schemas;

use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class ScheduleForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                DateTimePicker::make('jadwal_petugas')
                    ->label('Jadwal Petugas')
                    ->required()
                    ->seconds(false),
                Select::make('branch')
                    ->label('Branch')
                    ->options([
                        'AJC' => 'AJC',
                        'AJP' => 'AJP',
                        'AJK' => 'AJK',
                        'AJR' => 'AJR',
                    ])
                    ->searchable()
                    ->required(),
                Textarea::make('alamat')
                    ->label('Alamat')
                    ->rows(2)
                    ->columnSpanFull(),
                Textarea::make('keterangan')
                    ->label('Keterangan')
                    ->rows(2)
                    ->columnSpanFull(),
                Textarea::make('catatan')
                    ->label('Catatan')
                    ->rows(2)
                    ->columnSpanFull(),
                TextInput::make('pic')
                    ->label('PIC')
                    ->maxLength(255),
                Textarea::make('pekerja')
                    ->label('Pekerja')
                    ->rows(2)
                    ->helperText('Nama petugas, pisahkan dengan koma jika lebih dari satu.')
                    ->columnSpanFull(),
                Select::make('status')
                    ->label('Status')
                    ->options([
                        'Terjadwal' => 'Terjadwal',
                        'Selesai' => 'Selesai',
                    ])
                    ->default('Terjadwal')
                    ->required(),
            ]);
    }
}

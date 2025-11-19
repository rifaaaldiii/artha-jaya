<?php

namespace App\Filament\Resources\Produksis\Schemas;

use App\Models\team;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class ProduksiForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make("no_produksi")
                    ->label("No. Produksi")
                    ->required()
                    ->hidden()
                    ->dehydrated(fn ($state) => filled($state)) // Only dehydrate when field is not null
                    ->default(function ($record = null) {
                        // Only auto-generate if creating (not editing)
                        if ($record && $record->no_produksi) {
                            return $record->no_produksi;
                        }

                        $prefix = 'P-';
                        $padLength = 5;
                        $lastNo = \App\Models\Produksi::query()
                            ->where('no_produksi', 'like', $prefix . '%')
                            ->orderByDesc('id')
                            ->value('no_produksi');

                        if ($lastNo) {
                            $num = intval(substr($lastNo, strlen($prefix)));
                            $nextNum = $num + 1;
                        } else {
                            $nextNum = 1;
                        }

                        return $prefix . str_pad($nextNum, $padLength, '0', STR_PAD_LEFT);
                    })
                    ->afterStateHydrated(function ($component, $state, $record) {
                        // In case the input is still null for create, set default
                        if (blank($state)) {
                            $prefix = 'P-';
                            $padLength = 5;
                            $lastNo = \App\Models\Produksi::query()
                                ->where('no_produksi', 'like', $prefix . '%')
                                ->orderByDesc('id')
                                ->value('no_produksi');
                            if ($lastNo) {
                                $num = intval(substr($lastNo, strlen($prefix)));
                                $nextNum = $num + 1;
                            } else {
                                $nextNum = 1;
                            }
                            $component->state($prefix . str_pad($nextNum, $padLength, '0', STR_PAD_LEFT));
                        }
                    }),
                Select::make("nama_produksi")->label("Jenis Produksi")->required()->options([
                    "Step Nosing"=> "Step Nosing",
                    "Plint"=> "Plint",
                ]),
                TextInput::make("nama_bahan")
                    ->label("Nama Bahan")
                    ->required(),
                TextInput::make("jumlah")
                    ->label("Jumlah")
                    ->numeric()
                    ->required(),
                Select::make('team_id')
                    ->label('Team')
                    ->relationship('team', 'nama', fn ($query) => $query->where('status', 'ready'))
                    ->searchable()
                    ->preload()
                    ->getOptionLabelUsing(fn ($value): ?string => team::find($value)?->nama)
                    ->required(),
                Textarea::make("catatan")
                    ->label("Catatan"),
                TextInput::make("status")
                    ->default("produksi baru")
                    ->hidden()
                    ->required(),
                TextInput::make("createdAt")
                    ->default(fn ($record = null) =>
                        $record?->createdAt?->toDateTimeString() ?? now()->toDateTimeString()
                    )
                    ->hidden()
                    ->dehydrated(fn ($state) => filled($state)),
                TextInput::make("updateAt")
                    ->default(fn ($record = null) =>
                        $record?->updateAt?->toDateTimeString() ?? null
                    )
                    ->hidden()
                    ->dehydrated(fn ($state) => filled($state)),
            ]);
    }
}

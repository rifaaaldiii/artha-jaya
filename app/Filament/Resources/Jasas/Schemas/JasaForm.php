<?php

namespace App\Filament\Resources\Jasas\Schemas;

use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Toggle;
use Carbon\Carbon;
use Filament\Schemas\Schema;
use App\Models\JenisJasa;
use App\Models\pelanggan;

class JasaForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
        ->components([
            TextInput::make("no_jasa")
                ->label("No. jasa")
                ->required()
                ->hidden()
                ->dehydrated(true)
                ->default(function ($record = null) {
                    if ($record && $record->no_jasa) {
                        return $record->no_jasa;
                    }

                    $prefix = 'J-';
                    $padLength = 5;
                    $lastNo = \App\Models\Jasa::query()
                        ->where('no_jasa', 'like', $prefix . '%')
                        ->orderByDesc('id')
                        ->value('no_jasa');

                    if ($lastNo) {
                        $num = intval(substr($lastNo, strlen($prefix)));
                        $nextNum = $num + 1;
                    } else {
                        $nextNum = 1;
                    }

                    return $prefix . str_pad($nextNum, $padLength, '0', STR_PAD_LEFT);
                })
                ->afterStateHydrated(function ($component, $state, $record) {
                    if (blank($state)) {
                        $prefix = 'J-';
                        $padLength = 5;
                        $lastNo = \App\Models\Jasa::query()
                            ->where('no_jasa', 'like', $prefix . '%')
                            ->orderByDesc('id')
                            ->value('no_jasa');
                        if ($lastNo) {
                            $num = intval(substr($lastNo, strlen($prefix)));
                            $nextNum = $num + 1;
                        } else {
                            $nextNum = 1;
                        }
                        $component->state($prefix . str_pad($nextNum, $padLength, '0', STR_PAD_LEFT));
                    }
                }),
            Select::make("jenis_layanan")
                ->label("Jenis Jasa & Layanan")
                ->required()
                ->searchable()
                ->preload()
                ->options(fn () => JenisJasa::query()
                    ->orderBy('nama')
                    ->pluck('nama', 'nama')
                    ->toArray()
                )
                ->helperText('Kelola pilihan pada menu Management › Jenis Jasa'),

            TextInput::make("no_ref")
                ->label("No. Ref")
                ->required(),
            
            Select::make('pelanggan_id')
                ->label('Pilih Pelanggan')
                ->options(function () {
                    return pelanggan::query()
                        ->orderBy('nama')
                        ->get()
                        ->mapWithKeys(function ($pelanggan) {
                            return [$pelanggan->id => $pelanggan->nama . ' | ' . $pelanggan->alamat];
                        })
                        ->toArray();
                })
                ->searchable()
                ->getSearchResultsUsing(function (string $search) {
                    return pelanggan::query()
                        ->where(function ($query) use ($search) {
                            $searchTerm = '%' . trim($search) . '%';
                            $query->where('nama', 'like', $searchTerm)
                                ->orWhere('alamat', 'like', $searchTerm)
                                ->orWhere('kontak', 'like', $searchTerm);
                        })
                        ->orderBy('nama')
                        ->limit(50)
                        ->get()
                        ->mapWithKeys(function ($pelanggan) {
                            return [$pelanggan->id => $pelanggan->nama . ' | ' . $pelanggan->alamat];
                        })
                        ->toArray();
                })
                ->getOptionLabelUsing(fn ($value): ?string => 
                    ($data = pelanggan::find($value)) ? ($data->nama . ' | ' . $data->alamat) : null
                )
                ->preload()
                ->required(fn ($get, $record) => $record ? true : !$get('create_new_pelanggan'))
                ->visible(fn ($get, $record) => $record ? true : !$get('create_new_pelanggan'))
                ->dehydrated(true)
                ->reactive()
                ->afterStateUpdated(function ($state, callable $set, $get, $record) {
                    if ($record && $state) {
                        $pelanggan = pelanggan::find($state);
                        if ($pelanggan) {
                            $set('edit_pelanggan_nama', $pelanggan->nama);
                            $set('edit_pelanggan_kontak', $pelanggan->kontak);
                            $set('edit_pelanggan_alamat', $pelanggan->alamat);
                        }
                    }
                }),
            
            TextInput::make('edit_pelanggan_nama')
                ->label('Nama Pelanggan')
                ->required()
                ->visible(fn ($get, $record) => $record && ($get('pelanggan_id') || $record->pelanggan_id))
                ->dehydrated(fn ($get, $record) => $record && ($get('pelanggan_id') || $record->pelanggan_id))
                ->default(fn ($record) => $record?->pelanggan?->nama)
                ->afterStateHydrated(function ($component, $state, $record, $get) {
                    if (blank($state)) {
                        $pelangganId = $get('pelanggan_id') ?? $record?->pelanggan_id;
                        if ($pelangganId) {
                            $pelanggan = pelanggan::find($pelangganId);
                            if ($pelanggan) {
                                $component->state($pelanggan->nama);
                            }
                        }
                    }
                })
                ->rules([
                    function ($get, $record) {
                        return function (string $attribute, $value, \Closure $fail) use ($get, $record) {
                            if ($record && $value) {
                                $pelangganId = $get('pelanggan_id') ?? $record->pelanggan_id;
                                $exists = pelanggan::where('nama', $value)
                                    ->where('kontak', $get('edit_pelanggan_kontak'))
                                    ->where('alamat', $get('edit_pelanggan_alamat'))
                                    ->where('id', '!=', $pelangganId)
                                    ->exists();
                                
                                if ($exists) {
                                    $fail('Pelanggan dengan nama, kontak, dan alamat yang sama sudah ada.');
                                }
                            }
                        };
                    },
                ]),
            
            TextInput::make('edit_pelanggan_kontak')
                ->label('Kontak')
                ->required()
                ->visible(fn ($get, $record) => $record && ($get('pelanggan_id') || $record->pelanggan_id))
                ->dehydrated(fn ($get, $record) => $record && ($get('pelanggan_id') || $record->pelanggan_id))
                ->default(fn ($record) => $record?->pelanggan?->kontak)
                ->afterStateHydrated(function ($component, $state, $record, $get) {
                    if (blank($state)) {
                        $pelangganId = $get('pelanggan_id') ?? $record?->pelanggan_id;
                        if ($pelangganId) {
                            $pelanggan = pelanggan::find($pelangganId);
                            if ($pelanggan) {
                                $component->state($pelanggan->kontak);
                            }
                        }
                    }
                })
                ->rules([
                    function ($get, $record) {
                        return function (string $attribute, $value, \Closure $fail) use ($get, $record) {
                            if ($record && $value) {
                                $pelangganId = $get('pelanggan_id') ?? $record->pelanggan_id;
                                $exists = pelanggan::where('nama', $get('edit_pelanggan_nama'))
                                    ->where('kontak', $value)
                                    ->where('alamat', $get('edit_pelanggan_alamat'))
                                    ->where('id', '!=', $pelangganId)
                                    ->exists();
                                
                                if ($exists) {
                                    $fail('Pelanggan dengan nama, kontak, dan alamat yang sama sudah ada.');
                                }
                            }
                        };
                    },
                ]),
            
            Textarea::make('edit_pelanggan_alamat')
                ->label('Alamat')
                ->required()
                ->visible(fn ($get, $record) => $record && ($get('pelanggan_id') || $record->pelanggan_id))
                ->dehydrated(fn ($get, $record) => $record && ($get('pelanggan_id') || $record->pelanggan_id))
                ->default(fn ($record) => $record?->pelanggan?->alamat)
                ->afterStateHydrated(function ($component, $state, $record, $get) {
                    if (blank($state)) {
                        $pelangganId = $get('pelanggan_id') ?? $record?->pelanggan_id;
                        if ($pelangganId) {
                            $pelanggan = pelanggan::find($pelangganId);
                            if ($pelanggan) {
                                $component->state($pelanggan->alamat);
                            }
                        }
                    }
                })
                ->rules([
                    function ($get, $record) {
                        return function (string $attribute, $value, \Closure $fail) use ($get, $record) {
                            if ($record && $value) {
                                $pelangganId = $get('pelanggan_id') ?? $record->pelanggan_id;
                                $exists = pelanggan::where('nama', $get('edit_pelanggan_nama'))
                                    ->where('kontak', $get('edit_pelanggan_kontak'))
                                    ->where('alamat', $value)
                                    ->where('id', '!=', $pelangganId)
                                    ->exists();
                                
                                if ($exists) {
                                    $fail('Pelanggan dengan nama, kontak, dan alamat yang sama sudah ada.');
                                }
                            }
                        };
                    },
                ]),
            
            TextInput::make('new_pelanggan_nama')
                ->label('Nama Pelanggan')
                ->required(fn ($get) => $get('create_new_pelanggan'))
                ->visible(fn ($get, $record) => !$record && $get('create_new_pelanggan'))
                ->dehydrated(fn ($get) => $get('create_new_pelanggan'))
                ->rules([
                    function ($get) {
                        return function (string $attribute, $value, \Closure $fail) use ($get) {
                            if ($get('create_new_pelanggan') && $value) {
                                $exists = pelanggan::where('nama', $value)
                                    ->where('kontak', $get('new_pelanggan_kontak'))
                                    ->where('alamat', $get('new_pelanggan_alamat'))
                                    ->exists();
                                
                                if ($exists) {
                                    $fail('Pelanggan dengan nama, kontak, dan alamat yang sama sudah ada.');
                                }
                            }
                        };
                    },
                ]),
            
            TextInput::make('new_pelanggan_kontak')
                ->label('Kontak')
                ->required(fn ($get) => $get('create_new_pelanggan'))
                ->visible(fn ($get, $record) => !$record && $get('create_new_pelanggan'))
                ->dehydrated(fn ($get) => $get('create_new_pelanggan'))
                ->rules([
                    function ($get) {
                        return function (string $attribute, $value, \Closure $fail) use ($get) {
                            if ($get('create_new_pelanggan') && $value) {
                                $exists = pelanggan::where('nama', $get('new_pelanggan_nama'))
                                    ->where('kontak', $value)
                                    ->where('alamat', $get('new_pelanggan_alamat'))
                                    ->exists();
                                
                                if ($exists) {
                                    $fail('Pelanggan dengan nama, kontak, dan alamat yang sama sudah ada.');
                                }
                            }
                        };
                    },
                ]),
            
            Textarea::make('new_pelanggan_alamat')
                ->label('Alamat')
                ->required(fn ($get) => $get('create_new_pelanggan'))
                ->visible(fn ($get, $record) => !$record && $get('create_new_pelanggan'))
                ->dehydrated(fn ($get) => $get('create_new_pelanggan'))
                ->rules([
                    function ($get) {
                        return function (string $attribute, $value, \Closure $fail) use ($get) {
                            if ($get('create_new_pelanggan') && $value) {
                                $exists = pelanggan::where('nama', $get('new_pelanggan_nama'))
                                    ->where('kontak', $get('new_pelanggan_kontak'))
                                    ->where('alamat', $value)
                                    ->exists();
                                
                                if ($exists) {
                                    $fail('Pelanggan dengan nama, kontak, dan alamat yang sama sudah ada.');
                                }
                            }
                        };
                    },
                ]),
            
            DateTimePicker::make("jadwal")
                ->label("Jadwal Pelanggan"),
                
            Textarea::make("catatan")
                ->label("Catatan"),
            TextInput::make("status")
                ->default("Jasa baru")
                ->hidden()
                ->required()
                ->dehydrated(true),
            TextInput::make("createdAt")
                ->default(fn ($record = null) =>
                    $record?->createdAt
                        // Set timezone ke Asia/Bangkok (WIB/Waktu Indonesia Barat juga GMT+7)
                        ? Carbon::parse($record->createdAt)->setTimezone('Asia/Bangkok')->toDateTimeString()
                        : Carbon::now('Asia/Bangkok')->toDateTimeString()
                )
                ->hidden()
                ->dehydrated(fn ($state) => filled($state)),

            Toggle::make('create_new_pelanggan')
                ->label('Buat Pelanggan Baru')
                ->default(false)
                ->reactive()
                ->visible(fn ($record) => !$record)
                ->afterStateUpdated(function ($state, callable $set) {
                    if ($state) {
                        $set('pelanggan_id', null);
                    } else {
                        $set('new_pelanggan_nama', null);
                        $set('new_pelanggan_kontak', null);
                        $set('new_pelanggan_alamat', null);
                    }
                }),
            TextInput::make("updateAt")
                ->default(fn ($record = null) =>
                    $record?->updateAt
                        ? Carbon::parse($record->updateAt)->setTimezone('Asia/Bangkok')->toDateTimeString()
                        : null
                )
                ->hidden()
                ->dehydrated(fn ($state) => filled($state)),
        ]);
    }
}

<?php

namespace App\Filament\Resources\Jasas\Schemas;

use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Checkbox;
use Filament\Schemas\Components\Section;
use Filament\Forms\Components\Placeholder;
use Carbon\Carbon;
use Filament\Schemas\Schema;
use App\Models\JenisJasa;
use App\Models\Pelanggan;
use Illuminate\Support\Facades\Auth;

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

            TextInput::make("no_ref")
                ->label("No. Ref")
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
                ->required()
                ->default(fn () => Auth::user()->branch ?? null)
                ->disabled(fn () => Auth::user()->branch !== null)
                ->dehydrated(true),
            
            Select::make('pelanggan_id')
                ->label('Pilih Customer')
                ->options(function () {
                    return Pelanggan::query()
                        ->orderBy('nama')
                        ->get()
                        ->mapWithKeys(function ($pelanggan) {
                            return [$pelanggan->id => $pelanggan->nama . ' | ' . $pelanggan->alamat];
                        })
                        ->toArray();
                })
                ->searchable()
                ->getSearchResultsUsing(function (string $search) {
                    return Pelanggan::query()
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
                    ($data = Pelanggan::find($value)) ? ($data->nama . ' | ' . $data->alamat) : null
                )
                ->preload()
                ->required(fn ($get, $record) => $record ? true : !$get('create_new_pelanggan'))
                ->visible(fn ($get, $record) => $record ? true : !$get('create_new_pelanggan'))
                ->dehydrated(true)
                ->reactive()
                ->afterStateUpdated(function ($state, callable $set, $get, $record) {
                    if ($state) {
                        $pelanggan = Pelanggan::find($state);
                        if ($pelanggan) {
                            $set('edit_pelanggan_nama', $pelanggan->nama);
                            $set('edit_pelanggan_kontak', $pelanggan->kontak);
                            $set('edit_pelanggan_alamat', $pelanggan->alamat);
                            
                            // Update info placeholders
                            $set('pelanggan_nama_info', $pelanggan->nama);
                            $set('pelanggan_kontak_info', $pelanggan->kontak);
                            $set('pelanggan_alamat_info', $pelanggan->alamat);
                            
                            // If toggle is enabled (default), update alamat jasa
                            if ($get('use_pelanggan_alamat') !== false) {
                                $set('alamat', $pelanggan->alamat);
                            }
                        }
                    }
                })
                ->afterStateHydrated(function ($state, $component, $record, $get) {
                    if ($record && $record->pelanggan_id) {
                        $pelanggan = Pelanggan::find($record->pelanggan_id);
                        if ($pelanggan) {
                            $component->livewire->data['pelanggan_nama_info'] = $pelanggan->nama;
                            $component->livewire->data['pelanggan_kontak_info'] = $pelanggan->kontak;
                            $component->livewire->data['pelanggan_alamat_info'] = $pelanggan->alamat;
                        }
                    }
                }),
            
            
            TextInput::make('edit_pelanggan_nama')
                ->label('Nama Customer')
                ->required()
                ->visible(fn ($get, $record) => $record && ($get('pelanggan_id') || $record->pelanggan_id))
                ->dehydrated(true)
                ->default(fn ($record) => $record?->pelanggan?->nama)
                ->afterStateHydrated(function ($component, $state, $record, $get) {
                    if (blank($state)) {
                        $pelangganId = $get('pelanggan_id') ?? $record?->pelanggan_id;
                        if ($pelangganId) {
                            $pelanggan = Pelanggan::find($pelangganId);
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
                                $exists = Pelanggan::where('nama', $value)
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
                ->dehydrated(true)
                ->default(fn ($record) => $record?->pelanggan?->kontak)
                ->afterStateHydrated(function ($component, $state, $record, $get) {
                    if (blank($state)) {
                        $pelangganId = $get('pelanggan_id') ?? $record?->pelanggan_id;
                        if ($pelangganId) {
                            $pelanggan = Pelanggan::find($pelangganId);
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
                                $exists = Pelanggan::where('nama', $get('edit_pelanggan_nama'))
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
                ->dehydrated(true)
                ->default(fn ($record) => $record?->pelanggan?->alamat)
                ->afterStateHydrated(function ($component, $state, $record, $get) {
                    if (blank($state)) {
                        $pelangganId = $get('pelanggan_id') ?? $record?->pelanggan_id;
                        if ($pelangganId) {
                            $pelanggan = Pelanggan::find($pelangganId);
                            if ($pelanggan) {
                                $component->state($pelanggan->alamat);
                            }
                        }
                    }
                })
                ->reactive()
                ->afterStateUpdated(function ($state, callable $set, callable $get) {
                    // Update info placeholder
                    $set('pelanggan_alamat_info', $state);
                    
                    // If toggle is enabled, update alamat jasa
                    if ($get('use_pelanggan_alamat') !== false) {
                        $set('alamat', $state);
                    }
                })
                ->rules([
                    function ($get, $record) {
                        return function (string $attribute, $value, \Closure $fail) use ($get, $record) {
                            if ($record && $value) {
                                $pelangganId = $get('pelanggan_id') ?? $record->pelanggan_id;
                                $exists = Pelanggan::where('nama', $get('edit_pelanggan_nama'))
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
                ->label('Nama Customer')
                ->required(fn ($get) => $get('create_new_pelanggan'))
                ->visible(fn ($get, $record) => !$record && $get('create_new_pelanggan'))
                ->dehydrated(fn ($get) => $get('create_new_pelanggan'))
                ->rules([
                    function ($get) {
                        return function (string $attribute, $value, \Closure $fail) use ($get) {
                            if ($get('create_new_pelanggan') && $value) {
                                $exists = Pelanggan::where('nama', $value)
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
                                $exists = Pelanggan::where('nama', $get('new_pelanggan_nama'))
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
                                $exists = Pelanggan::where('nama', $get('new_pelanggan_nama'))
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
            Repeater::make('items')
                ->relationship('items')
                ->schema([
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
                        ->reactive()
                        ->afterStateUpdated(function ($state, callable $set, callable $get) {
                            if ($state) {
                                $jenisJasa = JenisJasa::where('nama', $state)->first();
                                if ($jenisJasa && $jenisJasa->harga) {
                                    $jumlah = $get('jumlah') ?? 1;
                                    $set('harga', $jenisJasa->harga * $jumlah);
                                }
                            }
                        }),
                    TextInput::make("jumlah")
                        ->label("Jumlah")
                        ->numeric()
                        ->required()
                        ->default(1)
                        ->reactive()
                        ->afterStateUpdated(function ($state, callable $set, callable $get) {
                            $jenisLayanan = $get('jenis_layanan');
                            if ($jenisLayanan && $state) {
                                $jenisJasa = JenisJasa::where('nama', $jenisLayanan)->first();
                                if ($jenisJasa && $jenisJasa->harga) {
                                    $set('harga', $jenisJasa->harga * $state);
                                }
                            }
                        }),
                    TextInput::make("harga")
                        ->label("Harga")
                        ->numeric()
                        ->prefix('Rp')
                        ->required()
                        ->disabled()
                        ->dehydrated(true),
                ])
                ->columns(3)
                ->addActionLabel('+ Tambah Item')
                ->required()
                ->minItems(1),
            // Switch/Toggle untuk menggunakan alamat pelanggan
            Toggle::make('use_pelanggan_alamat')
                ->label('Gunakan alamat pelanggan sebagai alamat jasa')
                ->default(true)
                ->reactive()
                ->visible(fn ($get) => filled($get('pelanggan_id')) || filled($get('edit_pelanggan_nama')))
                ->helperText('Jika diaktifkan, alamat jasa akan mengikuti alamat pelanggan')
                ->afterStateUpdated(function ($state, callable $set, callable $get) {
                    if ($state) {
                        // Get alamat from pelanggan
                        $pelangganId = $get('pelanggan_id');
                        if ($pelangganId) {
                            $pelanggan = Pelanggan::find($pelangganId);
                            if ($pelanggan) {
                                $set('alamat', $pelanggan->alamat);
                            }
                        } else {
                            // For edit mode with edit_pelanggan_alamat
                            $editAlamat = $get('edit_pelanggan_alamat');
                            if ($editAlamat) {
                                $set('alamat', $editAlamat);
                            }
                        }
                    } else {
                        $set('alamat', null);
                    }
                }),
            
            // Informasi Alamat Pelanggan (Read Only)
            Section::make('Informasi Pelanggan')
                ->schema([
                    TextInput::make('pelanggan_nama_info')
                        ->label('Nama Pelanggan')
                        ->disabled()
                        ->dehydrated(false)
                        ->visible(fn ($get) => filled($get('pelanggan_id')) || filled($get('edit_pelanggan_nama'))),
                    
                    TextInput::make('pelanggan_kontak_info')
                        ->label('Kontak Pelanggan')
                        ->disabled()
                        ->dehydrated(false)
                        ->visible(fn ($get) => filled($get('pelanggan_id')) || filled($get('edit_pelanggan_nama'))),
                    
                    Textarea::make('pelanggan_alamat_info')
                        ->label('Alamat Pelanggan')
                        ->disabled()
                        ->dehydrated(false)
                        ->visible(fn ($get) => filled($get('pelanggan_id')) || filled($get('edit_pelanggan_nama'))),
                ])
                ->columns(1)
                ->visible(fn ($get) => filled($get('pelanggan_id')) || filled($get('edit_pelanggan_nama')))
                ->statePath(''),
            
            
            Textarea::make('alamat')
                ->label('Alamat Jasa')
                ->required()
                ->visible(fn ($get) => !$get('use_pelanggan_alamat'))
                ->reactive()
                ->dehydrated(true)
                ->helperText('Input alamat jasa secara manual jika berbeda dari alamat pelanggan'),
            
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
                ->label('Create new customer')
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

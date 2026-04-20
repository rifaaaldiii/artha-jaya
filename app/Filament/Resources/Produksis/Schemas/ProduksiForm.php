<?php

namespace App\Filament\Resources\Produksis\Schemas;

use App\Models\JenisProduksi;
use App\Models\Pelanggan;
use App\Models\Team;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Components\DateTimePicker;
use Filament\Schemas\Components\Section;
use Carbon\Carbon;
use Filament\Schemas\Schema;
use Illuminate\Support\Facades\Auth;

class ProduksiForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
        ->components([
            TextInput::make("no_produksi")
                ->label("No. produksi")
                ->required()
                ->hidden()
                ->dehydrated(true)
                ->default(function ($record = null) {
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

            TextInput::make("no_ref")
                ->label("No. Ref")
                ->required()
                ->unique(table: 'produksis', column: 'no_ref', ignorable: fn ($record) => $record),
            
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
                            
                            // Auto-fill alamat produksi dari alamat pelanggan
                            $set('alamat', $pelanggan->alamat);
                        }
                    }
                })
                ->afterStateHydrated(function ($state, $component, $record, $get, $set) {
                    if ($record && $record->pelanggan_id) {
                        $pelanggan = Pelanggan::find($record->pelanggan_id);
                        if ($pelanggan) {
                            $set('pelanggan_nama_info', $pelanggan->nama);
                            $set('pelanggan_kontak_info', $pelanggan->kontak);
                            $set('pelanggan_alamat_info', $pelanggan->alamat);
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
                    // Update info placeholder only
                    $set('pelanggan_alamat_info', $state);
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
                ->reactive()
                ->afterStateUpdated(function ($state, callable $set, callable $get) {
                    // Auto-fill alamat produksi dari alamat pelanggan baru
                    if ($get('create_new_pelanggan') && $state) {
                        $set('alamat', $state);
                    }
                })
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
                    Select::make("nama_produksi")
                        ->label("Jenis Produksi")
                        ->required()
                        ->searchable()
                        ->preload()
                        ->options(fn () => JenisProduksi::query()
                            ->orderBy('nama')
                            ->pluck('nama', 'nama')
                            ->toArray()
                        )
                        ->reactive()
                        ->afterStateUpdated(function ($state, callable $set, callable $get) {
                            if ($state) {
                                $jenisProduksi = JenisProduksi::where('nama', $state)->first();
                                if ($jenisProduksi && $jenisProduksi->harga) {
                                    $jumlah = $get('jumlah') ?? 1;
                                    $set('harga', $jenisProduksi->harga * $jumlah);
                                }
                            }
                        }),
                    TextInput::make("nama_bahan")
                        ->label("Nama Bahan")
                        ->required()
                        ->columnSpan(2),
                    TextInput::make("jumlah")
                        ->label("Jumlah")
                        ->numeric()
                        ->required()
                        ->default(1)
                        ->reactive()
                        ->afterStateUpdated(function ($state, callable $set, callable $get) {
                            $namaProduksi = $get('nama_produksi');
                            if ($namaProduksi && $state) {
                                $jenisProduksi = JenisProduksi::where('nama', $namaProduksi)->first();
                                if ($jenisProduksi && $jenisProduksi->harga) {
                                    $set('harga', $jenisProduksi->harga * $state);
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
        
            Textarea::make('alamat')
                ->label('Alamat Produksi')
                ->required()
                ->reactive()
                ->dehydrated(true)
                ->helperText('Alamat otomatis terisi dari data customer. Ubah jika alamat produksi berbeda.'),
            
            DateTimePicker::make('jadwal')
                ->label('Jadwal Produksi')
                ->native(false)
                ->displayFormat('d/m/Y H:i')
                ->format('Y-m-d H:i:s')
                ->default(now())
                ->required()
                ->helperText('Jadwal pelaksanaan produksi'),
            
            Select::make('team_id')
                ->label('Team')
                ->relationship('team', 'nama', fn ($query) => $query->orderBy('order', 'asc'))
                ->searchable()
                ->preload()
                ->getOptionLabelUsing(fn ($value): ?string => Team::find($value)?->nama)
                ->options(function () {
                    return Team::orderBy('order', 'asc')
                        ->get()
                        ->mapWithKeys(function ($team) {
                            $activeCount = $team->getActiveProduksisCount();
                            $capacity = $team->hasAvailableCapacity() ? 'Tersedia' : 'Penuh';
                            return [
                                $team->id => $team->nama . ' (' . $activeCount . ' produksi aktif - ' . $capacity . ')'
                            ];
                        })
                        ->toArray();
                })
                ->required()
                ->helperText('Team dapat menangani beberapa produksi sekaligus'),
                
            Textarea::make("catatan")
                ->label("Catatan"),
            TextInput::make("status")
                ->default("baru")
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
                ->afterStateUpdated(function ($state, callable $set, callable $get) {
                    if ($state) {
                        $set('pelanggan_id', null);
                        // If there's already a new_pelanggan_alamat filled, use it
                        $newAlamat = $get('new_pelanggan_alamat');
                        if ($newAlamat) {
                            $set('alamat', $newAlamat);
                        }
                    } else {
                        $set('new_pelanggan_nama', null);
                        $set('new_pelanggan_kontak', null);
                        $set('new_pelanggan_alamat', null);
                        // Clear alamat produksi jika toggle dimatikan
                        if (!$get('pelanggan_id')) {
                            $set('alamat', null);
                        }
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

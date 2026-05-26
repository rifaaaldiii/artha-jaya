<?php

namespace App\Filament\Resources\Produksis\Schemas;

use App\Models\JenisProduksi;
use App\Models\Pelanggan;
use App\Models\Produksi;
use App\Models\Team;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\DatePicker;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Grid;
use Filament\Forms\Components\Placeholder;
use Carbon\Carbon;
use Filament\Schemas\Schema;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class ProduksiForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
        ->components([
            // Hidden Fields
            TextInput::make("no_produksi")
                ->label("No. produksi")
                ->required()
                ->hidden()
                ->dehydrated(true),

            TextInput::make("status")
                ->default("baru")
                ->hidden()
                ->required()
                ->dehydrated(true),
                
            TextInput::make("createdAt")
                ->default(fn ($record = null) =>
                    $record?->createdAt
                        ? Carbon::parse($record->createdAt)->setTimezone('Asia/Bangkok')->toDateTimeString()
                        : Carbon::now('Asia/Bangkok')->toDateTimeString()
                )
                ->hidden()
                ->dehydrated(fn ($state) => filled($state)),

            TextInput::make("updateAt")
                ->default(fn ($record = null) =>
                    $record?->updateAt
                        ? Carbon::parse($record->updateAt)->setTimezone('Asia/Bangkok')->toDateTimeString()
                        : null
                )
                ->hidden()
                ->dehydrated(fn ($state) => filled($state)),

            // Section: Informasi Referensi
            Section::make('Informasi Referensi')
                ->icon('heroicon-o-identification')
                ->description('Nomor referensi dan branch')
                ->schema([
                    Grid::make(2)
                        ->schema([
                            TextInput::make("no_ref")
                                ->label("No. Ref")
                                ->required()
                                ->unique(table: 'produksis', column: 'no_ref', ignorable: fn ($record) => $record)
                                ->columnSpan(1),
                            
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
                                ->dehydrated(true)
                                ->columnSpan(1),
                        ]),
                ])
                ->collapsible(),

            // Section: Informasi Customer
            Section::make('Informasi Customer')
                ->icon('heroicon-o-user')
                ->description('Data pelanggan dan alamat')
                ->schema([
                    Toggle::make('create_new_pelanggan')
                        ->label('Buat Customer Baru')
                        ->default(false)
                        ->reactive()
                        ->visible(fn ($record) => !$record)
                        ->helperText('Aktifkan untuk membuat customer baru')
                        ->afterStateUpdated(function ($state, callable $set, callable $get) {
                            if ($state) {
                                $set('pelanggan_id', null);
                                $set('alamat', null);
                            } else {
                                $set('new_pelanggan_nama', null);
                                $set('new_pelanggan_kontak', null);
                                if (!$get('pelanggan_id')) {
                                    $set('alamat', null);
                                }
                            }
                        })
                        ->columnSpanFull(),

                    // Existing Customer Selection
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
                        ->required()
                        ->dehydrated(true)
                        ->reactive()
                        ->visible(fn ($get, $record) => !$record && !$get('create_new_pelanggan'))
                        ->afterStateUpdated(function ($state, callable $set, callable $get) {
                            if ($state) {
                                $pelanggan = Pelanggan::find($state);
                                if ($pelanggan) {
                                    $set('alamat', $pelanggan->alamat);
                                }
                            } else {
                                $set('alamat', null);
                            }
                        })
                        ->afterStateHydrated(function ($state, $component, $record, $get, $set) {
                            if ($record && $record->pelanggan_id) {
                                $pelanggan = Pelanggan::find($record->pelanggan_id);
                                if ($pelanggan) {
                                    if (blank($get('alamat'))) {
                                        $set('alamat', $pelanggan->alamat);
                                    }
                                }
                            }
                        })
                        ->columnSpanFull(),

                    // New Customer Fields
                    Grid::make(2)
                        ->schema([
                            TextInput::make('new_pelanggan_nama')
                                ->label('Nama Customer')
                                ->required(fn ($get) => $get('create_new_pelanggan'))
                                ->visible(fn ($get, $record) => !$record && $get('create_new_pelanggan'))
                                ->dehydrated(fn ($get) => $get('create_new_pelanggan'))
                                ->columnSpan(1),
                            
                            TextInput::make('new_pelanggan_kontak')
                                ->label('Kontak')
                                ->required(fn ($get) => $get('create_new_pelanggan'))
                                ->visible(fn ($get, $record) => !$record && $get('create_new_pelanggan'))
                                ->dehydrated(fn ($get) => $get('create_new_pelanggan'))
                                ->columnSpan(1),
                        ]),

                    Textarea::make('alamat')
                        ->label(fn ($get) => $get('create_new_pelanggan') ? 'Alamat Customer' : 'Alamat Customer')
                        ->required()
                        ->reactive()
                        ->dehydrated(true)
                        ->rows(3)
                        ->helperText('Alamat otomatis terisi dari data customer. Ubah jika alamat produksi berbeda.')
                        ->columnSpanFull(),
                ])
                ->collapsible(),

            // Section: Detail Produksi
            Section::make('Detail Produksi')
                ->icon('heroicon-o-wrench-screwdriver')
                ->description('Jenis produksi dan bahan')
                ->schema([
                    Repeater::make('items')
                        ->relationship('items')
                        ->schema([
                            Grid::make(2)
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
                                        })
                                        ->columnSpan(1),
                                        
                                    TextInput::make("nama_bahan")
                                        ->label("Nama Bahan")
                                        ->required()
                                        ->columnSpan(1),
                                ]),
                            Grid::make(3)
                                ->schema([
                                ]),
                            
                            Section::make('Ukuran Potong')
                                ->schema([
                                    Grid::make(2)
                                        ->schema([
                                            TextInput::make("ukuran_panjang")
                                                ->label("Panjang")
                                                ->numeric()
                                                ->required()
                                                ->placeholder("20")
                                                ->reactive()
                                                ->afterStateHydrated(function ($component, $state, callable $get) {
                                                    $ukuran = $get('ukuran');
                                                    if ($ukuran && str_contains($ukuran, ' x ')) {
                                                        $parts = explode(' x ', $ukuran);
                                                        $component->state(trim($parts[1]));
                                                    }
                                                })
                                                ->afterStateUpdated(function ($state, callable $set, callable $get) {
                                                    $lebar = $get('ukuran_lebar');
                                                    $panjang = $state;
                                                    if ($lebar && $panjang) {
                                                        $ukuranValue = "{$panjang} x {$lebar}";
                                                        $set('ukuran', $ukuranValue);
                                                        Log::info('=== UKURAN SET FROM FORM (PANJANG) ===', [
                                                            'panjang' => $panjang,
                                                            'lebar' => $lebar,
                                                            'ukuran_value' => $ukuranValue,
                                                        ]);
                                                    } elseif (!$lebar) {
                                                        $set('ukuran', null);
                                                        Log::info('=== UKURAN CLEARED (NO LEBAR) ===');
                                                    }
                                                })
                                                ->columnSpan(1),

                                            TextInput::make("ukuran_lebar")
                                                ->label("Lebar")
                                                ->numeric()
                                                ->required()
                                                ->placeholder("30")
                                                ->reactive()
                                                ->afterStateHydrated(function ($component, $state, callable $get) {
                                                    $ukuran = $get('ukuran');
                                                    if ($ukuran && str_contains($ukuran, ' x ')) {
                                                        $parts = explode(' x ', $ukuran);
                                                        $component->state(trim($parts[0]));
                                                    }
                                                })
                                                ->afterStateUpdated(function ($state, callable $set, callable $get) {
                                                    $lebar = $state;
                                                    $panjang = $get('ukuran_panjang');
                                                    if ($lebar && $panjang) {
                                                        $ukuranValue = "{$panjang} x {$lebar}";
                                                        $set('ukuran', $ukuranValue);
                                                        Log::info('=== UKURAN SET FROM FORM (LEBAR) ===', [
                                                            'panjang' => $panjang,
                                                            'lebar' => $lebar,
                                                            'ukuran_value' => $ukuranValue,
                                                        ]);
                                                    } elseif (!$lebar) {
                                                        $set('ukuran', null);
                                                        Log::info('=== UKURAN CLEARED (NO LEBAR) ===');
                                                    }
                                                })
                                                ->columnSpan(1),

                                            
                                                
                                        ]),
                                ]),
                                        Grid::make(3)
                                        ->schema([
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
                                        })
                                        ->columnSpan(1),
                                    
                                    TextInput::make("harga")
                                        ->label("Harga")
                                        ->numeric()
                                        ->prefix('Rp')
                                        ->required()
                                        ->disabled()
                                        ->dehydrated(true)
                                        ->columnSpan(2),
                                    ]),

                                    TextInput::make("ukuran")
                                        ->label("Ukuran (Auto)")
                                        ->disabled()
                                        ->dehydrated(true)
                                        ->visible(true)
                                        ->readOnly()
                                        ->columnSpanFull()
                                        ->afterStateHydrated(function ($component, $state) {
                                            if ($state) {
                                                Log::info('=== UKURAN FIELD HYDRATED ===', [
                                                    'state' => $state,
                                                ]);
                                            }
                                        })
                                        ->dehydrateStateUsing(function ($state) {
                                            Log::info('=== UKURAN DEHYDRATE STATE ===', [
                                                'state' => $state,
                                                'state_type' => gettype($state),
                                            ]);
                                            return $state;
                                        }),
                                    ])
                                    ->columns(1)
                                    ->addActionLabel('+ Tambah Item')
                                    ->required()
                                    ->minItems(1)
                                    ->itemLabel(function (array $state): ?string {
                                        $namaProduksi = $state['nama_produksi'] ?? null;
                                        $jumlah = $state['jumlah'] ?? 1;
                                        $ukuran = $state['ukuran'] ?? null;
                                        
                                        if ($namaProduksi) {
                                            $label = "{$namaProduksi} (x{$jumlah})";
                                            if ($ukuran) {
                                                $label .= " Ukuran {$ukuran}";
                                            }
                                            return $label;
                                        }
                                        
                                        return 'Item';
                                    })
                                    ->collapsible()
                                    ->columnSpanFull(),
                                    ])
                                    ->collapsible(),
                                    
                                    // Section: Penjadwalan & Team
                                    Section::make('Penjadwalan & Catatan')
                                    ->icon('heroicon-o-calendar')
                                    ->description('Jadwal produksi dan assignment team')
                                    ->schema([
                                        DatePicker::make('jadwal')
                                            ->label('Jadwal Produksi')
                                            ->native(false)
                                            ->default(now())
                                            ->displayFormat('d/m/Y')
                                            ->format('Y-m-d')
                                            ->minDate(today())
                                            ->required()
                                            ->afterStateHydrated(function ($component, $state) {
                                                if ($state) {
                                                    $component->state(Carbon::parse($state)->format('Y-m-d'));
                                                }
                                            })
                                            ->dehydrateStateUsing(function ($state) {
                                                return $state ? $state . ' 00:00:00' : null;
                                            })
                                            ->columnSpanFull(),
                    
                    Section::make('Catatan')
                        ->icon('heroicon-o-clipboard')
                        ->description('Catatan tambahan untuk produksi')
                        ->schema([
                            Textarea::make("catatan")
                                ->label("Catatan")
                                ->rows(4)
                                ->columnSpanFull(),
                        ])
                        ->collapsible(),
                ])
                ->collapsible(),

            // Section: Team
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
                ->columnSpan(1)
                ->helperText('Team dapat menangani beberapa produksi sekaligus')
                ->columnSpanFull(),
        ]);
    }
}

<?php

namespace App\Filament\Resources\Jasas\Schemas;

use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Checkbox;
use Filament\Schemas\Components\Section;
use Filament\Forms\Components\Placeholder;
use Carbon\Carbon;
use Filament\Schemas\Schema;
use App\Models\Jasa;
use App\Models\JenisJasa;
use App\Models\KategoriJasaItem;
use App\Models\Pelanggan;
use App\Models\Accessori;
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
                ->dehydrated(true),

            TextInput::make("no_ref")
                ->label("No. Ref")
                ->required()
                ->unique(table: 'jasas', column: 'no_ref', ignorable: fn ($record) => $record),
            
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
                ->required()
                ->dehydrated(true)
                ->reactive()
                ->visible(fn ($get, $record) => !$record && !$get('create_new_pelanggan'))
                ->afterStateUpdated(function ($state, callable $set, callable $get) {
                    if ($state) {
                        $pelanggan = Pelanggan::find($state);
                        if ($pelanggan) {
                            // Auto-fill alamat jasa dari alamat pelanggan
                            $set('alamat', $pelanggan->alamat);
                        }
                    } else {
                        // Clear alamat when customer is deselected
                        $set('alamat', null);
                    }
                })
                ->afterStateHydrated(function ($state, $component, $record, $get, $set) {
                    if ($record && $record->pelanggan_id) {
                        $pelanggan = Pelanggan::find($record->pelanggan_id);
                        if ($pelanggan) {
                            // Auto-fill alamat jasa dari alamat pelanggan existing
                            if (blank($get('alamat'))) {
                                $set('alamat', $pelanggan->alamat);
                            }
                        }
                    }
                }),
            
            
            TextInput::make('new_pelanggan_nama')
                ->label('Nama Customer')
                ->required(fn ($get) => $get('create_new_pelanggan'))
                ->visible(fn ($get, $record) => !$record && $get('create_new_pelanggan'))
                ->dehydrated(fn ($get) => $get('create_new_pelanggan')),
            
            TextInput::make('new_pelanggan_kontak')
                ->label('Kontak')
                ->required(fn ($get) => $get('create_new_pelanggan'))
                ->visible(fn ($get, $record) => !$record && $get('create_new_pelanggan'))
                ->dehydrated(fn ($get) => $get('create_new_pelanggan')),
            Textarea::make('alamat')
                ->label(fn ($get) => $get('create_new_pelanggan') ? 'Alamat' : 'Alamat Jasa Instalasi')
                ->required()
                ->reactive()
                ->dehydrated(true)
                ->helperText('Abaikan jika alamat jasa instalasi sama dengan alamat customer.'),
            
            Repeater::make('items')
                ->relationship('items')
                ->schema([
                    Select::make('kategori_jasa_item_id')
                        ->label('Kategori')
                        ->required()
                        ->searchable()
                        ->preload()
                        ->options(fn () => KategoriJasaItem::query()
                            ->orderBy('nama')
                            ->pluck('nama', 'id')
                            ->toArray()
                        )
                        ->reactive()
                        ->afterStateUpdated(function ($state, callable $set, callable $get, $component) {
                            // Reset jenis_layanan when category changes
                            $set('jenis_layanan', null);
                            $set('harga', null);
                            
                            // Check if selected jenis_jasa has related accessories
                            if ($state) {
                                $jenisJasa = JenisJasa::find($state);
                                if ($jenisJasa && $jenisJasa->accessories()->exists()) {
                                    $accessories = $jenisJasa->accessories()->orderBy('nama')->get();
                                    $accessoriesItems = [];
                                    
                                    // Dynamic jumlahMap based on accessories count and order
                                    $count = $accessories->count();
                                    if ($count === 4) {
                                        $jumlahPattern = [1, 4, 3, 2];
                                    } elseif ($count === 5) {
                                        $jumlahPattern = [1, 1, 4, 3, 2];
                                    } else {
                                        $jumlahPattern = array_fill(0, $count, 1);
                                    }
                                    
                                    foreach ($accessories as $index => $accessori) {
                                        $jumlah = $jumlahPattern[$index] ?? 1;
                                        
                                        $accessoriesItems[] = [
                                            'kategori_jasa_item_id' => $jenisJasa->kategori_id,
                                            'jenis_layanan' => $accessori->nama,
                                            'jumlah' => $jumlah,
                                            'harga' => $accessori->harga * $jumlah,
                                        ];
                                    }
                                    
                                    $set('../../accessories_items', $accessoriesItems);
                                } else {
                                    $set('../../accessories_items', []);
                                }
                            } else {
                                $set('../../accessories_items', []);
                            }
                        })
                        ->columnSpan(1),
                    Select::make("jenis_layanan")
                        ->label("Jenis Jasa & Layanan")
                        ->required()
                        ->searchable()
                        ->preload()
                        ->options(function ($get) {
                            $kategoriId = $get('kategori_jasa_item_id');
                            $query = JenisJasa::query()->orderBy('nama');
                            
                            // Filter by category if selected
                            if ($kategoriId) {
                                $query->where('kategori_id', $kategoriId);
                            }
                            
                            return $query->pluck('nama', 'nama')->toArray();
                        })
                        ->reactive()
                        ->afterStateUpdated(function ($state, callable $set, callable $get) {
                            if ($state) {
                                $jenisJasa = JenisJasa::where('nama', $state)->first();
                                if ($jenisJasa && $jenisJasa->harga) {
                                    $jumlah = $get('jumlah') ?? 1;
                                    $set('harga', $jenisJasa->harga * $jumlah);
                                }
                                
                                // Check if selected jenis_jasa has related accessories
                                if ($jenisJasa && $jenisJasa->accessories()->exists()) {
                                    $accessories = $jenisJasa->accessories()->orderBy('nama')->get();
                                    $accessoriesItems = [];
                                    $userJumlah = $get('jumlah') ?? 1;
                                    
                                    // Dynamic jumlahMap based on accessories count and order
                                    $count = $accessories->count();
                                    if ($count === 4) {
                                        $jumlahPattern = [1, 4, 3, 2];
                                    } elseif ($count === 5) {
                                        $jumlahPattern = [1, 1, 4, 3, 2];
                                    } else {
                                        $jumlahPattern = array_fill(0, $count, 1);
                                    }
                                    
                                    foreach ($accessories as $index => $accessori) {
                                        $baseJumlah = $jumlahPattern[$index] ?? 1;
                                        $finalJumlah = $baseJumlah * $userJumlah;
                                        
                                        $accessoriesItems[] = [
                                            'kategori_jasa_item_id' => $jenisJasa->kategori_id,
                                            'jenis_layanan' => $accessori->nama,
                                            'jumlah' => $finalJumlah,
                                            'harga' => $accessori->harga * $finalJumlah,
                                        ];
                                    }
                                    
                                    $set('../../accessories_items', $accessoriesItems);
                                } else {
                                    $set('../../accessories_items', []);
                                }
                            }
                        })
                        ->columnSpan(2),
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
                                
                                // Check if selected jenis_jasa has related accessories
                                if ($jenisJasa && $jenisJasa->accessories()->exists()) {
                                    $accessories = $jenisJasa->accessories()->orderBy('nama')->get();
                                    $accessoriesItems = [];
                                    
                                    // Dynamic jumlahMap based on accessories count and order
                                    $count = $accessories->count();
                                    if ($count === 4) {
                                        $jumlahPattern = [1, 4, 3, 2];
                                    } elseif ($count === 5) {
                                        $jumlahPattern = [1, 1, 4, 3, 2];
                                    } else {
                                        $jumlahPattern = array_fill(0, $count, 1);
                                    }
                                    
                                    foreach ($accessories as $index => $accessori) {
                                        $baseJumlah = $jumlahPattern[$index] ?? 1;
                                        $finalJumlah = $baseJumlah * $state;
                                        
                                        $accessoriesItems[] = [
                                            'kategori_jasa_item_id' => $jenisJasa->kategori_id,
                                            'jenis_layanan' => $accessori->nama,
                                            'jumlah' => $finalJumlah,
                                            'harga' => $accessori->harga * $finalJumlah,
                                        ];
                                    }
                                    
                                    $set('../../accessories_items', $accessoriesItems);
                                } else {
                                    $set('../../accessories_items', []);
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
        
            Repeater::make('accessories_items')
                ->relationship('items')
                ->schema([
                    Select::make('kategori_jasa_item_id')
                        ->label('Kategori')
                        ->required()
                        ->searchable()
                        ->preload()
                        ->options(fn () => KategoriJasaItem::query()
                            ->whereRaw('LOWER(nama) = ?', ['ac'])
                            ->pluck('nama', 'id')
                            ->toArray()
                        )
                        ->disabled()
                        ->dehydrated(true)
                        ->columnSpan(1),
                    TextInput::make("jenis_layanan")
                        ->label("Accessories")
                        ->required()
                        ->disabled()
                        ->dehydrated(true)
                        ->columnSpan(2),
                    TextInput::make("jumlah")
                        ->label("Jumlah")
                        ->numeric()
                        ->required()
                        ->default(1)
                        ->reactive()
                        ->afterStateUpdated(function ($state, callable $set, callable $get) {
                            if ($state) {
                                $jenisLayanan = $get('jenis_layanan');
                                if ($jenisLayanan) {
                                    $accessori = Accessori::where('nama', $jenisLayanan)->first();
                                    if ($accessori && $accessori->harga) {
                                        $set('harga', $accessori->harga * $state);
                                    }
                                }
                            }
                        })
                        ->dehydrated(true),
                    TextInput::make("harga")
                        ->label("Harga Total")
                        ->numeric()
                        ->prefix('Rp')
                        ->required()
                        ->disabled()
                        ->dehydrated(true),
                ])
                ->columns(3)
                ->visible(function ($get) {
                    $items = $get('items') ?? [];
                    foreach ($items as $item) {
                        if (isset($item['jenis_layanan'])) {
                            $jenisJasa = JenisJasa::where('nama', $item['jenis_layanan'])->first();
                            if ($jenisJasa && $jenisJasa->accessories()->exists()) {
                                return true;
                            }
                        }
                    }
                    return false;
                })
                ->collapsible()
                ->itemLabel(fn (array $state): ?string => 
                    $state['jenis_layanan'] ?? 'Accessory Item'
                )
                ->deletable(false)
                ->reorderable(false)
                ->addable(false),
        
            DatePicker::make("jadwal")
                ->label("Penjadwalan Customer")
                ->native(false)
                ->displayFormat('d/m/Y')
                ->format('Y-m-d')
                ->minDate(today())
                ->required()
                // ->disabledDates(function ($record) {
                //     return Jasa::whereNotNull('jadwal_petugas')
                //         ->when($record, fn ($query) => $query->where('id', '!=', $record->id))
                //         ->get()
                //         ->groupBy(fn ($item) => Carbon::parse($item->jadwal_petugas)->format('Y-m-d'))
                //         ->keys()
                //         ->values()
                //         ->toArray();
                // })
                ->afterStateHydrated(function ($component, $state) {
                    if ($state) {
                        $component->state(Carbon::parse($state)->format('Y-m-d'));
                    }
                })
                ->dehydrateStateUsing(function ($state) {
                    return $state ? Carbon::parse($state)->format('Y-m-d') . ' ' . Carbon::now()->format('H:i:s') : null;
                }),
                
            Textarea::make("catatan")
                ->label("Catatan"),
            Textarea::make("note")
                ->label("Catatan Internal"),
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
                ->afterStateUpdated(function ($state, callable $set, callable $get) {
                    if ($state) {
                        $set('pelanggan_id', null);
                        // Clear alamat when switching to new customer
                        $set('alamat', null);
                    } else {
                        $set('new_pelanggan_nama', null);
                        $set('new_pelanggan_kontak', null);
                        // Clear alamat when switching to existing customer without selection
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

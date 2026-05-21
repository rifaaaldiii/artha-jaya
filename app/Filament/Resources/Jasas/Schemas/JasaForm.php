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
use Filament\Schemas\Components\Grid;
use Filament\Forms\Components\Placeholder;
use Filament\Forms\Components\Fieldset;
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
    /**
     * Get accessories for a given jenis_layanan
     */
    protected static function getAccessoriesForJenisJasa(?string $jenisLayananName, int $jumlah = 1): array
    {
        if (!$jenisLayananName) {
            return [];
        }
        
        $jenisJasa = JenisJasa::where('nama', $jenisLayananName)->first();
        
        if (!$jenisJasa || !$jenisJasa->accessories()->exists()) {
            return [];
        }
        
        $accessories = $jenisJasa->accessories()->orderBy('nama')->get();
        $accessoriesItems = [];
        
        // Dynamic jumlahPattern based on accessories count and order
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
            $finalJumlah = $baseJumlah * $jumlah;
            
            $accessoriesItems[] = [
                'kategori_jasa_item_id' => $jenisJasa->kategori_id,
                'jenis_layanan' => $accessori->nama,
                'jumlah' => $finalJumlah,
                'harga' => $accessori->harga * $finalJumlah,
            ];
        }
        
        return $accessoriesItems;
    }
    
    public static function configure(Schema $schema): Schema
    {
        return $schema
        ->components([
            // Hidden Fields
            TextInput::make("no_jasa")
                ->label("No. jasa")
                ->required()
                ->hidden()
                ->dehydrated(true),

            TextInput::make("status")
                ->default("Jasa baru")
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
                                ->unique(table: 'jasas', column: 'no_ref', ignorable: fn ($record) => $record)
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
                ->description('Data pelanggan dan alamat instalasi')
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
                        ->label(fn ($get) => $get('create_new_pelanggan') ? 'Alamat Customer' : 'Alamat Jasa Instalasi')
                        ->required()
                        ->reactive()
                        ->dehydrated(true)
                        ->rows(3)
                        ->helperText('Abaikan jika alamat jasa instalasi sama dengan alamat customer.')
                        ->columnSpanFull(),
                ])
                ->collapsible(),

            // Section: Detail Jasa & Layanan
            Section::make('Detail Jasa & Layanan')
                ->icon('heroicon-o-wrench-screwdriver')
                ->description('Pilih jenis jasa dan layanan')
                ->schema([
                    Repeater::make('items')
                        ->relationship('items')
                        ->saveRelationshipsUsing(function ($component, $state, $record) {
                            // Custom save logic to handle accessories
                            \Log::info('=== items Repeater saveRelationshipsUsing ===', [
                                'jasa_id' => $record->id,
                                'state_count' => count($state),
                            ]);
                            
                            // Delete existing items
                            $record->items()->delete();
                            
                            // Create new items from state
                            foreach ($state as $itemData) {
                                \Log::info('Processing item in saveRelationshipsUsing', [
                                    'item_keys' => array_keys($itemData),
                                    'has_accessories' => isset($itemData['accessories']),
                                    'accessories_count' => isset($itemData['accessories']) ? count($itemData['accessories']) : 0,
                                ]);
                                
                                // Create main item
                                $record->items()->create([
                                    'kategori_jasa_item_id' => $itemData['kategori_jasa_item_id'] ?? null,
                                    'jenis_layanan' => $itemData['jenis_layanan'] ?? null,
                                    'jumlah' => $itemData['jumlah'] ?? 1,
                                    'harga' => $itemData['harga'] ?? 0,
                                    'createdAt' => now(),
                                ]);
                                
                                // Create accessories items if toggle is ON
                                if (isset($itemData['accessories']) && is_array($itemData['accessories'])) {
                                    foreach ($itemData['accessories'] as $accessory) {
                                        \Log::info('Creating accessory item', [
                                            'accessory' => $accessory,
                                        ]);
                                        
                                        $record->items()->create([
                                            'kategori_jasa_item_id' => $accessory['kategori_jasa_item_id'] ?? null,
                                            'jenis_layanan' => $accessory['jenis_layanan'] ?? null,
                                            'jumlah' => $accessory['jumlah'] ?? 1,
                                            'harga' => $accessory['harga'] ?? 0,
                                            'createdAt' => now(),
                                        ]);
                                    }
                                }
                            }
                            
                            \Log::info('Items and accessories saved successfully', [
                                'jasa_id' => $record->id,
                                'total_items' => $record->items()->count(),
                            ]);
                        })
                        ->schema([
                            Grid::make(3)
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
                                        ->afterStateUpdated(function ($state, callable $set, callable $get) {
                                            $set('jenis_layanan', null);
                                            $set('harga', null);
                                            $set('add_accessories', false);
                                            $set('accessories', []);
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
                                                
                                                $set('add_accessories', false);
                                                $set('accessories', []);
                                            }
                                        })
                                        ->columnSpan(2),
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
                                            $jenisLayanan = $get('jenis_layanan');
                                            if ($jenisLayanan && $state) {
                                                $jenisJasa = JenisJasa::where('nama', $jenisLayanan)->first();
                                                if ($jenisJasa && $jenisJasa->harga) {
                                                    $set('harga', $jenisJasa->harga * $state);
                                                }
                                                
                                                if ($get('add_accessories') && $jenisJasa && $jenisJasa->accessories()->exists()) {
                                                    $accessoriesItems = self::getAccessoriesForJenisJasa($jenisLayanan, $state);
                                                    $set('accessories', $accessoriesItems);
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
                            
                            Toggle::make('add_accessories')
                                ->label('Tambahkan Accessories')
                                ->helperText('Aktifkan untuk menambahkan accessories otomatis')
                                ->default(false)
                                ->reactive()
                                ->visible(function ($get) {
                                    $jenisLayanan = $get('jenis_layanan');
                                    if (!$jenisLayanan) {
                                        return false;
                                    }
                                    $jenisJasa = JenisJasa::where('nama', $jenisLayanan)->first();
                                    return $jenisJasa && $jenisJasa->accessories()->exists();
                                })
                                ->afterStateUpdated(function ($state, callable $set, callable $get) {
                                    if ($state) {
                                        $jenisLayanan = $get('jenis_layanan');
                                        $jumlah = $get('jumlah') ?? 1;
                                        $accessoriesItems = self::getAccessoriesForJenisJasa($jenisLayanan, $jumlah);
                                        $set('accessories', $accessoriesItems);
                                    } else {
                                        $set('accessories', []);
                                    }
                                })
                                ->columnSpanFull(),
                            
                            Repeater::make('accessories')
                                ->label('Accessories')
                                ->schema([
                                    Grid::make(3)
                                        ->schema([
                                            Select::make('kategori_jasa_item_id')
                                                ->label('Kategori')
                                                ->required()
                                                ->searchable()
                                                ->preload()
                                                ->options(function () {
                                                    return KategoriJasaItem::query()
                                                        ->whereRaw('LOWER(nama) = ?', ['ac'])
                                                        ->pluck('nama', 'id')
                                                        ->toArray();
                                                })
                                                ->disabled()
                                                ->dehydrated(true)
                                                ->columnSpan(1),
                                            
                                            TextInput::make("jenis_layanan")
                                                ->label("Accessories")
                                                ->required()
                                                ->disabled()
                                                ->dehydrated(true)
                                                ->columnSpan(2),
                                        ]),
                                    
                                    Grid::make(2)
                                        ->schema([
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
                                                ->dehydrated(true)
                                                ->columnSpan(1),
                                            
                                            TextInput::make("harga")
                                                ->label("Harga Total")
                                                ->numeric()
                                                ->prefix('Rp')
                                                ->required()
                                                ->disabled()
                                                ->dehydrated(true)
                                                ->columnSpan(1),
                                        ]),
                                ])
                                ->columns(1)
                                ->visible(function ($get) {
                                    return $get('add_accessories');
                                })
                                ->collapsible()
                                ->itemLabel(function (array $state): ?string {
                                    return $state['jenis_layanan'] ?? 'Accessory Item';
                                })
                                ->deletable(false)
                                ->reorderable(false)
                                ->addable(false)
                                ->dehydrated(true)
                                ->columnSpanFull(),
                        ])
                        ->columns(1)
                        ->addActionLabel('+ Tambah Item')
                        ->required()
                        ->minItems(1)
                        ->itemLabel(function (array $state): ?string {
                            $jenisLayanan = $state['jenis_layanan'] ?? null;
                            $jumlah = $state['jumlah'] ?? 1;
                            
                            if ($jenisLayanan) {
                                return "{$jenisLayanan} (x{$jumlah})";
                            }
                            
                            return 'Item';
                        })
                        ->collapsible()
                        ->columnSpanFull(),
                ])
                ->collapsible(),

            // Section: Penjadwalan & Catatan
            Section::make('Penjadwalan & Catatan')
                ->icon('heroicon-o-calendar')
                ->description('Jadwal instalasi dan catatan')
                ->schema([
                    DatePicker::make("jadwal")
                        ->label("Penjadwalan Customer")
                        ->native(false)
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
                            return $state ? Carbon::parse($state)->format('Y-m-d') . ' ' . Carbon::now()->format('H:i:s') : null;
                        })
                        ->columnSpanFull(),
                    
                    Grid::make(2)
                        ->schema([
                            Textarea::make("catatan")
                                ->label("Catatan")
                                ->rows(4)
                                ->columnSpan(1),
                            
                            Textarea::make("note")
                                ->label("Catatan Internal")
                                ->rows(4)
                                ->helperText('Catatan ini hanya terlihat oleh internal')
                                ->columnSpan(1),
                        ]),
                ])
                ->collapsible(),
        ]);
    }
}

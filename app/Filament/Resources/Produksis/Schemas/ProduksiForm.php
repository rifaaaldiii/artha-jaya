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
                ->dehydrated(true),

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
                ->required()
                ->dehydrated(true)
                ->reactive()
                ->visible(fn ($get, $record) => !$record && !$get('create_new_pelanggan'))
                ->afterStateUpdated(function ($state, callable $set, callable $get) {
                    if ($state) {
                        $pelanggan = Pelanggan::find($state);
                        if ($pelanggan) {
                            // Auto-fill alamat produksi dari alamat pelanggan
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
                            // Auto-fill alamat produksi dari alamat pelanggan existing
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
                ->label(fn ($get) => $get('create_new_pelanggan') ? 'Alamat' : 'Alamat Produksi')
                ->required()
                ->reactive()
                ->dehydrated(true)
                ->helperText('Alamat otomatis terisi dari data customer. Ubah jika alamat produksi berbeda.'),
            
            DateTimePicker::make('jadwal')
                ->label('Jadwal Produksi')
                ->native(false)
                ->displayFormat('d/m/Y H:i')
                ->format('Y-m-d H:i:s')
                ->required()
                ->disabledDates(function ($record) {
                    return Produksi::whereNotNull('jadwal')
                        ->when($record, fn ($query) => $query->where('id', '!=', $record->id))
                        ->pluck('jadwal')
                        ->filter()
                        ->map(fn ($date) => Carbon::parse($date)->format('Y-m-d'))
                        ->unique()
                        ->values()
                        ->toArray();
                })
                ->helperText('Penjadwalan produksi'),
            
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

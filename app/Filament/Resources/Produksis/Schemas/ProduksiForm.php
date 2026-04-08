<?php

namespace App\Filament\Resources\Produksis\Schemas;

use App\Models\JenisProduksi;
use App\Models\Team;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Repeater;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Schema;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class ProduksiForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Informasi Produksi')
                    ->schema([
                        Grid::make(2)
                            ->schema([
                                TextInput::make("no_ref")
                                    ->label("No. Ref")
                                    ->required()
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
                                    ->columnSpan(1),
                            ]),
                    ])
                    ->collapsible()
                    ->collapsed(false),

                Section::make('Item Produksi')
                    ->description('Tambahkan satu atau lebih item produksi')
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
                                            ->columnSpan(2),
                                        
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
                                            })
                                            ->columnSpan(1),
                                        
                                        TextInput::make("harga")
                                            ->label("Harga")
                                            ->numeric()
                                            ->prefix('Rp')
                                            ->required()
                                            ->disabled()
                                            ->dehydrated(true)
                                            ->columnSpan(1),
                                    ]),
                            ])
                            ->columns(1)
                            ->addActionLabel('+ Tambah Item')
                            ->required()
                            ->minItems(1)
                            ->collapsible()
                            ->itemLabel(fn (array $state): ?string => 
                                ($state['nama_produksi'] ?? 'Item') . 
                                (isset($state['jumlah']) ? ' - ' . $state['jumlah'] . ' unit' : '')
                            ),
                    ])
                    ->collapsible()
                    ->collapsed(false),

                Section::make('Informasi Team')
                    ->schema([
                        Grid::make(2)
                            ->schema([
                                Select::make('team_id')
                                    ->label('Team')
                                    ->relationship('team', 'nama', fn ($query) => $query->where('status', 'ready'))
                                    ->searchable()
                                    ->preload()
                                    ->getOptionLabelUsing(fn ($value): ?string => Team::find($value)?->nama)
                                    ->required()
                                    ->columnSpan(1),

                                Textarea::make("catatan")
                                    ->label("Catatan")
                                    ->rows(3)
                                    ->columnSpan(1),
                            ]),
                    ])
                    ->collapsible()
                    ->collapsed(false),

                // Hidden fields
                TextInput::make("no_produksi")
                    ->label("No. Produksi")
                    ->required()
                    ->hidden()
                    ->dehydrated(fn ($state) => filled($state))
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
                    }),

                TextInput::make("status")
                    ->default("produksi baru")
                    ->hidden()
                    ->required(),

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
            ]);
    }

    public static function shouldRegisterNavigation(): bool
    {
        $user = Auth::user();

        return in_array($user->role, ['administrator', 'admin_toko'], true);
    }
}

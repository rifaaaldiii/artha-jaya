<?php

namespace App\Filament\Resources\JenisJasas\Schemas;

use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;
use App\Models\KategoriJasaItem;

class JenisJasaForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema->components([
            TextInput::make('itemcode')
                ->label('Item Code')
                ->required()
                ->unique(ignoreRecord: true)
                ->maxLength(255),
            Select::make('kategori_id')
                ->label('Kategori')
                ->required()
                ->searchable()
                ->preload()
                ->options(fn () => KategoriJasaItem::query()
                    ->orderBy('nama')
                    ->pluck('nama', 'id')
                    ->toArray()
                ),
            TextInput::make('nama')
                ->label('Nama Jenis Jasa')
                ->required()
                ->unique(ignoreRecord: true)
                ->maxLength(255),
            TextInput::make('harga')
                ->label('Harga')
                ->numeric()
                ->prefix('Rp')
                ->nullable()
                ->maxLength(255),
            Select::make('uom')
                ->label('UOM (Unit of Measure)')
                ->options([
                    'pcs' => 'Pcs (Pieces)',
                    'unit' => 'Unit',
                    'kg' => 'Kg (Kilogram)',
                    'gram' => 'Gram',
                    'meter' => 'Meter',
                    'cm' => 'Cm (Centimeter)',
                    'liter' => 'Liter',
                    'ml' => 'Ml (Milliliter)',
                    'm2' => 'M² (Meter Persegi)',
                    'm3' => 'M³ (Meter Kubik)',
                    'roll' => 'Roll',
                    'box' => 'Box',
                    'pack' => 'Pack',
                    'set' => 'Set',
                    'buah' => 'Buah',
                    'lembar' => 'Lembar',
                    'batang' => 'Batang',
                    'lusin' => 'Lusin',
                    'jam' => 'Jam',
                    'hari' => 'Hari',
                    'paket' => 'Paket',
                    'proyek' => 'Proyek',
                    'kpg' => 'Keping',
                ])
                ->searchable()
                ->nullable()
                ->placeholder('Pilih satuan'),
        ]);
    }
}


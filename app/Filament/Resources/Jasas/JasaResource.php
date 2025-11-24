<?php

namespace App\Filament\Resources\Jasas;

use App\Filament\Resources\Jasas\Pages\ListJasas;
use App\Filament\Resources\Jasas\Schemas\JasaForm;
use App\Filament\Resources\Jasas\Tables\JasasTable;
use App\Models\jasa;
use App\Models\pelanggan;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;

class JasaResource extends Resource
{
    protected static ?string $model = jasa::class;
    protected static ?string $title = 'Jasa Input';

    public static function getNavigationLabel(): string
    {
        return 'Jasa & Layanan Input';
    }

    public static function getLabel(): ?string
    {
        return 'Jasa & Layanan';
    }

    public static function getPluralLabel(): ?string
    {
        return 'Tabel Jasa & Layanan';
    }

    public static function form(Schema $schema): Schema
    {
        return JasaForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return JasasTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListJasas::route('/'),
        ];
    }

    public static function getNavigationGroup(): ?string
    {
        return 'Jasa & Layanan';
    }

    public static function shouldRegisterNavigation(): bool
    {
        $user = Auth::user();

        return in_array($user->role, ['administrator', 'admin_toko', 'kepala_teknisi_lapangan', 'petugas'], true);
    }

    public static function canCreate(): bool
    {
        $user = Auth::user();
        return in_array($user->role, ['administrator', 'admin_toko'], true);
    }

    public static function canEdit(Model $record): bool
    {
        $user = Auth::user();
        return in_array($user->role, ['administrator', 'admin_toko'], true);
    }

    public static function canDelete(Model $record): bool
    {
        $user = Auth::user();
        return in_array($user->role, ['administrator', 'admin_toko'], true);
    }

    public static function canDeleteAny(): bool
    {
        $user = Auth::user();
        return in_array($user->role, ['administrator', 'admin_toko'], true);
    }

    public static function mutateFormDataBeforeCreate(array $data): array
    {
        if (empty($data['no_jasa'])) {
            $prefix = 'J-';
            $padLength = 5;

            $lastNo = jasa::query()
                ->where('no_jasa', 'like', $prefix . '%')
                ->orderByDesc('id')
                ->value('no_jasa');

            $nextNum = $lastNo
                ? (int) substr($lastNo, strlen($prefix)) + 1
                : 1;

            $data['no_jasa'] = $prefix . str_pad($nextNum, $padLength, '0', STR_PAD_LEFT);
        }

        if (empty($data['status'])) {
            $data['status'] = 'Jasa baru';
        }

        if (!empty($data['create_new_pelanggan'])) {
            $existingPelanggan = pelanggan::where('nama', $data['new_pelanggan_nama'] ?? null)
                ->where('kontak', $data['new_pelanggan_kontak'] ?? null)
                ->where('alamat', $data['new_pelanggan_alamat'] ?? null)
                ->first();

            if ($existingPelanggan) {
                throw ValidationException::withMessages([
                    'new_pelanggan_nama' => ['Pelanggan dengan nama, kontak, dan alamat yang sama sudah ada.'],
                ]);
            }

            $pelanggan = pelanggan::create([
                'nama' => $data['new_pelanggan_nama'],
                'kontak' => $data['new_pelanggan_kontak'],
                'alamat' => $data['new_pelanggan_alamat'],
                'createdAt' => now(),
            ]);

            $data['pelanggan_id'] = $pelanggan->id;
        }

        unset(
            $data['create_new_pelanggan'],
            $data['new_pelanggan_nama'],
            $data['new_pelanggan_kontak'],
            $data['new_pelanggan_alamat'],
        );

        return $data;
    }
}

<?php

namespace App\Filament\Resources\Jasas\Pages;

use App\Filament\Resources\Jasas\JasaResource;
use App\Models\Jasa;
use Filament\Actions\Action;
use Filament\Resources\Pages\Page;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;

class ListCancelledJasas extends Page implements HasTable
{
    use InteractsWithTable;

    protected static string $resource = JasaResource::class;

    protected static ?string $title = 'Jasa Dibatalkan';

    protected static string|\BackedEnum|null $navigationIcon = 'heroicon-o-x-circle';

    protected static ?string $navigationLabel = 'Dibatalkan';

    protected string $view = 'filament.resources.jasas.pages.list-cancelled-jasas';

    public static function getNavigationGroup(): ?string
    {
        return 'Jasa & Layanan';
    }

    public static function canAccess(array $parameters = []): bool
    {
        $user = Auth::user();
        return $user && in_array($user->role, ['administrator', 'admin_toko', 'kepala_lapangan', 'superadmin'], true);
    }

    protected function getHeaderActions(): array
    {
        return [
            Action::make('back')
                ->label('Kembali')
                ->icon('heroicon-o-arrow-left')
                ->color('gray')
                ->url(JasaResource::getUrl('index')),
        ];
    }

    public function table(Table $table): Table
    {
        return $table
            ->query(
                Jasa::query()
                    ->where('status', 'batal')
                    ->when(Auth::user()?->branch, fn (Builder $q) => $q->where('branch', Auth::user()->branch))
            )
            ->columns([
                TextColumn::make('no_jasa')
                    ->label('No. Jasa')
                    ->sortable()
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('no_ref')
                    ->label('No. Ref')
                    ->sortable()
                    ->searchable(),
                TextColumn::make('pelanggan.nama')
                    ->label('Customer')
                    ->sortable()
                    ->searchable(),
                TextColumn::make('branch')
                    ->label('Branch')
                    ->sortable()
                    ->placeholder('-'),
                TextColumn::make('cancelled_reason')
                    ->label('Alasan Pembatalan')
                    ->limit(50)
                    ->tooltip(fn ($record) => $record->cancelled_reason),
                TextColumn::make('cancelled_at')
                    ->label('Tanggal Batal')
                    ->dateTime('d/m/Y H:i')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('cancelledByUser.name')
                    ->label('Dibatalkan Oleh')
                    ->placeholder('-'),
            ])
            ->actions([
                Action::make('restore')
                    ->label('Restore')
                    ->icon('heroicon-o-arrow-uturn-left')
                    ->color('success')
                    ->requiresConfirmation()
                    ->modalDescription('Apakah Anda yakin ingin mengembalikan jasa ini ke status "Jasa Baru"?')
                    ->action(function (Jasa $record) {
                        $record->update([
                            'status' => 'jasa baru',
                            'cancelled_reason' => null,
                            'cancelled_at' => null,
                            'cancelled_by' => null,
                        ]);
                    })
                    ->successNotificationTitle('Jasa berhasil dikembalikan ke status "Jasa Baru"'),
            ])
            ->defaultSort('cancelled_at', 'desc');
    }
}

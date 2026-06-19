<?php

namespace App\Filament\Resources\Produksis\Pages;

use App\Filament\Resources\Produksis\ProduksiResource;
use App\Models\Produksi;
use Filament\Actions\Action;
use Filament\Resources\Pages\Page;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;

class ListCancelledProduksis extends Page implements HasTable
{
    use InteractsWithTable;

    protected static string $resource = ProduksiResource::class;

    protected static ?string $title = 'Produksi Dibatalkan';

    protected static string|\BackedEnum|null $navigationIcon = 'heroicon-o-x-circle';

    protected static ?string $navigationLabel = 'Dibatalkan';

    protected string $view = 'filament.resources.produksis.pages.list-cancelled-produksis';

    public static function getNavigationGroup(): ?string
    {
        return 'StepNosing / Plint';
    }

    public static function canAccess(array $parameters = []): bool
    {
        $user = Auth::user();
        return $user && in_array($user->role, ['administrator', 'admin_toko', 'superadmin'], true);
    }

    protected function getHeaderActions(): array
    {
        return [
            Action::make('back')
                ->label('Kembali')
                ->icon('heroicon-o-arrow-left')
                ->color('gray')
                ->url(ProduksiResource::getUrl('index')),
        ];
    }

    public function table(Table $table): Table
    {
        return $table
            ->query(
                Produksi::query()
                    ->where('status', 'batal')
                    ->when(Auth::user()?->branch, fn (Builder $q) => $q->where('branch', Auth::user()->branch))
            )
            ->columns([
                TextColumn::make('no_produksi')
                    ->label('No. Produksi')
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
                    ->searchable()
                    ->placeholder('-'),
                TextColumn::make('branch')
                    ->label('Branch')
                    ->sortable()
                    ->placeholder('-'),
                TextColumn::make('team.nama')
                    ->label('Team')
                    ->sortable()
                    ->placeholder('-')
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('cancelled_reason')
                    ->label('Alasan Pembatalan')
                    ->limit(50)
                    ->tooltip(fn ($record) => $record->cancelled_reason),
                TextColumn::make('cancelled_at')
                    ->label('Tanggal Batal')
                    ->dateTime('d/m/Y H:i')
                    ->sortable(),
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
                    ->modalDescription('Apakah Anda yakin ingin mengembalikan produksi ini ke status "Baru"?')
                    ->action(function (Produksi $record) {
                        $record->update([
                            'status' => 'baru',
                            'cancelled_reason' => null,
                            'cancelled_at' => null,
                            'cancelled_by' => null,
                        ]);
                    })
                    ->successNotificationTitle('Produksi berhasil dikembalikan ke status "Baru"'),
            ])
            ->defaultSort('cancelled_at', 'desc');
    }
}

<?php

namespace App\Filament\Resources\Teams\Pages;

use App\Filament\Resources\Teams\TeamResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ManageRecords;
use Filament\Notifications\Notification;

class ListTeams extends ManageRecords
{
    protected static string $resource = TeamResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }

    // public function afterCreate(): void
    // {
    //     Notification::make()
    //         ->title('Success')
    //         ->body('Data Team berhasil dibuat.')
    //         ->success()
    //         ->icon('heroicon-o-check-circle')
    //         ->iconColor('success')
    //         ->send();
    // }
    // public function afterUpdate(): void
    // {
    //     Notification::make()
    //         ->title('Berhasil')
    //         ->body('Data Team berhasil diperbarui.')
    //         ->success()
    //         ->icon('heroicon-o-check-circle')
    //         ->iconColor('success')
    //         ->send();
    // }

    // public function afterDelete(): void
    // {
    //     Notification::make()
    //         ->title('Berhasil')
    //         ->body('Data Team berhasil dihapus.')
    //         ->danger()
    //         ->icon('heroicon-o-trash')
    //         ->iconColor('danger')
    //         ->send();
    // }
}

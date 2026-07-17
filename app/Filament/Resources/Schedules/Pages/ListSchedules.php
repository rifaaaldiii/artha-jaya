<?php

namespace App\Filament\Resources\Schedules\Pages;

use App\Filament\Resources\Schedules\ScheduleResource;
use Filament\Actions\Action;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ManageRecords;
use Filament\Support\Icons\Heroicon;

class ListSchedules extends ManageRecords
{
    protected static string $resource = ScheduleResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Action::make('lihat_jadwal_publik')
                ->label('Lihat Jadwal')
                ->icon(Heroicon::OutlinedArrowTopRightOnSquare)
                ->url('/schedule', shouldOpenInNewTab: true)
                ->color('gray'),
            CreateAction::make(),
        ];
    }
}

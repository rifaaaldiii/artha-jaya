<?php

namespace App\Filament\Resources\Schedules;

use App\Filament\Resources\Schedules\Pages\ListSchedules;
use App\Filament\Resources\Schedules\Schemas\ScheduleForm;
use App\Filament\Resources\Schedules\Tables\SchedulesTable;
use App\Models\Schedule;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class ScheduleResource extends Resource
{
    protected static ?string $model = Schedule::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedCalendarDays;

    public static function getNavigationLabel(): string
    {
        return 'Schedule';
    }

    public static function getLabel(): ?string
    {
        return 'Schedule';
    }

    public static function getPluralLabel(): ?string
    {
        return 'Tabel Schedule';
    }

    public static function form(Schema $schema): Schema
    {
        return ScheduleForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return SchedulesTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListSchedules::route('/'),
        ];
    }

    public static function getNavigationGroup(): ?string
    {
        return 'System';
    }

    public static function shouldRegisterNavigation(): bool
    {
        $user = Auth::user();

        return in_array($user->role, ['administrator', 'superadmin'], true);
    }

    public static function canCreate(): bool
    {
        $user = Auth::user();

        return in_array($user->role, ['administrator', 'superadmin'], true);
    }

    public static function canEdit(Model $record): bool
    {
        $user = Auth::user();

        return in_array($user->role, ['administrator', 'superadmin'], true);
    }

    public static function canDelete(Model $record): bool
    {
        $user = Auth::user();

        return in_array($user->role, ['administrator', 'superadmin'], true);
    }

    protected static ?int $navigationSort = 5;
}

<?php

namespace App\Filament\Resources\Users\Tables;

use App\Models\User;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Support\Facades\Auth;

class UsersTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->query(function () {
                $query = User::query();
                $user = Auth::user();
                if ($user && $user->role !== 'administrator') {
                    $query->where('role', '!=', 'administrator');
                }

                return $query;
            })
            ->columns([
                TextColumn::make("name")->label('Nama')->sortable()->searchable(),
                TextColumn::make("username")->label('Username')->sortable()->searchable(),
                TextColumn::make("email")->label('Email')->sortable()->searchable(),
                TextColumn::make("role")->label('Role')->sortable()->searchable(),
            ])
            ->filters([
                //
            ])
            ->actions([
                EditAction::make(),
                DeleteAction::make(),
            ])
            ->bulkActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}

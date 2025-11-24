<?php

namespace App\Filament\Pages\Auth;

use Filament\Auth\Pages\Register as BaseRegister;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Component;
use Filament\Schemas\Schema;

class Register extends BaseRegister
{
    public function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                $this->getNameFormComponent(),
                $this->getUsernameFormComponent(),
                $this->getEmailFormComponent(),
                $this->getPasswordFormComponent(),
                $this->getPasswordConfirmationFormComponent(),
            ]);
    }

    protected function getUsernameFormComponent(): Component
    {
        return TextInput::make('username')
            ->label('Username')
            ->required()
            ->maxLength(255)
            ->unique($this->getUserModel(), 'username')
            ->autocomplete('username');
    }

    protected function mutateFormDataBeforeRegister(array $data): array
    {
        // Set default role if not provided
        if (!isset($data['role'])) {
            $data['role'] = 'petugas'; // Default role
        }
        
        return $data;
    }
}


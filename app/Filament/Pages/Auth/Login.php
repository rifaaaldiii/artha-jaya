<?php

namespace App\Filament\Pages\Auth;

use Filament\Auth\Pages\Login as BaseLogin;
use Filament\Forms\Components\TextInput;

class Login extends BaseLogin
{
    protected function getEmailFormComponent(): TextInput
    {
        return TextInput::make('email')
            ->label('Username / Email')
            ->required()
            ->autocomplete()
            ->autofocus()
            ->extraInputAttributes(['tabindex' => 1]);
    }

    protected function getCredentialsFromFormData(array $data): array
    {
        // Determine if the input is email or username
        $fieldType = filter_var($data['email'], FILTER_VALIDATE_EMAIL) ? 'email' : 'username';
        
        return [
            $fieldType => $data['email'],
            'password' => $data['password'],
        ];
    }
}


<?php

namespace App\Filament\Pages;

use App\Models\User;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Select;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Schema;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Notifications\Notification;
use Filament\Pages\Page;
use Filament\Support\Icons\Heroicon;
use Filament\Support\Exceptions\Halt;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Livewire\Features\SupportFileUploads\WithFileUploads;
use BackedEnum;

class EditProfile extends Page implements HasForms
{
    use InteractsWithForms;
    use WithFileUploads;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedPencilSquare;

    protected static bool $shouldRegisterNavigation = false;

    public ?array $data = [];
    
    public ?User $user = null;

    public function mount(): void
    {
        $this->user = Auth::user();
        $this->schema->fill($this->user->attributesToArray());
    }

    public function schema(Schema $schema): Schema
    {
        return $schema
            ->schema([
                Grid::make(2)
                ->schema([
                    // Profile Photo Section - Full Width
                    Section::make('Profile Photo')
                        ->icon(Heroicon::OutlinedCamera)
                        ->description('Upload and manage your profile picture')
                        ->collapsed(fn () => filled($this->user->image))
                        ->schema([
                            FileUpload::make('image')
                                ->label('Profile Photo')
                                ->image()
                                ->disk('public_html_profile_images')
                                ->directory('users')
                                ->visibility('public')
                                ->maxSize(5120)
                                ->acceptedFileTypes(['image/jpeg', 'image/png', 'image/jpg', 'image/webp'])
                                ->imageEditor()
                                ->imageEditorAspectRatios([
                                    '1:1' => 'Square (1:1)',
                                    '4:3' => 'Standard (4:3)',
                                    '16:9' => 'Widescreen (16:9)',
                                    null => 'Free',
                                ])
                                ->circleCropper()
                                ->helperText('Upload a profile image. Max size: 5MB. Supported formats: JPG, PNG, WEBP')
                                ->downloadable()
                                ->openable()
                                ->previewable()
                                ->columnSpanFull(),
                        ]),
    
                    // Grid Layout for Other Sections
                    Section::make('Personal Information')
                        ->icon(Heroicon::OutlinedUser)
                        ->description('Your basic personal details')
                        ->schema([
                            TextInput::make('name')
                                ->label('Full Name')
                                ->required()
                                ->maxLength(255)
                                ->placeholder('Enter your full name'),
    
                            TextInput::make('username')
                                ->label('Username')
                                ->required()
                                ->maxLength(255)
                                ->unique('users', 'username', ignorable: $this->user)
                                ->regex('/^[a-zA-Z0-9_]+$/')
                                ->helperText('Only letters, numbers, and underscores allowed')
                                ->placeholder('username_example'),
    
                            TextInput::make('email')
                                ->label('Email Address')
                                ->email()
                                ->required()
                                ->maxLength(255)
                                ->unique('users', 'email', ignorable: $this->user)
                                ->placeholder('your.email@example.com'),
    
                            TextInput::make('kontak')
                                ->label('Contact Number')
                                ->tel()
                                ->placeholder('08123456789')
                                ->maxLength(20)
                                ->nullable()
                                ->helperText('Format: 08xxxxxxxxxx'),
                        ]),
    
                    // Right Column: Account & Security
                ]),

                Section::make('Account Settings')
                    ->icon(Heroicon::OutlinedCog)
                    ->description('Manage your account preferences')
                    ->schema([
                        Select::make('branch')
                            ->label('Branch Assignment')
                            ->options([
                                'AJP' => 'AJP',
                                'AJC' => 'AJC',
                                'AJR' => 'AJR',
                                'AJK' => 'AJK',
                            ])
                            ->searchable()
                            ->preload()
                            ->nullable()
                            ->native(false)
                            ->placeholder('Select your branch')
                            ->helperText('Select your assigned branch location'),
                    ]),

                // Security Section
                Section::make('Security')
                    ->icon(Heroicon::OutlinedLockClosed)
                    ->description('Update your password to keep your account secure')
                    ->schema([
                        TextInput::make('current_password')
                            ->label('Current Password')
                            ->password()
                            ->revealable()
                            ->nullable()
                            ->helperText('Required only when changing your password'),
                        
                        Grid::make(2)
                            ->schema([
                                TextInput::make('new_password')
                                    ->label('New Password')
                                    ->password()
                                    ->revealable()
                                    ->nullable()
                                    ->minLength(8)
                                    ->same('new_password_confirmation')
                                    ->helperText('Minimum 8 characters')
                                    ->placeholder('••••••••'),
        
                                TextInput::make('new_password_confirmation')
                                    ->label('Confirm New Password')
                                    ->password()
                                    ->revealable()
                                    ->nullable()
                                    ->minLength(8)
                                    ->placeholder('••••••••'),
                            ]),
                    ]),
            ])
            ->statePath('data')
            ->model($this->user);
    }

    protected function getSchemaActions(): array
    {
        return [
            \Filament\Schemas\Components\Actions\Action::make('save')
                ->label('Save Changes')
                ->submit('save')
                ->color('primary'),
        ];
    }

    public function save(): void
    {
        try {
            $data = $this->schema->getState();

            // Validate current password if trying to change password
            if (filled($data['new_password'] ?? null)) {
                if (!filled($data['current_password'] ?? null)) {
                    Notification::make()
                        ->title('Current Password Required')
                        ->body('Enter your current password to change password')
                        ->danger()
                        ->send();
                    
                    $this->dispatch('focus-field', field: 'data[current_password]');
                    return;
                }
                
                if (!Hash::check($data['current_password'], $this->user->password)) {
                    Notification::make()
                        ->title('Failed! Current password is incorrect')
                        ->danger()
                        ->send();
                    
                    $this->dispatch('focus-field', field: 'data[current_password]');
                    return;
                }
            }

            // Prepare update data
            $updateData = [
                'name' => $data['name'],
                'username' => $data['username'],
                'email' => $data['email'],
                'kontak' => $data['kontak'] ?? null,
                'branch' => $data['branch'] ?? null,
            ];

            // Update image if provided
            if (isset($data['image']) && $data['image']) {
                $updateData['image'] = $data['image'];
            }

            // Update password if provided
            if (filled($data['new_password'] ?? null)) {
                $updateData['password'] = Hash::make($data['new_password']);
            }

            // Update user
            $this->user->update($updateData);

            Notification::make()
                ->title('Profile updated successfully')
                ->success()
                ->send();

            $this->redirectRoute('filament.admin.pages.profile');
        } catch (Halt $exception) {
            return;
        }
    }

    public function getTitle(): string
    {
        return 'Edit Profile';
    }

    public static function getNavigationGroup(): ?string
    {
        return 'System';
    }

    public function getView(): string
    {
        return 'filament.pages.edit-profile';
    }
}
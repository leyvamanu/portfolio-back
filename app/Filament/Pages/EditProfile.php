<?php

namespace App\Filament\Pages;

use App\Models\User;
use Filament\Forms;
use Filament\Pages\Page;
use Filament\Forms\Contracts\HasForms;
use Filament\Forms\Concerns\InteractsWithForms;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Filament\Notifications\Notification;
use Filament\Pages\Auth\EditProfile as BaseEditProfile;

class EditProfile extends Page implements HasForms
{
    use InteractsWithForms;

    protected static ?string $navigationIcon = 'heroicon-o-user-circle';

    protected static string $view = 'filament.pages.edit-profile';

    public ?array $data = [];

    public function mount(): void
    {
        $user = Auth::user();

        $this->data = [
            'name' => $user->name,
            'email' => $user->email,
        ];

        $this->form->fill($this->data);
    }

    protected function getFormSchema(): array
    {
        return [
            Forms\Components\TextInput::make('name')
                ->label('Nombre')
                ->required()
                ->statePath('data.name'),

            Forms\Components\TextInput::make('email')
                ->label('Email')
                ->email()
                ->required()
                ->statePath('data.email'),

            Forms\Components\TextInput::make('password')
                ->label('Nueva contraseña')
                ->password()
                ->rule('confirmed')
                ->validationMessages([
                    'confirmed' => 'Las contraseñas no coinciden.',
                ])
                ->minLength(8)
                ->nullable()
                ->statePath('data.password'),
        ];
    }

    public function submit(): void
    {
        $user = Auth::user();
        $data = $this->data;

        $user->name = $data['name'];
        $user->email = $data['email'];

        if (!empty($data['password'])) {
            $user->password = Hash::make($data['password']);
        }

        $user->save();

        Notification::make()
            ->title('Perfil actualizado correctamente')
            ->success()
            ->send();
    }

    public static function shouldRegisterNavigation(): bool
    {
        return false;
    }
}

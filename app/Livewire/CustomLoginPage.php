<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\User; // Your User model in "arsip"
use App\Providers\AuthApiService;
use Filament\Actions\Action;
use Filament\Facades\Filament;
use Filament\Forms\Components\Checkbox;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Http\Responses\Auth\Contracts\LoginResponse;
use Filament\Notifications\Notification;
use Filament\Pages\Auth\Login as BaseLogin; // Use Filament's base Login for structure
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash; // Only if you need to set a dummy hash
use Illuminate\Validation\ValidationException;

class CustomLoginPage extends BaseLogin
{
    protected static string $view = 'filament.pages.auth.login'; // You can use Filament's default view or create your own

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                $this->getEmailFormComponent(),
                $this->getPasswordFormComponent(),
                $this->getRememberFormComponent(),
            ])
            ->statePath('data');
    }

    // Override the authenticate method
    public function authenticate(): ?LoginResponse
    {
        $data = $this->form->getState();
        $authApiService = app(AuthApiService::class);

        // 1. Attempt login via API
        $apiUserData = $authApiService->attemptLogin([
            'email' => $data['email'],
            'password' => $data['password'],
        ]);

        if (!$apiUserData) {
            // API authentication failed
            throw ValidationException::withMessages([
                'data.email' => __('filament-panels::pages/auth/login.messages.failed'),
            ]);
        }

        // 2. API authentication successful, find or create user in "arsip"
        //    Adjust field names ('name', 'email_verified_at') based on your API response
        //    and your "arsip" User model.
        $user = User::updateOrCreate(
            ['email' => $apiUserData['email']],
            [
                'name' => $apiUserData['name'] ?? 'User from API', // Get name from API if available
                // IMPORTANT: Do NOT store the password from the API.
                // You might want to set a very long random password hash
                // or ensure your local login guards never try to use it.
                'password' => Hash::make(str()->random(60)), // Set a dummy, unusable password
                'email_verified_at' => $apiUserData['email_verified_at'] ?? now(), // If API provides this
                // Add any other fields you want to sync from the API user data
            ]
        );

        // 3. Log the user into "arsip"
        Auth::login($user, $data['remember'] ?? false);

        // 4. Regenerate session and dispatch Filament's LoginResponse
        session()->regenerate();

        return app(LoginResponse::class);
    }
}

<?php

namespace App\Filament\Pages\Auth;

use Filament\Pages\Auth\Login as BaseLogin;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use Filament\Facades\Filament;

class Login extends BaseLogin
{

    public function authenticate(): ?\Filament\Http\Responses\Auth\Contracts\LoginResponse
    {

        $this->validate();
        $response = Http::post('http://summary4.test/api/login', [
            'email' => $this->form->getState()['email'],
            'password' => $this->form->getState()['password'],
        ]);

        if ($response->failed()) {
            $this->addError('email', 'The provided credentials are invalid.');
            return null;
        }

        $data = $response->json();

        $user = User::updateOrCreate(
            ['email' => $data['user']['email']],
            ['name' => $data['user']['name']]
        );

        Auth::login($user);
//        dd(Auth::check(), Auth::user());
        session(['summary_token' => $data['token']]);

        redirect()->intended(Filament::getUrl());
//        return app(\Filament\Http\Responses\Auth\Contracts\LoginResponse::class);
    }
}

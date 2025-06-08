<?php

use App\Http\Controllers\Auth\RemoteLoginController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/remote-login', function () {
    return view('remote-login');
});

Route::get('/login', [RemoteLoginController::class, 'showLoginForm'])->name('login')->middleware('guest');
Route::post('/login', [RemoteLoginController::class, 'login'])->middleware('guest');
Route::post('/logout', [RemoteLoginController::class, 'logout'])->name('logout')->middleware('auth');


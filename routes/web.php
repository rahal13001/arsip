<?php

use App\Http\Controllers\Auth\RemoteLoginController;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
//
//Route::get('/', function () {
//    return view('welcome');
//});
Route::get('/', function () {
    return Inertia::render('Home', [ // <-- 'Home' matches Home.vue
        'laravelVersion' => 12,
        'phpVersion' => 8.3,
    ]);
});

Route::get('/remote-login', function () {
    return view('remote-login');
});

Route::get('/login', [RemoteLoginController::class, 'showLoginForm'])->name('login')->middleware('guest');
Route::post('/login', [RemoteLoginController::class, 'login'])->middleware('guest');
Route::post('/logout', [RemoteLoginController::class, 'logout'])->name('logout')->middleware('auth');


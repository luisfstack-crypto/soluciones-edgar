<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Artisan;

Route::get('/', function () {
    if (auth()->check()) {
        return auth()->user()->is_admin ? redirect('/admin') : redirect('/app');
    }
    return redirect('/app/login');
});

Route::get('/login', function () {
    return redirect('/app/login');
})->name('login'); 

Route::get('/support/whatsapp', \App\Http\Controllers\SupportRedirectController::class)
    ->middleware('auth')
    ->name('support.whatsapp');

Route::view('dashboard', 'dashboard')
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

Route::view('profile', 'profile')
    ->middleware(['auth'])
    ->name('profile');

require __DIR__.'/auth.php';

Route::get('/dev/reset-database', function () {
    try {
        Artisan::call('migrate:fresh', [
            '--seed' => true,
            '--force' => true
        ]);
        return '¡Base de datos reiniciada y poblada con éxito (FORZADO)!';
    } catch (\Exception $e) {
        return 'Error al reiniciar: ' . $e->getMessage();
    }
});
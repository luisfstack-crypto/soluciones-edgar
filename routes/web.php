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

Route::get('/app/orders/{order}/download', function (\App\Models\Order $order) {
    if ($order->user_id !== auth()->id() && !auth()->user()->is_admin) {
        abort(403);
    }
    
    if (!$order->result_file_path) {
        abort(404);
    }

    return \Illuminate\Support\Facades\Storage::download(
        $order->result_file_path, 
        'Resultado_' . $order->id . '.pdf'
    );
})->middleware(['auth'])->name('orders.download');

require __DIR__.'/auth.php';

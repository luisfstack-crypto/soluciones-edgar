<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Artisan;
use App\Http\Controllers\DocMxWebhookController;

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

Route::post('/webhook/docmx', [DocMxWebhookController::class, 'handle'])->name('webhook.docmx');

Route::get('/app/orders/{order}/download', function (string $order) {
    $order = \App\Models\Order::findOrFail($order);

    if (!$order->result_file_path) {
        abort(404, 'No file path found in database.');
    }

    // 1. Handle external URLs gracefully
    if (filter_var($order->result_file_path, FILTER_VALIDATE_URL)) {
        return redirect($order->result_file_path);
    }

    try {
        // 2. Try fetching from Cloudflare R2 (S3) first
        if (\Illuminate\Support\Facades\Storage::disk('s3')->exists($order->result_file_path)) {
            return \Illuminate\Support\Facades\Storage::disk('s3')->download(
                $order->result_file_path,
                'Result_' . $order->id . '.pdf'
            );
        }

        // 3. Fallback for old orders stored in the local disk
        if (\Illuminate\Support\Facades\Storage::disk('public')->exists($order->result_file_path)) {
            return \Illuminate\Support\Facades\Storage::disk('public')->download(
                $order->result_file_path,
                'Result_' . $order->id . '.pdf'
            );
        }
    } catch (\Symfony\Component\HttpKernel\Exception\HttpExceptionInterface $exception) {
        throw $exception;
    } catch (\Throwable $exception) {
        \Illuminate\Support\Facades\Log::error('Failed to retrieve order document.', [
            'order_id' => $order->id,
            'path' => $order->result_file_path,
            'exception' => $exception,
        ]);

        abort(503, 'Document storage is temporarily unavailable.');
    }

    abort(404, 'File not found on the server.');
})->middleware(['signed'])->name('orders.download');

require __DIR__.'/auth.php';

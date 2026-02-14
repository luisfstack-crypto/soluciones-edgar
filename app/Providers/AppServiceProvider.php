<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->singleton(
            \Filament\Http\Responses\Auth\Contracts\LoginResponse::class,
            \App\Http\Responses\LoginResponse::class
        );
        $this->app->singleton(
            \Filament\Http\Responses\Auth\Contracts\LogoutResponse::class,
            \App\Http\Responses\LogoutResponse::class
        );
        $this->app->singleton(
            \Filament\Http\Responses\Auth\Contracts\RegistrationResponse::class,
            \App\Http\Responses\RegistrationResponse::class
        );
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        \Illuminate\Database\Eloquent\Model::shouldBeStrict();
        \App\Models\Order::observe(\App\Observers\OrderObserver::class);

        \Illuminate\Auth\Notifications\VerifyEmail::toMailUsing(function ($notifiable, $url) {
            \Illuminate\Support\Facades\Log::info('AppServiceProvider VerifyEmail callback triggered for: ' . $notifiable->email);
            
            return (new \Illuminate\Notifications\Messages\MailMessage)
                ->subject('Verifica tu correo electrónico - Soluciones Edgar')
                ->view('emails.auth.verify', ['url' => $url, 'user' => $notifiable]);
        });
    }
}

<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\MailMessage;
use Filament\Facades\Filament;

class ResetPasswordNotification extends Notification
{
    use Queueable;

    public $token;

    public function __construct($token)
    {
        $this->token = $token;
    }

    public function via($notifiable)
    {
        return ['mail'];
    }

    public function toMail($notifiable)
    {
        $routeName = $notifiable->is_admin 
            ? 'filament.admin.auth.password-reset.reset' 
            : 'filament.dashboard.auth.password-reset.reset';
            
        $url = route($routeName, [
            'token' => $this->token,
            'email' => $notifiable->getEmailForPasswordReset(),
        ]);

        \Illuminate\Support\Facades\Log::info('Enviando correo de restablecimiento a: ' . $notifiable->email . ' con URL: ' . $url);

        return (new MailMessage)
            ->subject('Restablecer Contraseña - Soluciones Edgar')
            ->view('emails.auth.reset', [
                'notifiable' => $notifiable,
                'url' => $url,
            ]);
    }
}

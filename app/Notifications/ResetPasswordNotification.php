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
        $panel = Filament::getCurrentPanel() ?? filament()->getPanel('dashboard');
        
        $url = $panel->getResetPasswordUrl($this->token, $notifiable);

        return (new MailMessage)
            ->subject('Restablecer Contraseña - Soluciones Edgar')
            ->greeting("Hola, {$notifiable->name}!")
            ->line('Recibiste este correo porque solicitaste restablecer tu contraseña.')
            ->action('Restablecer Contraseña', $url)
            ->line('Si no solicitaste este cambio, puedes ignorar este mensaje.')
            ->salutation('Saludos, Soluciones Edgar');
    }
}

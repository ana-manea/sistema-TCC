<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class SenhaAlteradaNotification extends Notification
{
    use Queueable;

    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject('Senha alterada no Sistema TCC')
            ->greeting('Olá, ' . $notifiable->name . '!')
            ->line('Sua senha foi alterada com sucesso.')
            ->line('Se você não realizou essa alteração, entre em contato com o administrador do sistema.')
            ->action('Acessar o sistema', route('login'));
    }
}

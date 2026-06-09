<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class ContaCriadaNotification extends Notification
{
    use Queueable;

    public function __construct(
        private readonly string $senhaTemporaria,
        private readonly string $funcao
    ) {
    }

    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject('Sua conta foi criada no Sistema TCC')
            ->greeting('Olá, ' . $notifiable->name . '!')
            ->line('Sua conta foi criada no Sistema TCC.')
            ->line('Função cadastrada: ' . ucfirst(str_replace('_', ' ', $this->funcao)))
            ->line('Senha inicial: ' . $this->senhaTemporaria)
            ->line('Por segurança, altere sua senha no primeiro acesso.')
            ->action('Acessar o sistema', route('login'));
    }
}

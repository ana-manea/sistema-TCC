<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class CustomResetPasswordNotification extends Notification
{
    use Queueable;

    public function __construct(private readonly string $token)
    {
    }

    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        $url = route('password.reset', [
            'token' => $this->token,
            'email' => $notifiable->getEmailForPasswordReset(),
        ]);

        return (new MailMessage)
            ->subject('Recuperação de senha - Sistema TCC')
            ->greeting('Olá, ' . $notifiable->name . '!')
            ->line('Recebemos uma solicitação para redefinir sua senha.')
            ->line('O link de recuperação expira em 60 minutos.')
            ->action('Redefinir senha', $url)
            ->line('Se você não solicitou a recuperação, ignore este e-mail.');
    }
}

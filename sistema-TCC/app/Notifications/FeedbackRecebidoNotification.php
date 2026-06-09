<?php

namespace App\Notifications;

use App\Models\Feedback;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class FeedbackRecebidoNotification extends Notification
{
    use Queueable;

    public function __construct(private readonly Feedback $feedback)
    {
    }

    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject('Novo feedback recebido - Sistema TCC')
            ->greeting('Olá, ' . $notifiable->name . '!')
            ->line('Você recebeu um novo feedback no TCC: ' . ($this->feedback->tcc?->tema ?? 'TCC #' . $this->feedback->tcc_id))
            ->line('Orientador: ' . ($this->feedback->orientador?->user?->name ?? '-'))
            ->action('Ver TCC', route('tccs.show', $this->feedback->tcc_id));
    }
}

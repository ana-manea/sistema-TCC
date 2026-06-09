<?php

namespace App\Notifications;

use App\Models\SolicitacaoOrientador;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class NovaSolicitacaoOrientadorNotification extends Notification
{
    use Queueable;

    public function __construct(private readonly SolicitacaoOrientador $solicitacao)
    {
    }

    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject('Nova solicitação de orientação - Sistema TCC')
            ->greeting('Olá, ' . $notifiable->name . '!')
            ->line('Você recebeu uma nova solicitação de orientação.')
            ->line('Aluno: ' . ($this->solicitacao->orientando?->user?->name ?? '-'))
            ->action('Ver solicitações', route('solicitacoes_orientador.index', $this->solicitacao->orientador_id));
    }
}

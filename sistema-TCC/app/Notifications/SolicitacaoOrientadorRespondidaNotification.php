<?php

namespace App\Notifications;

use App\Models\SolicitacaoOrientador;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class SolicitacaoOrientadorRespondidaNotification extends Notification
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
        $status = $this->solicitacao->status === 'aceita' ? 'aceita' : 'recusada';

        return (new MailMessage)
            ->subject('Solicitação de orientação ' . $status . ' - Sistema TCC')
            ->greeting('Olá, ' . $notifiable->name . '!')
            ->line('Sua solicitação de orientação foi ' . $status . '.')
            ->line('Orientador: ' . ($this->solicitacao->orientador?->user?->name ?? '-'))
            ->line('Resposta: ' . ($this->solicitacao->resposta ?: '-'))
            ->action('Ver solicitações', route('solicitacoes_orientando.index'));
    }
}

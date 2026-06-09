<?php

namespace App\Notifications;

use App\Models\Banca;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class BancaAgendadaNotification extends Notification
{
    use Queueable;

    public function __construct(private readonly Banca $banca)
    {
    }

    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        $dataHora = optional($this->banca->data_hora)->format('d/m/Y H:i') ?? $this->banca->data_hora;

        return (new MailMessage)
            ->subject('Banca agendada - Sistema TCC')
            ->greeting('Olá, ' . $notifiable->name . '!')
            ->line('Uma banca foi agendada para o TCC: ' . ($this->banca->tcc?->tema ?? 'TCC #' . $this->banca->tcc_id))
            ->line('Data/Hora: ' . ($dataHora ?: '-'))
            ->line('Local/Link: ' . ($this->banca->local ?: '-'))
            ->action('Ver banca', route('bancas.show', $this->banca));
    }
}

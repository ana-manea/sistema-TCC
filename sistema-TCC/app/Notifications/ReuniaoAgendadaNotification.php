<?php

namespace App\Notifications;

use App\Models\Reuniao;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class ReuniaoAgendadaNotification extends Notification
{
    use Queueable;

    public function __construct(private readonly Reuniao $reuniao)
    {
    }

    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        $dataHora = optional($this->reuniao->data_hora)->format('d/m/Y H:i') ?? $this->reuniao->data_hora;

        return (new MailMessage)
            ->subject('Reunião agendada - Sistema TCC')
            ->greeting('Olá, ' . $notifiable->name . '!')
            ->line('Uma reunião foi agendada para o TCC: ' . ($this->reuniao->tcc?->tema ?? 'TCC #' . $this->reuniao->tcc_id))
            ->line('Data/Hora: ' . ($dataHora ?: '-'))
            ->line('Local/Link: ' . ($this->reuniao->local ?: '-'))
            ->action('Ver reuniões', route('reunioes.index'));
    }
}

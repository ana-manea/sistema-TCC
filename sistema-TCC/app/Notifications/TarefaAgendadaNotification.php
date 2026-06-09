<?php

namespace App\Notifications;

use App\Models\Tarefa;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class TarefaAgendadaNotification extends Notification
{
    use Queueable;

    public function __construct(private readonly Tarefa $tarefa)
    {
    }

    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        $prazo = optional($this->tarefa->prazo)->format('d/m/Y') ?? $this->tarefa->prazo;

        return (new MailMessage)
            ->subject('Nova tarefa atribuída - Sistema TCC')
            ->greeting('Olá, ' . $notifiable->name . '!')
            ->line('Uma nova tarefa foi atribuída ao seu TCC.')
            ->line('Tarefa: ' . $this->tarefa->titulo)
            ->line('Prazo: ' . ($prazo ?: '-'))
            ->action('Ver tarefas', route('aluno.tarefas.index'));
    }
}

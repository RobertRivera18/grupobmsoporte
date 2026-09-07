<?php

namespace App\Notifications;

use App\Models\Ticket;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Support\HtmlString;

class TicketCreado extends Notification
{
    use Queueable;
    public $ticket;

    /**
     * Create a new notification instance.
     */
    public function __construct(Ticket $ticket)
    {
        $this->ticket = $ticket;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['database', 'mail'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject('Nuevo Ticket Creado')
            ->markdown('mail.ticket-creado', [
                'notifiable' => $notifiable,
                'ticket' => $this->ticket,
                'usuario' => $this->ticket->user,
                'descripcion_html' => new HtmlString($this->ticket->tick_descrip),
            ]);
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toDatabase(object $notifiable): array
    {
        $notifiable->notification += 1;
        $notifiable->save();

        return [
            'tick_id' => $this->ticket->tick_id,
            'titulo' => $this->ticket->tick_titulo,
            'descripcion' => $this->ticket->tick_descrip,
            'usuario' => $this->ticket->user->name,
            'usuario_id' => $this->ticket->user->id,
            'ruta' => route('admin.tickets.edit', $this->ticket->tick_id),
            'message' => $this->ticket->user->name . ' ha creado un nuevo ticket de soporte.',
        ];
    }
}

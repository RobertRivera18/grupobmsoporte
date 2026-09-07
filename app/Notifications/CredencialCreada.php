<?php

namespace App\Notifications;

use App\Models\Actas;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class CredencialCreada extends Notification
{
    use Queueable;

    public $acta;

    public function __construct(Actas $acta)
    {
        $this->acta = $acta;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {

        return ['database'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject('Nueva solicitud de credencial')
            ->line('Se ha generado una nueva solicitud de credencial.')
            ->line('Usuario: ' . $this->acta->user->name)
            ->action('Ver solicitud', route('admin.credenciales.edit', $this->acta->id))
            ->line('Gracias.');
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            //
        ];
    }

    public function toDatabase(object $notifiable): array
    {

        $notifiable->increment('notification');

        return [
            'acta_id' => $this->acta->id,
            'titulo' => 'Solicitud de credencial',
            'descripcion' => 'Nueva solicitud generada',
            'usuario' => $this->acta->user->name,
            'usuario_id' => $this->acta->user->id,
            'empresa' => $this->acta->empresa,
            'tipo_contrato_id' => $this->acta->tipo_contrato_id,
            'fecha_entrega' => $this->acta->fecha_entrega,
            'ruta' => route('admin.credenciales.index'),

            
            'message' => auth()->user()->name .
                ' generó una nueva solicitud de credencial para ' .
                $this->acta->user->name . '.',
        ];
    }
}

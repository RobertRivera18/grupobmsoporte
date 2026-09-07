<?php

namespace App\Notifications;

use App\Models\SolicitudDesvinculacion;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class SolicitudDesvinculacionCreada extends Notification
{
    use Queueable;

    public $solicitud;

    public function __construct(SolicitudDesvinculacion $solicitud)
    {
        $this->solicitud = $solicitud;
    }

    public function via(object $notifiable): array
    {
        return ['database'];
    }

    public function toDatabase(object $notifiable): array
    {
        $notifiable->increment('notification');
        return [
            'solicitud_id' => $this->solicitud->id,
            'titulo'       => 'Solicitud de desvinculación',
            'descripcion'  => 'Nueva solicitud de desvinculación generada',
            'usuario'      => $this->solicitud->user?->name,
            'usuario_id'   => $this->solicitud->user?->id,
            'etapa'        => $this->solicitud->etapa,
            'ruta'         => route('admin.desvinculacion.index'),
            'message'      => auth()->user()->name . 
                              ' generó una nueva solicitud de desvinculación para ' . 
                              ($this->solicitud->user?->name ?? 'un colaborador') . '.',
        ];
    }
}
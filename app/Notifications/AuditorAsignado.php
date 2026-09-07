<?php

namespace App\Notifications;

use App\Models\AuditoriaProceso;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class AuditorAsignado extends Notification
{
    use Queueable;

    public $auditoriaProceso;
    public $rol;

    public function __construct(AuditoriaProceso $auditoriaProceso, string $rol = 'auditor')
    {
        $this->auditoriaProceso = $auditoriaProceso;
        $this->rol = $rol;
    }

    public function via(object $notifiable): array
    {
        return ['database', 'mail'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        $auditoria = $this->auditoriaProceso->auditoria;
        $area      = $this->auditoriaProceso->area;
        $auditor   = $this->auditoriaProceso->auditor;
        $normas = $this->auditoriaProceso->normas->map(function ($norma) {
            return "• {$norma->codigo} - {$norma->descripcion}";
        })->implode("\n"); 
        $codigosNormas = $this->auditoriaProceso->normas->pluck('codigo')->implode(', ');

        $fechas = "del {$auditoria->fecha_inicio->format('d/m/Y')} al {$auditoria->fecha_fin->format('d/m/Y')}";
        $descripcion = match ($this->rol) {
            'auditor' => "Ha sido asignado como auditor del área \"{$area->nombre}\" para evaluar los procesos: {$codigosNormas}, correspondientes a la auditoría {$auditoria->anio}, programada {$fechas}.",

            'responsable' => "El auditor asignado a su área \"{$area->nombre}\" es {$auditor->name}. Los procesos a evaluar son: {$codigosNormas}, correspondientes a la auditoría {$auditoria->anio}, programada {$fechas}.",

            default => "Ha sido incluido dentro del proceso de auditoría del área {$area->nombre}.",
        };

        $titulo = match ($this->rol) {
            'auditor' => '📋 Asignación como Auditor',
            'responsable' => '📋 Nuevo auditor asignado a tu área',
            default => '📋 Asignación de Auditoría',
        };

        return (new MailMessage)
            ->subject($titulo)
            ->markdown('mail.auditor-asignado', [
                'notifiable' => $notifiable,
                'titulo' => $titulo,
                'descripcion' => $descripcion,
                'area' => $area->nombre,
                'auditor' => $auditor->name,
                'normas' => $normas, 
                'fecha_inicio' => $auditoria->fecha_inicio->format('d/m/Y'),
                'fecha_fin' => $auditoria->fecha_fin->format('d/m/Y'),
                'url' => route('admin.auditorias.show', $auditoria),
            ]);
    }

    public function toDatabase(object $notifiable): array
    {
        $auditoria = $this->auditoriaProceso->auditoria;
        $area      = $this->auditoriaProceso->area;
        $auditor   = $this->auditoriaProceso->auditor;
        $normas    = $this->auditoriaProceso->normas->pluck('nombre')->implode(', ');

        $fechas = "del {$auditoria->fecha_inicio->format('d/m/Y')} al {$auditoria->fecha_fin->format('d/m/Y')}";

        $descripcion = match ($this->rol) {

            'auditor' => "Hola {$notifiable->name}, se te ha asignado como auditor del área " .
                "\"{$area->nombre}\" en los procesos: {$normas}, durante la auditoría " .
                "{$auditoria->anio} que se llevará a cabo los días {$fechas}.",

            'responsable' => "Hola {$notifiable->name}, el auditor asignado a tu área \"{$area->nombre}\" " .
                "es {$auditor->name}, en tus procesos asignados: {$normas}, durante la auditoría " .
                "{$auditoria->anio} que se llevará a cabo los días {$fechas}.",

            default => "Has sido incluido en el proceso de auditoría del área \"{$area->nombre}\".",
        };

        $titulo = match ($this->rol) {
            'auditor'      => 'Asignación como auditor',
            'responsable'  => 'Nuevo auditor asignado a tu área',
            default        => 'Asignación en auditoría',
        };

        $notifiable->increment('notification');

        return [
            'auditoria_proceso_id' => $this->auditoriaProceso->id,
            'auditoria_id'         => $auditoria->id,
            'area_id'              => $area->id,
            'area_nombre'          => $area->nombre,
            'auditor_nombre'       => $auditor->name,
            'anio'                 => $auditoria->anio,
            'fecha_inicio'         => $auditoria->fecha_inicio->format('d/m/Y'),
            'fecha_fin'            => $auditoria->fecha_fin->format('d/m/Y'),
            'normas'               => $normas,
            'rol'                  => $this->rol,
            'titulo'               => $titulo,
            'descripcion'          => $descripcion,
            'usuario'              => $notifiable->name,
            'usuario_id'           => $notifiable->id,
            'ruta' =>               route('admin.auditorias.show', $auditoria),
            'message'              => $descripcion,
        ];
    }
}

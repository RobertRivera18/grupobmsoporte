<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AuditoriaProceso;

class CalendarController extends Controller
{
    public function index()
    {
        $eventos = AuditoriaProceso::with(['auditoria', 'area', 'auditor', 'normas'])
            ->whereHas('auditoria')
            ->get()
            ->map(function ($proceso) {
                $estado = $proceso->auditoria->estado;

                $colores = [
                    'pendiente'  => '#4f46e5',
                    'en_curso'   => '#f59e0b',
                    'finalizada' => '#10b981',
                ];

                return [
                    'id'              => $proceso->id,
                    'title'           => $proceso->area->nombre . ' — ' . $proceso->auditor->name,
                    'start'           => $proceso->auditoria->fecha_inicio->format('Y-m-d'),
                    'end'             => $proceso->auditoria->fecha_fin->addDay()->format('Y-m-d'),
                    'backgroundColor' => $colores[$estado] ?? '#4f46e5',
                    'borderColor'     => $colores[$estado] ?? '#4f46e5',
                    'extendedProps'   => [
                        'auditoria'  => $proceso->auditoria->anio,
                        'area'       => $proceso->area->nombre,
                        'auditor'    => $proceso->auditor->name,
                        'normas'     => $proceso->normas->pluck('nombre')->implode(', '),
                        'estado'     => $estado,
                        'fecha_inicio' => $proceso->auditoria->fecha_inicio->format('d/m/Y'),
                        'fecha_fin'    => $proceso->auditoria->fecha_fin->format('d/m/Y'),
                    ],
                ];
            });

        return view('admin.calendar.index', compact('eventos'));
    }
}
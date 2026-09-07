<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\AuditoriaProceso;

class DashboardCalendar extends Component
{
    public $eventos = [];

    public function mount()
    {
        $this->eventos = AuditoriaProceso::with([
            'auditoria',
            'area',
            'auditor',
            'normas'
        ])
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
                'id' => $proceso->id,
                'title' => $proceso->area->nombre,
                'start' => $proceso->auditoria->fecha_inicio->format('Y-m-d'),
                'end' => $proceso->auditoria->fecha_fin->copy()->addDay()->format('Y-m-d'),
                'backgroundColor' => $colores[$estado] ?? '#4f46e5',
                'borderColor' => $colores[$estado] ?? '#4f46e5',
            ];
        })->values();
    }

    public function render()
    {
        return view('livewire.dashboard-calendar');
    }
}
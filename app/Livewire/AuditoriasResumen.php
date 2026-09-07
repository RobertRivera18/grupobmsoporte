<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Auditoria;
use App\Models\AuditoriaProceso;

class AuditoriasResumen extends Component
{
    public $totalAuditorias;
    public $planificadas;
    public $totalProcesos;
    public $totalAreas;
    public $proximaAuditoria;
    public $ultimasAuditorias;

    public function mount()
    {
        $this->totalAuditorias = Auditoria::count();

        $this->planificadas = Auditoria::where('estado', 'planificada')->count();

        $this->totalProcesos = AuditoriaProceso::count();

        $this->totalAreas = AuditoriaProceso::distinct('area_id')
            ->count('area_id');

        $this->proximaAuditoria = Auditoria::whereDate('fecha_inicio', '>=', now())
            ->orderBy('fecha_inicio')
            ->first();

        $this->ultimasAuditorias = Auditoria::withCount('procesos')
            ->latest()
            ->take(5)
            ->get();
    }

    public function render()
    {
        return view('livewire.auditorias-resumen');
    }
}
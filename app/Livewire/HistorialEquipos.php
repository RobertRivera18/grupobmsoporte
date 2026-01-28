<?php

namespace App\Livewire;

use App\Models\Equipos;
use App\Models\HistorialAsignacion;
use Livewire\Component;

class HistorialEquipos extends Component
{
    public $equipoId;
    public $equipo;
    public $historial = [];

    public function mount($equipoId)
    {
        $this->equipoId = $equipoId;

        $this->equipo = Equipos::find($equipoId);

        $this->historial = HistorialAsignacion::where('equipo_id', $equipoId)
            ->with('user')
            ->orderBy('fecha_asignacion', 'asc')
            ->get();
    }

    public function render()
    {
        return view('livewire.historial-equipos');
    }
}

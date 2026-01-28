<?php

namespace App\Livewire;

use App\Models\TipoEquipo;
use Livewire\Component;

class GraficaEquiposDisponibles extends Component
{

    public $labels = [];
    public $asignados = [];
    public $libres = [];

    public function mount()
    {
        $tipos = TipoEquipo::with('equipos')->get();

        foreach ($tipos as $tipo) {
            $this->labels[] = $tipo->nombre;

            // 🔹 Equipos asignados: tienen usuario o cuadrilla
            $asignados = $tipo->equipos()
                ->where(function ($query) {
                    $query->whereHas('users')       // relación many-to-many con usuarios
                          ->orWhereHas('cuadrilla'); // relación many-to-many con cuadrillas
                })
                ->count();

            // 🔹 Equipos libres: sin usuario ni cuadrilla
            $libres = $tipo->equipos()
                ->whereDoesntHave('users')
                ->whereDoesntHave('cuadrilla')
                ->count();

            $this->asignados[] = $asignados;
            $this->libres[] = $libres;
        }
    }

    public function render()
    {
        return view('livewire.grafica-equipos-disponibles');
    }
}

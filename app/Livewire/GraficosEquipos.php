<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\TipoEquipo;

class GraficosEquipos extends Component
{
    public $labels = [];
    public $values = [];

    public function mount()
    {
        // Obtener tipos de equipos con la cantidad de equipos asociados
        $data = TipoEquipo::withCount('equipos')->get();

        $this->labels = $data->pluck('nombre');
        $this->values = $data->pluck('equipos_count');
    }

    public function render()
    {
        return view('livewire.graficos-equipos');
    }
}

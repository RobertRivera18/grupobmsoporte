<?php

namespace App\Livewire;

use App\Models\ActaFirmada;
use Livewire\Component;

class ConsultasIndex extends Component
{
    public $empleado = null;
    public $actas = [];

    protected $listeners = [
        'empleadoSeleccionado' => 'cargarActas'
    ];

    public function cargarActas($empleado)
    {
        $this->empleado = $empleado;

        $this->actas = ActaFirmada::where('cedula_responsable', $empleado['cedula'])
            ->orWhere('cedula_receptor', $empleado['cedula'])
            ->orderByDesc('fecha_firma')
            ->get();
    }

    public function render()
    {
        return view('livewire.consultas-index');
    }
}

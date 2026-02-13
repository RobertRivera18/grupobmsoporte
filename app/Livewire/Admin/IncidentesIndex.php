<?php

namespace App\Livewire\Admin;

use App\Models\Incidencia;
use Livewire\Component;

class IncidentesIndex extends Component{
    public $incidentes;

    protected $listeners = [
        'eliminarIncidencia'
    ];

    public function mount()
    {
        $this->cargarIncidentes();
    }

    public function cargarIncidentes()
    {
        $this->incidentes = Incidencia::with(['usuario', 'archivos'])
            ->orderBy('id', 'desc')
            ->get();
    }

    public function eliminarIncidencia($incidenciaId)
    {
        $incidencia = Incidencia::find($incidenciaId);

        if ($incidencia) {
            $incidencia->delete();
            $this->cargarIncidentes();
            $this->dispatch('swal:success');
        }
    }

    public function render()
    {
        return view('livewire.admin.incidentes-index');
    }
}

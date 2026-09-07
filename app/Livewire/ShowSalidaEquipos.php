<?php

namespace App\Livewire;

use App\Models\SalidaEquipo;
use Livewire\Component;
use Illuminate\Support\Facades\Auth;

class ShowSalidaEquipos extends Component
{
    public SalidaEquipo $salidaequipo;
    public $mostrarModalRechazo = false;
    public $motivo_rechazo = '';

    public function mount(SalidaEquipo $salidaequipo)
    {
        $this->salidaequipo = $salidaequipo;
        $this->salidaequipo->load(['equipos', 'user', 'aprobador']);
    }

    public function abrirModalRechazo()
    {
        $this->motivo_rechazo = '';
        $this->mostrarModalRechazo = true;
    }
    
    public function confirmarRechazo()
    {
        if ($this->salidaequipo->estado !== 'pendiente') {
            return;
        }

        $this->validate([
            'motivo_rechazo' => 'required|min:5'
        ]);

        $this->salidaequipo->update([
            'estado' => 'rechazado',
            'motivo_rechazo' => $this->motivo_rechazo,
            'aprobado_por' => Auth::id(),
            'fecha_aprobacion' => now(),
        ]);

        $this->mostrarModalRechazo = false;

        $this->salidaequipo->load(['equipos', 'user', 'aprobador']);

        session()->flash('message', 'Solicitud rechazada correctamente.');
    }
    public function aprobar()
    {
        if ($this->salidaequipo->estado !== 'pendiente') {
            return;
        }

        $this->salidaequipo->update([
            'estado' => 'aprobado',
            'aprobado_por' => Auth::id(),
            'fecha_aprobacion' => now(),
        ]);

        // Recargar relaciones
        $this->salidaequipo->load(['equipos', 'user', 'aprobador']);

        session()->flash('message', 'Solicitud aprobada correctamente.');
    }

    public function rechazar()
    {
        if ($this->salidaequipo->estado !== 'pendiente') {
            return;
        }

        $this->salidaequipo->update([
            'estado' => 'rechazado',
            'aprobado_por' => Auth::id(),
            'fecha_aprobacion' => now(),
        ]);

        $this->salidaequipo->load(['equipos', 'user', 'aprobador']);

    }
    public function render()
    {
        return view('livewire.show-salida-equipos');
    }
}

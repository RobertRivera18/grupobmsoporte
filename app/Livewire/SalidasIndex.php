<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\SalidaEquipo;
use Illuminate\Support\Facades\Auth;

class SalidasIndex extends Component
{
    public $salidas;

    public function mount()
    {
        $this->cargarSalidas();
    }

    public function cargarSalidas()
    {
        $query = SalidaEquipo::with(['equipos', 'user', 'aprobador'])
            ->latest();

        // 👤 Si NO es admin → solo sus solicitudes
        if (!Auth::user()->hasRole('Admin')) {
            $query->where('usuario_id', Auth::id());
        }

        $this->salidas = $query->get();
    }

    // ✅ Aprobar
    public function aprobar($id)
    {
        $salida = SalidaEquipo::findOrFail($id);

        if ($salida->estado !== 'pendiente') return;

        $salida->estado = 'aprobado';
        $salida->aprobado_por = Auth::id();
        $salida->fecha_aprobacion = now();
        $salida->save();

        $this->cargarSalidas();

        session()->flash('message', 'Solicitud aprobada correctamente.');
    }

    // ❌ Rechazar
    public function rechazar($id)
    {
        $salida = SalidaEquipo::findOrFail($id);

        if ($salida->estado !== 'pendiente') return;

        $salida->estado = 'rechazado';
        $salida->aprobado_por = Auth::id();
        $salida->fecha_aprobacion = now();
        $salida->save();

        $this->cargarSalidas();

        session()->flash('message', 'Solicitud rechazada.');
    }

    public function render()
    {
        return view('livewire.salidas-index');
    }
}

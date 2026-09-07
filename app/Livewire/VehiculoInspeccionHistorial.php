<?php

namespace App\App\Livewire;

use Livewire\Component;
use App\Models\Vehiculo;
use Livewire\WithPagination;

class VehiculoInspeccionHistorial extends Component
{
    use WithPagination;

    public $vehiculoId;

    public function mount($vehiculoId)
    {
        $this->vehiculoId = $vehiculoId;
    }

    // Propiedad computada para obtener el vehículo y sus inspecciones
    public function getVehiculoProperty()
    {
        return Vehiculo::with(['inspecciones' => function($query) {
            $query->latest(); // Ordena de la más reciente a la más antigua
        }])->findOrFail($this->vehiculoId);
    }

    public function render()
    {
        $inspecciones = $this->vehiculo->inspecciones()->paginate(10);

        return view('livewire.vehiculo-inspeccion-historial', [
            'vehiculo' => $this->vehiculo,
            'inspecciones' => $inspecciones
        ]);
    }
}
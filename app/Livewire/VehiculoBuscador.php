<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Vehiculo;
use Illuminate\Support\Collection;

class VehiculoBuscador extends Component
{
    public string $search = '';
    public ?int $vehiculoId = null;
    public ?Vehiculo $vehiculo = null;

    /* ===============================
     * SELECCIONAR VEHÍCULO
     * =============================== */
    public function seleccionarVehiculo(int $id): void
    {
        $this->vehiculoId = $id;

        // Traemos el vehículo por su ID
        $this->vehiculo = Vehiculo::select('id', 'placa', 'marca', 'modelo', 'color')
            ->find($id);

        // BUSQUEDA EN LA TABLA REAL: movimiento_vehiculo
        $ultimoMovimiento = $this->vehiculo->movimientos()->latest('id')->first();
        $ultimoKilometraje = $ultimoMovimiento ? $ultimoMovimiento->kilometraje : 0; 

        $this->reset('search');

        // Despachamos el evento con el kilometraje correcto de la tabla movimiento_vehiculo
        $this->dispatch('vehiculoSeleccionado', vehiculo: [
            'id'                 => $this->vehiculo->id,
            'placa'              => $this->vehiculo->placa,
            'marca'              => $this->vehiculo->marca,
            'modelo'             => $this->vehiculo->modelo,
            'color'              => $this->vehiculo->color,
            'ultimo_kilometraje' => $ultimoKilometraje,
        ]);
    }

    public function limpiarVehiculo(): void
    {
        $this->reset(['vehiculoId', 'vehiculo', 'search']);
        $this->dispatch('vehiculoLimpiado');
    }

    /* ===============================
     * BUSCADOR
     * =============================== */
    public function getVehiculosProperty(): Collection
    {
        if (strlen($this->search) < 5) {
            return collect();
        }

        return Vehiculo::where('placa', 'like', "%{$this->search}%")
            ->orderBy('placa')
            ->limit(5)
            ->get(['id', 'placa', 'marca', 'modelo', 'color']);
    }

    public function render()
    {
        return view('livewire.vehiculo-buscador');
    }
}
<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\User;
use Illuminate\Support\Collection;

class EmpleadoBuscador extends Component
{
    public string $search = '';
    public ?int $empleadoId = null;
    public ?User $empleado = null;

    /* ===============================
     * SELECCIONAR EMPLEADO
     * =============================== */
    public function seleccionarEmpleado(int $id): void
    {
        $this->empleadoId = $id;
        $this->empleado   = User::find($id);
        $this->reset('search');
    }

    public function limpiarEmpleado(): void
    {
        $this->reset(['empleadoId', 'empleado', 'search']);
    }

    /* ===============================
     * BUSCADOR
     * =============================== */
   public function getEmpleadosProperty(): Collection
{
    if (strlen($this->search) < 2) {
        return collect();
    }

    return User::where(function ($query) {
            $query->where('name', 'like', "%{$this->search}%")
                  ->orWhere('cedula', 'like', "%{$this->search}%");
        })
        ->orderBy('name')
        ->limit(10)
        ->get(['id', 'name', 'cedula']);
}

    public function render()
    {
        return view('livewire.empleado-buscador');
    }
}

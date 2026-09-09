<?php

namespace App\Livewire;

use App\Models\Cuadrilla;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use Livewire\WithPagination;

class CuadrillaIndex extends Component
{
    use WithPagination;

    public $search = '';
    public $empresa = '';
    public $estado = '';
    public $ciudad = '';

    // Resetea la paginación automáticamente al modificar cualquier filtro
    public function updatingSearch() { $this->resetPage(); }
    public function updatingEmpresa() { $this->resetPage(); }
    public function updatingEstado() { $this->resetPage(); }
    public function updatingCiudad() { $this->resetPage(); }

    public function limpiarFiltros()
    {
        $this->reset(['search', 'empresa', 'estado', 'ciudad']);
        $this->resetPage();
    }

    public function actualizarEstado($cuadrillaId, $estado)
    {
        $cuadrilla = Cuadrilla::find($cuadrillaId);
        if ($cuadrilla) {
            $cuadrilla->estado = $estado ? 1 : 0;
            $cuadrilla->save();
        }
    }

    public function render()
    {
        $user = Auth::user();
        $cuadrillasQuery = Cuadrilla::query();

        // Filtro de búsqueda por nombre
        if ($this->search) {
            $cuadrillasQuery->where('cua_nombre', 'LIKE', '%' . $this->search . '%');
        }

        // Filtro por Empresa
        if ($this->empresa !== '') {
            $cuadrillasQuery->where('cua_empresa', $this->empresa);
        }

        // Filtro por Estado
        if ($this->estado !== '') {
            $cuadrillasQuery->where('estado', $this->estado);
        }

        // Control de restricciones por rol de ciudad
        if ($user->hasRole('operador1')) {
            $cuadrillasQuery->where('cua_ciudad', 1); // Guayaquil fijo
        } elseif ($user->hasRole('operador2')) {
            $cuadrillasQuery->where('cua_ciudad', 2); // Quito fijo
        } else {
            // Si es Admin u otro rol con permisos globales, se habilita el filtro por ciudad
            if ($this->ciudad !== '') {
                $cuadrillasQuery->where('cua_ciudad', $this->ciudad);
            }
        }

        $cuadrillas = $cuadrillasQuery->latest('id')->paginate(15);

        return view('livewire.cuadrilla-index', compact('cuadrillas'));
    }
}
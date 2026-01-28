<?php

namespace App\Livewire;

use App\Models\Cuadrilla;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use Livewire\WithPagination;

class CuadrillaIndex extends Component
{
    use WithPagination;

    public $search;
    public $page;

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
        $cuadrillasQuery = Cuadrilla::query();
        $cuadrillasQuery->where('cua_nombre', 'LIKE', '%' . $this->search . '%');

        // Filtro por ciudad según el rol del usuario
        $user = Auth::user();

        if ($user->hasRole('operador1')) {
            $cuadrillasQuery->where('cua_ciudad', 1); // Guayaquil
        } elseif ($user->hasRole('operador2')) {
            $cuadrillasQuery->where('cua_ciudad', 2);//Quito
        }

        $cuadrillas = $cuadrillasQuery->latest('id')->paginate(15);


        return view('livewire.cuadrilla-index', compact('cuadrillas'));
    }

    public function limpiar_page()
    {
        $this->reset('page');
    }
}

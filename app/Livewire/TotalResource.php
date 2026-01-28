<?php

namespace App\Livewire;

use App\Models\Cuadrilla;
use App\Models\Report;
use Livewire\Component;

class TotalResource extends Component
{
    public function render()
    {
        $cuadrillas = Cuadrilla::latest()->take(3)->get();
        $cuadrillasgye = Cuadrilla::where('cua_ciudad', 1)->count();
        $cuadrillasuio = Cuadrilla::where('cua_ciudad', 2)->count();


        return view('livewire.total-resource', compact(
            'cuadrillas',
            'cuadrillasgye',
            'cuadrillasuio',
           
        ));
    }
}

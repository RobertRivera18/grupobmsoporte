<?php

namespace App\Livewire;

use App\Models\Area;
use Livewire\Component;

class IndexAreas extends Component
{
    public function eliminar($id)
    {
        $area = Area::find($id);

        if ($area) {
            $area->delete();
            
        }
    }

    public function render()
    {
        $areas = Area::all();
        return view('livewire.index-areas', compact('areas'));
    }
}

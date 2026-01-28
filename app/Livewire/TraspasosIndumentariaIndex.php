<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\TraspasoIndumentaria;

class TraspasosIndumentariaIndex extends Component
{
    use WithPagination;

    protected $paginationTheme = 'tailwind';

  public function render()
    {
        $traspasos = TraspasoIndumentaria::with([
                'origen:id,nombre',
                'destino:id,nombre',
                'detalles.indumentaria:id,nombre'
            ])
            ->orderBy('fecha', 'desc')
            ->paginate(10);

        return view('livewire.traspasos-indumentaria-index', compact('traspasos'));
    }
}

<?php

namespace App\Livewire\Admin;

use App\Models\Indumentaria;
use Livewire\Component;
use Livewire\WithPagination;
use Illuminate\Support\Facades\Auth;


class IndumentariaIndex extends Component
{
    use WithPagination;

    public $search = '';

    protected $queryString = ['search'];

    /**
     * Resetear paginación al buscar
     */
    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function render()
    {
    $user = Auth::user();

    $indumentarias = Indumentaria::with([
        'inventarios' => function ($q) use ($user) {
            if ($user->hasRole('operador2')) {
                $q->whereHas('ubicacion', function ($u) {
                    $u->where('nombre', 'Quito');
                });
            }
        },
        'inventariosUsados' => function ($q) use ($user) {
            if ($user->hasRole('operador2')) {
                $q->whereHas('ubicacion', function ($u) {
                    $u->where('nombre', 'Quito');
                });
            }
        },
    ])
    ->where(function ($q) {
        $q->where('nombre', 'like', "%{$this->search}%")
          ->orWhere('tipo', 'like', "%{$this->search}%");
    })
    ->when($user->hasRole('operador2'), function ($q) {
        $q->where(function ($qq) {
            $qq->whereHas('inventarios.ubicacion', function ($u) {
                $u->where('nombre', 'Quito');
            })
            ->orWhereHas('inventariosUsados.ubicacion', function ($u) {
                $u->where('nombre', 'Quito');
            });
        });
    })
    ->orderByDesc('id')
    ->paginate(10);

        return view('livewire.admin.indumentaria-index', compact('indumentarias'));
    }
}

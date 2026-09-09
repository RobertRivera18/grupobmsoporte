<?php

namespace App\Livewire\Admin;

use App\Models\Indumentaria;
use App\Models\Ubicacion;
use Livewire\Component;
use Livewire\WithPagination;
use Illuminate\Support\Facades\Auth;

class IndumentariaIndex extends Component
{
    use WithPagination;

    // Filtros
    public $search = '';
    public $selectedTipo = '';
    public $selectedColor = '';
    public $selectedTalla = '';
    public $selectedUbicacion = '';

    protected $queryString = [
        'search' => ['except' => ''],
        'selectedTipo' => ['except' => ''],
        'selectedColor' => ['except' => ''],
        'selectedTalla' => ['except' => ''],
        'selectedUbicacion' => ['except' => ''],
    ];

    public function updated($propertyName)
    {
        if (in_array($propertyName, ['search', 'selectedTipo', 'selectedColor', 'selectedTalla', 'selectedUbicacion'])) {
            $this->resetPage();
        }
    }

    public function clearFilters()
    {
        $this->reset(['search', 'selectedTipo', 'selectedColor', 'selectedTalla', 'selectedUbicacion']);
        $this->resetPage();
    }

    public function destroy($id)
    {
        Indumentaria::findOrFail($id)->delete();
        session()->flash('message', 'Indumentaria eliminada correctamente.');
        $this->resetPage();
    }

    // Método privado que aplica TODOS tus filtros exactamente igual que antes
    protected function applyFilters($isOperador2)
    {
        return Indumentaria::query()
            ->when($this->search, function ($q) {
                $terms = explode(' ', $this->search);
                foreach ($terms as $term) {
                    if (trim($term) !== '') {
                        $q->where(function ($sub) use ($term) {
                            $sub->where('nombre', 'like', "%{$term}%")
                                ->orWhere('tipo', 'like', "%{$term}%");
                        });
                    }
                }
            })
            ->when($this->selectedTipo, fn($q) => $q->where('tipo', $this->selectedTipo))
            ->when($this->selectedColor, fn($q) => $q->where('color', $this->selectedColor))
            ->when($this->selectedTalla, fn($q) => $q->where('talla', $this->selectedTalla))
            ->when($isOperador2, function ($q) {
                $q->where(function ($qq) {
                    $qq->whereHas('inventarios.ubicacion', fn($u) => $u->where('nombre', 'Quito'))
                        ->orWhereHas('inventariosUsados.ubicacion', fn($u) => $u->where('nombre', 'Quito'));
                });
            })
            ->when($this->selectedUbicacion, function ($q) {
                $q->where(function ($qq) {
                    $qq->whereHas('inventarios', fn($u) => $u->where('ubicacion_id', $this->selectedUbicacion))
                        ->orWhereHas('inventariosUsados', fn($u) => $u->where('ubicacion_id', $this->selectedUbicacion));
                });
            });
    }

    public function render()
    {
        $user = Auth::user();
        $isOperador2 = $user->hasRole('operador2');

        // Optimización de selects: Evitamos plucks duplicados innecesarios usando distinct()
        $tipos = Indumentaria::whereNotNull('tipo')->distinct()->pluck('tipo');
        $colores = Indumentaria::whereNotNull('color')->distinct()->pluck('color');
        $tallas = Indumentaria::whereNotNull('talla')->distinct()->pluck('talla');

        $ubicaciones = Ubicacion::when($isOperador2, function ($q) {
            $q->where('nombre', 'Quito');
        })->get();

        // Creamos la consulta con los filtros aplicados
        $query = $this->applyFilters($isOperador2);

        // Mantenemos tu lógica exacta: clonamos una sola vez para obtener todos los resultados filtrados en memoria
        $allResults = (clone $query)->get();

        $totalPrendas = $allResults->count();
        $totalNuevo = $allResults->sum('stock_nuevo');
        $totalUsado = $allResults->sum('stock_usado');
        $totalStock = $totalNuevo + $totalUsado;

        $chartTipoLabels = [];
        $chartTipoData = [];
        $tiposGrouped = $allResults->groupBy('tipo');

        foreach ($tiposGrouped as $tipoNombre => $items) {
            $chartTipoLabels[] = $tipoNombre ?: 'Sin tipo';
            $chartTipoData[] = $items->sum('stock_total');
        }

        // Paginación final manteniendo tus relaciones originales
        $paginatedIndumentarias = $query->with([
            'inventarios' => function ($q) use ($isOperador2) {
                if ($isOperador2) {
                    $q->whereHas('ubicacion', fn($u) => $u->where('nombre', 'Quito'));
                }
            },
            'inventariosUsados' => function ($q) use ($isOperador2) {
                if ($isOperador2) {
                    $q->whereHas('ubicacion', fn($u) => $u->where('nombre', 'Quito'));
                }
            },
        ])->orderByDesc('id')->paginate(10);

        return view('livewire.admin.indumentaria-index', [
            'indumentarias' => $paginatedIndumentarias,
            'tipos' => $tipos,
            'colores' => $colores,
            'tallas' => $tallas,
            'ubicaciones' => $ubicaciones,
            'totalPrendas' => $totalPrendas,
            'totalNuevo' => $totalNuevo,
            'totalUsado' => $totalUsado,
            'totalStock' => $totalStock,
            'chartTipoLabels' => $chartTipoLabels,
            'chartTipoData' => $chartTipoData,
        ]);
    }
}
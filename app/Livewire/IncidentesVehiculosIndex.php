<?php

namespace App\Livewire;

use App\Models\Incidente;
use Livewire\Component;
use Livewire\WithPagination;
use Illuminate\Support\Facades\DB;

class IncidentesVehiculosIndex extends Component
{
    use WithPagination;

    public $search = '';
    public $perPage = 10;
    public $sortField = 'id';
    public $sortDir = 'desc';

    public $queryString = [
        'search' => ['except' => ''],
    ];

    // Se dispara al cambiar cualquier propiedad de filtro (incluyendo perPage si se desea)
    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function updatingPerPage()
    {
        $this->resetPage();
    }

    public function sortBy($field)
    {
        if ($this->sortField === $field) {
            $this->sortDir = $this->sortDir === 'asc' ? 'desc' : 'asc';
        } else {
            $this->sortField = $field;
            $this->sortDir = 'asc';
        }
    }

    public function render()
    {
        $query = Incidente::query()
            ->with(['vehiculo:id,placa,marca,modelo'])
            ->when($this->search, function ($q) {
                $term = '%' . $this->search . '%';
                $q->where(function ($sub) use ($term) {
                    $sub->where('incidentes.id', 'like', $term)
                        ->orWhere('incidentes.tipo', 'like', $term)
                        ->orWhere('incidentes.fecha', 'like', $term)
                        ->orWhereHas('vehiculo', function ($vQuery) use ($term) {
                            $vQuery->where('placa', 'like', $term)
                                ->orWhere('marca', 'like', $term)
                                ->orWhere('modelo', 'like', $term);
                        });
                });
            });

        if ($this->sortField === 'placa') {
            $query->join('vehiculos', 'incidentes.vehiculo_id', '=', 'vehiculos.id')
                ->orderBy('vehiculos.placa', $this->sortDir)
                ->select('incidentes.*');
        } else {
            $query->select('incidentes.*')
                ->orderBy('incidentes.' . $this->sortField, $this->sortDir);
        }

        $perPage = $this->perPage === 'all' ? 500 : (int) $this->perPage;
        $incidentesPaginados = $perPage === 500 ? $query->get() : $query->paginate($perPage);
        $stats = Incidente::selectRaw('
            COUNT(*) as total,
            SUM(CASE WHEN tipo = 1 THEN 1 ELSE 0 END) as ingresos,
            SUM(CASE WHEN tipo = 2 THEN 1 ELSE 0 END) as salidas
        ')->first();

        return view('livewire.incidentes-vehiculos-index', [
            'incidentes' => $incidentesPaginados,
            'stats' => [
                'total' => $stats->total ?? 0,
                'ingresos' => $stats->ingresos ?? 0,
                'salidas' => $stats->salidas ?? 0,
            ]
        ]);
    }
}

<?php

namespace App\Livewire;

use App\Models\VehiculoInspeccion;
use App\Models\Vehiculo;
use Livewire\Component;
use Livewire\WithPagination;

class RevisionesVehicularesAll extends Component
{
    use WithPagination;

    protected $paginationTheme = 'tailwind';
    public string $search = '';
    public string $vehiculo = '';
    public string $fecha = '';
    public string $desde = '';
    public string $hasta = '';

    public string $sortField = 'fecha';
    public string $sortDirection = 'desc';
    protected array $allowedSorts = [
        'fecha',
        'kilometraje',
    ];

    protected $queryString = [
        'search' => ['except' => ''],
        'vehiculo' => ['except' => ''],
        'fecha' => ['except' => ''],
        'desde' => ['except' => ''],
        'hasta' => ['except' => ''],
        'sortField' => ['except' => 'fecha'],
        'sortDirection' => ['except' => 'desc'],
    ];


    public function updated($property)
    {
        if (in_array($property, ['search', 'vehiculo', 'fecha', 'desde', 'hasta'])) {
            $this->resetPage();
        }
    }

    public function sortBy(string $field): void
    {
        if (!in_array($field, $this->allowedSorts)) {
            return;
        }

        if ($this->sortField === $field) {
            $this->sortDirection = $this->sortDirection === 'asc' ? 'desc' : 'asc';
        } else {
            $this->sortField = $field;
            $this->sortDirection = 'asc';
        }

        $this->resetPage();
    }

    public function limpiarFiltros(): void
    {
        $this->reset(['search', 'vehiculo', 'fecha', 'desde', 'hasta']);
        $this->sortField = 'fecha';
        $this->sortDirection = 'desc';
        $this->resetPage();
    }


    protected function getRevisiones()
{
    $query = VehiculoInspeccion::query()->with(['vehiculo', 'tecnicoEncargado']);

    if (!empty($this->search)) {
        $query->where(function ($q) {
         $q->whereHas('tecnicoEncargado', function ($tQ) {
                $tQ->where('name', 'like', '%' . $this->search . '%');
            })
            ->orWhereHas('vehiculo', function ($vQ) {
                $vQ->where('placa', 'like', '%' . $this->search . '%')
                   ->orWhere('marca', 'like', '%' . $this->search . '%');
            });
        });
    }

    if (!empty($this->vehiculo)) {
        $query->where('vehiculo_id', $this->vehiculo);
    }

    if (!empty($this->fecha)) {
        $query->whereDate('fecha', $this->fecha);
    }

    if (!empty($this->desde)) {
        $query->whereDate('fecha', '>=', $this->desde);
    }

    if (!empty($this->hasta)) {
        $query->whereDate('fecha', '<=', $this->hasta);
    }

    return $query->orderBy($this->sortField, $this->sortDirection)->paginate(10);
}

   
    protected function getEstadisticas(): array
    {
        return [
            'total' => VehiculoInspeccion::count(),
            'esteMes' => VehiculoInspeccion::whereMonth('fecha', now()->month)
                ->whereYear('fecha', now()->year)
                ->count(),
            'totalVehiculos' => Vehiculo::count(),
        ];
    }

    
    public function eliminar(int $id): void
    {
        $inspeccion = VehiculoInspeccion::findOrFail($id);
        if (method_exists($inspeccion, 'fotos')) {
            $inspeccion->fotos()->delete();
        }
        
        $inspeccion->delete();
        $this->resetPage();
    }

    public function render()
    {
        return view('livewire.revisiones-vehiculares-all', [
            'revisiones' => $this->getRevisiones(),
            'vehiculos' => Vehiculo::orderBy('placa')->get(),
            ...$this->getEstadisticas(),
        ]);
    }
}
<?php

namespace App\Livewire;

use App\Models\RegistroMantenimiento;
use App\Models\Vehiculo;
use Livewire\Component;
use Livewire\WithPagination;

class MantenimientosAll extends Component
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
        'placa',
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
        if (in_array($property, [
            'search',
            'vehiculo',
            'fecha',
            'desde',
            'hasta',
        ])) {
            $this->resetPage();
        }
    }


    public function sortBy(string $field): void
    {
        if (! in_array($field, $this->allowedSorts)) {
            return;
        }

        if ($this->sortField === $field) {
            $this->sortDirection = $this->sortDirection === 'asc'
                ? 'desc'
                : 'asc';
        } else {
            $this->sortField = $field;
            $this->sortDirection = 'asc';
        }

        $this->resetPage();
    }

    /**
     * Limpiar filtros.
     */
    public function limpiarFiltros(): void
    {
        $this->reset([
            'search',
            'vehiculo',
            'fecha',
            'desde',
            'hasta',
        ]);

        $this->sortField = 'fecha';
        $this->sortDirection = 'desc';

        $this->resetPage();
    }

    /**
     * Consulta principal.
     */
    protected function getMantenimientos()
    {
        return RegistroMantenimiento::query()
            ->conRelaciones()
            ->buscar($this->search)
            ->vehiculo($this->vehiculo)
            ->fecha($this->fecha)
            ->rangoFechas($this->desde, $this->hasta)
            ->ordenar($this->sortField, $this->sortDirection)
            ->paginate(10);
    }

    /**
     * Estadísticas.
     */
    protected function getEstadisticas(): array
    {
        return [
            'total' => RegistroMantenimiento::count(),

            'esteMes' => RegistroMantenimiento::whereMonth(
                'fecha',
                now()->month
            )->whereYear(
                'fecha',
                now()->year
            )->count(),

            'totalVehiculos' => Vehiculo::count(),
        ];
    }

    public function eliminar(int $id): void
    {
        $mantenimiento = RegistroMantenimiento::findOrFail($id);
        $mantenimiento->detalles()->delete();
        $mantenimiento->delete();
        $this->resetPage();
    }

    public function render()
    {
        $estadisticas = $this->getEstadisticas();

        return view('livewire.mantenimientos-all', [
            'mantenimientos' => $this->getMantenimientos(),
            'vehiculos' => Vehiculo::orderBy('placa')->get(),
            ...$estadisticas,
        ]);
    }
}

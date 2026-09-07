<?php

namespace App\Livewire;

use App\Models\Equipos;
use Illuminate\Support\Facades\Log;
use Livewire\Component;
use Livewire\WithPagination;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

class EquiposTable extends Component
{
    use WithPagination;

    protected $paginationTheme = 'tailwind';

    /* =========================
     *  FILTROS / BÚSQUEDA
     * ========================= */
    public $search = '';
    public $filtroCiudad = '';   // '', 1 (Guayaquil), 2 (Quito)
    public $filtroEstado = '';   // '', 'libre', 'defecto', 'asignado'
    public $filtroMarca = '';
    public $perPage = 40;

    /* =========================
     *  ORDENAMIENTO
     * ========================= */
    public $sortField = 'created_at';
    public $sortDirection = 'desc';

    /* =========================
     *  MODAL DESACTIVACIÓN
     * ========================= */
    public $openModal = false;
    public $observacion = '';
    public $equipoIdSeleccionado = null;

    /* =========================
     *  EXPORT
     * ========================= */
    public $exportando = false;

    protected $listeners = ['delete'];

    protected $queryString = [
        'search' => ['except' => ''],
        'filtroCiudad' => ['except' => ''],
        'filtroEstado' => ['except' => ''],
        'filtroMarca' => ['except' => ''],
        'sortField' => ['except' => 'created_at'],
        'sortDirection' => ['except' => 'desc'],
    ];


    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function updatingFiltroCiudad()
    {
        $this->resetPage();
    }

    public function updatingFiltroEstado()
    {
        $this->resetPage();
    }

    public function updatingFiltroMarca()
    {
        $this->resetPage();
    }

    public function updatingPerPage()
    {
        $this->resetPage();
    }

    public function sortBy(string $field)
    {
        if ($this->sortField === $field) {
            $this->sortDirection = $this->sortDirection === 'asc' ? 'desc' : 'asc';
        } else {
            $this->sortField = $field;
            $this->sortDirection = 'asc';
        }

        $this->resetPage();
    }

    public function limpiarFiltros()
    {
        $this->reset(['search', 'filtroCiudad', 'filtroEstado', 'filtroMarca']);
        $this->resetPage();
    }


    public function delete($equipoId)
    {
        // Recomendado: $this->authorize('delete', Equipos::findOrFail($equipoId));
        Equipos::whereKey($equipoId)->delete();

        $this->dispatch('swal:success', [
            'title' => '¡Eliminado!',
            'text' => 'El equipo ha sido eliminado.',
            'icon' => 'success'
        ]);
    }

    public function actualizarEstado($equipoId, $estado)
    {
        // Recomendado: $this->authorize('update', Equipos::findOrFail($equipoId));
        if ($estado) {
            Equipos::whereKey($equipoId)->update([
                'estado' => 1,
                'observacion' => null
            ]);
            return;
        }
        $this->equipoIdSeleccionado = $equipoId;
        $this->observacion = '';
        $this->openModal = true;
    }

    public function confirmarDesactivacion()
    {
        $this->validate([
            'observacion' => 'required|string|min:5|max:255',
        ]);

        Equipos::whereKey($this->equipoIdSeleccionado)->update([
            'estado' => 0,
            'observacion' => $this->observacion,
        ]);

        $this->dispatch('swal:success', [
            'title' => 'Equipo desactivado',
            'text' => 'Motivo guardado correctamente.',
            'icon' => 'success'
        ]);

        $this->cerrarModal();
    }

    public function cerrarModal()
    {
        $this->openModal = false;
        $this->reset(['observacion', 'equipoIdSeleccionado']);
    }

    public function generarExcelEquipos()
    {
        $this->exportando = true;

        try {
            $equipos = $this->baseQuery()->get();
            $templatePath = public_path('templates/formatoStockEquipos.xlsx');
            if (!file_exists($templatePath)) {
                throw new \RuntimeException("Plantilla de Excel no encontrada en: {$templatePath}");
            }
            $spreadsheet = IOFactory::load($templatePath);
            $sheet = $spreadsheet->getActiveSheet();

            $fila = 9;
            $contador = 1;

            foreach ($equipos as $equipo) {
                $sheet->fromArray([
                    $contador,
                    $equipo->nombre,
                    $equipo->marca,
                    $equipo->modelo,
                    $equipo->serie,
                    $this->obtenerAsignacion($equipo),
                    $this->mapearCiudad($equipo->ciudad),
                    $equipo->observacion ?? '-',
                ], null, "C{$fila}");

                $fila++;
                $contador++;
            }

            foreach (range('A', 'J') as $col) {
                $sheet->getColumnDimension($col)->setAutoSize(true);
            }

            $fileName = 'reporteEquiposStock_' . now()->format('Y-m-d_His') . '.xlsx';
            $path = storage_path("app/public/{$fileName}");

            (new Xlsx($spreadsheet))->save($path);

            $this->exportando = false;

            return response()->download($path)->deleteFileAfterSend();
        } catch (\Throwable $e) {
            $this->exportando = false;

            Log::error('Error generando Excel de equipos: ' . $e->getMessage());

            $this->dispatch('swal', [
                'icon' => 'error',
                'title' => 'Error',
                'text' => 'No se pudo generar el Excel. Contacta a soporte si el problema persiste.',
            ]);
        }
    }

    private function obtenerAsignacion($equipo): string
    {
        if ($equipo->estado == 0) {
            return 'Equipo con defecto';
        }
        if ($equipo->users->isNotEmpty()) {
            return $equipo->users->first()->name;
        }
        if ($equipo->cuadrilla->isNotEmpty()) {
            return $equipo->cuadrilla->first()->cua_nombre;
        }

        return 'Equipo Libre';
    }

    private function mapearCiudad($ciudad): string
    {
        return match ($ciudad) {
            1 => 'Guayaquil',
            2 => 'Quito',
            default => '-',
        };
    }

    private function baseQuery()
    {
        $search = "%{$this->search}%";

        return Equipos::query()
            ->when($this->search !== '', function ($query) use ($search) {
                $query->where(function ($q) use ($search) {
                    $q->where('nombre', 'like', $search)
                        ->orWhere('marca', 'like', $search)
                        ->orWhere('modelo', 'like', $search)
                        ->orWhere('serie', 'like', $search)
                        ->orWhereHas('users', fn($q2) => $q2->where('name', 'like', $search))
                        ->orWhereHas('cuadrilla', fn($q2) => $q2->where('cua_nombre', 'like', $search));
                });
            })
            ->when($this->filtroCiudad !== '', function ($query) {
                $query->where('ciudad', $this->filtroCiudad);
            })
            ->when($this->filtroMarca !== '', function ($query) {
                $query->where('marca', $this->filtroMarca);
            })
            ->when($this->filtroEstado !== '', function ($query) {
                match ($this->filtroEstado) {
                    'defecto' => $query->where('estado', 0),
                    'libre' => $query->where('estado', 1)
                        ->whereDoesntHave('users')
                        ->whereDoesntHave('cuadrilla'),
                    'asignado' => $query->where(function ($q) {
                        $q->whereHas('users')->orWhereHas('cuadrilla');
                    }),
                    default => null,
                };
            })
            ->with(['users:id,name', 'cuadrilla:id,cua_nombre']);
    }



    public function getMarcasProperty()
    {
        return \Illuminate\Support\Facades\Cache::remember('equipos_marcas_distintas', 300, function () {
            return Equipos::query()
                ->whereNotNull('marca')
                ->distinct()
                ->orderBy('marca')
                ->pluck('marca');
        });
    }

 
    public function render()
    {
        $equipos = $this->baseQuery()
            ->orderBy($this->sortField, $this->sortDirection)
            ->paginate($this->perPage);

        return view('livewire.equipos-table', [
            'equipos' => $equipos,
            'marcas' => $this->marcas,
        ]);
    }
}
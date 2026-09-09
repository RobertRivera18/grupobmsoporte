<?php

namespace App\Livewire;

use App\Models\Cuadrilla;
use App\Models\Grupo;
use App\Models\InventarioControl;
use App\Models\Tecnologia;
use App\Models\TipoActividad; 
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Url;
use Livewire\Component;
use Livewire\WithPagination;
use PhpOffice\PhpSpreadsheet\Cell\Coordinate;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use Symfony\Component\HttpFoundation\StreamedResponse;

class InventarioListado extends Component
{
    use WithPagination;

    protected string $paginationTheme = 'tailwind';

    #[Url] public string $buscar = '';
    #[Url] public string $fecha_desde = '';
    #[Url] public string $fecha_hasta = '';
    #[Url] public string $grupo_id = '';
    #[Url] public string $tecnologia_id = '';
    #[Url] public string $cuadrilla_id = '';
    #[Url] public string $tipo_actividad_id = ''; // <--- 2. Nueva propiedad URL para filtrar por tipo de actividad

    public int $perPage = 15;
    public bool $mostrarModal = false;
    public bool $mostrarModalExcel = false;
    public ?int $inventarioSeleccionadoId = null;

    public string $excel_fecha_desde = '';
    public string $excel_fecha_hasta = '';

    public function updated($property): void
    {
        if (in_array($property, ['buscar', 'fecha_desde', 'fecha_hasta', 'grupo_id', 'tecnologia_id', 'cuadrilla_id', 'tipo_actividad_id'])) {
            $this->resetPage();
        }
    }

    public function limpiarFiltros(): void
    {
        $this->reset(['buscar', 'fecha_desde', 'fecha_hasta', 'grupo_id', 'tecnologia_id', 'cuadrilla_id', 'tipo_actividad_id']);
        $this->resetPage();
    }

    public function verInventario(int $id): void
    {
        $this->inventarioSeleccionadoId = $id;
        $this->mostrarModal = true;
    }

    public function cerrarModal(): void
    {
        $this->reset(['mostrarModal', 'inventarioSeleccionadoId']);
    }

    #[Computed]
    public function inventarioSeleccionado()
    {
        if (!$this->inventarioSeleccionadoId) {
            return null;
        }
        return InventarioControl::with(['tipoActividad'])->find($this->inventarioSeleccionadoId);
    }

    #[Computed]
    public function inventarioDetalles()
    {
        if (!$this->inventarioSeleccionadoId) {
            return collect();
        }

        return InventarioControl::with(['detalles.material'])
            ->find($this->inventarioSeleccionadoId)
            ?->detalles()
            ->where('stock_final', '>', 0)
            ->get() ?? collect();
    }

    public function eliminar(int $id): void
    {
        DB::transaction(function () use ($id) {
            $inventario = InventarioControl::findOrFail($id);
            $inventario->detalles()->delete();
            $inventario->delete();
        });

        $this->dispatch('swal', [
            'icon' => 'success',
            'title' => '¡Bien hecho!',
            'text' => 'Inventario eliminado con éxito.',
            'position' => 'top-end',
            'toast' => true,
            'timer' => 3000,
            'showConfirmButton' => false,
        ]);
    }

    #[Computed]
    public function grupos()
    {
        return Grupo::select('id', 'nombre')->orderBy('nombre')->get();
    }

    #[Computed]
    public function tecnologias()
    {
        return Tecnologia::select('id', 'nombre')->orderBy('nombre')->get();
    }

    #[Computed]
    public function cuadrillas()
    {
        return Cuadrilla::select('id', 'cua_nombre')->orderBy('cua_nombre')->get();
    }

    #[Computed]
    public function tipoActividades()
    {
        return TipoActividad::select('id', 'nombre')->orderBy('nombre')->get();
    }


    public function abrirModalExcel(): void
    {
        $this->excel_fecha_desde = now()->startOfMonth()->format('Y-m-d');
        $this->excel_fecha_hasta = now()->format('Y-m-d');
        $this->mostrarModalExcel = true;
    }

    public function cerrarModalExcel(): void
    {
        $this->reset(['mostrarModalExcel', 'excel_fecha_desde', 'excel_fecha_hasta']);
    }

    public function generarExcel(): ?StreamedResponse
    {
        $this->validate([
            'excel_fecha_desde' => 'required|date',
            'excel_fecha_hasta' => 'required|date|after_or_equal:excel_fecha_desde',
        ], [
            'excel_fecha_hasta.after_or_equal' => 'La fecha final debe ser igual o posterior a la fecha inicial.',
        ]);

        $queryExport = InventarioControl::query()
            ->whereDate('fecha_inventario', '>=', $this->excel_fecha_desde)
            ->whereDate('fecha_inventario', '<=', $this->excel_fecha_hasta);

        if (!$queryExport->exists()) {
            $this->dispatch('swal', [
                'icon' => 'warning',
                'title' => 'Sin registros',
                'text' => 'No hay reportes de inventario registrados en el rango de fechas seleccionado.',
                'position' => 'top-end',
                'toast' => true,
                'timer' => 3500,
                'showConfirmButton' => false,
            ]);
            return null;
        }

        $tecnologias = $this->tecnologias;
        $spreadsheet = new Spreadsheet();
        $spreadsheet->removeSheetByIndex(0); // Remover la hoja por defecto de PhpSpreadsheet

        $hojaCreada = false; // Bandera para verificar si al menos una tecnología tiene datos

        foreach ($tecnologias as $tecnologia) {
            $tieneInventarios = InventarioControl::query()
                ->where('tecnologia_id', $tecnologia->id)
                ->whereDate('fecha_inventario', '>=', $this->excel_fecha_desde)
                ->whereDate('fecha_inventario', '<=', $this->excel_fecha_hasta)
                ->exists();

            if (!$tieneInventarios) {
                continue;
            }

            $sheet = $spreadsheet->createSheet();
            $sheet->setTitle(substr($tecnologia->nombre, 0, 31));

            $sheet->setCellValue('A1', 'Código Material');
            $sheet->setCellValue('B1', 'Descripción');

            $this->llenarHojaTecnologiaRango($sheet, $tecnologia);

            $hojaCreada = true;
        }

        if (!$hojaCreada) {
            $this->dispatch('swal', [
                'icon' => 'warning',
                'title' => 'Sin registros',
                'text' => 'No hay reportes de inventario para ninguna de las tecnologías en el rango seleccionado.',
                'position' => 'top-end',
                'toast' => true,
                'timer' => 3500,
                'showConfirmButton' => false,
            ]);
            return null;
        }

        $writer = new Xlsx($spreadsheet);
        $nombreArchivo = 'inventario_' . $this->excel_fecha_desde . '_a_' . $this->excel_fecha_hasta . '.xlsx';

        $this->mostrarModalExcel = false;

        return response()->streamDownload(function () use ($writer) {
            $writer->save('php://output');
        }, $nombreArchivo, [
            'Content-Type' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
        ]);
    }

    private function llenarHojaTecnologiaRango($sheet, Tecnologia $tecnologia): void
    {
        $materiales = $tecnologia->materiales()->orderBy('materiales.id')->get(['materiales.id', 'codigo', 'descripcion']);

        $inventarios = InventarioControl::query()
            ->where('tecnologia_id', $tecnologia->id)
            ->whereDate('fecha_inventario', '>=', $this->excel_fecha_desde)
            ->whereDate('fecha_inventario', '<=', $this->excel_fecha_hasta)
            ->with(['cuadrilla:id,cua_nombre', 'detalles:id,inventario_control_id,material_id,stock_final'])
            ->get()
            ->sortBy([
                ['fecha_inventario', 'asc'],
                [fn($inv) => $inv->cuadrilla->cua_nombre ?? '', 'asc']
            ])
            ->values();

        $col = 3;
        $stockPorColumna = [];

        foreach ($inventarios as $inventario) {
            $letra = Coordinate::stringFromColumnIndex($col);
            $nombreCuadrilla = $inventario->cuadrilla->cua_nombre ?? 'Sin cuadrilla';
            $orden = $inventario->observaciones;
            $tipo_actividad = $inventario->tipoActividad->nombre;
            $fechaFmt = Carbon::parse($inventario->fecha_inventario)->format('d/m/Y');
            $textoCabecera = $nombreCuadrilla . " (" . $fechaFmt . ")\n" .
                "Orden: " . $orden . "\n" .
                "Actividad: " . $tipo_actividad;
            $sheet->setCellValue($letra . '1', $textoCabecera);
            $sheet->getStyle($letra . '1')->getAlignment()->setWrapText(true);
            $sheet->getStyle($letra . '1')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
            $sheet->getStyle($letra . '1')->getAlignment()->setVertical(Alignment::VERTICAL_CENTER);
            $sheet->getRowDimension(1)->setRowHeight(50);
            $stockPorColumna[$letra] = $inventario->detalles->pluck('stock_final', 'material_id');
            $col++;
        }
        $fila = 2;
        foreach ($materiales as $material) {
            $sheet->setCellValue('A' . $fila, $material->codigo);
            $sheet->setCellValue('B' . $fila, $material->descripcion);

            foreach ($stockPorColumna as $letra => $stocks) {
                $sheet->setCellValue($letra . $fila, $stocks->get($material->id, 0));
            }

            $fila++;
        }

        $ultimaColumna = $col > 3 ? Coordinate::stringFromColumnIndex($col - 1) : 'B';
        $ultimaFila = max($fila - 1, 1);

        $this->estilizarHoja($sheet, $ultimaColumna, $ultimaFila, $col);
    }

    private function estilizarHoja($sheet, string $ultimaColumna, int $ultimaFila, int $col): void
    {
        $rangoCabecera = 'A1:' . $ultimaColumna . '1';
        $sheet->getStyle($rangoCabecera)->getFont()->setBold(true);
        $sheet->getStyle($rangoCabecera)->getFill()
            ->setFillType(Fill::FILL_SOLID)
            ->getStartColor()->setRGB('D9E1F2');
        $sheet->getStyle($rangoCabecera)->getAlignment()->setHorizontal('center');

        $sheet->getStyle('A1:' . $ultimaColumna . $ultimaFila)
            ->getBorders()->getAllBorders()
            ->setBorderStyle(Border::BORDER_THIN);

        $sheet->getColumnDimension('A')->setWidth(18);
        $sheet->getColumnDimension('B')->setWidth(50);
        foreach (range(3, max($col - 1, 3)) as $c) {
            $sheet->getColumnDimensionByColumn($c)->setWidth(22);
        }

        $sheet->freezePane('C2');
    }
    public function render()
    {
        $inventarios = InventarioControl::query()
            ->with(['grupo:id,nombre', 'tecnologia:id,nombre', 'cuadrilla:id,cua_nombre', 'tipoActividad:id,nombre'])
            ->when($this->fecha_desde, fn($q) => $q->whereDate('fecha_inventario', '>=', $this->fecha_desde))
            ->when($this->fecha_hasta, fn($q) => $q->whereDate('fecha_inventario', '<=', $this->fecha_hasta))
            ->when($this->grupo_id, fn($q) => $q->where('grupo_id', $this->grupo_id))
            ->when($this->tecnologia_id, fn($q) => $q->where('tecnologia_id', $this->tecnologia_id))
            ->when($this->cuadrilla_id, fn($q) => $q->where('cuadrilla_id', $this->cuadrilla_id))
            ->when($this->tipo_actividad_id, fn($q) => $q->where('tipo_actividad_id', $this->tipo_actividad_id)) // 5. Filtro condicional
            ->when($this->buscar, function ($q) {
                $q->whereHas('cuadrilla', fn($q) => $q->where('cua_nombre', 'like', '%' . $this->buscar . '%'));
            })
            ->latest('fecha_inventario')
            ->paginate($this->perPage);

        return view('livewire.inventario-listado', [
            'inventarios' => $inventarios,
        ]);
    }
}

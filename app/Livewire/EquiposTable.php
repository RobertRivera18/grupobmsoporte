<?php

namespace App\Livewire;

use App\Models\Equipos;
use Livewire\Component;
use Livewire\WithPagination;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

class EquiposTable extends Component
{
    use WithPagination;

    protected $paginationTheme = 'tailwind';

    public $search = '';
    public $openModal = false;
    public $observacion = '';
    public $equipoIdSeleccionado = null;

    protected $listeners = ['delete'];

    /* =========================
     *  CICLO DE VIDA
     * ========================= */
    public function updatingSearch()
    {
        $this->resetPage();
    }

    /* =========================
     *  CRUD
     * ========================= */
    public function delete($equipoId)
    {
        Equipos::whereKey($equipoId)->delete();

        $this->dispatch('swal:success', [
            'title' => '¡Eliminado!',
            'text' => 'El equipo ha sido eliminado.',
            'icon' => 'success'
        ]);
    }

    public function actualizarEstado($equipoId, $estado)
    {
        if ($estado) {
            Equipos::whereKey($equipoId)->update([
                'estado' => 1,
                'observacion' => null
            ]);
            return;
        }

        // Desactivar → abrir modal
        $this->equipoIdSeleccionado = $equipoId;
        $this->observacion = '';
        $this->openModal = true;
    }

    public function confirmarDesactivacion()
    {
        $this->validate([
            'observacion' => 'required|string|min:5',
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

    /* =========================
     *  EXCEL
     * ========================= */
    public function generarExcelEquipos()
    {
        try {
            $equipos = Equipos::with(['users:id,name', 'cuadrilla:id,cua_nombre'])->get();

            $spreadsheet = IOFactory::load(public_path('templates/formatoStockEquipos.xlsx'));
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

            $fileName = 'reporteEquiposStock_' . now()->format('Y-m-d') . '.xlsx';
            $path = storage_path("app/public/{$fileName}");

            (new Xlsx($spreadsheet))->save($path);

            return response()->download($path)->deleteFileAfterSend();

        } catch (\Throwable $e) {
            $this->dispatch('swal', [
                'icon' => 'error',
                'title' => 'Error',
                'text' => 'No se pudo generar el Excel',
            ]);
        }
    }

    private function obtenerAsignacion($equipo): string
    {
        if ($equipo->estado == 0) return 'Equipo con defecto';
        if ($equipo->users->isNotEmpty()) return $equipo->users->first()->name;
        if ($equipo->cuadrilla->isNotEmpty()) return $equipo->cuadrilla->first()->cua_nombre;

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

    /* =========================
     *  RENDER
     * ========================= */
    public function render()
    {
        $search = "%{$this->search}%";

        $equipos = Equipos::query()
            ->where(function ($q) use ($search) {
                $q->where('nombre', 'like', $search)
                  ->orWhere('serie', 'like', $search);
            })
            ->orWhereHas('users', fn ($q) => $q->where('name', 'like', $search))
            ->orWhereHas('cuadrilla', fn ($q) => $q->where('cua_nombre', 'like', $search))
            ->with(['users:id,name', 'cuadrilla:id,cua_nombre'])
            ->latest()
            ->paginate(40);

        return view('livewire.equipos-table', compact('equipos'));
    }
}

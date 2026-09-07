<?php

namespace App\Livewire;

use App\Models\Cuadrilla;
use App\Models\Report;
use App\Models\ReporteRecargaDetalle;
use Illuminate\Support\Facades\DB;
use Livewire\Component;

class CreateReportRecargas extends Component
{
    public $seleccionadas = []; 
    public $valorRecarga = 10.50;
    public $search = '';

    // Método helper para reutilizar la consulta con buscador y filtros
    private function getCuadrillasQuery()
    {
        return Cuadrilla::whereHas('equipos', function ($query) {
                $query->where('tipo_equipo_id', 4);
            })
            ->when($this->search, function ($query) {
                $query->where(function ($q) {
                    $q->where('cua_nombre', 'like', '%' . $this->search . '%')
                      ->orWhere('cua_ciudad', 'like', '%' . $this->search . '%')
                      ->orWhereHas('equipos', fn($qEq) => $qEq->where('serie', 'like', '%' . $this->search . '%'))
                      ->orWhereHas('users', fn($qUs) => $qUs->where('name', 'like', '%' . $this->search . '%'));
                });
            })
            ->with([
                'equipos' => function ($query) {
                    $query->where('tipo_equipo_id', 4);
                },
                'users'
            ])
            ->orderBy('cua_ciudad', 'asc');
    }

    public function seleccionarTodas()
    {
        // Selecciona todas las cuadrillas que coinciden con el filtro/búsqueda actual
        $this->seleccionadas = $this->getCuadrillasQuery()
            ->pluck('id')
            ->map(fn($id) => (string) $id)
            ->toArray();
    }

    public function deseleccionarTodas()
    {
        $this->seleccionadas = [];
    }

    public function generarReporte()
    {
        if (empty($this->seleccionadas)) {
            $this->dispatch('error', message: 'Debes seleccionar al menos una cuadrilla.');
            return;
        }

        // Transacción DB para garantizar consistencia
        DB::transaction(function () {
            $total = count($this->seleccionadas) * $this->valorRecarga;

            $reporte = Report::create([
                'mes' => now()->translatedFormat('F Y'), 
                'total' => $total,
                'fecha_registro' => now()->toDateString(), 
            ]);

            foreach ($this->seleccionadas as $cuadrillaId) {
                ReporteRecargaDetalle::create([
                    'reporte_id' => $reporte->id,
                    'cuadrilla_id' => $cuadrillaId,
                    'valor_recarga' => $this->valorRecarga,
                ]);
            }
        });

        $this->dispatch('success', message: 'Reporte generado correctamente.');
        $this->seleccionadas = [];

        session()->flash('swal', [
            'icon' => 'success',
            'title' => '¡Bien hecho!',
            'text' => 'Reporte Creado con éxito',
            'position' => 'top-end',
            'toast' => true,
            'timer' => 3000,
            'showConfirmButton' => false
        ]);

        return redirect()->route('admin.reportes.index');
    }

    public function render()
    {
        $cuadrillas = $this->getCuadrillasQuery()->get();

        return view('livewire.create-report-recargas', compact('cuadrillas'));
    }
}

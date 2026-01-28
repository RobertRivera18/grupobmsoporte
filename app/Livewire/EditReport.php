<?php

namespace App\Livewire;

use App\Models\Cuadrilla;
use App\Models\Report;
use App\Models\ReporteRecargaDetalle;
use Livewire\Component;
use Illuminate\Support\Facades\DB;

class EditReport extends Component
{
    public $reportId;
    public $report;
    public $cuadrillas = [];
    public $seleccionadas = [];
    public $valorRecarga = 10.50;

    public function mount($reportId)
    {
        $this->reportId = $reportId;
        $this->report = Report::with('detalles')->findOrFail($reportId);

        $this->cuadrillas = Cuadrilla::with('equipos')
            ->whereHas('equipos')
            ->get();

        $this->seleccionadas = $this->report->detalles->pluck('cuadrilla_id')->toArray();
    }

    public function actualizarReporte()
    {
        if (empty($this->seleccionadas)) {
            $this->dispatch('error', message: 'Debes seleccionar al menos una cuadrilla.');
            return;
        }

        DB::transaction(function () {
            $total = count($this->seleccionadas) * $this->valorRecarga;
            $this->report->update([
                'total' => $total,
                'fecha_registro' => now()->toDateString(),
            ]);

            ReporteRecargaDetalle::where('reporte_id', $this->reportId)->delete();
            foreach ($this->seleccionadas as $cuadrillaId) {
                ReporteRecargaDetalle::create([
                    'reporte_id' => $this->reportId,
                    'cuadrilla_id' => $cuadrillaId,
                    'valor_recarga' => $this->valorRecarga,
                ]);
            }
        });

        $this->dispatch('success', message: 'Reporte actualizado correctamente.');
    }

    public function render()
    {
        return view('livewire.edit-report', [
            'cuadrillas' => $this->cuadrillas,
        ]);
    }
}

<?php

namespace App\Livewire;

use App\Models\Cuadrilla;
use App\Models\Report;
use App\Models\ReporteRecargaDetalle;
use Livewire\Component;

class CreateReportRecargas extends Component
{
    public $cuadrillas = [];
    public $seleccionadas = [];
    public $valorRecarga = 10.50;

    public function mount()
    {
        $this->cuadrillas = Cuadrilla::with(['equipos', 'users'])
            ->whereHas('equipos')
            ->get();
    }


    public function generarReporte()
    {
        if (empty($this->seleccionadas)) {
            $this->dispatch('error', message: 'Debes seleccionar al menos una cuadrilla.');
            return;
        }

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
        return view('livewire.create-report-recargas', [
            'cuadrillas' => $this->cuadrillas,
        ]);
    }
}

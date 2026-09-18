<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Report;
use App\Services\ExcelRecargasService;

class ReportRecargas extends Component
{
    public $reportes;

    public function mount()
    {
        $this->reportes = Report::withCount('detalles')->get();
    }

    public function eliminarReporte($id)
    {
        $reporte = Report::findOrFail($id);
        $reporte->delete();

        $this->reportes = Report::withCount('detalles')->get(); 
        $this->dispatch('success', message: 'Reporte eliminado correctamente.');
    }

    
    public function render()
    {
        return view('livewire.report-recargas');
    }
}

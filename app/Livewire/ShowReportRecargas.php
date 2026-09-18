<?php

namespace App\Livewire;

use App\Models\Report;
use App\Models\Cuadrilla;
use Illuminate\Support\Facades\DB;
use Livewire\Component;

class ShowReportRecargas extends Component
{
    public Report $reporte;
    public $observaciones = []; 
    public $search = '';
    public $valorRecarga = 10.50;

    public function mount(Report $reporte)
    {
        $this->reporte = $reporte;
        $this->reporte->load('cuadrillas');

        foreach ($this->reporte->cuadrillas as $cuadrilla) {
            $this->observaciones[$cuadrilla->id] = optional($cuadrilla->pivot)->observacion;
        }
    }

    private function actualizarTotalReporte()
    {
        // Contamos cuántas tienen recarga_realizada = 1 en la BD para este reporte
        $totalRealizadas = $this->reporte->cuadrillas()->where('recarga_realizada', 1)->count();
        $total = $totalRealizadas * $this->valorRecarga;
        
        $this->reporte->update([
            'total' => $total,
            'fecha_registro' => $this->reporte->fecha_registro ?? now()->toDateString(),
        ]);
    }

    public function toggleRecarga($cuadrillaId)
    {
        // Verificamos el estado actual en el pivote
        $cuadrillaActual = $this->reporte->cuadrillas()->where('cuadrilla_id', $cuadrillaId)->first();
        $estadoActual = $cuadrillaActual ? $cuadrillaActual->pivot->recarga_realizada : 0;
        $nuevoEstado = $estadoActual ? 0 : 1;

        DB::transaction(function () use ($cuadrillaId, $nuevoEstado) {
            $this->reporte->cuadrillas()->syncWithoutDetaching([
                $cuadrillaId => [
                    'valor_recarga'     => $this->valorRecarga,
                    'observacion'       => $this->observaciones[$cuadrillaId] ?? null,
                    'recarga_realizada' => $nuevoEstado,
                ]
            ]);

            $this->actualizarTotalReporte();
        });

        // Recargamos la relación para refrescar la vista al instante
        $this->reporte->load('cuadrillas');
        session()->flash('success', 'Estado actualizado correctamente.');
    }

    public function render()
    {
        // 1. IDs de las cuadrillas que ya tienen recarga realizada (true) en este reporte
        $idsRealizadas = $this->reporte->cuadrillas()
            ->where('recarga_realizada', 1)
            ->pluck('cuadrillas.id');

        // 2. Consulta para PENDIENTES (las que NO están marcadas como realizadas)
        $queryPendientes = Cuadrilla::with(['equipos', 'users'])
            ->whereHas('equipos')
            ->whereNotIn('id', $idsRealizadas);

        if (!empty($this->search)) {
            $queryPendientes->where(function($q) {
                $q->where('cua_nombre', 'like', '%' . $this->search . '%')
                  ->orWhere('cua_ciudad', 'like', '%' . $this->search . '%')
                  ->orWhereHas('equipos', fn($eq) => $eq->where('serie', 'like', '%' . $this->search . '%'))
                  ->orWhereHas('users', fn($us) => $us->where('name', 'like', '%' . $this->search . '%'));
            });
        }

        // 3. Consulta para REALIZADAS (las que SÍ están marcadas con true en este reporte)
        $queryRealizadas = Cuadrilla::with(['equipos', 'users'])
            ->whereIn('id', $idsRealizadas);

        if (!empty($this->search)) {
            $queryRealizadas->where(function($q) {
                $q->where('cua_nombre', 'like', '%' . $this->search . '%')
                  ->orWhere('cua_ciudad', 'like', '%' . $this->search . '%')
                  ->orWhereHas('equipos', fn($eq) => $eq->where('serie', 'like', '%' . $this->search . '%'))
                  ->orWhereHas('users', fn($us) => $us->where('name', 'like', '%' . $this->search . '%'));
            });
        }

        return view('livewire.show-report-recargas', [
            'cuadrillasPendientes' => $queryPendientes->get(),
            'cuadrillasRealizadas' => $queryRealizadas->get(),
            'totalRealizadasCount' => count($idsRealizadas),
        ]);
    }
}
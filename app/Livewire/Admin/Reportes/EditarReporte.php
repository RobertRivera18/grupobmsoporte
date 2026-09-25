<?php

namespace App\Livewire\Admin\Reportes;

use App\Models\Cuadrilla;
use App\Models\Report;
use App\Services\ExcelRecargasService;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class EditarReporte extends Component
{
    public Report $reporte;
    public string $search = '';
    public array $seleccionadas = [];
    public array $observaciones = [];
    public string $rol = '';

    public function mount(Report $reporte)
    {
        $this->reporte = $reporte;
        $this->rol = Auth::user()->getRoleNames()->first() ?? '';
        $this->reporte->load('cuadrillas', 'detalles');
        $this->seleccionadas = $this->reporte->cuadrillas->pluck('id')->map(fn($id) => (int)$id)->toArray();
        foreach ($this->reporte->detalles as $detalle) {
            $this->observaciones[$detalle->cuadrilla_id] = $detalle->observacion;
        }
    }


    public function generarExcel(ExcelRecargasService $service)
    {
        if (empty($this->seleccionadas)) {
            $this->dispatch('swal', [
                'icon'  => 'warning',
                'title' => 'Atención',
                'text'  => 'Debe seleccionar al menos una cuadrilla para generar el Excel.'
            ]);
            return;
        }

        try {
            return $service->generar($this->seleccionadas);
        } catch (\Throwable $e) {
            report($e);

            $this->dispatch('swal', [
                'icon'  => 'error',
                'title' => 'Error',
                'text'  => 'No fue posible generar el Excel: ' . $e->getMessage()
            ]);
        }
    }

    public function render()
    {
        $query = Cuadrilla::with([
            'equiposActivos' => function ($q) {
                $q->where('tipo_equipo_id', 4);
            },
            'users'
        ])
            ->whereHas('equiposActivos', function ($q) {
                $q->where('tipo_equipo_id', 4);
            });

        if ($this->rol === 'operador1') {
            $query->where('cua_ciudad', 1);
        } elseif ($this->rol === 'operador2') {
            $query->where('cua_ciudad', 2);
        }

        if (!empty(trim($this->search))) {
            $term = '%' . trim($this->search) . '%';
            $query->where(function ($q) use ($term) {
                $q->where('cua_nombre', 'like', $term)
                    ->orWhereHas('usersActivos', function ($u) use ($term) {
                        $u->where('name', 'like', $term);
                    })
                    ->orWhereHas('equiposActivos', function ($e) use ($term) {
                        $e->where('serie', 'like', $term);
                    });
            });
        }

        $cuadrillas = $query->get();
        return view('livewire.admin.reportes.editar-reporte', [
            'cuadrillas' => $cuadrillas
        ]);
    }
}

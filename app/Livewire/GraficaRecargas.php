<?php

namespace App\Livewire;

use App\Models\Report;
use Livewire\Component;

class GraficaRecargas extends Component
{
    public function render()
    {
        // Traer los últimos 6 reportes ordenados por ID ascendente
        $reportes = Report::select('mes', 'total')
            ->orderBy('id', 'asc')
            ->take(6)
            ->get();

        // Datos para el gráfico
        $labels = $reportes->pluck('mes');
        $totales = $reportes->pluck('total');

        return view('livewire.grafica-recargas', compact('labels', 'totales'));
    }
}

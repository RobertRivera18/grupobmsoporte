<?php

namespace App\Livewire;

use App\Models\IndicadorAnio;
use Livewire\Component;

class GraficaIndicador extends Component
{
    public IndicadorAnio $anio;

    protected $listeners = ['update-grafica' => 'actualizarGrafica'];

    public $labels = [];
    public $valores = [];
    public $metaBase = 0;

    public function mount(IndicadorAnio $anio)
    {
        $this->anio = $anio;
        $this->cargarDatos();
    }

    public function cargarDatos()
    {

        $periodos = match ($this->anio->indicador->frecuencia_id) {
            1 => ['Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio', 'Julio', 'Agosto', 'Septiembre', 'Octubre', 'Noviembre', 'Diciembre'],
            2 => ['1er Trimestre', '2do Trimestre', '3er Trimestre', '4to Trimestre'],
            3 => ['1er Semestre', '2do Semestre'],
            4 => ['Anual'],
            default => [],
        };

        $this->labels = $periodos;

        $this->valores = [];

        foreach ($this->anio->seguimientos()->orderBy('mes')->get() as $seg) {
            $this->valores[] = $seg->valor;
        }

        // Llenar nulos si faltan períodos
        while (count($this->valores) < count($this->labels)) {
            $this->valores[] = null;
        }

        $this->metaBase = $this->anio->meta;
    }

    public function actualizarGrafica()
    {
        $this->anio->refresh();
        $this->cargarDatos();
    }

    public function render()
    {
        $frecuencia = $this->anio->indicador->frecuencia->nombre;

        return view('livewire.grafica-indicador', compact('frecuencia'));
    }
}

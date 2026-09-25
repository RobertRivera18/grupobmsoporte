<?php

namespace App\Livewire;

use App\Models\Indicador;
use App\Models\IndicadorAnio;
use Livewire\Component;
use Livewire\Attributes\On; // Importante para Livewire 3

class SeguimientoIndicadores extends Component
{
    public $indicador;

    public $nuevoAnioVisible = false;
    public $anio;
    public $meta;
    public $resultado_obtenido;
    public $ultima_revision;
    public $observacion_anio;

    public $valores = [];
    public $observacion;

    public function mount(Indicador $indicador)
    {
        $this->indicador = $indicador;
        $this->observacion = $indicador->observacion;
    }

    /**
     * Se ejecuta automáticamente cuando el hijo dispara 'resultadoActualizado'
     */
    #[On('resultadoActualizado')]
    public function refrescarIndicador()
    {
        $this->indicador->refresh();
    }

    public function mostrarFormularioNuevoAnio()
    {
        $this->resetFormularioAnio();
        $this->nuevoAnioVisible = true;
    }

    private function resetFormularioAnio()
    {
        $this->anio = date('Y');
        $this->meta = null;
        $this->resultado_obtenido = null;
        $this->ultima_revision = null;
        $this->observacion_anio = null;
    }

    public function crearAnio()
    {
        $this->validate([
            'anio' => 'required|integer',
            'meta' => 'nullable|numeric',
            'resultado_obtenido' => 'nullable|numeric',
            'ultima_revision' => 'nullable|date',
            'observacion_anio' => 'nullable|string',
        ]);

        IndicadorAnio::create([
            'indicador_id' => $this->indicador->id,
            'anio' => $this->anio,
            'meta' => $this->meta,
            'resultado_obtenido' => $this->resultado_obtenido,
            'ultima_revision' => $this->ultima_revision,
            'observacion' => $this->observacion_anio,
        ]);

        $this->indicador->refresh();
        $this->nuevoAnioVisible = false;
        session()->flash('message', 'Año registrado correctamente.');
    }

    public function evaluarCumplimiento(IndicadorAnio $anioItem): ?array
    {
        if (is_null($anioItem->meta) || is_null($anioItem->resultado_obtenido)) {
            return null;
        }

        $sentido = $this->indicador->sentido ?? 'ascendente';
        $meta = (float) $anioItem->meta;
        $resultado = (float) $anioItem->resultado_obtenido;
        $diferencia = $resultado - $meta;

        if ($sentido === 'descendente') {
            $cumplido = $resultado <= $meta;
            return [
                'cumplido' => $cumplido,
                'mensaje' => $cumplido ? 'Meta cumplida' : 'Exceso sobre meta',
                'subtexto' => ($diferencia <= 0 ? '' : '+') . number_format($diferencia, 2) . ' respecto a meta',
                'icono' => $cumplido ? 'fas fa-arrow-down' : 'fas fa-arrow-up',
            ];
        }

        if ($sentido === 'mantenimiento') {
            $cumplido = abs($diferencia) < 0.001;
            return [
                'cumplido' => $cumplido,
                'mensaje' => $cumplido ? 'En rango / Manteniéndose' : 'Desviado de la meta',
                'subtexto' => ($diferencia > 0 ? '+' : '') . number_format($diferencia, 2) . ' de variación',
                'icono' => $cumplido ? 'fas fa-check-circle' : 'fas fa-exclamation-circle',
            ];
        }

        $cumplido = $resultado >= $meta;
        return [
            'cumplido' => $cumplido,
            'mensaje' => $cumplido ? 'Meta superada' : 'Por debajo de meta',
            'subtexto' => ($diferencia >= 0 ? '+' : '') . number_format($diferencia, 2) . ' de diferencia',
            'icono' => $cumplido ? 'fas fa-arrow-up' : 'fas fa-arrow-down',
        ];
    }

    public function render()
    {
        return view('livewire.seguimiento-indicadores');
    }
}
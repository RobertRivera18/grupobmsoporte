<?php

namespace App\Livewire;

use App\Models\Indicador;
use App\Models\IndicadorAnio;
use Livewire\Component;

class SeguimientoIndicadores extends Component
{
    public $indicador;

    // 📌 Formulario para crear nuevo año
    public $nuevoAnioVisible = false;
    public $anio;
    public $meta;
    public $resultado_obtenido;
    public $ultima_revision;
    public $observacion_anio;

    // Valores mensuales (esto ya lo tenías)
    public $valores = [];
    public $observacion;

    public function mount(Indicador $indicador)
    {
        $this->indicador = $indicador;
        $this->observacion = $indicador->observacion;
    }

    // 📌 Mostrar/ocultar formulario
    public function mostrarFormularioNuevoAnio()
    {
        $this->resetFormularioAnio();
        $this->nuevoAnioVisible = true;
    }

    // 📌 Reset de campos
    private function resetFormularioAnio()
    {
        $this->anio = date('Y');
        $this->meta = null;
        $this->resultado_obtenido = null;
        $this->ultima_revision = null;
        $this->observacion_anio = null;
    }

    // 📌 Crear registro en indicadores_anio
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

        // Recargar relaciones
        $this->indicador->refresh();

        $this->nuevoAnioVisible = false;
        session()->flash('message', 'Año registrado correctamente.');
    }

    public function render()
    {
        return view('livewire.seguimiento-indicadores');
    }
}

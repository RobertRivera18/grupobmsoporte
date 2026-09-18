<?php

namespace App\Livewire;

use App\Models\Area;
use App\Models\FrecuenciaIndicador;
use App\Models\Indicador;
use Livewire\Component;

class IndicadoresIndex extends Component
{
    public Area $area;
    public $indicadores;
    public $editingIndicadorId = null;

    public $nombre, $forma_calculo, $frecuencia;
    public $frecuencia_id;
    public $frecuencias;

    protected $rules = [
        'nombre' => 'required|string|max:255',
        'forma_calculo' => 'nullable|string',
        'frecuencia' => 'nullable|string|max:255',
        'frecuencia_id' => 'required|exists:frecuencias_indicadores,id',

    ];

    public function mount(Area $area)
    {
        $this->area = $area;
        $this->refreshIndicadores();
        $this->frecuencias = FrecuenciaIndicador::all();
    }


    private function refreshIndicadores()
    {
        $this->indicadores = $this->area
            ->indicadores()
            ->orderBy('id', 'asc')
            ->get();
    }

    /* ===========================================
       ELIMINAR INDICADOR
    =========================================== */
    public function destroyIndicador($indicadorId)
    {
        $indicador = $this->area->indicadores()->find($indicadorId);

        if (!$indicador) {
            session()->flash('error', 'No se encontró el indicador.');
            return;
        }

        $indicador->delete();

        $this->refreshIndicadores();

        session()->flash('message', 'Indicador eliminado correctamente.');
    }

    /* ===========================================
       CREAR INDICADOR
    =========================================== */
    public function store()
    {
        $this->validate();

        $indicador = $this->area->indicadores()->create([
            'nombre'         => $this->nombre,
            'responsable_id' => auth()->id(),
            'forma_calculo' => $this->forma_calculo,
            'frecuencia_id' => $this->frecuencia_id,

        ]);

        $this->resetForm();
        $this->refreshIndicadores();

        $this->dispatch('close-form');
        session()->flash('message', 'Indicador creado correctamente.');
    }



    public function editIndicador($id)
    {
        $indicador = $this->area->indicadores()->find($id);

        if (!$indicador) {
            session()->flash('error', 'Indicador no encontrado.');
            return;
        }

        $this->editingIndicadorId = $indicador->id;

        $this->nombre = $indicador->nombre;
        $this->forma_calculo = $indicador->forma_calculo;
        $this->frecuencia_id = $indicador->frecuencia_id;
    }

 
    public function update()
    {
        $this->validate();

        if (!$this->editingIndicadorId) {
            session()->flash('error', 'No se seleccionó ningún indicador para editar.');
            return;
        }

        $indicador = $this->area->indicadores()->find($this->editingIndicadorId);

        if (!$indicador) {
            session()->flash('error', 'El indicador no existe.');
            return;
        }

        $indicador->update([
            'nombre'                => $this->nombre,
            'forma_calculo'         => $this->forma_calculo,
            'frecuencia_id' => $this->frecuencia_id,

        ]);

        $this->resetForm();
        $this->refreshIndicadores();
        $this->dispatch('close-form');

        session()->flash('message', 'Indicador actualizado correctamente.');
    }

    public function resetForm()
    {
        $this->reset([
            'editingIndicadorId',
            'nombre',
            'forma_calculo',
            'frecuencia_id',
        ]);
    }

    public function render()
    {

        return view('livewire.indicadores-index', [
            'indicadores' => $this->indicadores,
        ]);
    }
}

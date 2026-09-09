<?php

namespace App\Livewire;

use App\Models\Cuadrilla;
use App\Models\Grupo;
use App\Models\InventarioControl;
use App\Models\InventarioMaterialControl;
use App\Models\Tecnologia;
use App\Models\TipoActividad;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;
use Livewire\Component;
use Livewire\Attributes\Computed;

class InventarioReporte extends Component
{
    public $grupo_id = '';
    public $tecnologia_id = '';
    public $cuadrilla_id = '';
    public $fecha_inventario;
    public $observaciones = ''; // Usaremos esta misma variable para el modal
    public $tipo_actividad_id = '';
    public $stocks = [];
    public $mostrarConfirmacion = false;
    public $materialesConfirmacion = [];

    public function mount()
    {
        $this->fecha_inventario = now()->format('Y-m-d');
    }

    public function updatedTecnologiaId($value)
    {
        $this->stocks = [];
        $this->cuadrilla_id = '';

        if ($value) {
            foreach ($this->materiales as $material) {
                $this->stocks[$material->id] = 0;
            }
        }
    }

    public function updatedGrupoId()
    {
        $this->cuadrilla_id = '';
    }

    #[Computed]
    public function materiales()
    {
        if (!$this->tecnologia_id) {
            return collect();
        }

        return Tecnologia::find($this->tecnologia_id)?->materiales ?? collect();
    }

    protected function rules()
    {
        return [
            'grupo_id'          => 'required',
            'tecnologia_id'     => 'required',
            'cuadrilla_id'      => 'required',
            'tipo_actividad_id' => 'required',
            'fecha_inventario'  => 'required|date|before_or_equal:today',
            'stocks.*'          => 'required|numeric|min:0',
        ];
    }

    protected function messages()
    {
        return [
            'grupo_id.required'              => 'Debe seleccionar un grupo.',
            'tecnologia_id.required'          => 'Debe seleccionar una tecnología.',
            'cuadrilla_id.required'           => 'Debe seleccionar una cuadrilla.',
            'fecha_inventario.required'       => 'Debe seleccionar la fecha.',
            'tipo_actividad_id.required'      => 'Debe seleccionar un tipo de actividad.',
            'fecha_inventario.before_or_equal' => 'La fecha del inventario no puede ser mayor a la fecha de hoy.',
            'stocks.*.required'               => 'Todas las cantidades de stock deben estar completas.',
            'stocks.*.numeric'                => 'El stock ingresado debe ser un número válido.',
            'stocks.*.min'                    => 'El stock no puede ser un número negativo.',
        ];
    }

    private function mostrarErroresValidacion(ValidationException $e)
    {
        $listaErrores = '<ul style="text-align: left; margin-top: 10px; font-size: 0.9em; line-height: 1.5;">';
        foreach ($e->errors() as $campo => $mensajes) {
            foreach ($mensajes as $mensaje) {
                $listaErrores .= '<li>• ' . e($mensaje) . '</li>';
            }
        }
        $listaErrores .= '</ul>';

        $this->dispatch('swal', [
            'icon'  => 'error',
            'title' => 'Atención: Revise el Formulario',
            'html'  => $listaErrores,
        ]);
    }

    public function confirmarGuardar()
    {
        try {
            $this->validate(); // Valida los campos básicos (sin observaciones)
        } catch (ValidationException $e) {
            $this->mostrarErroresValidacion($e);
            throw $e;
        }

        $this->observaciones = ''; 
        $this->materialesConfirmacion = [];

        foreach ($this->materiales as $material) {
            $cantidad = $this->stocks[$material->id] ?? 0;

            if ($cantidad > 0) {
                $this->materialesConfirmacion[] = [
                    'material_id' => $material->id,
                    'codigo'      => $material->codigo,
                    'descripcion' => $material->descripcion,
                    'cantidad'    => $cantidad,
                ];
            }
        }

        $this->mostrarConfirmacion = true; // Abre el modal de confirmación
    }

    public function cancelarConfirmacion()
    {
        $this->mostrarConfirmacion = false;
    }

    public function guardar()
    {
        try {
            $this->validate([
                'observaciones' => 'required|string|min:8',
            ], [
                'observaciones.required' => 'Debe ingresar el numero de Orden.',
                'observaciones.min'      => 'Las observaciones deben tener al menos 8 caracteres.',
            ]);
        } catch (ValidationException $e) {
            $this->mostrarErroresValidacion($e);
            throw $e;
        }

        DB::transaction(function () {
            $inventario = InventarioControl::create([
                'grupo_id'            => $this->grupo_id,
                'tecnologia_id'       => $this->tecnologia_id,
                'cuadrilla_id'        => $this->cuadrilla_id,
                'tipo_actividad_id'   => $this->tipo_actividad_id,
                'fecha_inventario'    => $this->fecha_inventario,
                'observaciones'       => $this->observaciones,
            ]);

            foreach ($this->stocks as $material_id => $cantidad) {
                InventarioMaterialControl::create([
                    'inventario_control_id' => $inventario->id,
                    'material_id'           => $material_id,
                    'stock_final'           => $cantidad,
                ]);
            }
        });

        $this->dispatch('swal:success', [
            'icon'  => 'success',
            'title' => '¡Éxito!',
            'text'  => 'Inventario reportado exitosamente.'
        ]);

        $this->reset([
            'grupo_id',
            'tecnologia_id',
            'cuadrilla_id',
            'tipo_actividad_id',
            'observaciones',
            'stocks',
            'materialesConfirmacion',
            'mostrarConfirmacion',
        ]);

        $this->fecha_inventario = now()->format('Y-m-d');
    }

    public function render()
    {
        $query = Cuadrilla::query();
        if ($this->grupo_id) {
            $query->where('grupo_id', $this->grupo_id);
        }

        if ($this->tecnologia_id) {
            $query->whereHas('tecnologias', function ($q) {
                $q->where('tecnologias.id', $this->tecnologia_id);
            });
        }

        $cuadrillas = ($this->grupo_id || $this->tecnologia_id)
            ? $query->get()
            : collect();

        return view('livewire.inventario-reporte', [
            'grupos'            => Grupo::all(),
            'tecnologias'       => Tecnologia::all(),
            'cuadrillas'        => $cuadrillas,
            'tipoActividades'   => TipoActividad::all(),
        ])->layout('layouts.app');
    }
}
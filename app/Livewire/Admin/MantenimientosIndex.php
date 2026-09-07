<?php

namespace App\Livewire\Admin;

use App\Models\RegistroMantenimiento;
use App\Models\RegistroMantenimientoDetalle;
use App\Models\TipoServicio;
use App\Models\Vehiculo;
use Illuminate\Support\Facades\DB;
use Livewire\Component;

class MantenimientosIndex extends Component
{
    public Vehiculo $vehiculo;

    public $open = false;

    // Cabecera del mantenimiento
    public $fecha;
    public $kilometraje;
    public $observaciones;

    // Catálogo de servicios
    public $tiposServicios = [];

    // Servicios seleccionados
    public $serviciosSeleccionados = [];

    // Observación individual de cada servicio
    public $descripcionServicios = [];

    protected function rules()
    {
        return [
            'fecha' => 'required|date',
            'kilometraje' => 'required|integer|min:0',
            'observaciones' => 'nullable|string|max:1000',
            'serviciosSeleccionados' => 'required|array|min:1',
            'serviciosSeleccionados.*' => 'exists:tipo_servicios,id',
        ];
    }

    protected $messages = [
        'fecha.required' => 'La fecha es obligatoria.',
        'kilometraje.required' => 'Ingrese el kilometraje.',
        'serviciosSeleccionados.required' => 'Seleccione al menos un servicio.',
        'serviciosSeleccionados.min' => 'Seleccione al menos un servicio.',
    ];

    public function mount(Vehiculo $vehiculo)
    {
        $this->vehiculo = $vehiculo;

        $this->fecha = now()->format('Y-m-d');

        $this->tiposServicios = TipoServicio::where('activo', 1)
            ->orderBy('nombre')
            ->get();

        $this->kilometraje = '';
        $this->observaciones = '';
    }

    public function nuevo()
    {
        $this->resetValidation();

        $this->fecha = now()->format('Y-m-d');
        $this->kilometraje = '';
        $this->observaciones = '';
        $this->serviciosSeleccionados = [];
        $this->descripcionServicios = [];

        $this->open = true;
    }

    public function guardar()
    {
        $this->validate();

        DB::transaction(function () {

            $registro = RegistroMantenimiento::create([
                'vehiculo_id'    => $this->vehiculo->id,
                'fecha'          => $this->fecha,
                'kilometraje'    => $this->kilometraje,
                'observaciones'  => $this->observaciones,
            ]);

            foreach ($this->serviciosSeleccionados as $servicio) {

                RegistroMantenimientoDetalle::create([
                    'registro_mantenimiento_id' => $registro->id,
                    'tipo_servicio_id'          => $servicio,
                    'descripcion'               => $this->descripcionServicios[$servicio] ?? null,
                ]);
            }

        });

        $this->open = false;

        session()->flash('success', 'Mantenimiento registrado correctamente.');
    }

    public function render()
    {
        $mantenimientos = RegistroMantenimiento::with('detalles.tipoServicio')
            ->where('vehiculo_id', $this->vehiculo->id)
            ->latest('fecha')
            ->get();

        return view('livewire.admin.mantenimientos-index', [
            'mantenimientos' => $mantenimientos
        ]);
    }
}
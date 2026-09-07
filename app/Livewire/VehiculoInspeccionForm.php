<?php

namespace App\Livewire;

use Illuminate\Support\Facades\DB;
use Livewire\Component;
use Livewire\WithFileUploads;
use App\Models\Vehiculo;
use App\Models\VehiculoInspeccion;
use App\Models\VehiculoInspeccionFoto;
use App\Models\User;

class VehiculoInspeccionForm extends Component
{
    use WithFileUploads;

    public Vehiculo $vehiculo;
    public $fecha;
    public $tecnico_encargado_id; // Corregido el nombre según la nueva migración
    public $kilometraje;
    public $tipo_equipo = 'propio';
    public $observaciones;
    public $choques_golpes;
    public $revisado_por_nombre;
    public $tecnico_responsable_nombre;
    public $recomendaciones_mantenimiento;

    public $fotos_subidas = [];

    public $fotos_camara = [];
    public $fotos_galeria = [];

    public $checklist = [
        'luces' => ['delanteras' => null, 'traseras' => null, 'freno' => null, 'direccionales' => null, 'reversa' => null, 'placa' => null],
        'aceites' => ['transmision' => null, 'motor' => null, 'direccion' => null, 'refrigerante' => null, 'agua_radiador' => null, 'liquido_freno' => null, 'agua_parabrisas' => null],
        'indicadores' => ['combustible' => null, 'temperatura' => null, 'presion_aceite' => null, 'velocimetro' => null],
        'frenos' => ['pastillas' => null, 'freno_mano' => null],
        'llantas_direccion' => ['direccion' => null, 'suspension_delantera' => null, 'suspension_trasera' => null, 'estado_llantas' => null],
        'estetitca' => ['plumas' => null, 'retrovisores' => null, 'vidrio_delantero' => null, 'vidrio_trasero' => null, 'vidrios_laterales' => null, 'pintura' => null, 'carroceria' => null, 'radio' => null, 'limpieza' => null],
        'equipos' => ['cono_seguridad' => null, 'gato_hidraulico' => null, 'extintor' => null, 'llave_rueda' => null, 'repuesto' => null, 'tapa_combustible' => null],
        'otros' => ['bandas_motor' => null, 'bocina' => null, 'alarma' => null, 'aire_acondicionado' => null],
    ];

    public function mount(Vehiculo $vehiculo)
    {
        $this->vehiculo = $vehiculo;
        $this->fecha = now()->format('Y-m-d');
    }


    public function updatedFotosCamara()
    {
        $this->acumularFotos($this->fotos_camara);
        $this->fotos_camara = [];
    }

   
    public function updatedFotosGaleria()
    {
        $this->acumularFotos($this->fotos_galeria);
        $this->fotos_galeria = []; 
    }

 
    private function acumularFotos($nuevasFotos)
    {
        if (empty($nuevasFotos)) {
            return;
        }

        $espacioDisponible = 10 - count($this->fotos_subidas);

        if ($espacioDisponible <= 0) {
            $this->addError('fotos_subidas', 'Ya alcanzaste el máximo de 10 fotos.');
            return;
        }

        $nuevasFotos = array_slice($nuevasFotos, 0, $espacioDisponible);

        foreach ($nuevasFotos as $foto) {
            $this->fotos_subidas[] = $foto;
        }

        $this->resetErrorBag('fotos_subidas');
    }

    public function guardarInspeccion()
    {
        $this->validate([
            'fecha' => 'required|date',
            'tecnico_encargado_id' => 'required|exists:users,id',
            'kilometraje' => 'required|integer|min:0',
            'tipo_equipo' => 'required|in:propio,alquilado',
            'fotos_subidas' => 'nullable|array|max:10',
            'fotos_subidas.*' => 'image|mimes:png,jpg,jpeg|max:4096',
        ]);

        foreach ($this->checklist as $categoria => $items) {
            foreach ($items as $item => $valor) {
                if ($valor === null || $valor === '') {
                    $this->addError('checklist', "Falta marcar '{$item}' en '{$categoria}'.");
                    return;
                }
            }
        }

        $checklistNormalizado = collect($this->checklist)
            ->map(fn($items) => collect($items)->map(fn($valor) => (bool) (int) $valor)->toArray())
            ->toArray();

        DB::transaction(function () use ($checklistNormalizado) {
            $inspeccion = VehiculoInspeccion::create([
                'vehiculo_id' => $this->vehiculo->id,
                'fecha' => $this->fecha,
                'tecnico_encargado_id' => $this->tecnico_encargado_id,
                'kilometraje' => $this->kilometraje,
                'tipo_equipo' => $this->tipo_equipo,
                'checklist' => $checklistNormalizado,
                'observaciones' => $this->observaciones,
                'choques_golpes' => $this->choques_golpes,
                'revisado_por_nombre' => $this->revisado_por_nombre,
                'tecnico_responsable_nombre' => $this->tecnico_responsable_nombre,
                'recomendaciones_mantenimiento' => $this->recomendaciones_mantenimiento,
            ]);

            foreach ($this->fotos_subidas as $foto) {
                $ruta = $foto->store('inspecciones', 'public');

                VehiculoInspeccionFoto::create([
                    'vehiculo_inspeccion_id' => $inspeccion->id,
                    'ruta_foto' => $ruta,
                ]);
            }
        });

        session()->flash('message', 'Inspección y evidencias fotográficas guardadas con éxito.');
        return redirect()->route('admin.revisiones.index');
    }

    public function removerFoto($index)
    {
        array_splice($this->fotos_subidas, $index, 1);
    }

    public function render()
    {
        $choferes = User::role('Chofer')->orderBy('name')->get();

        return view('livewire.vehiculo-inspeccion-form', compact('choferes'));
    }
}
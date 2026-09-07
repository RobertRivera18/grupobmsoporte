<?php

namespace App\Livewire;


use Livewire\Component;
use Illuminate\Support\Facades\Auth;
use App\Models\SalidaEquipo;

class SalidaEquipos extends Component
{
    public $equipos = [];
    public $equipo_id;
    public $fecha_salida_solicitada;
    public $fecha_retorno_estimada;
    public $motivo;


    protected $rules = [
        'equipo_id' => 'required|exists:equipos,id',
    ];

    public function mount()
    {
        // Cargar equipos del usuario autenticado (optimizado)
        $this->equipos = Auth::user()
            ->equipos()
            ->select('equipos.id', 'equipos.nombre', 'equipos.marca', 'equipos.modelo', 'equipos.serie')
            ->get();
    }

    public function solicitarSalida()
    {
        $this->validate();

        // Evitar duplicados
        $existe = \App\Models\SalidaEquipo::where('equipo_id', $this->equipo_id)
            ->where('estado', 'pendiente')
            ->exists();

        if ($existe) {
            session()->flash('error', 'Este equipo ya tiene una solicitud pendiente.');
            return;
        }

        \App\Models\SalidaEquipo::create([
            'usuario_id' => auth()->id(),
            'equipo_id' => $this->equipo_id,
            'fecha_salida_solicitada' => $this->fecha_salida_solicitada,
            'fecha_retorno_estimada' => $this->fecha_retorno_estimada,
            'motivo' => $this->motivo,
            'estado' => 'pendiente', // automático
        ]);

        $this->reset([
            'equipo_id',
            'fecha_salida_solicitada',
            'fecha_retorno_estimada',
            'motivo'
        ]);

        session()->flash('message', 'Solicitud enviada correctamente.');
    }

    public function render()
    {
        return view('livewire.salida-equipos');
    }
}

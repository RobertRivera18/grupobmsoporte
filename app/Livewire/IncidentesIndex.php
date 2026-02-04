<?php

namespace App\Livewire;

use App\Models\Incidencia;
use App\Models\User;
use Livewire\Component;
use Livewire\WithFileUploads;

class IncidentesIndex extends Component
{
    use WithFileUploads;
    public $cedula;
    public $nombre_usuario;
    public $usuarioEncontrado = null;

    public $nombre; // nombre de la incidencia
    public $detalle;
    public $fecha;
    public $archivos = [];

    // =============================
    // BUSCAR USUARIO POR CÉDULA
    // =============================
    public function buscarUsuario()
    {
        $this->validate([
            'cedula' => 'required',
        ]);

        $usuario = User::where('cedula', $this->cedula)->first();

        if ($usuario) {
            $this->usuarioEncontrado = $usuario;
            $this->nombre_usuario = $usuario->name;
        } else {
            $this->usuarioEncontrado = null;
            $this->nombre_usuario = null;
            $this->addError('cedula', 'No existe un usuario con esta cédula.');
        }
    }

    // =============================
    // GUARDAR INCIDENCIA
    // =============================
    public function guardarIncidencia()
    {
        $this->validate([
            'cedula'      => 'required',
            'nombre'      => 'required|string|max:255',
            'detalle'     => 'required|string',
            'fecha'       => 'required|date',
            'archivos.*'  => 'nullable|mimes:jpg,jpeg,png,pdf,docx|max:2048',
        ]);

        if (!$this->usuarioEncontrado) {
            $this->addError('cedula', 'Debe buscar y seleccionar un usuario válido.');
            return;
        }

        // 1️⃣ Guardar incidencia
        $incidencia = Incidencia::create([
            'nombre'  => $this->nombre,
            'detalle' => $this->detalle,
            'fecha'   => $this->fecha,
            'user_id' => $this->usuarioEncontrado->id,
        ]);

        // 2️⃣ Guardar archivos (SI EXISTEN)
        if (!empty($this->archivos)) {
            foreach ($this->archivos as $file) {

                // Guardar archivo físicamente
                $path = $file->store('incidentes/imagenes', 'public');


                // Guardar referencia en BD
                $incidencia->archivos()->create([
                    'archivo' => $path,
                    'tipo'    => $file->extension(),
                ]);
            }
        }

        $this->dispatch('swal', [
            'icon'  => 'success',
            'title' => 'Incidencia registrada',
            'text'  => 'La incidencia se guardó correctamente'
        ]);



        // 3️⃣ Limpiar formulario
        $this->reset([
            'cedula',
            'nombre_usuario',
            'usuarioEncontrado',
            'nombre',
            'detalle',
            'fecha',
            'archivos',
        ]);
    }


    public function render()
    {
        return view('livewire.incidentes-index');
    }
}

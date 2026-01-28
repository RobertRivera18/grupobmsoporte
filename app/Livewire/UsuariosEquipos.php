<?php

namespace App\Livewire;

use App\Models\Equipos;
use App\Models\HistorialAsignacion;
use App\Models\User;
use Livewire\Component;
use Livewire\WithPagination;
use Livewire\WithFileUploads;
use Illuminate\Support\Facades\Storage;
use PhpOffice\PhpWord\IOFactory;
use Barryvdh\DomPDF\Facade\Pdf;


class UsuariosEquipos extends Component
{
    use WithPagination, WithFileUploads;

    public $search = '';
    public $searchEquipos = '';
    public $open = false;
    public $upload = false;
    public $qr = false;
    public $userIdSeleccionado = null;
    //Paginacion de Tabla Usuarios
    public $page;
    //Paginacion de Tabla Equipos
    public $equipoPage;
    public $archivo;


    //Carga los equipos disponibles en el modal
    public function getEquiposDisponiblesProperty()
{
    return Equipos::query()
        ->where([
            ['datos', 1],
            ['estado', 1],
        ])
        ->whereDoesntHave('users')
        ->when($this->searchEquipos, function ($query) {
            $search = '%' . $this->searchEquipos . '%';
            $query->where(function ($q) use ($search) {
                $q->where('nombre', 'like', $search)
                    ->orWhere('serie', 'like', $search)
                    ->orWhere('marca', 'like', $search)
                    ->orWhere('modelo', 'like', $search);
            });
        })
        ->paginate(10, ['*'], 'equipoPage');
}


   public function generar($userId)
{
    require_once app_path('Libraries/tbs_class.php');
    require_once app_path('Libraries/tbs_plugin_opentbs.php');

    $user = User::with('equipos')->find($userId);

    if (!$user) {
        $this->dispatch('error', message: 'Usuario no encontrado.');
        return;
    }

    $TBS = new \clsTinyButStrong;
    $TBS->Plugin(TBS_INSTALL, OPENTBS_PLUGIN);

    $templatePath = public_path('templates/acta_entregaequipo.docx');
    $TBS->LoadTemplate($templatePath, OPENTBS_ALREADY_UTF8);

    $nombreCompleto = $user->name;
    $cedula = $user->cedula;
    $equipos = [];

    foreach ($user->equipos as $equipo) {
        $equipos[] = [
            'descripcion' => $equipo->nombre ?? 'N/A',
            'marca' => $equipo->marca ?? 'N/A',
            'modelo' => $equipo->modelo ?? 'N/A',
            'serie' => $equipo->serie ?? 'N/A',
        ];
    }

    while (count($equipos) < 7) {
        $equipos[] = [
            'descripcion' => 'N/A',
            'marca' => 'N/A',
            'modelo' => 'N/A',
            'serie' => 'N/A',
        ];
    }

    $TBS->MergeField('usu.nombre', $nombreCompleto);
    $TBS->MergeField('cedula', $cedula);
    $TBS->MergeField('fecha', date('d/m/Y'));

    foreach ($equipos as $index => $equipo) {
        $TBS->MergeField("equipos.descripcion_$index", $equipo['descripcion']);
        $TBS->MergeField("equipos.marca_$index", $equipo['marca']);
        $TBS->MergeField("equipos.modelo_$index", $equipo['modelo']);
        $TBS->MergeField("equipos.serie_$index", $equipo['serie']);
    }

    $fileName = 'acta_entrega_equipo_' . str_replace(' ', '_', strtolower($nombreCompleto)) . '_' . date('Ymd') . '.docx';
    $savePath = public_path('actas/entrega_equiposUsuarios/' . $fileName);

    $TBS->Show(OPENTBS_FILE, $savePath);

    // Abrir el archivo en nueva pestaña
    return response()->file($savePath, [
        'Content-Type' => 'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
        'Content-Disposition' => 'inline; filename="' . $fileName . '"'
    ]);
}




    public function uploadFile($userId)
    {
        $this->userIdSeleccionado = $userId;
        $this->upload = true;
    }

    public function guardarArchivo()
    {
        $this->validate([
    'archivo' => 'required|mimes:pdf,doc,docx,png,jpg,jpeg|max:50240',
]);


        $user = User::find($this->userIdSeleccionado);

        if (!$user) {
            $this->dispatch('error', message: 'Usuario no encontrado.');
            return;
        }

        $nombreArchivo = 'comprobante_' . $user->id . '_' . time() . '.' . $this->archivo->getClientOriginalExtension();

        $ruta = $this->archivo->storeAs('actas/entrega_equiposUsuarios', $nombreArchivo, 'public');

        $user->ruta_comprobante = 'actas/entrega_equiposUsuarios/' . $nombreArchivo;
        $user->save();

        $this->reset(['archivo', 'upload', 'userIdSeleccionado']);
        $this->dispatch('swal', [
            'icon' => 'success',
            'title' => '¡Bien hecho!',
            'text' => 'Los requerimientos se han actualizado correctamente.'
        ]);
    }
    public function agregarEquipo($userId)
    {
        $this->userIdSeleccionado = $userId;
        $this->open = true;
        $this->dispatch('openModalEquipos');
    }

    // Resetear la página al limpiar la búsqueda
    public function limpiar_page()
    {
        $this->reset('equipoPage');
        $this->resetPage('page');
    }

    // Resetear paginación al buscar
    public function updatingSearch()
    {
        $this->resetPage();
    }



    public function asignarEquipo($equipoId)
    {
        $user = User::find($this->userIdSeleccionado);

        if ($user) {
            // Relación actual
            $user->equipos()->attach($equipoId);

            // Registrar en historial
            HistorialAsignacion::create([
                'equipo_id' => $equipoId,
                'user_id' => $user->id,
                'fecha_asignacion' => now(),
            ]);

            $this->dispatch('success', message: 'Equipo asignado correctamente.');
        } else {
            $this->dispatch('error', message: 'Usuario no encontrado.');
        }

        $this->reset(['userIdSeleccionado']);
        $this->searchEquipos = '';
        $this->resetPage('equipoPage');
    }


    // Eliminar el equipo asignado de un usuario
    public function eliminarEquipo($userId, $equipoId)
    {
        $user = User::find($userId);

        if ($user) {
            // Eliminar relación
            $user->equipos()->detach($equipoId);

            // Actualizar la última asignación activa
            $asignacion = HistorialAsignacion::where('equipo_id', $equipoId)
                ->where('user_id', $userId)
                ->whereNull('fecha_desasignacion')
                ->latest()
                ->first();

            if ($asignacion) {
                $asignacion->fecha_desasignacion = now();
                $asignacion->save();
            }

            $this->dispatch('success', message: 'Equipo eliminado correctamente.');
        } else {
            $this->dispatch('error', message: 'Usuario no encontrado.');
        }
    }


    //Descargar archivo comprobante
    public function descargarArchivo($userId)
    {
        $user = User::find($userId);

        if (!$user || !$user->ruta_comprobante) {
            $this->dispatch('error', message: 'Archivo no disponible.');
            return;
        }

        $rutaAbsoluta = storage_path('app/public/' . $user->ruta_comprobante);

        if (!file_exists($rutaAbsoluta)) {
            $this->dispatch('error', message: 'Archivo no encontrado en el servidor.');
            return;
        }

        return response()->download($rutaAbsoluta);
    }


    //Funcion para generar qr
    public function generar_qr($userId)
    {
        require_once app_path('Libraries/barcode.php');

        $user = User::find($userId);

        if (!$user) {
            $this->dispatch('error', message: 'Usuario no encontrado.');
            return;
        }


        //Cambiar en produccion
        $url_qr = "http://127.0.0.1:8000/equipoInfo/{$user->id}";


        $generator = new \barcode_generator();
        $svg = $generator->render_svg("qr", $url_qr, "");


        $qrFileName = "qr_{$user->name}.svg";
        $qrStoragePath = "qrcodes/{$qrFileName}";
        $qrPublicPath = "qrcodes/{$qrFileName}";


        if (!Storage::exists('qrcodes')) {
            Storage::makeDirectory('qrcodes');
        }


        Storage::put($qrStoragePath, $svg);

        $user->qr_codigo = $qrPublicPath;
        $user->save();

        // Disparar notificación al frontend
        $this->dispatch('swal', [
            'icon' => 'success',
            'title' => 'Código QR generado',
            'text' => 'El QR fue generado correctamente.',
        ]);
    }


    public function ver_qr($userId)
    {


        // Carga fresca del usuario desde la base de datos
        $this->userIdSeleccionado = User::find($userId);

        if ($this->userIdSeleccionado && $this->userIdSeleccionado->qr_codigo) {
            $this->qr = true;
        } else {
            $this->qr = true; 
            session()->flash('message', 'Este usuario no tiene código QR.');
        }
    }


    // Renderizar el componente
    public function render()
    {
        $users = User::where('name', 'like', '%' . $this->search . '%')
            ->with('equipos')
            ->paginate(10);

        return view('livewire.usuarios-equipos', [
            'users' => $users,
        ]);
    }
}

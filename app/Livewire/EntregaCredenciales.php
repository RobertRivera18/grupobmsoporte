<?php

namespace App\Livewire;

use App\Models\Actas;
use App\Models\TipoContrato;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Livewire\Component;
use Livewire\WithPagination;
use Livewire\WithFileUploads;
use Illuminate\Support\Str;
use Intervention\Image\Facades\Image;
use Intervention\Image\ImageManager;
use Intervention\Image\Drivers\Gd\Driver;




class EntregaCredenciales extends Component
{

    use WithPagination, WithFileUploads;

    public $search = '';
    public $searchColaboradores = '';
    public $UserPage;
    public $page;
    public $open = false;
    public $upload = false;
    public $actaSeleccionada = null;
    public $archivo;
    public $empresa, $tipo_sangre, $tipo_contrato_id, $imagen;
    public $colaboradorSeleccionado;
    public $modalDatosActa = false;
    public $nuevo_nombre, $nuevo_cedula;

    public $tiposContratos = [];


    protected $rules = [
        'empresa' => 'required|in:Claro,CNEL',
        'tipo_sangre' => 'required|string|max:5',
        'tipo_contrato_id' => 'required|exists:tipo_contratos,id',
        'imagen' => 'nullable|image|max:5048', // 2MB
    ];


    public function mount()
    {
        $this->tiposContratos = TipoContrato::all();
    }
    //Abre modal para seleccionar el tipo de acta a crear (Credenciales/Equipos)
    public function crearActa()
    {
        $this->open = true;
    }

    // Carga los colaboradores disponibles en el modal
    public function getColaboradoresDisponiblesProperty()
    {
        return User::where('name', 'like', '%' . $this->searchColaboradores . '%')
            ->paginate(10, ['*'], 'colaboradoresPage');
    }

    public function updatingSearch()
    {
        $this->resetPage();
    }


    // Resetear la página al limpiar la búsqueda
    public function limpiar_page()
    {
        $this->resetPage('actasPage');
        $this->resetPage('colaboradoresPage');
    }




    // public function asignarColaborador($colaboradorId)
    // {
    //     DB::beginTransaction();

    //     try {

    //         Actas::create([
    //             'user_id' => $colaboradorId,
    //             'fecha_entrega' => Carbon::now(),
    //         ]);

    //         $this->reset(['open', 'searchColaboradores', 'UserPage']);
    //         DB::commit();
    //         session()->flash('message', 'Acta creada correctamente.');
    //     } catch (\Exception $e) {
    //         DB::rollBack();
    //         session()->flash('error', 'Ocurrió un error al asignar el colaborador.');
    //     }
    //     $this->searchColaboradores = '';
    //     $this->resetPage('colaboradoresPage');
    // }

    public function crearNuevoColaborador()
    {
        $this->validate([
            'nuevo_nombre' => 'required|string|max:255',
            'nuevo_cedula' => 'required|string|max:20|unique:users,cedula',
        ]);

        try {
            // Generar base para email (nombre limpio)
            $nombreLimpio = Str::slug($this->nuevo_nombre, '.'); // ej: "juan.perez"
            $baseEmail = $nombreLimpio;
            $email = $baseEmail . '@coprbm.com';

            // Si ya existe ese email, agregar sufijo numérico hasta que sea único
            $contador = 0;
            while (\App\Models\User::where('email', $email)->exists()) {
                $contador++;
                $email = $baseEmail . $contador . '@coprbm.com';
            }

            // Crear usuario asignando atributos uno por uno (evita problemas con fillable)
            $usuario = new \App\Models\User();
            $usuario->name = $this->nuevo_nombre;
            $usuario->cedula = $this->nuevo_cedula;
            $usuario->email = $email;
            // Asignar una contraseña por defecto segura (puedes cambiarla)
            $usuario->password = bcrypt(Str::random(10));
            $usuario->save();

            // Seleccionar y abrir modal de acta
            $this->colaboradorSeleccionado = $usuario->id;
            $this->modalDatosActa = true;
            $this->open = false;

            // Limpiar campos y notificar
            $this->reset(['nuevo_nombre', 'nuevo_cedula']);
            session()->flash('message', 'Colaborador creado correctamente y seleccionado.');
        } catch (\Exception $e) {
            session()->flash('error', 'No se pudo crear el colaborador. Revise logs para más detalles.');
        }
    }



    public function asignarColaborador($colaboradorId)
    {
        $this->colaboradorSeleccionado = $colaboradorId;
        $this->modalDatosActa = true;
        $this->open = false;
    }


    public function guardarActa()
    {
        $this->validate();

        DB::beginTransaction();

        try {
            $rutaImagen = null;

            if ($this->imagen) {
                $manager = new ImageManager(new Driver());
                $img = $manager->read($this->imagen->getRealPath());
                $img = $img->toJpeg(70);
                $nombreArchivo = 'actas/imagenes/' . uniqid() . '.jpg';
                Storage::disk('public')->put($nombreArchivo, $img);

                $rutaImagen = $nombreArchivo;
            }

            Actas::create([
                'user_id' => $this->colaboradorSeleccionado,
                'fecha_entrega' => Carbon::now(),
                'empresa' => $this->empresa,
                'tipo_sangre' => $this->tipo_sangre,
                'tipo_contrato_id' => $this->tipo_contrato_id,
                'imagen_path' => $rutaImagen,
            ]);

            DB::commit();

            $this->reset(['empresa', 'tipo_sangre', 'tipo_contrato_id', 'imagen', 'modalDatosActa']);
            session()->flash('message', 'Acta creada correctamente.');
        } catch (\Exception $e) {
            DB::rollBack();
            session()->flash('error', 'Error al crear el acta: ' . $e->getMessage());
        }
    }



    public function eliminarActa($id)
    {
        $acta = Actas::find($id);

        if ($acta) {
            $acta->delete();
            session()->flash('message', 'Acta eliminada correctamente.');
        } else {
            session()->flash('error', 'No se encontró el acta.');
        }
    }

    public function generarActa($acta_id)
    {
        require_once app_path('Libraries/tbs_class.php');
        require_once app_path('Libraries/tbs_plugin_opentbs.php');

        $acta = Actas::with('user')->find($acta_id);

        if (!$acta || !$acta->user) {
            $this->dispatch('error', message: 'No se encontró el acta o el colaborador.');
            return;
        }

        $TBS = new \clsTinyButStrong;
        $TBS->Plugin(TBS_INSTALL, OPENTBS_PLUGIN);
        $templatePath = public_path('templates/acta.docx');
        // Verificación de existencia del template
        if (!file_exists($templatePath)) {
            $this->dispatch('error', message: 'Plantilla no encontrada.');
            return;
        }

        $TBS->LoadTemplate($templatePath);

        $colaborador = $acta->user;
        $nombre = str_replace(' ', '', $colaborador->name);
        $fecha = date('d/m/Y');
        $cedula = $colaborador->cedula;

        // Insertar datos en la plantilla
        $TBS->MergeField('pro.colaborador', $colaborador->name ?? 'Sin nombre');
        $TBS->MergeField('pro.cedula', $cedula ?? 'Sin cedula');
        $TBS->MergeField('pro.fecha', $fecha);


        // Nombre del archivo con nombre del colaborador y fecha
        $fileName = "acta_credencial_{$nombre}_" . date('d_m_Y') . ".docx";
        $savePath = public_path("actas/actaCredencial/" . $fileName);

        // Guardar el archivo generado
        $TBS->Show(OPENTBS_FILE, $savePath);

        if (file_exists($savePath)) {
            return response()->download($savePath)->deleteFileAfterSend();
        } else {
            $this->dispatch('error', message: 'El archivo no se generó correctamente.');
            return;
        }
    }



    public function uploadActa($acta_id)
    {
        $this->actaSeleccionada = $acta_id;
        $this->upload = true;
    }

    public function guardarArchivo()
    {
        $this->validate([
            'archivo' => 'required|file|max:50240',
        ]);

        $acta = Actas::find($this->actaSeleccionada);

        if (!$acta) {
            $this->dispatch('error', message: 'Acta no encontrado.');
            return;
        }

        $nombreArchivo = 'comprobanteFirmado' . $acta->user->name . '_' . time() . '.' . $this->archivo->getClientOriginalExtension();

        $ruta = $this->archivo->storeAs('actas/actaCredencial', $nombreArchivo, 'public');

        $acta->ruta_firma = 'actas/actaCredencial/' . $nombreArchivo;
        $acta->save();

        $this->reset(['archivo', 'upload', 'actaSeleccionada']);
        $this->dispatch('swal', [
            'icon' => 'success',
            'title' => '¡Bien hecho!',
            'text' => 'Los requerimientos se han actualizado correctamente.'
        ]);
    }


    public function downloadActa($acta_id)
    {
        $acta = Actas::find($acta_id);

        if (!$acta || !$acta->ruta_firma) {
            $this->dispatch('error', message: 'Archivo no disponible.');
            return;
        }

        $rutaAbsoluta = storage_path('app/public/' . $acta->ruta_firma);


        if (!file_exists($rutaAbsoluta)) {
            $this->dispatch('error', message: 'Archivo no encontrado en el servidor.');
            return;
        }

        return response()->download($rutaAbsoluta);
    }

    public function updatedArchivo()
    {
        $this->validate([
            'archivo' => 'required|file|max:50240',
        ]);
    }


    public function render()
    {
        $actas = Actas::with('tipoContrato')
            ->whereHas('user', function ($query) {
                $query->where('name', 'like', '%' . $this->search . '%');
            })
            ->latest('id')
            ->paginate(10, ['*'], 'actasPage');

        return view('livewire.entrega-credenciales', ['actas' => $actas]);
    }
}

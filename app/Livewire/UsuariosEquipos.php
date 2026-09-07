<?php

namespace App\Livewire;

use App\Models\Equipos;
use App\Models\HistorialAsignacion;
use App\Models\User;
use Livewire\Component;
use Livewire\WithPagination;
use Livewire\WithFileUploads;
use Illuminate\Support\Facades\Storage;
use PhpOffice\PhpWord\TemplateProcessor;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;


class UsuariosEquipos extends Component
{
    use WithPagination, WithFileUploads;

    public $search = '';
    public $searchEquipos = '';
    public $open = false;
    public $upload = false;
    public $qr = false;
    public $userIdSeleccionado = null;
    public $page;
    public $equipoPage;
    public $archivo;
    public $modalFirma = false;
    public $user_id_firma = null;
    public $descargar = false;
    public $userDescarga = null;
    public $tieneFirma = false;
    public $tieneComprobante = false;


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
            $user->equipos()->detach($equipoId);
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
        if (!$user || !$user->ruta_firma) {
            $this->dispatch('error', message: 'No hay ningún acta o firma registrada para este usuario.');
            return;
        }

        $rutaAbsoluta = public_path($user->ruta_firma);
        if (!file_exists($rutaAbsoluta)) {
            $this->dispatch('error', message: 'El archivo firmado no existe en el servidor.');
            return;
        }
        $nombreDescarga = 'Acta_Entrega_' . Str::slug($user->name) . '.docx';
        return response()->download($rutaAbsoluta, $nombreDescarga);
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
        $url_qr = url("/equipoInfo/{$user->id}");
        $generator = new \barcode_generator();
        $svg = $generator->render_svg("qr", $url_qr, "");
        $nombreSeguro = Str::slug($user->name);
        $qrFileName = "qr_{$user->id}_{$nombreSeguro}.svg";
        $qrStoragePath = "qrcodes/{$qrFileName}";
        if ($user->qr_codigo && Storage::exists($user->qr_codigo) && $user->qr_codigo !== $qrStoragePath) {
            Storage::delete($user->qr_codigo);
        }
        if (!Storage::exists('qrcodes')) {
            Storage::makeDirectory('qrcodes');
        }
        Storage::put($qrStoragePath, $svg);
        $user->qr_codigo = $qrStoragePath;
        $user->save();
    }

    public function ver_qr($userId)
    {
        $this->userIdSeleccionado = User::find($userId);
        if ($this->userIdSeleccionado && $this->userIdSeleccionado->qr_codigo) {
            $this->qr = true;
        } else {
            $this->qr = true;
            session()->flash('message', 'Este usuario no tiene código QR.');
        }
    }

    public function abrirModalFirma($userId)
    {
        $this->user_id_firma = $userId;
        $this->modalFirma = true;
    }


    public function abrirModalDescargas($userId)
    {
        $user = User::find($userId);

        if (!$user) {
            $this->dispatch('error', message: 'Usuario no encontrado.');
            return;
        }

        $this->userDescarga = $user;

        $this->tieneFirma = !empty($user->ruta_firma)
            && file_exists(public_path($user->ruta_firma));

        $this->tieneComprobante = !empty($user->ruta_comprobante)
            && file_exists(storage_path('app/public/' . $user->ruta_comprobante));

        if (!$this->tieneFirma && !$this->tieneComprobante) {
            $this->dispatch('error', message: 'No existen archivos para descargar.');
            return;
        }

        $this->descargar = true;
    }

    public function descargarFirma()
{
    if (!$this->userDescarga) {
        return;
    }

    $ruta = public_path($this->userDescarga->ruta_firma);

    if (!file_exists($ruta)) {
        $this->dispatch('error', message: 'El acta firmada no existe.');
        return;
    }

    return response()->download(
        $ruta,
        'Acta_Entrega_' . Str::slug($this->userDescarga->name) . '.docx'
    );
}

public function descargarComprobante()
{
    if (!$this->userDescarga) {
        return;
    }

    $ruta = storage_path('app/public/' . $this->userDescarga->ruta_comprobante);

    if (!file_exists($ruta)) {
        $this->dispatch('error', message: 'El comprobante no existe.');
        return;
    }

    return response()->download($ruta);
}

    public function guardarYDescargarFirmado($dataUrl)
    {
        if (!$this->user_id_firma) {
            $this->dispatch('error', message: 'No se ha seleccionado un usuario.');
            return;
        }

        $imageParts = explode(";base64,", $dataUrl);
        if (count($imageParts) < 2) {
            $this->dispatch('error', message: 'Formato de firma inválido.');
            return;
        }

        $imageBase64 = base64_decode($imageParts[1]);
        $tempDir = storage_path('app/temp_firmas');
        if (!File::exists($tempDir)) {
            File::makeDirectory($tempDir, 0755, true);
        }

        $tempImagePath = $tempDir . '/firma_' . $this->user_id_firma . '_' . time() . '.png';
        File::put($tempImagePath, $imageBase64);

        $user = User::with('equipos')->find($this->user_id_firma);
        if (!$user) {
            $this->dispatch('error', message: 'Usuario no encontrado.');
            return;
        }

        $templatePath = public_path('templates/acta_entregaequipo_nuevo.docx');
        if (!File::exists($templatePath)) {
            $this->dispatch('error', message: 'Plantilla no encontrada.');
            return;
        }

        $templateProcessor = new TemplateProcessor($templatePath);
        $templateProcessor->setValue('nombre', $user->name);
        $templateProcessor->setValue('cedula', $user->cedula ?? 'N/A');
        $templateProcessor->setValue('fecha', date('d/m/Y'));
        $templateProcessor->setImageValue('firma', [
            'path'   => $tempImagePath,
            'width'  => 200,
            'height' => 60,
            'ratio'  => true
        ]);

        // 1. Mapear los equipos dinámicamente
        $equipos = $user->equipos->values()->map(fn($equipo, $index) => [
            'numero'      => $index + 1,
            'descripcion' => $equipo->nombre ?? 'N/A',
            'marca'       => $equipo->marca ?? 'N/A',
            'modelo'      => $equipo->modelo ?? 'N/A',
            'serie'       => $equipo->serie ?? 'N/A',
        ])->toArray();

        // En caso de que el usuario no tenga equipos asignados, dejamos 1 fila por defecto
        if (count($equipos) === 0) {
            $equipos[] = [
                'numero'      => 1,
                'descripcion' => 'N/A',
                'marca'       => 'N/A',
                'modelo'      => 'N/A',
                'serie'       => 'N/A',
            ];
        }

        // 2. Clonar la fila en la plantilla Word usando la variable 'descripcion'
        $templateProcessor->cloneRow('descripcion', count($equipos));

        // 3. Reemplazar los valores dinámicamente usando la sintaxis ${variable#indice}
        foreach ($equipos as $index => $equipo) {
            $row = $index + 1;
            $templateProcessor->setValue("\${numero#$row}", $equipo['numero']);
            $templateProcessor->setValue("\${descripcion#$row}", $equipo['descripcion']);
            $templateProcessor->setValue("\${marca#$row}", $equipo['marca']);
            $templateProcessor->setValue("\${modelo#$row}", $equipo['modelo']);
            $templateProcessor->setValue("\${serie#$row}", $equipo['serie']);
        }

        // 4. Guardar archivo final
        $fileName = 'acta_firmada_' . Str::slug($user->name) . '_' . date('Ymd_His') . '.docx';
        $relativePath = 'actas/entrega_equiposUsuarios/' . $fileName;
        $saveDir = public_path('actas/entrega_equiposUsuarios');

        if (!File::exists($saveDir)) {
            File::makeDirectory($saveDir, 0755, true);
        }

        $savePath = $saveDir . '/' . $fileName;
        $templateProcessor->saveAs($savePath);

        $user->ruta_firma = $relativePath;
        $user->save();

        if (File::exists($tempImagePath)) {
            File::delete($tempImagePath);
        }

        $this->modalFirma = false;
        $this->user_id_firma = null;

        return response()->download($savePath, $fileName, [
            'Content-Type' => 'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
        ]);
    }
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

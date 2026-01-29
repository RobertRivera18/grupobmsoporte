<?php

namespace App\Livewire;

use App\Models\ActaFirmada;
use App\Models\Cuadrilla;
use App\Models\Equipos;
use App\Models\User;
use Livewire\Component;
use Livewire\WithPagination;
use Livewire\WithFileUploads;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use Illuminate\Support\Facades\Log;
use Livewire\Attributes\On;
use Novay\Word\Facades\Word;
use PhpOffice\PhpWord\TemplateProcessor;

class EquiposCuadrillas extends Component
{
    use WithPagination, WithFileUploads;

    public $search = '';
    public $open = false;
    public $modalEquipos = false;
    public $searchColaboradores = '';
    public $cuaIdSeleccionado = null;
    public $searchEquipos = '';
    public $upload = false;
    public $archivo;
    public $opciones = false;
    public $firmaBase64 = null;
    public array $firmas = [];



    public function firmaCapturada(string $tipo): bool
    {
        return isset($this->firmas[$tipo]);
    }





    #[On('firmaCapturada')]
    public function setFirma($tipo, $firma)
    {
        $this->firmas[$tipo] = $firma;
    }

    public function descargarFirma()
    {
        if (!$this->firmaBase64 || !str_starts_with($this->firmaBase64, 'data:image')) {
            abort(400, 'No hay firma capturada');
        }

        return $this->guardarYDescargarFirma($this->firmaBase64);
    }


    private function guardarYDescargarFirma($firmaBase64)
    {
        $firma = preg_replace('#^data:image/\w+;base64,#i', '', $firmaBase64);
        $imagen = base64_decode($firma);

        if ($imagen === false) {
            abort(400, 'Base64 inválido');
        }

        $nombre = 'firma_' . now()->timestamp . '.png';
        $ruta = storage_path('app/tmp/' . $nombre);

        if (!is_dir(dirname($ruta))) {
            mkdir(dirname($ruta), 0755, true);
        }

        file_put_contents($ruta, $imagen);

        Log::info('Firma guardada', ['ruta' => $ruta]);

        return response()->download($ruta)->deleteFileAfterSend(true);
    }

    private function guardarFirmaTemporal(string $firmaBase64): ?string
    {
        if (!$firmaBase64) {
            return null;
        }

        $firma = preg_replace('#^data:image/\w+;base64,#i', '', $firmaBase64);
        $imagen = base64_decode($firma);

        if ($imagen === false) {
            return null;
        }

        $nombre = 'firma_' . uniqid() . '.png';
        $ruta = storage_path('app/tmp/' . $nombre);

        if (!is_dir(dirname($ruta))) {
            mkdir(dirname($ruta), 0755, true);
        }

        file_put_contents($ruta, $imagen);

        return $ruta;
    }




    // Listeners para actualizar el componente
    protected $listeners = [
        'refreshComponent' => '$refresh',
    ];

    public function updatingSearchColaboradores()
    {
        $this->resetPage('UserPage');
    }

    public function updatingSearchEquipos()
    {
        $this->resetPage('equipoPage');
    }


    public function getColaboradoresDisponiblesProperty()
    {
        return User::query()
            ->select('id', 'name', 'cedula')
            ->whereDoesntHave('cuadrillas')
            ->when($this->searchColaboradores, function ($query) {
                $search = '%' . $this->searchColaboradores . '%';

                $query->where(function ($q) use ($search) {
                    $q->where('name', 'like', $search)
                        ->orWhere('cedula', 'like', $search);
                });
            })
            ->paginate(10, ['*'], 'UserPage');
    }


    // Resetear la página al limpiar la búsqueda
    public function limpiar_page()
    {
        $this->resetPage();
    }

    //Metodo para abrir modal de colaboradores
    public function agregarColaborador($cuaId)
    {
        $this->cuaIdSeleccionado = $cuaId;
        $this->searchColaboradores = '';
        $this->resetPage('UserPage');
        $this->open = true;
    }

    // Método para asignar un colaborador a la cuadrilla seleccionada
    public function asignarColaborador($userId)
    {
        if (!$this->cuaIdSeleccionado) {
            $this->dispatch('error', message: 'Seleccione una cuadrilla primero.');
            return;
        }

        $cuadrilla = Cuadrilla::find($this->cuaIdSeleccionado);
        $user = User::find($userId);

        if (!$cuadrilla || !$user) {
            $this->dispatch('error', message: 'Colaborador o cuadrilla no encontrados.');
            return;
        }

        $cuadrilla->users()->attach($user->id);

        $this->dispatch('swal', [
            'icon' => 'success',
            'title' => 'Asignado',
            'text' => 'Colaborador asignado correctamente a la cuadrilla.',
        ]);

        // Mantener el modal abierto y actualizar la lista
        $this->resetPage('UserPage');
    }

    public function uploadFile($cuaId)
    {
        $this->cuaIdSeleccionado = $cuaId;
        $this->upload = true;
    }

    //Abrir modal para seleccionar tipo de acta
    public function abrirModalOpciones($cuaId)
    {
        $this->cuaIdSeleccionado = $cuaId;
        $this->opciones = true; // muestra el modal
    }


    public function guardarArchivo()
    {
        $this->validate([
            'archivo' => 'required|file|max:50240',
        ]);

        $cuadrilla = Cuadrilla::find($this->cuaIdSeleccionado);

        if (!$cuadrilla) {
            $this->dispatch('error', message: 'Cuadrilla no encontrado.');
            return;
        }

        $nombreArchivo = 'comprobante_' . $cuadrilla->id . '_' . time() . '.' . $this->archivo->getClientOriginalExtension();

        $ruta = $this->archivo->storeAs('actas/entrega_equiposCuadrilla', $nombreArchivo, 'public');

        $cuadrilla->ruta_comprobante = 'actas/entrega_equiposCuadrilla/' . $nombreArchivo;
        $cuadrilla->save();

        $this->reset(['archivo', 'upload', 'cuaIdSeleccionado']);

        $this->dispatch('swal', [
            'icon' => 'success',
            'title' => '¡Bien hecho!',
            'text' => 'El archivo se ha subido correctamente.'
        ]);
    }

    //Descargar archivo comprobante
    public function descargarArchivo($cuaId)
    {
        $cuadrilla = Cuadrilla::find($cuaId);

        if (!$cuadrilla || !$cuadrilla->ruta_comprobante) {
            $this->dispatch('error', message: 'Archivo no disponible.');
            return;
        }

        $rutaAbsoluta = storage_path('app/public/' . $cuadrilla->ruta_comprobante);

        if (!file_exists($rutaAbsoluta)) {
            $this->dispatch('error', message: 'Archivo no encontrado en el servidor.');
            return;
        }

        return response()->download($rutaAbsoluta);
    }

    //Eliminar un Colaborador de una cuadrilla
    public function eliminarColaborador($userId, $cuaId)
    {
        $user = User::find($userId);
        $cuadrilla = Cuadrilla::find($cuaId);

        if ($cuadrilla && $user) {
            $cuadrilla->users()->detach($user->id);

            $this->dispatch('swal', [
                'icon' => 'success',
                'title' => 'Eliminado',
                'text' => 'Colaborador eliminado correctamente de la cuadrilla.',
            ]);
        } else {
            $this->dispatch('error', message: 'Colaborador o cuadrilla no encontrados.');
        }
    }


    public function getEquiposDisponiblesProperty()
    {
        return Equipos::query()
            ->select('id', 'nombre', 'marca', 'modelo', 'serie')
            ->whereIn('datos', [2, 3])
            ->where('estado', 1)
            ->whereDoesntHave('cuadrilla')
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


    public function agregarEquipo($cuaId)
    {
        $this->cuaIdSeleccionado = $cuaId;
        $this->searchEquipos = '';
        $this->resetPage('equipoPage');
        $this->modalEquipos = true;
    }

    // Asignar un equipo a la cuadrilla seleccionada
    public function asignarEquipo($equipoId)
    {
        if (!$this->cuaIdSeleccionado) {
            $this->dispatch('error', message: 'Seleccione una cuadrilla primero.');
            return;
        }

        $cuadrilla = Cuadrilla::find($this->cuaIdSeleccionado);
        if ($cuadrilla) {
            $cuadrilla->equipos()->attach($equipoId);

            $this->dispatch('swal', [
                'icon' => 'success',
                'title' => 'Asignado',
                'text' => 'Equipo asignado correctamente.',
            ]);
            $this->resetPage('equipoPage');
        } else {
            $this->dispatch('error', message: 'Cuadrilla no encontrada.');
        }
    }

    // Eliminar el equipo asignado a una cuadrilla
    public function eliminarEquipo($cuaId, $equipoId)
    {
        $cuadrilla = Cuadrilla::find($cuaId);

        if ($cuadrilla) {
            $cuadrilla->equipos()->detach($equipoId);

            $this->dispatch('swal', [
                'icon' => 'success',
                'title' => 'Eliminado',
                'text' => 'Equipo eliminado correctamente.',
            ]);
        } else {
            $this->dispatch('error', message: 'Cuadrilla no encontrada.');
        }
    }

    public function generarExcel()
    {
        try {
            $cuadrillas = Cuadrilla::with(['equipos', 'users'])
                ->whereHas('equipos', function ($query) {
                    $query->where('datos', 2);
                })
                ->get();

            $inputFileName = public_path('templates/formatoListadoRecargas.xlsx');
            $spreadsheet = \PhpOffice\PhpSpreadsheet\IOFactory::load($inputFileName);
            $sheet = $spreadsheet->getActiveSheet();

            $fechaInicio = now();
            $fechaInicio->setDate($fechaInicio->format('Y'), $fechaInicio->format('m'), 23);
            $fechaFin = clone $fechaInicio;
            $fechaFin->modify('+1 month');

            $encabezado = "LISTADO DE LINEAS DE RECARGAS MENSUALES PERIODO " . $fechaInicio->format('d/m/Y') . " AL " . $fechaFin->format('d/m/Y');
            $sheet->mergeCells('C4:I7');
            $sheet->setCellValue('C4', $encabezado);
            $sheet->getStyle('C4')->getFont()->setBold(true);
            $sheet->getStyle('C4')
                ->getAlignment()
                ->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER)
                ->setVertical(\PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER);

            $fila = 9;
            $contador = 1;
            $cantidadCuadrillas = 0;
            foreach ($cuadrillas as $cuadrilla) {
                $colaboradores = $cuadrilla->users;
                $inicioFila = $fila;

                if ($colaboradores->isEmpty()) {
                    $sheet->setCellValue('C' . $fila, $contador);
                    $ciudadNombre = $cuadrilla->cua_ciudad == 1 ? 'Guayaquil' : ($cuadrilla->cua_ciudad == 2 ? 'Quito' : 'Sin ciudad');
                    $sheet->setCellValue('D' . $fila, $ciudadNombre);
                    $sheet->setCellValue('E' . $fila, $cuadrilla->equipos->first()->serie ?? 'Sin serie');
                    $sheet->setCellValue('F' . $fila, $cuadrilla->cua_nombre);
                    $sheet->setCellValue('G' . $fila, 'Sin colaboradores');
                    $sheet->setCellValue('H' . $fila, 10.50);
                    $sheet->setCellValue('I' . $fila, $cuadrilla->recargas == 1 ? 'Recarga Realizada' : 'Recarga No Realizada');
                    $fila++;
                } else {
                    foreach ($colaboradores as $colaborador) {
                        $sheet->setCellValue('C' . $fila, $contador);
                        $ciudadNombre = $cuadrilla->cua_ciudad == 1 ? 'Guayaquil' : ($cuadrilla->cua_ciudad == 2 ? 'Quito' : 'Sin ciudad');
                        $sheet->setCellValue('D' . $fila, $ciudadNombre);
                        $sheet->setCellValue('E' . $fila, $cuadrilla->equipos->first()->serie ?? 'Sin serie');
                        $sheet->setCellValue('F' . $fila, $cuadrilla->cua_nombre);
                        $sheet->setCellValue('G' . $fila, $colaborador->name);
                        $sheet->setCellValue('H' . $fila, 10.50);
                        $sheet->setCellValue('I' . $fila, $cuadrilla->recargas == 1 ? 'Recarga Realizada' : 'Recarga No Realizada');
                        $fila++;
                    }

                    $finFila = $fila - 1;
                    if ($inicioFila < $finFila) {
                        $sheet->mergeCells("C{$inicioFila}:C{$finFila}");
                        $sheet->mergeCells("D{$inicioFila}:D{$finFila}");
                        $sheet->mergeCells("E{$inicioFila}:E{$finFila}");
                        $sheet->mergeCells("F{$inicioFila}:F{$finFila}");
                        $sheet->mergeCells("H{$inicioFila}:H{$finFila}");
                        $sheet->mergeCells("I{$inicioFila}:I{$finFila}");
                    }
                }

                $contador++;
                $cantidadCuadrillas++;
            }

            $totalRecargas = $cantidadCuadrillas * 10.50;
            $sheet->setCellValue('H59', $totalRecargas);

            $writer = new \PhpOffice\PhpSpreadsheet\Writer\Xlsx($spreadsheet);
            $fileName = 'reporteRecargas_' . now()->format('Y-m-d') . '.xlsx';
            $tempPath = storage_path('app/public/temp');

            if (!file_exists($tempPath)) {
                mkdir($tempPath, 0755, true);
            }

            $filePath = $tempPath . '/' . $fileName;
            $writer->save($filePath);

            return response()->download($filePath, $fileName, [
                'Content-Type' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
            ])->deleteFileAfterSend(true);
        } catch (\Exception $e) {
            $this->dispatch('swal', [
                'icon' => 'error',
                'title' => 'Error',
                'text' => 'Error al generar Excel: ' . $e->getMessage(),
            ]);
        }
    }

    public function actualizarRecarga($cuadrillaId, $estado)
    {
        $cuadrilla = Cuadrilla::find($cuadrillaId);

        if ($cuadrilla) {
            $cuadrilla->recargas = $estado ? 1 : 0;
            $cuadrilla->save();
        }
    }

    // public function generar($cuaId)
    // {
    //     require_once app_path('Libraries/tbs_class.php');
    //     require_once app_path('Libraries/tbs_plugin_opentbs.php');

    //     $cuadrilla = Cuadrilla::with([
    //         'users',
    //         'equipos' => function ($query) {
    //             $query->where('tipo_equipo_id', 4);
    //         }
    //     ])->find($cuaId);
    //     if (!$cuadrilla) {
    //         $this->dispatch('error', message: 'Cuadrilla no encontrada.');
    //         return;
    //     }


    //     $TBS = new \clsTinyButStrong;
    //     $TBS->Plugin(TBS_INSTALL, OPENTBS_PLUGIN);

    //     $templatePath = public_path('templates/acta_entregachip.docx');

    //     if (!file_exists($templatePath)) {
    //         $this->dispatch('error', message: 'Plantilla no encontrada.');
    //         return;
    //     }

    //     $TBS->LoadTemplate($templatePath, OPENTBS_ALREADY_UTF8);

    //     $colaboradores = $cuadrilla->users->take(2);
    //     $nombres = $colaboradores->pluck('name')->toArray();
    //     $cedulas = $colaboradores->pluck('cedula')->toArray();

    //     $TBS->MergeField('cuadrilla.colaborador1', $nombres[0] ?? 'N/A');
    //     $TBS->MergeField('cuadrilla.colaborador2', $nombres[1] ?? 'N/A');
    //     $TBS->MergeField('cuadrilla.cedula1', $cedulas[0] ?? 'N/A');
    //     $TBS->MergeField('cuadrilla.cedula2', $cedulas[1] ?? 'N/A');
    //     $TBS->MergeField('cuadrilla.nombre', $cuadrilla->cua_nombre);

    //     $equipos = $cuadrilla->equipos->map(function ($equipo) {
    //         return "{$equipo->serie}";
    //     })->implode("\n");

    //     $TBS->MergeField('cuadrilla.equipos', $equipos ?: 'Ningún equipo asignado');
    //     $TBS->MergeField('fecha', date('d/M/Y'));

    //     $fileName = "acta_chip_" . date('Y-m-d') . ".docx";
    //     $savePath = public_path("actas/chipsCuadrillas/" . $fileName);

    //     $TBS->Show(OPENTBS_FILE, $savePath);

    //     if (file_exists($savePath)) {
    //         return response()->download($savePath)->deleteFileAfterSend();
    //     } else {
    //         $this->dispatch('error', message: 'El archivo no se generó correctamente.');
    //         return;
    //     }
    // }

    public function generar($cuaId)
    {
        if (
            empty($this->firmas['responsable']) ||
            empty($this->firmas['receptor'])
        ) {
            $this->dispatch('error', message: 'Debe capturar ambas firmas antes de generar el acta');
            return;
        }

        $cuadrilla = Cuadrilla::with([
            'users',
            'equipos' => fn($q) => $q->where('tipo_equipo_id', 4)
        ])->find($cuaId);

        if (!$cuadrilla) {
            $this->dispatch('error', message: 'Cuadrilla no encontrada.');
            return;
        }

        $templatePath = public_path('templates/acta_entregachip.docx');
        if (!file_exists($templatePath)) {
            $this->dispatch('error', message: 'Plantilla no encontrada.');
            return;
        }

        $colaboradores = $cuadrilla->users->take(2)->values();
        $equipos = $cuadrilla->equipos
            ->pluck('serie')
            ->implode("\n") ?: 'Ningún equipo asignado';
        $firmaResponsablePath = $this->guardarFirmaTemporal($this->firmas['responsable']);
        $firmaReceptorPath    = $this->guardarFirmaTemporal($this->firmas['receptor']);

        if (
            !$firmaResponsablePath || !file_exists($firmaResponsablePath) ||
            !$firmaReceptorPath || !file_exists($firmaReceptorPath)
        ) {
            $this->dispatch('error', message: 'No se pudo procesar una de las firmas');
            return;
        }
        $fileName = 'acta_chip_' . $cuadrilla->cua_nombre . '_' . now()->format('Ymd_His') . '.docx';
        $savePath = public_path('actas/chips/' . $fileName);

        $template = new \PhpOffice\PhpWord\TemplateProcessor($templatePath);
        $template->setValue('fecha', now()->format('d/m/Y'));
        $template->setValue('cuadrilla_nombre', $cuadrilla->cua_nombre);
        $template->setValue('colaborador1', $colaboradores[0]->name ?? 'N/A');
        $template->setValue('cedula1', $colaboradores[0]->cedula ?? 'N/A');
        $template->setValue('colaborador2', $colaboradores[1]->name ?? 'N/A');
        $template->setValue('cedula2', $colaboradores[1]->cedula ?? 'N/A');
        $template->setValue('equipos', $equipos);
        $template->setImageValue('firma_responsable', [
            'path'   => $firmaResponsablePath,
            'width'  => 180,
            'height' => 70,
            'ratio'  => true,
        ]);

        $template->setImageValue('firma_receptor', [
            'path'   => $firmaReceptorPath,
            'width'  => 180,
            'height' => 70,
            'ratio'  => true,
        ]);

        $template->saveAs($savePath);
        @unlink($firmaResponsablePath);
        @unlink($firmaReceptorPath);
        $this->firmas = [];

        return response()->download($savePath)->deleteFileAfterSend();
    }


    public function generarActaEquipo($cuaId)
    {
        if (
            empty($this->firmas['responsable']) ||
            empty($this->firmas['receptor'])
        ) {
            $this->dispatch('error', message: 'Debe capturar ambas firmas antes de generar el acta');
            return;
        }

        $cuadrilla = Cuadrilla::with([
            'users',
            'equipos' => fn($q) => $q->whereIn('tipo_equipo_id', [3, 10])
        ])->find($cuaId);

        if (!$cuadrilla) {
            $this->dispatch('error', message: 'Cuadrilla no encontrada.');
            return;
        }
        $equipos = $cuadrilla->equipos->values()->map(function ($equipo, $index) {
            return [
                'numero'      => $index + 1,
                'descripcion' => $equipo->nombre ?? 'N/A',
                'marca'       => $equipo->marca ?? 'N/A',
                'modelo'      => $equipo->modelo ?? 'N/A',
                'serie'       => $equipo->serie ?? 'N/A',
            ];
        })->toArray();

        if (count($equipos) === 0) {
            $equipos[] = [
                'numero' => '',
                'descripcion' => 'N/A',
                'marca' => 'N/A',
                'modelo' => 'N/A',
                'serie' => 'N/A',
            ];
        }
        $firmaResponsablePath = $this->guardarFirmaTemporal($this->firmas['responsable']);
        $firmaReceptorPath    = $this->guardarFirmaTemporal($this->firmas['receptor']);

        if (
            !$firmaResponsablePath || !file_exists($firmaResponsablePath) ||
            !$firmaReceptorPath || !file_exists($firmaReceptorPath)
        ) {
            $this->dispatch('error', message: 'No se pudo procesar una de las firmas');
            return;
        }
        $templatePath = public_path('templates/acta_entregaequipo_cuadrilla_nuevo.docx');
        $fileName = 'acta_entrega_equipo_' . now()->format('Ymd_His') . '.docx';
        $savePath = public_path('actas/entrega_equiposCuadrillas/' . $fileName);

        $template = new TemplateProcessor($templatePath);
        $template->setValue('fecha', now()->format('d/m/Y'));
        $template->setValue('colaborador1', $cuadrilla->users[0]->name ?? 'N/A');
        $template->setValue('cedula1', $cuadrilla->users[0]->cedula ?? 'N/A');
        $template->setValue('colaborador2', $cuadrilla->users[1]->name ?? 'N/A');
        $template->setValue('cedula2', $cuadrilla->users[1]->cedula ?? 'N/A');
        $template->setValue('cuadrilla_nombre', $cuadrilla->cua_nombre);
        $template->setImageValue('firma_responsable', [
            'path'   => $firmaResponsablePath,
            'width'  => 180,
            'height' => 70,
            'ratio'  => true,
        ]);

        $template->setImageValue('firma_receptor', [
            'path'   => $firmaReceptorPath,
            'width'  => 180,
            'height' => 70,
            'ratio'  => true,
        ]);

        $template->cloneRow('descripcion', count($equipos));
        foreach ($equipos as $i => $equipo) {
            $row = $i + 1;

            $template->setValue("numero#$row", $equipo['numero']);
            $template->setValue("descripcion#$row", $equipo['descripcion']);
            $template->setValue("marca#$row", $equipo['marca']);
            $template->setValue("modelo#$row", $equipo['modelo']);
            $template->setValue("serie#$row", $equipo['serie']);
        }

        $template->saveAs($savePath);
        ActaFirmada::create([
            'tipo'               => 'chip',
            'cuadrilla_id'       => $cuadrilla->id,
            'responsable_id'     => $cuadrilla->users[0]->id ?? null,
            'receptor_id'        => $cuadrilla->users[1]->id ?? null,
            'cedula_responsable' => $cuadrilla->users[0]->cedula,
            'cedula_receptor'    => $cuadrilla->users[1]->cedula ?? null,
            'ruta_docx'          => 'actas/chips/' . $fileName,
            'firmado_en'         => now(),
        ]);
        @unlink($firmaResponsablePath);
        @unlink($firmaReceptorPath);

        $this->firmas = [];
        return response()->download($savePath)->deleteFileAfterSend();
    }

    public function cerrarModal()
    {
        $this->reset(['opciones', 'cuaIdSeleccionado', 'firmaBase64']);
        $this->dispatch('limpiar-firma');
    }

    public function actaDescargo($cuaId)
    {
        require_once app_path('Libraries/tbs_class.php');
        require_once app_path('Libraries/tbs_plugin_opentbs.php');

        $cuadrilla = Cuadrilla::with('equipos')->find($cuaId);

        if (!$cuadrilla) {
            $this->dispatch('error', message: 'Cuadrilla no encontrada.');
            return;
        }

        $TBS = new \clsTinyButStrong;
        $TBS->Plugin(TBS_INSTALL, OPENTBS_PLUGIN);

        $templatePath = public_path('templates/descargo_cuadrilla_equipo.docx');
        $TBS->LoadTemplate($templatePath, OPENTBS_ALREADY_UTF8);

        $colaboradores = $cuadrilla->users->take(2);
        $nombres = $colaboradores->pluck('name')->toArray();
        $cedulas = $colaboradores->pluck('cedula')->toArray();

        $TBS->MergeField('cuadrilla.colaborador1', $nombres[0] ?? 'N/A');
        $TBS->MergeField('cuadrilla.colaborador2', $nombres[1] ?? 'N/A');
        $TBS->MergeField('cuadrilla.cedula1', $cedulas[0] ?? 'N/A');
        $TBS->MergeField('cuadrilla.cedula2', $cedulas[1] ?? 'N/A');
        $TBS->MergeField('cuadrilla.nombre', $cuadrilla->cua_nombre);

        $nombreCompleto = $cuadrilla->cua_nombre;
        $equipos = [];

        foreach ($cuadrilla->equipos as $equipo) {
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
        $TBS->MergeField('fecha', date('d/m/Y'));

        foreach ($equipos as $index => $equipo) {
            $TBS->MergeField("equipos.descripcion_$index", $equipo['descripcion']);
            $TBS->MergeField("equipos.marca_$index", $equipo['marca']);
            $TBS->MergeField("equipos.modelo_$index", $equipo['modelo']);
            $TBS->MergeField("equipos.serie_$index", $equipo['serie']);
        }

        $fileName = 'acta_descargo_equipo_' . str_replace(' ', '_', strtolower($nombreCompleto)) . '_' . date('Ymd') . '.docx';
        $savePath = public_path('actas/entrega_equiposUsuarios/' . $fileName);

        $TBS->Show(OPENTBS_FILE, $savePath);
        return response()->download($savePath)->deleteFileAfterSend();
    }

    public function render()
    {
        $user = auth()->user();

        $cuadrillas = Cuadrilla::with([
            'users:id,name,cedula',
            'equipos:id,nombre,marca,serie'
        ])
            ->when($this->search, function ($query) {
                $search = '%' . $this->search . '%';

                $query->where(function ($q) use ($search) {
                    $q->where('cua_nombre', 'like', $search)
                        ->orWhereHas(
                            'users',
                            fn($u) =>
                            $u->where('name', 'like', $search)
                        )
                        ->orWhereHas(
                            'equipos',
                            fn($e) =>
                            $e->where('serie', 'like', $search)
                        );
                });
            })
            ->when(
                $user->hasRole('operador1'),
                fn($q) => $q->where('cua_ciudad', 1)
            )
            ->when(
                $user->hasRole('operador2'),
                fn($q) => $q->where('cua_ciudad', 2)
            )
            ->latest('id')
            ->paginate(40);

        $users = User::select('id', 'name', 'cedula')
            ->whereDoesntHave('cuadrillas')
            ->get();

        return view('livewire.equipos-cuadrillas', compact('cuadrillas', 'users'));
    }
}

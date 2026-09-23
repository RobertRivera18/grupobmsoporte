<?php

namespace App\Livewire;

use App\Models\SolicitudDesvinculacion;
use App\Models\SolicitudDesvinculacionEquipo;
use clsTinyButStrong;
use Livewire\Component;
use Illuminate\Support\Collection;
use Livewire\WithFileUploads;
use Illuminate\Support\Facades\Storage;

class EditarDesvinculacion extends Component
{
    use WithFileUploads;

    public SolicitudDesvinculacion $solicitud;
    public Collection $equiposAsignados;
    public array $destinoEquipos = [];
    public array $observacionesEquipos = [];
    public string $observaciones = '';
    public $comprobante;

    public const MIN_FILAS_ACTA_DESCARGO = 5;

    public function mount($solicitudId)
    {
        $this->solicitud = SolicitudDesvinculacion::with([
            'user',
            'cuadrilla.equipos',
            'equiposDetalle'
        ])->findOrFail($solicitudId);

        $this->observaciones = $this->solicitud->observaciones ?? '';
        $this->equiposAsignados = $this->solicitud->cuadrilla?->equipos ?? collect();

        foreach (($this->solicitud->equiposDetalle ?? []) as $eqDetalle) {
            $this->destinoEquipos[$eqDetalle->equipo_id] = $eqDetalle->destino;
            $this->observacionesEquipos[$eqDetalle->equipo_id] = $eqDetalle->observaciones;
        }
    }

    public function marcarTodosComo(string $estado): void
    {
        if (!in_array($estado, ['entregado', 'faltante'])) return;

        foreach ($this->equiposAsignados as $equipo) {
            $this->destinoEquipos[$equipo->id] = $estado;
        }
    }

    public function getTodosEquiposMarcadosProperty(): bool
    {
        if ($this->equiposAsignados->isEmpty()) return true;
        return count($this->destinoEquipos) === $this->equiposAsignados->count();
    }

    public function completarSinEquipos()
    {
        $this->solicitud->update([
            'observaciones' => 'Colaborador no registra equipos asignados. Proceso completado.',
            'etapa'         => 'completado'
        ]);

        $this->dispatch('swal', 
            title: '¡Proceso Completado!', 
            text: 'La solicitud ha sido marcada como completada sin equipos asignados.', 
            icon: 'success'
        );
    }

    public function guardarDisposicion()
    {
        if ($this->equiposAsignados->isNotEmpty()) {
            SolicitudDesvinculacionEquipo::where('solicitud_desvinculacion_id', $this->solicitud->id)->delete();

            foreach ($this->equiposAsignados as $equipo) {
                $destinoKey = $this->destinoEquipos[$equipo->id] ?? 'faltante';
                $observacionEquipo = $this->observacionesEquipos[$equipo->id] ?? null;

                SolicitudDesvinculacionEquipo::create([
                    'solicitud_desvinculacion_id' => $this->solicitud->id,
                    'equipo_id'                  => $equipo->id,
                    'destino'                    => $destinoKey,
                    'observaciones'              => $observacionEquipo,
                ]);
            }
        }

        $this->solicitud->update([
            'observaciones' => $this->observaciones,
            'etapa'         => 'completado'
        ]);

        $this->dispatch('swal', title: '¡Guardado!', text: 'Disposición y observaciones registradas correctamente.', icon: 'success');
    }

    public function generarActa(string $tipoActa)
    {
        require_once app_path('Libraries/tbs_class.php');
        require_once app_path('Libraries/tbs_plugin_opentbs.php');
        $templateName = $tipoActa === 'descargo'
            ? 'descargo_cuadrilla_equipo.docx'
            : 'liberacion_cuadrilla_equipo.docx';

        $templatePath = public_path('templates/' . $templateName);

        if (!file_exists($templatePath)) {
            $this->dispatch('error', message: "Plantilla {$templateName} no encontrada.");
            return;
        }

        $cuadrilla = $this->solicitud->cuadrilla;

        $colaboradores = $cuadrilla ? $cuadrilla->users->take(2)->values() : collect();
        $nombres = $colaboradores->pluck('name')->toArray();
        $cedulas = $colaboradores->pluck('cedula')->toArray();
        $equipos = [];

        foreach ($this->equiposAsignados as $equipo) {
            $observacionEquipo = $this->observacionesEquipos[$equipo->id]
                ?? ($this->observaciones ?: 'Sin observaciones');

            $equipos[] = [
                'descripcion' => $equipo->nombre ?? $equipo->descripcion ?? 'N/A',
                'marca'       => $equipo->marca ?? 'N/A',
                'modelo'      => $equipo->modelo ?? 'N/A',
                'serie'       => $equipo->codigo ?? $equipo->serie ?? 'N/A',
                'notas'       => $observacionEquipo,
            ];
        }

        $minFilas = defined('self::MIN_FILAS_ACTA_DESCARGO') ? self::MIN_FILAS_ACTA_DESCARGO : 5;
        while (count($equipos) < $minFilas) {
            $equipos[] = [
                'descripcion' => 'N/A',
                'marca'       => 'N/A',
                'modelo'      => 'N/A',
                'serie'       => 'N/A',
                'notas'       => 'N/A',
            ];
        }

        $TBS = new \clsTinyButStrong();
        $TBS->Plugin(TBS_INSTALL, OPENTBS_PLUGIN);
        $TBS->LoadTemplate($templatePath, OPENTBS_ALREADY_UTF8);

        $TBS->MergeField('fecha', now()->format('d/m/Y'));
        $TBS->MergeField('cuadrilla.colaborador1', $nombres[0] ?? ($this->solicitud->user->name ?? 'N/A'));
        $TBS->MergeField('cuadrilla.colaborador2', $nombres[1] ?? 'N/A');
        $TBS->MergeField('cuadrilla.cedula1', $cedulas[0] ?? ($this->solicitud->user->cedula ?? 'N/A'));
        $TBS->MergeField('cuadrilla.cedula2', $cedulas[1] ?? 'N/A');
        $TBS->MergeField('cuadrilla.nombre', $cuadrilla->cua_nombre ?? 'N/A');
        $TBS->MergeField('usu.nombre', $cuadrilla->cua_nombre ?? 'N/A');

        foreach ($equipos as $index => $equipo) {
            $TBS->MergeField("equipos.descripcion_$index", $equipo['descripcion']);
            $TBS->MergeField("equipos.marca_$index", $equipo['marca']);
            $TBS->MergeField("equipos.modelo_$index", $equipo['modelo']);
            $TBS->MergeField("equipos.serie_$index", $equipo['serie']);
            $TBS->MergeField("equipos.notas_$index", $equipo['notas']);
        }

        $folderPath = public_path('actas/entrega_equiposUsuarios');
        if (!file_exists($folderPath)) {
            mkdir($folderPath, 0755, true);
        }

        $cleanName = str_replace(' ', '_', strtolower($cuadrilla->cua_nombre ?? $this->solicitud->user->name ?? 'colaborador'));
        $prefijo   = $tipoActa === 'descargo' ? 'acta_descargo_' : 'acta_liberacion_';
        $fileName  = $prefijo . $cleanName . '_' . now()->format('Ymd_His') . '.docx';
        $savePath  = $folderPath . '/' . $fileName;
        $TBS->Show(OPENTBS_FILE, $savePath);

        return response()->download($savePath)->deleteFileAfterSend();
    }

    public function guardarComprobante()
    {
        $this->validate([
            'comprobante' => 'required|image|mimes:jpg,jpeg,png,webp|max:4096',
        ], [
            'comprobante.required' => 'Debes seleccionar una imagen para subir.',
            'comprobante.image'    => 'El archivo debe ser una imagen válida.',
            'comprobante.max'      => 'La imagen no debe pesar más de 4MB.',
        ]);

        if ($this->solicitud->comprobante && Storage::disk('public')->exists($this->solicitud->comprobante)) {
            Storage::disk('public')->delete($this->solicitud->comprobante);
        }

        $path = $this->comprobante->store('comprobantes_desvinculacion', 'public');
        $this->solicitud->update([
            'comprobante' => $path
        ]);

        $this->reset('comprobante');
        session()->flash('success', 'El comprobante firmado se ha subido correctamente.');
    }

    public function render()
    {
        return view('livewire.editar-desvinculacion');
    }
}
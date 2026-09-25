<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\SolicitudDesvinculacion;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;

class DesvinculacionController extends Controller
{
    public function index()
    {
        $solicitudes = SolicitudDesvinculacion::with(['user', 'cuadrilla'])
            ->latest()
            ->paginate(10);

        return view('admin.desvinculacion.index', compact('solicitudes'));
    }
    public function create()
    {
        return view('admin.desvinculacion.create');
    }

    public function edit(SolicitudDesvinculacion $desvinculacion)
    {
        if (!auth()->user()->hasRole('Admin') && !auth()->user()->hasRole('Sistemas')) {
            abort(403, 'No tienes permisos para acceder a la gestion de equipos de Sistemas.');
        }

        return view('admin.desvinculacion.edit', ['solicitud' => $desvinculacion]);
    }

    public function show(SolicitudDesvinculacion $desvinculacion)
    {
        Gate::authorize('view', $desvinculacion);
        $desvinculacion->load(['user', 'cuadrilla', 'equiposDetalle']);
        $equiposAsignados = $desvinculacion->cuadrilla ? $desvinculacion->cuadrilla->equipos : collect();
        return view('admin.desvinculacion.show', [
            'solicitud'        => $desvinculacion,
            'equiposAsignados' => $equiposAsignados,
        ]);
    }

    //Cambiar Entrega Credecial/Uniformes
    public function updateTalentoHumano(Request $request, $id)
    {
        $solicitud = SolicitudDesvinculacion::findOrFail($id);
        $solicitud->update([
            'devolver_credencial' => $request->boolean('devolver_credencial'),
            'devolver_uniforme'   => $request->boolean('devolver_uniforme'),
        ]);

        return redirect()->back()->with('success', 'El estado de la entrega se actualizo correctamente.');
    }

    public function destroy(SolicitudDesvinculacion $solicitud)
    {
        try {
            if ($solicitud->equiposDetalle()) {
                $solicitud->equiposDetalle()->delete();
            }
            $solicitud->delete();
            return redirect()->route('admin.desvinculacion.index')
                ->with('success', 'La solicitud de desvinculacion ha sido eliminada correctamente.');
        } catch (\Exception $e) {
            return redirect()->route('admin.desvinculacion.index')
                ->with('error', 'Ocurrio un error al intentar eliminar la solicitud: ' . $e->getMessage());
        }
    }

    public function generarActaLiberacion(SolicitudDesvinculacion $desvinculacion)
{
    require_once app_path('Libraries/tbs_class.php');
    require_once app_path('Libraries/tbs_plugin_opentbs.php');
    $templateName = 'acta_liberacion.docx';
    $templatePath = public_path('templates/' . $templateName);

    if (!file_exists($templatePath)) {
        return back()->with('error', "La plantilla {$templateName} no existe en public/templates.");
    }

    $desvinculacion->loadMissing(['user', 'equiposDetalle.equipo']);
    $usuario = $desvinculacion->user;

    if (!$usuario) {
        return back()->with('error', "La solicitud no tiene un usuario asociado.");
    }

    if ($desvinculacion->equiposDetalle->isEmpty()) {
        $textoObservacion = 'EL COLABORADOR NO POSEE ASIGNACIÓN DE EQUIPOS NI VALORES PENDIENTES';
    } else {
        $observacionesConDetalle = $desvinculacion->equiposDetalle
            ->filter(function ($detalle) {
                $obs = trim($detalle->observaciones ?? $detalle->observacion ?? '');
                return !empty($obs) && !in_array(mb_strtolower($obs), ['sin observaciones', 'sin novedad', 'ninguna', 'n/a', 'ok']);
            })
            ->map(function ($detalle) {
                $obs = trim($detalle->observaciones ?? $detalle->observacion);
                $nombreEquipo = $detalle->equipo->nombre
                    ?? $detalle->equipo->descripcion
                    ?? $detalle->nombre_equipo
                    ?? 'Equipo';

                return "{$nombreEquipo}: {$obs}";
            });

        if ($observacionesConDetalle->isNotEmpty()) {
            $textoObservacion = $observacionesConDetalle->implode(' | ');
        } else {
            $textoObservacion = 'NO EXISTE PENDIENTE';
        }
    }

    $TBS = new \clsTinyButStrong();
    $TBS->Plugin(TBS_INSTALL, OPENTBS_PLUGIN);
    $TBS->LoadTemplate($templatePath, OPENTBS_ALREADY_UTF8);

    $fechaHoy = now()->locale('es')->isoFormat('D [de] MMMM [de] YYYY');
    $TBS->MergeField('fecha', $fechaHoy);
    $TBS->MergeField('cedula', $usuario->cedula ?? 'N/A');
    $TBS->MergeField('nombres', mb_strtoupper($usuario->name ?? 'N/A'));
    $TBS->MergeField('contrato', '-');
    $TBS->MergeField('observacion', mb_strtoupper($textoObservacion));

    $folderPath = public_path('actas/liberaciones');
    if (!file_exists($folderPath)) {
        mkdir($folderPath, 0755, true);
    }

    $nombreLimpio = str_replace(' ', '_', strtolower($usuario->name ?? 'colaborador'));
    $fileName   = 'acta_liberacion_' . $nombreLimpio . '_' . now()->format('Ymd_His') . '.docx';
    $savePath   = $folderPath . '/' . $fileName;
    $TBS->Show(OPENTBS_FILE, $savePath);

    return response()->download($savePath, $fileName)->deleteFileAfterSend(true);
}

    
}

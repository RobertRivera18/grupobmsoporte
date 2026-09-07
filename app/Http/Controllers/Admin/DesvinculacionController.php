<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\SolicitudDesvinculacion;
use Illuminate\Http\Request;

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
        return view('admin.desvinculacion.edit', ['solicitud' => $desvinculacion]);
    }

    public function show(SolicitudDesvinculacion $desvinculacion)
    {
        $desvinculacion->load(['user', 'cuadrilla', 'equiposDetalle']);
        $equiposAsignados = $desvinculacion->cuadrilla ? $desvinculacion->cuadrilla->equipos : collect();

        return view('admin.desvinculacion.show', [
            'solicitud'        => $desvinculacion,
            'equiposAsignados' => $equiposAsignados,
        ]);
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

    $TBS = new \clsTinyButStrong();
    $TBS->Plugin(TBS_INSTALL, OPENTBS_PLUGIN);
    $TBS->LoadTemplate($templatePath, OPENTBS_ALREADY_UTF8);

    $fechaHoy = now()->locale('es')->isoFormat('D [de] MMMM [de] YYYY');
    $TBS->MergeField('fecha', $fechaHoy);
    $TBS->MergeField('cedula', $usuario->cedula ?? 'N/A');
    $TBS->MergeField('nombres', mb_strtoupper($usuario->name ?? 'N/A'));
    $TBS->MergeField('contrato', 'HAGGERSTON');
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

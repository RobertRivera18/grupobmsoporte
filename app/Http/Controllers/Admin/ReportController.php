<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Report;
use App\Models\Cuadrilla;
use App\Models\ReporteRecargaDetalle;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class ReportController extends Controller
{
    public function index()
    {
        return view('admin.reportes.index');
    }

    public function create()
    {
        return view('admin.reportes.create');
    }


   public function edit(Report $reporte)
{
    $rol = Auth::user()->getRoleNames()->first();

    // Construimos la base de la consulta
   $query = Cuadrilla::with(['equipos', 'users'])
        ->whereHas('equipos'); // Solo cuadrillas que tienen equipos asignados


    // 🔹 Filtrar cuadrillas según el rol del usuario
    if ($rol === 'operador1') {
        $query->where('cua_ciudad', 1); // Guayaquil
    } elseif ($rol === 'operador2') {
        $query->where('cua_ciudad', 2); // Quito
    }

    // Ejecutar la consulta
    $cuadrillas = $query->get();

    // Cargar relaciones necesarias
    $reporte->load('cuadrillas');

    // Cuadrillas ya seleccionadas en este reporte
    $cuadrillasSeleccionadas = $reporte->cuadrillas->pluck('id')->toArray();

    // Retornar a la vista
    return view('admin.reportes.edit', [
        'reporte' => $reporte,
        'cuadrillas' => $cuadrillas,
        'cuadrillasSeleccionadas' => $cuadrillasSeleccionadas,
        'rol' => $rol,
    ]);
}


  public function update(Request $request, Report $reporte)
{
    $request->validate([
        'seleccionadas'   => 'array',
        'seleccionadas.*' => 'exists:cuadrillas,id',
        'observaciones'   => 'array',
    ]);

    DB::transaction(function () use ($reporte, $request) {
        $valorRecarga = 10.50;
        $rol = Auth::user()->getRoleNames()->first();

        // 🔹 Determinar qué cuadrillas son visibles para este usuario
        $cuadrillasVisibles = Cuadrilla::query()
            ->when($rol === 'operador1', fn($q) => $q->where('cua_ciudad', 1))
            ->when($rol === 'operador2', fn($q) => $q->where('cua_ciudad', 2))
            ->pluck('id')
            ->toArray();

        $seleccionadas = array_unique($request->seleccionadas ?? []);
        $observacionesActuales = $reporte->detalles()
            ->pluck('observacion', 'cuadrilla_id')
            ->toArray();

        $pivotData = [];

        // 🔹 Crear o actualizar solo las cuadrillas seleccionadas
        foreach ($seleccionadas as $cuadrillaId) {
            $observacionNueva = $request->observaciones[$cuadrillaId] ?? null;
            $observacionFinal = $observacionNueva !== null
                ? $observacionNueva
                : ($observacionesActuales[$cuadrillaId] ?? null);

            $pivotData[$cuadrillaId] = [
                'valor_recarga' => $valorRecarga,
                'observacion'   => $observacionFinal,
            ];
        }

        // 🔹 Obtener todas las cuadrillas actuales del reporte
        $existentes = $reporte->cuadrillas()->pluck('cuadrilla_id')->toArray();

        // 🔹 Cuadrillas que este operador puede tocar (visibles) y desmarcó
        $desmarcadas = array_diff(
            array_intersect($existentes, $cuadrillasVisibles),
            $seleccionadas
        );

        // 🔹 Eliminar solo las desmarcadas visibles
        if (!empty($desmarcadas)) {
            $reporte->cuadrillas()->detach($desmarcadas);
        }

        // 🔹 Actualizar o mantener las demás cuadrillas
        foreach ($pivotData as $id => $data) {
            $reporte->cuadrillas()->syncWithoutDetaching([$id => $data]);
        }

        // 🔹 Recalcular total con base en todas las cuadrillas aún vinculadas
        $total = $reporte->cuadrillas()->count() * $valorRecarga;
        $reporte->update([
            'total' => $total,
            'fecha_registro' => now()->toDateString(),
        ]);
    });

    return redirect()
        ->route('admin.reportes.index')
        ->with('success', 'Reporte actualizado correctamente.');
}



    public function show() {}
}

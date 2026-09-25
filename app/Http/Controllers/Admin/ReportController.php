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
        if (!Auth::user()->hasRole('Admin')) {
            abort(403, 'No tienes permisos para crear reportes.');
        }

        return view('admin.reportes.create');
    }


    public function edit(Report $reporte)
    {
        return view('admin.reportes.edit', compact('reporte'));
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
            $existentes = $reporte->cuadrillas()->pluck('cuadrilla_id')->toArray();
            $desmarcadas = array_diff(
                array_intersect($existentes, $cuadrillasVisibles),
                $seleccionadas
            );
            if (!empty($desmarcadas)) {
                $reporte->cuadrillas()->detach($desmarcadas);
            }

            foreach ($pivotData as $id => $data) {
                $reporte->cuadrillas()->syncWithoutDetaching([$id => $data]);
            }

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

}

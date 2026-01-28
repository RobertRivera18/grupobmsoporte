<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Indumentaria;
use App\Models\IngresoIndumentaria;
use App\Models\InventarioIndumentaria;
use App\Models\Ubicacion;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class IngresoIndumentariaController extends Controller
{
    public function index()
    {
        return view('admin.indumentarias.ingresos.index', [
            'indumentarias' => Indumentaria::orderBy('nombre')->get(),
            'ubicaciones'   => Ubicacion::orderBy('nombre')->get(),
            'ingresos'      => IngresoIndumentaria::with([
                'indumentaria',
                'ubicacion',
                'user'
            ])->latest()->paginate(10),
        ]);
    }
    public function store(Request $request)
    {
        $request->validate([
            'indumentaria_id' => 'required|exists:indumentarias,id',
            'ubicacion_id'    => 'required|exists:ubicaciones,id',
            'cantidad'        => 'required|integer|min:1',
            'observacion'     => 'nullable|string',
        ]);

        DB::transaction(function () use ($request) {

            // 1️⃣ Guardar ingreso (historial)
            IngresoIndumentaria::create([
                'indumentaria_id' => $request->indumentaria_id,
                'ubicacion_id'    => $request->ubicacion_id,
                'cantidad'        => $request->cantidad,
                'observacion'     => $request->observacion,
                'user_id'         => auth()->id(),
            ]);

            // 2️⃣ Actualizar stock
            $inventario = InventarioIndumentaria::firstOrCreate(
                [
                    'indumentaria_id' => $request->indumentaria_id,
                    'ubicacion_id'    => $request->ubicacion_id,
                ],
                ['stock' => 0]
            );

            $inventario->increment('stock', $request->cantidad);
        });

        return back()->with('success', 'Ingreso registrado correctamente');
    }
}

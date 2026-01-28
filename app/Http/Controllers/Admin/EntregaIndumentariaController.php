<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\EntregaDetalleIndumentaria;
use App\Models\EntregaIndumentaria;
use App\Models\Indumentaria;
use App\Models\InventarioIndumentaria;
use App\Models\InventarioIndumentariaUsada;
use App\Models\Ubicacion;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class EntregaIndumentariaController extends Controller
{
 public function index()
{
    $user = auth()->user();
    $quitoId = 2;

    $indumentarias = Indumentaria::query()
        ->when($user->hasRole('operador2'), function ($query) use ($quitoId) {
            $query->where(function ($q) use ($quitoId) {

                // Inventario NUEVO
                $q->whereHas('inventarios', function ($inv) use ($quitoId) {
                    $inv->where('ubicacion_id', $quitoId)
                        ->where('stock', '>', 0);
                })

                // O inventario USADO
                ->orWhereHas('inventariosUsados', function ($inv) use ($quitoId) {
                    $inv->where('ubicacion_id', $quitoId)
                        ->where('stock', '>', 0);
                });

            });
        })
        ->orderBy('nombre')
        ->get();

    $ubicaciones = Ubicacion::query()
        ->when($user->hasRole('operador2'), fn ($q) => $q->where('id', $quitoId))
        ->orderBy('nombre')
        ->get();

    return view('admin.indumentarias.entregas.index', [
        'ubicaciones'   => $ubicaciones,
        'indumentarias' => $indumentarias,
    ]);
}




    public function store(Request $request)
    {
        DB::beginTransaction();

        $request->validate([
            'user_id' => 'required|exists:users,id',
            'ubicacion_id' => 'required|exists:ubicaciones,id',
            'fecha_entrega' => 'required|date',
            'indumentarias' => 'required|array|min:1',
            'indumentarias.*.id' => 'required|exists:indumentarias,id',
            'indumentarias.*.tipo' => 'required|in:nuevo,usado',
            'indumentarias.*.cantidad' => 'required|integer|min:1',
        ]);

        $entrega = EntregaIndumentaria::create([
            'user_id'       => $request->user_id,
            'ubicacion_id'  => $request->ubicacion_id,
            'fecha_entrega' => $request->fecha_entrega,
            'observacion'   => $request->observacion,
        ]);

        foreach ($request->indumentarias as $item) {

            // 👉 Obtener indumentaria (para el mensaje)
            $indumentaria = \App\Models\Indumentaria::find($item['id']);
            $tipoTexto = $item['tipo'] === 'nuevo' ? 'Nuevo' : 'Usado';

            // 👉 Buscar inventario correcto
            $inventario = $item['tipo'] === 'nuevo'
                ? InventarioIndumentaria::where('indumentaria_id', $item['id'])
                ->where('ubicacion_id', $request->ubicacion_id)
                ->lockForUpdate()
                ->first()
                : InventarioIndumentariaUsada::where('indumentaria_id', $item['id'])
                ->where('ubicacion_id', $request->ubicacion_id)
                ->lockForUpdate()
                ->first();

            /* ===============================
         * VALIDACIONES CLARAS
         * =============================== */

            // ❌ No existe inventario
            if (!$inventario) {
                DB::rollBack();
                return back()->withInput()->with('swal', [
                    'icon'  => 'error',
                    'title' => 'Inventario inexistente',
                    'text'  => "No existe inventario de {$indumentaria->nombre} ({$tipoTexto}) en esta bodega."
                ]);
            }

            // ❌ Stock insuficiente
            if ($inventario->stock < $item['cantidad']) {
                DB::rollBack();
                return back()->withInput()->with('swal', [
                    'icon'  => 'error',
                    'title' => 'Stock insuficiente',
                    'text'  => "Stock insuficiente de {$indumentaria->nombre} ({$tipoTexto}). "
                        . "Disponible: {$inventario->stock} | Solicitado: {$item['cantidad']}"
                ]);
            }

            /* ===============================
         * DESCONTAR STOCK
         * =============================== */
            $inventario->decrement('stock', $item['cantidad']);

            /* ===============================
         * GUARDAR DETALLE
         * =============================== */
            EntregaDetalleIndumentaria::create([
                'entrega_id'      => $entrega->id,
                'indumentaria_id' => $item['id'],
                'cantidad'       => $item['cantidad'],
                'tipo_inventario' => $item['tipo'],
            ]);
        }

        DB::commit();

        return redirect()->back()->with('swal', [
            'icon'  => 'success',
            'title' => 'Entrega registrada',
            'text'  => 'La entrega se registró correctamente'
        ]);
    }

    
}

<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Indumentaria;
use App\Models\InventarioIndumentaria;
use App\Models\InventarioIndumentariaUsada;
use App\Models\TraspasoDetalleIndumentaria;
use App\Models\TraspasoIndumentaria;
use App\Models\Ubicacion;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class TrasladosController extends Controller
{
    public function index()
    {
        $ubicaciones = Ubicacion::orderBy('nombre')->get();
        $indumentarias = Indumentaria::orderBy('nombre')->get();
        return view('admin.indumentarias.traslados.index', compact(
            'ubicaciones',
            'indumentarias'
        ));
    }
    public function store(Request $request)
    {
        DB::beginTransaction();

        try {

            /* =====================================================
         * VALIDACIÓN
         * ===================================================== */

            $request->validate([
                'ubicacion_origen_id'       => 'required|different:ubicacion_destino_id',
                'ubicacion_destino_id'      => 'required',
                'fecha'                     => 'required|date',
                'items'                     => 'required|array|min:1',
                'items.*.indumentaria_id'   => 'required|exists:indumentarias,id',
                'items.*.cantidad'          => 'required|integer|min:1',
                'items.*.tipo'              => 'required|in:nuevo,usado',
            ]);

            /* =====================================================
         * CABECERA DEL TRASPASO
         * ===================================================== */

            $traspaso = TraspasoIndumentaria::create([
                'ubicacion_origen_id'  => $request->ubicacion_origen_id,
                'ubicacion_destino_id' => $request->ubicacion_destino_id,
                'fecha'                => $request->fecha,
                'observacion'          => $request->observacion,
            ]);

            /* =====================================================
         * DETALLE DEL TRASPASO
         * ===================================================== */

            foreach ($request->items as $item) {

                $cantidad = (int) $item['cantidad'];
                $tipo     = $item['tipo']; // nuevo | usado

                /* ===== DEFINIR INVENTARIO SEGÚN TIPO ===== */

                $modeloInventario = $tipo === 'nuevo'
                    ? InventarioIndumentaria::class
                    : InventarioIndumentariaUsada::class;

                /* ===== INVENTARIO ORIGEN ===== */

                $inventarioOrigen = $modeloInventario::where([
                    'indumentaria_id' => $item['indumentaria_id'],
                    'ubicacion_id'    => $request->ubicacion_origen_id,
                ])->first();

                if (!$inventarioOrigen || $inventarioOrigen->stock < $cantidad) {
                    throw new \Exception(
                        "Stock insuficiente ({$tipo}) para la indumentaria seleccionada"
                    );
                }

                /* ===== RESTAR STOCK EN ORIGEN ===== */
                $inventarioOrigen->decrement('stock', $cantidad);

                /* ===== INVENTARIO DESTINO ===== */

                $inventarioDestino = $modeloInventario::firstOrCreate(
                    [
                        'indumentaria_id' => $item['indumentaria_id'],
                        'ubicacion_id'    => $request->ubicacion_destino_id,
                    ],
                    ['stock' => 0]
                );

                $inventarioDestino->increment('stock', $cantidad);

                /* ===== REGISTRAR DETALLE ===== */

                TraspasoDetalleIndumentaria::create([
                    'traspaso_id'     => $traspaso->id,
                    'indumentaria_id' => $item['indumentaria_id'],
                    'cantidad'        => $cantidad,
                    'tipo_inventario' => $tipo,
                ]);
            }

            DB::commit();

            return redirect()->back()->with('swal', [
                'icon'  => 'success',
                'title' => 'Traspaso registrado',
                'text'  => 'El traspaso se realizó correctamente'
            ]);
        } catch (\Exception $e) {

            DB::rollBack();

            return redirect()->back()->withInput()->with('swal', [
                'icon'  => 'error',
                'title' => 'Error en el traspaso',
                'text'  => $e->getMessage()
            ]);
        }
    }
}

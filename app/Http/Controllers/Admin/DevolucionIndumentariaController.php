<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\DevolucionDetalleIndumentaria;
use App\Models\DevolucionIndumentaria;
use App\Models\EntregaDetalleIndumentaria;
use App\Models\EntregaIndumentaria;
use App\Models\Indumentaria;
use App\Models\InventarioIndumentariaUsada;
use App\Models\Ubicacion;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DevolucionIndumentariaController extends Controller
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



    public function entregasPorEmpleado($userId)
    {
        $data = DB::table('entrega_indumentaria_detalle as d')
            ->join('entrega_indumentarias as e', 'e.id', '=', 'd.entrega_id')
            ->join('indumentarias as i', 'i.id', '=', 'd.indumentaria_id')
            ->select(
                'd.indumentaria_id',
                'i.nombre as indumentaria',
                DB::raw('SUM(d.cantidad) as total_entregado')
            )
            ->where('e.user_id', $userId)
            ->groupBy('d.indumentaria_id', 'i.nombre')
            ->get();

        return response()->json($data);
    }
    public function store(Request $request)
{
    DB::beginTransaction();

    try {

        /* ================= VALIDACIÓN ================= */

        $request->validate([
            'user_id'                               => 'required|exists:users,id',
            'ubicacion_id'                          => 'required|exists:ubicaciones,id',
            'fecha_devolucion'                      => 'required|date',
            'indumentarias'                         => 'required|array|min:1',
            'indumentarias.*.id'                    => 'required|exists:indumentarias,id',
            'indumentarias.*.cantidad'              => 'required|integer|min:1',
            'indumentarias.*.cantidad_reutilizable' => 'required|integer|min:0',
            'indumentarias.*.cantidad_baja'         => 'required|integer|min:0',
        ]);

        /* ================= CABECERA DEVOLUCIÓN ================= */

        $devolucion = DevolucionIndumentaria::create([
            'user_id'          => $request->user_id,
            'ubicacion_id'     => $request->ubicacion_id,
            'fecha_devolucion' => $request->fecha_devolucion,
            'observacion'      => $request->observacion,
            'es_historica'     => false,
        ]);

        /* ================= DETALLE ================= */

        foreach ($request->indumentarias as $item) {

            $total        = (int) $item['cantidad'];
            $reutilizable = (int) $item['cantidad_reutilizable'];
            $baja         = (int) $item['cantidad_baja'];

            /* ----- VALIDAR SUMA ----- */
            if (($reutilizable + $baja) !== $total) {
                throw new \Exception(
                    'La suma de reutilizable y baja debe ser igual al total devuelto'
                );
            }

            /* ----- TOTAL ENTREGADO REAL ----- */
            $totalEntregado = EntregaDetalleIndumentaria::where('indumentaria_id', $item['id'])
                ->whereHas('entrega', function ($q) use ($request) {
                    $q->where('user_id', $request->user_id)
                      ->where('es_historica', false);
                })
                ->sum('cantidad');

            /* ================= NO EXISTE ENTREGA → CREAR HISTÓRICA ================= */
            if ($totalEntregado === 0) {

                $entregaHistorica = EntregaIndumentaria::create([
                    'user_id'      => $request->user_id,
                    'ubicacion_id' => $request->ubicacion_id,
                    'fecha_entrega'=> $request->fecha_devolucion,
                    'observacion'  => 'Entrega histórica generada automáticamente por devolución',
                    'es_historica' => true,
                ]);

                EntregaDetalleIndumentaria::create([
                    'entrega_id'       => $entregaHistorica->id,
                    'indumentaria_id'  => $item['id'],
                    'cantidad'         => $total,
                ]);

                $totalEntregado = $total;
            }

            /* ----- TOTAL DEVUELTO ----- */
            $totalDevuelto = DevolucionDetalleIndumentaria::where('indumentaria_id', $item['id'])
                ->whereHas('devolucion', function ($q) use ($request) {
                    $q->where('user_id', $request->user_id)
                      ->where('es_historica', false);
                })
                ->sum('cantidad');

            $disponible = $totalEntregado - $totalDevuelto;

            if ($total > $disponible) {
                throw new \Exception(
                    'La cantidad devuelta supera lo entregado'
                );
            }

            /* ----- INVENTARIO USADO ----- */
            if ($reutilizable > 0) {
                $inventario = InventarioIndumentariaUsada::firstOrCreate(
                    [
                        'indumentaria_id' => $item['id'],
                        'ubicacion_id'    => $request->ubicacion_id,
                    ],
                    ['stock' => 0]
                );

                $inventario->increment('stock', $reutilizable);
            }

            /* ----- GUARDAR DETALLE DEVOLUCIÓN ----- */
            DevolucionDetalleIndumentaria::create([
                'devolucion_id'         => $devolucion->id,
                'indumentaria_id'       => $item['id'],
                'cantidad'              => $total,
                'cantidad_reutilizable' => $reutilizable,
                'cantidad_baja'         => $baja,
            ]);
        }

        DB::commit();

        return redirect()->back()->with('swal', [
            'icon'  => 'success',
            'title' => 'Devolución registrada',
            'text'  => 'La devolución se registró correctamente'
        ]);

    } catch (\Exception $e) {

        DB::rollBack();

        return redirect()->back()->withInput()->with('swal', [
            'icon'  => 'error',
            'title' => 'Error',
            'text'  => $e->getMessage()
        ]);
    }
}

}

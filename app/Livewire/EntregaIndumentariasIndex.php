<?php

namespace App\Livewire;

use App\Models\EntregaIndumentaria;
use App\Models\InventarioIndumentaria;
use App\Models\InventarioIndumentariaUsada;
use Illuminate\Support\Facades\DB;
use Livewire\Component;
use Livewire\WithPagination;

class EntregaIndumentariasIndex extends Component
{
    use WithPagination;

    protected $paginationTheme = 'tailwind'; 

    public function generarActa($entregaId)
{
    require_once app_path('Libraries/tbs_class.php');
    require_once app_path('Libraries/tbs_plugin_opentbs.php');

    $entrega = \App\Models\EntregaIndumentaria::with([
        'empleado',
        'ubicacion',
        'detalles.indumentaria'
    ])->find($entregaId);

    if (!$entrega) {
        $this->dispatch('error', message: 'Entrega no encontrada');
        return;
    }

    /* ===============================
     * INICIALIZAR TBS
     * =============================== */
    $TBS = new \clsTinyButStrong;
    $TBS->Plugin(TBS_INSTALL, OPENTBS_PLUGIN);

    $templatePath = public_path('templates/acta_entrega_indumentaria.docx');

    if (!file_exists($templatePath)) {
        $this->dispatch('error', message: 'Plantilla no encontrada');
        return;
    }

    $TBS->LoadTemplate($templatePath, OPENTBS_ALREADY_UTF8);

    /* ===============================
     * DATOS GENERALES
     * =============================== */
    $TBS->MergeField('empleado.nombre', $entrega->empleado->name);
    $TBS->MergeField('empleado.cedula', $entrega->empleado->cedula);
    $TBS->MergeField('ubicacion.nombre', $entrega->ubicacion->nombre);
    $TBS->MergeField(
        'fecha',
        \Carbon\Carbon::parse($entrega->fecha_entrega)->format('d/m/Y')
    );
    $TBS->MergeField('observacion', $entrega->observacion ?? '—');

    /* ===============================
     * CONSOLIDAR INDUMENTARIA
     * (ignora nuevo / usado)
     * =============================== */
    $detalleAgrupado = $entrega->detalles
        ->groupBy('indumentaria_id')
        ->map(function ($items) {
            $indumentaria = $items->first()->indumentaria;

            return [
                'articulo' => $indumentaria->nombre,
                'talla'    => $indumentaria->talla ?? '',
                'cantidad' => $items->sum('cantidad'),
            ];
        })
        ->values();

    /* ===============================
     * MERGE INDEXADO (WORD)
     * =============================== */
    $maxFilas = 10; // igual a las filas del Word

    foreach ($detalleAgrupado as $i => $item) {
        if ($i >= $maxFilas) break;

        $TBS->MergeField("detalle.articulo_$i", $item['articulo']);
        $TBS->MergeField("detalle.cantidad_$i", $item['cantidad']);
        $TBS->MergeField("detalle.talla_$i", $item['talla']);
    }

    /* ===============================
     * LIMPIAR FILAS SOBRANTES
     * =============================== */
    for ($i = $detalleAgrupado->count(); $i < $maxFilas; $i++) {
        $TBS->MergeField("detalle.articulo_$i", '');
        $TBS->MergeField("detalle.cantidad_$i", '');
        $TBS->MergeField("detalle.talla_$i", '');
    }

    /* ===============================
     * GENERAR ARCHIVO
     * =============================== */
    $fileName = 'acta_entrega_indumentaria_' . $entrega->id . '.docx';
    $savePath = storage_path('app/public/' . $fileName);

    $TBS->Show(OPENTBS_FILE, $savePath);

    return response()
        ->download($savePath)
        ->deleteFileAfterSend();
}

public function eliminar($entregaId)
{
    DB::beginTransaction();

    try {

        $entrega = EntregaIndumentaria::with('detalles')
            ->lockForUpdate()
            ->findOrFail($entregaId);

        foreach ($entrega->detalles as $detalle) {

            // 🔍 Obtener inventario correcto
            $inventario = $detalle->tipo_inventario === 'nuevo'
                ? InventarioIndumentaria::where('indumentaria_id', $detalle->indumentaria_id)
                    ->where('ubicacion_id', $entrega->ubicacion_id)
                    ->lockForUpdate()
                    ->first()
                : InventarioIndumentariaUsada::where('indumentaria_id', $detalle->indumentaria_id)
                    ->where('ubicacion_id', $entrega->ubicacion_id)
                    ->lockForUpdate()
                    ->first();

           
            if ($inventario) {
                $inventario->increment('stock', $detalle->cantidad);
            }
        }
        $entrega->detalles()->delete();
        $entrega->delete();

        DB::commit();

        $this->dispatch('swal', [
            'icon'  => 'success',
            'title' => 'Entrega eliminada',
            'text'  => 'El stock fue restaurado correctamente'
        ]);

    } catch (\Throwable $e) {

        DB::rollBack();

        report($e);

        $this->dispatch('swal', [
            'icon'  => 'error',
            'title' => 'Error',
            'text'  => 'No se pudo eliminar la entrega'
        ]);
    }
}

   public function render()
{
    $query = EntregaIndumentaria::with([
        'empleado',
        'ubicacion',
        'detalles.indumentaria'
    ]);

    if (auth()->user()->hasRole('operador2')) {
        $query->where('ubicacion_id', 2);
    }

    return view('livewire.entrega-indumentarias-index', [
        'entregas' => $query->latest()->paginate(10),
    ]);
}

}

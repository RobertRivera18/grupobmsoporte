<?php

namespace App\Livewire;

use App\Models\DevolucionIndumentaria;
use Livewire\Component;
use Livewire\WithPagination;

class DevolucionIndumentariaIndex extends Component
{
    use WithPagination;

    protected $paginationTheme = 'tailwind';
  public function generarActa($devolucionId)
{
    require_once app_path('Libraries/tbs_class.php');
    require_once app_path('Libraries/tbs_plugin_opentbs.php');

    $devolucion = \App\Models\DevolucionIndumentaria::with([
        'usuario',
        'ubicacion',
        'detalles.indumentaria'
    ])->find($devolucionId);

    if (!$devolucion) {
        $this->dispatch('error', message: 'Devolución no encontrada');
        return;
    }

    /* ===============================
     * INICIALIZAR TBS
     * =============================== */
    $TBS = new \clsTinyButStrong;
    $TBS->Plugin(TBS_INSTALL, OPENTBS_PLUGIN);

    $templatePath = public_path('templates/acta_devolucion_indumentaria.docx');

    if (!file_exists($templatePath)) {
        $this->dispatch('error', message: 'Plantilla no encontrada');
        return;
    }

    $TBS->LoadTemplate($templatePath, OPENTBS_ALREADY_UTF8);

    /* ===============================
     * DATOS GENERALES
     * =============================== */
    $TBS->MergeField('empleado.nombre', $devolucion->usuario->name);
    $TBS->MergeField('empleado.cedula', $devolucion->usuario->cedula);
    $TBS->MergeField('ubicacion.nombre', $devolucion->ubicacion->nombre);
    $TBS->MergeField(
        'fecha',
        \Carbon\Carbon::parse($devolucion->fecha_devolucion)->format('d/m/Y')
    );
    $TBS->MergeField('observacion', $devolucion->observacion ?? '—');

    /* ===============================
     * CONSOLIDAR DETALLE (CORRECTO)
     * =============================== */
    $detalleFinal = collect();

    $devolucion->detalles
        ->groupBy('indumentaria_id')
        ->each(function ($items) use (&$detalleFinal) {

            $indumentaria = $items->first()->indumentaria;

            $totalReutilizable = $items->sum('cantidad_reutilizable');
            $totalBaja         = $items->sum('cantidad_baja');

            // 👉 REUTILIZABLE → BUEN ESTADO
            if ($totalReutilizable > 0) {
                $detalleFinal->push([
                    'articulo' => $indumentaria->nombre,
                    'talla'    => $indumentaria->talla ?? '',
                    'cantidad' => $totalReutilizable,
                    'estado'   => 'Buen estado',
                ]);
            }

            // 👉 BAJA → MAL ESTADO
            if ($totalBaja > 0) {
                $detalleFinal->push([
                    'articulo' => $indumentaria->nombre,
                    'talla'    => $indumentaria->talla ?? '',
                    'cantidad' => $totalBaja,
                    'estado'   => 'Mal estado',
                ]);
            }
        });

    /* ===============================
     * MERGE INDEXADO (WORD)
     * =============================== */
    $maxFilas = 10; // igual al número de filas del Word

    foreach ($detalleFinal as $i => $item) {
        if ($i >= $maxFilas) break;

        $TBS->MergeField("detalle.articulo_$i", $item['articulo']);
        $TBS->MergeField("detalle.talla_$i", $item['talla']);
        $TBS->MergeField("detalle.cantidad_$i", $item['cantidad']);
        $TBS->MergeField("detalle.estado_$i", $item['estado']);
    }

    /* ===============================
     * LIMPIAR FILAS SOBRANTES
     * =============================== */
    for ($i = $detalleFinal->count(); $i < $maxFilas; $i++) {
        $TBS->MergeField("detalle.articulo_$i", '');
        $TBS->MergeField("detalle.talla_$i", '');
        $TBS->MergeField("detalle.cantidad_$i", '');
        $TBS->MergeField("detalle.estado_$i", '');
    }

    /* ===============================
     * GENERAR ARCHIVO
     * =============================== */
    $fileName = 'acta_devolucion_indumentaria_' . $devolucion->id . '.docx';
    $savePath = storage_path('app/public/' . $fileName);

    $TBS->Show(OPENTBS_FILE, $savePath);

    return response()
        ->download($savePath)
        ->deleteFileAfterSend();
}


    public function render()
    {
        $devoluciones = DevolucionIndumentaria::with([
            'usuario',
            'ubicacion',
            'detalles.indumentaria'
        ])
            ->orderBy('fecha_devolucion', 'desc')
            ->paginate(10);

        return view('livewire.devolucion-indumentaria-index', [
            'devoluciones' => $devoluciones
        ]);
    }
}

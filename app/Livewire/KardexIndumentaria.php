<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\User;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

class KardexIndumentaria extends Component
{
    public string $search = '';
    public ?int $colaboradorId = null;
    public ?User $colaborador = null;

    /* ===============================
     * SELECCIONAR COLABORADOR
     * =============================== */
    public function seleccionarColaborador(int $id): void
    {
        $this->colaboradorId = $id;
        $this->colaborador   = User::find($id);
        $this->reset('search');
    }

    public function limpiarColaborador(): void
    {
        $this->reset(['colaboradorId', 'colaborador', 'search']);
    }

    /* ===============================
     * BUSCADOR DE COLABORADORES
     * =============================== */
    public function getColaboradoresProperty(): Collection
    {
        if (strlen($this->search) < 2) {
            return collect();
        }

        return User::where('name', 'like', "%{$this->search}%")
            ->orderBy('name')
            ->limit(10)
            ->get();
    }

    /* ===============================
     * KÁRDEX
     * =============================== */
    public function getKardexProperty(): Collection
{
    if (!$this->colaboradorId) {
        return collect();
    }

    /* ================= ENTREGAS ================= */
    $entregas = DB::table('entrega_indumentaria_detalle as d')
        ->join('entregas_indumentaria as e', 'e.id', '=', 'd.entrega_id')
        ->join('indumentarias as i', 'i.id', '=', 'd.indumentaria_id')
        ->where('e.user_id', $this->colaboradorId)
        ->select(
            'd.created_at as fecha',
            'i.nombre as articulo',
            DB::raw("'ENTREGA' as tipo"),
            DB::raw("
                SUM(CASE WHEN d.tipo_inventario = 'nuevo'
                THEN d.cantidad ELSE 0 END) as nuevo
            "),
            DB::raw("
                SUM(CASE WHEN d.tipo_inventario = 'usado'
                THEN d.cantidad ELSE 0 END) as usado
            "),
            DB::raw('0 as baja')
        )
        ->groupBy('fecha', 'articulo');

    /* ================= DEVOLUCIONES ================= */
    $devoluciones = DB::table('devolucion_indumentaria_detalle as d')
        ->join('devoluciones_indumentaria as dev', 'dev.id', '=', 'd.devolucion_id')
        ->join('indumentarias as i', 'i.id', '=', 'd.indumentaria_id')
        ->where('dev.user_id', $this->colaboradorId)
        ->select(
            'd.created_at as fecha',
            'i.nombre as articulo',
            DB::raw("'DEVOLUCIÓN' as tipo"),
            DB::raw('0 as nuevo'),
            DB::raw('SUM(d.cantidad_reutilizable) as usado'),
            DB::raw('SUM(d.cantidad_baja) as baja')
        )
        ->groupBy('fecha', 'articulo');

    return $entregas
        ->unionAll($devoluciones)
        ->orderBy('fecha', 'desc')
        ->get();
}


    public function render()
    {
        return view('livewire.kardex-indumentaria');
    }
}

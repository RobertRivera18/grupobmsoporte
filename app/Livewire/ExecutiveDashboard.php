<?php

namespace App\Livewire;

use Livewire\Component;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Cache;

class ExecutiveDashboard extends Component
{
    public $selectedAnio = 2026;
    public $selectedArea = 'todas';
    public $selectedFrecuencia = 'todas';
    public $selectedIndicadorId = null;

    private const MESES_POR_FRECUENCIA = [
        'mensual'    => [1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12],
        'trimestral' => [3, 6, 9, 12],
        'semestral'  => [6, 12],
        'anual'      => [12],
    ];

    private const PANEL_CACHE_SEGUNDOS = 30;

    public function mount()
    {
        $this->selectedIndicadorId = DB::table('indicadores')
            ->orderBy('id')
            ->value('id');
    }

    public function updatedSelectedArea()
    {
        $this->reconciliarIndicadorSeleccionado();
    }

    public function updatedSelectedAnio()
    {
        $this->reconciliarIndicadorSeleccionado();
    }

    public function updatedSelectedFrecuencia()
    {
        $this->reconciliarIndicadorSeleccionado();
    }

    public function selectIndicador($id)
    {
        $this->selectedIndicadorId = $id;
    }

    private function mesesAplicables(?string $frecuenciaNombre): array
    {
        $key = strtolower(trim($frecuenciaNombre ?? 'mensual'));
        return self::MESES_POR_FRECUENCIA[$key] ?? self::MESES_POR_FRECUENCIA['mensual'];
    }

    /**
     * Query base reutilizable: indicadores_anio + indicadores + filtros de
     * año / área / frecuencia. Se usa como punto único de partida para
     * evitar repetir los mismos joins/where en varios sitios.
     */
    private function baseIndicadoresAnioQuery()
    {
        $query = DB::table('indicadores_anio as ia')
            ->join('indicadores as i', 'i.id', '=', 'ia.indicador_id')
            ->where('ia.anio', $this->selectedAnio);

        return $this->aplicarFiltros($query);
    }

    private function aplicarFiltros($query, string $aliasIndicadores = 'i')
    {
        if ($this->selectedArea !== 'todas') {
            $query->where("{$aliasIndicadores}.area_id", $this->selectedArea);
        }

        if ($this->selectedFrecuencia !== 'todas') {
            $query->where("{$aliasIndicadores}.frecuencia_id", $this->selectedFrecuencia);
        }

        return $query;
    }

    /**
     * Sufijo de cache determinado por los filtros activos. Todo lo que no
     * dependa del indicador seleccionado se cachea con esta clave.
     */
    private function panelCacheSufijo(): string
    {
        return "{$this->selectedAnio}:{$this->selectedArea}:{$this->selectedFrecuencia}";
    }

    private function panelCacheTtl()
    {
        return now()->addSeconds(self::PANEL_CACHE_SEGUNDOS);
    }
    private function reconciliarIndicadorSeleccionado()
    {
        $idsValidos = $this->baseIndicadoresAnioQuery()
            ->orderBy('i.id')
            ->pluck('i.id');

        if (!$idsValidos->contains($this->selectedIndicadorId)) {
            $this->selectedIndicadorId = $idsValidos->first();
        }
    }

    private function calcularMetricasGlobales(): object
    {
        $data = Cache::remember(
            "dashboard:metricas:{$this->panelCacheSufijo()}",
            $this->panelCacheTtl(),
            function () {
                $sub = $this->baseIndicadoresAnioQuery()->selectRaw("
                    ia.resultado_obtenido,
                    ia.meta,
                    CASE WHEN ia.meta > 0
                        THEN (CAST(ia.resultado_obtenido AS DECIMAL(10,2)) / ia.meta)
                    END as ratio
                ");

                $row = DB::query()->fromSub($sub, 't')
                    ->selectRaw("
                        AVG(ratio) * 100 as cumplimiento_global,
                        SUM(CASE WHEN resultado_obtenido < meta THEN 1 ELSE 0 END) as en_rojo,
                        SUM(CASE WHEN ratio >= 1 THEN 1 ELSE 0 END) as en_meta,
                        SUM(CASE WHEN ratio BETWEEN 0.85 AND 0.999999 THEN 1 ELSE 0 END) as en_riesgo,
                        SUM(CASE WHEN ratio < 0.85 THEN 1 ELSE 0 END) as critico
                    ")
                    ->first();

                return [
                    'cumplimiento_global' => (float) ($row->cumplimiento_global ?? 0),
                    'en_rojo'             => (int) ($row->en_rojo ?? 0),
                    'en_meta'             => (int) ($row->en_meta ?? 0),
                    'en_riesgo'           => (int) ($row->en_riesgo ?? 0),
                    'critico'             => (int) ($row->critico ?? 0),
                ];
            }
        );

        return (object) $data;
    }

    private function catalogos(): array
    {
        return [
            'areas' => Cache::remember('dashboard:areas', now()->addHours(6), function () {
                return DB::table('areas')->get();
            }),
            'frecuencias' => Cache::remember('dashboard:frecuencias', now()->addHours(6), function () {
                return DB::table('frecuencias_indicadores')->orderBy('id')->get();
            }),
        ];
    }


    private function calcularHeatmap()
    {
        return Cache::remember(
            "dashboard:heatmap:{$this->panelCacheSufijo()}",
            $this->panelCacheTtl(),
            function () {
                $heatmapQuery = DB::table('indicadores as i')
                    ->join('indicadores_anio as ia', 'i.id', '=', 'ia.indicador_id')
                    ->leftJoin('seguimientos as s', 'ia.id', '=', 's.indicador_anio_id')
                    ->leftJoin('frecuencias_indicadores as f', 'i.frecuencia_id', '=', 'f.id')
                    ->select(
                        'i.id as indicador_id',
                        'i.nombre as indicador_nombre',
                        'i.area_id',
                        'ia.meta',
                        'ia.resultado_obtenido',
                        'f.nombre as frecuencia_nombre',
                        's.mes',
                        's.valor'
                    )
                    ->where('ia.anio', $this->selectedAnio);

                $this->aplicarFiltros($heatmapQuery);

                return $heatmapQuery->get()->groupBy('indicador_id');
            }
        );
    }


    private function calcularSerieIndicador(): array
    {
        $indicadorInfo = null;
        $chartActual = array_fill(0, 12, null);
        $chartAnterior = array_fill(0, 12, null);
        $metaActual = 0;

        if (!$this->selectedIndicadorId) {
            return [$indicadorInfo, $chartActual, $chartAnterior, $metaActual];
        }

        $indicadorInfo = DB::table('indicadores as i')
            ->leftJoin('frecuencias_indicadores as f', 'i.frecuencia_id', '=', 'f.id')
            ->where('i.id', $this->selectedIndicadorId)
            ->select('i.*', 'f.nombre as frecuencia_nombre')
            ->first();

        $mesesAplicables = $this->mesesAplicables($indicadorInfo->frecuencia_nombre ?? null);

        $cacheKey = "dashboard:serie:{$this->selectedIndicadorId}:{$this->selectedAnio}";

        ['actual' => $indAnioActual, 'anterior' => $indAnioAnt, 'seguimientos' => $seguimientos] =
            Cache::remember($cacheKey, $this->panelCacheTtl(), function () {
                // Traemos año actual y año anterior en una sola consulta.
                $aniosData = DB::table('indicadores_anio')
                    ->where('indicador_id', $this->selectedIndicadorId)
                    ->whereIn('anio', [$this->selectedAnio, $this->selectedAnio - 1])
                    ->get()
                    ->keyBy('anio');

                $indAnioActual = $aniosData->get($this->selectedAnio);
                $indAnioAnt = $aniosData->get($this->selectedAnio - 1);

                $idsAnio = array_filter([
                    $indAnioActual->id ?? null,
                    $indAnioAnt->id ?? null,
                ]);

                $seguimientos = empty($idsAnio)
                    ? collect()
                    : DB::table('seguimientos')
                    ->whereIn('indicador_anio_id', $idsAnio)
                    ->get()
                    ->groupBy('indicador_anio_id');

                return [
                    'actual' => $indAnioActual,
                    'anterior' => $indAnioAnt,
                    'seguimientos' => $seguimientos,
                ];
            });

        if ($indAnioActual) {
            $metaActual = $indAnioActual->meta;
            $segActual = ($seguimientos->get($indAnioActual->id) ?? collect())
                ->pluck('valor', 'mes');

            foreach (range(1, 12) as $m) {
                $chartActual[$m - 1] = in_array($m, $mesesAplicables, true)
                    ? (isset($segActual[$m]) ? (float) $segActual[$m] : null)
                    : null;
            }
        }

        if ($indAnioAnt) {
            $segAnt = ($seguimientos->get($indAnioAnt->id) ?? collect())
                ->pluck('valor', 'mes');

            foreach (range(1, 12) as $m) {
                $chartAnterior[$m - 1] = in_array($m, $mesesAplicables, true)
                    ? (isset($segAnt[$m]) ? (float) $segAnt[$m] : null)
                    : null;
            }
        }

        return [$indicadorInfo, $chartActual, $chartAnterior, $metaActual];
    }


    private function calcularRankingAreas()
    {
        return Cache::remember(
            "dashboard:ranking:{$this->panelCacheSufijo()}",
            $this->panelCacheTtl(),
            function () {
                $rankingQuery = DB::table('areas as a')
                    ->join('indicadores as i', 'a.id', '=', 'i.area_id')
                    ->join('indicadores_anio as ia', 'i.id', '=', 'ia.indicador_id')
                    ->where('ia.anio', $this->selectedAnio)
                    ->where('ia.meta', '>', 0);

                if ($this->selectedArea !== 'todas') {
                    $rankingQuery->where('a.id', $this->selectedArea);
                }
                if ($this->selectedFrecuencia !== 'todas') {
                    $rankingQuery->where('i.frecuencia_id', $this->selectedFrecuencia);
                }

                return $rankingQuery
                    ->groupBy('a.id', 'a.nombre')
                    ->select('a.nombre', DB::raw('ROUND(AVG((CAST(ia.resultado_obtenido AS DECIMAL(10,2)) / ia.meta) * 100), 1) as promedio'))
                    ->orderByDesc('promedio')
                    ->get();
            }
        );
    }


    private function calcularMacroMensual(): array
    {
        $macroMensual = Cache::remember(
            "dashboard:macro:{$this->panelCacheSufijo()}",
            $this->panelCacheTtl(),
            function () {
                $macroQuery = DB::table('seguimientos as s')
                    ->join('indicadores_anio as ia', 's.indicador_anio_id', '=', 'ia.id')
                    ->join('indicadores as i', 'i.id', '=', 'ia.indicador_id')
                    ->where('ia.anio', $this->selectedAnio);
                $this->aplicarFiltros($macroQuery);

                return $macroQuery
                    ->groupBy('s.mes')
                    ->select('s.mes', DB::raw('SUM(s.valor) as total'))
                    ->pluck('total', 'mes')
                    ->toArray();
            }
        );

        $macroMensualData = array_fill(0, 12, 0);
        foreach (range(1, 12) as $m) {
            $macroMensualData[$m - 1] = isset($macroMensual[$m]) ? (float) $macroMensual[$m] : 0;
        }

        return $macroMensualData;
    }


    public static function flushDashboardCache(?int $anio = null): void
    {
        if ($anio === null) {
            return;
        }
    }

    public function render()
    {
        $catalogos = $this->catalogos();
        $metricas = $this->calcularMetricasGlobales();
        $heatmapData = $this->calcularHeatmap();
        [$indicadorInfo, $chartActual, $chartAnterior, $metaActual] = $this->calcularSerieIndicador();
        $rankingAreas = $this->calcularRankingAreas();
        $macroMensualData = $this->calcularMacroMensual();

        $this->dispatch('update-executive-charts', [
            'interanual' => [
                'actual' => $chartActual,
                'anterior' => $chartAnterior,
                'meta' => (float) $metaActual,
                'label' => $indicadorInfo->nombre ?? 'Indicador',
                'anioActual' => $this->selectedAnio,
                'anioAnterior' => $this->selectedAnio - 1,
            ],
            'semaforo' => [
                $metricas->en_meta,
                $metricas->en_riesgo,
                $metricas->critico,
            ],
            'ranking' => [
                'labels' => $rankingAreas->pluck('nombre')->toArray(),
                'values' => $rankingAreas->pluck('promedio')->toArray(),
            ],
            'macroMensual' => $macroMensualData,
        ]);

        return view('livewire.executive-dashboard', [
            'areas' => $catalogos['areas'],
            'frecuencias' => $catalogos['frecuencias'],
            'cumplimientoGlobal' => round($metricas->cumplimiento_global, 1),
            'indicadoresEnRojo' => $metricas->en_rojo,
            'heatmapData' => $heatmapData,
            'indicadorInfo' => $indicadorInfo,
        ])->layout('layouts.admin');
    }
}

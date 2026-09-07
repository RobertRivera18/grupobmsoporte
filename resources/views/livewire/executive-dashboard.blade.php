<div class="min-h-screen bg-slate-50 text-slate-900 p-4 sm:p-6 space-y-6">

    {{-- Header & Filtros --}}
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 border-b border-slate-200 pb-4">
        <div>
            <h1 class="text-2xl font-bold tracking-tight text-slate-900 flex items-center gap-2">
                📊 Resumen Ejecutivo de Indicadores
            </h1>
            <p class="text-xs text-slate-500">Panel Gerencial de Salud Operativa y Comparativa Interanual</p>
        </div>

        <div class="flex items-center gap-3">
            <div>
                <label class="text-[10px] uppercase text-slate-500 font-semibold block mb-1">Año</label>
                <select wire:model.live="selectedAnio"
                    class="bg-white border border-slate-300 text-xs rounded-lg px-3 py-1.5 text-slate-900 focus:ring-2 focus:ring-sky-500 outline-none">
                    <option value="2026">2026</option>
                    <option value="2025">2025</option>
                    <option value="2024">2024</option>
                </select>
            </div>
            <div>
                <label class="text-[10px] uppercase text-slate-500 font-semibold block mb-1">Área</label>
                <select wire:model.live="selectedArea"
                    class="bg-white border border-slate-300 text-xs rounded-lg px-3 py-1.5 text-slate-900 focus:ring-2 focus:ring-sky-500 outline-none">
                    <option value="todas">Todas las Áreas</option>
                    @foreach ($areas as $area)
                        <option value="{{ $area->id }}">{{ $area->nombre }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="text-[10px] uppercase text-slate-500 font-semibold block mb-1">Frecuencia</label>
                <select wire:model.live="selectedFrecuencia"
                    class="bg-white border border-slate-300 text-xs rounded-lg px-3 py-1.5 text-slate-900 focus:ring-2 focus:ring-sky-500 outline-none">
                    <option value="todas">Todas</option>
                    @foreach ($frecuencias as $f)
                        <option value="{{ $f->id }}">{{ $f->nombre }}</option>
                    @endforeach
                </select>
            </div>
        </div>
    </div>

    {{-- KPIs Macro --}}
    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
        <div class="bg-white border border-slate-200 rounded-xl p-4 flex items-center justify-between shadow-sm">
            <div>
                <p class="text-xs text-slate-500 font-medium">Índice Global de Cumplimiento</p>
                <h3 class="text-2xl font-black text-emerald-500 mt-1">{{ $cumplimientoGlobal }}%</h3>
                <span class="text-[10px] text-slate-400">Promedio general vs Meta</span>
            </div>
            <div
                class="w-12 h-12 rounded-full border-4 border-emerald-500/20 border-t-emerald-500 flex items-center justify-center font-bold text-xs text-emerald-500">
                {{ $cumplimientoGlobal }}%
            </div>
        </div>

        <div class="bg-white border border-slate-200 rounded-xl p-4 flex items-center justify-between shadow-sm">
            <div>
                <p class="text-xs text-slate-500 font-medium">Indicadores Fuera de Meta</p>
                <h3 class="text-2xl font-black text-rose-500 mt-1">{{ $indicadoresEnRojo }}</h3>
                <span class="text-[10px] text-rose-500/80">Año {{ $selectedAnio }}</span>
            </div>
            <div class="p-3 bg-rose-500/10 rounded-lg text-rose-500 font-bold text-xl">⚠️</div>
        </div>

        <div class="bg-white border border-slate-200 rounded-xl p-4 flex items-center justify-between shadow-sm">
            <div>
                <p class="text-xs text-slate-500 font-medium">Indicador en Análisis</p>
                <h3 class="text-sm font-semibold text-sky-600 mt-1 truncate max-w-[200px]"
                    title="{{ $indicadorInfo->nombre ?? '' }}">
                    {{ $heatmapData->isNotEmpty() ? $indicadorInfo->nombre ?? 'Seleccione uno' : 'Sin registros' }}
                </h3>
                <span class="text-[10px] text-slate-400">
                    @if ($indicadorInfo && $indicadorInfo->frecuencia_nombre ?? false)
                        Frecuencia: {{ $indicadorInfo->frecuencia_nombre }}
                    @else
                        Haz clic en la tabla para cambiar
                    @endif
                </span>
            </div>
            <div class="p-3 bg-sky-500/10 rounded-lg text-sky-600 font-bold text-xl">📈</div>
        </div>
    </div>

    {{-- Bloque Principal: Heatmap + Comparativa Interanual --}}
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-6">

        {{-- Heatmap --}}
        <div
            class="lg:col-span-5 bg-white border border-slate-200 rounded-xl p-4 flex flex-col justify-between shadow-sm">
            <div>
                <h2 class="text-sm font-bold text-slate-900 mb-3 flex items-center gap-2">
                    🗺️ Matriz de Salud Operativa (Cumplimiento por Mes)
                </h2>

                <div class="overflow-x-auto">
                    <table class="w-full text-[11px] border-collapse">
                        <thead>
                            <tr class="text-slate-400 border-b border-slate-200">
                                <th class="text-left py-2 px-1 font-semibold">Indicador</th>
                                @foreach (['E', 'F', 'M', 'A', 'M', 'J', 'J', 'A', 'S', 'O', 'N', 'D'] as $m)
                                    <th class="text-center py-2 px-1 font-semibold">{{ $m }}</th>
                                @endforeach
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100">
                            @forelse($heatmapData as $indId => $registros)
                                @php
                                    $first = $registros->first();
                                    $meta = $first->meta ?? 1;
                                    $meses = $registros->pluck('valor', 'mes')->toArray();

                                    // Meses en los que este indicador debe reportar, según su frecuencia.
                                    // Debe coincidir con MESES_POR_FRECUENCIA del componente.
                                    $mesesAplicables = match (
                                        strtolower(trim($first->frecuencia_nombre ?? 'mensual'))
                                    ) {
                                        'trimestral' => [3, 6, 9, 12],
                                        'semestral' => [6, 12],
                                        'anual' => [12],
                                        default => [1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12],
                                    };
                                @endphp
                                <tr wire:click="selectIndicador({{ $indId }})"
                                    class="cursor-pointer hover:bg-slate-100 transition-colors {{ $selectedIndicadorId == $indId ? 'bg-sky-50 text-slate-900' : '' }}">
                                    <td class="py-2 px-1 font-medium text-slate-600 truncate max-w-[110px]"
                                        title="{{ $first->indicador_nombre }}">
                                        {{ $first->indicador_nombre }}
                                        <span class="text-[9px] text-slate-400 block font-normal">
                                            {{ $first->frecuencia_nombre ?? 'Mensual' }}
                                        </span>
                                    </td>
                                    @foreach (range(1, 12) as $mes)
                                        @php
                                            $aplica = in_array($mes, $mesesAplicables, true);
                                            $val = $meses[$mes] ?? null;
                                            $pct = $val !== null && $meta > 0 ? ($val / $meta) * 100 : null;

                                            if (!$aplica) {
                                                // Mes fuera de la periodicidad del indicador: no es alerta, es normal.
                                                $bg = 'bg-slate-50 text-slate-300';
                                            } elseif ($pct === null) {
                                                // Debía reportar este mes y no hay registro: alerta de dato faltante.
                                                $bg =
                                                    'bg-slate-100 text-slate-400 border border-dashed border-slate-300';
                                            } elseif ($pct >= 100) {
                                                $bg = 'bg-emerald-100 text-emerald-600 border border-emerald-300';
                                            } elseif ($pct >= 85) {
                                                $bg = 'bg-amber-100 text-amber-600 border border-amber-300';
                                            } else {
                                                $bg = 'bg-rose-100 text-rose-600 border border-rose-300';
                                            }
                                        @endphp
                                        <td class="text-center p-0.5">
                                            <div class="w-full py-1 rounded text-[9px] font-bold {{ $bg }}">
                                                {{ $aplica ? $val ?? '-' : '·' }}
                                            </div>
                                        </td>
                                    @endforeach
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="13" class="text-center py-8 text-slate-400 text-xs">
                                        No hay información registrada para los filtros seleccionados.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            <div
                class="flex flex-wrap items-center gap-4 text-[10px] text-slate-500 mt-4 pt-3 border-t border-slate-200">
                <span class="flex items-center gap-1"><span
                        class="w-2.5 h-2.5 rounded bg-emerald-100 border border-emerald-500"></span> ≥100% Meta</span>
                <span class="flex items-center gap-1"><span
                        class="w-2.5 h-2.5 rounded bg-amber-100 border border-amber-500"></span> 85-99%</span>
                <span class="flex items-center gap-1"><span
                        class="w-2.5 h-2.5 rounded bg-rose-100 border border-rose-500"></span> &lt;85%</span>
                <span class="flex items-center gap-1"><span
                        class="w-2.5 h-2.5 rounded bg-slate-100 border border-dashed border-slate-300"></span> Sin
                    reportar</span>
                <span class="flex items-center gap-1"><span
                        class="w-2.5 h-2.5 rounded bg-slate-50 border border-slate-200"></span> No aplica (·)</span>
            </div>
        </div>

        {{-- Interanual Chart / Empty State --}}
        <div
            class="lg:col-span-7 bg-white border border-slate-200 rounded-xl p-4 flex flex-col justify-between shadow-sm">
            @if ($heatmapData->isNotEmpty() && $indicadorInfo)
                <div>
                    <h2 class="text-sm font-bold text-slate-900 mb-1">Comparativa Interanual</h2>
                    <p class="text-xs text-slate-500 mb-4">
                        {{ $indicadorInfo->nombre }}
                        @if ($indicadorInfo->frecuencia_nombre ?? false)
                            <span class="text-slate-400">· {{ $indicadorInfo->frecuencia_nombre }}</span>
                        @endif
                    </p>

                    <div class="h-64 relative" wire:ignore>
                        <canvas id="interanualChart"></canvas>
                    </div>
                </div>

                <div class="mt-4 p-3 bg-slate-50 border border-slate-200 rounded-lg text-xs space-y-1">
                    <span class="font-bold text-sky-600">💡 Análisis Rápido:</span>
                    <p class="text-slate-600">
                        Fórmula de cálculo: <span
                            class="italic text-slate-500">{{ $indicadorInfo->forma_calculo ?? 'N/A' }}</span>.
                        @if ($indicadorInfo->accion_tomar)
                            <br><span class="text-amber-600 font-semibold">Acción preventiva registrada:</span>
                            {{ $indicadorInfo->accion_tomar }}
                        @endif
                    </p>
                </div>
            @else
                <div class="h-full min-h-[300px] flex flex-col items-center justify-center text-center p-6 space-y-3">
                    <div
                        class="w-16 h-16 rounded-full bg-slate-100 border border-slate-300 flex items-center justify-center text-2xl text-slate-400">
                        📊
                    </div>
                    <div>
                        <h3 class="text-sm font-semibold text-slate-600">No hay datos por presentar</h3>
                        <p class="text-xs text-slate-400 max-w-sm mt-1">
                            El área, año o frecuencia seleccionados no cuentan con indicadores registrados o
                            seguimientos llenados.
                        </p>
                    </div>
                </div>
            @endif
        </div>
    </div>


    @if ($heatmapData->isNotEmpty())
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">

            {{-- 1. Gráfico Dona: Semáforo Operativo --}}
            <div class="bg-white border border-slate-200 rounded-xl p-4 shadow-sm">
                <h3 class="text-sm font-bold text-slate-900 mb-1">🔴🟡🟢 Distribución de Cumplimiento</h3>
                <p class="text-xs text-slate-500 mb-4">Estado general del año {{ $selectedAnio }}</p>
                <div class="h-56 relative" wire:ignore>
                    <canvas id="semaforoChart"></canvas>
                </div>
            </div>

            {{-- 2. Gráfico Barras: Ranking por Área --}}
            <div class="bg-white border border-slate-200 rounded-xl p-4 shadow-sm">
                <h3 class="text-sm font-bold text-slate-900 mb-1">🏢 Cumplimiento Promedio por Área</h3>
                <p class="text-xs text-slate-500 mb-4">Ranking departamental (%)</p>
                <div class="h-56 relative" wire:ignore>
                    <canvas id="rankingChart"></canvas>
                </div>
            </div>

            {{-- 3. Gráfico Barras: Proyección Macro Mensual --}}
            <div class="bg-white border border-slate-200 rounded-xl p-4 shadow-sm">
                <h3 class="text-sm font-bold text-slate-900 mb-1">📅 Acumulado Operativo Mensual</h3>
                <p class="text-xs text-slate-500 mb-4">Suma de valores registrados por mes</p>
                <div class="h-56 relative" wire:ignore>
                    <canvas id="macroChart"></canvas>
                </div>
            </div>

        </div>
    @endif

    {{-- Script Chart.js unificado --}}
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        document.addEventListener('livewire:initialized', () => {
            let chartInteranual = null;
            let chartSemaforo = null;
            let chartRanking = null;
            let chartMacro = null;

            Livewire.on('update-executive-charts', (event) => {
                const payload = Array.isArray(event) ? event[0] : event;

                // 1. Interanual Chart
                const canvasInteranual = document.getElementById('interanualChart');
                if (canvasInteranual) {
                    if (!chartInteranual) {
                        chartInteranual = new Chart(canvasInteranual.getContext('2d'), {
                            type: 'line',
                            data: {
                                labels: ['Ene', 'Feb', 'Mar', 'Abr', 'May', 'Jun', 'Jul', 'Ago',
                                    'Sep', 'Oct', 'Nov', 'Dic'
                                ],
                                datasets: []
                            },
                            options: {
                                responsive: true,
                                maintainAspectRatio: false,
                                plugins: {
                                    legend: {
                                        labels: {
                                            color: '#475569',
                                            font: {
                                                size: 10
                                            }
                                        }
                                    }
                                },
                                scales: {
                                    x: {
                                        grid: {
                                            color: '#e2e8f0'
                                        },
                                        ticks: {
                                            color: '#64748b'
                                        }
                                    },
                                    y: {
                                        grid: {
                                            color: '#e2e8f0'
                                        },
                                        ticks: {
                                            color: '#64748b'
                                        }
                                    }
                                }
                            }
                        });
                    }
                    // spanGaps: true evita que la línea se corte en los meses que
                    // no aplican para la frecuencia del indicador (ej. trimestral).
                    // Los null que sí corresponden a "debía reportar y no lo hizo"
                    // igual quedan sin conectar visualmente de forma distinta si
                    // luego se quiere refinar con un plugin de puntos.
                    chartInteranual.data.datasets = [{
                            label: `Año ${payload.interanual.anioActual}`,
                            data: payload.interanual.actual,
                            borderColor: '#0284c7',
                            backgroundColor: 'rgba(2, 132, 199, 0.15)',
                            fill: true,
                            tension: 0.3,
                            borderWidth: 2,
                            spanGaps: true
                        },
                        {
                            label: `Año ${payload.interanual.anioAnterior}`,
                            data: payload.interanual.anterior,
                            borderColor: '#94a3b8',
                            borderDash: [3, 3],
                            tension: 0.3,
                            borderWidth: 1.5,
                            spanGaps: true
                        },
                        {
                            label: `Meta (${payload.interanual.meta})`,
                            data: Array(12).fill(payload.interanual.meta),
                            borderColor: '#ef4444',
                            borderDash: [5, 5],
                            borderWidth: 1.5,
                            pointRadius: 0
                        }
                    ];
                    chartInteranual.update();
                }

                // 2. Semáforo (Dona)
                const canvasSemaforo = document.getElementById('semaforoChart');
                if (canvasSemaforo) {
                    if (!chartSemaforo) {
                        chartSemaforo = new Chart(canvasSemaforo.getContext('2d'), {
                            type: 'doughnut',
                            data: {
                                labels: ['En Meta', 'En Riesgo', 'Crítico'],
                                datasets: []
                            },
                            options: {
                                responsive: true,
                                maintainAspectRatio: false,
                                plugins: {
                                    legend: {
                                        position: 'bottom',
                                        labels: {
                                            color: '#475569',
                                            font: {
                                                size: 10
                                            }
                                        }
                                    }
                                }
                            }
                        });
                    }
                    chartSemaforo.data.datasets = [{
                        data: payload.semaforo,
                        backgroundColor: ['#10b981', '#f59e0b', '#ef4444'],
                        borderWidth: 0
                    }];
                    chartSemaforo.update();
                }

                // 3. Ranking por Área (Barras Horizontales)
                const canvasRanking = document.getElementById('rankingChart');
                if (canvasRanking) {
                    if (!chartRanking) {
                        chartRanking = new Chart(canvasRanking.getContext('2d'), {
                            type: 'bar',
                            data: {
                                labels: [],
                                datasets: []
                            },
                            options: {
                                indexAxis: 'y',
                                responsive: true,
                                maintainAspectRatio: false,
                                plugins: {
                                    legend: {
                                        display: false
                                    }
                                },
                                scales: {
                                    x: {
                                        grid: {
                                            color: '#e2e8f0'
                                        },
                                        ticks: {
                                            color: '#64748b'
                                        }
                                    },
                                    y: {
                                        grid: {
                                            display: false
                                        },
                                        ticks: {
                                            color: '#475569'
                                        }
                                    }
                                }
                            }
                        });
                    }
                    chartRanking.data.labels = payload.ranking.labels;
                    chartRanking.data.datasets = [{
                        data: payload.ranking.values,
                        backgroundColor: '#38bdf8',
                        borderRadius: 4
                    }];
                    chartRanking.update();
                }

                // 4. Macro Mensual (Barras Verticales)
                const canvasMacro = document.getElementById('macroChart');
                if (canvasMacro) {
                    if (!chartMacro) {
                        chartMacro = new Chart(canvasMacro.getContext('2d'), {
                            type: 'bar',
                            data: {
                                labels: ['Ene', 'Feb', 'Mar', 'Abr', 'May', 'Jun', 'Jul', 'Ago',
                                    'Sep', 'Oct', 'Nov', 'Dic'
                                ],
                                datasets: []
                            },
                            options: {
                                responsive: true,
                                maintainAspectRatio: false,
                                plugins: {
                                    legend: {
                                        display: false
                                    }
                                },
                                scales: {
                                    x: {
                                        grid: {
                                            color: '#e2e8f0'
                                        },
                                        ticks: {
                                            color: '#64748b'
                                        }
                                    },
                                    y: {
                                        grid: {
                                            color: '#e2e8f0'
                                        },
                                        ticks: {
                                            color: '#64748b'
                                        }
                                    }
                                }
                            }
                        });
                    }
                    chartMacro.data.datasets = [{
                        label: 'Total Registrado',
                        data: payload.macroMensual,
                        backgroundColor: '#818cf8',
                        borderRadius: 4
                    }];
                    chartMacro.update();
                }
            });
        });
    </script>
</div>

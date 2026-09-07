<div class="space-y-6">
    {{-- Cargar ApexCharts CDN si no lo tienes en tu layout global --}}
    <script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>

    @if (session()->has('message'))
        <div class="p-4 rounded-xl bg-emerald-50 border border-emerald-200 text-emerald-800 text-sm flex items-center justify-between">
            <div class="flex items-center gap-2">
                <svg class="w-5 h-5 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                <span>{{ session('message') }}</span>
            </div>
            <button onclick="this.parentElement.remove()" class="text-emerald-500 hover:text-emerald-700">&times;</button>
        </div>
    @endif

    {{-- DASHBOARD COMPACTO --}}
    <div class="grid grid-cols-1 md:grid-cols-4 lg:grid-cols-12 gap-4">
        
        {{-- Targetas de Métricas --}}
        <div class="lg:col-span-7 grid grid-cols-2 gap-4">
            <div class="bg-white p-5 rounded-2xl shadow-sm border border-gray-100 flex items-center gap-4">
                <div class="p-3 bg-indigo-50 text-indigo-600 rounded-xl">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/></svg>
                </div>
                <div>
                    <p class="text-xs font-medium text-gray-500 uppercase tracking-wider">Total Productos</p>
                    <h3 class="text-2xl font-bold text-gray-900 mt-0.5">{{ $totalPrendas }}</h3>
                </div>
            </div>

            <div class="bg-white p-5 rounded-2xl shadow-sm border border-gray-100 flex items-center gap-4">
                <div class="p-3 bg-emerald-50 text-emerald-600 rounded-xl">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/></svg>
                </div>
                <div>
                    <p class="text-xs font-medium text-gray-500 uppercase tracking-wider">Stock Acumulado</p>
                    <h3 class="text-2xl font-bold text-gray-900 mt-0.5">{{ $totalStock }}</h3>
                </div>
            </div>

            <div class="bg-white p-5 rounded-2xl shadow-sm border border-gray-100 flex items-center gap-4">
                <div class="p-3 bg-blue-50 text-blue-600 rounded-xl">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                </div>
                <div>
                    <p class="text-xs font-medium text-gray-500 uppercase tracking-wider">Stock Nuevo</p>
                    <h3 class="text-2xl font-bold text-blue-600 mt-0.5">{{ $totalNuevo }}</h3>
                </div>
            </div>

            <div class="bg-white p-5 rounded-2xl shadow-sm border border-gray-100 flex items-center gap-4">
                <div class="p-3 bg-amber-50 text-amber-600 rounded-xl">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/></svg>
                </div>
                <div>
                    <p class="text-xs font-medium text-gray-500 uppercase tracking-wider">Stock Usado</p>
                    <h3 class="text-2xl font-bold text-amber-600 mt-0.5">{{ $totalUsado }}</h3>
                </div>
            </div>
        </div>

        {{-- Gráfica Dinámica por Tipo --}}
        <div class="lg:col-span-5 bg-white p-5 rounded-2xl shadow-sm border border-gray-100 flex flex-col justify-between"
             x-data="{
                labels: @js($chartTipoLabels),
                series: @js($chartTipoData),
                chart: null,
                init() {
                    let options = {
                        chart: { type: 'donut', height: 160 },
                        series: this.series,
                        labels: this.labels,
                        colors: ['#3b82f6', '#f59e0b', '#10b981', '#6366f1', '#ec4899', '#8b5cf6'],
                        legend: { position: 'right', fontSize: '12px' },
                        dataLabels: { enabled: false },
                        tooltip: { y: { formatter: (val) => val + ' prendas' } }
                    };
                    this.chart = new ApexCharts(this.$refs.chart, options);
                    this.chart.render();

                    // Escuchar cambios de Livewire para actualizar el gráfico sin parpadear
                    Livewire.hook('commit', ({ component, respond }) => {
                        respond(() => {
                            this.chart.updateOptions({
                                series: @js($chartTipoData),
                                labels: @js($chartTipoLabels)
                            });
                        });
                    });
                }
             }">
            <h4 class="text-xs font-semibold text-gray-500 uppercase tracking-wider mb-2">Distribución por Tipo</h4>
            <div x-ref="chart" class="w-full flex items-center justify-center"></div>
        </div>

    </div>

    {{-- TABLA Y FILTROS --}}
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 space-y-6">
        <div class="p-6 pb-0 space-y-4">
            <div class="grid grid-cols-1 md:grid-cols-12 gap-3">
                
                {{-- Buscador Principal --}}
                <div class="md:col-span-6 relative">
                    <input wire:model.live.debounce.300ms="search" type="text"
                        class="w-full pl-11 pr-4 py-2.5 text-sm bg-gray-50/50 border border-gray-200 rounded-xl focus:bg-white focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 transition-all placeholder:text-gray-400"
                        placeholder="Buscar por nombre o tipo...">
                    <svg class="w-5 h-5 text-gray-400 absolute left-3.5 top-1/2 -translate-y-1/2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                    </svg>
                </div>

                <div class="md:col-span-3">
                    <select wire:model.live="selectedTipo" class="w-full py-2.5 px-3 text-sm bg-gray-50/50 border border-gray-200 rounded-xl focus:bg-white focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500">
                        <option value="">Todos los Tipos</option>
                        @foreach($tipos as $tipo)
                            <option value="{{ $tipo }}">{{ $tipo }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="md:col-span-3">
                    <select wire:model.live="selectedUbicacion" class="w-full py-2.5 px-3 text-sm bg-gray-50/50 border border-gray-200 rounded-xl focus:bg-white focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500">
                        <option value="">Todas las Bodegas</option>
                        @foreach($ubicaciones as $ub)
                            <option value="{{ $ub->id }}">{{ $ub->nombre }}</option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div class="flex flex-wrap items-center justify-between gap-3 pt-2 border-t border-gray-100">
                <div class="flex flex-wrap items-center gap-2">
                    <select wire:model.live="selectedColor" class="py-1.5 px-3 text-xs bg-gray-50 border border-gray-200 rounded-lg">
                        <option value="">Color: Todos</option>
                        @foreach($colores as $color)
                            <option value="{{ $color }}">{{ $color }}</option>
                        @endforeach
                    </select>

                    <select wire:model.live="selectedTalla" class="py-1.5 px-3 text-xs bg-gray-50 border border-gray-200 rounded-lg">
                        <option value="">Talla: Todas</option>
                        @foreach($tallas as $talla)
                            <option value="{{ $talla }}">{{ $talla }}</option>
                        @endforeach
                    </select>

                    @if($search || $selectedTipo || $selectedColor || $selectedTalla || $selectedUbicacion)
                        <button wire:click="clearFilters" class="text-xs text-rose-600 hover:text-rose-700 font-medium px-2 py-1 flex items-center gap-1 transition">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                            Limpiar Filtros
                        </button>
                    @endif
                </div>

                <div wire:loading class="text-xs text-blue-600 font-medium flex items-center gap-2">
                    <svg class="animate-spin h-3.5 w-3.5 text-blue-600" fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v4a4 4 0 00-4 4H4z"></path>
                    </svg>
                    Actualizando...
                </div>
            </div>
        </div>

        <div class="relative overflow-x-auto">
            <table class="w-full text-sm text-left text-gray-600">
                <thead class="bg-gray-50/70 text-xs font-semibold uppercase tracking-wider text-gray-500 border-y border-gray-100">
                    <tr>
                        <th class="px-6 py-3.5">Indumentaria</th>
                        <th class="px-6 py-3.5">Detalles</th>
                        <th class="px-6 py-3.5 text-center">Nuevo</th>
                        <th class="px-6 py-3.5 text-center">Usado</th>
                        <th class="px-6 py-3.5 text-center">Total</th>
                        <th class="px-6 py-3.5">Stock por Bodega</th>
                        <th class="px-6 py-3.5 text-right">Acciones</th>
                    </tr>
                </thead>

                <tbody class="divide-y divide-gray-100">
                    @forelse ($indumentarias as $indumentaria)
                        <tr class="hover:bg-gray-50/60 transition-colors">
                            <td class="px-6 py-4">
                                <div class="flex items-center gap-3.5">
                                    @if ($indumentaria->image)
                                        <img src="{{ asset('storage/' . $indumentaria->image) }}"
                                             alt="{{ $indumentaria->nombre }}"
                                             class="w-12 h-12 object-cover rounded-xl border border-gray-100 shadow-sm">
                                    @else
                                        <div class="w-12 h-12 flex items-center justify-center bg-gray-100 rounded-xl text-gray-400">
                                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                                        </div>
                                    @endif

                                    <div>
                                        <div class="font-medium text-gray-900">{{ $indumentaria->nombre }}</div>
                                        <span class="inline-block text-[11px] text-gray-500 bg-gray-100 px-2 py-0.5 rounded-md font-mono mt-0.5">
                                            {{ $indumentaria->tipo }}
                                        </span>
                                    </div>
                                </div>
                            </td>

                            <td class="px-6 py-4">
                                <div class="flex flex-col gap-1 text-xs">
                                    <span class="text-gray-600"><strong class="text-gray-400">Color:</strong> {{ $indumentaria->color ?? 'N/A' }}</span>
                                    <span class="text-gray-600"><strong class="text-gray-400">Talla:</strong> {{ $indumentaria->talla ?? 'N/A' }}</span>
                                </div>
                            </td>

                            <td class="px-6 py-4 text-center">
                                <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-semibold bg-blue-50 text-blue-700">
                                    {{ $indumentaria->stock_nuevo }}
                                </span>
                            </td>

                            <td class="px-6 py-4 text-center">
                                <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-semibold bg-amber-50 text-amber-700">
                                    {{ $indumentaria->stock_usado }}
                                </span>
                            </td>

                            <td class="px-6 py-4 text-center font-bold text-gray-900">
                                {{ $indumentaria->stock_total }}
                            </td>

                            <td class="px-6 py-4">
                                <div class="space-y-1.5 max-w-[200px]">
                                    @foreach ($indumentaria->inventarios as $inv)
                                        <div class="flex items-center justify-between text-xs bg-blue-50/50 px-2 py-1 rounded border border-blue-100/50">
                                            <span class="text-blue-900 truncate">🆕 {{ $inv->ubicacion->nombre }}</span>
                                            <span class="font-bold text-blue-700 ml-2">{{ $inv->stock }}</span>
                                        </div>
                                    @endforeach

                                    @foreach ($indumentaria->inventariosUsados as $inv)
                                        <div class="flex items-center justify-between text-xs bg-amber-50/50 px-2 py-1 rounded border border-amber-100/50">
                                            <span class="text-amber-900 truncate">♻️ {{ $inv->ubicacion->nombre }}</span>
                                            <span class="font-bold text-amber-700 ml-2">{{ $inv->stock }}</span>
                                        </div>
                                    @endforeach

                                    @if ($indumentaria->inventarios->isEmpty() && $indumentaria->inventariosUsados->isEmpty())
                                        <span class="text-xs text-gray-400 italic">Sin stock asignado</span>
                                    @endif
                                </div>
                            </td>

                            <td class="px-6 py-4 text-right">
                                <div class="flex items-center justify-end gap-1.5">
                                    <a href="{{ route('admin.indumentarias.edit', $indumentaria) }}"
                                       class="p-2 text-gray-500 hover:text-blue-600 hover:bg-blue-50 rounded-lg transition"
                                       title="Editar indumentaria">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"/></svg>
                                    </a>

                                    <button wire:click="destroy({{ $indumentaria->id }})"
                                            wire:confirm="¿Seguro que deseas eliminar esta indumentaria?"
                                            class="p-2 text-gray-500 hover:text-rose-600 hover:bg-rose-50 rounded-lg transition"
                                            title="Eliminar indumentaria">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                    </button>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="px-6 py-12 text-center">
                                <div class="flex flex-col items-center justify-center text-gray-400">
                                    <svg class="w-10 h-10 mb-2 stroke-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"/></svg>
                                    <p class="text-sm">No se encontraron indumentarias con los filtros seleccionados.</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="px-6 py-4 border-t border-gray-100 bg-gray-50/50 rounded-b-2xl">
            {{ $indumentarias->links() }}
        </div>
    </div>
</div>
<div class="space-y-6">
    <!-- Encabezado -->
    <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-3">
        <div>
            <h1 class="text-2xl font-bold text-gray-800">Inventario de Control</h1>
            <p class="text-sm text-gray-500">Listado filtrable de registros de inventario</p>
        </div>

        <div class="flex items-center gap-2">
            <span class="text-sm text-gray-600">Total:</span>
            <span class="px-3 py-1 rounded-full bg-blue-50 text-blue-700 font-semibold text-sm">
                {{ $inventarios->total() }}
            </span>
        </div>
    </div>

    <!-- Filtros -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-5">
        <div class="grid grid-cols-1 md:grid-cols-6 gap-4">

            <!-- Buscador -->
            <div class="md:col-span-1">
                <label class="block text-xs font-medium text-gray-500 mb-1">Cuadrilla</label>
                <input type="text" wire:model.live.debounce.300ms="buscar" placeholder="Buscar..."
                    class="w-full rounded-lg border-gray-200 focus:border-blue-500 focus:ring-blue-500 text-sm" />
            </div>

            <!-- Rango Fechas: Desde -->
            <div>
                <label class="block text-xs font-medium text-gray-500 mb-1">Desde</label>
                <input type="date" wire:model.live="fecha_desde"
                    class="w-full rounded-lg border-gray-200 focus:border-blue-500 focus:ring-blue-500 text-sm" />
            </div>

            <!-- Rango Fechas: Hasta -->
            <div>
                <label class="block text-xs font-medium text-gray-500 mb-1">Hasta</label>
                <input type="date" wire:model.live="fecha_hasta"
                    class="w-full rounded-lg border-gray-200 focus:border-blue-500 focus:ring-blue-500 text-sm" />
            </div>

            <!-- Grupo -->
            <div>
                <label class="block text-xs font-medium text-gray-500 mb-1">Grupo</label>
                <select wire:model.live="grupo_id"
                    class="w-full rounded-lg border-gray-200 focus:border-blue-500 focus:ring-blue-500 text-sm">
                    <option value="">Todos</option>
                    @foreach ($this->grupos as $grupo)
                        <option value="{{ $grupo->id }}">{{ $grupo->nombre }}</option>
                    @endforeach
                </select>
            </div>

            <!-- Tecnología -->
            <div>
                <label class="block text-xs font-medium text-gray-500 mb-1">Tecnología</label>
                <select wire:model.live="tecnologia_id"
                    class="w-full rounded-lg border-gray-200 focus:border-blue-500 focus:ring-blue-500 text-sm">
                    <option value="">Todas</option>
                    @foreach ($this->tecnologias as $tec)
                        <option value="{{ $tec->id }}">{{ $tec->nombre }}</option>
                    @endforeach
                </select>
            </div>

            <!-- Cuadrilla -->
            <div>
                <label class="block text-xs font-medium text-gray-500 mb-1">Cuadrilla (Select)</label>
                <select wire:model.live="cuadrilla_id"
                    class="w-full rounded-lg border-gray-200 focus:border-blue-500 focus:ring-blue-500 text-sm">
                    <option value="">Todas</option>
                    @foreach ($this->cuadrillas as $cua)
                        <option value="{{ $cua->id }}">{{ $cua->cua_nombre }}</option>
                    @endforeach
                </select>
            </div>
        </div>

        <div class="flex justify-between items-center mt-4">
            <button wire:click="limpiarFiltros"
                class="inline-flex items-center gap-2 bg-gray-100 hover:bg-gray-200 text-gray-700 px-4 py-2 rounded-lg text-sm transition">
                Limpiar filtros
            </button>

            <div class="text-sm text-gray-500">
                Mostrando
                <span class="font-semibold text-gray-800">{{ $inventarios->count() }}</span>
                de
                <span class="font-semibold text-gray-800">{{ $inventarios->total() }}</span>
                registros
            </div>
        </div>
    </div>

    <!-- Tabla Principal -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden relative">
        <div wire:loading.delay wire:target="buscar, fecha_desde, fecha_hasta, grupo_id, tecnologia_id, cuadrilla_id, limpiarFiltros, page"
            class="absolute inset-0 bg-white/50 backdrop-blur-[1px] flex items-center justify-center z-10">
            <div class="animate-spin rounded-full h-8 w-8 border-b-2 border-blue-600"></div>
        </div>

        <div class="overflow-x-auto">
            <table class="min-w-full text-sm">
                <thead class="bg-gray-50 text-gray-600 text-xs uppercase tracking-wider">
                    <tr>
                        <th class="px-5 py-3 text-left">Fecha</th>
                        <th class="px-5 py-3 text-left">Grupo</th>
                        <th class="px-5 py-3 text-left">Tecnología</th>
                        <th class="px-5 py-3 text-left">Cuadrilla</th>
                        <th class="px-5 py-3 text-right">Acciones</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse($inventarios as $inv)
                        <tr wire:key="inv-row-{{ $inv->id }}" class="hover:bg-gray-50 transition">
                            <td class="px-5 py-3 text-gray-700">
                                {{ \Carbon\Carbon::parse($inv->fecha_inventario)->format('d/m/Y') }}
                            </td>
                            <td class="px-5 py-3">
                                <span class="px-2 py-1 bg-gray-100 text-gray-700 rounded-full text-xs">
                                    {{ $inv->grupo->nombre ?? '-' }}
                                </span>
                            </td>
                            <td class="px-5 py-3">
                                <span class="px-2 py-1 bg-blue-50 text-blue-700 rounded-full text-xs">
                                    {{ $inv->tecnologia->nombre ?? '-' }}
                                </span>
                            </td>
                            <td class="px-5 py-3 text-gray-700">
                                {{ $inv->cuadrilla->cua_nombre ?? '-' }}
                            </td>
                            <td class="px-5 py-3">
                                <div class="flex justify-end gap-2">
                                    <button wire:click="verInventario({{ $inv->id }})"
                                        class="bg-blue-600 hover:bg-blue-700 text-white p-2 rounded-lg transition"
                                        title="Ver detalle">
                                        <i class="fa-solid fa-eye"></i>
                                    </button>

                                    <button
                                        onclick="if(confirm('¿Está seguro de eliminar este inventario?')) { @this.call('eliminar', {{ $inv->id }}) }"
                                        class="bg-red-600 hover:bg-red-700 text-white p-2 rounded-lg transition"
                                        title="Eliminar">
                                        <i class="fa-solid fa-trash"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="py-10 text-center text-gray-400">
                                No hay inventarios registrados
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- Botón Exportar Excel -->
    <button type="button" wire:click="abrirModalExcel"
        class="bg-emerald-600 hover:bg-emerald-700 text-white px-4 py-2 rounded-lg flex items-center gap-2 transition">
        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
        </svg>
        Exportar Excel por Rango
    </button>

    <!-- Modal Exportar Excel por Rango -->
    @if ($mostrarModalExcel)
        <div class="fixed inset-0 z-50 flex items-center justify-center bg-black/50" wire:key="modal-excel">
            <div class="bg-white rounded-lg shadow-xl w-full max-w-md p-6" @click.outside="$wire.cerrarModalExcel()">

                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-lg font-semibold text-gray-800">Exportar Inventario por Rango</h3>
                    <button wire:click="cerrarModalExcel" class="text-gray-400 hover:text-gray-600">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>

                <p class="text-sm text-gray-500 mb-4">
                    Selecciona el rango de fechas para exportar el reporte consolidado en Excel.
                </p>

                <div class="grid grid-cols-2 gap-4 mb-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Fecha Desde</label>
                        <input type="date" wire:model="excel_fecha_desde"
                            class="w-full border-gray-300 rounded-lg shadow-sm focus:ring-emerald-500 focus:border-emerald-500 text-sm">
                        @error('excel_fecha_desde')
                            <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span>
                        @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Fecha Hasta</label>
                        <input type="date" wire:model="excel_fecha_hasta"
                            class="w-full border-gray-300 rounded-lg shadow-sm focus:ring-emerald-500 focus:border-emerald-500 text-sm">
                        @error('excel_fecha_hasta')
                            <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span>
                        @enderror
                    </div>
                </div>

                <div class="flex justify-end gap-3 mt-6">
                    <button type="button" wire:click="cerrarModalExcel"
                        class="px-4 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 transition">
                        Cancelar
                    </button>

                    <button type="button" wire:click="generarExcel" wire:loading.attr="disabled"
                        wire:target="generarExcel"
                        class="px-4 py-2 bg-emerald-600 hover:bg-emerald-700 text-white rounded-lg disabled:opacity-50 transition flex items-center gap-2">
                        <span wire:loading.remove wire:target="generarExcel">Generar Excel</span>
                        <span wire:loading wire:target="generarExcel">Generando...</span>
                    </button>
                </div>
            </div>
        </div>
    @endif

    <!-- Modal Detalle Inventario -->
    @if ($mostrarModal)
        <div class="fixed inset-0 z-50 flex items-center justify-center bg-black/50 px-4" wire:key="modal-detalles">
            <div class="w-full max-w-3xl rounded-2xl bg-white shadow-xl overflow-hidden" @click.outside="$wire.cerrarModal()">
                <div class="border-b border-slate-100 bg-slate-50 px-6 py-4">
                    <h2 class="text-lg font-semibold text-slate-900">Materiales utilizados</h2>
                    <p class="text-sm text-slate-500 mt-1">Solo se muestran materiales con cantidad mayor a 0</p>
                </div>

                <div class="p-6">
                    @if ($this->inventarioDetalles->isNotEmpty())
                        <div class="overflow-hidden rounded-xl border border-slate-200">
                            <div class="max-h-[420px] overflow-y-auto">
                                <table class="w-full text-sm">
                                    <thead class="bg-slate-50 text-xs uppercase text-slate-500 sticky top-0">
                                        <tr>
                                            <th class="px-4 py-3 text-left">Material</th>
                                            <th class="px-4 py-3 text-left">Código</th>
                                            <th class="px-4 py-3 text-center">Cantidad</th>
                                        </tr>
                                    </thead>
                                    <tbody class="divide-y divide-slate-100">
                                        @foreach ($this->inventarioDetalles as $detalle)
                                            <tr wire:key="det-{{ $detalle->id }}" class="hover:bg-slate-50">
                                                <td class="px-4 py-3 text-slate-800">
                                                    {{ $detalle->material->descripcion ?? 'Sin nombre' }}
                                                </td>
                                                <td class="px-4 py-3 font-mono text-xs text-slate-500">
                                                    {{ $detalle->material->codigo ?? '-' }}
                                                </td>
                                                <td class="px-4 py-3 text-center">
                                                    <span class="inline-flex items-center justify-center rounded-full bg-emerald-50 px-3 py-1 text-sm font-semibold text-emerald-700">
                                                        {{ $detalle->stock_final }}
                                                    </span>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>

                            @if($this->inventarioSeleccionado)
                                <span class="mt-2 block rounded-lg border border-slate-200 bg-slate-50 px-3 py-2 text-sm text-slate-600">
                                    <strong class="font-semibold text-slate-700">Observaciones:</strong>
                                    {{ $this->inventarioSeleccionado->observaciones ?: 'Sin observaciones' }}
                                </span>
                            @endif
                        </div>
                    @else
                        <div class="flex flex-col items-center justify-center py-10 text-center">
                            <p class="text-sm font-medium text-slate-600">No hay materiales registrados</p>
                        </div>
                    @endif
                </div>

                <div class="flex justify-end gap-3 border-t border-slate-100 px-6 py-4">
                    <button wire:click="cerrarModal"
                        class="rounded-lg border border-slate-200 px-4 py-2 text-sm text-slate-600 hover:bg-slate-50 transition">
                        Cerrar
                    </button>
                </div>
            </div>
        </div>
    @endif

    <!-- Paginación -->
    <div class="flex justify-center">
        {{ $inventarios->links() }}
    </div>
</div>
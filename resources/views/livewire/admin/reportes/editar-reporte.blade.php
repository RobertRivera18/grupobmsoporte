<div class="p-6 bg-white rounded-xl shadow-sm border border-gray-100 max-w-7xl mx-auto">
    <div class="flex flex-col sm:flex-row sm:items-center justify-between pb-5 mb-6 border-b border-gray-100 gap-4">
        <div>
            <span class="text-xs font-semibold text-blue-600 uppercase tracking-wider">Gestión de Reportes</span>
            <h2 class="text-2xl font-bold text-gray-900 flex items-center gap-2 mt-0.5">
                Editar Reporte:
                <span
                    class="inline-flex items-center px-2.5 py-0.5 rounded-md text-sm font-medium bg-blue-50 text-blue-700 border border-blue-100">
                    {{ $reporte->mes ?? 'N/A' }}
                </span>
            </h2>
        </div>

        <div class="relative w-full sm:w-80">
            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-gray-400">
                <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                </svg>
            </div>
            <input type="text" wire:model.live.debounce.300ms="search"
                placeholder="Buscar por cuadrilla, integrante..."
                class="w-full pl-9 pr-4 py-2 bg-gray-50 border border-gray-200 rounded-lg focus:bg-white focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 text-sm transition-all outline-none">
        </div>
    </div>

    <form action="{{ route('admin.reportes.update', $reporte) }}" method="POST">
        @csrf
        @method('PUT')

        @foreach ($seleccionadas as $id)
            <input type="hidden" name="seleccionadas[]" value="{{ $id }}">
        @endforeach

        @foreach ($observaciones as $cuadrillaId => $obs)
            @if (!empty($obs))
                <input type="hidden" name="observaciones[{{ $cuadrillaId }}]" value="{{ $obs }}">
            @endif
        @endforeach

        <div class="overflow-x-auto rounded-lg border border-gray-200/80 shadow-xs">
            <table class="w-full text-left border-collapse text-sm">
                <thead>
                    <tr
                        class="bg-gray-50/75 border-b border-gray-200 text-xs font-semibold text-gray-500 uppercase tracking-wider">
                        <th scope="col" class="px-4 py-3 text-center w-12">#</th>
                        <th scope="col" class="px-4 py-3">Cuadrilla & Ciudad</th>
                        <th scope="col" class="px-4 py-3">Integrantes</th>
                        <th scope="col" class="px-4 py-3">Equipos</th>
                        <th scope="col" class="px-4 py-3 text-right">Recarga</th>
                        <th scope="col" class="px-4 py-3 text-center w-40">Observaciones</th>
                    </tr>
                </thead>

                <tbody class="divide-y divide-gray-100 bg-white">
                    @forelse ($cuadrillas as $cuadrilla)
                        @php
                            $tieneObs = !empty($observaciones[$cuadrilla->id] ?? null);
                        @endphp
                        {{-- Se añade wire:key para garantizar la identificación única de cada fila al filtrar/buscar --}}
                        <tr wire:key="cuadrilla-row-{{ $cuadrilla->id }}" x-data="{ showObs: {{ $tieneObs ? 'true' : 'false' }} }" class="hover:bg-blue-50/30 transition-colors group">
                            {{-- Checkbox --}}
                            <td class="px-4 py-3 text-center">
                                <input type="checkbox" wire:model.live="seleccionadas" value="{{ $cuadrilla->id }}"
                                    class="w-4 h-4 rounded border-gray-300 text-blue-600 focus:ring-blue-500 focus:ring-offset-0 cursor-pointer">
                            </td>

                            <td class="px-4 py-3 font-medium text-gray-900">
                                <div class="flex items-center gap-2">
                                    <span>{{ $cuadrilla->cua_nombre }}</span>
                                    @if ($cuadrilla->cua_ciudad == 1)
                                        <span
                                            class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-amber-50 text-amber-700 border border-amber-200">
                                            Guayaquil
                                        </span>
                                    @elseif ($cuadrilla->cua_ciudad == 2)
                                        <span
                                            class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-purple-50 text-purple-700 border border-purple-200">
                                            Quito
                                        </span>
                                    @endif
                                </div>
                            </td>

                            <td class="px-4 py-3">
                                @if ($cuadrilla->users->isEmpty())
                                    <span class="text-xs text-gray-400 italic">Sin integrantes</span>
                                @else
                                    <div class="flex flex-wrap gap-1">
                                        @foreach ($cuadrilla->users as $user)
                                            <span
                                                class="inline-block bg-gray-100 text-gray-700 text-xs px-2 py-0.5 rounded-full border border-gray-200/60">
                                                {{ $user->name }}
                                            </span>
                                        @endforeach
                                    </div>
                                @endif
                            </td>

                            <td class="px-4 py-3">
                                <div class="flex flex-wrap gap-1">
                                    @forelse ($cuadrillas->find($cuadrilla->id)->equipos as $equipo)
                                        <span
                                            class="inline-flex items-center px-2 py-0.5 rounded text-xs font-mono bg-slate-100 text-slate-700 border border-slate-200">
                                            {{ $equipo->serie }}
                                        </span>
                                    @empty
                                        <span class="text-xs text-gray-400 italic">N/A</span>
                                    @endforelse
                                </div>
                            </td>

                            <td class="px-4 py-3 text-right font-mono font-medium text-gray-700">
                                ${{ number_format(10.5, 2) }}
                            </td>

                            <td class="px-4 py-3">
                                @if (in_array($rol, ['operador1', 'operador2']))
                                    <div class="flex flex-col items-center">
                                        {{-- Botón para alternar visibilidad si no hay texto --}}
                                        <button type="button" @click="showObs = !showObs"
                                            class="inline-flex items-center gap-1.5 text-xs font-medium px-2.5 py-1 rounded-md transition-colors"
                                            :class="showObs || '{{ $tieneObs }}' ?
                                                'bg-blue-50 text-blue-700 hover:bg-blue-100' :
                                                'bg-gray-100 text-gray-600 hover:bg-gray-200'">
                                            <svg class="w-3.5 h-3.5 transition-transform"
                                                :class="showObs ? 'rotate-45' : ''" fill="none" viewBox="0 0 24 24"
                                                stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M12 4v16m8-8H4" />
                                            </svg>
                                            <span
                                                x-text="showObs ? 'Cerrar' : '{{ $tieneObs ? 'Editar nota' : 'Agregar nota' }}'"></span>
                                        </button>

                                        <div x-show="showObs" x-collapse class="w-full mt-2">
                                            <textarea rows="2" wire:model.blur="observaciones.{{ $cuadrilla->id }}" placeholder="Escribe una observación..."
                                                class="w-full text-xs p-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 outline-none resize-y"></textarea>
                                        </div>
                                    </div>
                                @else
                                    <div class="text-center">
                                        @if ($tieneObs)
                                            <span
                                                class="inline-block text-xs bg-gray-100 text-gray-700 px-2 py-1 rounded border border-gray-200 max-w-xs truncate"
                                                title="{{ $observaciones[$cuadrilla->id] }}">
                                                {{ $observaciones[$cuadrilla->id] }}
                                            </span>
                                        @else
                                            <span class="text-xs text-gray-400 italic">—</span>
                                        @endif
                                    </div>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-4 py-8 text-center text-gray-500">
                                <div class="flex flex-col items-center justify-center gap-2">
                                    <svg class="w-8 h-8 text-gray-300" fill="none" viewBox="0 0 24 24"
                                        stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                            d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4" />
                                    </svg>
                                    <span class="text-sm font-medium">No se encontraron cuadrillas que coincidan con la
                                        búsqueda.</span>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @error('seleccionadas')
            <div class="mt-3 p-3 bg-red-50 border border-red-200 rounded-lg text-red-600 text-sm flex items-center gap-2">
                <svg class="w-4 h-4 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
                <span>{{ $message }}</span>
            </div>
        @enderror

        <div class="mt-6 flex items-center justify-between border-t border-gray-100 pt-4">
            <span class="text-xs text-gray-500">
                Seleccionados: <strong class="text-gray-700">{{ count($seleccionadas) }}</strong> cuadrilla(s)
            </span>

            <div class="flex items-center gap-3">
                {{-- Botón Generar Excel --}}
                <button type="button" wire:click="generarExcel" wire:loading.attr="disabled"
                    class="inline-flex items-center gap-2 bg-emerald-600 hover:bg-emerald-700 active:bg-emerald-800 text-white font-medium text-sm px-4 py-2.5 rounded-lg shadow-sm hover:shadow transition-all focus:outline-none focus:ring-2 focus:ring-emerald-500/50 disabled:opacity-50">
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                    </svg>
                    <span wire:loading.remove wire:target="generarExcel">Exportar Excel</span>
                    <span wire:loading wire:target="generarExcel">Generando...</span>
                </button>

                <button type="submit"
                    class="inline-flex items-center gap-2 bg-blue-600 hover:bg-blue-700 active:bg-blue-800 text-white font-medium text-sm px-5 py-2.5 rounded-lg shadow-sm hover:shadow transition-all focus:outline-none focus:ring-2 focus:ring-blue-500/50">
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                    </svg>
                    Actualizar Reporte
                </button>
            </div>
        </div>
    </form>
</div>
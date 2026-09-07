<div class="p-6 bg-white rounded-lg shadow">
    <!-- Encabezado y Buscador -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-6">
        <div>
            <h2 class="text-xl font-bold text-gray-800">Listado de Cuadrillas</h2>
            <p class="text-xs text-gray-500 mt-0.5">Filtradas por tipo de equipo #4</p>
        </div>

        <div class="w-full sm:w-80 relative">
            <input 
                type="text" 
                wire:model.live.debounce.300ms="search" 
                placeholder="Buscar por cuadrilla, ciudad, línea o integrante..."
                class="w-full pl-9 pr-4 py-2 text-sm border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition"
            >
            <svg class="w-4 h-4 text-gray-400 absolute left-3 top-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
            </svg>
        </div>
    </div>

    <!-- Barra de acciones de selección y totales -->
    <div class="mb-4 p-3 bg-gray-50 border border-gray-200 rounded-lg flex flex-col sm:flex-row sm:items-center justify-between gap-3">
        <div class="flex items-center gap-2">
            <button 
                type="button" 
                wire:click="seleccionarTodas" 
                class="px-3 py-1.5 text-xs font-medium text-blue-700 bg-blue-100 hover:bg-blue-200 rounded-md transition"
            >
                Seleccionar Todas
            </button>
            <button 
                type="button" 
                wire:click="deseleccionarTodas" 
                class="px-3 py-1.5 text-xs font-medium text-gray-700 bg-gray-200 hover:bg-gray-300 rounded-md transition"
            >
                Deseleccionar Todas
            </button>
        </div>

        <div class="text-sm text-gray-700 flex items-center gap-4">
            <span>Seleccionadas: <strong class="text-blue-600 font-bold">{{ count($seleccionadas) }}</strong></span>
            <span>Total: <strong class="text-green-600 font-bold">${{ number_format(count($seleccionadas) * $valorRecarga, 2) }}</strong></span>
        </div>
    </div>

    @if ($cuadrillas->isEmpty())
        <div class="text-center py-8 bg-gray-50 rounded-lg border border-dashed border-gray-200">
            <p class="text-gray-500 text-sm">No se encontraron cuadrillas {{ $search ? 'que coincidan con la búsqueda' : 'con equipo asignado' }}.</p>
        </div>
    @else
        <form wire:submit.prevent="generarReporte">
            <div class="overflow-x-auto border border-gray-200 rounded-lg">
                <table class="min-w-full divide-y divide-gray-200 text-sm">
                    <thead class="bg-gray-100">
                        <tr>
                            <th class="px-4 py-3 w-10"></th>
                            <th class="px-4 py-3 text-left font-semibold text-gray-600">#</th>
                            <th class="px-4 py-3 text-left font-semibold text-gray-600">Ciudad</th>
                            <th class="px-4 py-3 text-left font-semibold text-gray-600">Nombre de Cuadrilla</th>
                            <th class="px-4 py-3 text-left font-semibold text-gray-600">Líneas Asignadas</th>
                            <th class="px-4 py-3 text-left font-semibold text-gray-600">Integrantes</th>
                            <th class="px-4 py-3 text-right font-semibold text-gray-600">Valor Recarga</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-100">
                        @foreach ($cuadrillas as $index => $cuadrilla)
                            <tr wire:key="cuadrilla-row-{{ $cuadrilla->id }}" class="hover:bg-gray-50 transition">
                                <td class="px-4 py-2 text-center">
                                    <input 
                                        type="checkbox" 
                                        wire:model.live="seleccionadas" 
                                        value="{{ $cuadrilla->id }}"
                                        class="rounded border-gray-300 text-blue-600 focus:ring-blue-500"
                                    >
                                </td>

                                <td class="px-4 py-2 text-gray-500">
                                    {{ $index + 1 }}
                                </td>

                                <td class="px-4 py-2 text-gray-700 font-medium">
                                    <span class="inline-flex items-center px-2 py-0.5 rounded text-xs bg-gray-100 text-gray-700">
                                        {{ $cuadrilla->cua_ciudad ?? 'Sin ciudad' }}
                                    </span>
                                </td>

                                <td class="px-4 py-2 text-gray-800 font-semibold">
                                    {{ $cuadrilla->cua_nombre }}
                                </td>

                                <td class="px-4 py-2 text-gray-700">
                                    <div class="flex flex-wrap gap-1">
                                        @foreach ($cuadrilla->equipos as $equipo)
                                            <span class="inline-flex items-center px-2 py-0.5 rounded text-xs bg-blue-50 text-blue-700 font-mono">
                                                {{ $equipo->serie }}
                                            </span>
                                        @endforeach
                                    </div>
                                </td>

                                <td class="px-4 py-2 text-gray-700">
                                    @if ($cuadrilla->users->isEmpty())
                                        <span class="text-gray-400 text-xs italic">Sin integrantes</span>
                                    @else
                                        <ul class="list-disc list-inside text-xs space-y-0.5">
                                            @foreach ($cuadrilla->users as $user)
                                                <li>{{ $user->name }}</li>
                                            @endforeach
                                        </ul>
                                    @endif
                                </td>

                                <td class="px-4 py-2 text-right font-semibold text-gray-800">
                                    ${{ number_format($valorRecarga, 2) }}
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <!-- Botón Generar con Estado de Carga -->
            <div class="mt-6 flex justify-end">
                <button 
                    type="submit" 
                    wire:loading.attr="disabled"
                    class="bg-blue-600 hover:bg-blue-700 disabled:opacity-50 text-white font-medium px-5 py-2.5 rounded-lg shadow-sm transition flex items-center gap-2"
                >
                    <svg wire:loading wire:target="generarReporte" class="animate-spin -ml-1 mr-2 h-4 w-4 text-white" fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                    </svg>
                    <span>Generar Reporte</span>
                </button>
            </div>
        </form>
    @endif
</div>
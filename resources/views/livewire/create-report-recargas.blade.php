<div class="p-6 bg-white rounded-lg shadow">
    <h2 class="text-lg font-semibold text-gray-800 mb-4">Listado de Cuadrillas</h2>

    @if ($cuadrillas->isEmpty())
        <p class="text-gray-500 text-sm">No hay cuadrillas registradas.</p>
    @else
        <form wire:submit.prevent="generarReporte">
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200 text-sm">
                    <thead class="bg-gray-100">
                        <tr>
                            <th class="px-4 py-2"></th>
                            <th class="px-4 py-2 text-left font-semibold text-gray-600">#</th>
                            <th class="px-4 py-2 text-left font-semibold text-gray-600">Nombre de Cuadrilla</th>
                            <th class="px-4 py-2 text-left font-semibold text-gray-600">
                                Linea Asignada
                            </th>
                            <th class="px-4 py-2 text-left font-semibold text-gray-600">Integrantes</th>
                            <th class="px-4 py-2 text-left font-semibold text-gray-600">Valor de Recarga</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-100">
                        @foreach ($cuadrillas as $index => $cuadrilla)
                            @foreach ($cuadrilla->equipos as $equipo)
                                <tr class="hover:bg-gray-50">
                                    <td class="px-4 py-2">
                                        <input type="checkbox" wire:model="seleccionadas" value="{{ $cuadrilla->id }}"
                                            class="rounded text-blue-600">
                                    </td>

                                    <td class="px-4 py-2 text-gray-700">
                                        {{ $index + 1 }}
                                    </td>

                                    <td class="px-4 py-2 text-gray-700">
                                        {{ $cuadrilla->cua_nombre }}
                                    </td>

                                    <td class="px-4 py-2 text-gray-700">
                                        {{ $equipo->serie }}
                                    </td>

                                    {{-- INTEGRANTES --}}
                                    <td class="px-4 py-2 text-gray-700">
                                        @if ($cuadrilla->users->isEmpty())
                                            <span class="text-gray-400 text-xs">Sin integrantes</span>
                                        @else
                                            <ul class="list-disc list-inside text-xs">
                                                @foreach ($cuadrilla->users as $user)
                                                    <li>{{ $user->name }}</li>
                                                @endforeach
                                            </ul>
                                        @endif
                                    </td>

                                    <td class="px-4 py-2 text-gray-700">
                                        $ {{ number_format($valorRecarga, 2) }}
                                    </td>
                                </tr>
                            @endforeach
                        @endforeach
                    </tbody>

                </table>
            </div>

            <div class="mt-4 flex justify-end">
                <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">
                    Generar Reporte
                </button>
            </div>
        </form>
    @endif
</div>

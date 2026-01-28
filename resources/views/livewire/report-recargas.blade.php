<div class="p-6 bg-white rounded-lg shadow">
    <h2 class="text-lg font-semibold text-gray-800 mb-4">📋 Reportes Generados</h2>

    <div class="overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-200 text-sm">
            <thead class="bg-gray-100">
                <tr>
                    <th class="px-4 py-2 text-left font-semibold text-gray-600">#</th>
                    <th class="px-4 py-2 text-left font-semibold text-gray-600">Mes</th>
                    <th class="px-4 py-2 text-left font-semibold text-gray-600">Fecha</th>
                    <th class="px-4 py-2 text-left font-semibold text-gray-600">Total</th>
                    <th class="px-4 py-2 text-left font-semibold text-gray-600">Cuadrillas</th>
                    <th class="px-4 py-2 text-center font-semibold text-gray-600">Acciones</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-100">
                @foreach ($reportes as $index => $reporte)
                    <tr>
                        <td class="px-4 py-2">{{ $index + 1 }}</td>
                        <td class="px-4 py-2">{{ $reporte->mes }}</td>
                        <td class="px-4 py-2">{{ $reporte->fecha_registro }}</td>
                        <td class="px-4 py-2 font-semibold text-green-600">${{ number_format($reporte->total, 2) }}</td>
                        <td class="px-4 py-2">{{ $reporte->detalles_count }}</td>
                        <td class="px-4 py-2 text-center">
                            {{-- EDITAR --}}
                            <a href="{{ route('admin.reportes.edit', $reporte) }}"
                                onclick="return validarEdicionReporte(
                                   {{ auth()->user()->hasRole('admin') ? 'true' : 'false' }},
                                   '{{ $reporte->fecha_registro }}'
                               )"
                                class="text-blue-600 hover:text-blue-800">
                                <i class="fas fa-pen"></i>
                            </a>


                            @role('Admin')
                                <button wire:click="eliminarReporte({{ $reporte->id }})"
                                    class="ml-3 text-red-600 hover:text-red-800">
                                    <i class="fas fa-trash"></i>
                                </button>
                            @endrole

                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>

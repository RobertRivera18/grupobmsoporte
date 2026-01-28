<x-admin-layout :breadcrumbs="[
    [
        'name' => 'Home',
        'url' => route('admin.dashboard'),
    ],
    [
        'name' => 'Reportes Recargas',
        'url' => route('admin.reportes.index'),
    ],
    [
        'name' => 'Editar Reportes Recargas Mensuales',
    ],
]">

    <div class="p-6 bg-white rounded-lg shadow-md">
        <h2 class="text-xl font-semibold mb-6 border-b pb-2 text-gray-800">
            Editar Reporte:
            <span class="font-bold text-blue-700">{{ $reporte->mes ?? '' }}</span>
        </h2>

        <form action="{{ route('admin.reportes.update', $reporte) }}" method="POST">
            @csrf
            @method('PUT')

            <!-- 🌐 Tabla responsiva -->
            <div class="overflow-x-auto rounded-lg border border-gray-200">
                <table class="min-w-full divide-y divide-gray-200 text-sm  md:table">
                    <thead class="bg-gray-100">
                        <tr>
                            <th class="px-4 py-2"></th>
                            <th class="px-4 py-2 text-left font-semibold text-gray-600">#</th>
                            <th class="px-4 py-2 text-left font-semibold text-gray-600">Nombre de Cuadrilla</th>
                            <th class="px-4 py-2 text-left font-semibold text-gray-600">
                                Integrantes
                            </th>
                            <th class="px-4 py-2 text-left font-semibold text-gray-600">Equipos Asignados</th>
                            <th class="px-4 py-2 text-left font-semibold text-gray-600">Valor de Recarga</th>
                            <th class="px-4 py-2 text-left font-semibold text-gray-600">Observación</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-100">
                        @foreach ($cuadrillas as $index => $cuadrilla)
                            <tr class="hover:bg-gray-50 transition-colors">
                                <td class="px-4 py-2">
                                    <input type="checkbox" name="seleccionadas[]" value="{{ $cuadrilla->id }}"
                                        {{ in_array($cuadrilla->id, $cuadrillasSeleccionadas) ? 'checked' : '' }}
                                        class="rounded text-blue-600 focus:ring-blue-500">
                                </td>
                                <td class="px-4 py-2 text-gray-700">{{ $index + 1 }}</td>
                                <td class="px-4 py-2 text-gray-700 font-medium">{{ $cuadrilla->cua_nombre }}


                                    @if ($cuadrilla->cua_ciudad == 1)
                                        <span
                                            class="bg-yellow-100 text-yellow-800 text-xs font-medium ms-2 px-2.5 py-0.5 rounded-sm dark:bg-yellow-900 dark:text-yellow-300">
                                            Guayaquil
                                        </span>
                                    @elseif ($cuadrilla->cua_ciudad == 2)
                                        <span
                                            class="bg-purple-100 text-purple-800 text-xs font-medium ms-2 px-2.5 py-0.5 rounded-sm dark:bg-purple-900 dark:text-purple-300">
                                            Quito
                                        </span>
                                    @endif
                                </td>
                                <td class="px-4 py-2 text-gray-700">
                                    @if ($cuadrilla->users->isEmpty())
                                        <span class="text-gray-400 text-xs italic">
                                            Sin integrantes
                                        </span>
                                    @else
                                        <ul class="list-disc  text-xs">
                                            @foreach ($cuadrilla->users as $user)
                                                <li>{{ $user->name }}</li>
                                            @endforeach
                                        </ul>
                                    @endif
                                </td>

                                <td class="px-4 py-2 text-gray-700">
                                    @foreach ($cuadrilla->equipos as $equipo)
                                        <span
                                            class="inline-block bg-gray-100 text-gray-800 text-xs px-2 py-1 rounded-md mr-1 mb-1">
                                            {{ $equipo->serie }}
                                        </span>
                                    @endforeach
                                </td>
                                <td class="px-4 py-2 text-gray-700">$ {{ number_format(10.5, 2) }}</td>
                                <td class="px-4 py-2">
                                    @if ($rol === 'operador1' || $rol === 'operador2')
                                        <input type="text" name="observaciones[{{ $cuadrilla->id }}]"
                                            placeholder="Escribe una novedad o comentario..."
                                            class="w-full border-gray-300 rounded-md shadow-sm text-sm focus:ring-blue-500 focus:border-blue-500"
                                            value="{{ old('observaciones.' . $cuadrilla->id, optional($reporte->detalles->firstWhere('cuadrilla_id', $cuadrilla->id))->observacion ?? '') }}">
                                    @else
                                        <p
                                            class="text-gray-700 text-sm bg-gray-50 border border-gray-200 rounded-md px-2 py-1">
                                            {{ $reporte->detalles->firstWhere('cuadrilla_id', $cuadrilla->id)->observacion ?? '— Sin observaciones —' }}
                                        </p>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>

            </div>

            @error('seleccionadas')
                <p class="text-red-500 mt-3 text-sm">{{ $message }}</p>
            @enderror

            <div class="mt-6 text-right">
                <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-5 py-2 rounded-lg shadow">
                    Actualizar Reporte
                </button>
            </div>
        </form>
    </div>

</x-admin-layout>

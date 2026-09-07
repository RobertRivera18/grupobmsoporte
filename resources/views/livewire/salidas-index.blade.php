<div class="bg-white dark:bg-gray-800 p-6 rounded-2xl shadow-lg">

    {{-- Header --}}
    <div class="flex justify-between items-center mb-6">
        <div>
            <h2 class="text-xl font-semibold text-gray-800 dark:text-white">
                Mis Solicitudes de Salida
            </h2>
            <p class="text-sm text-gray-500">
                Historial de solicitudes de equipos
            </p>
        </div>

    </div>

    {{-- Mensajes --}}
    @if (session()->has('message'))
        <div class="mb-4 p-3 bg-green-100 text-green-800 rounded-lg">
            {{ session('message') }}
        </div>
    @endif

    @if (session()->has('error'))
        <div class="mb-4 p-3 bg-red-100 text-red-800 rounded-lg">
            {{ session('error') }}
        </div>
    @endif

    {{-- Tabla --}}
    <div class="overflow-x-auto border rounded-xl">

        <table class="w-full text-sm text-left text-gray-500 dark:text-gray-400">

            <thead class="text-xs uppercase bg-gray-100 dark:bg-gray-700 text-gray-600 dark:text-gray-300">
                <tr>
                    <th class="px-4 py-3">Fecha Solicitud</th>
                    <th class="px-4 py-3">Equipo</th>
                    <th class="px-4 py-3">Marca</th>
                    <th class="px-4 py-3">Modelo</th>
                    <th class="px-4 py-3">Serie</th>
                    <th class="px-4 py-3">Usuario</th>
                    <th class="px-4 py-3">Salida</th>
                    <th class="px-4 py-3">Retorno</th>
                    <th class="px-4 py-3">Estado</th>
                    <th class="px-4 py-3">Aprobado por</th>
                    <th class="px-4 py-3 text-center">Acciones</th>
                </tr>
            </thead>

            <tbody>

                @forelse ($salidas as $salida)
                    <tr class="border-b dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-700">

                        <td class="px-4 py-3 font-medium text-gray-800 dark:text-white">
                            {{ $salida->created_at->format('d/M/Y H:s') }}
                        </td>

                        {{-- Equipo --}}
                        <td class="px-4 py-3 font-medium text-gray-800 dark:text-white">
                            {{ $salida->equipos->nombre ?? '-' }}
                        </td>

                        <td class="px-4 py-3">{{ $salida->equipos->marca ?? '-' }}</td>
                        <td class="px-4 py-3">{{ $salida->equipos->modelo ?? '-' }}</td>
                        <td class="px-4 py-3">{{ $salida->equipos->serie ?? '-' }}</td>
                        <td class="px-4 py-3 font-medium text-gray-800 dark:text-white">
                            {{ $salida->user->name ?? '—' }}
                        </td>

                        {{-- Fechas --}}
                        <td class="px-4 py-3">
                            {{ \Carbon\Carbon::parse($salida->fecha_salida_solicitada)->format('d/m/Y') }}
                        </td>

                        <td class="px-4 py-3">
                            {{ \Carbon\Carbon::parse($salida->fecha_retorno_estimada)->format('d/m/Y') }}
                        </td>

                        {{-- Estado --}}
                        <td class="px-4 py-3">
                            @switch($salida->estado)
                                @case('pendiente')
                                    <span class="bg-yellow-100 text-yellow-800 text-xs px-2 py-1 rounded">
                                        Pendiente
                                    </span>
                                @break

                                @case('aprobado')
                                    <span class="bg-green-100 text-green-800 text-xs px-2 py-1 rounded">
                                        Aprobado
                                    </span>
                                @break

                                @case('rechazado')
                                    <span class="bg-red-100 text-red-800 text-xs px-2 py-1 rounded">
                                        Rechazado
                                    </span>
                                @break

                                @case('cancelado')
                                    <span class="bg-gray-200 text-gray-700 text-xs px-2 py-1 rounded">
                                        Cancelado
                                    </span>
                                @break
                            @endswitch
                        </td>

                        {{-- Aprobador --}}
                        <td class="px-4 py-3">
                            {{ $salida->aprobador->name ?? '—' }}
                        </td>

                        {{-- Acciones --}}
                        <td class="px-4 py-3 text-center flex items-center gap-2">

                            {{-- @if ($salida->estado === 'pendiente')
                                <button wire:click="cancelar({{ $salida->id }})"
                                    class="text-red-500 hover:underline text-sm">
                                    Cancelar
                                </button>
                            @else
                                <span class="text-gray-400 text-xs">—</span>
                            @endif --}}


                            {{-- Ver --}}
                            <a href="{{ route('admin.salidas.edit', $salida) }}"
                                class="p-2 rounded-lg bg-blue-100 text-blue-600 hover:bg-blue-200 transition"
                                title="Ver detalle">
                                <i class="fas fa-eye"></i>
                            </a>

                            {{-- Editar --}}
                            {{-- <button class="p-2 rounded-lg bg-yellow-100 text-yellow-600 hover:bg-yellow-200 transition"
                                title="Editar">
                                <i class="fas fa-pen"></i>
                            </button> --}}


                        </td>

                    </tr>
                    @empty
                        <tr>
                            <td colspan="9" class="text-center py-6 text-gray-500">
                                No tienes solicitudes registradas
                            </td>
                        </tr>
                    @endforelse

                </tbody>

            </table>

        </div>

    </div>

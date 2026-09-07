<x-admin-layout :breadcrumbs="[
    ['name' => 'Home', 'url' => route('admin.dashboard')],
    ['name' => 'Listado de Vehiculo', 'url' => route('admin.vehiculos.index')],
]">

    <div class="container mx-auto px-4 py-6">

        <div class="flex flex-col md:flex-row md:items-center md:justify-between mb-6 gap-4">
            <div>
                <h1 class="text-2xl font-bold text-gray-800 tracking-tight">Control de Vehículos</h1>
                <p class="text-sm text-gray-500 mt-1">
                    Visualiza, edita y gestiona el parque automotor registrado.
                    <span class="ml-1 px-2 py-0.5 text-xs font-semibold bg-gray-100 text-gray-600 rounded-full">
                        {{ $vehiculos->count() }} en total
                    </span>
                </p>
            </div>
         
        </div>

        <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
            @if ($vehiculos->isEmpty())
                <div class="p-12 text-center">
                    <div class="inline-flex p-4 bg-gray-50 rounded-full text-gray-400 mb-4">
                        <svg class="w-12 h-12" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4" />
                        </svg>
                    </div>
                    <h3 class="text-lg font-medium text-gray-900 mb-1">No hay vehículos registrados</h3>
                    <p class="text-gray-500 text-sm max-w-sm mx-auto mb-6">Comienza agregando tu primer vehículo al
                        sistema para realizar el seguimiento de incidentes o asignaciones.</p>
                    <a href="{{ route('admin.vehiculos.create') }}"
                        class="inline-flex items-center px-4 py-2 border border-gray-300 shadow-sm text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 central-transition">
                        Agregar vehículo ahora
                    </a>
                </div>
            @else
                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse whitespace-nowrap">
                        <thead>
                            <tr
                                class="bg-gray-50 border-b border-gray-200 text-xs font-semibold text-gray-600 uppercase tracking-wider">
                                <th class="px-6 py-4">Placa / Identificador</th>
                                <th class="px-6 py-4">Marca y Modelo</th>
                                <th class="px-6 py-4">Kilometraje Actual</th>
                                <th class="px-6 py-4 text-right">Acciones</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100 text-sm text-gray-700">
                            @foreach ($vehiculos as $vehiculo)
                                <tr class="hover:bg-gray-50/70 transition-colors">
                                    <td class="px-6 py-4">
                                        <span
                                            class="font-mono bg-gray-100 border border-gray-300 text-gray-800 px-2.5 py-1 rounded md:text-sm font-bold shadow-xs">
                                            {{ $vehiculo->placa }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4">
                                        <div class="font-medium text-gray-900">{{ $vehiculo->marca }}</div>
                                        <div class="text-xs text-gray-500">{{ $vehiculo->modelo }}</div>
                                    </td>


                                    <td class="px-6 py-4">
                                        @php
                                            $ultimoMovimiento = $vehiculo->movimientos->first();
                                        @endphp

                                        @if ($ultimoMovimiento && isset($ultimoMovimiento->kilometraje))
                                            <div class="flex flex-col items-flex-start space-y-1">
                                                <div
                                                    class="inline-flex items-center self-start px-2.5 py-1 rounded-md text-xs font-semibold bg-slate-100 text-slate-700 border border-slate-200 font-mono">
                                                    <svg class="w-4 h-4 mr-1.5 text-slate-500" fill="none"
                                                        stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            stroke-width="2"
                                                            d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 002 2h2a2 2 0 002-2z" />
                                                    </svg>
                                                    {{ number_format($ultimoMovimiento->kilometraje, 0, ',', '.') }} km
                                                </div>

                                                <span class="text-xs text-gray-400 inline-flex items-center pl-1">
                                                    <svg class="w-3 h-3 mr-1 text-gray-400" fill="none"
                                                        stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            stroke-width="2"
                                                            d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 002-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                                    </svg>

                                                    {{ \Carbon\Carbon::parse($ultimoMovimiento->fecha ?? $ultimoMovimiento->created_at)->format('d M Y H:i') }}
                                                </span>
                                            </div>
                                        @else
                                            <span class="text-xs text-gray-400 italic">Sin registros</span>
                                        @endif
                                    </td>

                                    <td class="px-6 py-4 text-right space-x-1">
                                        <a href="{{ route('admin.vehiculos.inspeccionar', $vehiculo->id) }}"
                                            class="inline-flex p-2 text-gray-400 hover:text-blue-600 rounded-lg hover:bg-gray-100 transition-colors"
                                            title="Nueva Inspección (FOR-MAN-05)">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4" />
                                            </svg>
                                        </a>

                                        <a href="{{ route('admin.vehiculos.show', $vehiculo->id) }}"
                                            class="inline-flex p-2 text-gray-400 hover:text-indigo-600 rounded-lg hover:bg-gray-100 transition-colors"
                                            title="Ver Detalles">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                            </svg>
                                        </a>

                                        <a href="{{ route('admin.vehiculos.edit', $vehiculo->id) }}"
                                            class="inline-flex p-2 text-gray-400 hover:text-amber-600 rounded-lg hover:bg-gray-100 transition-colors"
                                            title="Editar">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z" />
                                            </svg>
                                        </a>

                                        <form action="{{ route('admin.vehiculos.destroy', $vehiculo->id) }}"
                                            method="POST" class="inline-block"
                                            onsubmit="return confirm('¿Estás seguro de eliminar este vehículo?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit"
                                                class="inline-flex p-2 text-gray-400 hover:text-red-600 rounded-lg hover:bg-gray-100 transition-colors"
                                                title="Eliminar">
                                                <svg class="w-5 h-5" fill="none" stroke="currentColor"
                                                    viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        stroke-width="2"
                                                        d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                                </svg>
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endif
        </div>
    </div>
</x-admin-layout>

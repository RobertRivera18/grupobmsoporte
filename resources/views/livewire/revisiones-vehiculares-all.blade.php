<div class="space-y-6">
    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
        <div class="bg-white p-4 rounded-lg shadow-sm border border-gray-100 flex items-center justify-between">
            <div>
                <p class="text-sm font-medium text-gray-500 uppercase tracking-wider">Total Revisiones</p>
                <p class="text-2xl font-bold text-gray-800">{{ $total }}</p>
            </div>
            <div class="bg-blue-50 p-3 rounded-full text-blue-600 text-xl">📋</div>
        </div>
        <div class="bg-white p-4 rounded-lg shadow-sm border border-gray-100 flex items-center justify-between">
            <div>
                <p class="text-sm font-medium text-gray-500 uppercase tracking-wider">Revisiones este Mes</p>
                <p class="text-2xl font-bold text-gray-800">{{ $esteMes }}</p>
            </div>
            <div class="bg-green-50 p-3 rounded-full text-green-600 text-xl">📅</div>
        </div>
        <div class="bg-white p-4 rounded-lg shadow-sm border border-gray-100 flex items-center justify-between">
            <div>
                <p class="text-sm font-medium text-gray-500 uppercase tracking-wider">Total de Vehiculos</p>
                <p class="text-2xl font-bold text-gray-800">{{ $totalVehiculos }}</p>
            </div>
            <div class="bg-purple-50 p-3 rounded-full text-purple-600 text-xl">🚗</div>
        </div>
    </div>

    <div class="bg-white p-4 rounded-lg shadow-sm border border-gray-100 space-y-4">
        <div class="grid grid-cols-1 md:grid-cols-5 gap-3">
            <div>
                <label class="block text-xs font-semibold text-gray-600 uppercase mb-1">Buscar</label>
                <input wire:model.live.debounce.300ms="search" type="text" placeholder="Técnico, placa, marca..." class="w-full text-sm rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
            </div>

            <div>
                <label class="block text-xs font-semibold text-gray-600 uppercase mb-1">Vehículo</label>
                <select wire:model.live="vehiculo" class="w-full text-sm rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                    <option value="">Todos los vehículos</option>
                    @foreach($vehiculos as $v)
                        <option value="{{ $v->id }}">{{ $v->placa }} - {{ $v->marca }}</option>
                    @endforeach
                </select>
            </div>

            <div>
                <label class="block text-xs font-semibold text-gray-600 uppercase mb-1">Fecha Exacta</label>
                <input wire:model.live="fecha" type="date" class="w-full text-sm rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
            </div>

            <div>
                <label class="block text-xs font-semibold text-gray-600 uppercase mb-1">Desde</label>
                <input wire:model.live="desde" type="date" class="w-full text-sm rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
            </div>

            <div>
                <label class="block text-xs font-semibold text-gray-600 uppercase mb-1">Hasta</label>
                <input wire:model.live="hasta" type="date" class="w-full text-sm rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
            </div>
        </div>

        <div class="flex justify-end">
            <button wire:click="limpiarFiltros" type="button" class="inline-flex items-center text-xs font-semibold text-red-600 hover:text-red-800 transition">
                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-4v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                Limpiar Filtros
            </button>
        </div>
    </div>

    <div class="bg-white rounded-lg shadow-sm border border-gray-100 overflow-hidden">
        <table class="min-w-full divide-y divide-gray-200 text-sm">
            <thead class="bg-gray-50">
                <tr class="text-xs font-bold text-gray-500 uppercase tracking-wider">
                    <th wire:click="sortBy('fecha')" class="px-6 py-3 text-left cursor-pointer hover:bg-gray-100 transition select-none">
                        Fecha {{$sortField === 'fecha' ? ($sortDirection === 'asc' ? '▲':'▼') : ''}}
                    </th>
                    <th class="px-6 py-3 text-left">Placa / Vehículo</th>
                    <th wire:click="sortBy('tecnico_encargado')" class="px-6 py-3 text-left cursor-pointer hover:bg-gray-100 transition select-none">
                        Técnico {{$sortField === 'tecnico_encargado' ? ($sortDirection === 'asc' ? '▲':'▼') : ''}}
                    </th>
                    <th wire:click="sortBy('kilometraje')" class="px-6 py-3 text-left cursor-pointer hover:bg-gray-100 transition select-none">
                        Kilometraje {{$sortField === 'kilometraje' ? ($sortDirection === 'asc' ? '▲':'▼') : ''}}
                    </th>
                    <th class="px-6 py-3 text-left">Equipo</th>
                    <th class="px-6 py-3 text-right">Acciones</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-200 bg-white">
                @forelse($revisiones as $revision)
                    <tr class="hover:bg-gray-50 transition">
                        <td class="px-6 py-4 whitespace-nowrap font-medium text-gray-900">
                            {{ \Carbon\Carbon::parse($revision->fecha)->format('d/m/Y') }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="font-bold text-gray-800 bg-gray-50 border border-gray-200 inline-block px-2 py-0.5 rounded font-mono text-xs">
                                {{ $revision->vehiculo->placa }}
                            </div>
                            <div class="text-xs text-gray-500 mt-0.5">{{ $revision->vehiculo->marca }} {{ $revision->vehiculo->modelo }}</div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-gray-700">
                            {{ $revision->tecnicoEncargado->name }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-gray-700 font-medium">
                            <span class="bg-gray-100 px-2 py-1 rounded text-xs text-gray-600">
                                📊 {{ number_format($revision->kilometraje) }} km
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="px-2.5 py-0.5 inline-flex text-xs leading-5 font-semibold rounded-full {{ $revision->tipo_equipo === 'propio' ? 'bg-green-100 text-green-800' : 'bg-amber-100 text-amber-800' }}">
                                {{ ucfirst($revision->tipo_equipo) }}
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-right font-medium space-x-3">
                            <a href="{{ route('admin.revisiones.show', $revision) }}" class="text-gray-400 hover:text-green-600 inline-block transition" title="Ver Historial Completo">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                </svg>
                            </a>
                            <button onclick="confirm('¿Estás completamente seguro de eliminar este registro de revisión?') || event.stopImmediatePropagation()" 
                                    wire:click="eliminar({{ $revision->id }})" 
                                    class="text-gray-400 hover:text-red-600 inline-block transition" 
                                    title="Eliminar Reporte">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-4v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                </svg>
                            </button>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="px-6 py-12 text-center text-gray-500 font-medium">
                            No se encontraron registros de revisiones vehiculares que coincidan con los filtros aplicados.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-4">
        {{ $revisiones->links() }}
    </div>
</div>
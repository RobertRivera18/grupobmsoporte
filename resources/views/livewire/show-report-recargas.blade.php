<div class="max-w-7xl mx-auto space-y-6">
    
    <!-- ENCABEZADO Y BUSCADOR -->
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 flex flex-col md:flex-row md:items-center md:justify-between gap-4">
        <div class="space-y-1">
            <div class="flex items-center gap-2">
                <span class="px-2.5 py-1 text-xs font-semibold bg-blue-50 text-blue-700 rounded-full">Reporte #{{ $reporte->id }}</span>
                <span class="text-xs text-gray-400">•</span>
                <span class="text-xs text-gray-500 font-medium">Control Operativo</span>
            </div>
            <h1 class="text-2xl font-bold tracking-tight text-gray-900">Gestión de Recargas</h1>
            <p class="text-sm text-gray-500">Activa o desactiva las recargas para actualizar el estado y el acumulado al instante.</p>
        </div>

        <div class="w-full md:w-80 relative">
            <span class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-gray-400">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                </svg>
            </span>
            <input type="text" wire:model.live.debounce.300ms="search"
                placeholder="Buscar por cuadrilla, serie o técnico..."
                class="w-full pl-10 pr-4 py-2.5 text-sm bg-gray-50/50 border border-gray-200 rounded-xl focus:bg-white focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 transition-all">
        </div>
    </div>

    <!-- TARJETAS DE RESUMEN (KPIs) -->
    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
        <!-- Completadas -->
        <div class="bg-white rounded-2xl p-5 border border-emerald-100 shadow-sm flex items-center justify-between relative overflow-hidden">
            <div class="absolute -right-4 -bottom-4 w-20 h-20 bg-emerald-50 rounded-full pointer-events-none"></div>
            <div class="space-y-1">
                <p class="text-xs font-semibold uppercase tracking-wider text-emerald-600">Completadas</p>
                <h3 class="text-3xl font-extrabold text-gray-900">{{ $totalRealizadasCount }}</h3>
            </div>
            <div class="w-12 h-12 rounded-xl bg-emerald-50 text-emerald-600 flex items-center justify-center font-bold">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
            </div>
        </div>

        <!-- Pendientes -->
        <div class="bg-white rounded-2xl p-5 border border-amber-100 shadow-sm flex items-center justify-between relative overflow-hidden">
            <div class="absolute -right-4 -bottom-4 w-20 h-20 bg-amber-50 rounded-full pointer-events-none"></div>
            <div class="space-y-1">
                <p class="text-xs font-semibold uppercase tracking-wider text-amber-600">Pendientes</p>
                <h3 class="text-3xl font-extrabold text-gray-900">{{ $cuadrillasPendientes->count() }}</h3>
            </div>
            <div class="w-12 h-12 rounded-xl bg-amber-50 text-amber-600 flex items-center justify-center font-bold">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            </div>
        </div>

        <!-- Total Acumulado -->
        <div class="bg-white rounded-2xl p-5 border border-blue-100 shadow-sm flex items-center justify-between relative overflow-hidden">
            <div class="absolute -right-4 -bottom-4 w-20 h-20 bg-blue-50 rounded-full pointer-events-none"></div>
            <div class="space-y-1">
                <p class="text-xs font-semibold uppercase tracking-wider text-blue-600">Total Acumulado</p>
                <h3 class="text-3xl font-extrabold text-gray-900">${{ number_format($totalRealizadasCount * $valorRecarga, 2) }}</h3>
            </div>
            <div class="w-12 h-12 rounded-xl bg-blue-50 text-blue-600 flex items-center justify-center font-bold">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            </div>
        </div>
    </div>

    <!-- SECCIÓN 1: PENDIENTES -->
    <div class="space-y-4">
        <div class="flex items-center justify-between px-1">
            <h3 class="text-sm font-bold text-amber-700 uppercase tracking-wider flex items-center gap-2">
                <span class="w-2.5 h-2.5 rounded-full bg-amber-500 shadow-sm"></span>
                Pendientes de Recarga ({{ $cuadrillasPendientes->count() }})
            </h3>
        </div>

        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
            <table class="w-full text-left border-collapse">
                <thead class="bg-gray-50/75 border-b border-gray-100 text-xs font-semibold text-gray-500 uppercase tracking-wider">
                    <tr>
                        <th class="px-6 py-4 w-20 text-center">Estado</th>
                        <th class="px-6 py-4">Cuadrilla / Ciudad</th>
                        <th class="px-6 py-4">Líneas Asignadas</th>
                        <th class="px-6 py-4">Integrantes</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100 text-sm">
                    @forelse ($cuadrillasPendientes as $cuadrilla)
                        <tr wire:key="pend-{{ $cuadrilla->id }}" class="hover:bg-amber-50/30 transition-colors">
                            <td class="px-6 py-4 text-center">
                                <label class="relative inline-flex items-center cursor-pointer">
                                    <input type="checkbox" wire:click="toggleRecarga({{ $cuadrilla->id }})" class="sr-only peer">
                                    <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-emerald-600 shadow-inner"></div>
                                </label>
                            </td>
                            <td class="px-6 py-4">
                                <div class="font-bold text-gray-900">{{ $cuadrilla->cua_nombre }}</div>
                                <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-gray-100 text-gray-600 mt-1">
                                    {{ $cuadrilla->cua_ciudad == 1 ? 'Guayaquil' : ($cuadrilla->cua_ciudad == 2 ? 'Quito' : 'N/A') }}
                                </span>
                            </td>
                            <td class="px-6 py-4">
                                <div class="flex flex-wrap gap-1.5">
                                    @foreach ($cuadrilla->equipos as $equipo)
                                        <span class="px-2.5 py-1 rounded-lg text-xs font-mono bg-blue-50 text-blue-700 border border-blue-100 font-medium">{{ $equipo->serie }}</span>
                                    @endforeach
                                </div>
                            </td>
                            <td class="px-6 py-4 text-gray-600 space-y-0.5">
                                @foreach ($cuadrilla->users as $user)
                                    <div class="text-xs flex items-center gap-1.5">
                                        <span class="w-1.5 h-1.5 rounded-full bg-gray-400"></span>
                                        {{ $user->name }}
                                    </div>
                                @endforeach
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="text-center py-8 text-xs text-gray-400 italic">¡Excelente! No hay cuadrillas pendientes por recargar.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- SECCIÓN 2: REALIZADAS -->
    <div class="space-y-4 pt-4">
        <div class="flex items-center justify-between px-1">
            <h3 class="text-sm font-bold text-emerald-700 uppercase tracking-wider flex items-center gap-2">
                <span class="w-2.5 h-2.5 rounded-full bg-emerald-500 shadow-sm"></span>
                Recargas Realizadas ({{ $cuadrillasRealizadas->count() }})
            </h3>
        </div>

        <div class="bg-white rounded-2xl shadow-sm border border-emerald-100 overflow-hidden">
            <table class="w-full text-left border-collapse">
                <thead class="bg-emerald-50/50 border-b border-emerald-100 text-xs font-semibold text-emerald-800 uppercase tracking-wider">
                    <tr>
                        <th class="px-6 py-4 w-20 text-center">Estado</th>
                        <th class="px-6 py-4">Cuadrilla / Ciudad</th>
                        <th class="px-6 py-4">Líneas Asignadas</th>
                        <th class="px-6 py-4 text-right">Valor Asignado</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100 text-sm">
                    @forelse ($cuadrillasRealizadas as $cuadrilla)
                        <tr wire:key="realizada-{{ $cuadrilla->id }}" class="bg-emerald-50/10 hover:bg-emerald-50/30 transition-colors">
                            <td class="px-6 py-4 text-center">
                                <label class="relative inline-flex items-center cursor-pointer">
                                    <input type="checkbox" wire:click="toggleRecarga({{ $cuadrilla->id }})" class="sr-only peer" checked>
                                    <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-emerald-600 shadow-inner"></div>
                                </label>
                            </td>
                            <td class="px-6 py-4">
                                <div class="font-bold text-gray-900">{{ $cuadrilla->cua_nombre }}</div>
                                <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-emerald-100 text-emerald-800 mt-1">
                                    {{ $cuadrilla->cua_ciudad == 1 ? 'Guayaquil' : ($cuadrilla->cua_ciudad == 2 ? 'Quito' : 'N/A') }}
                                </span>
                            </td>
                            <td class="px-6 py-4">
                                <div class="flex flex-wrap gap-1.5">
                                    @foreach ($cuadrilla->equipos as $equipo)
                                        <span class="px-2.5 py-1 rounded-lg text-xs font-mono bg-blue-50 text-blue-700 border border-blue-100 font-medium">{{ $equipo->serie }}</span>
                                    @endforeach
                                </div>
                            </td>
                            <td class="px-6 py-4 text-right font-bold text-emerald-600">
                                ${{ number_format($valorRecarga, 2) }}
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="text-center py-8 text-xs text-gray-400 italic">Aún no se ha marcado ninguna recarga en este reporte.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
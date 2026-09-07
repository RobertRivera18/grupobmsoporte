<div class="space-y-6">
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        
        <div class="rounded-2xl border border-gray-200/80 dark:border-gray-700/60 bg-white dark:bg-gray-800 p-5 shadow-sm">
            <div class="flex items-center justify-between">
                <div class="flex h-11 w-11 items-center justify-center rounded-xl bg-indigo-50 dark:bg-indigo-950/50 text-indigo-600 dark:text-indigo-400">
                    <i class="fas fa-users-gear text-base"></i>
                </div>
                <span class="inline-flex items-center gap-1 rounded-lg bg-emerald-50 dark:bg-emerald-950/50 px-2 py-1 text-xs font-semibold text-emerald-600 dark:text-emerald-400 border border-emerald-200/50 dark:border-emerald-800/40">
                    <i class="fas fa-arrow-up text-[10px]"></i> 11.01%
                </span>
            </div>
            <div class="mt-4">
                <span class="text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wide">Guayaquil</span>
                <h4 class="text-2xl font-bold text-gray-900 dark:text-white mt-0.5">{{ $cuadrillasgye }}</h4>
                <p class="text-xs text-gray-400 dark:text-gray-500">Cuadrillas operativas Claro</p>
            </div>
        </div>

        <div class="rounded-2xl border border-gray-200/80 dark:border-gray-700/60 bg-white dark:bg-gray-800 p-5 shadow-sm">
            <div class="flex items-center justify-between">
                <div class="flex h-11 w-11 items-center justify-center rounded-xl bg-indigo-50 dark:bg-indigo-950/50 text-indigo-600 dark:text-indigo-400">
                    <i class="fas fa-users-gear text-base"></i>
                </div>
                <span class="inline-flex items-center gap-1 rounded-lg bg-rose-50 dark:bg-rose-950/50 px-2 py-1 text-xs font-semibold text-rose-600 dark:text-rose-400 border border-rose-200/50 dark:border-rose-800/40">
                    <i class="fas fa-arrow-down text-[10px]"></i> 9.05%
                </span>
            </div>
            <div class="mt-4">
                <span class="text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wide">Quito</span>
                <h4 class="text-2xl font-bold text-gray-900 dark:text-white mt-0.5">{{ $cuadrillasuio }}</h4>
                <p class="text-xs text-gray-400 dark:text-gray-500">Cuadrillas operativas Claro</p>
            </div>
        </div>

        <div class="rounded-2xl border border-gray-200/80 dark:border-gray-700/60 bg-white dark:bg-gray-800 p-5 shadow-sm flex flex-col justify-between">
            <div>
                <h4 class="text-xs font-bold uppercase tracking-wider text-gray-400 dark:text-gray-500 mb-3">
                    Acciones Rápidas
                </h4>
                <div class="grid grid-cols-1 gap-2.5">
                    <a href="{{ route('admin.cuadrillas.create') }}"
                        class="inline-flex items-center justify-center gap-2 px-3.5 py-2 bg-indigo-600 hover:bg-indigo-700 text-white text-xs font-medium rounded-xl transition shadow-sm">
                        <i class="fas fa-plus text-xs"></i>
                        <span>Crear Cuadrilla</span>
                    </a>

                    <a href="{{ route('admin.credenciales.index') }}"
                        class="inline-flex items-center justify-center gap-2 px-3.5 py-2 bg-gray-100 hover:bg-gray-200 dark:bg-gray-700/60 dark:hover:bg-gray-700 text-gray-700 dark:text-gray-200 text-xs font-medium rounded-xl transition">
                        <i class="fas fa-id-card text-xs"></i>
                        <span>Credenciales Corporativas</span>
                    </a>
                </div>
            </div>
        </div>
    </div>

    <div class="rounded-2xl border border-gray-200/80 dark:border-gray-700/60 bg-white dark:bg-gray-800 p-5 shadow-sm">
        @livewire('grafica-recargas')
    </div>

    <div class="rounded-2xl border border-gray-200/80 dark:border-gray-700/60 bg-white dark:bg-gray-800 p-6 shadow-sm">
        
        <div class="flex items-center justify-between pb-4 mb-4 border-b border-gray-100 dark:border-gray-700/60">
            <div>
                <h4 class="text-base font-bold text-gray-900 dark:text-white">Últimas Cuadrillas</h4>
                <p class="text-xs text-gray-500 dark:text-gray-400">Resumen de asignaciones y estados recientes</p>
            </div>
            <div class="w-9 h-9 flex items-center justify-center rounded-xl bg-gray-100 dark:bg-gray-700/50 text-indigo-600 dark:text-indigo-400 text-sm">
                <i class="fas fa-users-cog"></i>
            </div>
        </div>

        <div class="hidden md:block overflow-x-auto">
            <table class="w-full text-xs text-left text-gray-600 dark:text-gray-300">
                <thead class="bg-gray-50/80 dark:bg-gray-700/40 text-gray-400 dark:text-gray-400 uppercase text-[10px] font-bold tracking-wider">
                    <tr>
                        <th class="px-4 py-3 rounded-l-lg">Nombre</th>
                        <th class="px-4 py-3">Ciudad</th>
                        <th class="px-4 py-3 rounded-r-lg">Estado</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100 dark:divide-gray-700/50">
                    @forelse ($cuadrillas as $cuadrilla)
                        <tr class="hover:bg-gray-50/60 dark:hover:bg-gray-700/30 transition">
                            <td class="px-4 py-3 font-semibold text-gray-800 dark:text-gray-200">
                                {{ $cuadrilla->cua_nombre }}
                            </td>
                            <td class="px-4 py-3">
                                <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-md text-[11px] font-medium {{ $cuadrilla->cua_ciudad == 1 ? 'bg-sky-50 text-sky-700 dark:bg-sky-950/40 dark:text-sky-400' : 'bg-emerald-50 text-emerald-700 dark:bg-emerald-950/40 dark:text-emerald-400' }}">
                                    <i class="fas fa-location-dot text-[9px]"></i>
                                    {{ $cuadrilla->cua_ciudad == 1 ? 'Guayaquil' : 'Quito' }}
                                </span>
                            </td>
                            <td class="px-4 py-3">
                                <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-md text-[11px] font-medium {{ $cuadrilla->estado == 1 ? 'bg-emerald-50 text-emerald-700 dark:bg-emerald-950/40 dark:text-emerald-400' : 'bg-rose-50 text-rose-700 dark:bg-rose-950/40 dark:text-rose-400' }}">
                                    <span class="w-1.5 h-1.5 rounded-full {{ $cuadrilla->estado == 1 ? 'bg-emerald-500' : 'bg-rose-500' }}"></span>
                                    {{ $cuadrilla->estado == 1 ? 'Activa' : 'Inactiva' }}
                                </span>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="3" class="px-4 py-6 text-center text-gray-400 dark:text-gray-500">
                                No hay cuadrillas registradas.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="md:hidden space-y-3">
            @forelse ($cuadrillas as $cuadrilla)
                <div class="p-3.5 bg-gray-50/80 dark:bg-gray-700/30 border border-gray-100 dark:border-gray-700/40 rounded-xl space-y-2">
                    <div class="flex items-center justify-between">
                        <span class="text-xs font-bold text-gray-800 dark:text-gray-200">
                            {{ $cuadrilla->cua_nombre }}
                        </span>
                        <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-md text-[10px] font-medium {{ $cuadrilla->estado == 1 ? 'bg-emerald-50 dark:bg-emerald-950/50 text-emerald-600 dark:text-emerald-400' : 'bg-rose-50 dark:bg-rose-950/50 text-rose-600 dark:text-rose-400' }}">
                            {{ $cuadrilla->estado == 1 ? 'Activa' : 'Inactiva' }}
                        </span>
                    </div>

                    <div class="flex items-center justify-between text-[11px] text-gray-500 dark:text-gray-400 pt-1">
                        <span>Sede de operación:</span>
                        <span class="font-medium text-gray-700 dark:text-gray-300">
                            {{ $cuadrilla->cua_ciudad == 1 ? 'Guayaquil' : 'Quito' }}
                        </span>
                    </div>
                </div>
            @empty
                <div class="p-4 text-center text-xs text-gray-400 dark:text-gray-500">
                    No hay cuadrillas registradas.
                </div>
            @endforelse
        </div>

    </div>
</div>
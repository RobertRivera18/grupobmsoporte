<div class="space-y-8">
    <!-- Métricas -->
    <div class="grid grid-cols-1 sm:grid-cols-3 gap-6">
        <!-- Métrica: Customers -->
        <div class="rounded-2xl border border-gray-200 bg-white p-6 shadow-sm">
            <div class="flex items-center justify-between">
                <div class="flex h-12 w-12 items-center justify-center rounded-xl bg-gray-100">
                    <i class="fas fa-user-friends text-gray-700 text-lg"></i>
                </div>
                <span
                    class="flex items-center gap-1 rounded-full bg-green-100 text-green-700 text-sm px-2 py-0.5 font-medium">
                    <i class="fas fa-arrow-up text-xs"></i> 11.01%
                </span>
            </div>
            <div class="mt-4">
                <p class="text-sm text-gray-500">Cuadrillas Claro en Guayaquil</p>
                <h4 class="text-2xl font-bold text-gray-800">{{ $cuadrillasgye }}</h4>
            </div>


        </div>

        <!-- Métrica: Orders -->
        <div class="rounded-2xl border border-gray-200 bg-white p-6 shadow-sm">
            <div class="flex items-center justify-between">
                <div class="flex h-12 w-12 items-center justify-center rounded-xl bg-gray-100">
                    <i class="fas fa-user-friends text-gray-700 text-lg"></i>
                </div>
                <span
                    class="flex items-center gap-1 rounded-full bg-red-100 text-red-700 text-sm px-2 py-0.5 font-medium">
                    <i class="fas fa-arrow-down text-xs"></i> 9.05%
                </span>
            </div>
            <div class="mt-4">
                <p class="text-sm text-gray-500">Cuadrillas Claro en Quito</p>
                <h4 class="text-2xl font-bold text-gray-800">{{ $cuadrillasuio }}</h4>
            </div>
        </div>


        <div class="p-6 bg-white rounded-xl shadow-md">
            <p class="text-lg font-semibold text-gray-900 mb-4">Acciones Rápidas</p>
            <div class="space-y-3">
                <a href="{{ route('admin.cuadrillas.create') }}"
                    class="w-full flex items-center justify-center gap-2 px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white font-semibold rounded-lg transition">
                    <i class="fas fa-users"></i>

                    Crear Cuadrilla
                </a>

                <a href="{{ route('admin.credenciales.index') }}"
                    class="w-full flex items-center justify-center gap-2 px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white font-semibold rounded-lg transition">
                    <i class="fas fa-id-card"></i>
                    Credenciales Corporativas
                </a>


            </div>
        </div>
    </div>

    @livewire('grafica-recargas')

    <!-- Tabla de cuadrillas -->
    <div class="rounded-2xl border border-gray-200 bg-white p-6 shadow-sm">
        <!-- Encabezado -->
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-6">
            <div>
                <h4 class="text-2xl font-bold text-gray-800">Últimas Cuadrillas</h4>
                <p class="text-sm text-gray-500">Resumen de registros recientes</p>
            </div>
            <div class="text-indigo-500 text-3xl">
                <i class="fas fa-users-cog"></i>
            </div>
        </div>

        <!-- Tabla escritorio -->
        <div class="hidden md:block">
            <table class="w-full text-sm text-left text-gray-700">
                <thead class="bg-gray-50 text-gray-500 uppercase text-xs">
                    <tr>
                        <th class="px-5 py-3 font-semibold">Nombre</th>
                        <th class="px-5 py-3 font-semibold">Ciudad</th>
                        <th class="px-5 py-3 font-semibold">Estado</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100 bg-white">
                    @forelse ($cuadrillas as $cuadrilla)
                        <tr class="hover:bg-gray-50 transition">
                            <td class="px-5 py-3 font-medium text-gray-800">{{ $cuadrilla->cua_nombre }}</td>
                            <td class="px-5 py-3">
                                <span
                                    class="inline-flex items-center gap-2 px-3 py-1 rounded-full text-xs font-medium
                                    {{ $cuadrilla->cua_ciudad == 1 ? 'bg-blue-100 text-blue-800' : 'bg-green-100 text-green-800' }}">
                                    <i class="fas fa-map-marker-alt text-xs"></i>
                                    {{ $cuadrilla->cua_ciudad == 1 ? 'Guayaquil' : 'Quito' }}
                                </span>
                            </td>
                            <td class="px-5 py-3">
                                <span
                                    class="inline-flex items-center gap-2 px-3 py-1 rounded-full text-xs font-medium
                                    {{ $cuadrilla->estado == 1 ? 'bg-emerald-100 text-emerald-700' : 'bg-red-100 text-red-700' }}">
                                    <i class="fas fa-check-circle text-xs"></i>
                                    {{ $cuadrilla->estado == 1 ? 'Activa' : 'Inactiva' }}
                                </span>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="3" class="px-5 py-4 text-center text-gray-400">No hay cuadrillas
                                registradas.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Vista móvil -->
        {{-- <div class="md:hidden divide-y divide-gray-100">
            @forelse ($cuadrillas as $cuadrilla)
                <div class="p-4 flex flex-col gap-2 hover:bg-gray-50 transition">
                    <div>
                        <span class="block text-xs text-gray-500">Nombre</span>
                        <span class="font-medium text-gray-800">{{ $cuadrilla->cua_nombre }}</span>
                    </div>
                    <div>
                        <span class="block text-xs text-gray-500">Ciudad</span>
                        <span
                            class="inline-flex items-center gap-2 px-3 py-1 rounded-full text-xs font-medium
                            {{ $cuadrilla->cua_ciudad == 1 ? 'bg-blue-100 text-blue-800' : 'bg-green-100 text-green-800' }}">
                            <i class="fas fa-map-marker-alt text-xs"></i>
                            {{ $cuadrilla->cua_ciudad == 1 ? 'Guayaquil' : 'Quito' }}
                        </span>
                    </div>
                    <div>
                        <span class="block text-xs text-gray-500">Estado</span>
                        <span
                            class="inline-flex items-center gap-2 px-3 py-1 rounded-full text-xs font-medium
                            {{ $cuadrilla->estado == 1 ? 'bg-emerald-100 text-emerald-700' : 'bg-red-100 text-red-700' }}">
                            <i class="fas fa-check-circle text-xs"></i>
                            {{ $cuadrilla->estado == 1 ? 'Activa' : 'Inactiva' }}
                        </span>
                    </div>
                </div>
            @empty
                <div class="p-4 text-center text-gray-400">
                    No hay cuadrillas registradas.
                </div>
            @endforelse
        </div> --}}
        <div class="md:hidden divide-y divide-gray-100 text-[11px] leading-tight">
            @forelse ($cuadrillas as $cuadrilla)
                <div class="p-2 flex flex-col gap-1 hover:bg-gray-50 transition">
                    <div>
                        <span class="block text-[10px] text-gray-500 uppercase tracking-wide">Nombre</span>
                        <span class="font-medium text-gray-800 truncate">{{ $cuadrilla->cua_nombre }}</span>
                    </div>

                    <div>
                        <span class="block text-[10px] text-gray-500 uppercase tracking-wide">Ciudad</span>
                        <span
                            class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full text-[10px] font-medium
                    {{ $cuadrilla->cua_ciudad == 1 ? 'bg-blue-100 text-blue-700' : 'bg-green-100 text-green-700' }}">
                            <i class="fas fa-map-marker-alt text-[9px]"></i>
                            {{ $cuadrilla->cua_ciudad == 1 ? 'Guayaquil' : 'Quito' }}
                        </span>
                    </div>

                    <div>
                        <span class="block text-[10px] text-gray-500 uppercase tracking-wide">Estado</span>
                        <span
                            class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full text-[10px] font-medium
                    {{ $cuadrilla->estado == 1 ? 'bg-emerald-100 text-emerald-700' : 'bg-red-100 text-red-700' }}">
                            <i class="fas fa-check-circle text-[9px]"></i>
                            {{ $cuadrilla->estado == 1 ? 'Activa' : 'Inactiva' }}
                        </span>
                    </div>
                </div>
            @empty
                <div class="p-3 text-center text-gray-400 text-[11px]">
                    No hay cuadrillas registradas.
                </div>
            @endforelse
        </div>

    </div>
</div>

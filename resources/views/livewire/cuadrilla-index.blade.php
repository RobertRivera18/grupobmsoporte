<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    
    <!-- Header y Botón de Acción Principal -->
    <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4 mb-6">
        <div>
            <h1 class="text-2xl font-bold text-gray-800">Gestión de Cuadrillas</h1>
            <p class="text-sm text-gray-500">Administra, filtra y controla el estado de las cuadrillas operativas.</p>
        </div>
        <a href="{{ route('admin.cuadrillas.create') }}"
            class="inline-flex items-center justify-center bg-red-600 hover:bg-red-700 text-white text-sm font-medium px-4 py-2.5 rounded-xl shadow-sm transition duration-150 ease-in-out">
            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
            Crear nueva Cuadrilla
        </a>
    </div>

    <!-- Panel de Filtros UI/UX Moderno -->
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-4 sm:p-5 mb-6">
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-3 items-center">
            
            <!-- Buscador -->
            <div class="relative">
                <span class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-gray-400">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                </span>
                <input wire:model.live.debounce.300ms="search" type="text"
                    class="w-full pl-9 pr-4 text-sm bg-gray-50 border border-gray-200 rounded-xl focus:bg-white focus:border-red-500 focus:ring-1 focus:ring-red-500 transition"
                    placeholder="Buscar cuadrilla...">
            </div>


            <!-- Filtro Estado -->
            <div>
                <select wire:model.live="estado" class="w-full text-sm bg-gray-50 border border-gray-200 rounded-xl focus:bg-white focus:border-red-500 focus:ring-1 focus:ring-red-500 transition">
                    <option value="">Todos los Estados</option>
                    <option value="0">En Revisión</option>
                    <option value="1">Publicada</option>
                </select>
            </div>

            <!-- Filtro Ciudad (Solo visible si no es operador restringido o para Admin) -->
            @if(!auth()->user()->hasRole('operador1') && !auth()->user()->hasRole('operador2'))
            <div>
                <select wire:model.live="ciudad" class="w-full text-sm bg-gray-50 border border-gray-200 rounded-xl focus:bg-white focus:border-red-500 focus:ring-1 focus:ring-red-500 transition">
                    <option value="">Todas las Ciudades</option>
                    <option value="1">Guayaquil</option>
                    <option value="2">Quito</option>
                </select>
            </div>
            @endif
        </div>

        <!-- Botón Limpiar Filtros (Aparece si hay filtros activos) -->
        @if($search || $empresa !== '' || $estado !== '' || $ciudad !== '')
            <div class="mt-3 flex justify-end">
                <button wire:click="limpiarFiltros" class="inline-flex items-center text-xs font-medium text-gray-500 hover:text-red-600 transition">
                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                    Limpiar filtros
                </button>
            </div>
        @endif
    </div>

    @if ($cuadrillas->count() > 0)
        <!-- Vista Tarjetas Móviles (Responsive Cards) -->
        <div class="block md:hidden space-y-4">
            @foreach ($cuadrillas as $cuadrilla)
                <div class="bg-white shadow-sm border border-gray-100 rounded-2xl p-5 space-y-3 transition hover:shadow-md">
                    <div class="flex justify-between items-start">
                        <div>
                            <h3 class="text-base font-semibold text-gray-900">{{ $cuadrilla->cua_nombre }}</h3>
                            <span class="inline-block mt-1 text-xs text-gray-500 font-medium">
                                @switch($cuadrilla->cua_ciudad)
                                    @case(1) Guayaquil @break
                                    @case(2) Quito @break
                                @endswitch
                            </span>
                        </div>
                        <div>
                            @if ($cuadrilla->estado == 0)
                                <span class="bg-amber-50 text-amber-700 border border-amber-200 text-xs font-semibold px-2.5 py-1 rounded-full">En Revisión</span>
                            @else
                                <span class="bg-emerald-50 text-emerald-700 border border-emerald-200 text-xs font-semibold px-2.5 py-1 rounded-full">Publicada</span>
                            @endif
                        </div>
                    </div>

                    <div class="flex items-center text-sm text-gray-600">
                        <span class="font-medium mr-2">Empresa:</span>
                        @switch($cuadrilla->cua_empresa)
                            @case(1) <span class="bg-red-50 text-red-700 font-medium px-2 py-0.5 rounded-md text-xs">Claro</span> @break
                            @case(2) <span class="bg-blue-50 text-blue-700 font-medium px-2 py-0.5 rounded-md text-xs">CNEL</span> @break
                        @endswitch
                    </div>

                    <div class="text-sm bg-gray-50 p-3 rounded-xl">
                        <span class="font-medium text-gray-700 block mb-1">Colaboradores:</span>
                        @if ($cuadrilla->users->isNotEmpty())
                            <ul class="list-disc ml-4 text-xs text-gray-600 space-y-1">
                                @foreach ($cuadrilla->users as $user)
                                    <li>{{ $user->name }}</li>
                                @endforeach
                            </ul>
                        @else
                            <span class="text-gray-400 text-xs italic">Sin colaboradores asignados</span>
                        @endif
                    </div>

                    @role('Admin')
                        <div class="flex justify-between items-center pt-3 border-t border-gray-100">
                            <div class="flex items-center space-x-3 text-sm">
                                <a href="{{ route('admin.cuadrillas.edit', $cuadrilla) }}" class="text-indigo-600 hover:text-indigo-900 font-medium">Editar</a>
                                <form action="{{ route('admin.cuadrillas.destroy', $cuadrilla) }}" method="POST" class="formularioEliminar inline">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="text-red-600 hover:text-red-900 font-medium">Eliminar</button>
                                </form>
                            </div>

                            <label class="relative inline-flex items-center cursor-pointer">
                                <input type="checkbox" class="sr-only peer"
                                    wire:change="actualizarEstado({{ $cuadrilla->id }}, $event.target.checked)"
                                    @checked($cuadrilla->estado == 1)>
                                <div class="w-9 h-5 bg-gray-200 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-4 after:w-4 after:transition-all peer-checked:bg-red-600"></div>
                            </label>
                        </div>
                    @endrole
                </div>
            @endforeach
        </div>

        <!-- Tabla para Escritorio (Desktop Table) -->
        <div class="hidden md:block bg-white shadow-sm border border-gray-100 rounded-2xl overflow-hidden">
            <table class="min-w-full divide-y divide-gray-200 text-sm">
                <thead class="bg-gray-50 text-xs font-semibold text-gray-500 uppercase tracking-wider">
                    <tr>
                        <th class="px-6 py-3.5 text-left">Nombre / Ciudad</th>
                        <th class="px-6 py-3.5 text-left">Empresa</th>
                        <th class="px-6 py-3.5 text-left">Colaboradores</th>
                        <th class="px-6 py-3.5 text-left">Estado</th>
                        @role('Admin')
                            <th class="px-6 py-3.5 text-right">Acciones</th>
                        @endrole
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100 bg-white">
                    @foreach ($cuadrillas as $cuadrilla)
                        <tr class="hover:bg-gray-50/50 transition">
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="font-semibold text-gray-900">{{ $cuadrilla->cua_nombre }}</div>
                                <div class="text-xs text-gray-500 mt-0.5">
                                    @switch($cuadrilla->cua_ciudad)
                                        @case(1) Guayaquil @break
                                        @case(2) Quito @break
                                    @endswitch
                                </div>
                            </td>

                            <td class="px-6 py-4 whitespace-nowrap">
                                @switch($cuadrilla->cua_empresa)
                                    @case(1) <span class="bg-red-50 text-red-700 font-medium px-2.5 py-1 rounded-md text-xs">Claro</span> @break
                                    @case(2) <span class="bg-blue-50 text-blue-700 font-medium px-2.5 py-1 rounded-md text-xs">CNEL</span> @break
                                @endswitch
                            </td>

                            <td class="px-6 py-4">
                                @if ($cuadrilla->users->isNotEmpty())
                                    <ul class="list-disc ml-4 text-xs text-gray-600 space-y-0.5 max-w-xs">
                                        @foreach ($cuadrilla->users as $user)
                                            <li>{{ $user->name }}</li>
                                        @endforeach
                                    </ul>
                                @else
                                    <span class="text-gray-400 text-xs italic">Sin asignar</span>
                                @endif
                            </td>

                            <td class="px-6 py-4 whitespace-nowrap">
                                @if ($cuadrilla->estado == 0)
                                    <span class="bg-amber-50 text-amber-700 border border-amber-200 text-xs font-semibold px-2.5 py-1 rounded-full">En Revisión</span>
                                @else
                                    <span class="bg-emerald-50 text-emerald-700 border border-emerald-200 text-xs font-semibold px-2.5 py-1 rounded-full">Publicada</span>
                                @endif
                            </td>

                            @role('Admin')
                                <td class="px-6 py-4 whitespace-nowrap text-right space-x-3">
                                    <div class="inline-flex items-center space-x-3">
                                        <a href="{{ route('admin.cuadrillas.edit', $cuadrilla) }}" class="text-indigo-600 hover:text-indigo-900 font-medium text-xs bg-indigo-50 px-3 py-1.5 rounded-lg transition">Editar</a>
                                        
                                        <form action="{{ route('admin.cuadrillas.destroy', $cuadrilla) }}" method="POST" class="formularioEliminar inline">
                                            @csrf @method('DELETE')
                                            <button type="submit" class="text-red-600 hover:text-red-900 font-medium text-xs bg-red-50 px-3 py-1.5 rounded-lg transition">Eliminar</button>
                                        </form>

                                        <label class="relative inline-flex items-center cursor-pointer">
                                            <input type="checkbox" class="sr-only peer"
                                                wire:change="actualizarEstado({{ $cuadrilla->id }}, $event.target.checked)"
                                                @checked($cuadrilla->estado == 1)>
                                            <div class="w-9 h-5 bg-gray-200 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-4 after:w-4 after:transition-all peer-checked:bg-red-600"></div>
                                        </label>
                                    </div>
                                </td>
                            @endrole
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <!-- Paginación -->
        <div class="mt-6">
            {{ $cuadrillas->links() }}
        </div>
    @else
        <!-- Estado Vacío (Empty State) Profesional -->
        <div class="bg-white rounded-2xl border border-gray-100 p-12 text-center shadow-sm">
            <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
            <h3 class="mt-2 text-sm font-semibold text-gray-900">No hay registros</h3>
            <p class="mt-1 text-sm text-gray-500">Ninguna cuadrilla coincide con los criterios de búsqueda o filtros seleccionados.</p>
        </div>
    @endif
</div>

@push('js')
    <script>
        document.querySelectorAll('.formularioEliminar').forEach(form => {
            form.addEventListener('submit', function(e) {
                e.preventDefault();
                Swal.fire({
                    title: '¿Eliminar Cuadrilla?',
                    html: `<div style="font-size: 14px; color: #4b5563;">
                        <strong>¡Esta acción no se puede deshacer!</strong><br>
                        La cuadrilla será eliminada de forma permanente.
                    </div>`,
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#dc2626',
                    cancelButtonColor: '#6b7280',
                    confirmButtonText: 'Sí, eliminar',
                    cancelButtonText: 'Cancelar',
                    customClass: {
                        popup: 'rounded-2xl px-6 pt-6 pb-4',
                        confirmButton: 'px-4 py-2 text-sm font-medium rounded-xl',
                        cancelButton: 'px-4 py-2 text-sm font-medium rounded-xl'
                    },
                    buttonsStyling: true,
                    reverseButtons: true
                }).then((result) => {
                    if (result.isConfirmed) {
                        this.submit();
                    }
                });
            });
        });
    </script>
@endpush
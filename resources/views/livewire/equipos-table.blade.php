<div>
    {{-- ===================== BARRA DE ACCIONES ===================== --}}
    <div class="flex flex-wrap items-center justify-between gap-3 px-6 pt-4">
        <span wire:click='generarExcelEquipos()' wire:loading.attr="disabled" wire:target="generarExcelEquipos"
            class="inline-flex items-center gap-2 px-3 py-1.5 rounded-lg bg-green-100 text-green-800 hover:bg-green-200 transition-colors cursor-pointer text-sm font-medium disabled:opacity-50 disabled:cursor-not-allowed">
            <span wire:loading.remove wire:target="generarExcelEquipos" class="inline-flex items-center gap-2">
                <i class="fas fa-file-excel text-green-600"></i>
                <p>Generar Excel</p>
            </span>
            <span wire:loading wire:target="generarExcelEquipos" class="inline-flex items-center gap-2">
                <svg class="animate-spin h-4 w-4 text-green-700" xmlns="http://www.w3.org/2000/svg" fill="none"
                    viewBox="0 0 24 24">
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4" />
                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v4a4 4 0 00-4 4H4z" />
                </svg>
                Generando...
            </span>
        </span>

        @if ($search || $filtroCiudad !== '' || $filtroEstado !== '' || $filtroMarca !== '')
            <button wire:click="limpiarFiltros"
                class="text-xs font-medium text-gray-500 hover:text-red-600 inline-flex items-center gap-1">
                <i class="fas fa-times"></i> Limpiar filtros
            </button>
        @endif
    </div>

    {{-- ===================== FILTROS ===================== --}}
    <div class="px-6 py-4 grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-5 gap-3">
        <input wire:model.live.debounce.500ms="search"
            class="text-sm form-input rounded-full shadow-sm lg:col-span-2"
            placeholder="Buscar por usuario, equipo, marca, modelo o serie">

        <select wire:model.live="filtroCiudad" class="text-sm form-select rounded-full shadow-sm">
            <option value="">Todas las ciudades</option>
            <option value="1">Guayaquil</option>
            <option value="2">Quito</option>
        </select>

        <select wire:model.live="filtroEstado" class="text-sm form-select rounded-full shadow-sm">
            <option value="">Todos los estados</option>
            <option value="libre">Equipo Libre</option>
            <option value="asignado">Asignado</option>
            <option value="defecto">Con defecto</option>
        </select>

        <select wire:model.live="filtroMarca" class="text-sm form-select rounded-full shadow-sm">
            <option value="">Todas las marcas</option>
            @foreach ($marcas as $marca)
                <option value="{{ $marca }}">{{ $marca }}</option>
            @endforeach
        </select>
    </div>

    {{-- ===================== CHIPS DE FILTROS ACTIVOS ===================== --}}
    @if ($search || $filtroCiudad !== '' || $filtroEstado !== '' || $filtroMarca !== '')
        <div class="px-6 pb-2 flex flex-wrap gap-2">
            @if ($search)
                <span class="text-xs bg-gray-100 text-gray-700 px-2.5 py-1 rounded-full">
                    Búsqueda: "{{ $search }}"
                </span>
            @endif
            @if ($filtroCiudad !== '')
                <span class="text-xs bg-gray-100 text-gray-700 px-2.5 py-1 rounded-full">
                    Ciudad: {{ $filtroCiudad == 1 ? 'Guayaquil' : 'Quito' }}
                </span>
            @endif
            @if ($filtroEstado !== '')
                <span class="text-xs bg-gray-100 text-gray-700 px-2.5 py-1 rounded-full">
                    Estado: {{ ucfirst($filtroEstado) }}
                </span>
            @endif
            @if ($filtroMarca !== '')
                <span class="text-xs bg-gray-100 text-gray-700 px-2.5 py-1 rounded-full">
                    Marca: {{ $filtroMarca }}
                </span>
            @endif
        </div>
    @endif

    {{-- ===================== INDICADOR DE CARGA ===================== --}}
    <div wire:loading.flex wire:target="search,filtroCiudad,filtroEstado,filtroMarca,sortBy,perPage"
        class="px-6 py-2 text-sm text-blue-600 items-center gap-2 hidden">
        <svg class="animate-spin h-4 w-4 text-blue-600" xmlns="http://www.w3.org/2000/svg" fill="none"
            viewBox="0 0 24 24">
            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4" />
            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v4a4 4 0 00-4 4H4z" />
        </svg>
        Actualizando resultados...
    </div>

    {{-- ===================== TABLA ===================== --}}
    <div class="relative overflow-x-auto">
        <table id="search-table" class="w-full text-sm text-left rtl:text-right text-gray-500 dark:text-gray-400">
            <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                <tr>
                    @php
                        $columnas = [
                            'nombre' => 'Nombre',
                            'marca' => 'Marca',
                            'modelo' => 'Modelo',
                            'serie' => 'Serie',
                        ];
                    @endphp

                    @foreach ($columnas as $campo => $etiqueta)
                        <th scope="col" class="px-6 py-3 cursor-pointer select-none hover:text-gray-900"
                            wire:click="sortBy('{{ $campo }}')">
                            <span class="inline-flex items-center gap-1">
                                {{ $etiqueta }}
                                @if ($sortField === $campo)
                                    <i
                                        class="fas fa-sort-{{ $sortDirection === 'asc' ? 'up' : 'down' }} text-blue-500"></i>
                                @else
                                    <i class="fas fa-sort text-gray-300"></i>
                                @endif
                            </span>
                        </th>
                    @endforeach

                    <th scope="col" class="px-6 py-3">Asignado</th>
                    <th scope="col" class="px-6 py-3">Ciudad</th>
                    <th scope="col" class="px-6 py-3">Observaciones</th>
                    <th scope="col" class="px-6 py-3">
                        <span class="sr-only">Acciones</span>
                    </th>
                    <th scope="col" class="px-6 py-3">
                        <span class="sr-only">Historial</span>
                    </th>
                </tr>
            </thead>
            <tbody>
                @forelse ($equipos as $equipo)
                    <tr wire:key="equipo-{{ $equipo->id }}"
                        class="bg-white border-b dark:bg-gray-800 dark:border-gray-700 border-gray-200 hover:bg-gray-50 dark:hover:bg-gray-700/50">
                        <td class="px-6 py-4 font-medium text-gray-900 dark:text-white">
                            {{ $equipo->nombre }}
                        </td>
                        <td class="px-6 py-4">
                            {{ $equipo->marca }}
                        </td>
                        <td class="px-6 py-4">
                            {{ $equipo->modelo }}
                        </td>
                        <td class="px-6 py-4">
                            {{ $equipo->serie }}
                        </td>
                        <td class="px-6 py-4">
                            @if ($equipo->users->isNotEmpty())
                                <span
                                    class="bg-blue-100 text-blue-800 text-xs font-medium me-2 px-2.5 py-0.5 rounded-sm dark:bg-blue-900 dark:text-blue-300">
                                    {{ $equipo->users->first()->name }}
                                </span>
                            @elseif ($equipo->cuadrilla->isNotEmpty())
                                <span
                                    class="bg-purple-100 text-purple-800 text-xs font-medium me-2 px-2.5 py-0.5 rounded-sm dark:bg-purple-900 dark:text-purple-300">
                                    {{ $equipo->cuadrilla->first()->cua_nombre }}
                                </span>
                            @else
                                @if ($equipo->estado === 1)
                                    <span
                                        class="bg-green-100 text-green-800 text-xs font-medium me-2 px-2.5 py-0.5 rounded-sm dark:bg-green-900 dark:text-green-300">
                                        Equipo Libre
                                    </span>
                                @elseif ($equipo->estado === 0)
                                    <span
                                        class="bg-red-100 text-red-800 text-xs font-medium me-2 px-2.5 py-0.5 rounded-sm dark:bg-red-900 dark:text-red-300">
                                        Equipo con defecto
                                    </span>
                                @else
                                    <span
                                        class="bg-yellow-100 text-yellow-800 text-xs font-medium me-2 px-2.5 py-0.5 rounded-sm dark:bg-yellow-900 dark:text-yellow-300">
                                        Estado desconocido
                                    </span>
                                @endif
                            @endif
                        </td>
                        <td class="px-6 py-4">
                            @if ($equipo->ciudad == 1)
                                <span
                                    class="bg-yellow-100 text-yellow-800 text-xs font-medium px-2.5 py-0.5 rounded-sm dark:bg-yellow-900 dark:text-yellow-300">
                                    Guayaquil
                                </span>
                            @elseif ($equipo->ciudad == 2)
                                <span
                                    class="bg-purple-100 text-purple-800 text-xs font-medium px-2.5 py-0.5 rounded-sm dark:bg-purple-900 dark:text-purple-300">
                                    Quito
                                </span>
                            @else
                                <span>-</span>
                            @endif
                        </td>
                        <td class="px-6 py-4 text-center max-w-[200px] truncate" title="{{ $equipo->observacion }}">
                            {{ $equipo->observacion ?: '-' }}
                        </td>
                        <td class="px-6 py-4">
                            <div class="flex items-center gap-4">
                                <a class="text-red-500 font-semibold hover:underline"
                                    href="{{ route('admin.equipos.edit', $equipo) }}">Editar</a>

                                <button type="button" wire:click="$dispatch('openModal', { id: {{ $equipo->id }} })"
                                    aria-label="Eliminar equipo {{ $equipo->nombre }}">
                                    <i class="fas fa-trash text-lg text-red-500"></i>
                                </button>

                                <label class="inline-flex items-center cursor-pointer">
                                    <input type="checkbox" class="sr-only peer"
                                        wire:change="actualizarEstado({{ $equipo->id }}, $event.target.checked)"
                                        @checked($equipo->estado == 1) @disabled($equipo->users->isNotEmpty() || $equipo->cuadrilla->isNotEmpty())
                                        aria-label="Activar o desactivar equipo {{ $equipo->nombre }}">

                                    <div
                                        class="relative w-11 h-6 sm:w-10 sm:h-5 md:w-11 md:h-6 bg-gray-200 rounded-full peer peer-checked:bg-blue-600 dark:bg-gray-700 dark:peer-checked:bg-blue-600 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-blue-300 dark:peer-focus:ring-blue-800 after:content-[''] after:absolute after:top-[2px] after:start-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 sm:after:h-4 sm:after:w-4 md:after:h-5 md:after:w-5 after:transition-all peer-checked:after:translate-x-full rtl:peer-checked:after:-translate-x-full peer-checked:after:border-white dark:border-gray-600
                                        {{ $equipo->users->isNotEmpty() || $equipo->cuadrilla->isNotEmpty() ? 'opacity-50 cursor-not-allowed' : '' }}">
                                    </div>

                                    <span class="ms-3 text-xs sm:text-sm md:text-base font-medium text-gray-900 dark:text-gray-300">
                                        Activo
                                    </span>
                                </label>
                            </div>
                        </td>
                        <td class="px-6 py-4">
                            <a href="{{ route('admin.historial', $equipo) }}" aria-label="Ver historial de {{ $equipo->nombre }}">
                                <i class="fas fa-history"></i>
                            </a>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="9" class="px-6 py-10 text-center text-gray-500">
                            <i class="fas fa-box-open text-2xl mb-2 block"></i>
                            No se encontraron equipos con los filtros aplicados.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    {{-- ===================== PAGINACIÓN + PER PAGE ===================== --}}
    <div class="mt-4 px-6 flex flex-col sm:flex-row items-center justify-between gap-3">
        <div class="flex items-center gap-2 text-sm text-gray-600">
            <span>Mostrar</span>
            <select wire:model.live="perPage" class="text-sm form-select rounded-md">
                <option value="20">20</option>
                <option value="40">40</option>
                <option value="80">80</option>
                <option value="150">150</option>
            </select>
            <span>registros</span>
        </div>

        <div>
            {{ $equipos->links() }}
        </div>
    </div>

    <x-dialog-modal wire:model="openModal">
        <x-slot name="title">
            Motivo de Desactivación
        </x-slot>

        <x-slot name="content">
            <textarea wire:model.defer="observacion" rows="4"
                class="w-full p-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring focus:border-blue-300"
                placeholder="Describe el motivo por el cual estás desactivando este equipo..."></textarea>

            @error('observacion')
                <span class="text-red-500 text-sm">{{ $message }}</span>
            @enderror
        </x-slot>

        <x-slot name="footer">
            <x-secondary-button wire:click="$set('openModal', false)" class="mr-2">
                Cancelar
            </x-secondary-button>

            <x-danger-button wire:click="confirmarDesactivacion">
                Guardar Motivo
            </x-danger-button>
        </x-slot>
    </x-dialog-modal>
</div>

@push('js')
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        document.addEventListener('livewire:init', () => {
            Livewire.on('openModal', ({ id }) => {
                Swal.fire({
                    title: "¿Estás seguro?",
                    text: "¡No podrás revertir esto!",
                    icon: "warning",
                    showCancelButton: true,
                    confirmButtonColor: "#3085d6",
                    cancelButtonColor: "#d33",
                    confirmButtonText: "Sí, eliminar"
                }).then((result) => {
                    if (result.isConfirmed) {
                        Livewire.dispatch('delete', { equipoId: id });
                    }
                });
            });

            Livewire.on('swal:success', (data) => {
                const payload = Array.isArray(data) ? data[0] : data;
                Swal.fire({
                    icon: payload?.icon || 'success',
                    title: payload?.title || '¡Bien hecho!',
                    text: payload?.text || 'Operación realizada con éxito',
                    position: 'top-end',
                    toast: true,
                    timer: 3000,
                    showConfirmButton: false
                });
            });

            Livewire.on('swal', (data) => {
                const payload = Array.isArray(data) ? data[0] : data;
                Swal.fire({
                    icon: payload?.icon || 'info',
                    title: payload?.title || '',
                    text: payload?.text || ''
                });
            });
        });
    </script>
    <script>
        Livewire.on('descargarArchivo', ({ url }) => {
            window.open(url, '_blank');
        });
    </script>
@endpush
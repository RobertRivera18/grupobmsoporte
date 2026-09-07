<div>
    <div class="max-w-9xl mx-auto px-2 sm:px-4 lg:px-8 py-4 sm:py-8">

        <div class="flex items-center justify-end px-2 sm:px-4 py-2 mb-4">
            <button wire:click="generarExcel"
                class="flex items-center gap-2 bg-green-100 hover:bg-green-200 text-green-800 font-medium py-2 px-3 sm:px-4 rounded-lg shadow-sm transition duration-200 text-sm sm:text-base w-full sm:w-auto justify-center">
                <i class="fas fa-file-excel text-lg sm:text-2xl"></i>
                <span>Reporte de Recargas</span>
            </button>
        </div>

        <!-- Barra de búsqueda -->
        <div class="px-2 sm:px-6 py-3 sm:py-4 flex">
            <input wire:keydown="limpiar_page" wire:model.live.debounce.500ms="search"
                class="text-sm form-input flex-1 shadow-sm rounded-full"
                placeholder="Buscar cuadrilla, colaborador o serie...">
        </div>

        @if ($cuadrillas->count() > 0)
            <!-- Vista Desktop (pantallas grandes) -->
            <div class="hidden lg:block overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200" id="tabla-usuarios">
                    <thead class="bg-gray-50">
                        <tr>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">
                                Nombre Cuadrilla</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">
                                Integrantes</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">
                                Equipos</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">
                                Opciones</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @foreach ($cuadrillas as $cuadrilla)
                            <tr>
                                <td class="py-4 px-6 whitespace-nowrap">
                                    <div class="flex items-center">
                                        <div class="ml-4">
                                            <div class="text-sm font-medium text-gray-900">
                                                {{ $cuadrilla->cua_nombre }}
                                            </div>
                                            <div class="text-sm font-basic text-gray-900">
                                                @if ($cuadrilla->cua_ciudad == 1)
                                                    Guayaquil
                                                @elseif ($cuadrilla->cua_ciudad == 2)
                                                    Quito
                                                @else
                                                    Sin ciudad
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                </td>

                                <td class="py-4 px-6 whitespace-nowrap">
                                    <div class="flex flex-col gap-1">
                                        @if ($cuadrilla->users->isNotEmpty())
                                            @foreach ($cuadrilla->users as $user)
                                                <div class="flex items-center gap-2"
                                                    wire:key="colaborador-{{ $cuadrilla->id }}-{{ $user->id }}">
                                                    <span class="text-xs text-gray-700">
                                                        {{ $user->name }}
                                                    </span>
                                                    @role('Admin')
                                                        <button
                                                            wire:click="eliminarColaborador({{ $user->id }}, {{ $cuadrilla->id }})"
                                                            wire:confirm="¿Estás seguro de eliminar este colaborador?"
                                                            class="text-red-500 text-xs hover:underline"
                                                            title="Eliminar colaborador">
                                                            ❌
                                                        </button>
                                                    @endrole
                                                </div>
                                            @endforeach
                                        @else
                                            <span class="text-gray-400 text-sm">Sin Integrantes Aun</span>
                                        @endif

                                        @role('Admin')
                                            <button wire:click="agregarColaborador({{ $cuadrilla->id }})"
                                                class="mt-1 text-indigo-600 text-xs hover:underline">+ Agregar</button>
                                        @endrole
                                    </div>
                                </td>

                                <td class="py-4 px-6 whitespace-nowrap">
                                    <div class="flex flex-col gap-1">
                                        @if ($cuadrilla->equipos->isNotEmpty())
                                            @foreach ($cuadrilla->equipos as $equipo)
                                                <div class="flex items-center gap-2"
                                                    wire:key="equipo-{{ $cuadrilla->id }}-{{ $equipo->id }}">
                                                    <span class="text-xs text-gray-700">
                                                        {{ $equipo->nombre }} - {{ $equipo->marca }} -
                                                        {{ $equipo->serie }}
                                                    </span>
                                                    @role('Admin')
                                                        <button
                                                            wire:click="eliminarEquipo({{ $cuadrilla->id }}, {{ $equipo->id }})"
                                                            wire:confirm="¿Estás seguro de eliminar este equipo?"
                                                            class="text-red-500 text-xs hover:underline"
                                                            title="Eliminar equipo">
                                                            ❌
                                                        </button>
                                                    @endrole
                                                </div>
                                            @endforeach
                                        @else
                                            <span class="text-gray-400 text-sm">Sin Equipos Asignados</span>
                                        @endif

                                        @role('Admin')
                                            <button wire:click="agregarEquipo({{ $cuadrilla->id }})"
                                                class="mt-1 text-indigo-600 text-xs hover:underline">+ Agregar</button>
                                        @endrole
                                    </div>
                                </td>

                                <td>
                                    <div class="flex gap-4 flex-wrap items-center">
                                        @role('Admin')
                                            <i class="text-xl fa fa-image cursor-pointer text-red-500"
                                                wire:click="uploadFile({{ $cuadrilla->id }})"
                                                title="Subir comprobante"></i>

                                            <i class="fas fa-list-alt text-xl cursor-pointer text-blue-500 hover:text-blue-700"
                                                wire:click="abrirModalOpciones({{ $cuadrilla->id }})" title="Opciones"></i>

                                            <i class="text-xl fa fa-download cursor-pointer text-blue-500 hover:text-blue-700"
                                                wire:click="descargarArchivo({{ $cuadrilla->id }})"
                                                title="Descargar comprobante"></i>

                                            @if ($cuadrilla->equipos->isNotEmpty())
                                                <i class="text-xl fas fa-sticky-note cursor-pointer text-yellow-500 hover:text-yellow-700"
                                                    wire:click="actaDescargo({{ $cuadrilla->id }})"
                                                    title="Generar acta de descargo"></i>
                                            @endif
                                        @endrole

                                        <div>
                                            @include('livewire.partials.recarga-toggle', [
                                                'cuadrilla' => $cuadrilla,
                                                'label' => 'Recarga Realizada',
                                            ])
                                        </div>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <!-- Vista Móvil y Tablet (tarjetas) -->
            <div class="lg:hidden space-y-4 px-2">
                @foreach ($cuadrillas as $cuadrilla)
                    <div class="bg-white rounded-lg shadow-md border border-gray-200 overflow-hidden"
                        wire:key="card-{{ $cuadrilla->id }}">

                        <!-- Header de la tarjeta -->
                        <div class="bg-indigo-600 px-4 py-3">
                            <h3 class="text-white font-semibold text-base">{{ $cuadrilla->cua_nombre }}</h3>
                            <p class="text-indigo-100 text-xs mt-1">
                                @if ($cuadrilla->cua_ciudad == 1)
                                    📍 Guayaquil
                                @elseif ($cuadrilla->cua_ciudad == 2)
                                    📍 Quito
                                @else
                                    📍 Sin ciudad
                                @endif
                            </p>
                        </div>

                        <!-- Contenido -->
                        <div class="p-4 space-y-4">
                            <!-- Integrantes -->
                            <div>
                                <h4 class="text-sm font-semibold text-gray-700 mb-2 flex items-center">
                                    <i class="fas fa-users mr-2 text-indigo-500"></i>
                                    Integrantes
                                </h4>
                                <div class="space-y-2 ml-6">
                                    @if ($cuadrilla->users->isNotEmpty())
                                        @foreach ($cuadrilla->users as $user)
                                            <div class="flex items-center justify-between bg-gray-50 rounded p-2"
                                                wire:key="mobile-colaborador-{{ $cuadrilla->id }}-{{ $user->id }}">
                                                <span class="text-sm text-gray-700">{{ $user->name }}</span>
                                                @role('Admin')
                                                    <button
                                                        wire:click="eliminarColaborador({{ $user->id }}, {{ $cuadrilla->id }})"
                                                        wire:confirm="¿Estás seguro de eliminar este colaborador?"
                                                        class="text-red-500 hover:text-red-700">
                                                        <i class="fas fa-trash text-xs"></i>
                                                    </button>
                                                @endrole
                                            </div>
                                        @endforeach
                                    @else
                                        <p class="text-sm text-gray-400 italic">Sin integrantes</p>
                                    @endif

                                    @role('Admin')
                                        <button wire:click="agregarColaborador({{ $cuadrilla->id }})"
                                            class="w-full text-indigo-600 text-sm hover:bg-indigo-50 py-2 rounded transition border border-indigo-200">
                                            + Agregar colaborador
                                        </button>
                                    @endrole
                                </div>
                            </div>

                            <!-- Equipos -->
                            <div>
                                <h4 class="text-sm font-semibold text-gray-700 mb-2 flex items-center">
                                    <i class="fas fa-laptop mr-2 text-purple-500"></i>
                                    Equipos
                                </h4>
                                <div class="space-y-2 ml-6">
                                    @if ($cuadrilla->equipos->isNotEmpty())
                                        @foreach ($cuadrilla->equipos as $equipo)
                                            <div class="flex items-start justify-between bg-gray-50 rounded p-2"
                                                wire:key="mobile-equipo-{{ $cuadrilla->id }}-{{ $equipo->id }}">
                                                <div class="flex-1">
                                                    <p class="text-sm text-gray-700 font-medium">{{ $equipo->nombre }}
                                                    </p>
                                                    <p class="text-xs text-gray-500">{{ $equipo->marca }} -
                                                        {{ $equipo->serie }}</p>
                                                </div>
                                                @role('Admin')
                                                    <button
                                                        wire:click="eliminarEquipo({{ $cuadrilla->id }}, {{ $equipo->id }})"
                                                        wire:confirm="¿Estás seguro de eliminar este equipo?"
                                                        class="text-red-500 hover:text-red-700 ml-2">
                                                        <i class="fas fa-trash text-xs"></i>
                                                    </button>
                                                @endrole
                                            </div>
                                        @endforeach
                                    @else
                                        <p class="text-sm text-gray-400 italic">Sin equipos</p>
                                    @endif

                                    @role('Admin')
                                        <button wire:click="agregarEquipo({{ $cuadrilla->id }})"
                                            class="w-full text-indigo-600 text-sm hover:bg-indigo-50 py-2 rounded transition border border-indigo-200">
                                            + Agregar equipo
                                        </button>
                                    @endrole
                                </div>
                            </div>

                            <!-- Acciones -->
                            <div class="pt-3 border-t border-gray-200">
                                @role('Admin')
                                    <div class="grid grid-cols-4 gap-2 mb-3">

                                        <button wire:click="uploadFile({{ $cuadrilla->id }})"
                                            class="flex flex-col items-center gap-1 text-red-600 hover:text-red-800 p-2">
                                            <i class="fa fa-image text-xl"></i>
                                            <span class="text-xs">Subir</span>
                                        </button>

                                        <button wire:click="descargarArchivo({{ $cuadrilla->id }})"
                                            class="flex flex-col items-center gap-1 text-blue-600 hover:text-blue-800 p-2">
                                            <i class="fa fa-download text-xl"></i>
                                            <span class="text-xs">Bajar</span>
                                        </button>

                                        <button type="button" wire:click="abrirModalOpciones({{ $cuadrilla->id }})"
                                            class="flex flex-col items-center gap-1 text-purple-600 hover:text-purple-800 p-2">
                                            <i class="fas fa-list-alt text-xl"></i>
                                            <span class="text-xs">Opciones</span>
                                        </button>

                                        @if ($cuadrilla->equipos->isNotEmpty())
                                            <button wire:click="actaDescargo({{ $cuadrilla->id }})"
                                                class="flex flex-col items-center gap-1 text-yellow-600 hover:text-yellow-800 p-2">
                                                <i class="fas fa-sticky-note text-xl"></i>
                                                <span class="text-xs">Desc.</span>
                                            </button>
                                        @endif
                                    </div>
                                @endrole

                                <div class="flex justify-center">
                                    @include('livewire.partials.recarga-toggle', [
                                        'cuadrilla' => $cuadrilla,
                                        'label' => 'Recarga',
                                        'labelClass' => 'ms-3 text-sm font-medium text-gray-900',
                                    ])
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        @else
            <div class="px-6 py-12 text-center bg-white rounded-lg shadow">
                <p class="text-gray-500 text-sm">No se encontraron cuadrillas</p>
            </div>
        @endif

        <!-- Paginación -->
        <div class="mt-4 px-2 sm:px-6">
            <div class="flex flex-col sm:flex-row sm:justify-between sm:items-center gap-3">
                <span class="text-xs text-gray-500 text-center sm:text-left">
                    Mostrando {{ $cuadrillas->firstItem() ?? 0 }} a {{ $cuadrillas->lastItem() ?? 0 }} de
                    {{ $cuadrillas->total() }} resultados
                </span>
                <div class="flex justify-center sm:justify-end w-full sm:w-auto">
                    {{ $cuadrillas->links() }}
                </div>
            </div>
        </div>
    </div>

    {{-- Modal para mostrar los colaboradores --}}
    <x-dialog-modal wire:model="open">
        <x-slot name="title">
            <div class="flex justify-between items-center w-full">
                <h3 class="text-base sm:text-lg font-medium">Colaboradores Disponibles</h3>
                <span wire:click="$set('open', false)" class="cursor-pointer text-gray-500 hover:text-gray-800">
                    <i class="fa fa-times"></i>
                </span>
            </div>
        </x-slot>

        <x-slot name="content">
            <!-- Barra de búsqueda -->
            <div class="mb-4 px-2 sm:px-4">
                <div class="relative">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                        <i class="fa fa-search text-gray-400"></i>
                    </div>
                    <input wire:model.live.debounce.500ms="searchColaboradores"
                        class="w-full text-sm border-gray-300 rounded-full pl-10 pr-4 py-2 focus:ring-indigo-500 focus:border-indigo-500"
                        placeholder="Buscar colaborador por nombre o cédula">
                </div>
            </div>

            <!-- Tabla en pantallas medianas y grandes -->
            <div class="hidden sm:block overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200 table-auto text-sm">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-3 py-2 text-left font-semibold text-gray-600">Nombre</th>
                            <th class="px-3 py-2 text-left font-semibold text-gray-600">Cédula</th>
                            <th class="px-3 py-2 w-20"></th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-100">
                        @forelse ($this->colaboradoresDisponibles as $colaborador)
                            <tr class="hover:bg-gray-50" wire:key="modal-colaborador-{{ $colaborador->id }}">
                                <td class="px-3 py-2 text-gray-700">{{ $colaborador->name }}</td>
                                <td class="px-3 py-2 text-gray-700">{{ $colaborador->cedula }}</td>
                                <td class="px-3 py-2 text-right">
                                    <button wire:click="asignarColaborador({{ $colaborador->id }})"
                                        class="text-xs px-3 py-1 bg-indigo-600 text-white rounded hover:bg-indigo-700 transition">
                                        Asignar
                                    </button>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="3" class="px-3 py-4 text-center text-gray-500">
                                    No hay colaboradores disponibles para asignar
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Tarjetas en móviles -->
            <div class="sm:hidden space-y-3">
                @forelse ($this->colaboradoresDisponibles as $colaborador)
                    <div class="bg-white border rounded-lg shadow-sm p-4"
                        wire:key="modal-card-{{ $colaborador->id }}">
                        <div class="flex justify-between items-center">
                            <div class="flex-1 min-w-0 mr-3">
                                <h4 class="font-medium text-gray-800 text-sm truncate">{{ $colaborador->name }}</h4>
                                <p class="text-gray-600 text-xs mt-1">{{ $colaborador->cedula }}</p>
                            </div>
                            <button wire:click="asignarColaborador({{ $colaborador->id }})"
                                class="text-xs px-3 py-1 bg-indigo-600 text-white rounded hover:bg-indigo-700 transition whitespace-nowrap">
                                Asignar
                            </button>
                        </div>
                    </div>
                @empty
                    <div class="text-center py-6 text-sm text-gray-500 bg-white rounded-lg border">
                        No hay colaboradores disponibles para asignar
                    </div>
                @endforelse
            </div>

            
            <div class="mt-4 px-2 sm:px-6">
                <div class="flex flex-col sm:flex-row sm:justify-between sm:items-center gap-3">
                    <span class="text-xs text-gray-500 text-center sm:text-left">
                        Mostrando {{ $this->colaboradoresDisponibles->firstItem() ?? 0 }} a
                        {{ $this->colaboradoresDisponibles->lastItem() ?? 0 }} de
                        {{ $this->colaboradoresDisponibles->total() }} resultados
                    </span>
                    <div class="flex justify-center sm:justify-end w-full sm:w-auto">
                        {{ $this->colaboradoresDisponibles->links() }}
                    </div>
                </div>
            </div>
        </x-slot>

        <x-slot name="footer">
            <div class="flex justify-end w-full">
                <x-danger-button wire:click="$set('open', false)" class="w-full sm:w-auto">
                    Cerrar
                </x-danger-button>
            </div>
        </x-slot>
    </x-dialog-modal>

    {{-- Modal para mostrar los equipos --}}
    <x-dialog-modal wire:model="modalEquipos">
        <x-slot name="title">
            <div class="flex justify-between items-center w-full">
                <h3 class="text-base sm:text-lg font-medium">Equipos Disponibles</h3>
                <span wire:click="$set('modalEquipos',false)"
                    class="cursor-pointer text-gray-500 hover:text-gray-800">
                    <i class="fa fa-times"></i>
                </span>
            </div>
        </x-slot>

        <x-slot name="content">
            <!-- Búsqueda -->
            <div class="mb-4 px-2 sm:px-4">
                <div class="relative">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                        <i class="fa fa-search text-gray-400"></i>
                    </div>
                    <input wire:model.live.debounce.500ms="searchEquipos"
                        class="w-full text-sm border-gray-300 rounded-full pl-10 pr-4 py-2 focus:ring-indigo-500 focus:border-indigo-500"
                        placeholder="Buscar por serie, marca o modelo">
                </div>
            </div>

            <!-- Tabla para pantallas medianas y grandes -->
            <div class="hidden md:block overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200 table-auto text-sm">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-3 py-2 text-left font-semibold text-gray-600">Nombre</th>
                            <th class="px-3 py-2 text-left font-semibold text-gray-600">Marca</th>
                            <th class="px-3 py-2 text-left font-semibold text-gray-600">Modelo</th>
                            <th class="px-3 py-2 text-left font-semibold text-gray-600">Serie</th>
                            <th class="px-3 py-2 w-20"></th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-100">
                        @forelse ($this->equiposDisponibles as $equipo)
                            <tr class="hover:bg-gray-50" wire:key="modal-equipo-{{ $equipo->id }}">
                                <td class="px-3 py-2 text-gray-700">{{ $equipo->nombre }}</td>
                                <td class="px-3 py-2 text-gray-700">{{ $equipo->marca }}</td>
                                <td class="px-3 py-2 text-gray-700">{{ $equipo->modelo }}</td>
                                <td class="px-3 py-2 text-gray-700">{{ $equipo->serie }}</td>
                                <td class="px-3 py-2 text-right">
                                    <button wire:click="asignarEquipo({{ $equipo->id }})"
                                        class="text-xs px-3 py-1 bg-indigo-600 text-white rounded hover:bg-indigo-700 transition">
                                        Asignar
                                    </button>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="px-3 py-4 text-center text-gray-500">
                                    No hay equipos disponibles para asignar
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

           
            <div class="md:hidden space-y-3">
                @forelse ($this->equiposDisponibles as $equipo)
                    <div class="bg-white border rounded-lg shadow-sm p-3"
                        wire:key="modal-card-equipo-{{ $equipo->id }}">
                        <div class="flex justify-between items-start">
                            <div class="flex-1 min-w-0 mr-3">
                                <h4 class="font-medium text-gray-800 text-sm">{{ $equipo->nombre }}</h4>
                                <div class="mt-2 text-xs text-gray-600 space-y-1">
                                    <div><span class="font-medium">Marca:</span> {{ $equipo->marca }}</div>
                                    <div><span class="font-medium">Modelo:</span> {{ $equipo->modelo }}</div>
                                    <div><span class="font-medium">Serie:</span> {{ $equipo->serie }}</div>
                                </div>
                            </div>
                            <button wire:click="asignarEquipo({{ $equipo->id }})"
                                class="text-xs px-3 py-1 bg-indigo-600 text-white rounded hover:bg-indigo-700 transition whitespace-nowrap">
                                Asignar
                            </button>
                        </div>
                    </div>
                @empty
                    <div class="text-center py-6 text-sm text-gray-500 bg-white rounded-lg border">
                        No hay equipos disponibles para asignar
                    </div>
                @endforelse
            </div>

     
            <div class="mt-4 px-2 sm:px-6">
                <div class="flex flex-col sm:flex-row sm:justify-between sm:items-center gap-3">
                    <span class="text-xs text-gray-500 text-center sm:text-left">
                        Mostrando {{ $this->equiposDisponibles->firstItem() ?? 0 }} a
                        {{ $this->equiposDisponibles->lastItem() ?? 0 }} de
                        {{ $this->equiposDisponibles->total() }} resultados
                    </span>
                    <div class="flex justify-center sm:justify-end w-full sm:w-auto">
                        {{ $this->equiposDisponibles->links() }}
                    </div>
                </div>
            </div>
        </x-slot>

        <x-slot name="footer">
            <div class="flex justify-end w-full">
                <x-danger-button wire:click="$set('modalEquipos',false)" class="w-full sm:w-auto">
                    Cerrar
                </x-danger-button>
            </div>
        </x-slot>
    </x-dialog-modal>

    {{-- Modal para subir comprobante --}}
    <div>
        <x-dialog-modal wire:model="upload">
            <x-slot name="title">
                <h2 class="text-lg font-semibold text-gray-700">Subir archivo de comprobante</h2>
            </x-slot>

            <x-slot name="content">
                @if ($upload)
                    <form wire:submit.prevent="guardarArchivo" enctype="multipart/form-data" class="space-y-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-600 mb-1">Selecciona el archivo</label>
                            <input type="file" wire:model="archivo"
                                class="w-full text-sm text-gray-700 file:mr-4 file:py-2 file:px-4
                                   file:rounded-full file:border-0
                                   file:text-sm file:font-semibold
                                   file:bg-blue-50 file:text-blue-700
                                   hover:file:bg-blue-100 transition" />

                            @error('archivo')
                                <span class="text-sm text-red-600 mt-1 block">{{ $message }}</span>
                            @enderror

                            <!-- Indicador de carga -->
                            <div wire:loading wire:target="archivo" class="text-sm text-blue-600 mt-2">
                                Subiendo archivo, por favor espera...
                            </div>
                        </div>

                        <div class="flex justify-end gap-3">
                            <button type="button" wire:click="$set('upload', false)"
                                class="px-4 py-2 bg-gray-200 text-gray-700 rounded-md hover:bg-gray-300 transition">
                                Cancelar
                            </button>

                            <button type="submit"
                                class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 transition">
                                Subir archivo
                            </button>
                        </div>
                    </form>
                @endif
            </x-slot>

            <x-slot name="footer"></x-slot>
        </x-dialog-modal>
    </div>


    <div>
        <x-dialog-modal wire:model="opciones">

            {{-- 🔹 TÍTULO --}}
            <x-slot name="title">
                <div class="flex items-center gap-3">
                    <div class="p-2 bg-blue-100 rounded-lg">
                        <i class="fas fa-file-signature text-blue-600 text-lg"></i>
                    </div>
                    <div>
                        <h2 class="text-lg font-semibold text-gray-800">
                            Selección de Acta
                        </h2>
                        <p class="text-xs text-gray-500">
                            Firma y genera el documento correspondiente
                        </p>
                    </div>
                </div>
            </x-slot>

            <x-slot name="content">

                {{-- FIRMA --}}
                <div class="bg-gray-50 border border-gray-200 rounded-xl p-4 mb-6">
                    <div class="flex items-center justify-between mb-3">
                        <h3 class="text-sm font-semibold text-gray-700 flex items-center gap-2">
                            Firma (Responsable / Receptor)
                        </h3>
                        <span class="text-xs text-gray-400">
                            Usa mouse o pantalla táctil
                        </span>
                    </div>

                    {{-- CANVAS --}}
                    <div class="relative bg-white rounded-lg border border-dashed border-gray-300 p-2 mb-3"
                        wire:ignore>
                        <canvas id="canvas" width="250" height="250"
                            class="w-full h-[250px] cursor-crosshair touch-none rounded"
                            style="touch-action: none;"></canvas>
                    </div>

                    {{-- TOOLBAR --}}
                    <div class="flex flex-wrap gap-2 justify-end mb-4">
                        <button type="button" onclick="limpiarFirma()"
                            class="inline-flex items-center gap-1 px-3 py-1.5 text-xs font-medium bg-red-50 text-red-600 rounded hover:bg-red-100 transition">
                            <i class="fas fa-eraser"></i> Limpiar
                        </button>

                        <button type="button" onclick="enviarFirma('responsable')"
                            class="inline-flex items-center gap-1 px-3 py-1.5 text-xs font-medium bg-blue-600 text-white rounded hover:bg-blue-700 transition">
                            <i class="fas fa-user-check"></i> Firma responsable
                        </button>

                        <button type="button" onclick="enviarFirma('receptor')"
                            class="inline-flex items-center gap-1 px-3 py-1.5 text-xs font-medium bg-indigo-600 text-white rounded hover:bg-indigo-700 transition">
                            <i class="fas fa-user-edit"></i> Firma receptor
                        </button>
                    </div>

                    {{-- INDICADORES VISUALES --}}
                    <div class="space-y-2">
                        <div class="flex items-center gap-2 text-sm">
                            @if (isset($firmas['responsable']))
                                <i class="fas fa-check-circle text-green-600"></i>
                                <span class="text-green-700 font-medium">
                                    Firma responsable capturada
                                </span>
                            @else
                                <i class="fas fa-clock text-gray-400"></i>
                                <span class="text-gray-500">
                                    Firma responsable pendiente
                                </span>
                            @endif
                        </div>

                        <div class="flex items-center gap-2 text-sm">
                            @if (isset($firmas['receptor']))
                                <i class="fas fa-check-circle text-green-600"></i>
                                <span class="text-green-700 font-medium">
                                    Firma receptor capturada
                                </span>
                            @else
                                <i class="fas fa-clock text-gray-400"></i>
                                <span class="text-gray-500">
                                    Firma receptor pendiente
                                </span>
                            @endif
                        </div>
                    </div>
                </div>

                {{-- DESCRIPCIÓN --}}
                <p class="text-sm text-gray-600 mb-6 text-center leading-relaxed">
                    Selecciona el tipo de acta que deseas generar para la cuadrilla.
                </p>

                {{-- ACCIONES --}}
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <button wire:click="generar({{ $cuaIdSeleccionado }})" @disabled(!isset($firmas['responsable']))
                        class="flex items-center justify-center gap-2 px-5 py-3 text-sm font-semibold rounded-xl shadow transition
                {{ isset($firmas['responsable'])
                    ? 'bg-green-600 text-white hover:bg-green-700'
                    : 'bg-gray-300 text-gray-500 cursor-not-allowed' }}">
                        <i class="fas fa-sim-card"></i>
                        Acta de Chip
                    </button>

                    <button wire:click="generarActaEquipo({{ $cuaIdSeleccionado }})" @disabled(!isset($firmas['responsable'], $firmas['receptor']))
                        class="flex items-center justify-center gap-2 px-5 py-3 text-sm font-semibold rounded-xl shadow transition
                {{ isset($firmas['responsable'], $firmas['receptor'])
                    ? 'bg-blue-600 text-white hover:bg-blue-700'
                    : 'bg-gray-300 text-gray-500 cursor-not-allowed' }}">
                        <i class="fas fa-tablet-alt"></i>
                        Acta de Equipos / Medidor
                    </button>
                </div>

            </x-slot>

        
            <x-slot name="footer">
                <div class="flex justify-end">
                    <button wire:click="cerrarModal"
                        class="inline-flex items-center gap-2 px-4 py-2 text-sm font-medium text-gray-500 hover:text-gray-800 transition">
                        <i class="fas fa-times"></i> Cerrar
                    </button>
                </div>
            </x-slot>

        </x-dialog-modal>


    </div>


    @push('js')
        <script>
            let canvas, ctx, dibujando = false;

            document.addEventListener('livewire:init', () => {
                setTimeout(initFirma, 200);

                Livewire.on('limpiar-firma', limpiarFirma);
            });

            function initFirma() {
                canvas = document.getElementById('canvas');
                if (!canvas) return;

                ctx = canvas.getContext('2d');
                ctx.strokeStyle = 'black';
                ctx.lineWidth = 2;
                ctx.lineCap = 'round';

                canvas.style.touchAction = 'none';
                canvas.addEventListener('pointerdown', iniciar);
                canvas.addEventListener('pointermove', mover);
                canvas.addEventListener('pointerup', detener);
                canvas.addEventListener('pointerleave', detener);
            }

            function iniciar(e) {
                e.preventDefault();
                dibujando = true;

                const rect = canvas.getBoundingClientRect();
                ctx.beginPath();
                ctx.moveTo(
                    e.clientX - rect.left,
                    e.clientY - rect.top
                );
            }

            function mover(e) {
                if (!dibujando) return;
                e.preventDefault();

                const rect = canvas.getBoundingClientRect();
                ctx.lineTo(
                    e.clientX - rect.left,
                    e.clientY - rect.top
                );
                ctx.stroke();
            }

            function detener() {
                dibujando = false;
            }

            function enviarFirma(tipo) {
                if (!canvas) return;

                const firmaData = canvas.toDataURL('image/png');

                Livewire.dispatch('firmaCapturada', {
                    tipo: tipo,
                    firma: firmaData
                });

                limpiarFirma();
            }

            function limpiarFirma() {
                if (!ctx || !canvas) return;

                ctx.clearRect(0, 0, canvas.width, canvas.height);
                ctx.beginPath();
            }
        </script>
    @endpush

</div>
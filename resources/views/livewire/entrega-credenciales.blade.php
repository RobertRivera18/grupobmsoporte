<div>
    <div class="max-w-10xl mx-auto sm:px-6 py-8">
        <div class="bg-white shadow-lg rounded-2xl overflow-hidden">
            <!-- Header -->
            <div
                class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 px-6 py-4 bg-gradient-to-r from-indigo-600 to-blue-500">
                <h2 class="text-lg font-semibold text-white flex items-center gap-2">
                    <i class="fas fa-file-alt"></i> Actas Registradas
                </h2>
                <div class="flex flex-col sm:flex-row items-center gap-3 w-full sm:w-auto">
                    <input wire:keydown="limpiar_page" wire:model="search"
                        class="w-full sm:w-64 text-sm px-4 py-2 rounded-full border border-gray-300 focus:outline-none focus:ring-2 focus:ring-blue-300 transition"
                        placeholder="Buscar colaborador...">
                    <button wire:click="crearActa"
                        class="bg-red-600 hover:bg-red-700 text-white font-medium px-4 py-2 rounded-full text-sm flex items-center gap-2 shadow-md transition">
                        <i class="fas fa-plus"></i> Nuevo Registro
                    </button>
                </div>
            </div>

            <!-- Table -->
            <div class="overflow-x-auto">
                @if ($actas->count() > 0)
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th
                                    class="px-6 py-3 text-left text-xs font-bold text-gray-700 uppercase tracking-wider">
                                    Equipo Entregado
                                </th>
                                <th
                                    class="px-6 py-3 text-left text-xs font-bold text-gray-700 uppercase tracking-wider">
                                    Entregado a
                                </th>
                                <th
                                    class="px-6 py-3 text-left text-xs font-bold text-gray-700 uppercase tracking-wider">
                                    Empresa
                                </th>
                                <th
                                    class="px-6 py-3 text-left text-xs font-bold text-gray-700 uppercase tracking-wider">
                                    Contrato
                                </th>
                                <th
                                    class="px-6 py-3 text-left text-xs font-bold text-gray-700 uppercase tracking-wider">
                                    Tipo de Sangre
                                </th>
                                <th
                                    class="px-6 py-3 text-left text-xs font-bold text-gray-700 uppercase tracking-wider">
                                    Imagen Adjunta
                                </th>
                                <th
                                    class="px-6 py-3 text-left text-xs font-bold text-gray-700 uppercase tracking-wider">
                                    Fecha de Entrega
                                </th>
                                <th
                                    class="px-6 py-3 text-center text-xs font-bold text-gray-700 uppercase tracking-wider">
                                    Acciones
                                </th>
                            </tr>
                        </thead>

                        <tbody class="bg-white divide-y divide-gray-100">
                            @foreach ($actas as $acta)
                                <tr class="hover:bg-gray-50 transition">
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-800">
                                        Entrega de Credencial Corporativa
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-800">
                                        {{ $acta->user->name }}
                                        @if ($acta->user->created_at)
                                            <span
                                                class="ml-2 inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                                Ingreso nuevo
                                            </span>
                                        @endif
                                    </td>


                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-800">
                                        {{ $acta->empresa }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-800">
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-800">
                                        {{ $acta->tipoContrato ? $acta->tipoContrato->name : 'Sin contrato' }}
                                    </td>

                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-800">
                                        {{ $acta->tipo_sangre }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-800">
                                        @if ($acta->imagen_path)
                                            <a href="{{ asset('storage/' . $acta->imagen_path) }}" download
                                                class="inline-flex items-center gap-2 text-blue-600 hover:text-blue-800 font-medium">
                                                <i class="fas fa-download"></i> Descargar imagen
                                            </a>
                                        @else
                                            <span class="text-gray-400 italic">Sin archivo</span>
                                        @endif
                                    </td>

                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-800">
                                        {{ $acta->created_at->format('d/m/Y H:i:s') }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="flex items-center justify-center gap-3">
                                            <button wire:click="eliminarActa({{ $acta->id }})"
                                                class="text-red-500 hover:text-red-700 transition transform hover:scale-110"
                                                title="Eliminar">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                            <button wire:click="generarActa({{ $acta->id }})"
                                                class="text-blue-500 hover:text-blue-700 transition transform hover:scale-110"
                                                title="Generar Word">
                                                <i class="fas fa-file-word"></i>
                                            </button>
                                            <button wire:click="uploadActa({{ $acta->id }})"
                                                class="text-yellow-500 hover:text-yellow-700 transition transform hover:scale-110"
                                                title="Subir Imagen">
                                                <i class="fas fa-image"></i>
                                            </button>
                                            <button wire:click="downloadActa({{ $acta->id }})"
                                                class="text-green-500 hover:text-green-700 transition transform hover:scale-110"
                                                title="Descargar">
                                                <i class="fas fa-download"></i>
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>

                    <!-- Pagination -->
                    <div class="px-6 py-4 bg-gray-50">
                        {{ $actas->links() }}
                    </div>
                @else
                    <div class="p-6 text-center text-gray-500 text-sm">
                        <i class="fas fa-info-circle mr-2"></i>
                        No hay ningún registro que coincida con la búsqueda.
                    </div>
                @endif
            </div>
        </div>
    </div>


    {{-- Modal para seleccionar tipo de reporte a generar --}}
    <div>
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
                                {{-- @empty
                                <tr>
                                    <td colspan="3" class="px-3 py-4 text-center text-gray-500">
                                        No hay colaboradores disponibles para asignar
                                    </td>
                                </tr>
                            @endforelse --}}
                            @empty
                                <tr>
                                    <td colspan="3" class="px-3 py-4 text-center text-gray-500">
                                        No hay colaboradores disponibles para asignar.
                                    </td>
                                </tr>
                                <tr>
                                    <td colspan="3" class="px-3 py-4">
                                        <div class="bg-gray-50 p-4 rounded-lg shadow-inner space-y-3">
                                            <h4 class="text-sm font-semibold text-gray-700 mb-2">
                                                Crear nuevo colaborador
                                            </h4>
                                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                                                <div>
                                                    <label
                                                        class="block text-xs font-medium text-gray-600 mb-1">Nombre</label>
                                                    <input type="text" wire:model="nuevo_nombre"
                                                        class="w-full border-gray-300 rounded-md text-sm focus:ring-indigo-500 focus:border-indigo-500">
                                                    @error('nuevo_nombre')
                                                        <span class="text-red-600 text-xs">{{ $message }}</span>
                                                    @enderror
                                                </div>
                                                <div>
                                                    <label
                                                        class="block text-xs font-medium text-gray-600 mb-1">Cédula</label>
                                                    <input type="text" wire:model="nuevo_cedula"
                                                        class="w-full border-gray-300 rounded-md text-sm focus:ring-indigo-500 focus:border-indigo-500">
                                                    @error('nuevo_cedula')
                                                        <span class="text-red-600 text-xs">{{ $message }}</span>
                                                    @enderror
                                                </div>
                                            </div>
                                            <div class="flex justify-end">
                                                <button wire:click="crearNuevoColaborador"
                                                    class="px-4 py-1.5 bg-indigo-600 text-white rounded-md hover:bg-indigo-700 text-sm transition">
                                                    Guardar y continuar
                                                </button>
                                            </div>
                                        </div>
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
                                    <h4 class="font-medium text-gray-800 text-sm truncate">{{ $colaborador->name }}
                                    </h4>
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

                <!-- Paginación -->
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
    </div>


    <!-- Modal para completar datos del acta -->
    <div>
        <x-dialog-modal wire:model="modalDatosActa">
            <x-slot name="title">
                <h2 class="text-lg font-semibold text-gray-700">Completar Datos del Acta</h2>
            </x-slot>

            <x-slot name="content">
                <div class="space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Empresa</label>
                        <select wire:model="empresa"
                            class="w-full mt-1 border-gray-300 rounded-md text-sm focus:ring-indigo-500 focus:border-indigo-500">
                            <option value="">Seleccione una empresa</option>
                            <option value="Claro">Claro</option>
                            <option value="CNEL">CNEL</option>
                        </select>
                        @error('empresa')
                            <span class="text-red-600 text-sm">{{ $message }}</span>
                        @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700">Tipo de Sangre</label>
                        <input type="text" wire:model="tipo_sangre" placeholder="Ej: O+, A-, B+"
                            class="w-full mt-1 border-gray-300 rounded-md text-sm focus:ring-indigo-500 focus:border-indigo-500">
                        @error('tipo_sangre')
                            <span class="text-red-600 text-sm">{{ $message }}</span>
                        @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700">Tipo de Contrato</label>
                        <select wire:model="tipo_contrato_id"
                            class="w-full mt-1 border-gray-300 rounded-md text-sm focus:ring-indigo-500 focus:border-indigo-500">
                            <option value="">Seleccione un tipo de contrato</option>
                            @foreach ($tiposContratos as $contrato)
                                <option value="{{ $contrato->id }}">{{ $contrato->name }}</option>
                            @endforeach
                        </select>

                        @error('tipo_contrato_id')
                            <span class="text-red-600 text-sm">{{ $message }}</span>
                        @enderror
                    </div>


                    <div>
                        <label class="block text-sm font-medium text-gray-700">Adjuntar Imagen</label>
                        <input type="file" wire:model="imagen" accept="image/*"
                            class="w-full text-sm text-gray-700 file:mr-4 file:py-2 file:px-4
                               file:rounded-full file:border-0 file:bg-blue-50 file:text-blue-700
                               hover:file:bg-blue-100 transition" />
                        <div wire:loading wire:target="imagen" class="text-blue-600 text-sm mt-1">
                            Cargando imagen...
                        </div>
                        @error('imagen')
                            <span class="text-red-600 text-sm">{{ $message }}</span>
                        @enderror
                    </div>
                </div>
            </x-slot>

            <x-slot name="footer">
                <div class="flex justify-end gap-3">
                    <x-secondary-button wire:click="$set('modalDatosActa', false)">Cancelar</x-secondary-button>
                    <x-button wire:click="guardarActa"
                        class="bg-indigo-600 hover:bg-indigo-700 text-white">Guardar</x-button>
                </div>
            </x-slot>
        </x-dialog-modal>
    </div>


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
</div>

<script>
    Livewire.on('descargar-acta', data => {
        window.location.href = data.url;
    });
</script>

<div>
    <div class="max-w-9xl mx-auto px-2 sm:px-4 md:px-6 lg:px-8 py-6">
        <x-table-responsive>
            <!-- Barra de búsqueda -->
            <div class="px-3 sm:px-6 py-3 flex flex-col sm:flex-row gap-3 sm:gap-0">
                <input wire:keydown="limpiar_page" wire:model="search"
                    class="w-full text-sm form-input shadow-sm rounded-full border-gray-300 focus:ring-indigo-500 focus:border-indigo-500"
                    placeholder="Ingrese el nombre del Usuario o Equipo a Buscar">
            </div>

            @if ($users->count() > 0)
                <!-- Tabla para pantallas medianas y grandes -->
                <div class="hidden md:block overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Nombres</th>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Rol</th>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Equipos
                                    Asignados</th>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase"></th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @foreach ($users as $user)
                                <tr class="hover:bg-gray-50">
                                    <td class="py-3 px-4 whitespace-nowrap">
                                        <div>
                                            <div class="text-sm font-semibold text-gray-900">{{ $user->name }}</div>
                                            <div class="text-sm text-gray-600">{{ $user->email }}</div>
                                        </div>
                                    </td>

                                    <td class="py-3 px-4 whitespace-nowrap">
                                        @if ($user->roles->isNotEmpty())
                                            <span
                                                class="bg-blue-100 text-blue-800 text-xs font-medium px-2.5 py-0.5 rounded">
                                                {{ $user->roles->pluck('name')->join(', ') }}
                                            </span>
                                        @else
                                            <span class="text-gray-400 text-xs">Sin rol</span>
                                        @endif
                                    </td>

                                    <td class="py-3 px-4 whitespace-nowrap">
                                        <div class="flex flex-col gap-1">
                                            @if ($user->equipos->isNotEmpty())
                                                @foreach ($user->equipos as $equipo)
                                                    <div class="flex items-center gap-2">
                                                        <span class="text-xs text-gray-700">
                                                            {{ $equipo->nombre }} - {{ $equipo->marca }} -
                                                            {{ $equipo->serie }}
                                                        </span>
                                                        <button
                                                            wire:click="eliminarEquipo({{ $user->id }}, {{ $equipo->id }})"
                                                            class="text-red-500 text-xs hover:underline"
                                                            title="Eliminar equipo">
                                                            ❌
                                                        </button>
                                                    </div>
                                                @endforeach
                                            @else
                                                <span class="text-gray-400 text-sm">Sin equipos</span>
                                            @endif
                                            <button wire:click="agregarEquipo({{ $user->id }})"
                                                class="mt-1 text-indigo-600 text-xs hover:underline">+ Agregar</button>
                                        </div>
                                    </td>

                                    <td class="px-4 py-3">
                                        <div class="flex flex-wrap gap-3 justify-start md:justify-center">
                                            <i class="fa fa-file-word text-blue-500 text-lg hover:text-green-800 cursor-pointer"
                                                wire:click="generar({{ $user->id }})"></i>
                                            <i class="fa fa-signature text-purple-600 hover:text-purple-800 cursor-pointer text-lg"
                                                wire:click="abrirModalFirma({{ $user->id }})"
                                                title="Capturar Firma y Descargar"></i>
                                            <i class="fa fa-image text-yellow-500 cursor-pointer text-lg"
                                                wire:click="uploadFile({{ $user->id }})"></i>
                                            <i class="fa fa-download text-red-500 hover:text-blue-700 cursor-pointer text-lg"
                                                wire:click="abrirModalDescargas({{ $user->id }})">
                                            </i>

                                            @if (empty($user->qr_codigo))
                                                <i class="fa fa-qrcode text-blue-500 hover:text-blue-700 cursor-pointer text-lg"
                                                    wire:click="generar_qr({{ $user->id }})"
                                                    title="Generar QR"></i>
                                            @else
                                                {{-- Si ya existe, muestra el QR real y el botón flotante para actualizarlo --}}
                                                <div class="relative group inline-block">
                                                    <img class="w-16 h-16 rounded border bg-white shadow-sm"
                                                        src="{{ asset('storage/' . $user->qr_codigo) }}?v={{ time() }}"
                                                        alt="QR de {{ $user->name }}">


                                                    {{-- Botón pequeño para regenerar el QR si se añaden más equipos --}}
                                                    <button wire:click="generar_qr({{ $user->id }})"
                                                        class="absolute -top-1 -right-1 bg-indigo-600 text-white rounded-full w-5 h-5 flex items-center justify-center hover:bg-indigo-700 shadow transition"
                                                        title="Regenerar QR con datos actualizados">
                                                        <i class="fa fa-sync-alt text-[10px]"></i>
                                                    </button>
                                                </div>
                                            @endif
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <!-- Vista móvil -->
                <div class="md:hidden space-y-4">
                    @foreach ($users as $user)
                        <div class="bg-white shadow rounded-lg p-4 border border-gray-200">
                            <div class="flex justify-between items-start">
                                <div>
                                    <p class="font-semibold text-gray-900">{{ $user->name }}</p>
                                    <p class="text-xs text-gray-500">{{ $user->email }}</p>
                                </div>
                                <div>
                                    @if ($user->roles->isNotEmpty())
                                        <span
                                            class="bg-blue-100 text-blue-800 text-xs font-medium px-2.5 py-0.5 rounded">
                                            {{ $user->roles->pluck('name')->join(', ') }}
                                        </span>
                                    @else
                                        <span class="text-gray-400 text-xs">Sin rol</span>
                                    @endif
                                </div>
                            </div>

                            <div class="mt-3">
                                @if ($user->equipos->isNotEmpty())
                                    <ul class="text-xs text-gray-700 space-y-1">
                                        @foreach ($user->equipos as $equipo)
                                            <li class="flex justify-between items-center border-b pb-1">
                                                <span>{{ $equipo->nombre }} - {{ $equipo->marca }}</span>
                                                <button
                                                    wire:click="eliminarEquipo({{ $user->id }}, {{ $equipo->id }})"
                                                    class="text-red-500 text-xs hover:underline">❌</button>
                                            </li>
                                        @endforeach
                                    </ul>
                                @else
                                    <span class="text-gray-400 text-xs">Sin equipos</span>
                                @endif
                                <button wire:click="agregarEquipo({{ $user->id }})"
                                    class="mt-2 text-indigo-600 text-xs hover:underline">+ Agregar</button>
                            </div>

                            <div class="mt-3 flex flex-wrap gap-3 justify-start">
                                <i class="fa fa-file-word text-blue-500 text-lg hover:text-green-800 cursor-pointer"
                                    wire:click="generar({{ $user->id }})"></i>
                                <i class="fa fa-image text-yellow-500 cursor-pointer text-lg"
                                    wire:click="uploadFile({{ $user->id }})"></i>
                                <i class="fa fa-download text-red-500 hover:text-blue-700 cursor-pointer text-lg"
                                    wire:click="descargarArchivo({{ $user->id }})"></i>


                                @if (empty($user->qr_codigo))
                                    <i class="fa fa-qrcode text-blue-500 hover:text-blue-700 cursor-pointer text-lg"
                                        wire:click="generar_qr({{ $user->id }})" title="Generar QR"></i>
                                @else
                                    <div class="relative group inline-block">
                                        <img class="w-16 h-16 rounded border bg-white shadow-sm"
                                            src="{{ asset('storage/' . $user->qr_codigo) }}?v={{ time() }}"
                                            alt="QR de {{ $user->name }}">
                                        <button wire:click="generar_qr({{ $user->id }})"
                                            class="absolute -top-1 -right-1 bg-indigo-600 text-white rounded-full w-5 h-5 flex items-center justify-center hover:bg-indigo-700 shadow transition"
                                            title="Regenerar QR con datos actualizados">
                                            <i class="fa fa-sync-alt text-[10px]"></i>
                                        </button>
                                    </div>
                                @endif
                            </div>
                        </div>
                    @endforeach
                </div>

                <div class="px-4 py-4">
                    {{ $users->links() }}
                </div>
            @else
                <div class="px-6 py-4 text-center text-gray-500">
                    No hay ningún registro que coincida con la búsqueda.
                </div>
            @endif
        </x-table-responsive>
    </div>


    {{-- Modal para mostrar los equipos --}}
    <x-dialog-modal wire:model="open">
        <x-slot name="title">
            <div class="flex justify-between items-center w-full">
                <h3 class="text-base sm:text-lg font-medium">Equipos Disponibles</h3>
                <span wire:click="$set('open',false)" class="cursor-pointer text-gray-500 hover:text-gray-800">
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

            <!-- Vista tipo tarjeta para móviles y tablets pequeñas -->
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

            <!-- Paginación -->
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

                            <!-- Spinner de carga -->
                            <div wire:loading wire:target="archivo"
                                class="flex items-center gap-2 text-blue-600 mt-2">
                                <svg class="animate-spin h-5 w-5 text-blue-600" xmlns="http://www.w3.org/2000/svg"
                                    fill="none" viewBox="0 0 24 24">
                                    <circle class="opacity-25" cx="12" cy="12" r="10"
                                        stroke="currentColor" stroke-width="4"></circle>
                                    <path class="opacity-75" fill="currentColor"
                                        d="M4 12a8 8 0 018-8v4a4 4 0 00-4 4H4z"></path>
                                </svg>
                                <span class="text-sm font-medium">Subiendo archivo...</span>
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
        <x-dialog-modal wire:model="descargar">
            <x-slot name="title">
                <h2 class="text-lg font-semibold text-gray-700">
                    Descargar documentos
                </h2>
            </x-slot>

            <x-slot name="content">

                <div class="space-y-3">

                    @if ($tieneFirma)
                        <button wire:click="descargarFirma"
                            class="w-full flex items-center justify-between px-4 py-3 rounded-lg border hover:bg-gray-100">

                            <div class="flex items-center gap-3">
                                <i class="fa fa-file-signature text-green-600 text-xl"></i>

                                <div class="text-left">
                                    <div class="font-semibold">
                                        Acta firmada digitalmente
                                    </div>

                                    <div class="text-xs text-gray-500">
                                        Descargar acta con firma digital
                                    </div>
                                </div>
                            </div>

                            <i class="fa fa-download"></i>

                        </button>
                    @endif


                    @if ($tieneComprobante)
                        <button wire:click="descargarComprobante"
                            class="w-full flex items-center justify-between px-4 py-3 rounded-lg border hover:bg-gray-100">

                            <div class="flex items-center gap-3">
                                <i class="fa fa-file-upload text-blue-600 text-xl"></i>

                                <div class="text-left">
                                    <div class="font-semibold">
                                        Comprobante cargado manualmente
                                    </div>

                                    <div class="text-xs text-gray-500">
                                        Descargar archivo subido por el usuario
                                    </div>
                                </div>
                            </div>

                            <i class="fa fa-download"></i>

                        </button>
                    @endif

                </div>

            </x-slot>

            <x-slot name="footer">
                <x-secondary-button wire:click="$set('descargar', false)">
                    Cerrar
                </x-secondary-button>
            </x-slot>

        </x-dialog-modal>
    </div>


    <x-dialog-modal wire:model="modalFirma">
        <x-slot name="title">
            <div class="flex justify-between items-center w-full">
                <h3 class="text-base sm:text-lg font-medium text-gray-800">Capturar Firma del Usuario</h3>
                <span wire:click="$set('modalFirma', false)" class="cursor-pointer text-gray-500 hover:text-gray-800">
                    <i class="fa fa-times"></i>
                </span>
            </div>
        </x-slot>

        <x-slot name="content">
            <div x-data="{
                signaturePad: null,
                initPad() {
                    this.$nextTick(() => {
                        let canvas = this.$refs.canvas;
                        if (!canvas) return;
            
                        // Ajusta la resolución del canvas según la pantalla para evitar líneas borrosas
                        const ratio = Math.max(window.devicePixelRatio || 1, 1);
                        canvas.width = canvas.offsetWidth * ratio;
                        canvas.height = canvas.offsetHeight * ratio;
                        canvas.getContext('2d').scale(ratio, ratio);
            
                        if (this.signaturePad) {
                            this.signaturePad.clear();
                        } else {
                            this.signaturePad = new SignaturePad(canvas, {
                                backgroundColor: 'rgb(255, 255, 255)',
                                penColor: 'rgb(0, 0, 0)'
                            });
                        }
                    });
                },
                limpiar() {
                    if (this.signaturePad) {
                        this.signaturePad.clear();
                    }
                },
                guardar() {
                    if (!this.signaturePad || this.signaturePad.isEmpty()) {
                        alert('Por favor dibuje su firma antes de guardar.');
                        return;
                    }
                    let dataUrl = this.signaturePad.toDataURL('image/png');
                    $wire.guardarYDescargarFirmado(dataUrl);
                }
            }" x-init="$watch('$wire.modalFirma', value => { if (value) initPad() })" class="flex flex-col items-center">

                <p class="text-sm text-gray-600 mb-3 text-center">
                    Dibuje la firma dentro del recuadro usando el mouse o pantalla táctil.
                </p>

                <div
                    class="w-full max-w-md border-2 border-dashed border-gray-400 rounded-lg bg-white overflow-hidden shadow-inner">
                    <canvas x-ref="canvas" class="w-full h-48 touch-none cursor-crosshair block"></canvas>
                </div>

                <div class="flex gap-3 mt-4 w-full justify-between">
                    <button type="button" @click="limpiar()"
                        class="px-3 py-1.5 bg-gray-200 text-gray-700 text-xs font-semibold rounded hover:bg-gray-300 transition flex items-center gap-1">
                        <i class="fa fa-eraser"></i>
                    </button>

                    <button type="button" @click="guardar()"
                        class="px-4 py-2 bg-indigo-600 text-white text-xs font-semibold rounded hover:bg-indigo-700 transition flex items-center gap-1">
                        <i class="fa fa-file-download"></i> Guardar y Descargar Firmado
                    </button>
                </div>
            </div>
        </x-slot>

        <x-slot name="footer">
            <div class="flex justify-end w-full">
                <x-danger-button wire:click="$set('modalFirma', false)">
                    Cancelar
                </x-danger-button>
            </div>
        </x-slot>
    </x-dialog-modal>

    @push('js')
        <script src="https://cdn.jsdelivr.net/npm/signature_pad@4.1.7/dist/signature_pad.umd.min.js"></script>
    @endpush
</div>

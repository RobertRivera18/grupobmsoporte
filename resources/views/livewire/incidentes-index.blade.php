<div class="max-w-3xl mx-auto bg-white rounded-2xl shadow-lg p-8 space-y-8">
    <div class="border-b pb-4">
        <h2 class="text-2xl font-bold text-gray-800 flex items-center gap-2">
            📋 Registrar Incidencia
        </h2>
        <p class="text-sm text-gray-500 mt-1">
            Complete la información para registrar una nueva incidencia.
            En caso de ser pérdida de credencial se debe adjuntar la respectiva denuncia.
        </p>
    </div>

    @if (session('success'))
        <div class="p-4 rounded-lg bg-green-50 border border-green-200 text-green-700">
            {{ session('success') }}
        </div>
    @endif

    <form wire:submit.prevent="guardarIncidencia" class="space-y-6">
        <div>
            <label class="block text-sm font-semibold text-gray-700 mb-1">
                Cédula del usuario
            </label>

            <div class="flex gap-2">
                <input
                    type="text"
                    wire:model.defer="cedula"
                    placeholder="Ingrese la cédula"
                    class="w-full rounded-lg border-gray-300 focus:border-blue-500 focus:ring-blue-500"
                >

                <button
                    type="button"
                    wire:click="buscarUsuario"
                    class="px-4 rounded-lg bg-blue-600 text-white hover:bg-blue-700 transition"
                >
                    Buscar
                </button>
            </div>

            @error('cedula')
                <p class="text-sm text-red-500 mt-1">{{ $message }}</p>
            @enderror
        </div>

        <!-- USUARIO -->
        <div>
            <label class="block text-sm font-semibold text-gray-700 mb-1">
                Usuario
            </label>

            <input
                type="text"
                wire:model="nombre_usuario"
                readonly
                class="w-full rounded-lg bg-gray-100 border-gray-300 cursor-not-allowed"
            >

            @if ($usuarioEncontrado)
                <p class="text-sm text-green-600 mt-1 flex items-center gap-1">
                    ✔ Usuario encontrado:
                    <strong>{{ $usuarioEncontrado->name }}</strong>
                </p>
            @endif
        </div>

        <div>
            <label class="block text-sm font-semibold text-gray-700 mb-1">
                Tipo de incidencia
            </label>
            <select
                wire:model.defer="nombre"
                class="w-full rounded-lg border-gray-300 focus:border-blue-500 focus:ring-blue-500"
            >
                <option value="">-- Seleccione un tipo de incidencia --</option>
                @foreach (\App\Enums\TipoIncidencia::cases() as $tipo)
                    <option value="{{ $tipo->value }}">{{ $tipo->label() }}</option>
                @endforeach
            </select>

            @error('nombre')
                <p class="text-sm text-red-500 mt-1">{{ $message }}</p>
            @enderror
        </div>

        <div>
            <label class="block text-sm font-semibold text-gray-700 mb-1">
                Detalle
            </label>
            <textarea placeholder="Ingresa una breve descripcion de la i"
                rows="4"
                wire:model.defer="detalle"
                class="w-full rounded-lg border-gray-300 focus:border-blue-500 focus:ring-blue-500 resize-none"

            ></textarea>
            @error('detalle')
                <p class="text-sm text-red-500 mt-1">{{ $message }}</p>
            @enderror
        </div>

        <div class="max-w-xs">
            <label class="block text-sm font-semibold text-gray-700 mb-1">
                Fecha
            </label>
            <input
                type="date"
                wire:model.defer="fecha"
                class="w-full rounded-lg border-gray-300 focus:border-blue-500 focus:ring-blue-500"
            >
            @error('fecha')
                <p class="text-sm text-red-500 mt-1">{{ $message }}</p>
            @enderror
        </div>

        <div x-data="{ isUploading: false, progress: 0 }"
             x-on:livewire-upload-start="isUploading = true"
             x-on:livewire-upload-finish="isUploading = false"
             x-on:livewire-upload-error="isUploading = false"
             x-on:livewire-upload-progress="progress = $event.detail.progress">

            <label class="block text-sm font-semibold text-gray-700 mb-2">
                Adjuntar archivos
            </label>

            <div class="border-2 border-dashed border-gray-300 rounded-xl p-6 text-center hover:border-blue-500 transition-colors bg-gray-50/50">
                <input
                    type="file"
                    wire:model="archivos"
                    multiple
                    accept="image/*,.pdf,.docx"
                    class="w-full text-sm text-gray-600 cursor-pointer file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100"
                >
                <p class="text-xs text-gray-500 mt-2">
                    JPG, PNG, PDF, DOCX — máx. 2MB por archivo
                </p>

                <div x-show="isUploading" class="mt-4 w-full">
                    <div class="flex justify-between items-center mb-1">
                        <span class="text-xs font-semibold text-blue-600">Subiendo archivos...</span>
                        <span class="text-xs font-semibold text-blue-600" x-text="`${progress}%`"></span>
                    </div>
                    <div class="w-full bg-gray-200 rounded-full h-2.5 overflow-hidden">
                        <div class="bg-blue-600 h-2.5 rounded-full transition-all duration-150" :style="`width: ${progress}%`"></div>
                    </div>
                </div>
            </div>

            @error('archivos.*')
                <p class="text-sm text-red-500 mt-1">{{ $message }}</p>
            @enderror

            @if ($archivos)
                <div class="mt-4">
                    <p class="text-sm font-semibold text-gray-700 mb-2">
                        Archivos seleccionados ({{ count($archivos) }})
                    </p>

                    <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 gap-4">
                        @foreach ($archivos as $index =>$archivo)
                            <div class="relative group border rounded-xl overflow-hidden bg-white shadow-sm flex flex-col items-center justify-center p-2 h-32">
                                
                                <button type="button" wire:click="eliminarArchivo({{ $index }})"
                                    class="absolute top-1 right-1 bg-red-600 text-white rounded-full p-1 shadow hover:bg-red-700 transition opacity-90 group-hover:opacity-100 z-10"
                                    title="Eliminar archivo">
                                    <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                                    </svg>
                                </button>

                                @if (str_starts_with($archivo->getMimeType(), 'image/'))
                                    <img src="{{ $archivo->temporaryUrl() }}" class="object-cover w-full h-full rounded-lg">
                                @else
                                    <div class="flex flex-col items-center justify-center text-center p-2">
                                        @if ($archivo->extension() === 'pdf')
                                            <span class="px-2 py-1 bg-red-100 text-red-700 font-bold text-xs rounded border border-red-200">PDF</span>
                                        @else
                                            <span class="px-2 py-1 bg-blue-100 text-blue-700 font-bold text-xs rounded border border-blue-200">{{ strtoupper($archivo->extension()) }}</span>
                                        @endif
                                        <span class="text-xs text-gray-600 mt-2 truncate max-w-[100px]" title="{{ $archivo->getClientOriginalName() }}">
                                            {{ $archivo->getClientOriginalName() }}
                                        </span>
                                    </div>
                                @endif
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif
        </div>

        <div class="pt-4">
            <button
                type="submit"
                wire:loading.attr="disabled"
                class="w-full md:w-auto px-6 py-3 rounded-xl bg-green-600 text-white font-semibold hover:bg-green-700 transition flex items-center gap-2 disabled:opacity-50"
            >
                <span wire:loading.remove>Registrar incidencia</span>
                <span wire:loading>Guardando...</span>
            </button>
        </div>
    </form>

    <script>
        window.addEventListener('swal', event => {
            Swal.fire({
                icon: event.detail.icon ?? 'success',
                title: event.detail.title ?? 'Correcto',
                text: event.detail.text ?? 'Incidencia registrada con éxito',
                timer: event.detail.timer ?? 2000,
                showConfirmButton: false
            });
        });
    </script>

</div>
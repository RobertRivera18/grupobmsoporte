<div class="py-12 bg-gray-100 min-h-screen">
    {{-- Estilos personalizados rápidos para Dropzone adaptados a Tailwind --}}
    <style>
        .dropzone {
            border: 2px dashed #e5e7eb !important;
            background: #f9fafb !important;
            border-radius: 0.375rem !important;
            padding: 1.5rem !important;
        }
        .dropzone .dz-message {
            margin: 1.5rem 0 !important;
            font-weight: 500;
            color: #4b5563;
        }
    </style>

    <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">

        <header class="mb-6">
            <h3 class="text-2xl font-bold text-gray-800">Registro Ingreso / Salida Vehículos</h3>
        </header>

        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6 md:p-8">

            @if ($error_mensaje)
                <div class="p-4 mb-4 text-sm text-red-800 bg-red-100 rounded-lg">
                    {{ $error_mensaje }}
                </div>
            @endif

            <form wire:submit.prevent="guardar" class="space-y-6">

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Tipo de Registro</label>
                    <select wire:model.live="tipo"
                        class="w-full rounded-md shadow-sm border-gray-300 focus:border-indigo-500 focus:ring focus:ring-indigo-200">
                        <option value="1">Ingreso</option>
                        <option value="2">Salida</option>
                    </select>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Placa</label>
                    <input type="text" wire:model.live.debounce.300ms="placa"
                        class="w-full rounded-md shadow-sm border-gray-300 focus:border-indigo-500 focus:ring focus:ring-indigo-200 uppercase placeholder-gray-400"
                        placeholder="Ej: ABC-1234" required>
                </div>

                @if ($mostrar_datos)
                    <div class="space-y-6 bg-gray-50 p-4 rounded-md border border-gray-200 transition-all duration-300">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Modelo</label>
                            <input type="text"
                                class="w-full rounded-md bg-gray-200 border-gray-300 text-gray-600 cursor-not-allowed shadow-sm"
                                value="{{ $modelo }}" readonly>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Marca</label>
                            <input type="text"
                                class="w-full rounded-md bg-gray-200 border-gray-300 text-gray-600 cursor-not-allowed shadow-sm"
                                value="{{ $marca }}" readonly>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Kilometraje Actual</label>
                            <input type="number" wire:model="kilometraje"
                                class="w-full rounded-md shadow-sm border-gray-300 focus:border-indigo-500 focus:ring focus:ring-indigo-200"
                                min="{{ $ultimo_km }}" required>
                            @error('kilometraje')
                                <span class="text-xs text-red-600">{{ $message }}</span>
                            @enderror
                        </div>
                        
                        <div class="mt-1">
                            <p class="text-xs text-gray-500">
                                Último kilometraje registrado: <span class="font-semibold text-gray-700">{{ $ultimo_km }}</span>
                            </p>
                        </div>
                    </div>
                @endif

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Fecha</label>
                    <input type="date" wire:model="fecha"
                        class="w-full rounded-md shadow-sm border-gray-300 focus:border-indigo-500 focus:ring focus:ring-indigo-200"
                        required>
                </div>

                <div class="form-group" wire:ignore>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Fotos del incidente</label>
                    <div id="dropzoneFotos"
                        class="dropzone transition duration-150 ease-in-out hover:border-indigo-400">
                        <div class="dz-message text-center cursor-pointer">
                            Arrastra las fotos aquí o haz clic para subir
                        </div>
                    </div>
                </div>

                <div class="form-group" wire:ignore>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Chofer(es)</label>
                    <div class="mt-1">
                        <select id="choferes" class="w-full select2 js-choferes" multiple="multiple"
                            style="width:100%;">
                            @foreach ($choferesList as $chofer)
                                <option value="{{ $chofer->id }}">{{ $chofer->name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                @error('choferes_seleccionados')
                    <span class="text-xs text-red-600">Debe seleccionar al menos un chofer.</span>
                @enderror

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Observaciones</label>
                    <input type="text" wire:model="observaciones"
                        class="w-full rounded-md shadow-sm border-gray-300 focus:border-indigo-500 focus:ring focus:ring-indigo-200">
                </div>

                <div class="flex justify-end space-x-3 pt-4 border-t border-gray-100">
                    <button type="submit"
                        class="px-4 py-2 border border-transparent rounded-md text-sm font-medium text-white bg-indigo-600 hover:bg-indigo-700 shadow-sm transition">
                        Guardar Registro
                    </button>
                </div>

            </form>
        </div>
    </div>

    <script>
        document.addEventListener('livewire:navigated', () => {
            // 1. Inicializar Select2 de Choferes
            $('#choferes').select2();

            // Sincronizar cambios de Select2 con la propiedad de Livewire
            $('#choferes').on('change', function(e) {
                let data = $(this).val();
                @this.set('choferes_seleccionados', data);
            });

            // 2. Inicializar Dropzone controlando duplicaciones
            if (!Dropzone.instances.length) {
                new Dropzone("#dropzoneFotos", {
                    url: "{{ route('api.fotos.temp') }}",
                    headers: {
                        'X-CSRF-TOKEN': "{{ csrf_token() }}"
                    },
                    maxFilesize: 2,
                    acceptedFiles: "image/*",
                    success: function(file, response) {
                        if(response.success) {
                            // Empuja la ruta temporal devuelta por la API al array de Livewire
                            @this.push('fotos_temporales', response.path);
                        }
                    }
                });
            }
        });
    </script>
</div>
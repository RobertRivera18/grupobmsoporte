<x-app-layout>
    @push('css')
        <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0/dist/js/select2.min.css" rel="stylesheet" />
        <style>
            /* Estilos básicos para adaptar Select2 al diseño */
            .select2-container--default .select2-selection--multiple {
                border-color: #d1d5db !important;
                border-radius: 0.375rem !important;
                padding: 0.15rem !important;
            }

            .dark .select2-container--default .select2-selection--multiple {
                border-color: #374151 !important;
                background-color: #111827 !important;
            }

            .dark .select2-dropdown {
                background-color: #1f2937 !important;
                border-color: #374151 !important;
                color: #f3f4f6 !important;
            }

            .dark .select2-container--default .select2-selection--multiple .select2-selection__choice {
                background-color: #374151 !important;
                border-color: #4b5563 !important;
                color: #f3f4f6 !important;
            }
        </style>
    @endpush

    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Registrar Incidente de Vehículo') }}
        </h2>
    </x-slot>

    <div class="py-12 bg-gray-50 dark:bg-gray-900 min-h-screen">
        <div class="mb-3">
            <a href="{{ route('incidentesvehiculos.index') }}"
                class="inline-flex items-center text-xs font-medium text-gray-500 hover:text-gray-800 transition-colors">
                <i class="fas fa-arrow-left mr-1.5 text-[10px]"></i> Volver al historial
            </a>
        </div>
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div
                class="bg-white dark:bg-gray-800 shadow-sm rounded-xl border border-gray-100 dark:border-gray-700/50 overflow-hidden">

                <div class="border-b border-gray-100 dark:border-gray-700/60 p-6 bg-gray-50/50 dark:bg-gray-800/50">
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Formulario de Control de Movimientos
                    </h3>
                    <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">Registre los datos correspondientes al
                        ingreso o salida vehicular.</p>
                </div>

                <form id="formIncidente" action="{{ route('incidentesvehiculos.store') }}" method="POST"
                    autocomplete="off" enctype="multipart/form-data" class="p-6 space-y-8">
                    @csrf

                    {{-- ID del vehículo --}}
                    <input type="hidden" name="vehiculo_id" id="vehiculo_id" value="{{ old('vehiculo_id') }}">

                    <div class="space-y-4">
                        <div class="flex items-center space-x-2 border-b border-gray-100 dark:border-gray-700/40 pb-2">
                            <span
                                class="flex items-center justify-center h-6 w-6 rounded-md bg-indigo-50 dark:bg-indigo-900/40 text-indigo-600 dark:text-indigo-400 text-xs font-bold">1</span>
                            <h4 class="text-sm font-medium text-gray-900 dark:text-gray-100 uppercase tracking-wider">
                                Identificación del Vehículo</h4>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 items-start">
                            <div class="md:col-span-2">
                                @livewire('vehiculo-buscador')
                                @error('vehiculo_id')
                                    <span
                                        class="text-sm text-red-600 dark:text-red-400 mt-1 block font-medium">{{ $message }}</span>
                                @enderror
                            </div>

                            <div id="contenedorDatosVehiculo"
                                class="hidden p-3 bg-indigo-50/50 dark:bg-indigo-950/30 border border-indigo-100 dark:border-indigo-900/50 rounded-lg h-[42px] flex items-center md:mt-[28px]">
                                <p
                                    class="text-xs text-indigo-950 dark:text-indigo-300 flex items-center w-full truncate">
                                    <svg class="w-4 h-4 mr-1.5 text-indigo-500 shrink-0" fill="none"
                                        stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-12 9 9 9 0 0112-9z"></path>
                                    </svg>
                                    <span id="lblModelo" class="font-semibold truncate"></span>
                                    <span class="mx-1.5 text-indigo-300 dark:text-indigo-700">|</span>
                                    <span class="text-gray-500 dark:text-gray-400 mr-1">Color:</span>
                                    <strong id="lblColor"
                                        class="font-medium text-gray-800 dark:text-gray-200"></strong>
                                </p>
                            </div>
                        </div>
                    </div>

                    <div class="space-y-4">
                        <div class="flex items-center space-x-2 border-b border-gray-100 dark:border-gray-700/40 pb-2">
                            <span
                                class="flex items-center justify-center h-6 w-6 rounded-md bg-indigo-50 dark:bg-indigo-900/40 text-indigo-600 dark:text-indigo-400 text-xs font-bold">2</span>
                            <h4 class="text-sm font-medium text-gray-900 dark:text-gray-100 uppercase tracking-wider">
                                Datos del Reporte</h4>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Tipo de
                                    Registro</label>
                                <div class="grid grid-cols-2 gap-3">
                                    <label
                                        class="relative flex items-center justify-center p-3 rounded-lg border border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-900 cursor-pointer shadow-sm focus-within:ring-2 focus-within:ring-indigo-500 hover:bg-gray-50 dark:hover:bg-gray-800 transition">
                                        <input type="radio" name="tipo" value="1"
                                            {{ old('tipo', '1') == 1 ? 'checked' : '' }} class="sr-only peer">
                                        <span
                                            class="text-sm font-medium text-gray-700 dark:text-gray-300 peer-checked:text-indigo-600 dark:peer-checked:text-indigo-400">⚡
                                            Ingreso</span>
                                        <div
                                            class="absolute -inset-px rounded-lg border-2 border-transparent peer-checked:border-indigo-600 dark:peer-checked:border-indigo-500 pointer-events-none">
                                        </div>
                                    </label>

                                    <label
                                        class="relative flex items-center justify-center p-3 rounded-lg border border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-900 cursor-pointer shadow-sm focus-within:ring-2 focus-within:ring-indigo-500 hover:bg-gray-50 dark:hover:bg-gray-800 transition">
                                        <input type="radio" name="tipo" value="2"
                                            {{ old('tipo') == 2 ? 'checked' : '' }} class="sr-only peer">
                                        <span
                                            class="text-sm font-medium text-gray-700 dark:text-gray-300 peer-checked:text-indigo-600 dark:peer-checked:text-indigo-400">↗️
                                            Salida</span>
                                        <div
                                            class="absolute -inset-px rounded-lg border-2 border-transparent peer-checked:border-indigo-600 dark:peer-checked:border-indigo-500 pointer-events-none">
                                        </div>
                                    </label>
                                </div>
                                @error('tipo')
                                    <span
                                        class="text-sm text-red-600 dark:text-red-400 mt-1 block font-medium">{{ $message }}</span>
                                @enderror
                            </div>

                            <div>
                                <label for="kilometraje"
                                    class="block text-sm font-medium text-gray-700 dark:text-gray-300">Kilometraje
                                    Actual</label>
                                <div class="mt-1 relative rounded-md shadow-sm">
                                    <input type="number" name="kilometraje" id="kilometraje"
                                        value="{{ old('kilometraje') }}"
                                        class="block w-full rounded-lg border-gray-300 dark:border-gray-700 dark:bg-gray-900 text-gray-900 dark:text-gray-100 focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm disabled:opacity-60 transition"
                                        readonly placeholder="Busque un vehículo primero">
                                </div>
                                @error('kilometraje')
                                    <span
                                        class="text-sm text-red-600 dark:text-red-400 mt-1 block font-medium">{{ $message }}</span>
                                @enderror
                            </div>

                            <div class="md:col-span-2">
                                <label for="choferes"
                                    class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                                    Choferes Asignados / Involucrados
                                </label>
                                <select name="choferes[]" id="choferes" class="select2-multiple block w-full"
                                    multiple="multiple" style="width: 100%">
                                    @foreach ($choferes as $chofer)
                                        <option value="{{ $chofer->id }}"
                                            {{ in_array($chofer->id, old('choferes', [])) ? 'selected' : '' }}>
                                            {{ $chofer->name }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('choferes')
                                    <span
                                        class="text-sm text-red-600 dark:text-red-400 mt-1 block font-medium">{{ $message }}</span>
                                @enderror
                            </div>

                            <div class="md:col-span-2">
                                <label for="observaciones"
                                    class="block text-sm font-medium text-gray-700 dark:text-gray-300">Observaciones /
                                    Detalles</label>
                                <textarea name="observaciones" id="observaciones" rows="3"
                                    class="mt-1 block w-full rounded-lg border-gray-300 dark:border-gray-700 dark:bg-gray-900 text-gray-900 dark:text-gray-100 focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm placeholder-gray-400 dark:placeholder-gray-500 resize-none"
                                    placeholder="Escriba los pormenores detectados en la unidad..."></textarea>
                                @error('observaciones')
                                    <span
                                        class="text-sm text-red-600 dark:text-red-400 mt-1 block font-medium">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <div class="space-y-4">
                        <div class="flex items-center space-x-2 border-b border-gray-100 dark:border-gray-700/40 pb-2">
                            <span
                                class="flex items-center justify-center h-6 w-6 rounded-md bg-indigo-50 dark:bg-indigo-900/40 text-indigo-600 dark:text-indigo-400 text-xs font-bold">3</span>
                            <h4 class="text-sm font-medium text-gray-900 dark:text-gray-100 uppercase tracking-wider">
                                Evidencia Multimedia</h4>
                        </div>

                        <div class="md:col-span-2">
                            <div id="dropzone"
                                class="relative border-2 border-dashed border-gray-300 dark:border-gray-600 rounded-xl p-8 text-center cursor-pointer hover:border-indigo-500 dark:hover:border-indigo-400 bg-gray-50/50 dark:bg-gray-900/50 hover:bg-indigo-50/10 dark:hover:bg-indigo-950/10 transition duration-200 group">

                                <input type="file" id="inputFotos" name="fotos[]" multiple accept="image/*"
                                    capture="environment"
                                    class="absolute inset-0 w-full h-full opacity-0 cursor-pointer z-10">

                                <div id="dropzonePlaceholder" class="pointer-events-none">
                                    <div
                                        class="mx-auto h-12 w-12 text-gray-400 dark:text-gray-500 mb-3 bg-white dark:bg-gray-800 p-2 rounded-lg shadow-sm border border-gray-100 dark:border-gray-700 group-hover:scale-105 transition-transform">
                                        <svg class="w-full h-full text-indigo-500 dark:text-indigo-400" fill="none"
                                            stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z">
                                            </path>
                                        </svg>
                                    </div>
                                    <p class="text-sm text-gray-600 dark:text-gray-300 font-medium">
                                        <span class="text-indigo-600 dark:text-indigo-400 hover:underline">Haz clic
                                            para capturar o subir fotos</span> o arrastra y suelta
                                    </p>
                                    <p class="text-xs text-gray-400 dark:text-gray-500 mt-1.5">Formatos admitidos: PNG,
                                        JPG, WEBP de hasta 5 MB por archivo</p>
                                </div>
                            </div>

                            <div id="previewContainer"
                                class="mt-4 grid grid-cols-3 sm:grid-cols-4 md:grid-cols-6 gap-3 hidden bg-gray-50 dark:bg-gray-900/30 p-3 rounded-xl border border-gray-100 dark:border-gray-800">
                            </div>

                            @error('fotos')
                                <span
                                    class="text-sm text-red-600 dark:text-red-400 mt-1 block font-medium">{{ $message }}</span>
                            @enderror
                            @error('fotos.*')
                                <span
                                    class="text-sm text-red-600 dark:text-red-400 mt-1 block font-medium">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>

                    <div
                        class="mt-8 pt-5 border-t border-gray-100 dark:border-gray-700/60 flex items-center justify-end space-x-3">
                        <a href="{{ route('incidentesvehiculos.index') }}"
                            class="px-4 py-2.5 border border-gray-300 dark:border-gray-600 rounded-lg text-sm font-medium text-gray-700 dark:text-gray-300 bg-white dark:bg-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600 focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition shadow-sm">
                            Cancelar
                        </a>
                        <button type="submit" id="btnGuardar"
                            class="px-5 py-2.5 bg-indigo-600 hover:bg-indigo-700 dark:bg-indigo-600 dark:hover:bg-indigo-500 text-white font-medium text-sm rounded-lg shadow-sm focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition">
                            Guardar Reporte
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    @push('js')
        <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0/dist/js/select2.min.js"></script>
        <script>
            $(document).ready(function() {
                $('.select2-multiple').select2({
                    placeholder: " Seleccione los choferes",
                    allowClear: true,
                    width: '100%'
                });

                Livewire.on('vehiculoSeleccionado', (data) => {
                    const vehiculo = data.vehiculo;
                    $('#vehiculo_id').val(vehiculo.id);
                    $('#contenedorDatosVehiculo').removeClass('hidden');
                    $('#lblModelo').text(`${vehiculo.marca} ${vehiculo.modelo}`);
                    $('#lblColor').text(vehiculo.color);

                    $('#kilometraje')
                        .val(vehiculo.ultimo_kilometraje)
                        .removeAttr('readonly')
                        .attr('min', vehiculo.ultimo_kilometraje);
                });

                Livewire.on('vehiculoLimpiado', () => {
                    $('#vehiculo_id').val('');
                    $('#contenedorDatosVehiculo').addClass('hidden');
                    $('#lblModelo').text('');
                    $('#lblColor').text('');
                    $('#kilometraje').val('').attr('readonly', true).removeAttr('min');
                });

                // Conservar estado visual si falló la validación previa y el ID ya existía
                if ($('#vehiculo_id').val()) {
                    $('#kilometraje').removeAttr('readonly');
                }
                let archivosSeleccionados = new DataTransfer();

                const inputFotos = document.getElementById('inputFotos');
                const previewContainer = document.getElementById('previewContainer');
                const dropzone = document.getElementById('dropzone');

                function renderizarPreviews() {
                    previewContainer.innerHTML = '';
                    if (archivosSeleccionados.files.length === 0) {
                        previewContainer.classList.add('hidden');
                        return;
                    }
                    previewContainer.classList.remove('hidden');

                    Array.from(archivosSeleccionados.files).forEach((file, index) => {
                        const reader = new FileReader();
                        reader.onload = (e) => {
                            const wrapper = document.createElement('div');
                            wrapper.className = 'relative group';
                            wrapper.innerHTML = `
                <img src="${e.target.result}" class="h-20 w-full object-cover rounded-md border border-gray-200 dark:border-gray-600" />
                <button type="button" data-index="${index}"
                    class="btn-eliminar-foto absolute top-0.5 right-0.5 bg-red-600 text-white rounded-full w-5 h-5 text-xs flex items-center justify-center opacity-0 group-hover:opacity-100 transition-opacity">
                    ✕
                </button>
                <p class="text-xs text-gray-500 dark:text-gray-400 mt-0.5 truncate">${file.name}</p>
            `;
                            previewContainer.appendChild(wrapper);
                        };
                        reader.readAsDataURL(file);
                    });
                }

                inputFotos.addEventListener('change', function() {
                    Array.from(this.files).forEach(file => archivosSeleccionados.items.add(file));
                    this.files = archivosSeleccionados.files;
                    renderizarPreviews();
                });

                previewContainer.addEventListener('click', function(e) {
                    const btn = e.target.closest('.btn-eliminar-foto');
                    if (!btn) return;
                    const index = parseInt(btn.dataset.index);
                    const nuevaLista = new DataTransfer();
                    Array.from(archivosSeleccionados.files).forEach((file, i) => {
                        if (i !== index) nuevaLista.items.add(file);
                    });
                    archivosSeleccionados = nuevaLista;
                    inputFotos.files = archivosSeleccionados.files;
                    renderizarPreviews();
                });

                // Drag & Drop
                dropzone.addEventListener('dragover', e => {
                    e.preventDefault();
                    dropzone.classList.add('border-indigo-400');
                });
                dropzone.addEventListener('dragleave', () => dropzone.classList.remove('border-indigo-400'));
                dropzone.addEventListener('drop', e => {
                    e.preventDefault();
                    dropzone.classList.remove('border-indigo-400');
                    Array.from(e.dataTransfer.files).forEach(file => {
                        if (file.type.startsWith('image/')) archivosSeleccionados.items.add(file);
                    });
                    inputFotos.files = archivosSeleccionados.files;
                    renderizarPreviews();
                });
            });
        </script>
    @endpush
</x-app-layout>

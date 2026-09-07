<div class="max-w-7xl mx-auto my-4 sm:my-6 p-4 sm:p-6">

    @if (session()->has('message'))
        <div class="mb-4 p-4 text-sm text-green-700 bg-green-100 rounded-lg" role="alert">
            {{ session('message') }}
        </div>
    @endif

    @error('checklist')
        <div class="mb-4 p-4 text-sm text-red-700 bg-red-100 rounded-lg" role="alert">
            {{ $message }}
        </div>
    @enderror

    <form wire:submit.prevent="guardarInspeccion">

        <div class="border border-gray-400 p-4 mb-6 rounded bg-gray-50">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4 items-center text-center md:text-left">
                <div>
                    <h2 class="text-xs font-bold tracking-wider text-gray-500 uppercase">
                        Departamento de Mantenimiento
                    </h2>
                    <h1 class="text-lg sm:text-xl font-black text-gray-800">INSPECCIÓN DE VEHÍCULOS LIVIANOS</h1>
                </div>
                <div class="text-center">
                    <span class="text-lg font-extrabold text-blue-700 uppercase tracking-wide">Grupo BM</span>
                </div>
                <div class="text-center md:text-right text-xs text-gray-500 font-mono">
                    <p><strong>CÓDIGO:</strong> FOR-MAN-05</p>
                    <p><strong>REV:</strong> 02</p>
                </div>
            </div>
        </div>

        <div class="bg-gray-50 border border-gray-200 rounded-lg p-4 mb-6 grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-5 gap-4">
            <div>
                <label class="block text-xs font-bold text-gray-600 uppercase">Vehículo (Placa)</label>
                <input type="text" value="{{ $vehiculo->placa }}" disabled
                    class="mt-1 block w-full rounded-md border-gray-300 bg-gray-200 font-mono text-sm font-bold text-gray-700 shadow-sm">
            </div>
            <div>
                <label class="block text-xs font-bold text-gray-600 uppercase">Fecha de Inspección</label>
                <input type="date" wire:model="fecha"
                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm text-sm focus:ring-blue-500 focus:border-blue-500">
                @error('fecha')
                    <span class="text-red-500 text-xs">{{ $message }}</span>
                @enderror
            </div>
            <div>
                <label class="block text-xs font-bold text-gray-600 uppercase">Técnico a Cargo</label>
                <select wire:model="tecnico_encargado_id"
                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm text-sm focus:border-indigo-500 focus:ring-indigo-500 @error('tecnico_encargado_id') border-red-500 text-red-900 @enderror">
                    <option value="">Seleccione el chofer/técnico</option>
                    @foreach ($choferes as $chofer)
                        <option value="{{ $chofer->id }}">{{ $chofer->name }}</option>
                    @endforeach
                </select>
                @error('tecnico_encargado_id')
                    <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span>
                @enderror
            </div>
            <div>
                <label class="block text-xs font-bold text-gray-600 uppercase">Kilometraje (KMS)</label>
                <input type="number" wire:model.blur="kilometraje" placeholder="000000"
                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm text-sm font-mono">
                @error('kilometraje')
                    <span class="text-red-500 text-xs">{{ $message }}</span>
                @enderror
            </div>
            <div class="sm:col-span-2 lg:col-span-1">
                <label class="block text-xs font-bold text-gray-600 uppercase mb-2">Tipo de Equipo</label>
                <div class="flex items-center space-x-4 mt-2 text-sm">
                    <label class="inline-flex items-center cursor-pointer">
                        <input type="radio" wire:model="tipo_equipo" value="propio"
                            class="text-blue-600 focus:ring-blue-500">
                        <span class="ml-2 text-gray-700 font-medium">Propio</span>
                    </label>
                    <label class="inline-flex items-center cursor-pointer">
                        <input type="radio" wire:model="tipo_equipo" value="alquilado"
                            class="text-blue-600 focus:ring-blue-500">
                        <span class="ml-2 text-gray-700 font-medium">Alquilado</span>
                    </label>
                </div>
                @error('tipo_equipo')
                    <span class="text-red-500 text-xs">{{ $message }}</span>
                @enderror
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
            @foreach ($checklist as $categoria => $items)
                <div class="border border-gray-200 rounded-lg shadow-sm overflow-hidden" wire:key="cat-{{ $categoria }}">
                    <div class="bg-gray-800 text-white px-4 py-2 text-xs font-bold uppercase tracking-wider">
                        {{ str_replace('_', ' Y ', $categoria) }}
                    </div>

                    <div class="divide-y divide-gray-100 bg-white">
                        @foreach ($items as $item => $valor)
                            <div class="flex flex-col sm:flex-row sm:justify-between sm:items-center gap-2 px-4 py-3 hover:bg-gray-50 transition"
                                wire:key="item-{{ $categoria }}-{{ $item }}">

                                <span class="text-sm text-gray-700 capitalize font-medium pr-2 break-words">
                                    {{ str_replace('_', ' ', $item) }}
                                </span>

                                <div class="flex items-center space-x-4 self-end sm:self-auto">
                                    <label class="inline-flex items-center cursor-pointer select-none">
                                        <input type="radio"
                                            wire:model="checklist.{{ $categoria }}.{{ $item }}"
                                            value="1"
                                            class="w-4 h-4 text-green-600 border-gray-300 focus:ring-green-500 bg-gray-100">
                                        <span class="ml-1.5 text-xs font-bold text-green-700 bg-green-50 px-2 py-0.5 rounded border border-green-2000">
                                            OK
                                        </span>
                                    </label>

                                    <label class="inline-flex items-center cursor-pointer select-none">
                                        <input type="radio"
                                            wire:model="checklist.{{ $categoria }}.{{ $item }}"
                                            value="0"
                                            class="w-4 h-4 text-red-600 border-gray-300 focus:ring-red-500 bg-gray-100">
                                        <span class="ml-1.5 text-xs font-bold text-red-700 bg-red-50 px-2 py-0.5 rounded border border-red-200">
                                            NO OK
                                        </span>
                                    </label>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endforeach
        </div>

        <div class="mt-8 grid grid-cols-1 md:grid-cols-2 gap-6 border-t pt-6">
            <div class="space-y-4">
                <div>
                    <label class="block text-sm font-bold text-gray-700 uppercase mb-1">Choques y Golpes (Detalles)</label>
                    <textarea wire:model.blur="choques_golpes" rows="3" placeholder="Describir abolladuras, raspaduras o golpes..."
                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm text-sm focus:ring-blue-500 focus:border-blue-500"></textarea>
                </div>
                <div>
                    <label class="block text-sm font-bold text-gray-700 uppercase mb-1">Observaciones Generales</label>
                    <textarea wire:model.blur="observaciones" rows="3" placeholder="Novedades adicionales del vehículo..."
                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm text-sm focus:ring-blue-500 focus:border-blue-500"></textarea>
                </div>
            </div>

            <div>
                <label class="block text-sm font-bold text-gray-700 uppercase mb-2">Evidencias Fotográficas (Choques / Estado)</label>

                <div x-data="{ itIsOver: false }" x-on:dragover.prevent="itIsOver = true"
                    x-on:dragleave.prevent="itIsOver = false" x-on:drop.prevent="itIsOver = false"
                    :class="itIsOver ? 'border-blue-500 bg-blue-50' : 'border-gray-300 bg-gray-50'"
                    class="relative flex flex-col items-center justify-center w-full min-h-40 border-2 border-dashed rounded-lg transition-all p-4 text-center">

                    <div class="flex flex-col items-center justify-center pt-4 pb-4">
                        <svg class="w-10 h-10 mb-3 text-gray-400" fill="none" stroke="currentColor"
                            viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12">
                            </path>
                        </svg>
                        <p class="mb-3 text-sm text-gray-500">Selecciona el origen de las fotos</p>

                        <div class="flex flex-col sm:flex-row gap-3">
                            <!-- Botón: Tomar foto con cámara -->
                            <label for="camara-input"
                                class="cursor-pointer inline-flex items-center justify-center gap-2 px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white text-xs font-bold rounded-md shadow-sm transition">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z" />
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M15 13a3 3 0 11-6 0 3 3 0 016 0z" />
                                </svg>
                                Tomar Foto
                            </label>
                            <input id="camara-input" type="file" wire:model="fotos_camara" multiple
                                accept="image/png,image/jpeg" capture="environment" class="hidden">

                            <!-- Botón: Elegir de galería -->
                            <label for="galeria-input"
                                class="cursor-pointer inline-flex items-center justify-center gap-2 px-4 py-2 bg-gray-100 hover:bg-gray-200 text-gray-700 text-xs font-bold rounded-md shadow-sm border transition">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14M14 8h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                </svg>
                                Elegir de Galería
                            </label>
                            <input id="galeria-input" type="file" wire:model="fotos_galeria" multiple
                                accept="image/png,image/jpeg" class="hidden">
                        </div>

                        <p class="text-xs text-gray-400 mt-3">PNG, JPG o JPEG (Máx. 4MB por foto, máx. 10 fotos)</p>
                    </div>
                </div>

                <div wire:loading wire:target="fotos_camara, fotos_galeria" class="mt-2 text-xs text-blue-600 font-semibold flex items-center">
                    <svg class="animate-spin h-4 w-4 mr-2 text-blue-600" fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"></path>
                    </svg>
                    Subiendo imágenes...
                </div>

                @error('fotos_subidas') <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span> @enderror
                @error('fotos_subidas.*') <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span> @enderror
                @error('fotos_camara.*') <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span> @enderror
                @error('fotos_galeria.*') <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span> @enderror

                @if (!empty($fotos_subidas))
                    <div class="grid grid-cols-3 sm:grid-cols-4 gap-2 mt-4 max-h-48 overflow-y-auto p-1 border rounded bg-gray-100">
                        @foreach ($fotos_subidas as $index => $foto)
                            <div class="relative group h-20 bg-gray-200 rounded overflow-hidden shadow-inner border" wire:key="foto-{{ $index }}">
                                @if (method_exists($foto, 'temporaryUrl'))
                                    <img src="{{ $foto->temporaryUrl() }}" class="w-full h-full object-cover">
                                @endif

                                <button type="button" wire:click="removerFoto({{ $index }})"
                                    class="absolute top-1 right-1 bg-red-600 text-white rounded-full p-1 shadow hover:bg-red-700 transition opacity-90 sm:opacity-0 group-hover:opacity-100">
                                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                    </svg>
                                </button>
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>
        </div>

        <div class="mt-8 flex flex-col-reverse sm:flex-row sm:justify-end gap-3 sm:gap-0">
            <a href="{{ route('admin.vehiculos.index') }}"
                class="sm:mr-4 px-5 py-2.5 bg-gray-100 hover:bg-gray-200 text-gray-700 text-sm font-medium rounded-md transition border text-center common-btn">
                Cancelar
            </a>
            <button type="submit" wire:loading.attr="disabled"
                class="px-6 py-2.5 bg-blue-600 hover:bg-blue-700 text-white text-sm font-bold rounded-md shadow-sm transition inline-flex items-center justify-center common-btn">
                <span wire:loading wire:target="guardarInspeccion" class="mr-2">
                    <svg class="animate-spin h-4 w-4 text-white" fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"></path>
                    </svg>
                </span>
                Guardar Inspección
            </button>
        </div>
    </form>
</div>
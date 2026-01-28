<x-admin-layout :breadcrumbs="[
    ['name' => 'Home', 'url' => route('admin.dashboard')],
    ['name' => 'Indumentaria', 'url' => route('admin.indumentarias.index')],
    ['name' => 'Editar'],
]">

    @php
        $sinCaracteristicas = empty($indumentaria->color) && empty($indumentaria->talla);
    @endphp

    <div class="max-w-3xl mx-auto bg-white shadow rounded-lg p-6">

        <h2 class="text-xl font-bold text-gray-800 mb-6 flex items-center gap-2">
            ✏️ Editar Indumentaria
        </h2>

        <form action="{{ route('admin.indumentarias.update', $indumentaria) }}"
              method="POST"
              enctype="multipart/form-data"
              class="space-y-6">
            @csrf
            @method('PUT')

            {{-- NOMBRE --}}
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">
                    Nombre
                </label>
                <input type="text"
                       name="nombre"
                       value="{{ old('nombre', $indumentaria->nombre) }}"
                       class="w-full rounded-lg border-gray-300 focus:ring-blue-500 focus:border-blue-500">
                @error('nombre')
                    <span class="text-red-500 text-sm">{{ $message }}</span>
                @enderror
            </div>

            {{-- TIPO --}}
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">
                    Tipo
                </label>
                <select name="tipo"
                        class="w-full rounded-lg border-gray-300 focus:ring-blue-500 focus:border-blue-500">
                    <option value="">Seleccione un tipo</option>
                    @foreach (['camisas','pantalon','casco'] as $tipo)
                        <option value="{{ $tipo }}"
                            {{ old('tipo', $indumentaria->tipo) === $tipo ? 'selected' : '' }}>
                            {{ ucfirst($tipo) }}
                        </option>
                    @endforeach
                </select>
                @error('tipo')
                    <span class="text-red-500 text-sm">{{ $message }}</span>
                @enderror
            </div>

            {{-- 🎨 CARACTERÍSTICAS --}}
            <div
                x-data="{ mostrar: {{ $sinCaracteristicas ? 'false' : 'true' }} }"
                class="space-y-4"
            >

                {{-- BOTÓN SOLO SI NO EXISTEN --}}
                @if ($sinCaracteristicas)
                    <button type="button"
                        @click="mostrar = true"
                        class="text-sm text-blue-600 hover:underline flex items-center gap-2">
                        ➕ Añadir características
                    </button>
                @endif

                {{-- COLOR + TALLA --}}
                <div x-show="mostrar" x-transition class="space-y-6">

                    {{-- COLOR --}}
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">
                            Color
                        </label>
                        <input type="text"
                               name="color"
                               value="{{ old('color', $indumentaria->color) }}"
                               class="w-full rounded-lg border-gray-300 focus:ring-blue-500 focus:border-blue-500"
                               placeholder="Ej: Rojo, Negro">
                    </div>

                    {{-- TALLA --}}
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">
                            Talla
                        </label>
                        <select name="talla"
                                class="w-full rounded-lg border-gray-300 focus:ring-blue-500 focus:border-blue-500">
                            <option value="">Seleccione talla</option>
                            @foreach (['XS','S','M','L','XL','XXL'] as $talla)
                                <option value="{{ $talla }}"
                                    {{ old('talla', $indumentaria->talla) === $talla ? 'selected' : '' }}>
                                    {{ $talla }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                </div>
            </div>

            {{-- 🖼️ IMAGEN --}}
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">
                    Imagen de la indumentaria
                </label>

                <div class="flex items-center gap-6">
                    <div class="w-32 h-32 border-2 border-dashed rounded-lg flex items-center justify-center bg-gray-50">
                        <img id="preview-image"
                             src="{{ $indumentaria->image ? asset('storage/'.$indumentaria->image) : '' }}"
                             class="{{ $indumentaria->image ? '' : 'hidden' }} w-full h-full object-cover rounded-lg">
                        <span id="placeholder-text"
                              class="{{ $indumentaria->image ? 'hidden' : '' }} text-xs text-gray-400">
                            Sin imagen
                        </span>
                    </div>

                    <input type="file"
                           name="image"
                           accept="image/*"
                           onchange="previewImage(event)"
                           class="block w-full text-sm text-gray-700">
                </div>
            </div>

            {{-- 📦 BODEGAS --}}
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">
                    Bodegas donde existe la indumentaria
                </label>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                    @foreach ($ubicaciones as $ubicacion)
                        <label class="flex items-center gap-2 p-3 border rounded-lg cursor-pointer">
                            <input type="checkbox"
                                   name="ubicaciones[]"
                                   value="{{ $ubicacion->id }}"
                                   {{ in_array(
                                        $ubicacion->id,
                                        old(
                                            'ubicaciones',
                                            $indumentaria->inventarios->pluck('ubicacion_id')->toArray()
                                        )
                                   ) ? 'checked' : '' }}>
                            <span>{{ $ubicacion->nombre }}</span>
                        </label>
                    @endforeach
                </div>
            </div>

            {{-- BOTONES --}}
            <div class="flex justify-end gap-3 pt-4">
                <a href="{{ route('admin.indumentarias.index') }}"
                   class="px-4 py-2 bg-gray-200 rounded-lg">
                    Cancelar
                </a>

                <button type="submit"
                        class="px-4 py-2 bg-blue-600 text-white rounded-lg">
                    Actualizar
                </button>
            </div>

        </form>
    </div>

    {{-- Script preview imagen --}}
    <script>
        function previewImage(event) {
            const preview = document.getElementById('preview-image');
            const placeholder = document.getElementById('placeholder-text');

            preview.src = URL.createObjectURL(event.target.files[0]);
            preview.classList.remove('hidden');
            placeholder.classList.add('hidden');
        }
    </script>

</x-admin-layout>

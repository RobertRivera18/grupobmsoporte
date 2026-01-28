<x-admin-layout :breadcrumbs="[
    ['name' => 'Home', 'url' => route('admin.dashboard')],
    ['name' => 'Indumentaria', 'url' => route('admin.indumentarias.index')],
    ['name' => 'Nuevo'],
]">

<div class="max-w-3xl mx-auto bg-white shadow rounded-lg p-6">

    <h2 class="text-xl font-bold text-gray-800 mb-6 flex items-center gap-2">
        👕 Registrar Nueva Indumentaria
    </h2>

    <form
        x-data="{
            tipo: '{{ old('tipo') }}',
            talla: '{{ old('talla') }}',

            tallasCamisa: ['XS','S','M','L','XL','XXL'],
            tallasPantalon: ['28','34','36','38','40','42','44'],

            get tallas() {
                if (this.tipo === 'camisas') return this.tallasCamisa
                if (this.tipo === 'pantalon') return this.tallasPantalon
                return []
            }
        }"
        action="{{ route('admin.indumentarias.store') }}"
        method="POST"
        enctype="multipart/form-data"
        class="space-y-6"
    >
        @csrf

        {{-- NOMBRE --}}
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Nombre</label>
            <input type="text"
                   name="nombre"
                   value="{{ old('nombre') }}"
                   class="w-full rounded-lg border-gray-300 focus:ring-blue-500 focus:border-blue-500"
                   placeholder="Ej: Camisa, Pantalón, Botas">
            @error('nombre')
                <span class="text-red-500 text-sm">{{ $message }}</span>
            @enderror
        </div>

        {{-- TIPO --}}
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Tipo</label>
            <select
                name="tipo"
                x-model="tipo"
                @change="talla = ''"
                class="w-full rounded-lg border-gray-300 focus:ring-blue-500 focus:border-blue-500"
            >
                <option value="">Seleccione un tipo</option>
                <option value="camisas">Camisas</option>
                <option value="pantalon">Pantalón</option>
                <option value="casco">Casco</option>
            </select>
            @error('tipo')
                <span class="text-red-500 text-sm">{{ $message }}</span>
            @enderror
        </div>

        {{-- COLOR --}}
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Color</label>
            <input type="text"
                   name="color"
                   value="{{ old('color') }}"
                   class="w-full rounded-lg border-gray-300 focus:ring-blue-500 focus:border-blue-500"
                   placeholder="Ej: Rojo, Azul, Negro">
            @error('color')
                <span class="text-red-500 text-sm">{{ $message }}</span>
            @enderror
        </div>

        {{-- TALLA DINÁMICA --}}
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Talla</label>

            <select
                name="talla"
                x-model="talla"
                :disabled="tallas.length === 0"
                class="w-full rounded-lg border-gray-300 focus:ring-blue-500 focus:border-blue-500 disabled:bg-gray-100"
            >
                <option value="">Seleccione talla</option>

                <template x-for="opcion in tallas" :key="opcion">
                    <option :value="opcion" x-text="opcion"></option>
                </template>
            </select>

            @error('talla')
                <span class="text-red-500 text-sm">{{ $message }}</span>
            @enderror
        </div>

        {{-- 🖼️ IMAGEN --}}
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-2">
                Imagen de la indumentaria
            </label>

            <div class="flex items-center gap-6">
                <div class="w-32 h-32 border-2 border-dashed rounded-lg flex items-center justify-center bg-gray-50">
                    <img id="preview-image"
                         class="hidden w-full h-full object-cover rounded-lg">
                    <span id="placeholder-text" class="text-xs text-gray-400">
                        Sin imagen
                    </span>
                </div>

                <input type="file"
                       name="image"
                       accept="image/*"
                       onchange="previewImage(event)"
                       class="block w-full text-sm text-gray-700
                              file:mr-4 file:py-2 file:px-4
                              file:rounded-lg file:border-0
                              file:text-sm file:font-semibold
                              file:bg-blue-50 file:text-blue-700
                              hover:file:bg-blue-100">
            </div>

            @error('image')
                <span class="text-red-500 text-sm">{{ $message }}</span>
            @enderror
        </div>

        {{-- 📦 BODEGAS --}}
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-2">
                Bodegas donde existirá la indumentaria
            </label>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                @foreach ($ubicaciones as $ubicacion)
                    <label class="flex items-center gap-2 p-3 border rounded-lg cursor-pointer hover:bg-gray-50">
                        <input type="checkbox"
                               name="ubicaciones[]"
                               value="{{ $ubicacion->id }}"
                               {{ in_array($ubicacion->id, old('ubicaciones', [])) ? 'checked' : '' }}>
                        <span>{{ $ubicacion->nombre }}</span>
                    </label>
                @endforeach
            </div>

            @error('ubicaciones')
                <span class="text-red-500 text-sm">{{ $message }}</span>
            @enderror
        </div>

        {{-- BOTONES --}}
        <div class="flex justify-end gap-3 pt-4">
            <a href="{{ route('admin.indumentarias.index') }}"
               class="px-4 py-2 bg-gray-200 rounded-lg">
                Cancelar
            </a>

            <button type="submit"
                    class="px-4 py-2 bg-blue-600 text-white rounded-lg">
                Guardar
            </button>
        </div>

    </form>
</div>

<script>
function previewImage(event) {
    const preview = document.getElementById('preview-image');
    const placeholder = document.getElementById('placeholder-text');
    const file = event.target.files[0];

    if (file) {
        preview.src = URL.createObjectURL(file);
        preview.classList.remove('hidden');
        placeholder.classList.add('hidden');
    }
}
</script>

</x-admin-layout>

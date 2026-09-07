<x-admin-layout :breadcrumbs="[
    ['name' => 'Home', 'url' => route('admin.dashboard')],
    ['name' => 'Indumentaria', 'url' => route('admin.indumentarias.index')],
    ['name' => 'Editar'],
]">

    <div class="max-w-4xl mx-auto bg-white shadow-xl rounded-2xl p-8">

        <h2 class="text-2xl font-bold text-gray-800 mb-8 flex items-center gap-2">
            ✏️ Editar Indumentaria
        </h2>

        <form x-data="formIndumentariaEdit()" action="{{ route('admin.indumentarias.update', $indumentaria) }}" method="POST"
            enctype="multipart/form-data" class="space-y-6">
            @csrf
            @method('PUT')

            {{-- ================= DATOS GENERALES ================= --}}
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

                {{-- NOMBRE --}}
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1">
                        Nombre *
                    </label>
                    <input type="text" name="nombre" value="{{ old('nombre', $indumentaria->nombre) }}"
                        class="w-full rounded-xl border-gray-300 focus:ring-blue-500 focus:border-blue-500">

                    @error('nombre')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                {{-- TIPO --}}
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1">
                        Tipo *
                    </label>

                    <select name="tipo" x-model="tipo"
                        class="w-full rounded-xl border-gray-300 focus:ring-blue-500 focus:border-blue-500">

                        <option value="">Seleccione un tipo</option>
                        <option value="camisas">Camisas</option>
                        <option value="buzos">Buzos</option>
                        <option value="pantalon">Pantalón</option>
                        <option value="casco">Casco</option>
                        <option value="gorra">Gorra</option>
                        <option value="camisetas">Camiseta</option>
                        <option value="chaleco">Chaleco</option>
                        <option value="impermeable">Impermeable</option>
                        <option value="bolso">Bolso</option>
                        <option value="lonchera">Lonchera</option>
                        <option value="medias">Medias</option>
                        <option value="chompa">Chompa</option>
                    </select>

                    @error('tipo')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                {{-- COLOR --}}
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1">
                        Color
                    </label>
                    <input type="text" name="color" value="{{ old('color', $indumentaria->color) }}"
                        class="w-full rounded-xl border-gray-300">
                </div>

                {{-- TALLA DINÁMICA --}}
                <div>
                    @if ($indumentaria->talla)
                        <div class="mb-2 flex items-center gap-2"> <span
                                class="inline-flex items-center px-3 py-1 text-xs font-semibold rounded-full bg-blue-100 text-blue-700">
                                {{ $indumentaria->talla }} </span> <span class="text-xs text-gray-500"> Si deseas
                                actualizarla, selecciona abajo </span> </div>
                    @endif
                    <label class="block text-sm font-semibold text-gray-700 mb-1">
                        Talla
                    </label>

                    <select name="talla" x-model="talla" :disabled="tallas.length === 0"
                        class="w-full rounded-xl border-gray-300">

                        <option value="">Seleccione talla</option>

                        <template x-for="opcion in tallas" :key="opcion">
                            <option :value="opcion" x-text="opcion"></option>
                        </template>
                    </select>
                </div>

            </div>

            {{-- ================= IMAGEN ================= --}}
            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-3">
                    Imagen
                </label>

                <div class="flex items-center gap-6">

                    <div
                        class="w-40 h-40 border-2 border-dashed rounded-xl overflow-hidden bg-gray-50 flex items-center justify-center">

                        <img x-show="imageUrl" :src="imageUrl" class="w-full h-full object-cover">

                        <span x-show="!imageUrl" class="text-gray-400 text-sm">
                            Sin imagen
                        </span>
                    </div>

                    <div>
                        <input type="file" name="image" @change="previewImage"
                            class="block w-full text-sm text-gray-500">

                        <p class="text-xs text-gray-400 mt-1">
                            JPG, PNG o WEBP (máx. 2MB)
                        </p>
                    </div>
                </div>

                @error('image')
                    <p class="text-red-500 text-xs mt-2">{{ $message }}</p>
                @enderror
            </div>

            {{-- ================= INVENTARIO POR BODEGA ================= --}}
            <div>
                <h3 class="text-lg font-semibold text-gray-700 mb-4">
                    📦 Inventario por Bodega
                </h3>

                <div class="space-y-4">

                    @foreach ($ubicaciones as $ubicacion)
                        @php
                            $inventarioNuevo = $indumentaria->inventarios->firstWhere('ubicacion_id', $ubicacion->id);

                            $inventarioUsado = $indumentaria->inventariosUsados->firstWhere(
                                'ubicacion_id',
                                $ubicacion->id,
                            );

                            $ubicacionesActuales = $indumentaria->inventarios->pluck('ubicacion_id')->toArray();
                        @endphp

                        <div class="border rounded-xl p-4">

                            {{-- CHECKBOX --}}
                            <div class="flex items-center gap-3 mb-3">
                                <input type="checkbox" name="ubicaciones[]" value="{{ $ubicacion->id }}"
                                    x-model="ubicacionesSeleccionadas" class="rounded"
                                    {{ in_array($ubicacion->id, old('ubicaciones', $ubicacionesActuales)) ? 'checked' : '' }}>

                                <span class="font-medium">
                                    {{ $ubicacion->nombre }}
                                </span>
                            </div>

                            @role('Admin')
                                <div x-show="ubicacionesSeleccionadas.includes('{{ $ubicacion->id }}')"
                                    class="grid grid-cols-2 gap-4">

                                    {{-- STOCK NUEVO --}}
                                    <div>
                                        <label class="text-xs text-gray-500">
                                            Stock Nuevo
                                        </label>
                                        <input type="number" min="0" name="stock_nuevo[{{ $ubicacion->id }}]"
                                            value="{{ old('stock_nuevo.' . $ubicacion->id, $inventarioNuevo->stock ?? 0) }}"
                                            class="w-full rounded-xl border-gray-300">
                                    </div>

                                    {{-- STOCK USADO --}}
                                    <div>
                                        <label class="text-xs text-gray-500">
                                            Stock Usado
                                        </label>
                                        <input type="number" min="0" name="stock_usado[{{ $ubicacion->id }}]"
                                            value="{{ old('stock_usado.' . $ubicacion->id, $inventarioUsado->stock ?? 0) }}"
                                            class="w-full rounded-xl border-gray-300">
                                    </div>

                                </div>
                            @endrole

                        </div>
                    @endforeach

                </div>

                @error('ubicaciones')
                    <p class="text-red-500 text-xs mt-2">{{ $message }}</p>
                @enderror
            </div>

            {{-- ================= BOTONES ================= --}}
            <div class="flex justify-end gap-4 pt-6 border-t">

                <a href="{{ route('admin.indumentarias.index') }}"
                    class="px-5 py-2 bg-gray-200 hover:bg-gray-300 rounded-xl transition">
                    Cancelar
                </a>

                <button type="submit"
                    class="px-6 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-xl shadow transition">
                    Actualizar Indumentaria
                </button>
            </div>

        </form>
    </div>

    {{-- ================= ALPINE COMPONENT ================= --}}
    <script>
        function formIndumentariaEdit() {
            return {
                tipo: '{{ old('tipo', $indumentaria->tipo) }}',
                talla: '{{ old('talla', $indumentaria->talla) }}',
                imageUrl: '{{ $indumentaria->image ? asset($indumentaria->image) : '' }}',

                ubicacionesSeleccionadas: @json(old('ubicaciones', $indumentaria->inventarios->pluck('ubicacion_id'))),

                tallasCamisa: ['XS', 'S', 'M', 'L', 'XL', 'XXL', 'XXXL'],
                tallasPantalon: ['28', '34', '36', '38', '40', '42', '44'],

                get tallas() {
                    if (
                        this.tipo === 'camisas' ||
                        this.tipo === 'buzos' ||
                        this.tipo === 'camisetas' ||
                        this.tipo === 'impermeable' ||
                        this.tipo === 'chompa' ||
                        this.tipo === 'chaleco'
                    ) {
                        return this.tallasCamisa
                    }

                    if (this.tipo === 'pantalon') {
                        return [
                            ...this.tallasCamisa,
                            ...this.tallasPantalon
                        ]
                    }

                    return []
                },

                previewImage(event) {
                    const file = event.target.files[0]
                    if (file) {
                        this.imageUrl = URL.createObjectURL(file)
                    }
                }
            }
        }
    </script>

</x-admin-layout>

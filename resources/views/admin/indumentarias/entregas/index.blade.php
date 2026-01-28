<x-admin-layout>
    @push('css')
    
    @endpush
    {{-- TOM SELECT --}}

    <div class="max-w-6xl mx-auto space-y-6">

        {{-- FORMULARIO --}}
        <div class="bg-white shadow rounded-lg p-6">
            <h2 class="font-bold text-lg mb-4">📦 Nueva entrega de indumentaria</h2>

            <form method="POST" action="{{ route('admin.indumentarias.entregas.store') }}">
                @csrf

                {{-- CABECERA --}}
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">

                    {{-- EMPLEADO --}}
                    <div>
                        @livewire('empleado-buscador')
                    </div>

                    {{-- BODEGA --}}
                    <select name="ubicacion_id" class="rounded border-gray-300" required>
                        <option value="">🏭 Bodega</option>
                        @foreach ($ubicaciones as $u)
                            <option value="{{ $u->id }}">{{ $u->nombre }}</option>
                        @endforeach
                    </select>

                    {{-- FECHA --}}
                    <input type="date" name="fecha_entrega" class="rounded border-gray-300"
                        value="{{ date('Y-m-d') }}" required>
                </div>

                {{-- DETALLE --}}
                <div id="items" class="space-y-3">

                    {{-- FILA BASE --}}
                    <div class="grid grid-cols-1 md:grid-cols-5 gap-3 items-center item-row">

                        {{-- INDUMENTARIA --}}
                        <select name="indumentarias[0][id]" class="rounded border-gray-300 indumentaria-select"
                            required>
                            <option value="">👕 Buscar indumentaria...</option>
                            @foreach ($indumentarias as $i)
                                <option value="{{ $i->id }}">
                                    {{ $i->nombre }}
                                    @if ($i->talla)
                                        - {{ $i->talla }}
                                    @endif
                                </option>
                            @endforeach
                        </select>

                        {{-- TIPO --}}
                        <select name="indumentarias[0][tipo]" class="rounded border-gray-300" required>
                            <option value="">📦 Tipo</option>
                            <option value="nuevo">🆕 Nuevo</option>
                            <option value="usado">♻️ Usado</option>
                        </select>

                        {{-- CANTIDAD --}}
                        <input type="number" name="indumentarias[0][cantidad]" min="1"
                            class="rounded border-gray-300" placeholder="Cantidad" required>

                        <div></div>
                    </div>
                </div>

                {{-- AGREGAR ITEM --}}
                <button type="button" onclick="addItem()" class="text-blue-600 text-sm mt-4 flex items-center gap-1">
                    ➕ Agregar otra indumentaria
                </button>

                {{-- OBSERVACIÓN --}}
                <div class="mt-4">
                    <textarea name="observacion" rows="2" class="w-full rounded border-gray-300" placeholder="Observación (opcional)"></textarea>
                </div>

                {{-- SUBMIT --}}
                <div class="mt-6 flex justify-end">
                    <button class="bg-red-600 hover:bg-red-700 text-white px-6 py-2 rounded-lg">
                        💾 Registrar entrega
                    </button>
                </div>

            </form>
        </div>

        {{-- HISTORIAL --}}
        @livewire('entrega-indumentarias-index')

    </div>

    {{-- JS --}}
    @push('js')
        <script>
            let index = 1;

            function initIndumentariaSelect(el) {
                new TomSelect(el, {
                    placeholder: '🔍 Buscar indumentaria...',
                    allowEmptyOption: true,
                    searchField: ['text'],
                    maxOptions: 100,
                });
            }

            // inicializar el primero
            document.querySelectorAll('.indumentaria-select').forEach(initIndumentariaSelect);

            function addItem() {
                const html = `
                <div class="grid grid-cols-1 md:grid-cols-5 gap-3 items-center item-row mt-2">

                    <select name="indumentarias[${index}][id]"
                            class="rounded border-gray-300 indumentaria-select"
                            required>
                        <option value="">👕 Buscar indumentaria...</option>
                        @foreach ($indumentarias as $i)
                            <option value="{{ $i->id }}">
                                {{ $i->nombre }} @if ($i->talla) - {{ $i->talla }} @endif
                            </option>
                        @endforeach
                    </select>

                    <select name="indumentarias[${index}][tipo]"
                            class="rounded border-gray-300"
                            required>
                        <option value="">📦 Tipo</option>
                        <option value="nuevo">🆕 Nuevo</option>
                        <option value="usado">♻️ Usado</option>
                    </select>

                    <input type="number"
                           name="indumentarias[${index}][cantidad]"
                           min="1"
                           class="rounded border-gray-300"
                           placeholder="Cantidad"
                           required>

                    <button type="button"
                            onclick="removeItem(this)"
                            class="text-red-600 text-xl">
                        🗑️
                    </button>
                </div>
            `;

                document.getElementById('items').insertAdjacentHTML('beforeend', html);

                const selects = document.querySelectorAll('.indumentaria-select');
                initIndumentariaSelect(selects[selects.length - 1]);

                index++;
            }

            function removeItem(btn) {
                if (document.querySelectorAll('.item-row').length === 1) return;
                btn.closest('.item-row').remove();
            }
        </script>
    @endpush
</x-admin-layout>

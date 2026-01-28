<x-admin-layout>

    @push('css')
        <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0/dist/css/select2.min.css" rel="stylesheet" />
    @endpush

    <div class="max-w-6xl mx-auto space-y-6">

        {{-- MENSAJE --}}
        @if (session('swal'))
            <script>
                Swal.fire(@json(session('swal')))
            </script>
        @endif

        <div class="bg-white shadow rounded-lg p-6">
            <h2 class="font-bold text-lg mb-4">♻️ Devolución de indumentaria</h2>

            <form method="POST" action="{{ route('admin.indumentarias.devoluciones.store') }}">
                @csrf

                {{-- CABECERA --}}
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">

                    {{-- EMPLEADO (BUSCADOR LIVEWIRE) --}}
                    <div>
                        @livewire('empleado-buscador')
                    </div>

                    <select name="ubicacion_id" class="rounded border-gray-300" required>
                        <option value="">🏭 Bodega</option>
                        @foreach ($ubicaciones as $u)
                            <option value="{{ $u->id }}">{{ $u->nombre }}</option>
                        @endforeach
                    </select>

                    <input type="date" name="fecha_devolucion" class="rounded border-gray-300"
                        value="{{ date('Y-m-d') }}" required>
                </div>

                {{-- DETALLE --}}
                <div id="items" class="space-y-3">

                    <div class="grid grid-cols-1 md:grid-cols-6 gap-3 item-row">

                        <select name="indumentarias[0][id]" class="rounded border-gray-300 indumentaria-select" required >
                            <option value="">👕 Indumentaria</option>
                            @foreach ($indumentarias as $i)
                                <option value="{{ $i->id }}">{{ $i->nombre }}</option>
                            @endforeach
                        </select>

                        <input type="number" name="indumentarias[0][cantidad]" min="1"
                            class="rounded border-gray-300 total-input" placeholder="Total" required>

                        <input type="number" name="indumentarias[0][cantidad_reutilizable]" min="0"
                            class="rounded border-gray-300 reutilizable-input" placeholder="Reutilizable" required>

                        <input type="number" name="indumentarias[0][cantidad_baja]" min="0"
                            class="rounded border-gray-300 baja-input" placeholder="Baja" required>

                        <div></div>
                    </div>
                </div>

                <button type="button" onclick="addItem()" class="text-blue-600 text-sm mt-4">
                    ➕ Agregar otra indumentaria
                </button>

                <div class="mt-4">
                    <textarea name="observacion" rows="2" class="w-full rounded border-gray-300" placeholder="Observación (opcional)"></textarea>
                </div>

                <div class="mt-6 flex justify-end">
                    <button class="bg-green-600 hover:bg-green-700 text-white px-6 py-2 rounded-lg">
                        ♻️ Registrar devolución
                    </button>
                </div>
            </form>
        </div>

        <livewire:devolucion-indumentaria-index />

    </div>

 
        @push('js')
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
        <div class="grid grid-cols-1 md:grid-cols-6 gap-3 item-row mt-2">

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

            <input type="number"
                   name="indumentarias[${index}][cantidad]"
                   min="1"
                   class="rounded border-gray-300 total-input"
                   placeholder="Total"
                   required>

            <input type="number"
                   name="indumentarias[${index}][cantidad_reutilizable]"
                   min="0"
                   class="rounded border-gray-300 reutilizable-input"
                   placeholder="Reutilizable"
                   required>

            <input type="number"
                   name="indumentarias[${index}][cantidad_baja]"
                   min="0"
                   class="rounded border-gray-300 baja-input"
                   placeholder="Baja"
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
    @endpush

</x-admin-layout>

<div class="space-y-6">

    {{-- BUSCADOR --}}
    <div class="relative">
        <input
            type="text"
            wire:model.live="search"
            placeholder="🔍 Buscar colaborador..."
            class="w-full border rounded-lg px-4 py-2"
        />

        {{-- RESULTADOS --}}
        @if ($search && $this->colaboradores->count())
            <ul class="absolute bg-white border w-full rounded shadow z-10">
                @foreach ($this->colaboradores as $user)
                    <li
                        wire:click="seleccionarColaborador({{ $user->id }})"
                        class="px-4 py-2 hover:bg-gray-100 cursor-pointer"
                    >
                        {{ $user->name }}
                    </li>
                @endforeach
            </ul>
        @endif
    </div>

    {{-- COLABORADOR SELECCIONADO --}}
    @if ($colaborador)
        <div class="flex justify-between items-center bg-gray-100 p-3 rounded">
            <strong>👤 {{ $colaborador->name }}</strong>

            <button
                wire:click="limpiarColaborador"
                class="text-sm text-red-600 hover:underline"
            >
                Quitar
            </button>
        </div>
    @endif

    {{-- KÁRDEX --}}
    @if ($this->kardex->count())
        <div class="overflow-x-auto">
            <table class="w-full text-sm border">
                <thead class="bg-gray-200">
                    <tr>
                        <th class="p-2 border">Fecha</th>
                        <th class="p-2 border">Artículo</th>
                        <th class="p-2 border">Movimiento</th>
                        <th class="p-2 border text-center">Nuevo</th>
                        <th class="p-2 border text-center">Usado</th>
                        <th class="p-2 border text-center">Baja</th>
                    </tr>
                </thead>

                <tbody>
                    @foreach ($this->kardex as $item)
                        <tr>
                            <td class="p-2 border">
                                {{ \Carbon\Carbon::parse($item->fecha)->format('d/m/Y H:i') }}
                            </td>

                            <td class="p-2 border">
                                {{ $item->articulo }}
                            </td>

                            <td class="p-2 border font-semibold
                                {{ $item->tipo === 'ENTREGA' ? 'text-blue-600' : 'text-orange-600' }}">
                                {{ $item->tipo }}
                            </td>

                            {{-- NUEVO --}}
                            <td class="p-2 border text-center text-green-700">
                                {{ $item->nuevo > 0 ? $item->nuevo : '-' }}
                            </td>

                            {{-- USADO --}}
                            <td class="p-2 border text-center text-yellow-700">
                                {{ $item->usado > 0 ? $item->usado : '-' }}
                            </td>

                            {{-- BAJA --}}
                            <td class="p-2 border text-center text-red-700">
                                {{ $item->baja > 0 ? $item->baja : '-' }}
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

    @elseif($colaborador)
        <p class="text-gray-500">
            Este colaborador no tiene movimientos.
        </p>
    @endif

</div>

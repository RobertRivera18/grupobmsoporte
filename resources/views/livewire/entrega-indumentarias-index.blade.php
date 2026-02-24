<div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">

    {{-- HEADER --}}
    <div class="px-5 py-4 border-b flex items-center justify-between">
        <h3 class="font-semibold text-gray-800 text-sm">
            📋 Historial de entregas de indumentaria
        </h3>

        <span class="text-xs text-gray-500">
            Total: {{ $entregas->total() }}
        </span>
    </div>

    {{-- TABLA --}}
    <div class="overflow-x-auto">
        <table class="min-w-full text-sm">
            <thead class="bg-gray-50 text-gray-600 uppercase text-xs">
                <tr>
                    <th class="px-4 py-3 text-left">Fecha</th>
                    <th class="px-4 py-3 text-left">Empleado</th>
                    <th class="px-4 py-3 text-left">Bodega</th>
                    <th class="px-4 py-3 text-left">Tipo</th> {{-- NUEVA COLUMNA --}}
                    <th class="px-4 py-3 text-left">Indumentarias entregadas</th>
                    <th class="px-4 py-3 text-center">Acta</th>
                    <th class="px-4 py-3 text-center"></th>
                </tr>
            </thead>

            <tbody class="divide-y divide-gray-100">
                @forelse ($entregas as $e)
                    <tr class="hover:bg-gray-50 transition">

                        {{-- FECHA --}}
                        <td class="px-4 py-3 whitespace-nowrap">
                            <div class="text-gray-800 font-medium">
                                {{ \Carbon\Carbon::parse($e->fecha_entrega)->format('d/m/Y') }}
                            </div>
                            <div class="text-xs text-gray-400">
                                {{ \Carbon\Carbon::parse($e->created_at)->format('H:i') }}
                            </div>
                        </td>

                        {{-- EMPLEADO --}}
                        <td class="px-4 py-3">
                            <div class="font-medium text-gray-700 flex items-center gap-2">
                                👤 {{ $e->empleado->name }}
                            </div>
                        </td>

                        {{-- BODEGA --}}
                        <td class="px-4 py-3">
                            <span
                                class="inline-flex items-center px-2 py-1 rounded-full text-xs
                                bg-blue-100 text-blue-700">
                                🏭 {{ $e->ubicacion->nombre }}
                            </span>
                        </td>

                        {{-- TIPO ENTREGA --}}
                        <td class="px-4 py-3">
                            @if ($e->es_historica)
                                <span class="inline-flex items-center px-2 py-1 rounded-full text-xs
                                    bg-amber-100 text-amber-700 font-medium">
                                    🕰️ Histórica
                                </span>
                                <div class="text-[11px] text-amber-600 mt-1">
                                    Generada automáticamente
                                </div>
                            @else
                                <span class="inline-flex items-center px-2 py-1 rounded-full text-xs
                                    bg-green-100 text-green-700 font-medium">
                                    ✅ Normal
                                </span>
                            @endif
                        </td>

                        {{-- INDUMENTARIAS --}}
                        <td class="px-4 py-3">
                            <div class="space-y-2">
                                @foreach ($e->detalles as $d)
                                    <div class="flex items-center justify-between gap-3 text-gray-700">

                                        <div class="flex items-center gap-2">
                                            {{-- CANTIDAD --}}
                                            <span
                                                class="inline-flex items-center justify-center w-6 h-6
                                                rounded-full bg-red-100 text-red-700 text-xs font-semibold">
                                                {{ $d->cantidad }}
                                            </span>

                                            {{-- NOMBRE --}}
                                            <span>{{ $d->indumentaria->nombre }}</span>
                                            <span>{{ $d->indumentaria->talla }}</span>
                                        </div>

                                        {{-- TIPO INVENTARIO --}}
                                        @if ($d->tipo_inventario === 'nuevo')
                                            <span
                                                class="px-2 py-1 text-xs rounded-full
                                                bg-green-100 text-green-700 font-medium">
                                                🆕 Nuevo
                                            </span>
                                        @else
                                            <span
                                                class="px-2 py-1 text-xs rounded-full
                                                bg-yellow-100 text-yellow-700 font-medium">
                                                ♻️ Usado
                                            </span>
                                        @endif
                                    </div>
                                @endforeach
                            </div>
                        </td>

                        {{-- ACTA DE ENTREGA --}}
                        <td class="px-4 py-3 text-center">
                            <button wire:click="generarActa({{ $e->id }})"
                                title="Descargar acta de entrega"
                                class="inline-flex items-center justify-center
                                w-9 h-9 rounded-full
                                bg-indigo-100 text-indigo-700
                                hover:bg-indigo-200 hover:text-indigo-900
                                transition">
                                <i class="fa-solid fa-file-word text-lg"></i>
                            </button>
                        </td>

                        {{-- ELIMINAR --}}
                        <td class="px-4 py-3 text-center">

                            @if (!$e->es_historica)
                                <button wire:click="eliminar({{ $e->id }})"
                                    title="Eliminar Registro Entrega"
                                    class="inline-flex items-center justify-center
                                    w-9 h-9 rounded-full
                                    bg-red-100 text-red-700
                                    hover:bg-red-200 hover:text-red-900
                                    transition">
                                    <i class="fas fa-trash-alt text-lg"></i>
                                </button>
                            @else
                                <span class="text-gray-400 text-xs">
                                    🔒 Automática
                                </span>
                            @endif

                        </td>

                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="py-10 text-center text-gray-500">
                            📦 No hay entregas registradas
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    {{-- PAGINACIÓN --}}
    <div class="px-5 py-4 border-t bg-gray-50">
        {{ $entregas->links() }}
    </div>

</div>

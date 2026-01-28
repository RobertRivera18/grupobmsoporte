<div class="space-y-6">

    <h2 class="text-xl font-bold">📦 Historial de devoluciones de indumentaria</h2>

    @forelse ($devoluciones as $devolucion)

        <div class="bg-white shadow rounded-lg p-4 space-y-3">

            {{-- CABECERA --}}
            <div class="grid grid-cols-1 md:grid-cols-6 gap-3 text-sm items-start">

                <div>
                    <span class="font-semibold">👤 Colaborador:</span><br>
                    {{ $devolucion->usuario->name }}
                </div>

                <div>
                    <span class="font-semibold">🏭 Bodega:</span><br>
                    {{ $devolucion->ubicacion->nombre }}
                </div>

                <div>
                    <span class="font-semibold">📅 Fecha:</span><br>
                    {{ \Carbon\Carbon::parse($devolucion->fecha_devolucion)->format('d/m/Y') }}
                </div>

                {{-- TIPO --}}
                <div>
                    <span class="font-semibold">📌 Tipo:</span><br>
                    @if ($devolucion->es_historica)
                        <span class="inline-flex items-center gap-1
                                     px-2 py-1 text-xs font-semibold
                                     rounded-full bg-amber-100 text-amber-700">
                            🕰️ Histórica
                        </span>
                    @else
                        <span class="inline-flex items-center gap-1
                                     px-2 py-1 text-xs font-semibold
                                     rounded-full bg-green-100 text-green-700">
                            ✅ Normal
                        </span>
                    @endif
                </div>

                <div>
                    <span class="font-semibold">📝 Observación:</span><br>
                    {{ $devolucion->observacion ?? '—' }}
                </div>

                <div class="flex items-center justify-center">
                    <button wire:click="generarActa({{ $devolucion->id }})"
                            title="Descargar acta de devolución"
                            class="inline-flex items-center justify-center
                                   w-9 h-9 rounded-full
                                   bg-indigo-100 text-indigo-700
                                   hover:bg-indigo-200 hover:text-indigo-900
                                   transition">
                        <i class="fa-solid fa-file-word text-lg"></i>
                    </button>
                </div>

            </div>

            {{-- ⚠️ AVISO DEVOLUCIÓN HISTÓRICA --}}
            @if ($devolucion->es_historica)
                <div class="mt-2 text-xs text-amber-700 bg-amber-50 p-2 rounded">
                    ⚠️ Esta devolución fue registrada como histórica, no existe entrega previa registrada en el sistema.
                </div>
            @endif

            {{-- DETALLE --}}
            <div class="overflow-x-auto">
                <table class="w-full text-sm border mt-3">
                    <thead class="bg-gray-100 text-left">
                        <tr>
                            <th class="p-2 border">Indumentaria</th>
                            <th class="p-2 border text-center">Total</th>
                            <th class="p-2 border text-center">♻️ Reutilizable</th>
                            <th class="p-2 border text-center">❌ Baja</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($devolucion->detalles as $detalle)
                            <tr>
                                <td class="p-2 border">
                                    {{ $detalle->indumentaria->nombre }}
                                </td>
                                <td class="p-2 border text-center">
                                    {{ $detalle->cantidad }}
                                </td>
                                <td class="p-2 border text-center text-green-600 font-semibold">
                                    {{ $detalle->cantidad_reutilizable }}
                                </td>
                                <td class="p-2 border text-center text-red-600 font-semibold">
                                    {{ $detalle->cantidad_baja }}
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

        </div>

    @empty
        <div class="text-gray-500 text-center">
            No existen devoluciones registradas.
        </div>
    @endforelse

    {{-- PAGINACIÓN --}}
    <div class="mt-4">
        {{ $devoluciones->links() }}
    </div>

</div>

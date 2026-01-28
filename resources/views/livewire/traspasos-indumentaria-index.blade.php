<div class="max-w-7xl mx-auto space-y-6">

    <div class="bg-white shadow rounded-lg p-6 mt-6">

        <h3 class="font-bold text-lg mb-4">📦 Historial de Traspasos</h3>

        <div class="overflow-x-auto">
            <table class="w-full text-sm text-left text-gray-600">
                <thead class="bg-gray-100 text-xs uppercase">
                    <tr>
                        <th class="px-4 py-2">Fecha</th>
                        <th class="px-4 py-2">Origen</th>
                        <th class="px-4 py-2">Destino</th>
                        <th class="px-4 py-2">Detalle</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($traspasos as $traspaso)
                        <tr class="border-b">
                            <td class="px-4 py-2">
                                {{ \Carbon\Carbon::parse($traspaso->fecha)->format('d/m/Y') }}
                            </td>

                            <td class="px-4 py-2 font-semibold">
                                {{ $traspaso->origen->nombre }}
                            </td>

                            <td class="px-4 py-2 font-semibold">
                                {{ $traspaso->destino->nombre }}
                            </td>

                            <td class="px-4 py-2">
                                <ul class="space-y-1">
                                    @foreach ($traspaso->detalles as $detalle)
                                        <li class="flex justify-between">
                                            <span>
                                                👕 {{ $detalle->indumentaria->nombre }}
                                                ({{ $detalle->cantidad }})
                                            </span>

                                            <span
                                                class="text-xs px-2 py-1 rounded
                                            {{ $detalle->tipo_inventario === 'nuevo' ? 'bg-blue-100 text-blue-700' : 'bg-green-100 text-green-700' }}">
                                                {{ ucfirst($detalle->tipo_inventario) }}
                                            </span>
                                        </li>
                                    @endforeach
                                </ul>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="px-4 py-4 text-center text-gray-500">
                                No existen traspasos registrados
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="mt-4">
            {{ $traspasos->links() }}
        </div>

    </div>

</div>

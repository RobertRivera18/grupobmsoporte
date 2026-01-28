<div>
    <h2 class="text-lg font-semibold mb-4">Historial del equipo</h2>

    @if ($equipo)
        <div class="mb-6 bg-white p-4 rounded-md border shadow text-sm text-gray-800">
            <p><strong>Nombre:</strong> {{ $equipo->nombre }}</p>
            <p><strong>Marca:</strong> {{ $equipo->marca }}</p>
            <p><strong>Modelo:</strong> {{ $equipo->modelo }}</p>
            <p><strong>Serie:</strong> {{ $equipo->serie }}</p>
        </div>
    @endif

    @if (!empty($historial) && $historial->count())
        <div class="relative border-l-2 border-blue-300 pl-4 ml-2">
            <h3 class="font-semibold text-base mb-4 text-blue-700">Historial de asignaciones</h3>

            @foreach ($historial as $item)
                <div class="mb-6 relative">
                    <!-- Punto del timeline -->
                    <div class="absolute -left-2.5 top-1 w-4 h-4 bg-blue-500 border-2 border-white rounded-full shadow"></div>

                    <!-- Tarjeta de asignación -->
                    <div class="bg-white p-4 rounded shadow-sm border text-sm text-gray-800">
                        <p class="font-semibold text-blue-600">
                            {{ $item->user->name }}
                        </p>
                        <p>Asignado: {{ \Carbon\Carbon::parse($item->fecha_asignacion)->format('d/m/Y H:i') }}</p>

                        @if ($item->fecha_desasignacion)
                            <p>Desasignado: {{ \Carbon\Carbon::parse($item->fecha_desasignacion)->format('d/m/Y H:i') }}</p>
                        @else
                            <p class="text-green-600 font-medium">Actualmente asignado</p>
                        @endif
                    </div>
                </div>
            @endforeach
        </div>
    @else
        <p class="text-gray-500 mt-2 text-sm">Este equipo no tiene historial de asignaciones.</p>
    @endif
</div>

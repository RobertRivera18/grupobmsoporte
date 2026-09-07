<x-admin-layout>

    <div class="max-w-3xl mx-auto bg-white dark:bg-gray-800 p-6 rounded-2xl shadow-lg">

        {{-- Header --}}
        <div class="mb-6 border-b pb-4">
            <h2 class="text-xl font-semibold text-gray-800 dark:text-white flex items-center gap-2">
                <i class="fas fa-eye text-blue-600"></i>
                Detalle de Solicitud
            </h2>
        </div>

        {{-- Información --}}
        <div class="space-y-4 text-sm">

            <div>
                <strong>Usuario:</strong>
                {{ $salida->user->name ?? '-' }}
            </div>

            <div>
                <strong>Equipo:</strong>
                {{ $salida->equipos->nombre ?? '-' }}
            </div>

            <div>
                <strong>Marca / Modelo:</strong>
                {{ $salida->equipos->marca ?? '-' }} -
                {{ $salida->equipos->modelo ?? '-' }}
            </div>

            <div>
                <strong>Serie:</strong>
                {{ $salida->equipos->serie ?? '-' }}
            </div>

            <div>
                <strong>Fecha salida:</strong>
                {{ \Carbon\Carbon::parse($salida->fecha_salida_solicitada)->format('d/m/Y') }}
            </div>

            <div>
                <strong>Fecha retorno:</strong>
                {{ \Carbon\Carbon::parse($salida->fecha_retorno_estimada)->format('d/m/Y') }}
            </div>

            <div>
                <strong>Motivo:</strong>
                <p class="mt-1 text-gray-600 dark:text-gray-300">
                    {{ $salida->motivo }}
                </p>
            </div>

            <div>
                <strong>Estado:</strong>
                <span
                    class="px-2 py-1 rounded text-xs
                @if ($salida->estado == 'pendiente') bg-yellow-100 text-yellow-800
                @elseif($salida->estado == 'aprobado') bg-green-100 text-green-800
                @elseif($salida->estado == 'rechazado') bg-red-100 text-red-800
                @else bg-gray-200 text-gray-700 @endif
            ">
                    {{ ucfirst($salida->estado) }}
                </span>
            </div>

            <div>
                <strong>Aprobado por:</strong>
                {{ $salida->aprobador->name ?? '—' }}
            </div>

        </div>

        {{-- Botón volver --}}
        <div class="mt-6">
            <a href="{{ route('admin.salidas.index') }}" class="text-blue-600 hover:underline">
                ← Volver
            </a>
        </div>

    </div>

</x-admin-layout>

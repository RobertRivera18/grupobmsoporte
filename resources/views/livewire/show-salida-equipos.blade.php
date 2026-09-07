<div class="max-w-4xl mx-auto bg-white dark:bg-gray-800 p-8 rounded-2xl shadow-xl">

    {{-- Mensajes --}}
    @if (session()->has('message'))
        <div class="mb-6 p-4 bg-green-100 text-green-800 rounded-xl border border-green-300">
            {{ session('message') }}
        </div>
    @endif

    {{-- Header --}}
    <div class="flex justify-between items-center mb-8 border-b pb-4">
        <div>
            <h2 class="text-2xl font-bold text-gray-800 dark:text-white flex items-center gap-2">
                <i class="fas fa-laptop text-blue-600"></i>
                Detalle de Solicitud
            </h2>
            <p class="text-sm text-gray-500">
                Información completa de la solicitud de salida
            </p>
            <p class="text-sm text-gray-500">
                Fecha de solicitud: <span class="font-bold">{{ $salidaequipo->created_at->format('d/M/Y H:s') }}</span>
            </p>

        </div>

        {{-- Estado --}}
        <span
            class="px-3 py-1 text-xs font-semibold rounded-full
            @if ($salidaequipo->estado == 'pendiente') bg-yellow-100 text-yellow-800
            @elseif($salidaequipo->estado == 'aprobado') bg-green-100 text-green-800
            @elseif($salidaequipo->estado == 'rechazado') bg-red-100 text-red-800
            @else bg-gray-200 text-gray-700 @endif
        ">
            {{ strtoupper($salidaequipo->estado) }}
        </span>
    </div>

    {{-- GRID INFO --}}
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 text-sm">

        <div>
            <p class="text-gray-500 text-xs">Usuario</p>
            <p class="font-semibold text-gray-800 dark:text-white">
                {{ $salidaequipo->user->name ?? '-' }}
            </p>
        </div>

        <div>
            <p class="text-gray-500 text-xs">Equipo</p>
            <p class="font-semibold text-gray-800 dark:text-white">
                {{ $salidaequipo->equipos->nombre ?? '-' }}
            </p>
        </div>

        <div>
            <p class="text-gray-500 text-xs">Marca / Modelo</p>
            <p class="font-medium text-gray-700 dark:text-gray-300">
                {{ $salidaequipo->equipos->marca ?? '-' }} - {{ $salidaequipo->equipos->modelo ?? '-' }}
            </p>
        </div>

        <div>
            <p class="text-gray-500 text-xs">Serie</p>
            <p class="font-medium text-gray-700 dark:text-gray-300">
                {{ $salidaequipo->equipos->serie ?? '-' }}
            </p>
        </div>

        <div>
            <p class="text-gray-500 text-xs">Fecha salida</p>
            <p class="font-medium">
                {{ \Carbon\Carbon::parse($salidaequipo->fecha_salida_solicitada)->format('d/m/Y') }}
            </p>
        </div>

        <div>
            <p class="text-gray-500 text-xs">Fecha retorno</p>
            <p class="font-medium">
                {{ \Carbon\Carbon::parse($salidaequipo->fecha_retorno_estimada)->format('d/m/Y') }}
            </p>
        </div>

        @if ($salidaequipo->aprobador)
            <div class="md:col-span-2">
                <p class="text-gray-500 text-xs">Revisado por</p>
                <p class="font-medium text-green-700">
                    {{ $salidaequipo->aprobador->name }}
                </p>
            </div>
        @endif

    </div>

    {{-- Motivo --}}
    <div class="mt-6">
        <p class="text-gray-500 text-xs mb-1">Motivo de Salida </p>
        <div class="bg-gray-100 dark:bg-gray-700 p-4 rounded-xl text-gray-700 dark:text-gray-300">
            {{ $salidaequipo->motivo }}
        </div>
    </div>

    {{-- ACCIONES --}}
    @role('Admin')
        @if ($salidaequipo->estado === 'pendiente')
            <div class="mt-8 flex gap-4 justify-end">
                {{-- Rechazar --}}
                <button wire:click="abrirModalRechazo" wire:loading.attr="disabled"
                    class="px-5 py-2 rounded-xl border border-red-500 text-red-600 hover:bg-red-50 transition flex items-center gap-2">

                    <span wire:loading.remove wire:target="abrirModalRechazo">
                        <i class="fas fa-times"></i> Rechazar
                    </span>
                    <span wire:loading wire:target="abrirModalRechazo">
                        Abriendo...
                    </span>
                </button>


                {{-- Aprobar --}}
                <button wire:click="aprobar" wire:loading.attr="disabled"
                    class="px-5 py-2 rounded-xl bg-green-600 text-white hover:bg-green-700 transition flex items-center gap-2 shadow-md">

                    <span wire:loading.remove wire:target="aprobar">
                        <i class="fas fa-check"></i> Aprobar
                    </span>
                    <span wire:loading wire:target="aprobar">
                        Procesando...
                    </span>
                </button>

            </div>
        @endif
    @endrole
    @if ($salidaequipo->estado === 'rechazado' && $salidaequipo->motivo_rechazo)
        <div class="mt-6">
            <p class="text-red-500 text-xs mb-1">Motivo de Rechazo</p>
            <div class="bg-red-50 border border-red-200 p-4 rounded-xl text-red-700">
                {{ $salidaequipo->motivo_rechazo }}
            </div>
        </div>
    @endif

    {{-- Volver --}}
    <div class="mt-8 border-t pt-4">
        <a href="{{ route('admin.salidas.index') }}"
            class="text-sm text-blue-600 hover:underline flex items-center gap-1">
            <i class="fas fa-arrow-left"></i>
            Volver al listado
        </a>
    </div>

    @if ($mostrarModalRechazo)
        <div class="fixed inset-0 flex items-center justify-center bg-black bg-opacity-50 z-50 transition">

            <div class="bg-white dark:bg-gray-800 p-6 rounded-2xl shadow-xl w-full max-w-md animate-fadeIn">

                {{-- Header --}}
                <h2 class="text-lg font-semibold text-gray-800 dark:text-white mb-4 flex items-center gap-2">
                    <i class="fas fa-exclamation-triangle text-red-500"></i>
                    Motivo de rechazo
                </h2>

                {{-- Texto --}}
                <textarea wire:model.defer="motivo_rechazo"
                    class="w-full border rounded-xl p-3 dark:bg-gray-700 dark:text-white focus:ring-2 focus:ring-red-400" rows="4"
                    placeholder="Escribe el motivo del rechazo..."></textarea>

                @error('motivo_rechazo')
                    <span class="text-red-500 text-sm">{{ $message }}</span>
                @enderror




                {{-- Botones --}}
                <div class="mt-5 flex justify-end gap-3">

                    <button wire:click="$set('mostrarModalRechazo', false)"
                        class="px-4 py-2 bg-gray-200 hover:bg-gray-300 rounded-lg">
                        Cancelar
                    </button>

                    <button wire:click="confirmarRechazo" wire:loading.attr="disabled"
                        class="px-4 py-2 bg-red-600 text-white hover:bg-red-700 rounded-lg flex items-center gap-2">

                        <span wire:loading.remove wire:target="confirmarRechazo">
                            Confirmar rechazo
                        </span>

                        <span wire:loading wire:target="confirmarRechazo">
                            Guardando...
                        </span>
                    </button>

                </div>

            </div>
        </div>
    @endif

</div>

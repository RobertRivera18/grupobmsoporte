<x-admin-layout :breadcrumbs="[
    ['name' => 'Home', 'url' => route('admin.dashboard')],
    ['name' => 'Auditorías', 'url' => route('admin.auditorias.index')],
    ['name' => $auditoria->anio],
]">

    @php
        $badges = [
            'planificada' => 'bg-purple-100 text-purple-800 ring-purple-300',
            'en_proceso'  => 'bg-amber-100  text-amber-800  ring-amber-300',
            'cerrada'     => 'bg-green-100  text-green-800  ring-green-300',
        ];
        $dots = [
            'planificada' => 'bg-purple-500',
            'en_proceso'  => 'bg-amber-500',
            'cerrada'     => 'bg-green-500',
        ];
        $labels = [
            'planificada' => 'Planificada',
            'en_proceso'  => 'En proceso',
            'cerrada'     => 'Cerrada',
        ];
    @endphp

    {{-- ================= CABECERA ================= --}}
    <div class="bg-white border-b border-gray-200">
        <div class="max-w-7xl mx-auto px-6 py-6 flex flex-col gap-4">

            {{-- Fila superior: periodo + badge estado actual --}}
            <div class="flex items-center justify-between flex-wrap gap-3">
                <div class="flex items-center gap-3 bg-gray-50 border border-gray-200 rounded-xl px-4 py-2">
                    <div class="text-sm text-gray-500">
                        <i class="fas fa-calendar-alt mr-1"></i> Periodo
                    </div>
                    <div class="text-sm font-medium text-gray-700">
                        {{ \Carbon\Carbon::parse($auditoria->fecha_inicio)->format('d/m/Y') }}
                        <span class="mx-1 text-gray-400">—</span>
                        {{ \Carbon\Carbon::parse($auditoria->fecha_fin)->format('d/m/Y') }}
                    </div>
                </div>

                <span class="inline-flex items-center gap-2 px-3 py-1 rounded-full text-xs font-medium ring-1 {{ $badges[$auditoria->estado] }}">
                    <span class="w-2 h-2 rounded-full {{ $dots[$auditoria->estado] }}"></span>
                    {{ $labels[$auditoria->estado] }}
                </span>
            </div>

            {{-- Selector de estado (solo admin) --}}
            @can('Admin')
                <div x-data="{ pendiente: null }" class="flex flex-col gap-3">

                    <div class="flex items-center gap-2 flex-wrap">
                        <span class="text-xs text-gray-500 mr-1">Cambiar a:</span>

                        @foreach ([
                            'planificada' => ['label' => 'Planificada', 'ring' => 'ring-purple-400', 'bg' => 'bg-purple-50', 'text' => 'text-purple-800', 'dot' => 'bg-purple-500'],
                            'en_proceso'  => ['label' => 'En proceso',  'ring' => 'ring-amber-400',  'bg' => 'bg-amber-50',  'text' => 'text-amber-800',  'dot' => 'bg-amber-500'],
                            'cerrada'     => ['label' => 'Cerrada',     'ring' => 'ring-green-400',  'bg' => 'bg-green-50',  'text' => 'text-green-800',  'dot' => 'bg-green-500'],
                        ] as $valor => $cfg)
                            <button
                                type="button"
                                @click="pendiente = '{{ $valor }}'"
                                @class([
                                    'inline-flex items-center gap-2 px-4 py-1.5 rounded-full text-sm font-medium border transition',
                                    'ring-2 ' . $cfg['ring'] . ' ' . $cfg['bg'] . ' ' . $cfg['text'] => $auditoria->estado === $valor,
                                    'border-gray-200 text-gray-500 hover:bg-gray-50'                  => $auditoria->estado !== $valor,
                                ])
                            >
                                <span class="w-2 h-2 rounded-full {{ $cfg['dot'] }}"></span>
                                {{ $cfg['label'] }}
                            </button>
                        @endforeach
                    </div>

                    {{-- Barra de confirmación --}}
                    <div
                        x-show="pendiente !== null && pendiente !== '{{ $auditoria->estado }}'"
                        x-transition
                        class="flex items-center justify-between gap-4 bg-gray-50 border border-gray-200 rounded-lg px-4 py-2.5 text-sm"
                    >
                        <span class="text-gray-700">
                            <i class="fas fa-circle-exclamation mr-1 text-gray-400"></i>
                            ¿Cambiar el estado a <strong x-text="pendiente"></strong>?
                        </span>
                        <div class="flex gap-2">
                            <button
                                type="button"
                                @click="pendiente = null"
                                class="px-3 py-1 rounded-md border border-gray-300 text-gray-600 hover:bg-gray-100 transition text-xs"
                            >
                                Cancelar
                            </button>

                            <form
                                method="POST"
                                :action="'{{ route('admin.auditorias.cambiarEstado', $auditoria) }}'"
                                x-ref="formEstado"
                            >
                                @csrf
                                @method('PATCH')
                                <input type="hidden" name="estado" :value="pendiente">
                                <button
                                    type="submit"
                                    class="px-3 py-1 rounded-md bg-gray-800 text-white hover:bg-gray-900 transition text-xs"
                                >
                                    Confirmar
                                </button>
                            </form>
                        </div>
                    </div>

                    {{-- Flash de éxito --}}
                    @if (session('success'))
                        <p class="text-sm text-green-700 flex items-center gap-2">
                            <i class="fas fa-circle-check"></i>
                            {{ session('success') }}
                        </p>
                    @endif

                </div>
            @endcan

        </div>
    </div>

    {{-- ================= CONTENIDO ================= --}}
    <div class="max-w-7xl mx-auto px-6 py-8 space-y-6">
        @livewire('create-auditoria-proceso', ['auditoria' => $auditoria])
    </div>

</x-admin-layout>
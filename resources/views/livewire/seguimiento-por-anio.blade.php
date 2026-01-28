<div class="space-y-6">

    {{-- ============================= --}}
    {{-- HEADER CON AÑO --}}
    {{-- ============================= --}}
    <div class="bg-gradient-to-r from-indigo-500 to-purple-600 rounded-xl shadow-lg p-4 sm:p-6">
        <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-4">

            {{-- Información izquierda --}}
            <div class="flex items-center gap-4">
                <div class="bg-white/20 backdrop-blur-sm p-3 rounded-lg shrink-0">
                    <i class="fas fa-calendar-alt text-white text-2xl"></i>
                </div>

                <div>
                    <h3 class="text-lg sm:text-2xl font-bold text-white">
                        Seguimiento anual
                    </h3>

                    <p class="text-indigo-100 text-sm mt-1">
                        Registro de valores para el año {{ $anio->anio }}
                    </p>

                    @if ($anio->ultima_revision)
                        <p class="text-indigo-200 text-xs mt-1">
                            Última revisión:
                            <span class="font-semibold text-white">
                                {{ $anio->ultima_revision->format('d/m/Y H:i') }}
                            </span>
                        </p>
                    @endif
                </div>
            </div>

            {{-- Sección derecha --}}
            <div class="flex flex-col sm:flex-row lg:flex-col items-start sm:items-center lg:items-end gap-3">

                {{-- Año --}}
                <div class="bg-white/90 px-4 sm:px-6 py-2 sm:py-3 rounded-lg w-full sm:w-auto text-center">
                    <p class="text-gray-600 text-xs uppercase tracking-wider">Año</p>
                    <p class="text-2xl sm:text-3xl font-bold text-indigo-600">
                        {{ $anio->anio }}
                    </p>
                </div>

                {{-- Botón revisión --}}
                <x-button
                    wire:click="marcarRevision"
                    class="w-full sm:w-auto bg-gradient-to-r from-yellow-400 to-yellow-500
                           hover:from-yellow-500 hover:to-yellow-600 text-gray-800 
                           font-semibold px-4 py-2 rounded-lg shadow-md
                           transition-all flex items-center justify-center gap-2">
                    <i class="fas fa-clock text-sm"></i>
                    Registrar Revisión
                </x-button>

            </div>
        </div>
    </div>

    {{-- ============================= --}}
    {{-- TABLA DE PERIODOS --}}
    {{-- ============================= --}}
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">

        {{-- Header --}}
        <div class="px-4 sm:px-6 py-4 border-b bg-gray-50">
            <div class="flex items-center gap-3">
                <div class="bg-indigo-100 p-2 rounded-lg">
                    <i class="fas fa-table text-indigo-600"></i>
                </div>
                <div>
                    <h4 class="text-base sm:text-lg font-bold text-gray-900">
                        Valores Registrados
                    </h4>
                    <p class="text-gray-500 text-sm">
                        Ingrese los valores según la frecuencia
                    </p>
                </div>
            </div>
        </div>

        {{-- Tabla responsive --}}
        <div class="overflow-x-auto">
            <table class="min-w-full text-sm">
                <thead class="bg-gray-50">
                    <tr>
                        @foreach ($periodos as $nombre)
                            <th class="px-3 py-3 text-center font-semibold text-gray-700 uppercase">
                                {{ $nombre }}
                            </th>
                        @endforeach
                    </tr>
                </thead>

                <tbody class="divide-y">
                    <tr>
                        @foreach ($periodos as $num => $nombre)
                            <td class="px-3 py-4">
                                <div class="flex flex-col items-center gap-2 min-w-[120px]">

                                    <input
                                        type="number"
                                        step="0.01"
                                        wire:model.defer="valores.{{ $num }}"
                                        placeholder="0.00"
                                        class="w-full max-w-[110px] px-3 py-2 border-2 border-gray-300 rounded-lg 
                                               text-center font-semibold
                                               focus:border-indigo-500 focus:ring-2 focus:ring-indigo-200">

                                    <button
                                        wire:click="savePeriodo({{ $num }})"
                                        class="w-full bg-indigo-600 hover:bg-indigo-700
                                               text-white text-xs font-semibold
                                               px-3 py-2 rounded-lg
                                               flex items-center justify-center gap-1">
                                        <i class="fas fa-save"></i>
                                        Guardar
                                    </button>

                                </div>
                            </td>
                        @endforeach
                    </tr>
                </tbody>
            </table>
        </div>

        {{-- Leyenda --}}
        <div class="px-4 sm:px-6 py-3 border-t bg-gray-50">
            <div class="flex items-center gap-2 text-sm text-gray-600">
                <i class="fas fa-info-circle text-indigo-500"></i>
                Valores numéricos con hasta 2 decimales
            </div>
        </div>
    </div>

    {{-- ============================= --}}
    {{-- OBSERVACIONES --}}
    {{-- ============================= --}}
    <div class="bg-white rounded-xl shadow-sm border">

        <div class="px-4 sm:px-6 py-4 border-b bg-emerald-50">
            <div class="flex items-center gap-3">
                <div class="bg-emerald-500 p-2 rounded-lg">
                    <i class="fas fa-comment-alt text-white"></i>
                </div>
                <div>
                    <h4 class="text-base sm:text-lg font-bold text-gray-900">
                        Observaciones
                    </h4>
                    <p class="text-sm text-gray-500">
                        Notas del seguimiento
                    </p>
                </div>
            </div>
        </div>

        <div class="p-4 sm:p-6 space-y-4">
            <textarea
                wire:model.defer="observacion"
                rows="4"
                class="w-full px-4 py-3 border-2 rounded-lg resize-none
                       focus:border-emerald-500 focus:ring-2 focus:ring-emerald-200"
                placeholder="Escriba observaciones..."></textarea>

            <div class="flex justify-end">
                <button
                    wire:click="saveObservacion"
                    class="w-full sm:w-auto bg-emerald-600 hover:bg-emerald-700
                           text-white font-semibold px-6 py-3 rounded-lg
                           flex items-center justify-center gap-2">
                    <i class="fas fa-check-circle"></i>
                    Guardar Observación
                </button>
            </div>
        </div>
    </div>

    {{-- ============================= --}}
    {{-- MENSAJE ÉXITO --}}
    {{-- ============================= --}}
    @if (session()->has('message'))
        <div
            x-data="{ show: true }"
            x-show="show"
            x-init="setTimeout(() => show = false, 5000)"
            class="bg-green-50 border-l-4 border-green-500 p-4 rounded-lg shadow-md">

            <div class="flex flex-col sm:flex-row items-start sm:items-center gap-3">
                <i class="fas fa-check-circle text-green-600 text-xl"></i>
                <div class="flex-1">
                    <p class="font-semibold text-green-900">¡Guardado exitosamente!</p>
                    <p class="text-sm text-green-700">{{ session('message') }}</p>
                </div>
                <button @click="show = false" class="text-green-600">
                    <i class="fas fa-times"></i>
                </button>
            </div>
        </div>
    @endif

    {{-- ============================= --}}
    {{-- GRÁFICA --}}
    {{-- ============================= --}}
    <div class="bg-white rounded-xl shadow-md p-4 sm:p-6 border">
        @livewire('grafica-indicador', ['anio' => $anio], key('grafica-' . $anio->id))
    </div>


    @push('js')
        <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    @endpush


</div>

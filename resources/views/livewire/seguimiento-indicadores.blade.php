{{-- ======================= --}}
{{-- HISTORIAL POR AÑO      --}}
{{-- ======================= --}}

<div class="space-y-6" x-data="{ abierto: null }">

    {{-- Botón Nuevo Año - MEJORADO --}}
    <div class="flex items-center justify-end">
        <x-button wire:click="mostrarFormularioNuevoAnio">
            <span class="relative font-bold tracking-wide">Registrar Nuevo Año</span>
            <span class="relative flex items-center justify-center">
                <i
                    class="fas fa-arrow-right text-sm opacity-0 group-hover:opacity-100 transform translate-x-[-10px] group-hover:translate-x-0 transition-all duration-300"></i>
            </span>
        </x-button>
    </div>

    {{-- Header de sección --}}
    <div class="bg-gradient-to-r from-slate-700 to-slate-800 rounded-xl shadow-lg p-6">
        <div class="flex items-center justify-between">
            <div class="flex items-center gap-4">
                <div class="bg-white/20 backdrop-blur-sm p-3 rounded-lg">
                    <i class="fas fa-history text-white text-2xl"></i>
                </div>
                <div>
                    <h3 class="text-2xl font-bold text-white">Historial por Año</h3>
                    <p class="text-slate-200 text-sm mt-1">Resultados y seguimiento de años anteriores</p>
                </div>
            </div>
            <div class="bg-white/90 backdrop-blur-sm px-4 py-2 rounded-lg">
                <p class="text-slate-600 text-xs uppercase tracking-wider">Total</p>
                <p class="text-2xl font-bold text-slate-700">{{ $indicador->anios->count() }} años</p>
            </div>
        </div>
    </div>

    {{-- Formulario de nuevo año --}}
    @if ($nuevoAnioVisible)
        <div class="bg-white border border-gray-300 rounded-xl p-6 shadow-md mb-6">
            <h3 class="text-xl font-bold text-gray-800 mb-4">Registrar Nuevo Año</h3>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">

                <!-- Año -->
                <div class="flex flex-col gap-1">
                    <label class="text-sm font-medium text-gray-700">Año</label>
                    <input type="number" wire:model="anio"
                        class="w-full rounded-lg border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 p-2.5 shadow-sm"
                        placeholder="Ej: 2025">
                    <x-input-error for="anio" />
                </div>

                <!-- Meta -->
                <div class="flex flex-col gap-1">
                    <label class="text-sm font-medium text-gray-700">Meta</label>
                    <input type="number" step="0.01" wire:model="meta"
                        class="w-full rounded-lg border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 p-2.5 shadow-sm"
                        placeholder="Ej: 95.5">
                    <x-input-error for="meta" />
                </div>



                {{-- <div>
                    <label class="font-semibold">Resultado Obtenido</label>
                    <input type="number" step="0.01" wire:model="resultado_obtenido"
                        class="w-full border rounded p-2">
                </div> --}}

                {{-- <div>
                    <label class="font-semibold">Última Revisión</label>
                    <input type="date" wire:model="ultima_revision" class="w-full border rounded p-2">
                </div> --}}

                {{-- <div class="md:col-span-3">
                    <label class="font-semibold">Observación</label>
                    <textarea wire:model="observacion_anio" rows="3" class="w-full border rounded p-2"></textarea>
                </div> --}}

            </div>

            <div class="mt-4 flex items-center gap-3">
                <button wire:click="crearAnio" class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-lg">
                    Guardar Año
                </button>

                <button wire:click="$set('nuevoAnioVisible', false)"
                    class="bg-gray-300 hover:bg-gray-400 px-4 py-2 rounded-lg">
                    Cancelar
                </button>
            </div>
        </div>
    @endif


    {{-- LISTA DE AÑOS CON ALPINE --}}
    @forelse ($indicador->anios as $index => $anio)
        <div
            class="bg-white border-2 border-gray-200 rounded-xl shadow-sm hover:shadow-lg hover:border-indigo-300 transition-all duration-300">

            {{-- ---- HEADER DEL AÑO ---- --}}
            <div class="bg-gradient-to-r from-indigo-50 via-purple-50 to-pink-50 border-b-2 border-gray-200 px-6 py-5 cursor-pointer"
                @click="abierto === {{ $anio->id }} ? abierto = null : abierto = {{ $anio->id }}">

                <div class="flex items-center justify-between">

                    <div class="flex items-center gap-4">
                        <div class="bg-gradient-to-br from-indigo-500 to-purple-600 p-3 rounded-xl shadow-md">
                            <i class="fas fa-calendar-check text-white text-xl"></i>
                        </div>

                        <div>
                            <div class="flex items-center gap-3">
                                <h4 class="text-2xl font-bold text-gray-900">{{ $anio->anio }}</h4>

                                @if ($anio->anio == date('Y'))
                                    <span
                                        class="bg-green-100 border border-green-300 text-green-700 text-xs font-semibold px-3 py-1 rounded-full">
                                        <i class="fas fa-circle text-green-500 text-xs mr-1 animate-pulse"></i>
                                        Año Actual
                                    </span>
                                @else
                                    <span
                                        class="bg-gray-100 border border-gray-300 text-gray-600 text-xs font-semibold px-3 py-1 rounded-full">
                                        <i class="fas fa-archive text-gray-500 text-xs mr-1"></i>
                                        Histórico
                                    </span>
                                @endif
                            </div>

                            <p class="text-gray-500 text-sm mt-1">Registro completo del año {{ $anio->anio }}</p>
                        </div>
                    </div>

                    {{-- Icono para abrir/cerrar --}}
                    <div class="text-gray-600">
                        <i class="fas fa-chevron-down transition-transform duration-300"
                            :class="abierto === {{ $anio->id }} ? 'rotate-180' : ''"></i>
                    </div>

                </div>
            </div>


            {{-- ---- CONTENIDO DEL AÑO (colapsable con Alpine) ---- --}}
            <div class="p-6" x-show="abierto === {{ $anio->id }}" x-transition x-collapse>

                {{-- GRID DE MÉTRICAS --}}
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4 mb-6">

                    {{-- Meta --}}
                    <div
                        class="bg-gradient-to-br from-blue-50 to-blue-100/50 rounded-xl p-4 border-2 border-blue-200 hover:shadow-md transition-shadow">
                        <div class="flex items-center gap-3 mb-3">
                            <div class="bg-blue-500 p-2 rounded-lg">
                                <i class="fas fa-bullseye text-white text-sm"></i>
                            </div>
                            <h5 class="text-sm font-bold text-gray-700 uppercase tracking-wide">Meta</h5>
                        </div>
                        <p class="text-2xl font-bold text-blue-900">{{ $anio->meta ?? 'No definida' }}</p>
                        <p class="text-xs text-blue-600 mt-1">Objetivo planificado</p>
                    </div>

                    {{-- Resultado --}}
                    <div
                        class="bg-gradient-to-br from-emerald-50 to-emerald-100/50 rounded-xl p-4 border-2 border-emerald-200 hover:shadow-md transition-shadow">
                        <div class="flex items-center gap-3 mb-3">
                            <div class="bg-emerald-500 p-2 rounded-lg">
                                <i class="fas fa-chart-line text-white text-sm"></i>
                            </div>
                            <h5 class="text-sm font-bold text-gray-700 uppercase tracking-wide">Resultado</h5>
                        </div>
                        <p class="text-2xl font-bold text-emerald-900">{{ $anio->resultado_obtenido ?? 'Sin datos' }}
                        </p>
                        <p class="text-xs text-emerald-600 mt-1">Valor alcanzado</p>
                    </div>

                    {{-- Revisión --}}
                    <div
                        class="bg-gradient-to-br from-amber-50 to-amber-100/50 rounded-xl p-4 border-2 border-amber-200 hover:shadow-md transition-shadow">
                        <div class="flex items-center gap-3 mb-3">
                            <div class="bg-amber-500 p-2 rounded-lg">
                                <i class="fas fa-clock text-white text-sm"></i>
                            </div>
                            <h5 class="text-sm font-bold text-gray-700 uppercase tracking-wide">Revisión</h5>
                        </div>
                        <p class="text-lg font-bold text-amber-900">
                            {{ optional($anio->ultima_revision)->format('d/m/Y') ?? 'Pendiente' }}
                        </p>
                        <p class="text-xs text-amber-600 mt-1">Última actualización</p>
                    </div>

                    {{-- Diferencia --}}
                    @if ($anio->meta && $anio->resultado_obtenido)
                        @php
                            $diferencia = $anio->resultado_obtenido - $anio->meta;
                            $cumplida = $diferencia >= 0;
                        @endphp

                        <div
                            class="bg-gradient-to-br from-{{ $cumplida ? 'green' : 'red' }}-50 to-{{ $cumplida ? 'green' : 'red' }}-100/50 rounded-xl p-4 border-2 border-{{ $cumplida ? 'green' : 'red' }}-200 hover:shadow-md transition-shadow">
                            <div class="flex items-center gap-3 mb-3">
                                <div class="bg-{{ $cumplida ? 'green' : 'red' }}-500 p-2 rounded-lg">
                                    <i
                                        class="fas fa-{{ $cumplida ? 'arrow-up' : 'arrow-down' }} text-white text-sm"></i>
                                </div>
                                <h5 class="text-sm font-bold text-gray-700 uppercase tracking-wide">Diferencia</h5>
                            </div>
                            <p class="text-2xl font-bold text-{{ $cumplida ? 'green' : 'red' }}-900">
                                {{ $cumplida ? '+' : '' }}{{ number_format($diferencia, 2) }}
                            </p>
                            <p class="text-xs text-{{ $cumplida ? 'green' : 'red' }}-600 mt-1">
                                {{ $cumplida ? 'Meta superada' : 'Por debajo' }}
                            </p>
                        </div>
                    @endif
                </div>

                {{-- Observación --}}
                @if ($anio->observacion)
                    <div
                        class="bg-gradient-to-r from-purple-50 to-pink-50 rounded-xl p-5 border-2 border-purple-200 mb-6">
                        <div class="flex items-start gap-3">
                            <div class="bg-purple-500 p-2 rounded-lg mt-1">
                                <i class="fas fa-comment-dots text-white"></i>
                            </div>
                            <div class="flex-1">
                                <h5 class="text-sm font-bold text-gray-900 uppercase tracking-wide mb-2">Observaciones
                                </h5>
                                <p class="text-gray-700 leading-relaxed">{{ $anio->observacion }}</p>
                            </div>
                        </div>
                    </div>
                @endif

                {{-- Divider --}}
                <div class="border-t-2 border-dashed border-gray-300 my-6"></div>

                {{-- Livewire Seguimiento --}}
                <div class="bg-gray-50 rounded-xl p-1">
                    @livewire('seguimiento-por-anio', ['anio' => $anio], key('anio-' . $anio->id))
                </div>

            </div>

        </div>

    @empty

        {{-- Estado vacío --}}
        <div class="bg-white border-2 border-dashed border-gray-300 rounded-xl p-12">
            <div class="text-center">
                <div class="inline-flex items-center justify-center w-20 h-20 bg-gray-100 rounded-full mb-4">
                    <i class="fas fa-calendar-times text-gray-400 text-3xl"></i>
                </div>
                <h4 class="text-xl font-bold text-gray-900 mb-2">No hay historial disponible</h4>
                <p class="text-gray-500 mb-6">
                    Aún no se han registrado datos para ningún año en este indicador.
                </p>
            </div>
        </div>
    @endforelse
</div>

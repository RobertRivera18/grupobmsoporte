<div class="space-y-6" x-data="{ abierto: null }">

    {{-- Botón Nuevo Año --}}
    <div class="flex items-center justify-end">
        <x-button wire:click="mostrarFormularioNuevoAnio" class="group relative inline-flex items-center gap-2 overflow-hidden px-5 py-2.5 bg-slate-900 hover:bg-slate-800 text-white rounded-xl transition-all shadow-xs font-medium text-sm">
            <span>Registrar Nuevo Año</span>
            <i class="fas fa-plus text-xs"></i>
        </x-button>
    </div>

    {{-- Header de sección estilo Apple Clean --}}
    <div class="bg-white rounded-2xl shadow-xs border border-slate-200/80 p-6 sm:p-8 flex flex-col sm:flex-row items-start sm:items-center justify-between gap-6">
        <div class="flex items-center gap-4">
            <div class="bg-slate-100 p-3.5 rounded-xl border border-slate-200 text-slate-700">
                <i class="fas fa-history text-xl"></i>
            </div>
            <div>
                <h3 class="text-2xl font-bold tracking-tight text-slate-900">Historial por Año</h3>
                <p class="text-slate-500 text-sm mt-0.5">Resultados y seguimiento detallado de periodos anteriores</p>
            </div>
        </div>
        <div class="bg-slate-50 border border-slate-200 px-4 py-2.5 rounded-xl flex items-center gap-3">
            <div>
                <p class="text-slate-400 text-xs uppercase tracking-wider font-semibold">Total Registrados</p>
                <p class="text-xl font-bold text-slate-900">{{ $indicador->anios->count() }} <span class="text-xs font-normal text-slate-500">años</span></p>
            </div>
        </div>
    </div>

    {{-- Formulario de nuevo año --}}
    @if ($nuevoAnioVisible)
        <div class="bg-white border border-slate-200 rounded-2xl p-6 sm:p-8 shadow-sm transition-all">
            <div class="flex items-center justify-between mb-6 pb-4 border-b border-slate-100">
                <h3 class="text-lg font-bold text-slate-900 flex items-center gap-2">
                    <i class="fas fa-calendar-plus text-blue-600"></i> Registrar Nuevo Año
                </h3>
                <button wire:click="$set('nuevoAnioVisible', false)" class="text-slate-400 hover:text-slate-600 transition-colors p-1 rounded-lg hover:bg-slate-100">
                    <i class="fas fa-times text-base"></i>
                </button>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Año -->
                <div class="flex flex-col gap-1.5">
                    <label class="text-xs font-semibold text-slate-600 uppercase tracking-wider">
                        Año
                    </label>
                    <input type="number" wire:model="anio"
                        class="w-full rounded-xl border-slate-200 bg-slate-50/50 focus:bg-white focus:border-blue-600 focus:ring-blue-600 px-4 py-2.5 text-sm shadow-xs transition-all"
                        placeholder="Ej: 2026">
                    <x-input-error for="anio" />
                </div>

                <!-- Meta -->
                <div class="flex flex-col gap-1.5">
                    <label class="text-xs font-semibold text-slate-600 uppercase tracking-wider">
                        Meta Planificada
                    </label>
                    <input type="number" step="0.01" wire:model="meta"
                        class="w-full rounded-xl border-slate-200 bg-slate-50/50 focus:bg-white focus:border-blue-600 focus:ring-blue-600 px-4 py-2.5 text-sm shadow-xs transition-all"
                        placeholder="Ej: 95.50">
                    <x-input-error for="meta" />
                </div>
            </div>

            <div class="mt-6 flex items-center justify-end gap-3 pt-4 border-t border-slate-100">
                <button wire:click="$set('nuevoAnioVisible', false)"
                    class="px-4 py-2.5 rounded-xl bg-slate-100 hover:bg-slate-200 text-slate-700 font-medium text-sm transition-all">
                    Cancelar
                </button>
                <button wire:click="crearAnio" 
                    class="px-5 py-2.5 rounded-xl bg-blue-600 hover:bg-blue-700 text-white font-medium text-sm transition-all shadow-xs">
                    Guardar Año
                </button>
            </div>
        </div>
    @endif

    {{-- LISTA DE AÑOS CON ALPINE --}}
    <div class="space-y-4">
        @forelse ($indicador->anios as $index => $anioItem)
            <div class="bg-white border border-slate-200/80 rounded-2xl shadow-xs hover:border-slate-300 transition-all duration-200 overflow-hidden">

                {{-- ---- HEADER DEL AÑO ---- --}}
                <div class="bg-slate-50/50 hover:bg-slate-50 px-6 py-4 cursor-pointer select-none transition-colors border-b border-slate-100"
                    @click="abierto === {{ $anioItem->id }} ? abierto = null : abierto = {{ $anioItem->id }}">

                    <div class="flex items-center justify-between">
                        <div class="flex items-center gap-4">
                            <div class="bg-white border border-slate-200 p-3 rounded-xl shadow-xs text-slate-700">
                                <i class="fas fa-calendar text-sm"></i>
                            </div>

                            <div>
                                <div class="flex items-center gap-3">
                                    <h4 class="text-lg font-bold text-slate-900">{{ $anioItem->anio }}</h4>

                                    @if ($anioItem->anio == date('Y'))
                                        <span class="inline-flex items-center gap-1.5 bg-emerald-50 border border-emerald-200 text-emerald-700 text-xs font-semibold px-2.5 py-0.5 rounded-full">
                                            <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span>
                                            Año Actual
                                        </span>
                                    @else
                                        <span class="inline-flex items-center gap-1.5 bg-slate-100 border border-slate-200 text-slate-600 text-xs font-medium px-2.5 py-0.5 rounded-full">
                                            Histórico
                                        </span>
                                    @endif
                                </div>
                                <p class="text-slate-500 text-xs mt-0.5">Registro completo e indicadores de desempeño</p>
                            </div>
                        </div>

                        {{-- Icono para abrir/cerrar --}}
                        <div class="w-8 h-8 rounded-full bg-white border border-slate-200 flex items-center justify-center text-slate-500 shadow-xs transition-transform duration-200"
                            :class="abierto === {{ $anioItem->id }} ? 'rotate-180 bg-slate-100 text-slate-900' : ''">
                            <i class="fas fa-chevron-down text-xs"></i>
                        </div>
                    </div>
                </div>

                {{-- ---- CONTENIDO DEL AÑO (colapsable) ---- --}}
                <div class="p-6 bg-white space-y-6" x-show="abierto === {{ $anioItem->id }}" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 transform -translate-y-1" x-transition:enter-end="opacity-100 transform translate-y-0" x-cloak>

                    {{-- GRID DE MÉTRICAS --}}
                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">

                        {{-- Meta --}}
                        <div class="bg-slate-50 rounded-xl p-4 border border-slate-200/80">
                            <div class="flex items-center gap-2 mb-2">
                                <span class="text-slate-400"><i class="fas fa-bullseye text-xs"></i></span>
                                <h5 class="text-xs font-bold text-slate-600 uppercase tracking-wider">Meta</h5>
                            </div>
                            <p class="text-xl font-bold text-slate-900">{{ $anioItem->meta ?? 'No definida' }}</p>
                            <p class="text-xs text-slate-500 mt-1">Objetivo planificado</p>
                        </div>

                        {{-- Resultado --}}
                        <div class="bg-slate-50 rounded-xl p-4 border border-slate-200/80">
                            <div class="flex items-center gap-2 mb-2">
                                <span class="text-slate-400"><i class="fas fa-chart-line text-xs"></i></span>
                                <h5 class="text-xs font-bold text-slate-600 uppercase tracking-wider">Resultado</h5>
                            </div>
                            <p class="text-xl font-bold text-slate-900">{{ $anioItem->resultado_obtenido ?? 'Sin datos' }}</p>
                            <p class="text-xs text-slate-500 mt-1">Valor alcanzado</p>
                        </div>

                        {{-- Revisión --}}
                        <div class="bg-slate-50 rounded-xl p-4 border border-slate-200/80">
                            <div class="flex items-center gap-2 mb-2">
                                <span class="text-slate-400"><i class="fas fa-clock text-xs"></i></span>
                                <h5 class="text-xs font-bold text-slate-600 uppercase tracking-wider">Revisión</h5>
                            </div>
                            <p class="text-base font-bold text-slate-900">
                                {{ optional($anioItem->ultima_revision)->format('d/m/Y') ?? 'Pendiente' }}
                            </p>
                            <p class="text-xs text-slate-500 mt-1">Última actualización</p>
                        </div>

                        {{-- Diferencia --}}
                        @if ($anioItem->meta && $anioItem->resultado_obtenido)
                            @php
                                $diferencia = $anioItem->resultado_obtenido - $anioItem->meta;
                                $cumplida = $diferencia >= 0;
                            @endphp

                            <div class="bg-slate-50 rounded-xl p-4 border border-slate-200/80">
                                <div class="flex items-center gap-2 mb-2">
                                    <span class="{{ $cumplida ? 'text-emerald-600' : 'text-rose-600' }}">
                                        <i class="fas fa-{{ $cumplida ? 'arrow-up' : 'arrow-down' }} text-xs"></i>
                                    </span>
                                    <h5 class="text-xs font-bold text-slate-600 uppercase tracking-wider">Diferencia</h5>
                                </div>
                                <p class="text-xl font-bold {{ $cumplida ? 'text-emerald-700' : 'text-rose-700' }}">
                                    {{ $cumplida ? '+' : '' }}{{ number_format($diferencia, 2) }}
                                </p>
                                <p class="text-xs text-slate-500 mt-1">
                                    {{ $cumplida ? 'Meta superada' : 'Por debajo de meta' }}
                                </p>
                            </div>
                        @endif
                    </div>

                    {{-- Observación --}}
                    @if ($anioItem->observacion)
                        <div class="bg-slate-50 rounded-xl p-4 border border-slate-200/80">
                            <div class="flex items-start gap-3">
                                <div class="text-slate-400 mt-0.5">
                                    <i class="fas fa-comment-alt text-sm"></i>
                                </div>
                                <div>
                                    <h5 class="text-xs font-bold text-slate-700 uppercase tracking-wider mb-1">Observaciones</h5>
                                    <p class="text-slate-600 text-sm leading-relaxed">{{ $anioItem->observacion }}</p>
                                </div>
                            </div>
                        </div>
                    @endif

                    {{-- Divider --}}
                    <div class="border-t border-slate-100"></div>

                    {{-- Livewire Seguimiento --}}
                    <div>
                        @livewire('seguimiento-por-anio', ['anio' => $anioItem], key('anio-' . $anioItem->id))
                    </div>

                </div>

            </div>
        @empty
            {{-- Estado vacío estilo Apple --}}
            <div class="bg-white border border-slate-200 rounded-2xl p-12 text-center shadow-xs">
                <div class="inline-flex items-center justify-center w-14 h-14 bg-slate-50 border border-slate-200 text-slate-400 rounded-2xl mb-4">
                    <i class="fas fa-calendar-times text-xl"></i>
                </div>
                <h4 class="text-lg font-bold text-slate-900 mb-1">No hay historial disponible</h4>
                <p class="text-slate-500 text-sm max-w-sm mx-auto mb-6">
                    Aún no se han registrado datos o años anteriores para este indicador.
                </p>
                <button wire:click="mostrarFormularioNuevoAnio" class="inline-flex items-center gap-2 px-4 py-2.5 bg-slate-900 hover:bg-slate-800 text-white font-medium rounded-xl transition-all shadow-xs text-sm">
                    <i class="fas fa-plus text-xs"></i> Registrar Primer Año
                </button>
            </div>
        @endforelse
    </div>

</div>
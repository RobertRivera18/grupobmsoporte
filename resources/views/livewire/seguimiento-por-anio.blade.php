<div class="space-y-6">

    {{-- ============================= --}}
    {{-- HEADER CON AÑO --}}
    {{-- ============================= --}}
    <div class="bg-white rounded-2xl shadow-xs border border-slate-200/80 p-6 sm:p-8">
        <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-6">

            {{-- Información izquierda --}}
            <div class="flex items-center gap-4">
                <div class="bg-slate-100 p-3.5 rounded-2xl shrink-0 border border-slate-200/60 text-slate-700">
                    <i class="fas fa-calendar-alt text-xl"></i>
                </div>

                <div class="space-y-1">
                    <h3 class="text-xl sm:text-2xl font-bold text-slate-900 tracking-tight">
                        Seguimiento anual
                    </h3>

                    <p class="text-slate-500 text-sm">
                        Registro de valores para el año <span class="font-semibold text-slate-800">{{ $anio->anio }}</span>
                    </p>

                    @if ($anio->ultima_revision)
                        <p class="text-slate-400 text-xs pt-1 flex items-center gap-1.5">
                            <i class="fas fa-clock text-slate-400"></i>
                            Última revisión:
                            <span class="font-semibold text-slate-700">
                                {{ $anio->ultima_revision->format('d/m/Y H:i') }}
                            </span>
                        </p>
                    @endif
                </div>
            </div>

            {{-- Sección derecha (Año destacado) --}}
            <div class="flex flex-col sm:flex-row lg:flex-col items-start sm:items-center lg:items-end gap-3">
                <div class="bg-slate-50/80 border border-slate-200/80 px-6 py-3 rounded-2xl w-full sm:w-auto text-center">
                    <p class="text-slate-400 text-[10px] font-bold uppercase tracking-wider">Año fiscal</p>
                    <p class="text-2xl sm:text-3xl font-bold text-slate-900 tracking-tight">
                        {{ $anio->anio }}
                    </p>
                </div>
            </div>

        </div>
    </div>

    {{-- ============================= --}}
    {{-- TABLA DE PERIODOS --}}
    {{-- ============================= --}}
    <div class="bg-white rounded-2xl shadow-xs border border-slate-200/80 overflow-hidden">

        {{-- Header --}}
        <div class="px-6 py-5 border-b border-slate-100 flex items-center gap-3">
            <div class="bg-slate-100 p-2.5 rounded-xl text-slate-700">
                <i class="fas fa-table text-sm"></i>
            </div>
            <div>
                <h4 class="text-base font-bold text-slate-900 tracking-tight">
                    Valores Registrados
                </h4>
                <p class="text-slate-500 text-xs">
                    Ingrese los valores correspondientes según la frecuencia establecida
                </p>
            </div>
        </div>

        {{-- Tabla responsive --}}
        <div class="overflow-x-auto">
            <table class="min-w-full text-sm">
                <thead class="bg-slate-50/50 border-b border-slate-100">
                    <tr>
                        @foreach ($periodos as $nombre)
                            <th class="px-4 py-3.5 text-center font-semibold text-slate-500 uppercase tracking-wider text-[11px]">
                                {{ $nombre }}
                            </th>
                        @endforeach
                    </tr>
                </thead>

                <tbody class="divide-y divide-slate-100">
                    <tr>
                        @foreach ($periodos as $num => $nombre)
                            <td class="px-4 py-5">
                                <div class="flex flex-col items-center gap-3 min-w-[130px]">

                                    <input type="number" step="0.01" wire:model.defer="valores.{{ $num }}"
                                        placeholder="0.00"
                                        class="w-full max-w-[120px] px-3.5 py-2.5 bg-slate-50/70 border border-slate-200/80 rounded-xl 
                                               text-center font-semibold text-slate-900 placeholder:text-slate-300
                                               focus:bg-white focus:border-slate-400 focus:ring-2 focus:ring-slate-400/10 transition-all text-sm">

                                    <button wire:click="savePeriodo({{ $num }})"
                                        class="w-full bg-slate-900 hover:bg-slate-800 active:scale-[0.98]
                                               text-white text-xs font-semibold
                                               px-3.5 py-2 rounded-xl
                                               flex items-center justify-center gap-1.5 transition-all shadow-xs">
                                        <i class="fas fa-save text-[10px]"></i>
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
        <div class="px-6 py-3.5 border-t border-slate-100 bg-slate-50/50">
            <div class="flex items-center gap-2 text-xs text-slate-500">
                <i class="fas fa-info-circle text-slate-400"></i>
                Valores numéricos con soporte de hasta 2 decimales.
            </div>
        </div>
    </div>

    {{-- ============================= --}}
    {{-- MENSAJE ÉXITO --}}
    {{-- ============================= --}}
    @if (session()->has('message'))
        <div x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 5000)"
            class="bg-emerald-50/90 border border-emerald-200/80 p-4 rounded-2xl shadow-xs flex items-center justify-between gap-3">
            <div class="flex items-center gap-3">
                <div class="w-8 h-8 rounded-xl bg-emerald-500 text-white flex items-center justify-center shrink-0">
                    <i class="fas fa-check text-xs"></i>
                </div>
                <div>
                    <p class="font-semibold text-emerald-900 text-sm">Guardado exitosamente</p>
                    <p class="text-xs text-emerald-700">{{ session('message') }}</p>
                </div>
            </div>
            <button @click="show = false" class="text-emerald-500 hover:text-emerald-700 p-1">
                <i class="fas fa-times text-xs"></i>
            </button>
        </div>
    @endif

    {{-- ============================= --}}
    {{-- GRÁFICA Y REVIEWS --}}
    {{-- ============================= --}}
    <div class="bg-white rounded-2xl shadow-xs border border-slate-200/80 p-6 sm:p-8">
        @livewire('grafica-indicador', ['anio' => $anio], key('grafica-' . $anio->id))
    </div>

    <div class="bg-white rounded-2xl shadow-xs border border-slate-200/80 p-6 sm:p-8">
        <livewire:reviews-indicador :anio="$anio" />
    </div>

    @push('js')
        <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    @endpush

</div>
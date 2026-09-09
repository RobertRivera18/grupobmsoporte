<x-admin-layout :breadcrumbs="[
    ['name' => 'Home', 'url' => route('admin.dashboard')],
    ['name' => 'Areas', 'url' => route('admin.areas.index')],
    ['name' => $indicador->area->nombre, 'url' => route('admin.areas.show', $indicador->area)],
    ['name' => 'Indicadores'],
    ['name' => $indicador->nombre],
]">

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6 space-y-8">

        {{-- ============================= --}}
        {{-- HEADER CARD --}}
        {{-- ============================= --}}
        <div class="bg-white rounded-2xl shadow-xs border border-slate-200/80 p-6 sm:p-8 flex flex-col md:flex-row md:items-center md:justify-between gap-6">
            <div class="space-y-2">
                <div class="flex items-center gap-2 text-xs font-semibold uppercase tracking-wider text-slate-600 bg-slate-100 px-3 py-1 rounded-full w-fit border border-slate-200/60">
                    <i class="fas fa-chart-line text-slate-500"></i> Indicador de Gestión
                </div>

                <h1 class="text-2xl sm:text-3xl font-bold text-slate-900 tracking-tight">
                    {{ $indicador->nombre }}
                </h1>

                <div class="flex flex-wrap items-center gap-4 text-sm text-slate-500">
                    <span class="flex items-center gap-1.5">
                        <i class="fas fa-folder text-slate-400"></i>
                        {{ $indicador->area->nombre }}
                    </span>
                    <span class="text-slate-300">&bull;</span>
                    <span class="flex items-center gap-1.5">
                        <i class="fas fa-clock text-slate-400"></i>
                        {{ $indicador->frecuencia->nombre ?? 'N/A' }}
                    </span>
                </div>
            </div>

            {{-- Acciones --}}
            <div class="flex items-center gap-3">
                <a href="{{ route('admin.areas.show', $indicador->area) }}"
                    class="inline-flex items-center justify-center px-4 py-2.5 rounded-xl border border-slate-200 text-slate-700 bg-white hover:bg-slate-50 font-medium text-sm transition-all shadow-xs gap-2"
                    title="Volver">
                    <i class="fas fa-arrow-left text-slate-400 text-xs"></i>
                    <span>Volver</span>
                </a>
            </div>
        </div>

        {{-- ============================= --}}
        {{-- INFORMACIÓN / METODOLOGÍA Y DETALLES --}}
        {{-- ============================= --}}
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            
            {{-- Metodología (Columna Principal) --}}
            <div class="lg:col-span-2 bg-white rounded-2xl shadow-xs border border-slate-200/80 p-6 sm:p-8 flex flex-col justify-between">
                <div>
                    <h2 class="text-xs uppercase tracking-wider text-slate-400 font-bold mb-4 flex items-center gap-2">
                        <i class="fas fa-calculator text-slate-500"></i> Metodología y Forma de Cálculo
                    </h2>
                    <div class="text-slate-700 leading-relaxed text-sm sm:text-base bg-slate-50/70 p-4 rounded-xl border border-slate-200/60">
                        {{ $indicador->forma_calculo }}
                    </div>
                </div>
            </div>

            {{-- Ficha Técnica / Detalles (Columna Lateral) --}}
            <div class="bg-white rounded-2xl shadow-xs border border-slate-200/80 p-6 sm:p-8 space-y-6">
                <h2 class="text-xs uppercase tracking-wider text-slate-400 font-bold flex items-center gap-2">
                    <i class="fas fa-info-circle text-slate-500"></i> Ficha Técnica
                </h2>

                <div class="space-y-4">
                    <div class="p-4 rounded-xl bg-slate-50/70 border border-slate-200/60">
                        <span class="text-xs font-medium text-slate-400 uppercase tracking-wider block mb-1">Frecuencia</span>
                        <span class="text-slate-900 font-semibold text-sm flex items-center gap-2">
                            <i class="fas fa-calendar-alt text-slate-400"></i>
                            {{ $indicador->frecuencia->nombre ?? 'No especificada' }}
                        </span>
                    </div>

                    <div class="p-4 rounded-xl bg-slate-50/70 border border-slate-200/60">
                        <span class="text-xs font-medium text-slate-400 uppercase tracking-wider block mb-1">Área Responsable</span>
                        <span class="text-slate-900 font-semibold text-sm flex items-center gap-2">
                            <i class="fas fa-building text-slate-400"></i>
                            {{ $indicador->area->nombre }}
                        </span>
                    </div>
                </div>
            </div>

        </div>

        {{-- ============================= --}}
        {{-- LIVEWIRE SEGUIMIENTO --}}
        {{-- ============================= --}}
        <div class="bg-white rounded-2xl shadow-xs border border-slate-200/80 p-6 sm:p-8">
            <div class="mb-6 pb-4 border-b border-slate-100">
                <h2 class="text-lg font-bold text-slate-900">Seguimiento de Registros</h2>
                <p class="text-sm text-slate-500">Historial y evolución periódica de los valores del indicador.</p>
            </div>
            <div>
                @livewire('seguimiento-indicadores', ['indicador' => $indicador])
            </div>
        </div>

    </div>

</x-admin-layout>
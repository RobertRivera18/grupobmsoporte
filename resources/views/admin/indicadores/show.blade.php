<x-admin-layout :breadcrumbs="[
    ['name' => 'Home', 'url' => route('admin.dashboard')],
    ['name' => 'Areas', 'url' => route('admin.areas.index')],
    ['name' => $indicador->area->nombre],
    ['name' => 'Indicadores', 'url' => route('admin.areas.show', $indicador->area)],
    ['name' => $indicador->nombre],
]">

    <div class="max-w-5xl mx-auto px-4 sm:px-6 space-y-10">

        {{-- ============================= --}}
        {{-- HEADER --}}
        {{-- ============================= --}}
        <div class="space-y-6">

            <div class="flex flex-col sm:flex-row sm:items-start sm:justify-between gap-6">

                {{-- Título --}}
                <div>
                    <h1 class="text-2xl sm:text-3xl lg:text-4xl font-light text-gray-900 mb-3">
                        {{ $indicador->nombre }}
                    </h1>

                    <div class="flex flex-wrap items-center gap-3 text-sm text-gray-500">
                        <span class="flex items-center gap-2">
                            <i class="fas fa-folder text-gray-400"></i>
                            {{ $indicador->area->nombre }}
                        </span>
                    </div>
                </div>

                {{-- Acciones --}}
                <div class="flex gap-4 self-start sm:self-auto">
                    <a href="{{ route('admin.areas.show', $indicador->area) }}"
                        class="p-2 rounded-lg text-gray-600 hover:text-gray-900 hover:bg-gray-100 transition"
                        title="Volver">
                        <i class="fas fa-arrow-left"></i>
                    </a>

                    <button class="p-2 rounded-lg text-gray-600 hover:text-gray-900 hover:bg-gray-100 transition"
                        title="Editar">
                        <i class="fas fa-edit"></i>
                    </button>
                </div>

            </div>

            <div class="h-px bg-gradient-to-r from-gray-200 via-gray-300 to-transparent"></div>
        </div>

        {{-- ============================= --}}
        {{-- INFORMACIÓN --}}
        {{-- ============================= --}}
        <div class="space-y-10">

            {{-- Metodología --}}
            <div>
                <h2 class="text-xs uppercase tracking-wider text-gray-500 mb-3 font-medium">
                    Metodología
                </h2>
                <p class="text-gray-700 leading-relaxed text-sm sm:text-base">
                    {{ $indicador->forma_calculo }}
                </p>
            </div>

            {{-- Detalles --}}
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-6 pt-6 border-t border-gray-200">

                <div>
                    <h3 class="text-xs uppercase tracking-wider text-gray-500 mb-2 font-medium">
                        Frecuencia
                    </h3>
                    <p class="text-gray-900">{{ $indicador->frecuencia->nombre }}</p>
                    
                </div>

                <div>
                    <h3 class="text-xs uppercase tracking-wider text-gray-500 mb-2 font-medium">
                        Área
                    </h3>
                    <p class="text-gray-900 text-sm sm:text-base">
                        {{ $indicador->area->nombre }}
                    </p>
                </div>

            </div>

        </div>

        {{-- ============================= --}}
        {{-- LIVEWIRE --}}
        {{-- ============================= --}}
        <div class="pt-8 border-t border-gray-200">
            @livewire('seguimiento-indicadores', ['indicador' => $indicador])
        </div>

    </div>

</x-admin-layout>

<x-admin-layout :breadcrumbs="[
    ['name' => 'Home', 'url' => route('admin.dashboard')],
    ['name' => 'Auditorías', 'url' => route('admin.auditorias.index')],
    ['name' => $auditoria->anio],
]">

    {{-- ================= CABECERA ================= --}}
    <div class="bg-white border-b border-gray-200">
        <div class="max-w-7xl mx-auto px-6 py-6">

            <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">

                {{-- Título --}}
                <div>
                    <h2 class="text-2xl font-bold text-gray-800 flex items-center gap-2">
                        <i class="fas fa-clipboard-check text-indigo-600"></i>
                        Auditoría {{ $auditoria->anio }}
                    </h2>

                    <p class="text-sm text-gray-500 mt-1">
                        Gestión de procesos y normas ISO asociadas a la auditoría
                    </p>
                </div>

                {{-- Fechas --}}
                <div class="flex items-center gap-3 bg-gray-50 border border-gray-200 rounded-xl px-4 py-2">
                    <div class="text-sm text-gray-500">
                        <i class="fas fa-calendar-alt mr-1"></i>
                        Periodo
                    </div>
                    <div class="text-sm font-medium text-gray-700">
                        {{ $auditoria->fecha_inicio }}
                        <span class="mx-1 text-gray-400">—</span>
                        {{ $auditoria->fecha_fin }}
                    </div>
                </div>

            </div>
        </div>
    </div>

    {{-- ================= CONTENIDO ================= --}}
    <div class="max-w-7xl mx-auto px-6 py-8 space-y-6">

        {{-- Livewire: Procesos de auditoría --}}
        @livewire('create-auditoria-proceso', ['auditoria' => $auditoria])

    </div>

</x-admin-layout>

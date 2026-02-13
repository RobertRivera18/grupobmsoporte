<x-admin-layout :breadcrumbs="[
    ['name' => 'Home', 'url' => route('admin.dashboard')],
    ['name' => 'Auditorías', 'url' => route('admin.auditorias.index')],
    ['name' => $auditoria->anio, 'url' => route('admin.auditorias.show', $auditoria)],
    ['name' => $proceso->area->nombre],
    ['name' => $proceso->nombre],
]">

    @php
        $estadoColor = match ($auditoria->estado) {
            'planificada' => 'bg-yellow-500',
            'en_proceso' => 'bg-blue-600',
            'cerrada' => 'bg-green-600',
            default => 'bg-gray-500',
        };
    @endphp

    {{-- HEADER PRINCIPAL --}}
    <div class="mb-8 flex flex-col md:flex-row md:items-center md:justify-between gap-4">

        <div>
            <h1 class="text-2xl font-bold text-gray-900">
                {{ $proceso->nombre }}
            </h1>
            <p class="text-sm text-gray-500 mt-1">
                Área: {{ $proceso->area->nombre }} · Auditoría {{ $auditoria->anio }}
            </p>
        </div>

        <div>
            <span class="px-4 py-2 text-sm font-semibold text-white rounded-full {{ $estadoColor }}">
                {{ $auditoria->estado }}
            </span>
        </div>

    </div>

    {{-- TARJETAS RESUMEN (ESCANEO RÁPIDO) --}}
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">

        <div class="bg-white rounded-xl shadow-sm border p-5">
            <p class="text-xs uppercase text-gray-500 tracking-wide">Auditor</p>
            <p class="mt-2 text-lg font-semibold text-gray-800">
                {{ $proceso->auditor->name }}
            </p>
        </div>

        <div class="bg-white rounded-xl shadow-sm border p-5">
            <p class="text-xs uppercase text-gray-500 tracking-wide">Responsable</p>
            <p class="mt-2 text-lg font-semibold text-gray-800">
                {{ $proceso->responsable->name ?? 'No asignado' }}
            </p>
        </div>

        <div class="bg-white rounded-xl shadow-sm border p-5">
            <p class="text-xs uppercase text-gray-500 tracking-wide">Fecha de inicio</p>
            <p class="mt-2 text-lg font-semibold text-gray-800">
                {{ optional($auditoria->fecha_inicio)->format('d/m/Y') ?? '—' }}
            </p>
        </div>

    </div>

    {{-- SECCIÓN DE CONTENIDO --}}
    <div>

        <h2 class="text-lg font-semibold text-gray-800 mb-4">
            Gestión del proceso
        </h2>

        <div>
            <livewire:admin.auditorias.informes-manager :proceso="$proceso" />
        </div>
    </div>
</x-admin-layout>

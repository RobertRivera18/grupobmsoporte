<x-admin-layout :breadcrumbs="[
    ['name' => 'Home', 'url' => route('admin.dashboard')],
    ['name' => 'Auditorías', 'url' => route('admin.auditorias.index')],
    ['name' => 'Editar Auditoría'],
]">

    <div class="max-w-3xl mx-auto">

        <!-- Header -->
        <div class="mb-10">
            <span
                class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium 
                       bg-amber-50 text-amber-600 mb-3">
                Auditoría Interna · ISO 9001
            </span>

            <h1 class="text-3xl font-bold text-gray-900 tracking-tight">
                Editar Auditoría
            </h1>

            <p class="mt-2 text-gray-500 max-w-xl">
                Modifique la información general de la auditoría interna.
            </p>
        </div>

        <!-- Form Card -->
        <form action="{{ route('admin.auditorias.update', $auditoria) }}" method="POST"
            class="bg-white rounded-2xl p-8 shadow-lg border border-gray-100 space-y-10">

            @csrf
            @method('PUT')

            <!-- Errores -->
            <x-validation-errors :errors="$errors" />

            <!-- Sección: Información General -->
            <div>
                <h2 class="text-lg font-semibold text-gray-800 mb-1">
                    Información general
                </h2>
                <p class="text-sm text-gray-500 mb-6">
                    Datos base de la auditoría interna.
                </p>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">

                    <!-- Año -->
                    <div class="space-y-1">
                        <x-label class="text-sm font-medium text-gray-700">
                            Año
                        </x-label>
                        <x-input
                            class="w-full rounded-lg border-gray-300 
                                   focus:border-indigo-500 focus:ring-indigo-500"
                            type="number"
                            name="anio"
                            value="{{ old('anio', $auditoria->anio) }}"
                            required />
                    </div>

                    <!-- Fecha inicio -->
                    <div class="space-y-1">
                        <x-label class="text-sm font-medium text-gray-700">
                            Fecha de inicio
                        </x-label>
                        <x-input
                            class="w-full rounded-lg border-gray-300 
                                   focus:border-indigo-500 focus:ring-indigo-500"
                            type="date"
                            name="fecha_inicio"
                            value="{{ old('fecha_inicio', $auditoria->fecha_inicio ? $auditoria->fecha_inicio->format('Y-m-d') : '') }}"
                            required />
                    </div>

                    <!-- Fecha fin -->
                    <div class="space-y-1">
                        <x-label class="text-sm font-medium text-gray-700">
                            Fecha de fin
                        </x-label>
                        <x-input
                            class="w-full rounded-lg border-gray-300 
                                   focus:border-indigo-500 focus:ring-indigo-500"
                            type="date"
                            name="fecha_fin"
                            value="{{ old('fecha_fin', $auditoria->fecha_fin ? $auditoria->fecha_fin->format('Y-m-d') : '') }}" />
                    </div>

                </div>
            </div>

            <!-- Acciones -->
            <div class="flex items-center justify-between pt-6 border-t border-gray-100">

                <a href="{{ route('admin.auditorias.index') }}"
                    class="text-sm font-medium text-gray-500 hover:text-gray-700 transition">
                    ← Volver a auditorías
                </a>

                <div class="flex gap-3">
                    <a href="{{ route('admin.auditorias.index') }}"
                        class="px-4 py-2 text-sm rounded-lg bg-gray-100 text-gray-600 
                               hover:bg-gray-200 transition">
                        Cancelar
                    </a>

                    <x-button class="px-6 py-2 text-sm shadow-sm bg-amber-600 hover:bg-amber-700">
                        Actualizar Auditoría
                    </x-button>
                </div>

            </div>

        </form>
    </div>
</x-admin-layout>

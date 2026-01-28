<x-admin-layout :breadcrumbs="[
    ['name' => 'Home', 'url' => route('admin.dashboard')],
    ['name' => 'Areas', 'url' => route('admin.areas.index')],
    ['name' => 'Nuevo'],
]">

    <div class="max-w-2xl mx-auto">

        <!-- Header del formulario -->
        <div class="mb-6">
            <h1 class="text-2xl font-bold text-gray-800">Crear nuevo Departamento</h1>
            <p class="text-gray-500 mt-1">Complete los datos para registrar un nuevo departamento dentro del sistema.</p>
        </div>

        <form action="{{ route('admin.areas.store') }}" method="POST"
            class="bg-white rounded-2xl p-8 shadow-md border border-gray-100 space-y-6">
            @csrf

            <!-- Errores -->
            <x-validation-errors :errors="$errors" class="mb-4" />

            <!-- Campo nombre -->
            <div class="space-y-2">
                <x-label class="font-semibold text-gray-700">Nombre del Departamento</x-label>
                <x-input
                    class="w-full px-4 py-2 rounded-lg border-gray-300 focus:border-indigo-500 focus:ring-indigo-500"
                    type="text" name="nombre" placeholder="Ej. Recursos Humanos" required />
            </div>

            <!-- Acciones -->
            <div class="flex justify-end">

                <a href="{{ route('admin.areas.index') }}"
                    class="mr-3 px-4 py-2 text-sm text-gray-600 bg-gray-100 rounded-lg hover:bg-gray-200 transition">
                    Cancelar
                </a>

                <x-button class="px-5 py-2 text-sm">
                    Crear Departamento
                </x-button>
            </div>

        </form>
    </div>

</x-admin-layout>

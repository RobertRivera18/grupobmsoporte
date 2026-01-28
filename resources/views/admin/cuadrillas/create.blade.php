<x-admin-layout :breadcrumbs="[
    ['name' => 'Home', 'url' => route('admin.dashboard')],
    ['name' => 'Cuadrillas', 'url' => route('admin.cuadrillas.index')],
    ['name' => 'Nuevo'],
]">

    <form action="{{ route('admin.cuadrillas.store') }}" method="POST" class="bg-white rounded-lg p-6 shadow-lg">
        @csrf

        <x-validation-errors :errors="$errors" class="mb-4" />

        {{-- Nombre de la cuadrilla --}}
        <div class="mb-4">
            <x-label class="mb-2" for="cua_nombre">Nombre de la cuadrilla</x-label>
            <x-input class="w-full" type="text" id="cua_nombre" name="cua_nombre" value="{{ old('cua_nombre') }}"
                placeholder="Escriba el nombre de la Cuadrilla" />
        </div>

        {{-- Empresa y Ciudad --}}
        <div class="flex flex-col md:flex-row gap-4">
            <div class="flex-1">
                <x-label for="cua_empresa" class="mb-2">Empresa</x-label>
                <select id="cua_empresa" name="cua_empresa"
                        class="w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500">
                    <option value="1" {{ old('cua_empresa') == 1 ? 'selected' : '' }}>Claro</option>
                    <option value="2" {{ old('cua_empresa') == 2 ? 'selected' : '' }}>CNEL</option>
                </select>
            </div>

            <div class="flex-1">
                <x-label for="cua_ciudad" class="mb-2">Ciudad</x-label>
                <select id="cua_ciudad" name="cua_ciudad"
                        class="w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500">
                    <option value="1" {{ old('cua_ciudad') == 1 ? 'selected' : '' }}>Guayaquil</option>
                    <option value="2" {{ old('cua_ciudad') == 2 ? 'selected' : '' }}>Quito</option>
                </select>
            </div>
        </div>

        {{-- Botón --}}
        <div class="flex justify-end mt-6">
            <x-button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-md transition duration-200">
                <i class="fas fa-save mr-2"></i>
                Crear Cuadrilla
            </x-button>
        </div>
    </form>

</x-admin-layout>

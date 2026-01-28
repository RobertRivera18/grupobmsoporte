<x-admin-layout :breadcrumbs="[
    [
        'name' => 'Home',
        'url' => route('admin.dashboard'),
    ],
    [
        'name' => 'Equipos',
        'url' => route('admin.equipos.index'),
    ],
    [
        'name' => 'Nuevo',
    ],
]">
    <x-slot name="action">
        <a class="text-white bg-gray-800 hover:bg-gray-900 focus:outline-none focus:ring-4 focus:ring-gray-300 font-medium rounded-lg text-sm px-5 py-2.5 me-2 mb-2 dark:bg-gray-800 dark:hover:bg-gray-700 dark:focus:ring-gray-700 dark:border-gray-700"
            href="{{ route('admin.equipos.create') }}">Nuevo</a>
    </x-slot>
    <form action="{{ route('admin.equipos.store') }}" method="POST" class="bg-white rounded-lg p-6 shadow-lg">
        @csrf

        <x-validation-errors :errors="$errors" class="mb-4" />

        <div class="mb-4">
            <x-label class="mb-2">Nombre de Equipo</x-label>
            <x-input class="w-full" type="text" name="nombre" value="{{ old('nombre') }}"
                placeholder="Escriba el nombre del Equipo Informatico" />
        </div>
        <div class="mb-4">
            <x-label class="mb-2">Marca de Equipo</x-label>
            <x-input class="w-full" type="text" name="marca" value="{{ old('marca') }}"
                placeholder="Escriba el marca del Equipo Informatico" />
        </div>
        <div class="mb-4">
            <x-label class="mb-2">Modelo</x-label>
            <x-input class="w-full" type="text" name="modelo" value="{{ old('modelo') }}"
                placeholder="Escriba el modelo del Equipo Informatico" />
        </div>
        <div class="mb-4">
            <x-label class="mb-2">Serie/IMEI de Equipo</x-label>
            <x-input class="w-full" type="text" name="serie" value="{{ old('serie') }}"
                placeholder="Escriba la serie del equipo" />
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 mb-4">
            <div>
                <x-label class="mb-2 block">Ubicación: Ciudad</x-label>
                <x-select name="ciudad" class="w-full">
                    <option value="1">Guayaquil</option>
                    <option value="2">Quito</option>
                </x-select>
            </div>

            <div>
                <x-label class="mb-2 block">Tipo de equipo</x-label>
                <x-select name="tipo_equipo_id" class="w-full">
                    @foreach ($tipoEquipos as $tipo)
                        <option value="{{ $tipo->id }}">{{ $tipo->nombre }}</option>
                    @endforeach
                </x-select>
            </div>
        </div>


        <div class="mb-4">
            <x-label class="mb-2">Equipo Para:</x-label>
            <x-select name='datos'>
                <option value="1">Administrativos</option>
                <option value="2">Tecnicos Claro/IMEI</option>
                <option value="3">Equipos Tecnicos Medidores</option>

            </x-select>
        </div>

        <div class="flex justify-end">
            <x-button>
                <i class="fas fa-save mr-2"></i>
                Crear Equipo
            </x-button>
        </div>


    </form>

</x-admin-layout>

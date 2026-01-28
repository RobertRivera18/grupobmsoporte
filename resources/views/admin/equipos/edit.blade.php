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
        'name' => 'Editar',
    ],
]">

    <form action="{{ route('admin.equipos.update', $equipo) }}" method="POST" class="bg-white rounded-lg p-6 shadow-lg">
        @csrf
        @method('PUT')

        <x-validation-errors :errors="$errors" class="mb-4" />

        <div class="mb-4">
            <x-label class="mb-2">Nombre de Equipo</x-label>
            <x-input class="w-full" type="text" name="nombre" value="{{ $equipo->nombre }}"
                placeholder="Escriba el nombre del Equipo Informatico" />
        </div>
        <div class="mb-4">
            <x-label class="mb-2">Marca de Equipo</x-label>
            <x-input class="w-full" type="text" name="marca" value="{{ $equipo->marca }}"
                placeholder="Escriba el marca del Equipo Informatico" />
        </div>
        <div class="mb-4">
            <x-label class="mb-2">Modelo</x-label>
            <x-input class="w-full" type="text" name="modelo" value="{{ $equipo->modelo }}"
                placeholder="Escriba el modelo del Equipo Informatico" />
        </div>
        <div class="mb-4">
            <x-label class="mb-2">Serie/IMEI de Equipo</x-label>
            <x-input class="w-full" type="text" name="serie" value="{{ $equipo->serie }}"
                placeholder="Escriba la serie del equipo" />
        </div>


        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 mb-4">
            <div>
                <x-label class="mb-2 block">Ubicación: Ciudad</x-label>
                <x-select name="ciudad" class="w-full">
                    <option value="1" {{ $equipo->ciudad == 1 ? 'selected' : '' }}>Guayaquil</option>
                    <option value="2" {{ $equipo->ciudad == 2 ? 'selected' : '' }}>Quito</option>
                </x-select>
            </div>

            <div>
                <x-label class="mb-2 block">Tipo de Equipo</x-label>
                <x-select name="tipo_equipo_id" class="w-full">
                    <option value="">-- Selecciona un tipo --</option>
                    @foreach ($tipoEquipos as $tipo)
                        <option value="{{ $tipo->id }}"
                            {{ $equipo->tipo_equipo_id == $tipo->id ? 'selected' : '' }}>
                            {{ ucfirst($tipo->nombre) }}
                        </option>
                    @endforeach
                </x-select>
            </div>
        </div>


        <div class="mb-4">
            <x-label class="mb-2">Equipo para:</x-label>
            <x-select name="datos">
                <option value="1" {{ $equipo->datos == 1 ? 'selected' : '' }}>Administrativos</option>
                <option value="2" {{ $equipo->datos == 2 ? 'selected' : '' }}>Equipos Tecnicos Claro</option>
                <option value="3" {{ $equipo->datos == 3 ? 'selected' : '' }}>Equipos CNEL</option>
            </x-select>
        </div>

        <div class="flex justify-end">
            <x-button>Actualizar Equipo</x-button>
        </div>

    </form>

</x-admin-layout>

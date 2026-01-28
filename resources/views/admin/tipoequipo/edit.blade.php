<x-admin-layout :breadcrumbs="[
[
'name'=>'Home',
'url'=>route('admin.dashboard')

],
[
'name'=>'Tipo de Equipos',
'url'=>route('admin.tipoequipos.index')
],
[
'name'=>'Editar',
],


]">
    <div class="bg-white shadow rounded-lg p-6">

        <x-validation-errors :errors="$errors" class="mb-4" />
        <form action="{{ route('admin.tipoequipos.update', $tipoequipo) }}" method="POST">
            @method('PUT')
            @csrf
            <div class="mb-4">
                <x-label class="mb-1 ">
                    Nombre del Tipo de Equipo
                </x-label>
                <x-input class="w-full" name="nombre" placeholder="Ingrese Nombre del Tipo de Equipo"
                    value="{{ old('nombre', $tipoequipo->nombre) }}" />
            </div>

            <div class="flex justify-start">
                <x-button class="mr-2">
                    Actualizar Tipo Equipo
                </x-button>
                <x-danger-button class="mr-2" onclick="deleteRole()">Eliminar</x-danger-button>
            </div>
        </form>
    </div>
    <form action="{{ route('admin.tipoequipos.destroy', $tipoequipo) }}" method="POST" id="formDelete">
        @method('DELETE')
        @csrf
    </form>

    @push('js')
        <script>
            function deleteRole() {
                let form = document.getElementById('formDelete')
                form.submit();
            }
        </script>
    @endpush
</x-admin-layout>

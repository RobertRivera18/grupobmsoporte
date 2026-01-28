<x-admin-layout :breadcrumbs="[
[
'name'=>'Home',
'url'=>route('admin.dashboard')

],
[
'name'=>'Tipo de Equipos',
'url'=>route('admin.tipocontratos.index')
],
[
'name'=>'Editar',
],


]">
    <div class="bg-white shadow rounded-lg p-6">

        <x-validation-errors :errors="$errors" class="mb-4" />
        <form action="{{ route('admin.tipocontratos.update', $tipocontrato) }}" method="POST">
            @method('PUT')
            @csrf
            <div class="mb-4">
                <x-label class="mb-1 ">
                    Nombre del Tipo de Contrato
                </x-label>
                <x-input class="w-full" name="name" placeholder="Ingrese Nombre del Tipo de Contrato"
                    value="{{ old('name', $tipocontrato->name) }}" />
            </div>

            <div class="flex justify-start">
                <x-button class="mr-2">
                    Actualizar Tipo Contrato
                </x-button>
                <x-danger-button class="mr-2" onclick="deleteContrato()">Eliminar</x-danger-button>
            </div>
        </form>
    </div>
    <form action="{{ route('admin.tipocontratos.destroy', $tipocontrato) }}" method="POST" id="formDelete">
        @method('DELETE')
        @csrf
    </form>

    @push('js')
        <script>
            function deleteContrato() {
                let form = document.getElementById('formDelete')
                form.submit();
            }
        </script>
    @endpush
</x-admin-layout>

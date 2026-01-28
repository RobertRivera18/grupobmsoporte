<x-admin-layout :breadcrumbs="[
[
'name'=>'Home',
'url'=>route('admin.dashboard')

],
[
'name'=>'Permisos',
'url'=>route('admin.permissions.index')
],
[
'name'=>'Editar',
],


]">
    <div class="bg-white shadow rounded-lg p-6">

        <x-validation-errors :errors="$errors" class="mb-4" />
        <form action="{{ route('admin.permissions.update', $permission) }}" method="POST">
            @method('PUT')
            @csrf
            <div class="mb-4">
                <x-label class="mb-1 ">
                    Nombre del Permiso
                </x-label>
                <x-input class="w-full" name="name" placeholder="Ingrese Nombre del Permiso"
                    value="{{ old('name', $permission->name) }}" />
            </div>
            <div class="flex justify-end">
                <x-button class="mr-2">
                    Actualizar Permiso
                </x-button>
                <x-danger-button class="mr-2" onclick="deletePermission()">Eliminar</x-danger-button>
            </div>
        </form>
    </div>
    <form action="{{ route('admin.permissions.destroy', $permission) }}" method="POST" id="formDelete">
        @method('DELETE')
        @csrf
    </form>

@push('js')
<script>
    function deletePermission() {
        let form = document.getElementById('formDelete')
        form.submit();
    }
</script>
@endpush
</x-admin-layout>

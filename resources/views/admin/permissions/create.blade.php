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
'name'=>'Nuevo',
],


]">
    <div class="bg-white shadow rounded-lg p-6">
        <x-validation-errors :errors="$errors" class="mb-4" />
        <form action="{{ route('admin.permissions.store') }}" method="POST">
            @csrf
            <div class="mb-4">
                <x-label class="mb-1 ">
                    Nombre del Permiso
                </x-label>
                <x-input class="w-full" name="name" placeholder="Ingrese Nombre del Permiso" />
            </div>
            <x-button>Crear Permiso</x-button>
        </form>

    </div>
</x-admin-layout>

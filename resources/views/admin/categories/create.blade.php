<x-admin-layout :breadcrumbs="[
[
'name'=>'Home',
'url'=>route('admin.dashboard')

],
[
'name'=>'Categorias',
'url'=>route('admin.categories.index')
],
[
'name'=>'Nuevo',
],


]">
    <form action="{{ route('admin.categories.store') }}" method="POST" class="bg-white rounded-lg p-6 shadow-lg">
        @csrf

        <x-validation-errors :errors="$errors"  class="mb-4"/>

        <div class="mb-4">
            <x-label class="mb-2">Nombre de Categoria</x-label>
            <x-input class="w-full" type="text" name="name" placeholder="Escriba el nombre de la Categoria" />
        </div>

        <div class="flex justify-end">
            <x-button>Crear Categoria</x-button>
        </div>

    </form>

</x-admin-layout>

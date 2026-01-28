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
'name'=>'Editar',
],


]">
    <form action="{{ route('admin.areas.update', $area) }}" method="POST"
        class="bg-white rounded-lg p-6 shadow-lg">
        @csrf
        @method('PUT')

        <x-validation-errors :errors="$errors" class="mb-4" />

        <div class="mb-4">
            <x-label class="mb-2">Nombre de Categoria</x-label>
            <x-input class="w-full" type="text" name="name" value="{{ $category->name }}"
                placeholder="Escriba el nombre de la Categoria" />
        </div>

        <div class="flex justify-end">
            <x-danger-button class="mr-2" onclick="deleteCategory()">Eliminar</x-danger-button>
            <x-button>Actualizar Categoria</x-button>

        </div>

    </form>

    <form action="{{ route('admin.categories.destroy', $category) }}" method="POST" id="formDelete">
        @method('DELETE')
        @csrf
    </form>

    @push('js')
        <script>
            function deleteCategory(){
                let form =document.getElementById('formDelete')
                form.submit();
            }
        </script>
    @endpush

</x-admin-layout>

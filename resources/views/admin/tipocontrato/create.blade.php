<x-admin-layout :breadcrumbs="[
[
'name'=>'Home',
'url'=>route('admin.dashboard')

],
[
'name'=>'Tipos de Equipos',
'url'=>route('admin.tipocontratos.index')
],
[
'name'=>'Nuevo',
],


]">
    <div class="bg-white shadow rounded-lg p-6">
        <x-validation-errors :errors="$errors" class="mb-4" />
        <form action="{{ route('admin.tipocontratos.store') }}" method="POST">
            @csrf
            <div class="mb-4">
                <x-label class="mb-1 ">
                    Nombre del Contrato
                </x-label>
                <x-input class="w-full" name="name" placeholder="Ingrese Nombre del Contrato" />
            </div>

           
            <x-button>Crear Tipo de Contrato</x-button>
        </form>

    </div>
</x-admin-layout>

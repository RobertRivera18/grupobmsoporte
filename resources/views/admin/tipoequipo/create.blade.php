<x-admin-layout :breadcrumbs="[
[
'name'=>'Home',
'url'=>route('admin.dashboard')

],
[
'name'=>'Tipos de Equipos',
'url'=>route('admin.tipoequipos.index')
],
[
'name'=>'Nuevo',
],


]">
    <div class="bg-white shadow rounded-lg p-6">
        <x-validation-errors :errors="$errors" class="mb-4" />
        <form action="{{ route('admin.tipoequipos.store') }}" method="POST">
            @csrf
            <div class="mb-4">
                <x-label class="mb-1 ">
                    Nombre del Equipo
                </x-label>
                <x-input class="w-full" name="nombre" placeholder="Ingrese Nombre del tipo de Equipo" />
            </div>

           
            <x-button>Crear Tipo de Equipo</x-button>
        </form>

    </div>
</x-admin-layout>

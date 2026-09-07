<x-admin-layout :breadcrumbs="[
[
'name'=>'Home',
'url'=>route('admin.dashboard')

],
[
'name'=>'Solicitudes Salida Equipos',
'url'=>route('admin.salidas.index')
],
[
'name'=>'Nuevo',
],


]">
    @livewire('salida-equipos')
</x-admin-layout>

<x-admin-layout :breadcrumbs="[
[
'name'=>'Home',
'url'=>route('admin.dashboard')

],
[
'name'=>'Revisiones Vehiculares',
'url'=>route('admin.revisiones.index')
],

]">
    @livewire('revisiones-vehiculares-all')
</x-admin-layout>

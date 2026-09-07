<x-admin-layout :breadcrumbs="[
    [
        'name' => 'Home',
        'url' => route('admin.dashboard'),
    ],
    [
        'name' => 'Inventarios',
        'url' => route('admin.tecnicos.index'),
    ],
]">

@livewire('inventario-listado')
</x-admin-layout>

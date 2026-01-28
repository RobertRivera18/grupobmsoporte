<x-admin-layout :breadcrumbs="[
    [
        'name' => 'Home',
        'url' => route('admin.dashboard'),
    ],
    [
        'name' => 'Equipos',
        'url' => route('admin.equipos.index'),
    ],
    [
        'name' => 'Asigancion Cuadrillas-Equipos',
    ],
]">

    @livewire('equipos-cuadrillas')


</x-admin-layout>

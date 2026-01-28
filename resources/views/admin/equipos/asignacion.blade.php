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
        'name' => 'Asigancion Usuarios-Equipos',
    ],
]">

    @livewire('usuarios-equipos')


</x-admin-layout>

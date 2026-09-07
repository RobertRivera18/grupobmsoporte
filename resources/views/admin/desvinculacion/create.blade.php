<x-admin-layout :breadcrumbs="[
    [
        'name' => 'Home',
        'url' => route('admin.dashboard'),
    ],
    [
        'name' => 'Solicitudes Desvinculación',
        'url' => route('admin.desvinculacion.index'),
    ],
    [
        'name' => 'Nueva',
    ],
]">
    @livewire('crear-desvinculacion')
</x-admin-layout>
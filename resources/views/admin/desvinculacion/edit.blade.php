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
        'name' => 'Gestionar Equipos',
    ],
]">

    @livewire('editar-desvinculacion', ['solicitudId' => $solicitud->id])



</x-admin-layout>

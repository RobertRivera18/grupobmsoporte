<x-admin-layout :breadcrumbs="[
    [
        'name' => 'Home',
        'url' => route('admin.dashboard'),
    ],
    [
        'name' => 'Incidentes',
        'url' => route('admin.incidentes.index'),
    ],
    
]">
    @livewire('admin.incidentes-index')
</x-admin-layout>

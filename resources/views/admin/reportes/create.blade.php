<x-admin-layout :breadcrumbs="[
    [
        'name' => 'Home',
        'url' => route('admin.dashboard'),
    ],
    [
        'name' => 'Crear Reportes Recargas Mensuales',
        'url' => route('admin.reportes.index'),
    ],
]">

@livewire('create-report-recargas')
    

   

</x-admin-layout>

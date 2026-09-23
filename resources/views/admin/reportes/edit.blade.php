<x-admin-layout :breadcrumbs="[
    ['name' => 'Home', 'url' => route('admin.dashboard')],
    ['name' => 'Reportes Recargas', 'url' => route('admin.reportes.index')],
    ['name' => 'Editar Reportes Recargas Mensuales'],
]">

    @livewire('admin.reportes.editar-reporte', ['reporte' => $reporte])

</x-admin-layout>
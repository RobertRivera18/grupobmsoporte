<x-admin-layout :breadcrumbs="[
    ['name' => 'Home', 'url' => route('admin.dashboard')],
    ['name' => 'Listado de Vehiculo', 'url' => route('admin.vehiculos.index')],
    ['name' => 'Revision'.' '. $vehiculo->placa],
]">
    @livewire('vehiculo-inspeccion-form', ['vehiculo' => $vehiculo])

</x-admin-layout>

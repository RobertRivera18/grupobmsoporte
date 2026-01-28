<x-admin-layout :breadcrumbs="[
    [
        'name' => 'Home',
        'url' => route('admin.dashboard'),
    ],
    [
        'name' => 'Areas',
        'url' => route('admin.areas.index'),
    ],
    [
        'name' => $area->nombre,
    ],
]">
    <h2 class="font-semibold text-xl text-gray-800 leading-tight p-6">
        <i class="fas fa-building mr-2"></i> Area/Departamento: <span class="font-bold">{{ $area->nombre }}</span>
    </h2>
    <hr>

    @livewire('indicadores-index', ['area' => $area])
</x-admin-layout>

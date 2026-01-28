<x-admin-layout :breadcrumbs="[
    [
        'name' => 'Home',
        'url' => route('admin.dashboard'),
    ],
    [
        'name' => 'Reportes Recargas Mensuales',
        'url' => route('admin.reportes.index'),
    ],
]">


    <x-slot name="action">
        <a class="text-white bg-gray-800 hover:bg-gray-900 focus:outline-none focus:ring-4 focus:ring-gray-300 font-medium rounded-lg text-sm px-5 py-2.5 me-2 mb-2 dark:bg-gray-800 dark:hover:bg-gray-700 dark:focus:ring-gray-700 dark:border-gray-700"
            href="{{ route('admin.reportes.create') }}">Nuevo</a>
    </x-slot>

   
@livewire('report-recargas')
</x-admin-layout>

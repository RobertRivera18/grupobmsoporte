<x-admin-layout :breadcrumbs="[
    ['name' => 'Home', 'url' => route('admin.dashboard')],
    ['name' => 'Auditorias', 'url' => route('admin.auditorias.index')],
]">

    @role('Admin|LiderAuditorias')
        <x-slot name="action">
            <a href="{{ route('admin.auditorias.create') }}"
                class="inline-flex items-center px-5 py-2.5 text-sm font-medium text-white bg-indigo-600 rounded-xl shadow-md hover:bg-indigo-700 focus:ring-4 focus:ring-indigo-300 transition">
                + Nueva Auditoria
            </a>
        </x-slot>
    @endrole

    @livewire('auditorias-index')
</x-admin-layout>

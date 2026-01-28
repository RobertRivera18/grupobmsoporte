<x-admin-layout :breadcrumbs="[
    [
        'name' => 'Home',
        'url' => route('admin.dashboard'),
    ],
    [
        'name' => 'Usuarios',
        'url' => route('admin.users.index'),
    ],
]">
    
    @livewire('users-index')
</x-admin-layout>

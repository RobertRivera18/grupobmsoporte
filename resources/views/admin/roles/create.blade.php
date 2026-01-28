<x-admin-layout :breadcrumbs="[
[
'name'=>'Home',
'url'=>route('admin.dashboard')

],
[
'name'=>'Roles',
'url'=>route('admin.roles.index')
],
[
'name'=>'Nuevo',
],


]">
    <div class="bg-white shadow rounded-lg p-6">

        <x-validation-errors :errors="$errors" class="mb-4" />
        <form action="{{ route('admin.roles.store') }}" method="POST">
            @csrf
            <div class="mb-4">
                <x-label class="mb-1 ">
                    Nombre del Rol
                </x-label>
                <x-input class="w-full" name="name" placeholder="Ingrese Nombre del Rol" />
            </div>

            <div class="mb-4">
                <ul>
                    @foreach ($permissions as $permission)
                        <li>
                            <label>
                                <x-checkbox name="permissions[]" value="{{ $permission->id }}" :checked="in_array($permission->id, old('permissions',[]))" />
                                {{ $permission->name }}
                            </label>
                        </li>
                    @endforeach
                </ul>
            </div>
            <x-button>Crear Rol</x-button>
        </form>

    </div>
</x-admin-layout>

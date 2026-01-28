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
'name'=>'Editar',
],


]">
    <div class="bg-white shadow rounded-lg p-6">

        <x-validation-errors :errors="$errors" class="mb-4" />
        <form action="{{ route('admin.roles.update', $role) }}" method="POST">
            @method('PUT')
            @csrf
            <div class="mb-4">
                <x-label class="mb-1 ">
                    Nombre del Rol
                </x-label>
                <x-input class="w-full" name="name" placeholder="Ingrese Nombre del Rol"
                    value="{{ old('name', $role->name) }}" />
            </div>

            <div class="mb-4">
                <ul>
                    @foreach ($permissions as $permission)
                        <li>
                            <label>
                                <x-checkbox name="permissions[]" value="{{ $permission->id }}"
                                :checked="in_array($permission->id,old('permissions',$role->permissions->pluck('id')->toArray()))"    
                                />
                                {{$permission->name}}
                            </label>
                        </li>
                    @endforeach
                </ul>
            </div>

            <div class="flex justify-start">
                <x-button class="mr-2">
                    Actualizar Rol
                </x-button>
                <x-danger-button class="mr-2" onclick="deleteRole()">Eliminar</x-danger-button>
            </div>
        </form>
    </div>
    <form action="{{ route('admin.roles.destroy', $role) }}" method="POST" id="formDelete">
        @method('DELETE')
        @csrf
    </form>

    @push('js')
        <script>
            function deleteRole() {
                let form = document.getElementById('formDelete')
                form.submit();
            }
        </script>
    @endpush
</x-admin-layout>

<x-admin-layout :breadcrumbs="[
[
'name'=>'Home',
'url'=>route('admin.dashboard')

],
[
'name'=>'Usuarios',
'url'=>route('admin.users.index')
],
[
'name'=>'Nuevo'
]


]">
    
    <div class="bg-white shadow rounded-lg p-6">

        <form action="{{ route('admin.users.store') }}" method="POST">
            @csrf
          
          
            <x-validation-errors :errors="$errors" class="mb-4" />
            <div class="mb-4">
                <x-label>
                    Nombre
                </x-label>
                <x-input class="w-full" name="name" type="text" />

            </div>

            <div class="mb-4">
                <x-label>
                    Email
                </x-label>
                <x-input class="w-full" name="email" type="text" />

            </div>


            <div class="mb-4">
                <x-label>
                    Cedula de Identidad
                </x-label>
                <x-input class="w-full" name="cedula" type="text"  />
            </div>

            <div class="mb-4">
                <x-label>
                    Password
                </x-label>
                <x-input class="w-full" name="password" type="password" />
            </div>

            <div class="mb-4">
                <x-label>
                    Confirmar Contraseña
                </x-label>
                <x-input class="w-full" name="password_confirmation" type="password" />

            </div>
            {{-- <div class="mb-4">
                <ul>
                    @foreach ($roles as $rol)
                        <li>
                            <label>
                                <x-checkbox name="roles[]
                        " value="{{ $rol->id }}"
                                    :checked="in_array($rol->id, old('roles', $user->roles->pluck('id')->toArray()))" />
                            </label>
                            {{ $rol->name }}
                        </li>
                    @endforeach
                </ul>
            </div> --}}
            <div class="flex justify-end">
                <x-button>
                    Crear Usuario
                </x-button>
            </div>

        </form>
    </div>
</x-admin-layout>

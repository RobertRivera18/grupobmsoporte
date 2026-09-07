<div>
    {{-- BUSCADOR Y BOTÓN CREAR --}}
    <div class="px-6 py-4 flex">
        <input type="text" wire:model.live="search" class="text-sm form-input flex-1 shadow-sm rounded-full"
            placeholder="Ingrese el nombre del Usuario">

        <a class="bg-red-600 px-4 py-2 ml-2 text-white rounded-lg text-sm hover:bg-red-700"
            href="{{ route('admin.users.create') }}">
            Crear Usuario
        </a>
    </div>

    {{-- TABLA --}}
    <div class="relative overflow-x-auto">

        @if ($users->count() > 0)

            <table class="w-full text-sm text-left rtl:text-right text-gray-500 dark:text-gray-400">

                {{-- CABECERA --}}
                <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                    <tr>
                        <th scope="col" class="px-6 py-3">
                            Id
                        </th>

                        <th scope="col" class="px-6 py-3">
                            Nombre
                        </th>

                        <th scope="col" class="px-6 py-3">
                            Email
                        </th>

                        <th scope="col" class="px-6 py-3">
                            Cedula
                        </th>

                        <th scope="col" class="px-6 py-3">
                            Estado
                        </th>

                        <th scope="col" class="px-6 py-3">
                            Acciones
                        </th>
                    </tr>
                </thead>

                {{-- CUERPO --}}
                <tbody>

                    @foreach ($users as $user)
                        <tr wire:key="user-{{ $user->id }}"
                            class="bg-white border-b dark:bg-gray-800 dark:border-gray-700 border-gray-200">

                            {{-- ID --}}
                            <th scope="row"
                                class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                                {{ $user->id }}
                            </th>

                            {{-- NOMBRE --}}
                            <td class="px-6 py-4">
                                {{ $user->name }}
                            </td>

                            {{-- EMAIL --}}
                            <td class="px-6 py-4">
                                {{ $user->email }}
                            </td>

                            {{-- CÉDULA --}}
                            <td class="px-6 py-4">

                                @if ($user->cedula)
                                    {{ $user->cedula }}
                                @else
                                    <span
                                        class="bg-blue-100 text-blue-800 text-xs font-medium me-2 px-2.5 py-0.5 rounded-sm dark:bg-blue-900 dark:text-blue-300">
                                        Sin cedula
                                    </span>
                                @endif

                            </td>

                            {{-- ESTADO --}}
                            <td class="px-6 py-4" wire:key="user-estado-{{ $user->id }}">
                                <label class="inline-flex items-center cursor-pointer select-none">
                                    <input type="checkbox" wire:change="toggleEstado({{ $user->id }})"
                                        class="sr-only peer" @checked($user->estado == 1)>
                                    <div
                                        class="relative w-11 h-6 bg-gray-200 peer-focus:outline-none rounded-full peer dark:bg-gray-700 peer-checked:after:translate-x-full rtl:peer-checked:after:-translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:start-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all dark:border-gray-600 peer-checked:bg-emerald-600">
                                    </div>
                                    <span
                                        class="ms-3 text-xs font-medium {{ $user->estado == 1 ? 'text-emerald-600 dark:text-emerald-400' : 'text-rose-500' }}">
                                        {{ $user->estado == 1 ? 'Activo' : 'Inactivo' }}
                                    </span>
                                </label>
                            </td>

                            {{-- ACCIONES --}}
                            <td class="px-6 py-4 flex items-center gap-6">

                                {{-- EDITAR --}}
                                <a class="text-red-500 font-semibold hover:text-red-700"
                                    href="{{ route('admin.users.edit', $user) }}">
                                    Editar
                                </a>

                                {{-- ELIMINAR --}}
                                <i wire:click="$dispatch('openModal', { id: {{ $user->id }} })"
                                    class="fas fa-trash text-red-600 cursor-pointer hover:text-red-800"></i>

                            </td>

                        </tr>
                    @endforeach

                </tbody>

            </table>

            {{-- PAGINACIÓN --}}
            <div class="px-6 py-4">
                {{ $users->links() }}
            </div>
        @else
            <div class="px-6 py-4">
                No hay ningún registro que coincida con la búsqueda
            </div>

        @endif

    </div>
</div>


@push('js')
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script>
        document.addEventListener('livewire:init', () => {
            Livewire.on('openModal', ({
                id
            }) => {

                Swal.fire({
                    title: "¿Estás seguro?",
                    text: "¡No podrás revertir esto!",
                    icon: "warning",
                    showCancelButton: true,
                    confirmButtonColor: "#3085d6",
                    cancelButtonColor: "#d33",
                    confirmButtonText: "Sí, eliminar",
                    cancelButtonText: "Cancelar"
                }).then((result) => {

                    if (result.isConfirmed) {

                        Livewire.dispatch('deleteUser', {
                            userId: id
                        });

                    }

                });

            });



            Livewire.on('swal:success', () => {

                Swal.fire({
                    icon: 'success',
                    title: '¡Bien hecho!',
                    text: 'Usuario eliminado con éxito',
                    position: 'top-end',
                    toast: true,
                    timer: 3000,
                    showConfirmButton: false
                });

            });




            Livewire.on('swal:toast', (data) => {

                Swal.fire({
                    icon: data.icon ?? 'success',
                    title: data.title ?? 'Estado actualizado',
                    position: 'top-end',
                    toast: true,
                    timer: 2500,
                    showConfirmButton: false
                });

            });

        });
    </script>
@endpush

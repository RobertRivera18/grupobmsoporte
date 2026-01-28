<div>
    <div class="px-6 py-4 flex">
        <input wire:keydown="limpiar_page" wire:model="search" class=" text-sm form-input flex-1 shadow-sm rounded-full"
            placeholder="Ingrese el nombre del Usuario">
        <a class="bg-red-600 px-2 py-2 ml-2 text-white rounded-lg text-sm" href="{{ route('admin.users.create') }}">Crear
            Usuario</a>
    </div>
    <div class="relative overflow-x-auto">
        @if ($users->count() > 0)
            <table class="w-full text-sm text-left rtl:text-right text-gray-500 dark:text-gray-400">
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

                        </th>
                    </tr>
                </thead>
                <tbody>

                    @foreach ($users as $user)
                        <tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700 border-gray-200">
                            <th scope="row"
                                class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                                {{ $user->id }}
                            </th>

                            <td class="px-6 py-4">
                                {{ $user->name }}
                            </td>
                            <td class="px-6 py-4">
                                {{ $user->email }}
                            </td>
                            <td class="px-6 py-4">
                                @if ($user->cedula)
                                    {{ $user->cedula }}
                                @else
                                    <span
                                        class="bg-blue-100 text-blue-800 text-xs font-medium me-2 px-2.5 py-0.5 rounded-sm dark:bg-blue-900 dark:text-blue-300">Sin
                                        cedula</span>
                                @endif
                            </td>


                            <td class="px-6 py-4 flex justify-between items-center gap-6">
                                <a class="text-red-500 font-semibold"
                                    href="{{ route('admin.users.edit', $user) }}">Editar</a>

                                <i wire:click="$dispatch('openModal', { id: {{ $user->id }} })" class="fas fa-trash text-red-600"></i>
                            </td>
                        </tr>
                    @endforeach


                </tbody>
            </table>
            <div class="px-6 py-4">
                {{ $users->links() }}
            </div>
        @else
            <div class="px-6 py-4">
                No hay nigun registro que coincida con la busqueda
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
                    confirmButtonText: "Sí, eliminar"
                }).then((result) => {
                    if (result.isConfirmed) {
                      
                        Livewire.dispatch('deleteUser', {
                            userId: id
                        });
                    }
                });
            });

            // Escuchar el evento para mostrar mensaje de éxito
            Livewire.on('swal:success', (data) => {
                Swal.fire({
                    icon: 'success',
                    title: '¡Bien hecho!',
                    text: 'Equipo Eliminado con éxito',
                    position: 'top-end',
                    toast: true,
                    timer: 3000,
                    showConfirmButton: false
                });
            });

        });
    </script>
@endpush

<x-admin-layout :breadcrumbs="[
    [
        'name' => 'Home',
        'url' => route('admin.dashboard'),
    ],
    [
        'name' => 'Equipos',
        'url' => route('admin.equipos.index'),
    ],
    [
        'name' => 'Nuevo',
    ],
]">
    <x-slot name="action">
        <a class="text-white bg-gray-800 hover:bg-gray-900 focus:outline-none focus:ring-4 focus:ring-gray-300 font-medium rounded-lg text-sm px-5 py-2.5 me-2 mb-2 dark:bg-gray-800 dark:hover:bg-gray-700 dark:focus:ring-gray-700 dark:border-gray-700"
            href="{{ route('admin.equipos.create') }}">Nuevo</a>
    </x-slot>

    {{-- <div class="relative overflow-x-auto">
        <table class="w-full text-sm text-left rtl:text-right text-gray-500 dark:text-gray-400">
            <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                <tr>

                    <th scope="col" class="px-6 py-3">
                        Nombre
                    </th>

                    <th scope="col" class="px-6 py-3">
                        Marca
                    </th>
                    <th scope="col" class="px-6 py-3">
                        Modelo
                    </th>
                    <th scope="col" class="px-6 py-3">
                        Serie
                    </th>

                    <th scope="col" class="px-6 py-3">
                        Asignado
                    </th>

                    <th scope="col" class="px-6 py-3">

                    </th>
                </tr>
            </thead>
            <tbody>

                @foreach ($equipos as $equipo)
                    <tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700 border-gray-200">


                        <td class="px-6 py-4">
                            {{ $equipo->nombre }}
                        </td>
                        <td class="px-6 py-4">
                            {{ $equipo->marca }}
                        </td>

                        <td class="px-6 py-4">
                            {{ $equipo->modelo }}
                        </td>

                        <td class="px-6 py-4">
                            {{ $equipo->serie }}
                        </td>

                        <td class="px-6 py-4">
                            @if ($equipo->users->isNotEmpty())
                                <span
                                    class="bg-blue-100 text-blue-800 text-xs font-medium me-2 px-2.5 py-0.5 rounded-sm dark:bg-blue-900 dark:text-blue-300">
                                    {{ $equipo->users->first()->name }}
                                </span>
                            @elseif ($equipo->cuadrilla->isNotEmpty())
                                <span
                                    class="bg-purple-100 text-purple-800 text-xs font-medium me-2 px-2.5 py-0.5 rounded-sm dark:bg-purple-900 dark:text-purple-300">
                                    {{ $equipo->cuadrilla->first()->cua_nombre }}
                                </span>
                            @else
                                <span
                                    class="bg-green-100 text-green-800 text-xs font-medium me-2 px-2.5 py-0.5 rounded-sm dark:bg-green-900 dark:text-green-300">
                                    Equipo Libre
                                </span>
                            @endif
                        </td>

                        <td>
                            @if ($equipo->ciudad == 1)
                                <span
                                    class="bg-yellow-100 text-yellow-800 text-xs font-medium ms-2 px-2.5 py-0.5 rounded-sm dark:bg-yellow-900 dark:text-yellow-300">
                                    Guayaquil
                                </span>
                            @elseif ($equipo->ciudad == 2)
                                <span
                                    class="bg-purple-100 text-purple-800 text-xs font-medium ms-2 px-2.5 py-0.5 rounded-sm dark:bg-purple-900 dark:text-purple-300">
                                    Quito
                                </span>
                            @endif
                        </td>



                        <td class="px-6 py-4">
                            <div class="flex items-center gap-4">
                                <a class="text-red-500 font-semibold"
                                    href="{{ route('admin.equipos.edit', $equipo) }}">Editar</a>

                                <form action="{{ route('admin.equipos.destroy', $equipo) }}" method="POST"
                                    class="formularioEliminar">
                                    @csrf
                                    @method('delete')

                                    <button type="submit" class="bg-transparent border-none p-0 m-0 cursor-pointer">
                                        <i class="fas fa-trash text-lg text-red-500"></i>
                                    </button>
                                </form>
                                <div>
                                    <label class="inline-flex items-center cursor-pointer">
                                        <input 
                                            type="checkbox" 
                                            class="sr-only peer"
                                            wire:change="actualizarEstado({{ $equipo->id }}, $event.target.checked)"
                                            @checked($equipo->estado == 1)
                                        >
                                        <div 
                                            class="relative 
                                                   w-11 h-6 sm:w-10 sm:h-5 md:w-11 md:h-6 
                                                   bg-gray-200 rounded-full 
                                                   peer peer-checked:bg-blue-600 
                                                   dark:bg-gray-700 dark:peer-checked:bg-blue-600 
                                                   peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-blue-300 
                                                   dark:peer-focus:ring-blue-800 
                                                   after:content-[''] after:absolute after:top-[2px] after:start-[2px] 
                                                   after:bg-white after:border-gray-300 after:border 
                                                   after:rounded-full 
                                                   after:h-5 after:w-5 sm:after:h-4 sm:after:w-4 md:after:h-5 md:after:w-5
                                                   after:transition-all peer-checked:after:translate-x-full 
                                                   rtl:peer-checked:after:-translate-x-full 
                                                   peer-checked:after:border-white dark:border-gray-600">
                                        </div>
                                        <span class="ms-3 text-xs sm:text-sm md:text-base font-medium text-gray-900 dark:text-gray-300">
                                            Activo
                                        </span>
                                    </label>
                                </div>
                            </div>
                        </td>


                    </tr>
                @endforeach


            </tbody>
        </table>
    </div>

    <div class="mt-4">
        {{ $equipos->links() }}
    </div> --}}

    {{-- @push('js')
        <script>
            document.querySelectorAll('.formularioEliminar').forEach(form => {
                form.addEventListener('submit', function(e) {
                    e.preventDefault();
                    Swal.fire({
                        title: '¿Eliminar equipo?',
                        html: `
        <div style="font-size: 15px;">
            <strong>¡Esta acción no se puede deshacer!</strong><br>
            El equipo será eliminado de forma permanente.
        </div>
    `,
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonColor: '#e3342f',
                        cancelButtonColor: '#6c757d',
                        confirmButtonText: '<i class="fas fa-trash-alt"></i> Sí, eliminar',
                        cancelButtonText: '<i class="fas fa-times-circle"></i> Cancelar',
                        customClass: {
                            popup: 'rounded-xl px-6 pt-6 pb-4',
                            confirmButton: 'px-4 py-2 text-sm',
                            cancelButton: 'px-4 py-2 text-sm'
                        },
                        buttonsStyling: false,
                        reverseButtons: true
                    }).then((result) => {
                        if (result.isConfirmed) {
                            this.submit();
                        }
                    });

                });
            });
        </script>
    @endpush --}}

  @livewire('equipos-table')

</x-admin-layout>



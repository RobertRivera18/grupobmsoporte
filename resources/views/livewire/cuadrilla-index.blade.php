<div class="max-w-9xl mx-auto px-4 sm:px-6 lg:px-8 py-8">

    <!-- Buscador y botón -->
    <div class="px-6 py-4 flex flex-col sm:flex-row sm:items-center gap-3">
        <input wire:keydown="limpiar_page" wire:model="search"
            class="text-sm form-input rounded-full shadow-sm w-full sm:w-auto flex-1"
            placeholder="Ingrese el nombre de la cuadrilla">
        <a href="{{ route('admin.cuadrillas.create') }}"
            class="bg-red-600 hover:bg-red-700 text-white text-sm px-4 py-2 rounded-lg text-center">
            Crear nueva Cuadrilla
        </a>
    </div>

    @if ($cuadrillas->count() > 0)

        <!-- Vista tipo tarjeta para móviles -->
        <div class="block md:hidden space-y-4">
            @foreach ($cuadrillas as $cuadrilla)
                <div class="bg-white shadow-md rounded-lg p-4">
                    <div class="flex justify-between">
                        <div class="text-gray-900 font-semibold">{{ $cuadrilla->cua_nombre }}</div>
                        <div class="text-sm text-gray-500 mb-2">
                            @switch($cuadrilla->cua_ciudad)
                                @case(1)
                                    <span class="font-semibold px-2 py-0.5">Guayaquil</span>
                                @break

                                @case(2)
                                    <span class="font-semibold px-2 py-0.5">Quito</span>
                                @break
                            @endswitch
                        </div>
                    </div>


                    <div class="text-sm mb-2">
                        Empresa:
                        @switch($cuadrilla->cua_empresa)
                            @case(1)
                                <span class="bg-red-100 text-red-800 px-2 py-0.5 rounded-full">Claro</span>
                            @break

                            @case(2)
                                <span class="bg-blue-100 text-blue-800 px-2 py-0.5 rounded-full">CNEL</span>
                            @break
                        @endswitch
                    </div>
                    <div class="text-sm mb-2">
                        @if ($cuadrilla->users->isNotEmpty())
                            <strong>Colaboradores:</strong>
                            <ul class="list-disc ml-5 text-gray-700">
                                @foreach ($cuadrilla->users as $user)
                                    <li>{{ $user->name }}</li>
                                @endforeach
                            </ul>
                        @else
                            <span class="bg-blue-100 text-blue-800 px-2 py-0.5 rounded-full text-xs">No tiene
                                asignado</span>
                        @endif
                    </div>
                    <div class="mb-2">
                        @if ($cuadrilla->estado == 0)
                            <span class="bg-yellow-100 text-yellow-800 text-xs font-medium px-2.5 py-0.5 rounded">En
                                Revisión</span>
                        @else
                            <span
                                class="bg-green-100 text-green-800 text-xs font-medium px-2.5 py-0.5 rounded">Publicada</span>
                        @endif
                    </div>
                    @role('Admin')
                        <div class="flex justify-between items-center text-sm">
                            <a href="{{ route('admin.cuadrillas.edit', $cuadrilla) }}"
                                class="text-indigo-600 hover:text-indigo-900">Editar</a>

                            <form action="{{ route('admin.cuadrillas.destroy', $cuadrilla) }}" method="POST"
                                class="formularioEliminar">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="text-red-600 hover:text-red-900">Eliminar</button>
                            </form>

                            <label class="inline-flex items-center cursor-pointer">
                                <input type="checkbox" class="sr-only peer"
                                    wire:change="actualizarEstado({{ $cuadrilla->id }}, $event.target.checked)"
                                    @checked($cuadrilla->estado == 1)>
                                <div
                                    class="w-10 h-5 bg-gray-200 peer-checked:bg-blue-600 rounded-full relative transition-all after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:h-4 after:w-4 after:rounded-full peer-checked:after:translate-x-full">
                                </div>
                            </label>
                        </div>
                    @endrole
                </div>
            @endforeach
        </div>

        <!-- Tabla para pantallas medianas en adelante -->
        <div class="hidden md:block overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200 text-sm">
                <thead class="bg-gray-50 text-xs text-gray-500 uppercase">
                    <tr>
                        <th class="px-6 py-3 text-left font-medium">Nombres</th>
                        <th class="px-6 py-3 text-left font-medium">Empresa</th>
                        <th class="px-6 py-3 text-left font-medium">Colaboradores Asignados</th>
                        <th class="px-6 py-3 text-left font-medium">Estado</th>
                        @role('Admin')
                            <th class="px-6 py-3"><span class="sr-only">Editar</span></th>
                            <th class="px-6 py-3"><span class="sr-only">Eliminar</span></th>
                            <th class="px-6 py-3"><span class="sr-only">Switch</span></th>
                        @endrole
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @foreach ($cuadrillas as $cuadrilla)
                        <tr>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="font-medium text-gray-900">{{ $cuadrilla->cua_nombre }}</div>
                                <div class="text-xs text-gray-500 mt-1">
                                    @switch($cuadrilla->cua_ciudad)
                                        @case(1)
                                            <span class="bg-red-100 text-red-800 px-2 py-0.5 rounded-full">Guayaquil</span>
                                        @break

                                        @case(2)
                                            <span class="bg-yellow-100 text-yellow-800 px-2 py-0.5 rounded-full">Quito</span>
                                        @break
                                    @endswitch
                                </div>
                            </td>

                            <td class="px-6 py-4 whitespace-nowrap">
                                @switch($cuadrilla->cua_empresa)
                                    @case(1)
                                        <span class="bg-red-100 text-red-800 px-2 py-0.5 rounded-full">Claro</span>
                                    @break

                                    @case(2)
                                        <span class="bg-blue-100 text-blue-800 px-2 py-0.5 rounded-full">CNEL</span>
                                    @break
                                @endswitch
                            </td>

                            <td class="px-6 py-4 whitespace-nowrap">
                                @if ($cuadrilla->users->isNotEmpty())
                                    <ul class="list-disc ml-5 text-gray-700">
                                        @foreach ($cuadrilla->users as $user)
                                            <li>{{ $user->name }}</li>
                                        @endforeach
                                    </ul>
                                @else
                                    <span class="bg-blue-100 text-blue-800 px-2 py-0.5 rounded-full text-xs">No tiene
                                        asignado</span>
                                @endif
                            </td>

                            <td class="px-6 py-4 whitespace-nowrap">
                                @if ($cuadrilla->estado == 0)
                                    <span
                                        class="bg-yellow-100 text-yellow-800 text-xs font-medium px-2.5 py-0.5 rounded">En
                                        Revisión</span>
                                @else
                                    <span
                                        class="bg-green-100 text-green-800 text-xs font-medium px-2.5 py-0.5 rounded">Publicada</span>
                                @endif
                            </td>

                            @role('Admin')
                                <td class="px-6 py-4 whitespace-nowrap text-right">
                                    <a href="{{ route('admin.cuadrillas.edit', $cuadrilla) }}"
                                        class="text-indigo-600 hover:text-indigo-900 text-sm">Editar</a>
                                </td>

                                <td class="px-6 py-4 whitespace-nowrap text-right">
                                    <form action="{{ route('admin.cuadrillas.destroy', $cuadrilla) }}" method="POST"
                                        class="formularioEliminar inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit"
                                            class="text-red-600 hover:text-red-900 text-sm">Eliminar</button>
                                    </form>
                                </td>

                                <td class="px-6 py-4 whitespace-nowrap">
                                    <label class="inline-flex items-center cursor-pointer">
                                        <input type="checkbox" class="sr-only peer"
                                            wire:change="actualizarEstado({{ $cuadrilla->id }}, $event.target.checked)"
                                            @checked($cuadrilla->estado == 1)>
                                        <div
                                            class="w-11 h-6 bg-gray-200 peer-checked:bg-blue-600 rounded-full relative transition-all after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:h-5 after:w-5 after:rounded-full peer-checked:after:translate-x-full">
                                        </div>
                                        <span class="ml-2 text-sm text-gray-700">Activo</span>
                                    </label>
                                </td>
                            @endrole
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <!-- Paginación -->
        <div class="px-6 py-4">
            {{ $cuadrillas->links() }}
        </div>
    @else
        <div class="px-6 py-4 text-gray-500">No hay ningún registro que coincida con la búsqueda.</div>
    @endif
</div>

@push('js')
    <script>
        document.querySelectorAll('.formularioEliminar').forEach(form => {
            form.addEventListener('submit', function(e) {
                e.preventDefault();
                Swal.fire({
                    title: '¿Eliminar Cuadrilla?',
                    html: `<div style="font-size: 15px;">
                    <strong>¡Esta acción no se puede deshacer!</strong><br>
                    La cuadrilla será eliminada de forma permanente.
                </div>`,
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
@endpush

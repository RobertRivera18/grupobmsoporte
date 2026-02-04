<div>

    <div class="px-6 py-4 flex">
        <input wire:keydown="limpiar_page" wire:model="search" class=" text-sm form-input flex-1 shadow-sm rounded-full"
            placeholder="Ingrese el nombrel Usuario o Equipo a Buscar">
    </div>
    <div class="relative overflow-x-auto">
        <table id="search-table" class="w-full text-sm text-left rtl:text-right text-gray-500 dark:text-gray-400">
            <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                <tr>
                    <th scope="col" class="px-6 py-3">
                        ID
                    </th>
                    <th scope="col" class="px-6 py-3">
                        Usuario que reporta
                    </th>
                    <th scope="col" class="px-6 py-3">
                        Nombre
                    </th>
                    <th scope="col" class="px-6 py-3">
                        Detalle
                    </th>

                    <th scope="col" class="px-6 py-3">
                        Fecha
                    </th>
                    <th scope="col" class="px-6 py-3">
                        <!-- Acciones -->
                    </th>
                </tr>
            </thead>
            <tbody>
                @foreach ($incidentes as $incidente)
                    <tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700 border-gray-200">
                        <td class="px-6 py-4">
                            {{ $incidente->id }}
                        </td>
                        <td class="px-6 py-4">
                            {{ $incidente->usuario->name }}
                            <span>{{ $incidente->usuario->cedula }}</span>
                        </td>
                        <td class="px-6 py-4">
                            {{ $incidente->nombre }}
                        </td>
                        <td class="px-6 py-4">
                            {{ $incidente->detalle }}
                        </td>
                        <td class="px-6 py-4">
                            {{ $incidente->fecha }}
                        </td>
                        <td class="px-6 py-4">
                            @if ($incidente->archivos->count())
                                <div class="space-y-1">
                                    @foreach ($incidente->archivos as $archivo)
                                        <a href="{{ asset('storage/' . $archivo->archivo) }}" download
                                            class="inline-flex items-center gap-2 text-blue-600 hover:text-blue-800 font-medium">
                                            <i class="fas fa-download"></i> Descargar imagen
                                        </a>
                                        <span>{{ basename($archivo->archivo) }}</span>
                                        </a>
                                    @endforeach

                                </div>
                            @else
                                <span class="text-gray-400 text-sm">Sin archivos</span>
                            @endif

                        </td>


                        <td>

                            <a href="#" wire:click.prevent="$dispatch('openModal', { id: {{ $incidente->id }} })"
                                class="text-red-500 font-semibold hover:underline">
                                Eliminar
                            </a>
                        </td>

                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    {{-- <div class="mt-4">
        {{ $incidentes->links() }}
    </div> --}}

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
                        Livewire.dispatch('eliminarIncidencia', {
                            incidenciaId: id
                        });
                    }
                });
            });

            Livewire.on('swal:success', () => {
                Swal.fire({
                    icon: 'success',
                    title: '¡Bien hecho!',
                    text: 'Incidencia eliminada con éxito',
                    position: 'top-end',
                    toast: true,
                    timer: 3000,
                    showConfirmButton: false
                });
            });

        });
    </script>
@endpush

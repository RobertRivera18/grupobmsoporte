<x-admin-layout :breadcrumbs="[
    ['name' => 'Home', 'url' => route('admin.dashboard')],
    ['name' => 'Cuadrillas', 'url' => route('admin.cuadrillas.index')],
    ['name' => 'Editar'],
]">
    @push('css')
        <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css" />
    @endpush
    <form action="{{ route('admin.cuadrillas.update', $cuadrilla) }}" method="POST"
        class="bg-white rounded-xl p-8 shadow-lg space-y-6">
        @csrf
        @method('PUT')

        <x-validation-errors :errors="$errors" class="mb-6" />

        <div>
            <x-label for="cua_nombre" class="mb-2">Nombre de la Cuadrilla</x-label>
            <x-input id="cua_nombre" class="w-full" type="text" name="cua_nombre"
                value="{{ old('cua_nombre', $cuadrilla->cua_nombre) }}"
                placeholder="Escriba el nombre de la Cuadrilla" />
        </div>

        <div class="flex gap-4">
            <div class="w-1/2">
                <x-label for="cua_empresa" class="mb-2">Empresa</x-label>
                <x-select id="cua_empresa" name="cua_empresa" class="w-full">
                    <option value="1" {{ old('cua_empresa', $cuadrilla->cua_empresa) == 1 ? 'selected' : '' }}>
                        Claro</option>
                    <option value="2" {{ old('cua_empresa', $cuadrilla->cua_empresa) == 2 ? 'selected' : '' }}>CNEL
                    </option>
                </x-select>
            </div>

            <div class="w-1/2">
                <x-label for="cua_ciudad" class="mb-2">Ciudad</x-label>
                <x-select id="cua_ciudad" name="cua_ciudad" class="w-full">
                    <option value="1" {{ old('cua_ciudad', $cuadrilla->cua_ciudad) == 1 ? 'selected' : '' }}>
                        Guayaquil</option>
                    <option value="2" {{ old('cua_ciudad', $cuadrilla->cua_ciudad) == 2 ? 'selected' : '' }}>Quito
                    </option>
                </x-select>
            </div>
        </div>


        <div class="mt-6 overflow-x-auto w-full">
            <x-label class="mb-2">Seleccionar Colaboradores</x-label>
            <table class="min-w-full bg-white rounded-lg shadow" id="usuarios-table">
                <thead>
                    <tr class="bg-gray-200 text-gray-700">
                        <th class="px-4 py-2 text-left">Seleccionar</th>
                        <th class="px-4 py-2 text-left">Nombre</th>
                        <th class="px-4 py-2 text-left">email</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($users as $user)
                        <tr class="border-t">
                            <td class="px-4 py-2">
                                <input type="checkbox" name="users[]" value="{{ $user->id }}"
                                    @if ($cuadrilla->users->contains($user->id)) checked @endif
                                    class="rounded text-blue-600 border-gray-300 shadow-sm focus:ring focus:ring-blue-200">
                            </td>
                            <td class="px-4 py-2">{{ $user->name }}</td>
                            <td class="px-4 py-2">{{ $user->email }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>



        <div class="flex justify-end">
            <x-button>
                <i class="fas fa-save mr-2"></i>
                Actualizar Cuadrilla
            </x-button>
        </div>
    </form>


    @push('js')
      
        <script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>
        <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>

        <script>
            $(document).ready(function() {
                // Inicializar DataTable con opciones mejoradas
                
                const table = $('#usuarios-table').DataTable({
                    responsive: true,
                    searchable: true,
                    language: {
                        url: "//cdn.datatables.net/plug-ins/1.13.6/i18n/es-ES.json"
                    },
                    dom: '<"flex flex-col md:flex-row justify-between items-center mb-4"<"flex items-center"l><"relative"f>>rtip',
                    pagingType: "full_numbers",
                    pageLength: 10,
                    lengthMenu: [
                        [5, 10, 25, 50, -1],
                        [5, 10, 25, 50, "Todos"]
                    ],
                    stateSave: true,
                    order: [
                        [1, 'asc']
                    ], // Ordenar por nombre (segunda columna) por defecto
                    columnDefs: [{
                            orderable: false,
                            targets: 0
                        } // Deshabilitar ordenamiento en la columna de checkboxes
                    ],
                    drawCallback: function() {
                        updateSelectedCount();
                        styleDataTable();
                    }
                });

                // Función para estilizar elementos del DataTable
                function styleDataTable() {
                    // Estilizar la búsqueda
                    $('.dataTables_filter input').removeClass().addClass(
                        'border border-gray-300 rounded-md px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 ml-2'
                    );
                    $('.dataTables_filter input').attr('placeholder', 'Buscar usuarios...');

                    // Estilizar el selector de registros por página
                    $('.dataTables_length select').removeClass().addClass(
                        'border border-gray-300 rounded-md px-2 py-1 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 mx-1'
                    );

                    // Estilizar la paginación
                    $('.dataTables_paginate').addClass('mt-4');
                    $('.paginate_button').addClass(
                        'px-3 py-1 border border-gray-300 rounded-md mx-1 hover:bg-gray-100 cursor-pointer');
                    $('.paginate_button.current').removeClass('hover:bg-gray-100').addClass(
                        'bg-blue-500 text-white border-blue-500 hover:bg-blue-600');
                    $('.paginate_button.disabled').addClass('opacity-50 cursor-not-allowed');

                    // Estilizar la información
                    $('.dataTables_info').addClass('mt-4 text-sm text-gray-500');
                }

                // Llamar a estilizar inicialmente
                styleDataTable();

                // Agregar icono de búsqueda
                $('.dataTables_filter').prepend(
                    '<span class="absolute left-2 top-2 text-gray-400"><svg class="w-4 h-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" /></svg></span>'
                );
                $('.dataTables_filter input').addClass('pl-8');

                // Función para actualizar contador de seleccionados
                function updateSelectedCount() {
                    const count = $('.user-checkbox:checked').length;
                    $('#selected-count').text(count);
                }

                // Manejar eventos de selección
                $(document).on('change', '.user-checkbox', function() {
                    updateSelectedCount();
                });

                // Seleccionar todos los usuarios
                $('#select-all').click(function() {
                    $('.user-checkbox').prop('checked', true);
                    updateSelectedCount();
                });

                // Deseleccionar todos los usuarios
                $('#deselect-all').click(function() {
                    $('.user-checkbox').prop('checked', false);
                    updateSelectedCount();
                });

                // Permitir hacer clic en la fila para seleccionar
                $(document).on('click', '#usuarios-table tbody tr', function(e) {
                    if (e.target.type !== 'checkbox') {
                        const checkbox = $(this).find('.user-checkbox');
                        checkbox.prop('checked', !checkbox.prop('checked'));
                        updateSelectedCount();
                    }
                });

                // Filtrar solo usuarios visibles al seleccionar todos
                $('#select-all-visible').click(function() {
                    table.rows({
                        page: 'current'
                    }).every(function() {
                        $(this.node()).find('.user-checkbox').prop('checked', true);
                    });
                    updateSelectedCount();
                });
            });
        </script>
    @endpush
</x-admin-layout>

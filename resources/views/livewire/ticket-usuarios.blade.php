<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8 space-y-6">

    <!-- ENCABEZADO -->
    <div class="flex flex-col sm:flex-row justify-between items-center gap-4">
        <h2 class="text-xl font-semibold text-gray-800 flex items-center gap-2">
            <i class="fas fa-ticket-alt text-indigo-600"></i>
            Gestión de Tickets
        </h2>

        <div class="flex flex-col sm:flex-row gap-3 w-full sm:w-auto">
            <input wire:keydown="limpiar_page" wire:model="search"
                class="w-full sm:w-64 px-4 py-2 text-sm rounded-full border border-gray-300 focus:outline-none focus:ring-2 focus:ring-indigo-400 transition"
                placeholder="Buscar ticket...">

            <a href="{{ route('admin.tickets.create') }}"
                class="bg-indigo-600 hover:bg-indigo-700 text-white px-4 py-2 rounded-full text-sm font-medium shadow-sm transition flex items-center gap-2 justify-center">
                <i class="fas fa-plus"></i> Nuevo Ticket
            </a>
        </div>
    </div>

    <!-- TABLA -->
    <div class="bg-white shadow-sm border border-gray-100 rounded-2xl overflow-x-auto">
        @if ($tickets->count() > 0)
            <table class="min-w-full text-sm text-gray-700">
                <thead class="bg-gray-50 text-xs uppercase font-semibold text-gray-600">
                    <tr>
                        <th class="px-4 py-3 text-left">Nro.</th>
                        <th class="px-4 py-3 text-left">Título</th>
                        <th class="px-4 py-3 text-left">Categoría</th>
                        <th class="px-4 py-3 text-left">Estado</th>
                        <th class="px-4 py-3 text-left">Usuario</th>
                        <th class="px-4 py-3 text-left">Creación</th>
                        <th class="px-4 py-3 text-left">Soporte</th>
                        <th class="px-4 py-3 text-left">Asignación</th>
                        <th class="px-4 py-3 text-center">Acciones</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @foreach ($tickets as $ticket)
                        <tr class="hover:bg-gray-50 transition">
                            <td class="px-4 py-3">{{ $ticket->tick_id }}</td>
                            <td class="px-4 py-3 font-medium text-gray-900 truncate max-w-[160px]">
                                {{ $ticket->tick_titulo }}
                            </td>
                            <td class="px-4 py-3">
                                <span class="px-2 py-0.5 text-xs rounded-full bg-blue-50 text-blue-700 font-medium">
                                    {{ $ticket->category->name }}
                                </span>
                            </td>
                            <td class="px-4 py-3">
                                @switch($ticket->tick_estado)
                                    @case(1)
                                        <span class="px-2 py-0.5 text-xs rounded-full bg-green-50 text-green-700 font-medium">Abierto</span>
                                        @break
                                    @case(2)
                                        <span
                                            @if (auth()->user()->hasRole('Admin')) 
                                                wire:click="cambiarEstadoTicket({{ $ticket->tick_id }})" 
                                                class="px-2 py-0.5 text-xs rounded-full bg-red-50 text-red-700 font-medium hover:bg-red-100 cursor-pointer transition"
                                            @else 
                                                class="px-2 py-0.5 text-xs rounded-full bg-red-50 text-red-700 font-medium"
                                            @endif>
                                            Cerrado
                                        </span>
                                        @break
                                    @default
                                        <span class="px-2 py-0.5 text-xs rounded-full bg-gray-100 text-gray-600 font-medium">Desconocido</span>
                                @endswitch
                            </td>
                            <td class="px-4 py-3">{{ $ticket->user->name }}</td>
                            <td class="px-4 py-3 text-gray-500">
                                {{ $ticket->created_at->setTimezone('America/Guayaquil')->format('d/m/y H:i') }}
                            </td>
                            <td class="px-4 py-3">
                                @if ($ticket->soporte)
                                    <span class="flex items-center gap-2 text-sm text-gray-700">
                                        <i class="fas fa-user text-indigo-500"></i> {{ $ticket->soporte->name }}
                                    </span>
                                @else
                                    @if (auth()->user()->hasRole('Admin'))
                                        <button wire:click="asignarSoporte({{ $ticket->tick_id }})"
                                            class="text-indigo-600 hover:text-indigo-800 text-xs font-medium flex items-center gap-2 transition">
                                            <i class="fas fa-user-plus"></i> Asignar
                                        </button>
                                    @else
                                        <span class="text-gray-400 text-xs">Sin Asignar</span>
                                    @endif
                                @endif
                            </td>
                            <td class="px-4 py-3 text-gray-500">
                                {{ $ticket->fecha_asignacion ? $ticket->fecha_asignacion->format('d/m/y H:i') : '-' }}
                            </td>
                            <td class="px-4 py-3 text-center">
                                <a href="{{ route('admin.tickets.edit', $ticket->tick_id) }}"
                                    class="text-indigo-500 hover:text-indigo-700 transition transform hover:scale-110"
                                    title="Ver Detalles">
                                    <i class="fas fa-eye"></i>
                                </a>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>

            <div class="px-4 py-3 bg-gray-50">
                {{ $tickets->links() }}
            </div>
        @else
            <div class="p-6 text-center text-gray-500 text-sm">
                <i class="fas fa-info-circle mr-2"></i> No hay registros que coincidan con la búsqueda.
            </div>
        @endif
    </div>

    <!-- MODAL -->
    <x-dialog-modal wire:model="open">
        <x-slot name="title">
            <div class="flex justify-between items-center">
                <h3 class="text-base font-semibold text-gray-800">Ingenieros de Soporte</h3>
                <button wire:click="$set('open',false)" class="text-gray-400 hover:text-gray-600">
                    <i class="fa fa-times"></i>
                </button>
            </div>
        </x-slot>

        <x-slot name="content">
            <div class="space-y-3">
                @forelse ($this->ColaboradoresDisponibles as $colaborador)
                    <div class="flex justify-between items-center bg-gray-50 hover:bg-gray-100 rounded-lg px-4 py-2 transition">
                        <span class="text-sm font-medium text-gray-800">{{ $colaborador->name }}</span>
                        <x-button wire:click="asignarColaborador({{ $colaborador->id }})"
                            class="text-xs bg-indigo-600 hover:bg-indigo-700 text-white px-3 py-1.5 rounded-md">
                            Asignar
                        </x-button>
                    </div>
                @empty
                    <div class="text-center py-4 text-sm text-gray-500">
                        No hay colaboradores disponibles para asignar.
                    </div>
                @endforelse
            </div>
        </x-slot>

        <x-slot name="footer">
            <x-secondary-button wire:click="$set('open',false)">Cerrar</x-secondary-button>
        </x-slot>
    </x-dialog-modal>
</div>

<x-admin-layout :breadcrumbs="[
    ['name' => 'Home', 'url' => route('admin.dashboard')],
    ['name' => 'Tickets', 'url' => route('admin.tickets.index')],
    ['name' => 'Detalle Ticket #' . $ticket->tick_id],
]">
    <div class="bg-gray-100 p-6 rounded shadow">
        <div class="mb-4">
            <h1 class="text-2xl font-semibold">Detalle Ticket - {{ $ticket->tick_id }}</h1>

            <div class="flex items-center space-x-4 mt-2">
                @switch($ticket->tick_estado)
                    @case(1)
                        <span class="bg-green-100 text-green-800 text-sm font-semibold px-2.5 py-0.5 rounded-full">
                            Abierto
                        </span>
                        @break

                    @case(2)
                        <span class="bg-red-100 text-red-800 text-sm font-semibold px-2.5 py-0.5 rounded-full">
                            Cerrado
                        </span>
                        @break

                    @default
                @endswitch

                <span class="bg-blue-100 text-blue-800 text-sm font-semibold px-2.5 py-0.5 rounded-full">
                    {{ $ticket->user->name ?? 'Sin asignar' }}
                </span>

                <span class="bg-gray-200 text-gray-800 text-sm font-semibold px-2.5 py-0.5 rounded-full">
                    {{ $ticket->created_at->format('d/m/Y H:i:s') }}
                </span>
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-1">Categoría</label>
                <input type="text" value="{{ $ticket->category->name ?? 'Sin categoría' }}" disabled
                    class="w-full px-4 py-2 bg-gray-100 border border-gray-300 rounded">
            </div>

            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-1">Título</label>
                <input type="text" value="{{ $ticket->tick_titulo }}" disabled
                    class="w-full px-4 py-2 bg-gray-100 border border-gray-300 rounded">
            </div>
        </div>

        <div class="mt-4 md:col-span-2">
            <label class="block text-sm font-semibold text-gray-700 mb-1">Descripción</label>
            <div class="w-full px-4 py-2 bg-gray-100 border border-gray-300 rounded min-h-[100px]">
                {!! $ticket->tick_descrip !!}
            </div>
        </div>

        <div class="mt-6 md:col-span-2">
            <label class="block text-sm font-semibold text-gray-700 mb-2">Archivos Adjuntos</label>

            @if ($ticket->documentos->isNotEmpty())
                <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-4">
                    @foreach ($ticket->documentos as $documento)
                        @php
                            $ext = strtolower(pathinfo($documento->doc_nombre, PATHINFO_EXTENSION));
                            $pathLimpio = ltrim($documento->doc_nombre, '/');
                                                        if (str_starts_with($pathLimpio, 'public/')) {
                                $pathLimpio = substr($pathLimpio, 7);
                            }
                            
                            $url = asset('storage/' . $pathLimpio);

                            $esImagen = in_array($ext, ['jpg', 'jpeg', 'png', 'webp', 'gif']);

                            $iconos = [
                                'pdf' => 'fas fa-file-pdf text-red-600',
                                'doc' => 'fas fa-file-word text-blue-600',
                                'docx' => 'fas fa-file-word text-blue-600',
                                'xls' => 'fas fa-file-excel text-green-600',
                                'xlsx' => 'fas fa-file-excel text-green-600',
                                'ppt' => 'fas fa-file-powerpoint text-orange-500',
                                'pptx' => 'fas fa-file-powerpoint text-orange-500',
                                'txt' => 'fas fa-file-alt text-gray-600',
                                'default' => 'fas fa-file text-gray-500',
                            ];
                            $iconoClase = $iconos[$ext] ?? $iconos['default'];
                        @endphp

                        @if ($esImagen)
                            <div class="border p-2 rounded shadow-sm bg-white overflow-hidden flex flex-col justify-between">
                                <a href="{{ $url }}" target="_blank" class="block overflow-hidden rounded">
                                    <img src="{{ $url }}" alt="Imagen Adjunta"
                                        class="w-full h-48 object-cover rounded hover:scale-105 transition duration-300">
                                </a>
                                <p class="mt-2 text-sm text-gray-600 truncate break-all w-full"
                                    title="{{ basename($documento->doc_nombre) }}">
                                    {{ basename($documento->doc_nombre) }}
                                </p>
                            </div>
                        @else
                            <div class="border p-4 rounded shadow-sm bg-white flex items-start gap-3 overflow-hidden">
                                <i class="{{ $iconoClase }} text-3xl mt-1 shrink-0"></i>
                                <div class="min-w-0 flex-1">
                                    <a href="{{ $url }}" target="_blank"
                                        class="text-blue-600 hover:underline text-sm font-medium truncate block break-all w-full"
                                        title="{{ basename($documento->doc_nombre) }}">
                                        {{ basename($documento->doc_nombre) }}
                                    </a>
                                    <div class="text-xs text-gray-500 mt-1 uppercase font-semibold">
                                        {{ $ext }} | Documento
                                    </div>
                                </div>
                            </div>
                        @endif
                    @endforeach
                </div>
            @else
                <p class="text-gray-500 italic text-sm">No hay documentos adjuntos en este ticket.</p>
            @endif
        </div>
    </div>

    <div class="mt-6 flex justify-end space-x-4">
        @role('Admin')
            <form action="{{ route('admin.tickets.estado', $ticket) }}" method="POST"
                onsubmit="return confirm('¿Estás seguro de que deseas {{ $ticket->tick_estado == 1 ? 'cerrar' : 'reabrir' }} este ticket?')">
                @csrf
                @method('PATCH')
                <button type="submit"
                    class="{{ $ticket->tick_estado == 1 ? 'bg-green-600 hover:bg-green-700' : 'bg-yellow-600 hover:bg-yellow-700' }} text-white font-semibold px-4 py-2 rounded">
                    {{ $ticket->tick_estado == 1 ? 'Cerrar Ticket' : 'Reabrir Ticket' }}
                </button>
            </form>
        @endrole

        <a href="{{ route('admin.tickets.index') }}"
            class="bg-gray-500 hover:bg-gray-600 text-white font-semibold px-4 py-2 rounded">
            Cancelar
        </a>
    </div>
</x-admin-layout>
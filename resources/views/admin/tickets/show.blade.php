<x-admin-layout :breadcrumbs="[
    [
        'name' => 'Home',
        'url' => route('admin.dashboard'),
    ],
    [
        'name' => 'Tickets',
        'url' => route('admin.tickets.index'),
    ],
    [
        'name' => 'Detalle Ticket',
    ],
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
        <div class="md:col-span-2">
            <label class="block text-sm font-semibold text-gray-700 mb-1">Descripción</label>
            <div class="w-full px-4 py-2 bg-gray-100 border border-gray-300 rounded min-h-[100px]">
                {!! $ticket->tick_descrip !!}
            </div>
        </div>
        <div class="md:col-span-2">
            <label class="block text-sm font-semibold text-gray-700 mb-1">Archivos Adjuntos</label>
            @if ($ticket->documentos->isNotEmpty())
                <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-4">
                    @foreach ($ticket->documentos as $documento)
                        @php
                            $ext = strtolower(pathinfo($documento->doc_nombre, PATHINFO_EXTENSION));
                            $url = Storage::url($documento->doc_nombre);
                            $esImagen = in_array($ext, ['jpg', 'jpeg', 'png', 'webp']);

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
                            <div class="border p-2 rounded shadow-sm bg-white overflow-hidden">
                                <a href="{{ $url }}" target="_blank">
                                    <img src="{{ $url }}" alt="Imagen Adjunta"
                                        class="w-full h-48 object-cover rounded hover:opacity-90 transition">
                                </a>
                                <p class="mt-2 text-sm text-gray-600 truncate break-all w-full"
                                    title="{{ basename($documento->doc_nombre) }}">
                                    {{ basename($documento->doc_nombre) }}
                                </p>
                            </div>
                        @else
                            <div class="border p-4 rounded shadow-sm bg-white flex items-start gap-2 overflow-hidden">
                                <i class="{{ $iconoClase }} text-2xl mt-1 shrink-0"></i>
                                <div class="min-w-0">
                                    <a href="{{ $url }}" target="_blank"
                                        class="text-blue-600 hover:underline text-sm truncate block break-all w-full"
                                        title="{{ basename($documento->doc_nombre) }}">
                                        {{ basename($documento->doc_nombre) }}
                                    </a>
                                    <div class="text-sm text-gray-500 mt-1 uppercase">{{ $ext }} | Documento
                                    </div>
                                </div>
                            </div>
                        @endif
                    @endforeach
                </div>
            @else
                <p class="text-gray-500">No hay documentos adjuntos.</p>
            @endif
        </div>
    </div>

    {{-- <div class="space-y-1">
        <p class="text-sm text-gray-500">{{$ticket->detalle->created_at}}</p>

        <!-- Tarjeta del comentario -->
        <div class="bg-white border border-gray-200 rounded-lg p-4 shadow-sm">
            <!-- Encabezado -->
            <div class="flex items-center space-x-3 mb-2">
                <img src="https://i.pravatar.cc/40" alt="Avatar" class="w-10 h-10 rounded-full">
                <div>
                    <p class="font-semibold text-gray-800">Soporte Ing. Robert Rivera</p>
                    <p class="text-xs text-gray-500">Soporte</p>
                </div>
            </div>

            <!-- Hora y mensaje -->
            <div class="flex space-x-3">
                <p class="text-sm text-gray-500 min-w-[80px]">10:42:49</p>
                <p class="text-sm text-gray-700">
                    El sistema presenta un error de momento, tiene quemada la fuente de poder
                </p>
            </div>
        </div>
    </div> --}}


    {{-- <form action="{{ route('admin.tickets.detalle', $ticket) }}" method="POST">
        @csrf
        <div>
            <label class="block font-semibold">Ingresa una duda o consulta</label>
            <textarea name="tickd_descrip" rows="6" id="tickd_descrip"
                class="w-full mt-1 border border-gray-300 rounded px-3 py-2 focus:outline-none focus:ring focus:border-blue-300"
                placeholder="Describa el problema o requerimiento..."></textarea>
            @error('tickd_descrip')
                <span class="text-red-600 text-sm">{{ $message }}</span>
            @enderror
        </div>
        <button type="submit" class="bg-gray-500 hover:bg-gray-600 text-white font-semibold px-4 py-2 rounded">
            Enviar
        </button>

    </form> --}}
    <div class="mt-6 flex justify-end space-x-4">
        @role('Admin')
            <form action="{{ route('admin.tickets.estado', $ticket) }}" method="POST"
                onsubmit="return confirm('¿Estás seguro de que deseas {{ $ticket->tick_estado == 1 ? 'cerrar' : 'reabrir' }} este ticket?')">
                @csrf
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

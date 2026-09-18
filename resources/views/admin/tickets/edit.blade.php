<x-admin-layout :breadcrumbs="[
    ['name' => 'Home', 'url' => route('admin.dashboard')],
    ['name' => 'Tickets', 'url' => route('admin.tickets.index')],
    ['name' => 'Detalle Ticket #' . $ticket->tick_id],
]">

    @push('css')
        <link href="https://cdnjs.cloudflare.com/ajax/libs/summernote/0.8.18/summernote-lite.min.css" rel="stylesheet">
        <style>
            .note-editor.note-frame {
                border-radius: 0.5rem;
                border-color: #e5e7eb;
                overflow: hidden;
            }
        </style>
    @endpush

    <div class="max-w-7xl mx-auto space-y-6">

        <!-- Header y Acciones Rápidas -->
        <div
            class="bg-white p-6 rounded-xl shadow-sm border border-gray-100 flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
            <div>
                <div class="flex items-center gap-3">
                    <h1 class="text-2xl font-bold text-gray-900">Ticket #{{ $ticket->tick_id }}</h1>
                    @if ($ticket->tick_estado == 1)
                        <span
                            class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-emerald-100 text-emerald-800">
                            <span class="w-2 h-2 mr-1.5 bg-emerald-500 rounded-full"></span> Abierto
                        </span>
                    @else
                        <span
                            class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-rose-100 text-rose-800">
                            <span class="w-2 h-2 mr-1.5 bg-rose-500 rounded-full"></span> Cerrado
                        </span>
                    @endif
                </div>
                <p class="text-sm text-gray-500 mt-1">Creado el {{ $ticket->created_at->format('d/m/Y \a \l\a\s H:i') }}
                    hs</p>
            </div>

            <div class="flex items-center gap-3">
                @role('Admin')
                    <form action="{{ route('admin.tickets.estado', $ticket) }}" method="POST"
                        onsubmit="return confirm('¿Estás seguro de que deseas {{ $ticket->tick_estado == 1 ? 'cerrar' : 'reabrir' }} este ticket?')">
                        @csrf
                        @method('PATCH')
                        <button type="submit"
                            class="inline-flex items-center px-4 py-2 text-sm font-medium rounded-lg transition text-white {{ $ticket->tick_estado == 1 ? 'bg-rose-600 hover:bg-rose-700' : 'bg-amber-600 hover:bg-amber-700' }}">
                            <i class="fas {{ $ticket->tick_estado == 1 ? 'fa-lock' : 'fa-lock-open' }} mr-2"></i>
                            {{ $ticket->tick_estado == 1 ? 'Cerrar Ticket' : 'Reabrir Ticket' }}
                        </button>
                    </form>
                @endrole

                <a href="{{ route('admin.tickets.index') }}"
                    class="px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 transition">
                    <i class="fas fa-arrow-left mr-1"></i> Volver
                </a>
            </div>
        </div>

        <!-- Layout de 2 Columnas -->
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

            <!-- Columna Principal (Detalles + Comentarios) -->
            <div class="lg:col-span-2 space-y-6">

                <!-- Detalle Principal del Ticket -->
                <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-100">
                    <h2 class="text-xl font-bold text-gray-800 mb-4">{{ $ticket->tick_titulo }}</h2>

                    <div class="prose max-w-none text-gray-700 bg-gray-50 p-4 rounded-lg border border-gray-200">
                        {!! $ticket->tick_descrip !!}
                    </div>

                    <!-- Adjuntos -->
                    <div class="mt-6 pt-6 border-t border-gray-100">
                        <h3 class="text-sm font-bold text-gray-700 uppercase tracking-wider mb-3">Archivos Adjuntos</h3>
                        @if ($ticket->documentos->isNotEmpty())
                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                                @foreach ($ticket->documentos as $documento)
                                    @php
                                        $ext = strtolower(pathinfo($documento->doc_nombre, PATHINFO_EXTENSION));
                                        $url = Storage::url($documento->doc_nombre);
                                        $esImagen = in_array($ext, ['jpg', 'jpeg', 'png', 'webp']);

                                        $iconos = [
                                            'pdf' => 'fas fa-file-pdf text-red-500',
                                            'doc' => 'fas fa-file-word text-blue-500',
                                            'docx' => 'fas fa-file-word text-blue-500',
                                            'xls' => 'fas fa-file-excel text-emerald-500',
                                            'xlsx' => 'fas fa-file-excel text-emerald-500',
                                            'ppt' => 'fas fa-file-powerpoint text-orange-500',
                                            'pptx' => 'fas fa-file-powerpoint text-orange-500',
                                            'txt' => 'fas fa-file-alt text-gray-500',
                                            'default' => 'fas fa-file text-gray-400',
                                        ];
                                        $iconoClase = $iconos[$ext] ?? $iconos['default'];
                                    @endphp

                                    @if ($esImagen)
                                        <div
                                            class="group relative border border-gray-200 rounded-lg p-2 bg-white hover:border-blue-400 transition">
                                            <a href="{{ $url }}" target="_blank" class="block">
                                                <img src="{{ $url }}" alt="Imagen Adjunta"
                                                    class="w-full h-32 object-cover rounded">
                                                <span class="block mt-2 text-xs font-medium text-gray-600 truncate"
                                                    title="{{ basename($documento->doc_nombre) }}">
                                                    {{ basename($documento->doc_nombre) }}
                                                </span>
                                            </a>
                                        </div>
                                    @else
                                        <a href="{{ $url }}" target="_blank"
                                            class="flex items-center p-3 border border-gray-200 rounded-lg bg-white hover:border-blue-400 transition">
                                            <i class="{{ $iconoClase }} text-3xl mr-3"></i>
                                            <div class="min-w-0">
                                                <p class="text-sm font-medium text-gray-800 truncate"
                                                    title="{{ basename($documento->doc_nombre) }}">
                                                    {{ basename($documento->doc_nombre) }}
                                                </p>
                                                <p class="text-xs text-gray-400 uppercase font-semibold mt-0.5">
                                                    {{ $ext }}</p>
                                            </div>
                                        </a>
                                    @endif
                                @endforeach
                            </div>
                        @else
                            <p class="text-sm text-gray-400 italic">No hay documentos adjuntos a este ticket.</p>
                        @endif
                    </div>
                </div>

                <!-- Historial de Comentarios -->
                <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-100">
                    <h2 class="text-lg font-bold text-gray-800 mb-6 flex items-center">
                        <i class="fas fa-comments text-blue-500 mr-2"></i> Historial de Comentarios
                    </h2>

                    @if ($ticket->detalles->isNotEmpty())
                        <div class="relative pl-6 border-l-2 border-gray-200 space-y-8 ml-2">
                            @foreach ($ticket->detalles as $detalle)
                                <div class="relative group">
                                    <div
                                        class="absolute -left-[31px] top-1.5 w-4 h-4 rounded-full border-2 border-white bg-blue-500">
                                    </div>

                                    <!-- Tarjeta -->
                                    <div class="bg-gray-50 border border-gray-200 rounded-xl p-4 transition shadow-2xs">
                                        <div class="flex items-center justify-between mb-3">
                                            <div class="flex items-center space-x-3">
                                                <img src="{{ $detalle->user->profile_photo_url }}" alt="Avatar"
                                                    class="w-8 h-8 rounded-full object-cover">
                                                <div>
                                                    <span class="text-sm font-bold text-gray-800 block leading-tight">
                                                        {{ $detalle->user->name ?? 'Usuario desconocido' }}
                                                    </span>
                                                    <span
                                                        class="text-xs px-2 py-0.5 rounded font-medium inline-block mt-0.5 {{ $detalle->user?->hasRole('Admin') ? 'bg-indigo-100 text-indigo-700' : 'bg-gray-200 text-gray-700' }}">
                                                        {{ $detalle->user?->hasRole('Admin') ? 'Soporte Técnico' : 'Cliente' }}
                                                    </span>
                                                </div>
                                            </div>
                                            <span class="text-xs text-gray-400 font-medium">
                                                {{ $detalle->created_at->format('d/m/Y H:i') }}
                                            </span>
                                        </div>

                                        <div
                                            class="prose max-w-none text-sm text-gray-700 bg-white p-3 rounded-lg border border-gray-100">
                                            {!! $detalle->tickd_descrip !!}
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="text-center py-6 text-gray-400 text-sm">
                            No hay respuestas registradas aún.
                        </div>
                    @endif
                </div>

                <!-- Formulario de Respuesta -->
                @if ($ticket->tick_estado == 1)
                    <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-100">
                        <h3 class="text-md font-bold text-gray-800 mb-3">Responder al ticket</h3>
                        <form action="{{ route('admin.tickets.update', $ticket) }}" method="POST">
                            @csrf
                            @method('PUT')
                            <div class="mb-4">
                                <textarea name="tickd_descrip" id="tickd_descrip"></textarea>
                                @error('tickd_descrip')
                                    <span class="text-rose-600 text-xs font-semibold mt-1 block">{{ $message }}</span>
                                @enderror
                            </div>
                            <div class="flex justify-end">
                                <button type="submit"
                                    class="bg-blue-600 hover:bg-blue-700 text-white font-medium px-5 py-2.5 rounded-lg transition inline-flex items-center text-sm shadow-sm">
                                    <i class="fas fa-paper-plane mr-2"></i> Enviar Respuesta
                                </button>
                            </div>
                        </form>
                    </div>
                @else
                    <div
                        class="bg-amber-50 border border-amber-200 text-amber-800 p-4 rounded-xl text-sm flex items-center">
                        <i class="fas fa-exclamation-circle text-lg mr-3 text-amber-600"></i>
                        Este ticket está cerrado. Para agregar más información debes reabrirlo primero.
                    </div>
                @endif

            </div>

            <!-- Columna Lateral (Información del Ticket) -->
            <div class="space-y-6">
                <div class="bg-white p-5 rounded-xl shadow-sm border border-gray-100">
                    <h3 class="text-xs font-bold text-gray-400 uppercase tracking-wider mb-4">Información General</h3>

                    <div class="space-y-4">
                        <div>
                            <span class="text-xs text-gray-500 block">Categoría</span>
                            <span
                                class="text-sm font-semibold text-gray-800 bg-gray-100 px-2.5 py-1 rounded inline-block mt-1">
                                {{ $ticket->category->name ?? 'Sin categoría' }}
                            </span>
                        </div>

                        <div>
                            <span class="text-xs text-gray-500 block">Usuario Solicitante</span>
                            <div class="flex items-center space-x-2 mt-1">
                                <div
                                    class="w-7 h-7 rounded-full bg-blue-100 text-blue-600 flex items-center justify-center font-bold text-xs">
                                    {{ strtoupper(substr($ticket->user->name ?? 'U', 0, 1)) }}
                                </div>
                                <span class="text-sm font-medium text-gray-700">
                                    {{ $ticket->user->name ?? 'Sin asignar' }}
                                </span>
                            </div>
                        </div>

                        <div>
                            <span class="text-xs text-gray-500 block">Fecha de Creación</span>
                            <span class="text-sm font-medium text-gray-700 block mt-0.5">
                                {{ $ticket->created_at->format('d/m/Y H:i:s') }}
                            </span>
                        </div>

                        <div>
                            <span class="text-xs text-gray-500 block">Última Actualización</span>
                            <span class="text-sm font-medium text-gray-700 block mt-0.5">
                                {{ $ticket->updated_at->format('d/m/Y H:i:s') }}
                            </span>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>

    @push('js')
        <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/summernote/0.8.18/summernote-lite.min.js"></script>
        <script>
            $(document).ready(function() {
                $('#tickd_descrip').summernote({
                    placeholder: 'Escribe tu respuesta aquí...',
                    tabsize: 2,
                    height: 180,
                    toolbar: [
                        ['style', ['bold', 'italic', 'underline', 'clear']],
                        ['font', ['strikethrough']],
                        ['para', ['ul', 'ol', 'paragraph']],
                        ['insert', ['link', 'picture']],
                    ]
                });
            });
        </script>
    @endpush
</x-admin-layout>

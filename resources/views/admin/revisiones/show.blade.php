<x-admin-layout :breadcrumbs="[
    [
        'name' => 'Home',
        'url' => route('admin.dashboard'),
    ],
    [
        'name' => 'Revisiones Vehiculares',
        'url' => route('admin.revisiones.index'),
    ],
    [
        'name' => 'Revisión ' . $vehiculoinspeccion->vehiculo->placa,
        'url' => route('admin.revisiones.show', $vehiculoinspeccion),
    ],
]">
    <div class="container mx-auto px-4 py-4 sm:py-8 space-y-4 sm:space-y-6 max-w-7xl antialiased" x-data="{ openFirmaModal: false }">

        <div
            class="flex flex-col sm:flex-row justify-between items-stretch sm:items-center gap-4 bg-white p-4 sm:p-6 rounded-xl shadow-sm border border-gray-200/80">
            <div class="space-y-2 sm:space-y-0">
                <div class="flex flex-col sm:flex-row sm:items-center gap-2 sm:gap-3">
                    <h1 class="text-xl sm:text-2xl font-bold tracking-tight text-gray-900 leading-tight">
                        Reporte de Inspección Técnica
                    </h1>
                    <span
                        class="self-start px-2.5 py-1 text-[11px] sm:text-xs font-semibold rounded-md uppercase tracking-wider {{ $vehiculoinspeccion->tipo_equipo === 'propio' ? 'bg-emerald-50 text-emerald-700 border border-emerald-200' : 'bg-amber-50 text-amber-700 border border-amber-200' }}">
                        Equipo {{ ucfirst($vehiculoinspeccion->tipo_equipo) }}
                    </span>
                    @if ($vehiculoinspeccion->documento_firmado_path)
                        <span
                            class="self-start px-2.5 py-1 text-[11px] sm:text-xs font-semibold rounded-md uppercase tracking-wider bg-blue-50 text-blue-700 border border-blue-200 inline-flex items-center gap-1">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M5 13l4 4L19 7" />
                            </svg>
                            Firmado
                        </span>
                    @endif
                </div>
                <p class="text-xs sm:text-sm text-gray-500 mt-1.5 flex flex-wrap items-center gap-1.5 leading-relaxed">
                    <span class="flex items-center gap-1">
                        <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                        </svg>
                        Realizado: <span
                            class="font-medium text-gray-800">{{ \Carbon\Carbon::parse($vehiculoinspeccion->fecha)->format('d/m/Y') }}</span>
                    </span>
                    <span class="hidden sm:inline text-gray-300">•</span>
                    <span class="flex items-center gap-1 w-full sm:w-auto">
                        <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                        </svg>
                        Técnico: <span
                            class="font-medium text-gray-800 break-all">{{ $vehiculoinspeccion->tecnicoEncargado->name }}</span>
                    </span>
                    @if ($vehiculoinspeccion->firmado_at)
                        <span class="hidden sm:inline text-gray-300">•</span>
                        <span class="flex items-center gap-1 w-full sm:w-auto">
                            <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.907c.961 0 1.36 1.243.58 1.8a1 1 0 00-.364 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.9-2.833a1 1 0 00-1.17 0l-3.9 2.833c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.364-1.118l-3.9-2.834c-.78-.57-.38-1.81.58-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z" />
                            </svg>
                            Firmado el: <span
                                class="font-medium text-gray-800">{{ $vehiculoinspeccion->firmado_at->format('d/m/Y H:i') }}</span>
                        </span>
                    @endif
                </p>
            </div>

            <a href="{{ route('admin.revisiones.index') }}"
                class="inline-flex items-center justify-center gap-2 text-sm font-medium text-gray-600 hover:text-gray-900 bg-gray-50 hover:bg-gray-100 border border-gray-200 px-4 py-2.5 sm:py-2 rounded-lg transition-all shadow-sm w-full sm:w-auto">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                </svg>
                Volver al Listado
            </a>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-4 sm:gap-6">
            <div class="bg-white p-4 sm:p-5 rounded-xl shadow-sm border border-gray-200 space-y-3.5">
                <h3 class="text-xs font-bold text-gray-400 uppercase tracking-widest border-b border-gray-100 pb-2">
                    Datos del Vehículo</h3>
                <div class="flex justify-between items-start gap-2">
                    <div>
                        <span class="block text-xs font-medium text-gray-400">Placa</span>
                        <span
                            class="font-mono font-bold text-xs sm:text-sm bg-amber-50 text-amber-900 px-2.5 py-1 rounded border border-amber-200 inline-block mt-1 uppercase tracking-wider shadow-sm">
                            {{ $vehiculoinspeccion->vehiculo->placa ?? 'S/P' }}
                        </span>
                    </div>
                    <div class="text-right">
                        <span class="block text-xs font-medium text-gray-400">Kilometraje</span>
                        <span class="text-sm sm:text-base font-bold text-gray-900 block mt-1 break-all">
                            {{ number_format($vehiculoinspeccion->kilometraje) }} km
                        </span>
                    </div>
                </div>
                <div>
                    <span class="block text-xs font-medium text-gray-400">Marca y Modelo</span>
                    <span
                        class="text-sm font-semibold text-gray-800 block mt-0.5 break-words">{{ $vehiculoinspeccion->vehiculo->marca }}
                        {{ $vehiculoinspeccion->vehiculo->modelo }}</span>
                </div>
            </div>

            <div class="bg-white p-4 sm:p-5 rounded-xl shadow-sm border border-gray-200 space-y-3.5">
                <h3 class="text-xs font-bold text-gray-400 uppercase tracking-widest border-b border-gray-100 pb-2">
                    Responsables</h3>
                <div>
                    <span class="block text-xs font-medium text-gray-400">Revisado Por</span>
                    <span
                        class="text-sm font-semibold text-gray-800 block mt-0.5 break-words">{{ $vehiculoinspeccion->revisado_por_nombre ?? 'No registrado' }}</span>
                </div>
                <div>
                    <span class="block text-xs font-medium text-gray-400">Técnico Responsable</span>
                    <span
                        class="text-sm font-semibold text-gray-800 block mt-0.5 break-words">{{ $vehiculoinspeccion->tecnicoEncargado->name ?? 'No registrado' }}</span>
                </div>
            </div>

            <div
                class="sm:col-span-2 md:col-span-1 bg-white p-4 sm:p-5 rounded-xl shadow-sm border border-gray-200 space-y-3.5">
                <h3 class="text-xs font-bold text-gray-400 uppercase tracking-widest border-b border-gray-100 pb-2">
                    Daños y Colisiones</h3>
                <div>
                    <span class="block text-xs font-medium text-gray-400 mb-1.5">Registro de Choques / Golpes</span>
                    @if ($vehiculoinspeccion->choques_golpes)
                        <p
                            class="text-xs sm:text-sm text-red-900 bg-red-50/70 p-3 rounded-lg border border-red-100 min-h-[60px] font-medium break-words">
                            ⚠️ {{ $vehiculoinspeccion->choques_golpes }}
                        </p>
                    @else
                        <p
                            class="text-xs sm:text-sm text-emerald-800 bg-emerald-50/60 p-3 rounded-lg border border-emerald-100 min-h-[60px] flex items-center gap-2">
                            <svg class="w-4 h-4 text-emerald-600 shrink-0" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M5 13l4 4L19 7" />
                            </svg>
                            <span>Ninguno reportado de gravedad.</span>
                        </p>
                    @endif
                </div>
            </div>
        </div>

        <div class="bg-white p-4 sm:p-6 rounded-xl shadow-sm border border-gray-200">
            <h3
                class="text-base sm:text-lg font-bold text-gray-900 mb-4 sm:mb-5 flex items-center gap-2 tracking-tight">
                <svg class="w-5 h-5 text-indigo-600 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4" />
                </svg>
                Evaluación de Componentes
            </h3>

            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4 sm:gap-6">
                @foreach ($vehiculoinspeccion->checklist as $categoria => $items)
                    <div class="border border-gray-200/80 rounded-xl overflow-hidden bg-white shadow-xs">
                        <div class="bg-gray-50 border-b border-gray-200 px-4 py-2.5">
                            <h4 class="font-semibold text-gray-700 uppercase text-xs tracking-wider truncate">
                                {{ str_replace('_', ' ', $categoria) }}
                            </h4>
                        </div>
                        <ul class="divide-y divide-gray-100 p-1 sm:p-2">
                            @foreach ($items as $item => $estado)
                                <li
                                    class="flex justify-between items-center py-2.5 px-2 hover:bg-gray-50/80 rounded-lg transition-colors gap-2">
                                    <span
                                        class="capitalize text-xs font-medium text-gray-600 break-words line-clamp-2 max-w-[60%]">
                                        {{ str_replace('_', ' ', $item) }}
                                    </span>
                                    <div class="shrink-0">
                                        @if ($estado)
                                            <span
                                                class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full text-[10px] sm:text-[11px] font-semibold bg-emerald-50 text-emerald-700 border border-emerald-200 whitespace-nowrap">
                                                <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span>
                                                <span class="hidden xs:inline">Buen Estado</span><span
                                                    class="xs:hidden">OK</span>
                                            </span>
                                        @else
                                            <span
                                                class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full text-[10px] sm:text-[11px] font-semibold bg-rose-50 text-rose-700 border border-rose-200 animate-pulse whitespace-nowrap">
                                                <span class="w-1.5 h-1.5 rounded-full bg-rose-500"></span>
                                                <span class="hidden xs:inline">Revisar</span><span
                                                    class="xs:hidden">NO
                                                    OK</span>
                                            </span>
                                        @endif
                                    </div>
                                </li>
                            @endforeach
                        </ul>
                    </div>
                @endforeach
            </div>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 sm:gap-6">
            <div class="bg-white p-4 sm:p-5 rounded-xl shadow-sm border border-gray-200">
                <h4
                    class="text-xs font-bold text-gray-400 uppercase tracking-widest border-b border-gray-100 pb-2 mb-3">
                    Observaciones Generales</h4>
                <p
                    class="text-xs sm:text-sm text-gray-600 bg-gray-50/60 p-3 sm:p-3.5 rounded-lg border border-gray-200/60 min-h-[80px] whitespace-pre-line leading-relaxed break-words">
                    {{ $vehiculoinspeccion->observaciones ?? 'Sin observaciones adicionales.' }}
                </p>
            </div>

            <div class="bg-white p-4 sm:p-5 rounded-xl shadow-sm border border-gray-200">
                <h4
                    class="text-xs font-bold text-gray-400 uppercase tracking-widest border-b border-gray-100 pb-2 mb-3">
                    Recomendaciones de Mantenimiento</h4>
                <p
                    class="text-xs sm:text-sm text-gray-600 bg-gray-50/60 p-3 sm:p-3.5 rounded-lg border border-gray-200/60 min-h-[80px] whitespace-pre-line leading-relaxed break-words">
                    {{ $vehiculoinspeccion->recomendaciones_mantenimiento ?? 'No se prescribieron acciones correctivas inmediatas.' }}
                </p>
            </div>
        </div>

        <div class="bg-white p-4 sm:p-6 rounded-xl shadow-sm border border-gray-200">
            <h3 class="text-base sm:text-lg font-bold text-gray-900 mb-4 flex items-center gap-2 tracking-tight">
                <svg class="w-5 h-5 text-indigo-600 shrink-0" fill="none" stroke="currentColor"
                    viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                </svg>
                Evidencias Fotográficas Levantadas
            </h3>

            @if ($vehiculoinspeccion->fotos->isEmpty())
                <div
                    class="text-center py-8 sm:py-10 border-2 border-dashed border-gray-200 rounded-xl text-gray-400 text-xs sm:text-sm bg-gray-50/30 px-4">
                    <p class="font-medium">No se adjuntaron capturas ni evidencias visuales para esta inspección.</p>
                </div>
            @else
                <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-5 gap-3 sm:gap-4">
                    @foreach ($vehiculoinspeccion->fotos as $foto)
                        <div
                            class="group relative rounded-xl overflow-hidden border border-gray-200 shadow-xs bg-gray-50 hover:shadow-md transition-all duration-300">
                            <img src="{{ asset('storage/' . $foto->ruta_foto) }}" alt="Evidencia fotográfica"
                                class="w-full h-28 sm:h-36 object-cover group-hover:scale-105 transition duration-500">
                            <div
                                class="absolute inset-0 bg-gray-900/40 opacity-0 group-hover:opacity-100 flex items-center justify-center transition-opacity duration-300 backdrop-blur-xs">
                                <a href="{{ asset('storage/' . $foto->ruta_foto) }}" target="_blank"
                                    class="bg-white text-gray-900 px-2.5 py-1.5 sm:px-3 sm:py-2 rounded-lg text-[11px] sm:text-xs font-semibold shadow-md hover:bg-gray-50 transition-colors flex items-center gap-1">
                                    <span>Ampliar</span>
                                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14" />
                                    </svg>
                                </a>
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>

        <div class="mt-6 flex flex-col sm:flex-row gap-3 justify-end">
            @if ($vehiculoinspeccion->documento_firmado_path)
                {{-- Ya existe un comprobante firmado: se puede descargar directo o volver a firmar --}}
                <a href="{{ route('admin.revisiones.exportWord.get', $vehiculoinspeccion) }}"
                    class="inline-flex items-center justify-center gap-2 text-sm font-medium text-white bg-emerald-600 hover:bg-emerald-700 px-4 py-2.5 rounded-lg transition-all shadow-sm w-full sm:w-auto">
                    <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                    </svg>
                    Descargar Comprobante Firmado
                </a>
                <button type="button" onclick="abrirModalFirma(true)"
                    class="inline-flex items-center justify-center gap-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 hover:bg-gray-50 px-4 py-2.5 rounded-lg transition-all shadow-sm w-full sm:w-auto cursor-pointer">
                    <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.907c.961 0 1.36 1.243.58 1.8a1 1 0 00-.364 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.9-2.833a1 1 0 00-1.17 0l-3.9 2.833c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.364-1.118l-3.9-2.834c-.78-.57-.38-1.81.58-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z" />
                    </svg>
                    Volver a Firmar
                </button>
            @else
                {{-- No hay comprobante todavía: exige capturar la firma --}}
                <button type="button" onclick="abrirModalFirma(false)"
                    class="inline-flex items-center justify-center gap-2 text-sm font-medium text-white bg-blue-600 hover:bg-blue-700 px-4 py-2.5 rounded-lg transition-all shadow-sm w-full sm:w-auto cursor-pointer">
                    <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.907c.961 0 1.36 1.243.58 1.8a1 1 0 00-.364 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.9-2.833a1 1 0 00-1.17 0l-3.9 2.833c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.364-1.118l-3.9-2.834c-.78-.57-.38-1.81.58-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z" />
                    </svg>
                    Capturar Firma y Descargar Word
                </button>
            @endif
        </div>
    </div>

    <div id="modalFirma" class="fixed inset-0 z-50 hidden overflow-y-auto" aria-labelledby="modal-title"
        role="dialog" aria-modal="true">
        <div class="flex items-center justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:block sm:p-0">
            <div class="fixed inset-0 transition-opacity bg-gray-500/75 backdrop-blur-xs"
                onclick="cerrarModalFirma()"></div>

            <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>

            <div
                class="inline-block align-bottom bg-white rounded-xl text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full border border-gray-200">
                <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                    <div class="sm:flex sm:items-start">
                        <div class="w-full text-center sm:text-left">
                            <h3 class="text-lg font-bold leading-6 text-gray-950 mb-1" id="modal-title">
                                Capturar Firma Digital (Técnico)
                            </h3>
                            <p class="text-xs text-gray-500 mb-4">
                                Use su dedo o lápiz óptico directamente sobre el recuadro para firmar el reporte.
                            </p>

                            <div
                                class="relative w-full flex justify-center bg-gray-50 rounded-lg p-2 border border-gray-200">
                                <canvas id="canvasFirma" width="440" height="220"
                                    class="bg-white border-2 border-dashed border-gray-300 rounded-lg max-w-full touch-none shadow-inner cursor-crosshair"></canvas>
                            </div>

                            <div class="mt-2 flex justify-start">
                                <button type="button" id="btnLimpiarFirma"
                                    class="inline-flex items-center gap-1 text-xs font-semibold text-gray-600 bg-gray-100 hover:bg-gray-200 px-3 py-1.5 rounded-md transition-colors">
                                    Limpiar Lienzo
                                </button>
                            </div>
                        </div>
                    </div>
                </div>

                <form id="formExportFirma"
                    action="{{ route('admin.revisiones.exportWord', $vehiculoinspeccion->id) }}" method="POST">
                    @csrf
                    <input type="hidden" name="firma_base64" id="firma_base64">
                    <input type="hidden" name="regenerar" id="regenerarInput" value="0">

                    <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse gap-2">
                        <button type="submit"
                            class="w-full sm:w-auto inline-flex justify-center items-center px-4 py-2.5 bg-blue-600 hover:bg-blue-700 text-sm font-semibold text-white rounded-lg shadow-sm transition-colors cursor-pointer">
                            Confirmar y Descargar Word
                        </button>
                        <button type="button" onclick="cerrarModalFirma()"
                            class="mt-3 sm:mt-0 w-full sm:w-auto inline-flex justify-center items-center px-4 py-2.5 bg-white border border-gray-300 hover:bg-gray-50 text-sm font-medium text-gray-700 rounded-lg shadow-xs transition-colors cursor-pointer">
                            Cancelar
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        let canvas, ctx, dibujando = false;

        document.addEventListener("DOMContentLoaded", function() {
            canvas = document.getElementById('canvasFirma');
            ctx = canvas.getContext('2d');
            ctx.strokeStyle = "#0f172a";
            ctx.lineWidth = 3;
            ctx.lineJoin = "round";
            ctx.lineCap = "round";

            function obtenerPosicion(e) {
                const rect = canvas.getBoundingClientRect();
                const clientX = e.touches ? e.touches[0].clientX : e.clientX;
                const clientY = e.touches ? e.touches[0].clientY : e.clientY;

                return {
                    x: (clientX - rect.left) * (canvas.width / rect.width),
                    y: (clientY - rect.top) * (canvas.height / rect.height)
                };
            }

            function iniciarDibujo(e) {
                dibujando = true;
                const pos = obtenerPosicion(e);
                ctx.beginPath();
                ctx.moveTo(pos.x, pos.y);
            }

            function dibujar(e) {
                if (!dibujando) return;
                e.preventDefault();
                const pos = obtenerPosicion(e);
                ctx.lineTo(pos.x, pos.y);
                ctx.stroke();
            }

            function detenerDibujo() {
                dibujando = false;
            }

            canvas.addEventListener('mousedown', iniciarDibujo);
            canvas.addEventListener('mousemove', dibujar);
            window.addEventListener('mouseup', detenerDibujo);

            canvas.addEventListener('touchstart', iniciarDibujo);
            canvas.addEventListener('touchmove', dibujar);
            window.addEventListener('touchend', detenerDibujo);
            document.getElementById('btnLimpiarFirma').addEventListener('click', function() {
                ctx.clearRect(0, 0, canvas.width, canvas.height);
            });

            document.getElementById('formExportFirma').addEventListener('submit', function(e) {
                const blank = document.createElement('canvas');
                blank.width = canvas.width;
                blank.height = canvas.height;

                if (canvas.toDataURL() === blank.toDataURL()) {
                    e.preventDefault();
                    alert("Por favor, estampe la firma del técnico antes de exportar.");
                    return;
                }

                document.getElementById('firma_base64').value = canvas.toDataURL();
                cerrarModalFirma();
            });
        });

        function abrirModalFirma(esRegenerar = false) {
            document.getElementById('modalFirma').classList.remove('hidden');
            document.getElementById('regenerarInput').value = esRegenerar ? '1' : '0';
            if (ctx) ctx.clearRect(0, 0, canvas.width, canvas.height);
        }

        function cerrarModalFirma() {
            document.getElementById('modalFirma').classList.add('hidden');
        }
    </script>
</x-admin-layout>

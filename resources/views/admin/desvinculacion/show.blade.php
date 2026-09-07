<x-admin-layout :breadcrumbs="[
    ['name' => 'Home', 'url' => route('admin.dashboard')],
    ['name' => 'Solicitudes Desvinculación', 'url' => route('admin.desvinculacion.index')],
    ['name' => 'Detalles Desvinculación'],
]">
    <div class="max-w-7xl mx-auto my-6 px-4 sm:px-6 space-y-6">

        <!-- Header y Acciones Principales -->
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 pb-4 border-b border-slate-200">
            <div>
                <h1 class="text-xl font-bold text-slate-900">Detalles de Desvinculación</h1>
                <p class="text-xs text-slate-500 mt-0.5">Gestión de entrega de activos y paz y salvo del colaborador</p>
            </div>
            <div class="flex items-center gap-2">
                <a href="{{ route('admin.desvinculacion.index') }}"
                    class="inline-flex items-center gap-2 px-3 py-2 text-xs font-medium text-slate-700 bg-white border border-slate-300 rounded-lg hover:bg-slate-50 transition shadow-sm">
                    <i class="fas fa-arrow-left"></i>
                    <span>Volver</span>
                </a>

                <a href="{{ $solicitud->equiposDetalle->isNotEmpty() ? route('admin.desvinculacion.acta-liberacion', $solicitud->id) : '#' }}"
                    @class([
                        'inline-flex items-center gap-2 px-4 py-2 text-xs font-semibold rounded-lg shadow-sm transition',
                        'bg-indigo-600 hover:bg-indigo-700 text-white' => $solicitud->equiposDetalle->isNotEmpty(),
                        'bg-slate-200 text-slate-400 cursor-not-allowed pointer-events-none' => $solicitud->equiposDetalle->isEmpty(),
                    ])
                    aria-label="Descargar Acta de Liberación"
                    title="{{ $solicitud->equiposDetalle->isEmpty() ? 'Esta solicitud no tiene equipos asociados' : 'Descargar Acta' }}">
                    <i class="fas fa-file-word text-sm"></i>
                    <span>Acta Liberación</span>
                </a>
            </div>
        </div>

        <!-- Contenido en Dashboard (2 Columnas) -->
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

            <!-- Columna Izquierda: Información General / RRHH -->
            <div class="space-y-6">

                <!-- Ficha del Colaborador -->
                <div class="bg-white border border-slate-200 rounded-xl p-5 shadow-sm space-y-4">
                    <div class="flex items-center gap-3">
                        <div class="bg-slate-100 text-slate-600 rounded-full w-12 h-12 flex items-center justify-center font-bold text-lg shrink-0 border border-slate-200">
                            <i class="fas fa-user"></i>
                        </div>
                        <div class="min-w-0">
                            <h2 class="font-bold text-slate-900 text-base truncate">{{ $solicitud->user->name ?? 'N/A' }}</h2>
                            <p class="text-xs font-mono text-slate-500">C.I.: {{ $solicitud->user->cedula ?? 'N/A' }}</p>
                        </div>
                    </div>

                    <div class="pt-3 border-t border-slate-100 flex items-center justify-between text-xs">
                        <span class="text-slate-500"><i class="fas fa-users text-slate-400 mr-1.5"></i>Cuadrilla</span>
                        <span class="font-medium text-slate-800">{{ $solicitud->cuadrilla->cua_nombre ?? 'Sin cuadrilla activa' }}</span>
                    </div>
                </div>

                <!-- Resumen de Talento Humano -->
                <div class="bg-white border border-slate-200 rounded-xl p-5 shadow-sm space-y-4">
                    <div>
                        <h3 class="font-bold text-slate-800 text-sm flex items-center gap-2">
                            <i class="fas fa-id-card text-indigo-600"></i> Talento Humano
                        </h3>
                        <p class="text-xs text-slate-500 mt-0.5">Implementos generales a entregar</p>
                    </div>

                    <div class="space-y-2">
                        <div class="flex items-center justify-between p-2.5 rounded-lg border text-xs {{ $solicitud->devolver_credencial ? 'bg-emerald-50/50 border-emerald-200 text-emerald-900' : 'bg-slate-50 border-slate-200 text-slate-600' }}">
                            <span class="font-medium">Credencial</span>
                            <span class="font-bold inline-flex items-center gap-1">
                                <i class="fas {{ $solicitud->devolver_credencial ? 'fa-check text-emerald-600' : 'fa-times text-slate-400' }}"></i>
                                {{ $solicitud->devolver_credencial ? 'Entregada' : 'No entregada' }}
                            </span>
                        </div>

                        <div class="flex items-center justify-between p-2.5 rounded-lg border text-xs {{ $solicitud->devolver_uniforme ? 'bg-emerald-50/50 border-emerald-200 text-emerald-900' : 'bg-slate-50 border-slate-200 text-slate-600' }}">
                            <span class="font-medium">Uniformes</span>
                            <span class="font-bold inline-flex items-center gap-1">
                                <i class="fas {{ $solicitud->devolver_uniforme ? 'fa-check text-emerald-600' : 'fa-times text-slate-400' }}"></i>
                                {{ $solicitud->devolver_uniforme ? 'Entregados' : 'No entregados' }}
                            </span>
                        </div>
                    </div>
                </div>

                <!-- Observaciones Generales (si existen) -->
                @if ($solicitud->observaciones)
                    <div class="bg-white border border-slate-200 rounded-xl p-5 shadow-sm space-y-2">
                        <h3 class="text-xs font-semibold text-slate-500 uppercase tracking-wider">
                            Observaciones Generales
                        </h3>
                        <p class="text-xs text-slate-700 bg-slate-50 p-3 rounded-lg border border-slate-100 leading-relaxed">
                            {{ $solicitud->observaciones }}
                        </p>
                    </div>
                @endif

            </div>

            <!-- Columna Derecha: Equipos y Documento Adjunto -->
            <div class="lg:col-span-2 space-y-6">

                <!-- Disposición de Equipos -->
                <div class="bg-white border border-slate-200 rounded-xl p-5 shadow-sm space-y-4">
                    <div class="flex items-center justify-between pb-3 border-b border-slate-100">
                        <div>
                            <h3 class="font-bold text-slate-800 text-sm flex items-center gap-2">
                                <i class="fas fa-laptop-code text-indigo-600"></i> Disposición de Equipos (Sistemas)
                            </h3>
                            <p class="text-xs text-slate-500 mt-0.5">Verificación de activos asignados al usuario</p>
                        </div>
                        <span class="text-xs bg-slate-100 text-slate-600 px-2.5 py-1 rounded-full font-medium">
                            Total: {{ $equiposAsignados->count() }}
                        </span>
                    </div>

                    @if ($equiposAsignados->count())
                        <div class="space-y-3">
                            @foreach ($equiposAsignados as $equipo)
                                @php
                                    $detalle = $solicitud->equiposDetalle?->firstWhere('equipo_id', $equipo->id);
                                    $estado = $detalle->destino ?? 'pendiente';
                                    $observacionEquipo = $detalle->observaciones ?? null;
                                @endphp

                                <div class="p-4 border border-slate-200 rounded-xl bg-white hover:border-slate-300 transition flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                                    <div class="space-y-1">
                                        <p class="font-semibold text-slate-800 text-sm">
                                            {{ $equipo->nombre ?? $equipo->descripcion }}
                                        </p>
                                        <p class="text-xs text-slate-500">
                                            Código/Serie: <span class="font-mono text-slate-700 font-medium">{{ $equipo->codigo ?? ($equipo->serie ?? 'N/A') }}</span>
                                        </p>

                                        @if ($observacionEquipo)
                                            <p class="text-xs text-slate-600 bg-slate-50 border border-slate-200 p-2 rounded-md mt-2 italic flex items-start gap-1.5">
                                                <i class="fas fa-comment-dots text-slate-400 mt-0.5"></i>
                                                <span>{{ $observacionEquipo }}</span>
                                            </p>
                                        @endif
                                    </div>

                                    <div class="shrink-0 self-start sm:self-center">
                                        @if ($estado === 'entregado')
                                            <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-semibold bg-emerald-50 text-emerald-700 border border-emerald-200">
                                                <i class="fas fa-check-circle"></i> Devuelto
                                            </span>
                                        @elseif ($estado === 'faltante')
                                            <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-semibold bg-rose-50 text-rose-700 border border-rose-200">
                                                <i class="fas fa-minus-circle"></i> No Devuelve
                                            </span>
                                        @else
                                            <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-semibold bg-amber-50 text-amber-700 border border-amber-200">
                                                <i class="fas fa-clock"></i> Pendiente
                                            </span>
                                        @endif
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="p-8 text-center border border-dashed border-slate-200 rounded-xl bg-slate-50 text-slate-500 text-xs">
                            <i class="fas fa-inbox text-2xl text-slate-300 mb-2 block"></i>
                            Sin equipos asignados a esta cuadrilla.
                        </div>
                    @endif
                </div>

                <!-- Comprobante / Acta Firmada -->
                <div class="bg-white border border-slate-200 rounded-xl p-5 shadow-sm space-y-4">
                    <h3 class="font-bold text-slate-800 text-sm flex items-center gap-2">
                        <i class="fas fa-file-signature text-indigo-600"></i> Comprobante / Acta Firmada
                    </h3>

                    @if ($solicitud->comprobante)
                        <div class="border border-slate-200 rounded-xl overflow-hidden bg-slate-50">
                            <div class="flex items-center justify-between px-4 py-2.5 bg-slate-100/80 border-b border-slate-200">
                                <span class="text-xs font-medium text-slate-600">Vista previa del documento</span>
                                <a href="{{ asset('storage/' . $solicitud->comprobante) }}" target="_blank"
                                    class="inline-flex items-center gap-1.5 text-xs font-semibold text-indigo-600 hover:text-indigo-800">
                                    <i class="fas fa-external-link-alt text-2xs"></i> Abrir en pantalla completa
                                </a>
                            </div>
                            <div class="p-4 flex justify-center">
                                <a href="{{ asset('storage/' . $solicitud->comprobante) }}" target="_blank" class="group block relative">
                                    <img src="{{ asset('storage/' . $solicitud->comprobante) }}"
                                        alt="Comprobante de Desvinculación"
                                        class="max-h-80 w-auto object-contain rounded border border-slate-200 shadow-sm group-hover:opacity-95 transition">
                                </a>
                            </div>
                        </div>
                    @else
                        <div class="p-8 text-center border border-dashed border-slate-200 rounded-xl bg-slate-50 text-slate-500 text-xs">
                            <i class="fas fa-file-excel text-2xl text-slate-300 mb-2 block"></i>
                            No se ha adjuntado ningún comprobante o acta firmada aún.
                        </div>
                    @endif
                </div>

            </div>

        </div>
    </div>
</x-admin-layout>
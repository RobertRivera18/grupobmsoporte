<x-admin-layout :breadcrumbs="[
    ['name' => 'Home', 'url' => route('admin.dashboard')],
    ['name' => 'Solicitudes Desvinculación', 'url' => route('admin.desvinculacion.index')],
    ['name' => 'Listado'],
]">
    <x-slot name="action">
        <a href="{{ route('admin.desvinculacion.create') }}"
            class="inline-flex items-center gap-2 text-white bg-indigo-600 hover:bg-indigo-700 focus:ring-4 focus:ring-indigo-100 font-medium rounded-xl text-sm px-4 py-2.5 shadow-sm shadow-indigo-200 transition-all duration-200">
            <i class="fas fa-plus text-xs"></i>
            <span>Nueva Solicitud</span>
        </a>
    </x-slot>

    <!-- Resumen de Métricas Rápidas (KPIs) -->
    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 mb-6">
        <div class="bg-white p-4 rounded-xl border border-gray-100 shadow-sm flex items-center gap-4">
            <div class="w-12 h-12 rounded-xl bg-indigo-50 text-indigo-600 flex items-center justify-center font-bold text-lg">
                <i class="fas fa-file-alt"></i>
            </div>
            <div>
                <p class="text-xs font-medium text-gray-500 uppercase tracking-wider">Total Solicitudes</p>
                <p class="text-xl font-bold text-gray-900">{{ $solicitudes->total() }}</p>
            </div>
        </div>

        <div class="bg-white p-4 rounded-xl border border-gray-100 shadow-sm flex items-center gap-4">
            <div class="w-12 h-12 rounded-xl bg-amber-50 text-amber-600 flex items-center justify-center font-bold text-lg">
                <i class="fas fa-spinner"></i>
            </div>
            <div>
                <p class="text-xs font-medium text-gray-500 uppercase tracking-wider">En Proceso</p>
                <p class="text-xl font-bold text-gray-900">
                    {{ $solicitudes->where('etapa', '!=', 'completado')->count() }}
                </p>
            </div>
        </div>

        <div class="bg-white p-4 rounded-xl border border-gray-100 shadow-sm flex items-center gap-4">
            <div class="w-12 h-12 rounded-xl bg-emerald-50 text-emerald-600 flex items-center justify-center font-bold text-lg">
                <i class="fas fa-check-circle"></i>
            </div>
            <div>
                <p class="text-xs font-medium text-gray-500 uppercase tracking-wider">Finalizadas</p>
                <p class="text-xl font-bold text-gray-900">
                    {{ $solicitudes->where('etapa', 'completado')->count() }}
                </p>
            </div>
        </div>
    </div>

    <div class="bg-white border border-gray-200/80 rounded-2xl shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-gray-50/80 border-b border-gray-200/80 text-gray-500 font-semibold text-xs uppercase tracking-wider">
                        <th class="px-6 py-3.5">Colaborador</th>
                        <th class="px-6 py-3.5">Cuadrilla</th>
                        <th class="px-6 py-3.5">Etapa / Estado</th>
                        <th class="px-6 py-3.5">Novedades Sistemas</th>
                        <th class="px-6 py-3.5">Fecha Registro</th>
                        <th class="px-6 py-3.5 text-right">Acciones</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100 text-sm">
                    @forelse ($solicitudes as $solicitud)
                        <tr class="hover:bg-slate-50/60 transition-colors">
                            <td class="px-6 py-4">
                                <div class="flex items-center gap-3">
                                    <div class="w-9 h-9 rounded-full bg-slate-100 text-slate-700 font-bold text-xs flex items-center justify-center border border-slate-200 uppercase">
                                        {{ substr($solicitud->user->name ?? 'U', 0, 2) }}
                                    </div>
                                    <div>
                                        <p class="font-semibold text-gray-900 leading-tight">
                                            {{ $solicitud->user->name ?? 'N/A' }}
                                        </p>
                                        <p class="text-xs text-gray-400 font-mono mt-0.5">
                                            CI: {{ $solicitud->user->cedula ?? 'N/A' }}
                                        </p>
                                    </div>
                                </div>
                            </td>

                            <td class="px-6 py-4 text-gray-600 font-medium">
                                <span class="inline-flex items-center gap-1.5 text-xs text-gray-700">
                                    <i class="fas fa-users text-gray-400 text-xs"></i>
                                    {{ $solicitud->cuadrilla->cua_nombre ?? 'Sin Cuadrilla' }}
                                </span>
                            </td>

                            <td class="px-6 py-4">
                                @if ($solicitud->etapa === 'tthh')
                                    <span class="inline-flex items-center gap-1.5 px-2.5 py-1 text-xs font-medium bg-amber-50 text-amber-700 border border-amber-200/60 rounded-lg">
                                        <span class="w-1.5 h-1.5 rounded-full bg-amber-500"></span>
                                        Talento Humano
                                    </span>
                                @elseif($solicitud->etapa === 'sistemas')
                                    <span class="inline-flex items-center gap-1.5 px-2.5 py-1 text-xs font-medium bg-blue-50 text-blue-700 border border-blue-200/60 rounded-lg">
                                        <span class="w-1.5 h-1.5 rounded-full bg-blue-500 animate-pulse"></span>
                                        Pendiente Sistemas
                                    </span>
                                @else
                                    <span class="inline-flex items-center gap-1.5 px-2.5 py-1 text-xs font-medium bg-emerald-50 text-emerald-700 border border-emerald-200/60 rounded-lg">
                                        <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span>
                                        Completado
                                    </span>
                                @endif
                            </td>
                            <td class="px-6 py-4">
                                @php
                                    $tieneRegistros = $solicitud->equiposDetalle->isNotEmpty();
                                    $tieneObservaciones = $solicitud->equiposDetalle->contains(function ($detalle) {
                                        $obs = trim($detalle->observaciones ?? ($detalle->observacion ?? ''));
                                        return !empty($obs) &&
                                            !in_array(mb_strtolower($obs), [
                                                'sin observaciones',
                                                'sin novedad',
                                                'ninguna',
                                                'n/a',
                                                'ok',
                                            ]);
                                    });
                                @endphp

                                @if (!$tieneRegistros)
                                    <span class="inline-flex items-center gap-1.5 px-2.5 py-1 text-xs font-medium bg-gray-100 text-gray-500 border border-gray-200 rounded-lg">
                                        <i class="fas fa-clock text-gray-400 text-[10px]"></i>
                                        Sin registrar
                                    </span>
                                @elseif ($tieneObservaciones)
                                    <span class="inline-flex items-center gap-1.5 px-2.5 py-1 text-xs font-semibold bg-rose-50 text-rose-700 border border-rose-200 rounded-lg"
                                        title="Hay observaciones o novedades en los equipos">
                                        <i class="fas fa-exclamation-triangle text-rose-500 text-[11px]"></i>
                                        Revisar Novedades
                                    </span>
                                @else
                                    <span class="inline-flex items-center gap-1.5 px-2.5 py-1 text-xs font-medium bg-emerald-50 text-emerald-700 border border-emerald-200 rounded-lg">
                                        <i class="fas fa-check-circle text-emerald-500 text-[11px]"></i>
                                        Sin observaciones
                                    </span>
                                @endif
                            </td>

                            <!-- Fecha -->
                            <td class="px-6 py-4 text-gray-500 text-xs whitespace-nowrap">
                                <p class="font-medium text-gray-700">{{ $solicitud->created_at->format('d/m/Y') }}</p>
                                <p class="text-[11px] text-gray-400">{{ $solicitud->created_at->format('H:i A') }}</p>
                            </td>

                            <td class="px-6 py-4 text-right whitespace-nowrap">
                                <div class="inline-flex items-center justify-end gap-1.5">
                                    <a href="{{ route('admin.desvinculacion.edit', $solicitud) }}"
                                        class="inline-flex items-center gap-1.5 px-3 py-1.5 text-xs font-medium text-slate-700 bg-white border border-gray-200 rounded-lg hover:bg-slate-50 hover:text-indigo-600 hover:border-indigo-200 transition-all shadow-2xs"
                                        title="Gestionar Equipos">
                                        <i class="fas fa-laptop-code text-xs text-indigo-500"></i>
                                        <span>Equipos</span>
                                    </a>

                                    <a href="{{ route('admin.desvinculacion.show', $solicitud) }}"
                                        class="inline-flex items-center justify-center w-8 h-8 text-gray-500 bg-white border border-gray-200 rounded-lg hover:bg-slate-50 hover:text-gray-800 transition-all shadow-2xs"
                                        title="Ver Detalles">
                                        <i class="fas fa-eye text-xs"></i>
                                    </a>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-6 py-12 text-center">
                                <div class="max-w-xs mx-auto text-center">
                                    <div class="w-12 h-12 bg-gray-100 text-gray-400 rounded-full flex items-center justify-center mx-auto mb-3">
                                        <i class="fas fa-folder-open text-xl"></i>
                                    </div>
                                    <h3 class="text-sm font-semibold text-gray-900">No hay solicitudes</h3>
                                    <p class="text-xs text-gray-500 mt-1">Aún no se han registrado procesos de desvinculación en el sistema.</p>
                                    <a href="{{ route('admin.desvinculacion.create') }}" class="inline-flex items-center gap-1.5 text-xs text-indigo-600 font-semibold mt-3 hover:underline">
                                        <i class="fas fa-plus text-[10px]"></i> Crear primera solicitud
                                    </a>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if ($solicitudes->hasPages())
            <div class="px-6 py-3.5 bg-gray-50/50 border-t border-gray-200/80">
                {{ $solicitudes->links() }}
            </div>
        @endif
    </div>
</x-admin-layout>
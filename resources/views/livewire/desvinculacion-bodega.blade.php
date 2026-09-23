<div class="max-w-6xl mx-auto space-y-6">
    {{-- ENCABEZADO --}}
    <div
        class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 bg-white p-6 rounded-2xl border border-gray-200/80 shadow-xs">
        <div class="flex items-center gap-3">
            <div
                class="w-10 h-10 rounded-xl bg-indigo-50 text-indigo-600 flex items-center justify-center font-semibold text-lg">
                <i class="fas fa-warehouse text-sm"></i>
            </div>
            <div>
                <h1 class="text-lg font-bold text-gray-900 leading-tight">Gestión de Descuentos - Bodega</h1>
                <p class="text-xs text-gray-500">Registro e imputación de valores a descontar por desvinculación</p>
            </div>
        </div>
        <div>
            <span
                class="inline-flex items-center gap-1.5 px-3 py-1 text-xs font-semibold bg-slate-100 text-slate-700 rounded-lg border border-slate-200">
                <i class="fas fa-hashtag text-[10px] text-slate-400"></i> Solicitud #{{ $solicitud->id }}
            </span>
        </div>
    </div>

    {{-- INFORMACIÓN DE LA SOLICITUD --}}
    <div class="bg-white border border-gray-200/80 rounded-2xl p-5 shadow-xs">
        <div class="grid grid-cols-1 sm:grid-cols-3 gap-6">
            <div class="flex items-center gap-3">
                <div
                    class="w-9 h-9 rounded-full bg-slate-100 text-slate-600 font-bold text-xs flex items-center justify-center border border-slate-200 uppercase">
                    {{ substr($solicitud->user->name ?? 'U', 0, 2) }}
                </div>
                <div>
                    <span
                        class="text-[11px] font-semibold text-gray-400 uppercase tracking-wider block">Colaborador</span>
                    <p class="text-sm font-semibold text-gray-900 leading-tight">
                        {{ $solicitud->user->name ?? 'No asignado' }}
                    </p>
                    <p class="text-[11px] text-gray-400 font-mono">CI: {{ $solicitud->user->cedula ?? 'N/A' }}</p>
                </div>
            </div>

            <div class="border-t sm:border-t-0 sm:border-l border-gray-100 pt-3 sm:pt-0 sm:pl-6">
                <span class="text-[11px] font-semibold text-gray-400 uppercase tracking-wider block">Cuadrilla</span>
                <p class="text-sm font-medium text-gray-700 mt-1 flex items-center gap-1.5">
                    <i class="fas fa-users text-xs text-gray-400"></i>
                    {{ $solicitud->cuadrilla->cua_nombre ?? 'Sin cuadrilla' }}
                </p>
            </div>

            <div class="border-t sm:border-t-0 sm:border-l border-gray-100 pt-3 sm:pt-0 sm:pl-6">
                <span class="text-[11px] font-semibold text-gray-400 uppercase tracking-wider block">Etapa del
                    Proceso</span>
                <div class="mt-1">
                    <span
                        class="inline-flex items-center gap-1.5 px-2.5 py-1 text-xs font-medium bg-blue-50 text-blue-700 border border-blue-200/60 rounded-lg capitalize">
                        <span class="w-1.5 h-1.5 rounded-full bg-blue-500"></span>
                        {{ $solicitud->etapa ?? 'Pendiente' }}
                    </span>
                </div>
            </div>
        </div>
    </div>

    {{-- ALERTAS DE ÉXITO --}}
    @if (session()->has('success'))
        <div
            class="flex items-center gap-3 p-4 bg-emerald-50 border border-emerald-200 text-emerald-800 text-xs font-medium rounded-xl shadow-2xs">
            <i class="fas fa-check-circle text-emerald-500 text-base"></i>
            <span class="flex-1">{{ session('success') }}</span>
        </div>
    @endif

    {{-- TABLA DE CONCEPTOS E ÍTEMS --}}
    <div class="bg-white border border-gray-200/80 rounded-2xl shadow-xs overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-100 flex justify-between items-center bg-slate-50/50">
            <h2 class="text-sm font-semibold text-gray-800">Conceptos e Ítems a Descontar</h2>

            {{-- Botón de agregar solo para Admin / Bodega --}}
            @can('gestionarBodega', $solicitud)
                <button type="button" wire:click="agregarFila"
                    class="inline-flex items-center gap-1.5 text-xs font-medium text-indigo-600 bg-indigo-50 hover:bg-indigo-100 px-3 py-1.5 rounded-lg transition-colors border border-indigo-200/60">
                    <i class="fas fa-plus text-[10px]"></i>
                    <span>Agregar Concepto</span>
                </button>
            @endcan
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr
                        class="border-b border-gray-200/80 text-gray-500 font-semibold text-[11px] uppercase tracking-wider bg-gray-50/50">
                        <th class="px-6 py-3">Concepto / Detalle del Descuento</th>
                        <th class="px-6 py-3 w-60">Valor ($)</th>
                        @can('gestionarBodega', $solicitud)
                            <th class="px-6 py-3 w-16 text-center">Acción</th>
                        @endcan
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100 text-sm">
                    @forelse ($descuentos as $index => $descuento)
                        <tr class="hover:bg-slate-50/50 transition-colors">

                            @can('gestionarBodega', $solicitud)
                                {{-- MODO EDICIÓN --}}
                                <td class="px-6 py-3.5 align-top">
                                    <input type="text"
                                        class="w-full text-xs rounded-xl border-gray-200 focus:border-indigo-500 focus:ring-indigo-500/20 shadow-2xs placeholder-gray-400 @error("descuentos.{$index}.concepto") border-rose-300 bg-rose-50/20 @enderror"
                                        wire:model.blur="descuentos.{{ $index }}.concepto"
                                        placeholder="Ej. Tarjeta de Gasolina, Equipos Drop, Cable UTP">
                                    @error("descuentos.{$index}.concepto")
                                        <p class="text-[11px] text-rose-600 mt-1 font-medium">{{ $message }}</p>
                                    @enderror
                                </td>

                                <td class="px-6 py-3.5 align-top">
                                    <div class="relative rounded-xl shadow-2xs">
                                        <div
                                            class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-gray-400 text-xs">
                                            $</div>
                                        <input type="number" step="0.01" min="0"
                                            class="w-full text-xs pl-7 rounded-xl border-gray-200 focus:border-indigo-500 focus:ring-indigo-500/20 placeholder-gray-400 font-mono @error("descuentos.{$index}.valor") border-rose-300 bg-rose-50/20 @enderror"
                                            wire:model.live.debounce.300ms="descuentos.{{ $index }}.valor"
                                            wire:change="calcularTotal" placeholder="0.00">
                                    </div>
                                    @error("descuentos.{$index}.valor")
                                        <p class="text-[11px] text-rose-600 mt-1 font-medium">{{ $message }}</p>
                                    @enderror
                                </td>

                                <td class="px-6 py-3.5 text-center align-top">
                                    <button type="button"
                                        class="w-8 h-8 inline-flex items-center justify-center text-gray-400 hover:text-rose-600 hover:bg-rose-50 rounded-lg transition-colors border border-transparent hover:border-rose-100"
                                        wire:click="eliminarFila({{ $index }})" title="Eliminar fila">
                                        <i class="fas fa-trash-alt text-xs"></i>
                                    </button>
                                </td>
                            @else
                                {{-- MODO SOLO LECTURA --}}
                                <td class="px-6 py-3.5 align-middle font-medium text-gray-800 text-xs">
                                    {{ $descuento['concepto'] ?: 'Sin concepto registrado' }}
                                </td>
                                <td class="px-6 py-3.5 align-middle font-mono font-semibold text-gray-700 text-xs">
                                    $ {{ number_format((float) ($descuento['valor'] ?? 0), 2) }}
                                </td>
                            @endcan

                        </tr>
                    @empty
                        <tr>
                            <td colspan="{{ auth()->user()->can('gestionarBodega', $solicitud) ? 3 : 2 }}"
                                class="px-6 py-8 text-center text-gray-400 text-xs">
                                <i class="fas fa-receipt text-2xl mb-2 block text-gray-300"></i>
                                No hay conceptos registrados.
                                @can('gestionarBodega', $solicitud)
                                    Haz clic en <b>"Agregar Concepto"</b> para registrar un descuento.
                                @endcan
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- PIE DE TABLA Y ACCIONES --}}
        <div
            class="px-6 py-4 bg-gray-50/50 border-t border-gray-200/80 flex flex-col sm:flex-row justify-between items-center gap-4">
            <div class="flex items-center gap-2">
                <span class="text-xs text-gray-500 font-medium">Suma Total:</span>
                <span
                    class="text-lg font-bold text-rose-700 font-mono bg-rose-50 border border-rose-200/60 px-3 py-1 rounded-xl">
                    ${{ number_format($total, 2) }}
                </span>
            </div>

            <div class="flex items-center gap-3 w-full sm:w-auto">
                {{-- Guardar solo habilitado para Admin / Bodega --}}
                @can('gestionarBodega', $solicitud)
                    <button type="button" wire:click="guardar" wire:loading.attr="disabled"
                        class="w-full sm:w-auto inline-flex items-center justify-center gap-2 text-white bg-indigo-600 hover:bg-indigo-700 focus:ring-4 focus:ring-indigo-100 font-medium rounded-xl text-xs px-5 py-2.5 shadow-xs shadow-indigo-200 transition-all duration-200 disabled:opacity-50">
                        <span wire:loading.remove wire:target="guardar" class="inline-flex items-center gap-2">
                            <i class="fas fa-save text-xs"></i>
                            <span>Guardar Descuentos</span>
                        </span>
                        <span wire:loading wire:target="guardar" class="inline-flex items-center gap-2">
                            <i class="fas fa-spinner fa-spin text-xs"></i>
                            <span>Guardando...</span>
                        </span>
                    </button>
                @endcan

                {{-- Generar Acta visible para todos --}}
                <button type="button" wire:click="generarActaLiberacion" wire:loading.attr="disabled"
                    class="w-full sm:w-auto inline-flex items-center justify-center gap-2 text-white bg-emerald-600 hover:bg-emerald-700 focus:ring-4 focus:ring-emerald-100 font-medium rounded-xl text-xs px-5 py-2.5 shadow-xs shadow-emerald-200 transition-all duration-200 disabled:opacity-50">
                    <span wire:loading.remove wire:target="generarActaLiberacion"
                        class="inline-flex items-center gap-2">
                        <i class="fas fa-file-word text-xs"></i>
                        <span>Generar Acta Liberación</span>
                    </span>
                    <span wire:loading wire:target="generarActaLiberacion" class="inline-flex items-center gap-2">
                        <i class="fas fa-spinner fa-spin text-xs"></i>
                        <span>Generando Acta...</span>
                    </span>
                </button>
            </div>
        </div>
    </div>
</div>

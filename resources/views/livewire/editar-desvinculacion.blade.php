<div class="max-w-5xl mx-auto mt-8 space-y-6">

    <!-- Header Principal: Info Colaborador -->
    <div class="bg-white rounded-2xl border border-slate-200 shadow-sm p-5 flex flex-col md:flex-row items-start md:items-center justify-between gap-4">
        <div class="flex items-center gap-4">
            <div class="w-12 h-12 rounded-full bg-slate-100 border border-slate-200 flex items-center justify-center text-slate-600 font-bold text-lg shrink-0">
                <i class="fas fa-user"></i>
            </div>
            <div>
                <div class="flex items-center gap-2">
                    <h2 class="text-lg font-semibold text-slate-900">{{ $solicitud->user->name ?? 'N/A' }}</h2>
                    <span class="text-xs bg-slate-100 text-slate-600 px-2.5 py-0.5 rounded-full font-medium">
                        C.I.: {{ $solicitud->user->cedula ?? 'N/A' }}
                    </span>
                </div>
                <p class="text-xs text-slate-500 mt-0.5">
                    Cuadrilla: <span class="font-medium text-slate-700">{{ $solicitud->cuadrilla->cua_nombre ?? ($solicitud->cuadrilla ? 'Cuadrilla #' . $solicitud->cuadrilla->id : 'Sin asignación activa') }}</span>
                </p>
            </div>
        </div>

        <a href="{{ route('admin.desvinculacion.index') }}"
            class="inline-flex items-center gap-2 text-xs font-medium text-slate-600 hover:text-slate-900 bg-slate-50 hover:bg-slate-100 border border-slate-200 px-3.5 py-2 rounded-xl transition">
            <i class="fas fa-arrow-left text-slate-400"></i>
            <span>Volver al listado</span>
        </a>
    </div>

    <!-- Resumen de Talento Humano -->
    <div class="bg-white rounded-2xl border border-slate-200 shadow-sm p-5 space-y-3">
        <div class="flex items-center gap-2 text-slate-800">
            <i class="fas fa-id-card text-slate-400"></i>
            <h3 class="text-sm font-semibold">Estado entregado por Talento Humano</h3>
        </div>
        <div class="flex flex-wrap gap-3">
            <div class="inline-flex items-center gap-2 px-3 py-1.5 rounded-lg border text-xs font-medium {{ $solicitud->devolver_credencial ? 'bg-emerald-50 text-emerald-700 border-emerald-200' : 'bg-slate-50 text-slate-500 border-slate-200' }}">
                <i class="fas {{ $solicitud->devolver_credencial ? 'fa-check-circle text-emerald-500' : 'fa-times-circle text-slate-400' }}"></i>
                <span>Credencial: {{ $solicitud->devolver_credencial ? 'Entregada' : 'No entregada' }}</span>
            </div>
            <div class="inline-flex items-center gap-2 px-3 py-1.5 rounded-lg border text-xs font-medium {{ $solicitud->devolver_uniforme ? 'bg-emerald-50 text-emerald-700 border-emerald-200' : 'bg-slate-50 text-slate-500 border-slate-200' }}">
                <i class="fas {{ $solicitud->devolver_uniforme ? 'fa-check-circle text-emerald-500' : 'fa-times-circle text-slate-400' }}"></i>
                <span>Uniformes: {{ $solicitud->devolver_uniforme ? 'Entregados' : 'No entregados' }}</span>
            </div>
        </div>
    </div>

    <!-- Sección Equipos y Disposición -->
    <div class="bg-white rounded-2xl border border-slate-200 shadow-sm p-6 space-y-5">
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 pb-4 border-b border-slate-100">
            <div>
                <h3 class="text-base font-semibold text-slate-900 flex items-center gap-2">
                    <i class="fas fa-laptop-code text-blue-600"></i>
                    Disposición de Equipos (Sistemas)
                </h3>
                <p class="text-xs text-slate-500 mt-0.5">Indica el destino final de cada activo y agrega observaciones si aplican sanciones o descuentos.</p>
            </div>

            @if ($equiposAsignados->count())
                <div class="flex items-center gap-2 text-xs self-end sm:self-center">
                    <span class="text-slate-400 font-medium">Lote:</span>
                    <button type="button" wire:click="marcarTodosComo('entregado')"
                        class="px-2.5 py-1 bg-emerald-50 text-emerald-700 hover:bg-emerald-100 border border-emerald-200 font-medium rounded-lg transition">
                        A Bodega (Todos)
                    </button>
                    <button type="button" wire:click="marcarTodosComo('faltante')"
                        class="px-2.5 py-1 bg-rose-50 text-rose-700 hover:bg-rose-100 border border-rose-200 font-medium rounded-lg transition">
                        No Devuelve (Todos)
                    </button>
                </div>
            @endif
        </div>

        @if ($equiposAsignados->count())
            <div class="space-y-3">
                @foreach ($equiposAsignados as $equipo)
                    @php
                        $estadoActual = $destinoEquipos[$equipo->id] ?? null;
                    @endphp

                    <div class="p-4 rounded-xl border transition-all {{ $estadoActual == null ? 'bg-amber-50/40 border-amber-200' : 'bg-slate-50/50 border-slate-200 hover:border-slate-300' }}">
                        <div class="grid grid-cols-1 md:grid-cols-12 gap-4 items-center">
                            
                            <!-- Info Equipo -->
                            <div class="md:col-span-6 space-y-1">
                                <p class="text-sm font-semibold text-slate-800">{{ $equipo->nombre ?? $equipo->descripcion }}</p>
                                <p class="text-xs text-slate-500 flex items-center gap-1">
                                    <span>Serie / Cód:</span>
                                    <code class="font-mono bg-slate-100 text-slate-700 px-1.5 py-0.5 rounded text-[11px]">{{ $equipo->codigo ?? ($equipo->serie ?? 'N/A') }}</code>
                                </p>
                            </div>

                            <!-- Selector Radio -->
                            <div class="md:col-span-6 grid grid-cols-2 gap-2">
                                <label class="flex items-center justify-center gap-2 p-2 rounded-lg border text-xs font-medium cursor-pointer transition select-none {{ $estadoActual === 'entregado' ? 'bg-emerald-600 text-white border-emerald-600 shadow-sm' : 'bg-white text-slate-700 border-slate-200 hover:bg-slate-100' }}">
                                    <input type="radio" name="destino_{{ $equipo->id }}" value="entregado"
                                        wire:model.live="destinoEquipos.{{ $equipo->id }}" class="sr-only" />
                                    <i class="fas fa-check-circle text-xs"></i>
                                    <span>A Bodega</span>
                                </label>

                                <label class="flex items-center justify-center gap-2 p-2 rounded-lg border text-xs font-medium cursor-pointer transition select-none {{ $estadoActual === 'faltante' ? 'bg-rose-600 text-white border-rose-600 shadow-sm' : 'bg-white text-slate-700 border-slate-200 hover:bg-slate-100' }}">
                                    <input type="radio" name="destino_{{ $equipo->id }}" value="faltante"
                                        wire:model.live="destinoEquipos.{{ $equipo->id }}" class="sr-only" />
                                    <i class="fas fa-exclamation-circle text-xs"></i>
                                    <span>No Devuelve</span>
                                </label>
                            </div>

                            <!-- Campo Observación -->
                            <div class="md:col-span-12">
                                <input type="text" wire:model.live="observacionesEquipos.{{ $equipo->id }}"
                                    placeholder="Observación opcional (ej: Pantalla rota, cobro por pérdida...)"
                                    class="w-full text-xs border border-slate-200 rounded-lg p-2.5 bg-white focus:ring-2 focus:ring-blue-500 focus:border-blue-500 text-slate-700 placeholder-slate-400 transition" />
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        @else
            <div class="p-8 text-center border border-dashed rounded-xl bg-slate-50 text-slate-500 text-sm">
                <i class="fas fa-box-open text-2xl text-slate-300 mb-2 block"></i>
                La cuadrilla no registra equipos o herramientas asignadas.
            </div>
        @endif
    </div>

    <!-- Comprobante / Acta Firmada -->
    <div class="bg-white rounded-2xl border border-slate-200 shadow-sm p-6 space-y-4">
        <div>
            <h3 class="text-base font-semibold text-slate-900 flex items-center gap-2">
                <i class="fas fa-file-upload text-blue-600"></i>
                Comprobante / Acta Firmada
            </h3>
            <p class="text-xs text-slate-500 mt-0.5">Sube el soporte físico firmado por el trabajador.</p>
        </div>

        <div class="bg-slate-50/50 border-2 border-dashed border-slate-300 rounded-2xl p-6 text-center hover:border-blue-400 transition">
            <input type="file" wire:model="comprobante" id="comprobanteInput" class="hidden" accept="image/*">

            <label for="comprobanteInput" class="cursor-pointer flex flex-col items-center justify-center space-y-2">
                <div class="w-10 h-10 bg-blue-50 text-blue-600 rounded-full flex items-center justify-center text-lg">
                    <i class="fas fa-cloud-upload-alt"></i>
                </div>
                <span class="text-sm font-semibold text-slate-700">Seleccionar imagen del acta</span>
                <span class="text-xs text-slate-400">JPG, PNG, WEBP (Máx. 4MB)</span>
            </label>

            <div wire:loading wire:target="comprobante" class="mt-3 text-xs text-blue-600 font-medium">
                <i class="fas fa-spinner fa-spin mr-1"></i> Cargando archivo...
            </div>

            @if ($comprobante)
                <div class="mt-4 p-3 bg-white border border-slate-200 rounded-xl flex items-center justify-between gap-4 max-w-md mx-auto shadow-sm">
                    <div class="flex items-center gap-3 overflow-hidden">
                        <img src="{{ $comprobante->temporaryUrl() }}" class="w-10 h-10 object-cover rounded-lg border">
                        <div class="text-left truncate">
                            <p class="text-xs font-semibold text-slate-800 truncate">{{ $comprobante->getClientOriginalName() }}</p>
                            <p class="text-[10px] text-emerald-600 font-medium">Listo para guardar</p>
                        </div>
                    </div>
                    <button type="button" wire:click="guardarComprobante"
                        class="bg-emerald-600 hover:bg-emerald-700 text-white text-xs font-medium px-3 py-1.5 rounded-lg transition flex items-center gap-1.5 shrink-0">
                        <i class="fas fa-save"></i> Guardar
                    </button>
                </div>
            @endif

            @error('comprobante')
                <p class="mt-2 text-xs text-rose-600 font-medium"><i class="fas fa-exclamation-circle"></i> {{ $message }}</p>
            @enderror
        </div>

        @if ($solicitud->comprobante)
            <div class="p-3 bg-emerald-50/60 border border-emerald-200 rounded-xl flex items-center justify-between">
                <div class="flex items-center gap-3">
                    <i class="fas fa-file-image text-emerald-600 text-xl"></i>
                    <div>
                        <p class="text-xs font-semibold text-emerald-950">Comprobante guardado previamente</p>
                        <a href="{{ asset('storage/' . $solicitud->comprobante) }}" target="_blank"
                            class="text-xs text-blue-600 hover:underline inline-flex items-center gap-1 mt-0.5">
                            <i class="fas fa-external-link-alt"></i> Ver documento cargado
                        </a>
                    </div>
                </div>
                <span class="text-[11px] font-semibold bg-emerald-100 text-emerald-800 px-2.5 py-0.5 rounded-full">
                    Cargado
                </span>
            </div>
        @endif
    </div>

    <!-- Barra de Acciones Final (Sticky Bottom Bar) -->
    <div class="bg-white rounded-2xl border border-slate-200 shadow-md p-4 flex flex-col sm:flex-row items-center justify-between gap-4">
        <div>
            @if (!$this->todosEquiposMarcados)
                <span class="text-xs text-amber-700 bg-amber-50 border border-amber-200 px-3 py-1.5 rounded-lg font-medium inline-flex items-center gap-1.5">
                    <i class="fas fa-exclamation-triangle text-amber-500"></i> Faltan equipos por clasificar.
                </span>
            @else
                <span class="text-xs text-emerald-700 bg-emerald-50 border border-emerald-200 px-3 py-1.5 rounded-lg font-medium inline-flex items-center gap-1.5">
                    <i class="fas fa-check-circle text-emerald-500"></i> Todos los equipos han sido validados.
                </span>
            @endif
        </div>

        <div class="flex flex-wrap items-center gap-2.5 w-full sm:w-auto justify-end">
            <button type="button" wire:click="generarActa('descargo')"
                class="bg-slate-100 hover:bg-slate-200 text-slate-700 font-semibold text-xs px-4 py-2.5 rounded-xl transition disabled:opacity-50 disabled:cursor-not-allowed flex items-center gap-1.5"
                @if (!$this->todosEquiposMarcados) disabled @endif>
                <i class="fas fa-file-word text-blue-600"></i> Acta Descargo
            </button>

            <button type="button" wire:click="generarActa('liberacion')"
                class="bg-slate-100 hover:bg-slate-200 text-slate-700 font-semibold text-xs px-4 py-2.5 rounded-xl transition disabled:opacity-50 disabled:cursor-not-allowed flex items-center gap-1.5"
                @if (!$this->todosEquiposMarcados) disabled @endif>
                <i class="fas fa-file-word text-indigo-600"></i> Acta Liberación
            </button>

            <button type="button" wire:click="guardarDisposicion"
                class="bg-emerald-600 hover:bg-emerald-700 text-white font-semibold text-xs px-5 py-2.5 rounded-xl shadow-sm transition disabled:opacity-50 disabled:cursor-not-allowed flex items-center gap-1.5"
                @if (!$this->todosEquiposMarcados) disabled @endif>
                <i class="fas fa-save"></i> Guardar Cambios
            </button>
        </div>
    </div>

</div>
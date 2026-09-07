<div class="max-w-4xl mx-auto p-6 bg-white rounded-xl shadow-sm border border-gray-100" wire:loading.class="opacity-60">

    {{-- ================= STEPPER SUPERIOR ================= --}}
    <div class="flex items-center justify-between mb-8">
        @foreach (['Entorno', 'Equipos', 'Prioridad', 'Resultado'] as $i => $label)
            @php $n = $i + 1; @endphp
            <button
                type="button"
                wire:click="irAlPaso({{ $n }})"
                class="flex-1 flex flex-col items-center gap-1 group"
            >
                <span
                    class="flex items-center justify-center h-8 w-8 rounded-full text-xs font-bold border-2 transition-colors
                        {{ $step === $n ? 'bg-blue-600 border-blue-600 text-white' : ($step > $n ? 'bg-blue-100 border-blue-300 text-blue-600' : 'bg-white border-gray-300 text-gray-400') }}"
                >
                    {{ $step > $n ? '✓' : $n }}
                </span>
                <span class="text-[11px] font-semibold uppercase tracking-wide {{ $step === $n ? 'text-blue-600' : 'text-gray-400' }}">
                    {{ $label }}
                </span>
            </button>
            @if ($n < 4)
                <div class="flex-1 h-0.5 mx-1 {{ $step > $n ? 'bg-blue-300' : 'bg-gray-200' }}"></div>
            @endif
        @endforeach
    </div>

    {{-- ================= PASO 1 · ENTORNO ================= --}}
    @if ($step === 1)
        <div>
            <div class="mb-6">
                <div class="flex items-center gap-2 text-sm font-semibold text-blue-600 uppercase tracking-wider">
                    <span>Paso 1</span>
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3" />
                    </svg>
                </div>
                <h2 class="text-xl font-bold text-gray-800 mt-1">Seleccione Entorno</h2>
                <p class="text-sm text-gray-500">Elija el tipo de ubicación donde se utilizará el generador eléctrico para
                    personalizar los equipos disponibles.</p>
            </div>

            <div class="grid grid-cols-2 md:grid-cols-3 gap-4">
                @foreach ($this->entornos as $entorno)
                    @php $activo = $entorno_id === $entorno->id; @endphp
                    <label
                        wire:click="seleccionarEntorno({{ $entorno->id }})"
                        class="relative flex flex-col items-center justify-center p-6 rounded-xl border-2 cursor-pointer transition-all duration-200 group
                            {{ $activo ? 'bg-blue-50/50 border-blue-600 shadow-sm' : 'border-gray-200 bg-white hover:border-blue-400 hover:bg-gray-50/50' }}"
                    >
                        <div class="p-3 rounded-lg group-hover:scale-105 transition-transform duration-200
                            {{ $activo ? 'bg-blue-100 text-blue-600' : 'bg-gray-100 text-gray-600 group-hover:text-blue-500 group-hover:bg-blue-50' }}">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                @switch($entorno->slug)
                                    @case('residencial')
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
                                        @break
                                    @case('comercial')
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                                        @break
                                    @case('industrial')
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M3 21h18M3 7l5 4V7l5 4V7l6 5v9H3V7z" />
                                        @break
                                    @case('eventos')
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M5 3v4M3 5h4M6 17v4m-2-2h4m5-16l2.286 6.857L21 12l-5.714 2.143L13 21l-2.286-6.857L5 12l5.714-2.143L13 3z" />
                                        @break
                                    @case('campamento')
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 22L12 3l6 19M6 22h12M12 3v19M9 14h6" />
                                        @break
                                @endswitch
                            </svg>
                        </div>
                        <span class="mt-4 font-bold text-sm tracking-wide uppercase {{ $activo ? 'text-gray-800' : 'text-gray-700 group-hover:text-gray-900' }}">
                            {{ $entorno->nombre }}
                        </span>
                        @if ($activo)
                            <span class="absolute top-3 right-3 flex h-5 w-5 items-center justify-center rounded-full bg-blue-600 text-white text-xs">✓</span>
                        @endif
                    </label>
                @endforeach
            </div>

            @error('entorno') <p class="text-sm text-red-600 mt-3">{{ $message }}</p> @enderror

            <div class="mt-6 flex justify-end">
                <button type="button" wire:click="siguientePaso"
                    class="inline-flex items-center gap-2 px-5 py-2.5 bg-gray-800 hover:bg-gray-900 text-white font-semibold text-sm rounded-lg transition-colors duration-150 shadow-sm">
                    Siguiente
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                    </svg>
                </button>
            </div>
        </div>
    @endif

    {{-- ================= PASO 2 · EQUIPOS ================= --}}
    @if ($step === 2)
        <div>
            <div class="mb-6">
                <div class="flex items-center gap-2 text-sm font-semibold text-blue-600 uppercase tracking-wider">
                    <span>Paso 2</span>
                </div>
                <h2 class="text-xl font-bold text-gray-800 mt-1">Seleccione Equipos</h2>
                <p class="text-sm text-gray-500">Marque los equipos que se conectarán y ajuste la cantidad de cada uno.</p>
            </div>

            {{-- Buscador para añadir equipos que no estén en el catálogo del entorno --}}
            <div class="relative mb-4">
                <div class="flex items-center gap-2 border border-gray-200 rounded-lg px-3 py-2">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-4.35-4.35M17 10a7 7 0 11-14 0 7 7 0 0114 0z" />
                    </svg>
                    <input type="text" wire:model.live.debounce.300ms="busquedaEquipo" placeholder="Buscar equipo..."
                        class="w-full text-sm outline-none placeholder-gray-400">
                </div>

                @if ($this->sugerenciasBusqueda->isNotEmpty())
                    <div class="absolute z-10 mt-1 w-full bg-white border border-gray-200 rounded-lg shadow-lg overflow-hidden">
                        @foreach ($this->sugerenciasBusqueda as $sugerencia)
                            <button type="button" wire:click="agregarEquipo({{ $sugerencia->id }})"
                                class="w-full flex items-center gap-3 px-4 py-2.5 text-sm text-left hover:bg-blue-50">
                                <span class="text-lg">{{ $sugerencia->icono }}</span>
                                <span class="text-gray-700">{{ $sugerencia->nombre }}</span>
                                <span class="ml-auto text-xs text-gray-400">{{ $sugerencia->potencia_nominal_w }}W</span>
                            </button>
                        @endforeach
                    </div>
                @endif
            </div>

            {{-- Lista de equipos --}}
            <div class="border border-gray-100 rounded-lg divide-y divide-gray-100 max-h-96 overflow-y-auto">
                @forelse ($this->catalogoCompleto as $equipoId => $equipo)
                    @php $item = $equipos[$equipoId] ?? ['seleccionado' => false, 'cantidad' => 0]; @endphp
                    <div class="flex items-center gap-3 px-4 py-3">
                        <input type="checkbox"
                            wire:click="toggleEquipo({{ $equipoId }})"
                            @checked($item['seleccionado'])
                            class="h-4 w-4 rounded border-gray-300 text-blue-600 focus:ring-blue-500">

                        <span class="text-xl w-6 text-center">{{ $equipo->icono }}</span>

                        <div class="flex-1">
                            <p class="text-sm font-medium text-gray-800">{{ $equipo->nombre }}</p>
                            <p class="text-xs text-gray-400">
                                {{ $equipo->potencia_nominal_w }}W
                                @if ($equipo->es_motor)
                                    · arranque {{ $equipo->potencia_arranque_w }}W
                                @endif
                            </p>
                        </div>

                        <input type="number" min="0"
                            wire:change="actualizarCantidad({{ $equipoId }}, $event.target.value)"
                            value="{{ $item['cantidad'] }}"
                            class="w-16 text-center text-sm border border-gray-200 rounded-md py-1.5">

                        <button type="button" wire:click="quitarEquipo({{ $equipoId }})" title="Quitar"
                            class="text-gray-300 hover:text-red-500 transition-colors">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                            </svg>
                        </button>
                    </div>
                @empty
                    <p class="px-4 py-6 text-sm text-center text-gray-400">Usa el buscador para añadir equipos.</p>
                @endforelse
            </div>

            @error('equipos') <p class="text-sm text-red-600 mt-3">{{ $message }}</p> @enderror

            <div class="mt-6 flex justify-between">
                <button type="button" wire:click="pasoAnterior"
                    class="inline-flex items-center gap-2 px-5 py-2.5 text-gray-600 hover:text-gray-900 font-semibold text-sm">
                    Atrás
                </button>
                <button type="button" wire:click="siguientePaso"
                    class="inline-flex items-center gap-2 px-5 py-2.5 bg-gray-800 hover:bg-gray-900 text-white font-semibold text-sm rounded-lg transition-colors duration-150 shadow-sm">
                    Siguiente
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                    </svg>
                </button>
            </div>
        </div>
    @endif

    {{-- ================= PASO 3 · PRIORIDAD (opcional) ================= --}}
    @if ($step === 3)
        <div>
            <div class="mb-6">
                <div class="flex items-center gap-2 text-sm font-semibold text-blue-600 uppercase tracking-wider">
                    <span>Paso 3 · Opcional</span>
                </div>
                <h2 class="text-xl font-bold text-gray-800 mt-1">Prioridad de Equipos</h2>
                <p class="text-sm text-gray-500">Marca qué equipos deben mantenerse siempre encendidos (críticos) y cuáles se usan de forma intermitente. Esto ayuda a dimensionar mejor el arranque.</p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                {{-- Equipos críticos --}}
                <div>
                    <h3 class="text-xs font-bold text-gray-500 uppercase tracking-wide mb-2">Equipos Críticos</h3>
                    <div class="border border-gray-100 rounded-lg divide-y divide-gray-100">
                        @forelse ($this->equiposActivos() as $equipo)
                            <label class="flex items-center gap-3 px-4 py-3 cursor-pointer">
                                <input type="checkbox"
                                    wire:click="marcarPrioridad({{ $equipo->id }}, 'critico')"
                                    @checked(($prioridades[$equipo->id] ?? null) === 'critico')
                                    class="h-4 w-4 rounded border-gray-300 text-blue-600 focus:ring-blue-500">
                                <span class="text-sm font-medium text-gray-700">{{ $equipo->nombre }}</span>
                                @if (($prioridades[$equipo->id] ?? null) === 'critico')
                                    <span class="ml-auto text-[10px] font-bold uppercase bg-blue-100 text-blue-600 px-2 py-0.5 rounded-full">Encendido</span>
                                @endif
                            </label>
                        @empty
                            <p class="px-4 py-6 text-sm text-center text-gray-400">No hay equipos seleccionados.</p>
                        @endforelse
                    </div>
                </div>

                {{-- Equipos intermitentes --}}
                <div>
                    <h3 class="text-xs font-bold text-gray-500 uppercase tracking-wide mb-2">Equipos Intermitentes</h3>
                    <div class="border border-gray-100 rounded-lg divide-y divide-gray-100">
                        @forelse ($this->equiposActivos() as $equipo)
                            <label class="flex items-center gap-3 px-4 py-3 cursor-pointer">
                                <input type="checkbox"
                                    wire:click="marcarPrioridad({{ $equipo->id }}, 'intermitente')"
                                    @checked(($prioridades[$equipo->id] ?? null) === 'intermitente')
                                    class="h-4 w-4 rounded border-gray-300 text-blue-600 focus:ring-blue-500">
                                <span class="text-sm font-medium text-gray-700">{{ $equipo->nombre }}</span>
                                @if (($prioridades[$equipo->id] ?? null) === 'intermitente')
                                    <span class="ml-auto text-[10px] font-bold uppercase bg-amber-100 text-amber-600 px-2 py-0.5 rounded-full">Encendido</span>
                                @endif
                            </label>
                        @empty
                            <p class="px-4 py-6 text-sm text-center text-gray-400">No hay equipos seleccionados.</p>
                        @endforelse
                    </div>
                </div>
            </div>

            <div class="mt-6 flex justify-between">
                <button type="button" wire:click="pasoAnterior"
                    class="inline-flex items-center gap-2 px-5 py-2.5 text-gray-600 hover:text-gray-900 font-semibold text-sm">
                    Atrás
                </button>
                <button type="button" wire:click="calcularCapacidad"
                    class="inline-flex items-center gap-2 px-5 py-2.5 bg-blue-600 hover:bg-blue-700 text-white font-semibold text-sm rounded-lg transition-colors duration-150 shadow-sm">
                    Calcular Capacidad
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                    </svg>
                </button>
            </div>
        </div>
    @endif

    {{-- ================= PASO 4 · RESULTADO Y RECOMENDACIÓN ================= --}}
    @if ($step === 4)
        @php $resultado = $this->resultado(); @endphp
        <div>
            <div class="mb-6">
                <div class="flex items-center gap-2 text-sm font-semibold text-blue-600 uppercase tracking-wider">
                    <span>Paso 4</span>
                </div>
                <h2 class="text-xl font-bold text-gray-800 mt-1">Resultado y Recomendación</h2>
            </div>

            {{-- Capacidad total --}}
            <div class="text-center mb-8">
                <p class="text-xs font-bold text-gray-500 uppercase tracking-wide mb-2">Capacidad Total Recomendada</p>
                <div class="inline-block px-8 py-4 bg-gray-50 border-2 border-gray-800 rounded-xl">
                    <span class="text-3xl font-black text-gray-800">
                        {{ $resultado['capacidad_kva'] }} kVA / {{ $resultado['capacidad_kw'] }} kW
                    </span>
                </div>
                <p class="text-xs text-gray-400 mt-2">
                    *Incluye margen de seguridad del 25% (aprox. {{ round($resultado['margen_w'] / 1000, 1) }} kVA)
                </p>
            </div>

            {{-- Modelos recomendados --}}
            <h3 class="text-sm font-bold text-gray-700 uppercase tracking-wide mb-3">Modelos Recomendados</h3>
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 mb-6">
                @forelse ($this->generadoresRecomendados() as $generador)
                    <div class="border border-gray-200 rounded-xl p-4 flex flex-col items-center text-center hover:border-blue-400 transition-colors">
                        <div class="h-24 w-full flex items-center justify-center bg-gray-50 rounded-lg mb-3">
                            @if ($generador->imagen)
                                <img src="{{ $generador->imagen }}" alt="{{ $generador->nombre }}" class="h-20 object-contain">
                            @else
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-10 w-10 text-gray-300" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 7h16M4 7v10a1 1 0 001 1h14a1 1 0 001-1V7M4 7l2-3h12l2 3M9 12h6" />
                                </svg>
                            @endif
                        </div>
                        <p class="text-sm font-bold text-gray-800">{{ $generador->nombre }}</p>
                        <p class="text-xs text-gray-500 mt-1">
                            Precio: {{ $generador->precio ? '$'.number_format($generador->precio, 2) : 'Consultar' }}
                        </p>
                        <button type="button" wire:click="abrirModalCotizacion" wire:click.prevent="$set('generadorSeleccionado', {{ $generador->id }})"
                            class="mt-3 text-xs font-semibold text-blue-600 hover:text-blue-800">
                            Detalles / Cotizar →
                        </button>
                    </div>
                @empty
                    <p class="col-span-2 text-sm text-center text-gray-400 py-6">
                        No hay modelos que cubran esta capacidad con el filtro actual. Ajusta el filtro de combustible.
                    </p>
                @endforelse
            </div>

            {{-- Filtro combustible --}}
            <div class="mb-6">
                <p class="text-xs font-bold text-gray-500 uppercase tracking-wide mb-2">Filtrar por combustible</p>
                <div class="flex gap-4">
                    @foreach (['gasolina' => 'Gasolina', 'diesel' => 'Diésel', 'gas' => 'Gas'] as $key => $label)
                        <label class="flex items-center gap-2 text-sm text-gray-600 cursor-pointer">
                            <input type="checkbox" wire:model.live="filtroCombustible.{{ $key }}"
                                class="h-4 w-4 rounded border-gray-300 text-blue-600 focus:ring-blue-500">
                            {{ $label }}
                        </label>
                    @endforeach
                </div>
            </div>

            <div class="flex flex-col sm:flex-row gap-3">
                <button type="button" wire:click="pasoAnterior"
                    class="inline-flex items-center justify-center gap-2 px-5 py-3 text-gray-600 hover:text-gray-900 font-semibold text-sm">
                    Atrás
                </button>
                <button type="button" wire:click="abrirModalCotizacion"
                    class="flex-1 inline-flex items-center justify-center gap-2 px-5 py-3 bg-gray-800 hover:bg-gray-900 text-white font-bold text-sm rounded-lg transition-colors duration-150 shadow-sm">
                    Cotizar / Contactar Asesor
                </button>
            </div>
        </div>
    @endif

    {{-- ================= MODAL DE COTIZACIÓN ================= --}}
    @if ($mostrarModalCotizacion)
        <div class="fixed inset-0 bg-black/40 flex items-center justify-center z-50 p-4" wire:click.self="cerrarModalCotizacion">
            <div class="bg-white rounded-xl shadow-xl w-full max-w-md p-6">
                @if ($cotizacionEnviada)
                    <div class="text-center py-6">
                        <div class="mx-auto h-12 w-12 flex items-center justify-center rounded-full bg-green-100 text-green-600 text-2xl mb-3">✓</div>
                        <h3 class="text-lg font-bold text-gray-800">¡Solicitud enviada!</h3>
                        <p class="text-sm text-gray-500 mt-1">Un asesor se pondrá en contacto contigo pronto.</p>
                        <button type="button" wire:click="cerrarModalCotizacion"
                            class="mt-4 px-5 py-2 bg-gray-800 text-white text-sm font-semibold rounded-lg">Cerrar</button>
                    </div>
                @else
                    <h3 class="text-lg font-bold text-gray-800 mb-1">Solicitar cotización</h3>
                    <p class="text-sm text-gray-500 mb-4">Déjanos tus datos y un asesor te contactará con el detalle.</p>

                    <div class="space-y-3">
                        <div>
                            <label class="text-xs font-semibold text-gray-500">Nombre</label>
                            <input type="text" wire:model="nombre_cliente"
                                class="w-full mt-1 border border-gray-200 rounded-lg px-3 py-2 text-sm">
                            @error('nombre_cliente') <p class="text-xs text-red-600 mt-1">{{ $message }}</p> @enderror
                        </div>
                        <div>
                            <label class="text-xs font-semibold text-gray-500">Teléfono</label>
                            <input type="text" wire:model="telefono"
                                class="w-full mt-1 border border-gray-200 rounded-lg px-3 py-2 text-sm">
                            @error('telefono') <p class="text-xs text-red-600 mt-1">{{ $message }}</p> @enderror
                        </div>
                        <div>
                            <label class="text-xs font-semibold text-gray-500">Email (opcional)</label>
                            <input type="email" wire:model="email"
                                class="w-full mt-1 border border-gray-200 rounded-lg px-3 py-2 text-sm">
                            @error('email') <p class="text-xs text-red-600 mt-1">{{ $message }}</p> @enderror
                        </div>
                    </div>

                    <div class="mt-5 flex justify-end gap-3">
                        <button type="button" wire:click="cerrarModalCotizacion"
                            class="px-4 py-2 text-sm font-semibold text-gray-500 hover:text-gray-800">Cancelar</button>
                        <button type="button" wire:click="enviarCotizacion"
                            class="px-5 py-2 bg-blue-600 hover:bg-blue-700 text-white text-sm font-bold rounded-lg">Enviar</button>
                    </div>
                @endif
            </div>
        </div>
    @endif
</div>

<div class="space-y-6 max-w-4xl mx-auto mt-8">

    <!-- Buscador Minimalista con Estado Activo Mejorado -->
    <div class="relative group">
        <span class="absolute inset-y-0 left-4 flex items-center text-gray-400 group-focus-within:text-gray-900 transition-colors">
            <i class="fas fa-search text-sm"></i>
        </span>
        <input type="text" wire:model.live="search" placeholder="Buscar colaborador por nombre o cédula..."
            class="w-full pl-11 pr-4 py-3.5 bg-white border border-gray-200/80 rounded-2xl text-sm focus:outline-none focus:border-gray-900 focus:ring-2 focus:ring-gray-900/10 transition-all shadow-sm placeholder:text-gray-400 disabled:bg-gray-50/50 disabled:text-gray-400"
            @if ($this->colaborador) disabled @endif />
    </div>

    <!-- Resultados Flotantes con Diseño Limpio -->
    @if ($search && $this->colaboradores->count())
        <ul class="absolute z-30 w-full bg-white/95 backdrop-blur-md border border-gray-100 rounded-2xl shadow-xl max-h-60 overflow-auto divide-y divide-gray-50 mt-1">
            @foreach ($this->colaboradores as $user)
                <li wire:click="seleccionarColaborador({{ $user->id }})"
                    class="px-5 py-3.5 hover:bg-gray-50 cursor-pointer transition-all flex items-center justify-between group">
                    <span class="text-sm font-medium text-gray-900 group-hover:text-black">{{ $user->name }}</span>
                    <span class="text-xs text-gray-400 font-mono bg-gray-50 px-2 py-1 rounded-md group-hover:bg-white transition-colors">C.I. {{ $user->cedula }}</span>
                </li>
            @endforeach
        </ul>
    @endif

    <!-- Ficha del Colaborador Seleccionado -->
    @if ($this->colaborador)
        <div class="bg-white border border-gray-200/80 rounded-3xl p-7 space-y-6 shadow-sm">

            <!-- Cabecera Limpia con Identidad -->
            <div class="flex items-center justify-between pb-5 border-b border-gray-100">
                <div class="flex items-center gap-4">
                    <div class="w-12 h-12 rounded-2xl bg-gray-900 text-white flex items-center justify-center font-semibold text-sm shadow-md shadow-gray-900/10">
                        {{ strtoupper(substr($this->colaborador->name, 0, 2)) }}
                    </div>
                    <div>
                        <h3 class="text-base font-bold text-gray-900 tracking-tight">{{ $this->colaborador->name }}</h3>
                        <p class="text-xs text-gray-500 font-medium mt-0.5">Cédula: <span class="font-mono text-gray-700">{{ $this->colaborador->cedula }}</span></p>
                    </div>
                </div>
                <button wire:click="limpiarColaborador"
                    class="text-xs font-medium text-gray-500 hover:text-red-600 transition-colors flex items-center gap-1.5 px-3 py-2 rounded-xl hover:bg-red-50/80 border border-transparent hover:border-red-100">
                    <i class="fas fa-times text-xs"></i> Cambiar
                </button>
            </div>

            <!-- Información de Cuadrilla y Compañero (Tarjetas Minimalistas) -->
            @php
                $cuadrillaAsignada = $this->colaborador->cuadrillas()->with('users')->first();
                $companero = $cuadrillaAsignada 
                    ? $cuadrillaAsignada->users->where('id', '!=', $this->colaborador->id)->first() 
                    : null;
            @endphp

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                <div class="bg-gray-50/60 border border-gray-100 rounded-2xl p-4 flex items-start gap-3.5">
                    <div class="w-8 h-8 rounded-xl bg-white border border-gray-200/60 flex items-center justify-center text-gray-600 shrink-0 shadow-xs">
                        <i class="fas fa-users text-xs"></i>
                    </div>
                    <div>
                        <span class="block text-[10px] font-bold uppercase tracking-wider text-gray-400">Cuadrilla Asignada</span>
                        <span class="text-xs font-bold text-gray-800 mt-0.5 block">
                            {{ $cuadrillaAsignada->cua_nombre ?? 'Sin cuadrilla activa' }}
                        </span>
                    </div>
                </div>

                <div class="bg-gray-50/60 border border-gray-100 rounded-2xl p-4 flex items-start gap-3.5">
                    <div class="w-8 h-8 rounded-xl bg-white border border-gray-200/60 flex items-center justify-center text-gray-600 shrink-0 shadow-xs">
                        <i class="fas fa-user-friends text-xs"></i>
                    </div>
                    <div>
                        <span class="block text-[10px] font-bold uppercase tracking-wider text-gray-400">Compañero de Cuadrilla</span>
                        <span class="text-xs font-bold text-gray-800 mt-0.5 block">
                            {{ $companero->name ?? 'Ninguno' }}
                        </span>
                    </div>
                </div>
            </div>

            <!-- Sección de Talento Humano Minimal -->
            <div class="space-y-4 pt-2">
                <div>
                    <h4 class="text-xs font-bold uppercase tracking-wider text-gray-400">Proceso de Talento Humano</h4>
                    <p class="text-sm text-gray-600 mt-0.5">Indique los implementos a entregar por el colaborador.</p>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-3 pt-1">
                    <label class="flex items-center gap-3 p-3.5 bg-white border border-gray-200/80 rounded-2xl cursor-pointer hover:border-gray-300 hover:bg-gray-50/50 transition-all select-none">
                        <input type="checkbox" wire:model="devolverCredencial" class="w-4 h-4 text-gray-900 border-gray-300 rounded focus:ring-gray-900 cursor-pointer">
                        <span class="text-sm font-medium text-gray-700">Devuelve Credencial</span>
                    </label>
                    <label class="flex items-center gap-3 p-3.5 bg-white border border-gray-200/80 rounded-2xl cursor-pointer hover:border-gray-300 hover:bg-gray-50/50 transition-all select-none">
                        <input type="checkbox" wire:model="devolverUniforme" class="w-4 h-4 text-gray-900 border-gray-300 rounded focus:ring-gray-900 cursor-pointer">
                        <span class="text-sm font-medium text-gray-700">Devuelve Uniformes</span>
                    </label>
                </div>

                <!-- Observaciones -->
                <div class="space-y-1.5 pt-1">
                    <label class="block text-xs font-semibold text-gray-500">Observaciones</label>
                    <textarea wire:model="observaciones" rows="3" placeholder="Notas adicionales del proceso..."
                        class="w-full border border-gray-200/80 rounded-2xl p-3.5 text-sm focus:outline-none focus:border-gray-900 focus:ring-2 focus:ring-gray-900/10 transition placeholder:text-gray-400 text-gray-700 shadow-sm"></textarea>
                </div>

                <div class="flex justify-end pt-3">
                    <button type="button" wire:click="guardarSolicitudTTHH"
                        class="bg-gray-900 hover:bg-black text-white text-sm font-semibold px-6 py-3 rounded-2xl transition-all shadow-sm hover:shadow flex items-center gap-2">
                        <span>Guardar y Enviar a Sistemas</span>
                        <i class="fas fa-arrow-right text-xs"></i>
                    </button>
                </div>
            </div>

        </div>
    @endif
</div>
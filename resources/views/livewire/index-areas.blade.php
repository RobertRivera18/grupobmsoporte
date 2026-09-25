<div x-data="{ viewMode: @entangle('viewMode').live || 'grid' }" class="space-y-4">
    
    <div class="flex justify-end items-center mb-4">
        <div class="inline-flex p-1 bg-gray-100/80 backdrop-blur-md rounded-2xl border border-gray-200/60 shadow-xs">
            <button 
                type="button"
                @click="viewMode = 'grid'" 
                :class="viewMode === 'grid' ? 'bg-white text-gray-900 shadow-sm font-semibold' : 'text-gray-500 hover:text-gray-700'"
                class="flex items-center gap-2 px-3 py-1.5 text-xs rounded-xl transition-all duration-200 cursor-pointer"
                title="Vista en tarjetas">
                <i class="fas fa-th-large"></i>
                <span class="hidden sm:inline">Tarjetas</span>
            </button>

            <button 
                type="button"
                @click="viewMode = 'list'" 
                :class="viewMode === 'list' ? 'bg-white text-gray-900 shadow-sm font-semibold' : 'text-gray-500 hover:text-gray-700'"
                class="flex items-center gap-2 px-3 py-1.5 text-xs rounded-xl transition-all duration-200 cursor-pointer"
                title="Vista en lista">
                <i class="fas fa-list"></i>
                <span class="hidden sm:inline">Lista</span>
            </button>
        </div>
    </div>

    <div x-show="viewMode === 'grid'" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 scale-98" x-transition:enter-end="opacity-100 scale-100" class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 xl:grid-cols-4 gap-5">
        @foreach ($areas as $area)
            <div class="group relative bg-white/80 backdrop-blur-xl rounded-3xl border border-gray-200/60 p-6 shadow-2xl/5 hover:shadow-xl hover:border-gray-300/80 hover:-translate-y-1 transition-all duration-300 flex flex-col justify-between">
                <div>
                    <div class="flex items-start justify-between">
                        <div class="flex items-center justify-center w-12 h-12 rounded-2xl bg-gray-100 text-gray-800 text-base font-semibold tracking-tight group-hover:bg-black group-hover:text-white transition-all duration-300">
                            {{ strtoupper(substr($area->nombre, 0, 1)) }}
                        </div>

                        <div class="flex items-center gap-0.5 bg-gray-100/80 backdrop-blur-md p-1 rounded-full border border-gray-200/50 opacity-0 group-hover:opacity-100 transition-all duration-200 shadow-sm">
                            <a href="{{ route('admin.areas.show', $area) }}"
                                class="p-2 rounded-full text-gray-500 hover:bg-white hover:text-black hover:shadow-xs transition"
                                title="Ver detalle">
                                <i class="fas fa-eye text-xs"></i>
                            </a>

                            <a href="{{ route('admin.areas.edit', $area) }}"
                                class="p-2 rounded-full text-gray-500 hover:bg-white hover:text-black hover:shadow-xs transition"
                                title="Editar">
                                <i class="fas fa-edit text-xs"></i>
                            </a>

                            <button wire:click="eliminar({{ $area->id }})"
                                class="p-2 rounded-full text-gray-500 hover:bg-white hover:text-red-500 hover:shadow-xs transition cursor-pointer"
                                title="Eliminar">
                                <i class="fas fa-trash text-xs"></i>
                            </button>
                        </div>
                    </div>

                    <div class="mt-5">
                        <h3 class="text-base font-semibold text-gray-900 tracking-tight group-hover:text-black transition-colors line-clamp-1" title="{{ $area->nombre }}">
                            {{ $area->nombre }}
                        </h3>
                    </div>
                </div>

                <div class="mt-6 pt-4 border-t border-gray-100/80 flex items-center gap-3">
                    @if($area->responsable)
                        <div class="w-8 h-8 rounded-full bg-gray-100 text-gray-700 flex items-center justify-center text-xs font-medium tracking-tighter shrink-0">
                            {{ strtoupper(substr($area->responsable->name, 0, 1)) }}
                        </div>
                        <div class="overflow-hidden">
                            <p class="text-[10px] font-medium text-gray-400 tracking-wide">Responsable</p>
                            <p class="text-xs font-medium text-gray-800 truncate" title="{{ $area->responsable->name }}">
                                {{ $area->responsable->name }}
                            </p>
                        </div>
                    @else
                        <div class="w-8 h-8 rounded-full bg-gray-50 text-gray-300 flex items-center justify-center shrink-0">
                            <i class="fas fa-minus text-[10px]"></i>
                        </div>
                        <div class="overflow-hidden">
                            <p class="text-[10px] font-medium text-gray-400 tracking-wide">Responsable</p>
                            <p class="text-xs font-normal text-gray-400 italic">No asignado</p>
                        </div>
                    @endif
                </div>
            </div>
        @endforeach
    </div>

    <!-- VISTA EN LISTA -->
    <div x-show="viewMode === 'list'" x-cloak x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 scale-98" x-transition:enter-end="opacity-100 scale-100" class="bg-white/80 backdrop-blur-xl rounded-3xl border border-gray-200/60 shadow-2xl/5 overflow-hidden">
        <div class="divide-y divide-gray-100">
            @foreach ($areas as $area)
                <div class="group flex items-center justify-between p-4 sm:px-6 hover:bg-gray-50/80 transition-all duration-200">
                    
                    <!-- Info Izquierda -->
                    <div class="flex items-center gap-4 min-w-0">
                        <div class="flex items-center justify-center w-10 h-10 rounded-2xl bg-gray-100 text-gray-800 text-sm font-semibold tracking-tight group-hover:bg-black group-hover:text-white transition-all duration-300 shrink-0">
                            {{ strtoupper(substr($area->nombre, 0, 1)) }}
                        </div>

                        <div class="min-w-0">
                            <h3 class="text-sm font-semibold text-gray-900 truncate" title="{{ $area->nombre }}">
                                {{ $area->nombre }}
                            </h3>
                            <div class="flex items-center gap-2 mt-0.5">
                                <span class="text-[11px] text-gray-400">Responsable:</span>
                                @if($area->responsable)
                                    <span class="text-xs font-medium text-gray-700 truncate">{{ $area->responsable->name }}</span>
                                @else
                                    <span class="text-xs font-normal text-gray-400 italic">No asignado</span>
                                @endif
                            </div>
                        </div>
                    </div>

                    <!-- Acciones Derecha -->
                    <div class="flex items-center gap-1 shrink-0">
                        <a href="{{ route('admin.areas.show', $area) }}"
                            class="p-2 rounded-xl text-gray-500 hover:bg-gray-100 hover:text-black transition"
                            title="Ver detalle">
                            <i class="fas fa-eye text-xs"></i>
                        </a>

                        <a href="{{ route('admin.areas.edit', $area) }}"
                            class="p-2 rounded-xl text-gray-500 hover:bg-gray-100 hover:text-black transition"
                            title="Editar">
                            <i class="fas fa-edit text-xs"></i>
                        </a>

                        <button wire:click="eliminar({{ $area->id }})"
                            class="p-2 rounded-xl text-gray-500 hover:bg-red-50 hover:text-red-500 transition cursor-pointer"
                            title="Eliminar">
                            <i class="fas fa-trash text-xs"></i>
                        </button>
                    </div>

                </div>
            @endforeach
        </div>
    </div>

</div>
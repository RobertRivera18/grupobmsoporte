<x-app-layout>
    <div class="py-4 px-2 sm:px-4 lg:px-6">
        <div class="max-w-3xl mx-auto">
            
            <div class="mb-3">
                <a href="{{ route('incidentesvehiculos.index') }}" 
                   class="inline-flex items-center text-xs font-medium text-gray-500 hover:text-gray-800 transition-colors">
                    <i class="fas fa-arrow-left mr-1.5 text-[10px]"></i> Volver al historial
                </a>
            </div>

            <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
                
                <div class="px-4 py-3 bg-gray-50/75 border-b border-gray-100 flex items-center justify-between">
                    <div class="flex items-center space-x-2">
                        <span class="text-base font-bold text-gray-900">Movimiento #{{ $incidente->id }}</span>
                        
                        @if ($incidente->tipo == 1)
                            <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[11px] font-medium bg-emerald-50 text-emerald-700 border border-emerald-100">
                                <span class="w-1.5 h-1.5 mr-1 bg-emerald-500 rounded-full"></span>
                                Ingreso
                            </span>
                        @else
                            <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[11px] font-medium bg-amber-50 text-amber-700 border border-amber-100">
                                <span class="w-1.5 h-1.5 mr-1 bg-amber-500 rounded-full"></span>
                                Salida
                            </span>
                        @endif
                    </div>

                    <a href="{{ route('incidentesvehiculos.edit', $incidente) }}" 
                       class="inline-flex items-center px-2.5 py-1 text-xs font-medium bg-white text-gray-700 border border-gray-200 rounded-lg hover:bg-gray-50 transition-colors shadow-sm">
                        <i class="fas fa-pencil mr-1 text-gray-400"></i> Editar
                    </a>
                </div>

                <div class="p-4 space-y-4">
                    
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div class="bg-gray-50/50 p-3 rounded-xl border border-gray-100/80">
                            <span class="block text-[11px] uppercase tracking-wider font-bold text-gray-400 mb-1.5">
                                <i class="fas fa-car mr-1"></i> Información del Vehículo
                            </span>
                            <div class="space-y-1">
                                <div class="flex items-center justify-between text-xs">
                                    <span class="text-gray-500">Placa:</span>
                                    <span class="font-mono font-bold bg-white px-1.5 py-0.5 border border-gray-200 rounded text-gray-800 uppercase">
                                        {{ $incidente->vehiculo->placa ?? 'S/P' }}
                                    </span>
                                </div>
                                <div class="flex items-center justify-between text-xs pt-1">
                                    <span class="text-gray-500">Marca / Modelo:</span>
                                    <span class="font-medium text-gray-800">
                                        {{ $incidente->vehiculo->marca ?? 'S/M' }} {{ $incidente->vehiculo->modelo ?? '' }}
                                    </span>
                                </div>
                            </div>
                        </div>

                        <div class="bg-gray-50/50 p-3 rounded-xl border border-gray-100/80 flex flex-col justify-between">
                            <div>
                                <span class="block text-[11px] uppercase tracking-wider font-bold text-gray-400 mb-1.5">
                                    <i class="fas fa-tachometer-alt mr-1"></i> Métricas del Viaje
                                </span>
                                <div class="flex items-center justify-between text-xs">
                                    <span class="text-gray-500">Kilometraje:</span>
                                    <span class="font-semibold text-gray-900 bg-indigo-50/60 px-2 py-0.5 border border-indigo-100 rounded">
                                        {{ $kilometraje }}
                                    </span>
                                </div>
                            </div>
                            <div class="flex items-center justify-between text-xs pt-1 border-t border-gray-200/40 mt-2">
                                <span class="text-gray-400 text-[11px]">Fecha y Hora:</span>
                                <span class="text-gray-600 font-medium text-[11px]">
                                    {{ \Carbon\Carbon::parse($incidente->fecha)->format('d/m/Y - h:i A') }}
                                </span>
                            </div>
                        </div>
                    </div>

                    <div class="bg-gray-50/50 p-3 rounded-xl border border-gray-100/80">
                        <span class="block text-[11px] uppercase tracking-wider font-bold text-gray-400 mb-2">
                            <i class="fas fa-user-tie mr-1"></i> Personal Asignado (Choferes)
                        </span>
                        @if($incidente->choferes->isNotEmpty())
                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-2">
                                @foreach($incidente->choferes as $chofer)
                                    <div class="flex items-center space-x-2.5 bg-white p-2 rounded-lg border border-gray-200/60 shadow-sm">
                                        <div class="w-7 h-7 bg-indigo-600 rounded-full flex items-center justify-center text-white text-xs font-bold uppercase tracking-wider">
                                            {{ substr($chofer->name, 0, 1) }}
                                        </div>
                                        <div class="flex flex-col">
                                            <span class="text-xs font-semibold text-gray-800 leading-tight">{{ $chofer->name }}</span>
                                            <span class="text-[10px] text-gray-400">{{ $chofer->email }}</span>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @else
                            <p class="text-xs text-gray-400 italic">No se registraron choferes asignados.</p>
                        @endif
                    </div>

                    <div class="bg-gray-50/50 p-3 rounded-xl border border-gray-100/80">
                        <span class="block text-[11px] uppercase tracking-wider font-bold text-gray-400 mb-2">
                            <i class="fas fa-images mr-1"></i> Evidencias fotográficas
                        </span>
                        @if($incidente->fotos->isNotEmpty())
                            <div class="grid grid-cols-3 sm:grid-cols-4 gap-2">
                                @foreach($incidente->fotos as $foto)
                                    <div class="relative group aspect-square bg-gray-100 rounded-lg overflow-hidden border border-gray-200 shadow-sm">
                                        <img src="{{ asset('storage/' . $foto->archivo) }}" 
                                             alt="Evidencia" 
                                             class="w-full h-full object-cover group-hover:scale-105 transition duration-200 cursor-pointer">
                                        
                                        <div class="absolute inset-0 bg-black/30 opacity-0 group-hover:opacity-100 flex items-center justify-center transition duration-200">
                                            <a href="{{ asset('storage/' . $foto->archivo) }}" target="_blank" class="text-white text-xs bg-black/60 px-2 py-1 rounded-md backdrop-blur-sm">
                                                <i class="fas fa-search-plus"></i> Ver
                                            </a>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @else
                            <p class="text-xs text-gray-400 italic">No se adjuntaron fotografías para este reporte.</p>
                        @endif
                    </div>

                    @if(!empty($incidente->observaciones))
                        <div class="bg-gray-50/50 p-3 rounded-xl border border-gray-100/80">
                            <span class="block text-[11px] uppercase tracking-wider font-bold text-gray-400 mb-1">
                                <i class="fas fa-comment-alt mr-1"></i> Observaciones del Incidente
                            </span>
                            <p class="text-xs text-gray-600 leading-relaxed bg-white p-2 rounded border border-gray-100">
                                {{ $incidente->observaciones }}
                            </p>
                        </div>
                    @endif

                </div>

                <div class="px-4 py-2 bg-gray-50/30 border-t border-gray-100 flex justify-between text-[10px] text-gray-400">
                    <span>Creado: {{ $incidente->created_at ? $incidente->created_at->format('d/m/Y H:i') : 'S/F' }}</span>
                    <span>Última actualización: {{ $incidente->updated_at ? $incidente->updated_at->format('d/m/Y H:i') : 'S/F' }}</span>
                </div>

            </div>

        </div>
     
    </div>
    
</x-app-layout>
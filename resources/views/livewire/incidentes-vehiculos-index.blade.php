
    <div class="py-6 px-3 sm:px-3 lg:px-3">
        <div class="max-w-7xl mx-auto">

            <div class="flex flex-col sm:flex-row sm:items-end sm:justify-between gap-4 mb-5">
                <div>
                    <div class="flex items-center gap-2 text-[11px] font-semibold text-indigo-600 uppercase tracking-wider mb-1">
                        <i class="fas fa-truck-pickup text-[10px]" aria-hidden="true"></i>
                        Control de flota
                    </div>
                    <h1 class="text-xl font-bold text-gray-900 tracking-tight">Historial de Incidentes</h1>
                    <p class="text-xs text-gray-500 mt-0.5">Registro de ingresos y salidas de vehículos.</p>
                </div>

                <div class="flex items-center gap-4">
                    <div class="hidden sm:flex items-center gap-2.5">
                        <div class="flex items-center gap-2 px-3.5 py-2 rounded-xl border border-gray-100 bg-gray-50/70">
                            <span class="text-base font-bold text-gray-900 leading-none">{{ $stats['total'] }}</span>
                            <span class="text-[10px] font-semibold text-gray-400 uppercase tracking-wide">Total</span>
                        </div>
                        <div class="flex items-center gap-2 px-3.5 py-2 rounded-xl border border-gray-100 bg-gray-50/70">
                            <span class="text-base font-bold text-emerald-600 leading-none">{{ $stats['ingresos'] }}</span>
                            <span class="text-[10px] font-semibold text-gray-400 uppercase tracking-wide">Ingresos</span>
                        </div>
                        <div class="flex items-center gap-2 px-3.5 py-2 rounded-xl border border-gray-100 bg-gray-50/70">
                            <span class="text-base font-bold text-amber-600 leading-none">{{ $stats['salidas'] }}</span>
                            <span class="text-[10px] font-semibold text-gray-400 uppercase tracking-wide">Salidas</span>
                        </div>
                    </div>

                    <a href="{{ route('incidentesvehiculos.create') }}"
                        class="inline-flex items-center px-3.5 py-2 bg-indigo-600 hover:bg-indigo-700 text-white font-semibold text-xs rounded-lg shadow-sm shadow-indigo-200 transition-colors duration-150">
                        <i class="fas fa-plus mr-1.5 text-[10px]" aria-hidden="true"></i> Nuevo registro
                    </a>
                </div>
            </div>

            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
                <div class="p-4 sm:p-5">

                    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3 pb-4 mb-1">
                        <label class="flex items-center gap-2 text-xs text-gray-500">
                            <span class="font-medium">Mostrar</span>
                            <select wire:model.live="perPage"
                                class="text-xs bg-white border border-gray-200 text-gray-700 rounded-lg px-2 py-1.5 outline-none focus:border-indigo-500 focus:ring-2 focus:ring-indigo-100">
                                <option value="5">5</option>
                                <option value="10">10</option>
                                <option value="25">25</option>
                                <option value="50">50</option>
                                <option value="all">Todos</option>
                            </select>
                            <span class="font-medium">registros</span>
                        </label>

                        <div class="relative w-full sm:w-64">
                            <i class="fas fa-magnifying-glass absolute left-3 top-1/2 -translate-y-1/2 text-gray-300 text-xs" aria-hidden="true"></i>
                            <input type="search" wire:model.live.debounce.300ms="search"
                                placeholder="Buscar placa, vehiculo, tipo..."
                                class="w-full text-xs bg-white border border-gray-200 text-gray-700 rounded-lg pl-8 pr-3 py-1.5 outline-none transition-shadow focus:border-indigo-500 focus:ring-2 focus:ring-indigo-100">
                        </div>
                    </div>

                    <div class="overflow-x-auto w-full">
                        <table class="w-full text-xs text-left text-gray-600">
                            <thead class="text-[11px] text-gray-500 uppercase bg-gray-50/80 border-b border-gray-100">
                                <tr>
                                    <th scope="col" class="cursor-pointer select-none font-semibold px-3 py-3" wire:click="sortBy('id')">
                                        ID @include('livewire.partials.sort-icon', ['field' => 'id'])
                                    </th>
                                    <th scope="col" class="cursor-pointer select-none font-semibold px-3 py-3" wire:click="sortBy('placa')">
                                        Vehiculo @include('livewire.partials.sort-icon', ['field' => 'placa'])
                                    </th>
                                    <th scope="col" class="cursor-pointer select-none font-semibold px-3 py-3 text-center" wire:click="sortBy('tipo')">
                                        Tipo de registro @include('livewire.partials.sort-icon', ['field' => 'tipo'])
                                    </th>
                                    <th scope="col" class="cursor-pointer select-none font-semibold px-3 py-3" wire:click="sortBy('fecha')">
                                        Fecha y hora @include('livewire.partials.sort-icon', ['field' => 'fecha'])
                                    </th>
                                    <th scope="col" class="w-16 px-3 py-3 text-right">
                                        <span class="sr-only">Acciones</span>
                                    </th>
                                </tr>
                            </thead>

                            <tbody class="divide-y divide-gray-100">
                                @forelse($incidentes as $incidente)
                                    @php
                                        $vehiculo = $incidente->vehiculo;
                                        $fecha = \Carbon\Carbon::parse($incidente->fecha);
                                    @endphp
                                    <tr class="bg-white hover:bg-gray-50/70 transition-colors" wire:key="incidente-{{ $incidente->id }}">
                                        <td class="px-3 py-3 font-bold text-gray-900">
                                            #{{ $incidente->id }}
                                        </td>

                                        <td class="px-3 py-3">
                                            <div class="flex items-center gap-2">
                                                <span class="inline-flex items-center gap-1.5 px-2 py-0.5 rounded-md border border-gray-200 bg-gradient-to-b from-gray-50 to-gray-100 shadow-inner font-mono text-[11px] font-bold tracking-wider text-gray-800 whitespace-nowrap">
                                                    <span class="w-1.5 h-1.5 rounded-full bg-indigo-600" aria-hidden="true"></span>
                                                    {{ $vehiculo->placa ?? 'S/P' }}
                                                </span>
                                                <span class="text-gray-500 text-[11px]">
                                                    {{ trim(($vehiculo->marca ?? '') . ' ' . ($vehiculo->modelo ?? '')) ?: 'Sin datos' }}
                                                </span>
                                            </div>
                                        </td>

                                        <td class="px-3 py-3 text-center">
                                            @if((int)$incidente->tipo === 1)
                                                <span class="inline-flex items-center px-2.5 py-1 rounded-full text-[11px] font-semibold bg-emerald-50 text-emerald-700 border border-emerald-100">
                                                    <span class="w-1.5 h-1.5 mr-1.5 bg-emerald-500 rounded-full" aria-hidden="true"></span>
                                                    Ingreso
                                                </span>
                                            @else
                                                <span class="inline-flex items-center px-2.5 py-1 rounded-full text-[11px] font-semibold bg-amber-50 text-amber-700 border border-amber-100">
                                                    <span class="w-1.5 h-1.5 mr-1.5 bg-amber-500 rounded-full" aria-hidden="true"></span>
                                                    Salida
                                                </span>
                                            @endif
                                        </td>

                                        <td class="px-3 py-3 text-gray-500 whitespace-nowrap">
                                            <i class="fas fa-clock text-gray-300 mr-1 text-[10px]" aria-hidden="true"></i>
                                            {{ $fecha->format('d/m/Y h:i A') }}
                                        </td>

                                        <td class="px-3 py-3 whitespace-nowrap text-right">
                                            <div class="flex items-center justify-end gap-1">
                                                <a href="{{ route('incidentesvehiculos.show', $incidente) }}"
                                                    class="inline-flex items-center justify-center w-7 h-7 rounded-lg text-gray-400 hover:bg-indigo-50 hover:text-indigo-600 transition-colors"
                                                    title="Ver detalles" aria-label="Ver detalles del incidente">
                                                    <i class="fas fa-eye text-sm" aria-hidden="true"></i>
                                                </a>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5" class="px-3 py-14 text-center">
                                            <i class="fas fa-clipboard-list text-2xl text-gray-200 mb-2 block" aria-hidden="true"></i>
                                            <p class="text-sm font-semibold text-gray-500">Sin resultados para tu búsqueda</p>
                                            <p class="text-xs text-gray-400 mt-0.5">Prueba con otra placa, vehículo o tipo de registro.</p>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    @if ($perPage !== 'all' && method_exists($incidentes, 'links'))
                        <div class="pt-4 mt-3 border-t border-gray-100">
                            {{ $incidentes->links() }}
                        </div>
                    @endif

                </div>
            </div>
        </div>
    </div>

<x-admin-layout :breadcrumbs="[
    ['name' => 'Home', 'url' => route('admin.dashboard')],
    ['name' => 'Listado de Vehiculo', 'url' => route('admin.vehiculos.index')],
    ['name' => 'Vehiculo'.' '. $vehiculo->placa],
]">
    <div class="container mx-auto px-4 py-6 max-w-5xl">

        <div class="mb-4">
            <a href="{{ route('admin.vehiculos.index') }}"
                class="inline-flex items-center text-sm font-medium text-gray-500 hover:text-indigo-600 transition-colors">
                <svg class="w-5 h-5 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                </svg>
                Volver al listado
            </a>
        </div>

        @php
            $ultimoMovimiento = $vehiculo->movimientos->first();
        @endphp

        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 mb-6">
            <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-6">

                <div class="flex items-center space-x-4">
                    <div class="p-4 bg-indigo-50 text-indigo-600 rounded-xl hidden sm:block">
                        <svg class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4" />
                        </svg>
                    </div>
                    <div>
                        <div class="flex items-center gap-3 flex-wrap">
                            <span
                                class="font-mono bg-gray-900 text-white border-2 border-gray-800 px-3 py-1 rounded-md text-lg font-bold shadow-sm tracking-wide">
                                {{ $vehiculo->placa }}
                            </span>
                            <span
                                class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                Activo
                            </span>
                        </div>
                        <h1 class="text-2xl font-bold text-gray-900 mt-2">{{ $vehiculo->marca }} <span
                                class="text-gray-500 font-normal">{{ $vehiculo->modelo }}</span></h1>
                    </div>
                </div>

                <div class="bg-slate-50 border border-slate-200 rounded-xl p-4 w-full md:w-auto min-w-[220px]">
                    <p class="text-xs font-semibold text-slate-500 uppercase tracking-wider mb-1">Kilometraje Actual</p>
                    <div class="flex items-baseline space-x-1">
                        <span class="text-3xl font-bold text-slate-800 font-mono tracking-tight">
                            {{ $ultimoMovimiento && isset($ultimoMovimiento->kilometraje) ? number_format($ultimoMovimiento->kilometraje, 0, ',', '.') : '0' }}
                        </span>
                        <span class="text-sm font-medium text-slate-500">km</span>
                    </div>
                    <p class="text-xs text-gray-400 mt-1 flex items-center">
                        <svg class="w-3.5 h-3.5 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        @if ($ultimoMovimiento)
                            Último registro:
                            {{ \Carbon\Carbon::parse($ultimoMovimiento->fecha ?? $ultimoMovimiento->created_at)->format('d M Y') }}
                        @else
                            Sin registros de movimientos
                        @endif
                    </p>
                </div>
            </div>


        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-3">

            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 md:col-span-1">
                <h3 class="text-base font-bold text-gray-800 mb-4 border-b border-gray-100 pb-2">Especificaciones</h3>

                <div class="space-y-2">
                    <div>
                        <label class="text-xs font-semibold text-gray-400 uppercase">Marca</label>
                        <p class="text-sm font-medium text-gray-800 mt-0.5">{{ $vehiculo->marca }}</p>
                    </div>
                    <div>
                        <label class="text-xs font-semibold text-gray-400 uppercase">Modelo</label>
                        <p class="text-sm font-medium text-gray-800 ">{{ $vehiculo->modelo }}</p>
                    </div>

                </div>
            </div>

            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 md:col-span-2">
                <div class="flex justify-between items-center mb-4 border-b border-gray-100 pb-2">
                    <h3 class="text-base font-bold text-gray-800">Historial de Kilometrajes (Movimientos)</h3>
                    <span class="text-xs bg-indigo-50 text-indigo-700 font-semibold px-2 py-1 rounded">Secuencia
                        temporal</span>
                </div>

                @if ($vehiculo->movimientos->isEmpty())
                    <div class="py-8 text-center text-gray-400 text-sm">
                        <svg class="w-8 h-8 mx-auto mb-2 text-gray-300" fill="none" stroke="currentColor"
                            viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        Este vehículo aún no cuenta con movimientos registrados.
                    </div>
                @else
                    <div class="flow-root">
                        <ul class="-mb-8">
                            @foreach ($vehiculo->movimientos->take(5) as $movimiento)
                                <li>
                                    <div class="relative pb-8">
                                        @if (!$loop->last)
                                            <span class="absolute top-4 left-4 -ml-px h-full w-0.5 bg-gray-200"
                                                aria-hidden="true"></span>
                                        @endif

                                        <div class="relative flex space-x-3">
                                            <div>
                                                <span
                                                    class="h-8 w-8 rounded-full bg-slate-100 border border-slate-300 flex items-center justify-center ring-8 ring-white text-slate-600">
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor"
                                                        viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6" />
                                                    </svg>
                                                </span>
                                            </div>
                                            <div
                                                class="flex-1 min-w-0 flex justify-between items-center space-x-4 pt-1.5">
                                                <div>
                                                    <p class="text-sm font-bold text-gray-800 font-mono">
                                                        {{ number_format($movimiento->kilometraje, 0, ',', '.') }} km
                                                    </p>
                                                    <p class="text-xs text-gray-500 mt-0.5">
                                                        {{ $movimiento->descripcion ?? 'Registro de movimiento periódico' }}
                                                    </p>
                                                </div>
                                                <div
                                                    class="text-right text-xs whitespace-nowrap text-gray-400 font-medium">
                                                    <time
                                                        datetime="{{ $movimiento->fecha ?? $movimiento->created_at }}">
                                                        {{ \Carbon\Carbon::parse($movimiento->fecha ?? $movimiento->created_at)->format('d M, Y - h:i A') }}
                                                    </time>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </li>
                            @endforeach
                        </ul>
                    </div>
                @endif
            </div>

        </div>
    </div>
    <livewire:admin.mantenimientos-index :vehiculo="$vehiculo"/>
</x-admin-layout>

<div>

    {{-- ENCABEZADO --}}
    <div class="flex flex-col md:flex-row justify-between items-center mb-6">

        <div>
            <h1 class="text-3xl font-bold text-gray-800">
                Historial de Mantenimientos
            </h1>

            <p class="text-gray-500">
                Administración de mantenimientos registrados.
            </p>
        </div>

    </div>


    {{-- TARJETAS --}}
    <div class="grid grid-cols-1 md:grid-cols-3 gap-5 mb-6">

        <div class="bg-white rounded-xl shadow p-5">

            <p class="text-gray-500">
                Total mantenimientos
            </p>

            <h2 class="text-3xl font-bold text-blue-600">
                {{ $total }}
            </h2>

        </div>

        <div class="bg-white rounded-xl shadow p-5">

            <p class="text-gray-500">
                Este mes
            </p>

            <h2 class="text-3xl font-bold text-green-600">
                {{ $esteMes }}
            </h2>

        </div>

        <div class="bg-white rounded-xl shadow p-5">

            <p class="text-gray-500">
                Vehículos registrados
            </p>

            <h2 class="text-3xl font-bold text-orange-600">
                {{ $totalVehiculos }}
            </h2>

        </div>

    </div>


    {{-- FILTROS --}}
    <div class="bg-white rounded-xl shadow p-5 mb-6">

        <div class="grid grid-cols-1 md:grid-cols-5 gap-4">

            {{-- Buscar --}}
            <div>

                <label class="text-sm font-semibold">
                    Buscar
                </label>

                <input type="text" wire:model.live.debounce.300ms="search" placeholder="Placa, marca o modelo..."
                    class="w-full rounded-lg border-gray-300">

            </div>

            {{-- Vehículo --}}
            <div>

                <label class="text-sm font-semibold">
                    Vehículo
                </label>

                <select wire:model.live="vehiculo" class="w-full rounded-lg border-gray-300">

                    <option value="">Todos</option>

                    @foreach ($vehiculos as $veh)
                        <option value="{{ $veh->id }}">

                            {{ $veh->placa }}
                            -
                            {{ $veh->marca }}
                            {{ $veh->modelo }}

                        </option>
                    @endforeach

                </select>

            </div>

            {{-- Fecha --}}
            <div>

                <label class="text-sm font-semibold">
                    Fecha
                </label>

                <input type="date" wire:model.live="fecha" class="w-full rounded-lg border-gray-300">

            </div>

            {{-- Desde --}}
            <div>

                <label class="text-sm font-semibold">
                    Desde
                </label>

                <input type="date" wire:model.live="desde" class="w-full rounded-lg border-gray-300">

            </div>

            {{-- Hasta --}}
            <div>

                <label class="text-sm font-semibold">
                    Hasta
                </label>

                <input type="date" wire:model.live="hasta" class="w-full rounded-lg border-gray-300">

            </div>

        </div>

        <div class="mt-4">

            <button wire:click="limpiarFiltros" class="bg-gray-700 hover:bg-gray-800 text-white px-4 py-2 rounded">

                <i class="fa-solid fa-filter-circle-xmark mr-2"></i>

                Limpiar filtros

            </button>

        </div>

    </div>


    {{-- LOADING --}}
    <div wire:loading class="mb-4">

        <div class="bg-blue-100 text-blue-700 p-3 rounded">

            Cargando información...

        </div>

    </div>


    {{-- TABLA --}}
    <div class="bg-white rounded-xl shadow overflow-hidden">

        <div class="overflow-x-auto">

            <table class="min-w-full">

                <thead class="bg-gray-100">

                    <tr>

                        <th wire:click="sortBy('fecha')" class="cursor-pointer px-4 py-3">

                            Fecha

                        </th>

                        <th wire:click="sortBy('placa')" class="cursor-pointer px-4 py-3">

                            Vehículo

                        </th>

                        <th wire:click="sortBy('kilometraje')" class="cursor-pointer px-4 py-3">

                            Kilometraje

                        </th>

                        <th class="px-4 py-3">

                            Servicios

                        </th>

                        <th class="px-4 py-3">

                            Observaciones

                        </th>

                        <th class="px-4 py-3 text-center">

                            Acciones

                        </th>

                    </tr>

                </thead>

                <tbody>

                    @forelse($mantenimientos as $mantenimiento)

                        <tr class="border-b hover:bg-gray-50">

                            <td class="px-4 py-4">

                                {{ $mantenimiento->fecha->format('d/m/Y') }}

                            </td>

                            <td class="px-4 py-4">

                                <div class="font-semibold">

                                    {{ $mantenimiento->vehiculo->placa }}

                                </div>

                                <div class="text-sm text-gray-500">

                                    {{ $mantenimiento->vehiculo->marca }}

                                    {{ $mantenimiento->vehiculo->modelo }}

                                </div>

                            </td>

                            <td class="px-4 py-4">

                                {{ number_format($mantenimiento->kilometraje) }} km

                            </td>

                            <td class="px-4 py-4">

                                <div class="flex flex-wrap gap-2">

                                    @foreach ($mantenimiento->detalles as $detalle)
                                        <span class="bg-blue-100 text-blue-700 px-2 py-1 rounded-full text-xs">

                                            {{ $detalle->tipoServicio->nombre }}

                                        </span>
                                    @endforeach

                                </div>

                            </td>

                            <td class="px-4 py-4">

                                {{ $mantenimiento->observaciones }}

                            </td>

                            <td class="px-4 py-4">

                                <div class="flex justify-center">
                                    <button wire:click="eliminar({{ $mantenimiento->id }})"
                                        wire:confirm="¿Está seguro de eliminar este mantenimiento?"
                                        class="flex h-9 w-9 items-center justify-center rounded-lg text-gray-500 transition duration-200 hover:bg-red-50 hover:text-red-600"
                                        title="Eliminar mantenimiento">
                                        <i class="fa-solid fa-trash-can"></i>
                                    </button>
                                </div>

                            </td>

                        </tr>

                    @empty

                        <tr>

                            <td colspan="6" class="text-center py-10 text-gray-500">

                                <i class="fa-solid fa-car text-5xl mb-4"></i>

                                <br>

                                No existen mantenimientos registrados.

                            </td>

                        </tr>

                    @endforelse

                </tbody>

            </table>

        </div>

    </div>


    {{-- PAGINACIÓN --}}
    <div class="mt-5">

        {{ $mantenimientos->links() }}

    </div>

</div>

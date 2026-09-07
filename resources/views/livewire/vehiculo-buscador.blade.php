<div class="space-y-2 relative">

    {{-- INPUT BUSCADOR --}}
    <label for="search_vehiculo" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
        Placa del Vehículo
    </label>
    <input type="text" id="search_vehiculo" wire:model.live.debounce.400ms="search"
        placeholder="🔍 Buscar vehículo por placa..."
        class="w-full border rounded-lg px-4 py-2 uppercase dark:bg-gray-900 dark:border-gray-700 dark:text-gray-100"
        @disabled($vehiculo)>

    {{-- RESULTADOS --}}
    @if ($search && $this->vehiculos->count())
        <ul
            class="absolute bg-white dark:bg-gray-800 border dark:border-gray-700 w-full rounded shadow z-20 max-h-52 overflow-auto">
            @foreach ($this->vehiculos as $v)
                <li wire:click="seleccionarVehiculo({{ $v->id }})"
                    class="px-4 py-2 hover:bg-gray-100 dark:hover:bg-gray-700 cursor-pointer flex justify-between items-center text-sm">
                    <span class="font-bold text-gray-900 dark:text-white uppercase">🚗 {{ $v->placa }}</span>
                    <span class="text-xs text-gray-500 dark:text-gray-400">{{ $v->marca }} {{ $v->modelo }} —
                        {{ $v->color }}</span>
                </li>
            @endforeach
        </ul>
    @endif

    {{-- VEHÍCULO SELECCIONADO --}}
    @if ($vehiculo)
        <div class="flex justify-between items-center bg-gray-100 dark:bg-gray-700 p-2 rounded">
            <strong class="text-sm text-gray-800 dark:text-gray-200">
                ✅ Placa: <span
                    class="uppercase font-bold text-indigo-600 dark:text-indigo-400">{{ $vehiculo->placa }}</span>
                ({{ $vehiculo->marca }} {{ $vehiculo->modelo }})
            </strong>
            <button type="button" wire:click="limpiarVehiculo"
                class="text-sm text-red-600 dark:text-red-400 hover:underline">
                Quitar
            </button>
        </div>
    @endif

    {{-- INPUT OCULTO PARA EL FORM --}}
    <input type="hidden" name="vehiculo_id" value="{{ $vehiculoId }}">
</div>

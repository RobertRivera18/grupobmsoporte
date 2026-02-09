<div class="space-y-2 relative">

    {{-- INPUT BUSCADOR --}}
    <input type="text" wire:model.live.debounce.400ms="search" placeholder="🔍 Buscar empleado..."
        class="w-full border rounded-lg px-4 py-2" @disabled($empleado)>

    {{-- RESULTADOS --}}
    @if ($search && $this->empleados->count())
        <ul class="absolute bg-white border w-full rounded shadow z-20 max-h-52 overflow-auto">
            @foreach ($this->empleados as $user)
                <li wire:click="seleccionarEmpleado({{ $user->id }})"
                    class="px-4 py-2 hover:bg-gray-100 cursor-pointer">
                    {{ $user->name }}
                </li>
            @endforeach
        </ul>
    @endif

    {{-- EMPLEADO SELECCIONADO --}}
    @if ($empleado)
        <div class="flex justify-between items-center bg-gray-100 p-2 rounded">
            <strong>👤 {{ $empleado->name }}</strong>
            <button type="button" wire:click="limpiarEmpleado" class="text-sm text-red-600 hover:underline">
                Quitar
            </button>
        </div>
    @endif

    {{-- INPUT OCULTO PARA EL FORM --}}
    <input type="hidden" name="user_id" value="{{ $empleadoId }}">
</div>

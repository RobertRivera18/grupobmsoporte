<div class="p-4">
    <h2 class="text-xl font-bold mb-4">Editar Reporte: {{ $report->mes ?? '' }}</h2>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
        @foreach ($cuadrillas as $cuadrilla)
            <label class="flex items-center space-x-2 border rounded-lg p-3 shadow-sm cursor-pointer">
                <input type="checkbox" wire:model="seleccionadas" value="{{ $cuadrilla->id }}">
                <span class="font-semibold">{{ $cuadrilla->cua_nombre }}</span>
            </label>
        @endforeach
    </div>

    <div class="mt-6">
        <button wire:click="actualizarReporte"
                class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg shadow">
            Actualizar Reporte
        </button>
    </div>
</div>

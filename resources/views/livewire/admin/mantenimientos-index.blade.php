<div>

    <div class="bg-white rounded-xl shadow-sm border border-gray-200">

        {{-- Encabezado --}}
        <div class="flex justify-between items-center p-5 border-b">

            <div>
                <h2 class="text-lg font-bold text-gray-800">
                    Historial de Mantenimientos
                </h2>

                <p class="text-sm text-gray-500">
                    Registre y consulte los mantenimientos realizados al vehículo.
                </p>
            </div>

            <x-button wire:click="nuevo">
                <i class="fas fa-plus mr-2"></i>
                Registrar
            </x-button>

        </div>

        @if(session()->has('success'))

            <div class="mx-5 mt-5 p-3 rounded-lg bg-green-100 text-green-700">
                {{ session('success') }}
            </div>

        @endif

        {{-- Tabla --}}
        <div class="overflow-x-auto">

            <table class="min-w-full divide-y divide-gray-200">

                <thead class="bg-gray-50">

                    <tr>

                        <th class="px-4 py-3 text-left text-xs font-semibold uppercase">
                            Fecha
                        </th>

                        <th class="px-4 py-3 text-left text-xs font-semibold uppercase">
                            Kilometraje
                        </th>

                        <th class="px-4 py-3 text-left text-xs font-semibold uppercase">
                            Servicios
                        </th>

                        <th class="px-4 py-3 text-left text-xs font-semibold uppercase">
                            Observaciones
                        </th>

                    </tr>

                </thead>

                <tbody class="bg-white divide-y divide-gray-100">

                    @forelse($mantenimientos as $mantenimiento)

                        <tr>

                            <td class="px-4 py-4 text-sm">

                                {{ $mantenimiento->fecha->format('d/m/Y') }}

                            </td>

                            <td class="px-4 py-4">

                                <span class="font-mono font-semibold">

                                    {{ number_format($mantenimiento->kilometraje,0,',','.') }} km

                                </span>

                            </td>

                            <td class="px-4 py-4">

                                <div class="flex flex-wrap gap-2">

                                    @foreach($mantenimiento->detalles as $detalle)

                                        <span class="px-2 py-1 bg-indigo-100 text-indigo-700 rounded-full text-xs">

                                            {{ $detalle->tipoServicio->nombre }}

                                        </span>

                                    @endforeach

                                </div>

                            </td>

                            <td class="px-4 py-4 text-sm text-gray-600">

                                {{ $mantenimiento->observaciones }}

                            </td>

                        </tr>

                    @empty

                        <tr>

                            <td colspan="4" class="text-center py-10 text-gray-400">

                                No existen mantenimientos registrados.

                            </td>

                        </tr>

                    @endforelse

                </tbody>

            </table>

        </div>

    </div>



    {{-- Modal --}}

    <x-dialog-modal wire:model="open">

        <x-slot name="title">

            Registrar mantenimiento

        </x-slot>

        <x-slot name="content">

            <div class="grid grid-cols-1 md:grid-cols-2 gap-5">

                {{-- Fecha --}}
                <div>

                    <x-label value="Fecha" />

                    <x-input
                        type="date"
                        class="w-full mt-1"
                        wire:model.defer="fecha"/>

                    @error('fecha')

                        <span class="text-red-500 text-xs">{{ $message }}</span>

                    @enderror

                </div>

                {{-- Kilometraje --}}
                <div>

                    <x-label value="Kilometraje" />

                    <x-input
                        type="number"
                        class="w-full mt-1"
                        wire:model.defer="kilometraje"/>

                    @error('kilometraje')

                        <span class="text-red-500 text-xs">{{ $message }}</span>

                    @enderror

                </div>

                {{-- Observaciones --}}
                <div class="md:col-span-2">

                    <x-label value="Observaciones" />

                    <textarea
                        rows="3"
                        wire:model.defer="observaciones"
                        class="w-full mt-1 rounded-md border-gray-300"></textarea>

                </div>

                {{-- Servicios --}}
                <div class="md:col-span-2">

                    <x-label value="Servicios realizados" />

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-3 mt-3">

                        @foreach($tiposServicios as $servicio)

                            <div class="border rounded-lg p-3">

                                <label class="flex items-center">

                                    <input
                                        type="checkbox"
                                        value="{{ $servicio->id }}"
                                        wire:model="serviciosSeleccionados"
                                        class="rounded border-gray-300 text-indigo-600">

                                    <span class="ml-2 font-medium">

                                        {{ $servicio->nombre }}

                                    </span>

                                </label>

                                @if(in_array($servicio->id,$serviciosSeleccionados))

                                    <textarea

                                        wire:model.defer="descripcionServicios.{{ $servicio->id }}"

                                        rows="2"

                                        class="w-full mt-3 rounded-md border-gray-300"

                                        placeholder="Detalle del servicio...">

                                    </textarea>

                                @endif

                            </div>

                        @endforeach

                    </div>

                    @error('serviciosSeleccionados')

                        <span class="text-red-500 text-xs">

                            {{ $message }}

                        </span>

                    @enderror

                </div>

            </div>

        </x-slot>

        <x-slot name="footer">

            <x-secondary-button wire:click="$set('open',false)">

                Cancelar

            </x-secondary-button>

            <x-button
                class="ml-3"
                wire:click="guardar">

                <i class="fas fa-save mr-2"></i>

                Guardar

            </x-button>

        </x-slot>

    </x-dialog-modal>

</div>
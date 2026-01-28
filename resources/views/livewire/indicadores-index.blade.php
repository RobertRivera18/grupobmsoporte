<div x-data="{ showForm: false }"
     x-on:close-form.window="showForm = false"
     class="space-y-6">

    {{-- ============================= --}}
    {{-- LISTA DE INDICADORES --}}
    {{-- ============================= --}}
    <div class="bg-white shadow-sm rounded-xl border border-gray-200 overflow-hidden">

        {{-- Header --}}
        <div class="bg-gradient-to-r from-indigo-50 to-white border-b border-gray-200 px-4 sm:px-6 py-4">
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">

                <div class="flex items-center gap-3">
                    <div class="bg-indigo-100 p-2.5 rounded-lg">
                        <i class="fa-solid fa-chart-column text-indigo-600 text-lg"></i>
                    </div>
                    <div>
                        <h2 class="text-lg sm:text-xl font-bold text-gray-900">
                            Indicadores del Área
                        </h2>
                        <p class="text-sm text-gray-500">
                            Gestiona los indicadores de medición
                        </p>
                    </div>
                </div>

                {{-- Botón crear --}}
                <x-button
                    class="w-full sm:w-auto px-5 py-2.5"
                    x-show="!showForm"
                    @click="showForm = true">
                    <i class="fas fa-plus mr-2"></i>
                    Nuevo Indicador
                </x-button>

            </div>
        </div>

        {{-- Lista --}}
        <div class="p-4 sm:p-6">
            @if ($indicadores && count($indicadores) > 0)

                <div class="space-y-3">
                    @foreach ($indicadores as $indicador)
                        <div
                            wire:key="indicador-{{ $indicador->id }}"
                            class="bg-gray-50 hover:bg-indigo-50 border border-gray-200 rounded-lg transition">

                            <div class="flex flex-col sm:flex-row sm:items-center">

                                {{-- Icono --}}
                                <div class="px-4 py-3 text-indigo-600">
                                    <i class="fa-solid fa-chart-line"></i>
                                </div>

                                {{-- Nombre --}}
                                <div class="flex-1 px-4 pb-2 sm:pb-0">
                                    <p class="font-medium text-gray-900">
                                        {{ $indicador->nombre }}
                                    </p>
                                </div>

                                {{-- Acciones --}}
                                <div
                                    class="flex w-full sm:w-auto border-t sm:border-t-0 sm:border-l border-gray-200 divide-x">

                                    <a href="{{ route('admin.areas.indicadores.show', [$area->id, $indicador->id]) }}"
                                       class="flex-1 sm:flex-none px-4 py-3 text-center text-gray-600 hover:bg-indigo-100"
                                       title="Ver">
                                        <i class="fas fa-eye"></i>
                                    </a>

                                    <button
                                        @click="showForm = true"
                                        wire:click="editIndicador({{ $indicador->id }})"
                                        class="flex-1 sm:flex-none px-4 py-3 text-center text-gray-600 hover:bg-amber-100"
                                        title="Editar">
                                        <i class="fas fa-edit"></i>
                                    </button>

                                    <button
                                        wire:click="destroyIndicador({{ $indicador->id }})"
                                        onclick="return confirm('¿Eliminar este indicador?')"
                                        class="flex-1 sm:flex-none px-4 py-3 text-center text-red-600 hover:bg-red-100"
                                        title="Eliminar">
                                        <i class="far fa-trash-alt"></i>
                                    </button>

                                </div>

                            </div>
                        </div>
                    @endforeach
                </div>

            @else
                {{-- Estado vacío --}}
                <div class="text-center py-12">
                    <div class="flex justify-center mb-4">
                        <i class="fa-solid fa-chart-column text-4xl text-gray-300"></i>
                    </div>
                    <h3 class="text-lg font-semibold text-gray-900">
                        No hay indicadores
                    </h3>
                    <p class="text-sm text-gray-500 mb-4">
                        Aún no existen indicadores registrados para esta área.
                    </p>
                    <button
                        @click="showForm = true"
                        class="inline-flex items-center px-4 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 transition">
                        <i class="fas fa-plus mr-2"></i>
                        Crear primer indicador
                    </button>
                </div>
            @endif
        </div>
    </div>

    {{-- ============================= --}}
    {{-- FORMULARIO CREAR / EDITAR --}}
    {{-- ============================= --}}
    <div x-show="showForm"
         x-transition
         class="bg-white rounded-xl shadow-lg border border-gray-200 overflow-hidden">

        {{-- Header --}}
        <div class="px-4 sm:px-6 py-4 border-b border-gray-200">
            <div class="flex justify-between items-start gap-4">
                <div>
                    <h3 class="text-lg font-bold text-gray-900">
                        {{ $editingIndicadorId ? 'Editar Indicador' : 'Nuevo Indicador' }}
                    </h3>
                    <p class="text-sm text-gray-500">
                        Completa la información del indicador
                    </p>
                </div>

                <button
                    type="button"
                    @click="showForm = false; $wire.call('resetForm')"
                    class="text-gray-400 hover:text-gray-600 hover:bg-gray-100 p-2 rounded-lg">
                    <i class="fas fa-times"></i>
                </button>
            </div>
        </div>

        {{-- Formulario --}}
        <form
            wire:submit="{{ $editingIndicadorId ? 'update' : 'store' }}"
            class="p-4 sm:p-6 space-y-5">

            {{-- Nombre --}}
            <div>
                <x-label for="nombre" value="Nombre del Indicador" />
                <x-input
                    id="nombre"
                    wire:model="nombre"
                    class="w-full"
                    placeholder="Ej: Tiempo de respuesta promedio" />
                <x-input-error for="nombre" />
            </div>

            {{-- Forma de cálculo --}}
            <div>
                <x-label value="Forma de Cálculo" />
                <textarea
                    wire:model="forma_calculo"
                    rows="4"
                    class="w-full border-gray-300 rounded-lg focus:ring-indigo-500 focus:border-indigo-500"
                    placeholder="Describe la metodología del indicador..."></textarea>
                <x-input-error for="forma_calculo" />
            </div>

            {{-- Frecuencia --}}
            <div>
                <x-label value="Frecuencia de Medición" />
                <select
                    wire:model="frecuencia_id"
                    class="w-full border-gray-300 rounded-lg focus:ring-indigo-500 focus:border-indigo-500">
                    <option value="">Seleccione una frecuencia</option>
                    @foreach ($frecuencias as $freq)
                        <option value="{{ $freq->id }}">{{ $freq->nombre }}</option>
                    @endforeach
                </select>
                <x-input-error for="frecuencia_id" />
            </div>

            {{-- Acciones --}}
            <div class="flex flex-col sm:flex-row gap-3 pt-4 border-t border-gray-200">
                <button
                    type="button"
                    @click="showForm = false; $wire.call('resetForm')"
                    class="w-full sm:w-auto px-4 py-2 bg-gray-100 hover:bg-gray-200 rounded-lg transition">
                    <i class="fas fa-times mr-2"></i>
                    Cancelar
                </button>

                <x-button class="w-full sm:w-auto px-6 py-2">
                    <i class="fas fa-save mr-2"></i>
                    {{ $editingIndicadorId ? 'Actualizar' : 'Crear Indicador' }}
                </x-button>
            </div>

        </form>
    </div>

</div>


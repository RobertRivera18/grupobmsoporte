<div x-data="{ showForm: false }"
     x-on:close-form.window="showForm = false"
     class="space-y-6 max-w-7xl mx-auto">

    {{-- ============================= --}}
    {{-- LISTA DE INDICADORES --}}
    {{-- ============================= --}}
    <div class="bg-white shadow-xl rounded-2xl border border-gray-100 overflow-hidden transition-all duration-300">

        {{-- Header --}}
        <div class="bg-gradient-to-r from-indigo-50/80 via-white to-white border-b border-gray-100 px-6 py-5">
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                <div class="flex items-center gap-4">
                    <div class="bg-indigo-600 text-white p-3 rounded-xl shadow-md shadow-indigo-200">
                        <i class="fa-solid fa-chart-column text-xl"></i>
                    </div>
                    <div>
                        <div class="flex items-center gap-2">
                            <h2 class="text-xl font-extrabold text-gray-900 tracking-tight">
                                Indicadores del Área
                            </h2>
                            @if($indicadores && count($indicadores) > 0)
                                <span class="bg-indigo-100 text-indigo-700 text-xs font-semibold px-2.5 py-0.5 rounded-full">
                                    {{ count($indicadores) }}
                                </span>
                            @endif
                        </div>
                        <p class="text-sm text-gray-500 mt-0.5">
                            Gestiona y monitorea los indicadores de medición clave
                        </p>
                    </div>
                </div>

                {{-- Botón crear --}}
                <button
                    type="button"
                    x-show="!showForm"
                    @click="showForm = true"
                    class="inline-flex items-center justify-center px-5 py-2.5 bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-medium rounded-xl shadow-md shadow-indigo-100 hover:shadow-lg transition-all transform active:scale-95">
                    <i class="fas fa-plus mr-2 text-xs"></i>
                    Nuevo Indicador
                </button>
            </div>
        </div>

        {{-- Lista --}}
        <div class="p-6">
            @if ($indicadores && count($indicadores) > 0)
                <div class="grid grid-cols-1 gap-4">
                    @foreach ($indicadores as $indicador)
                        <div
                            wire:key="indicador-{{ $indicador->id }}"
                            class="group bg-white hover:bg-indigo-50/30 border border-gray-200/80 hover:border-indigo-200 rounded-xl p-4 sm:p-5 transition-all duration-200 shadow-sm hover:shadow-md flex flex-col sm:flex-row sm:items-center justify-between gap-4">

                            <div class="flex items-start sm:items-center gap-4">
                                <div class="bg-indigo-50 text-indigo-600 p-3 rounded-xl group-hover:bg-indigo-100 transition-colors shrink-0">
                                    <i class="fa-solid fa-chart-line text-lg"></i>
                                </div>
                                <div>
                                    <h3 class="font-semibold text-gray-900 text-base sm:text-lg group-hover:text-indigo-900 transition-colors">
                                        {{ $indicador->nombre }}
                                    </h3>
                                    @if(isset($indicador->frecuencia))
                                        <span class="inline-flex items-center text-xs text-gray-500 mt-1 gap-1">
                                            <i class="far fa-clock"></i> Frecuencia: {{ $indicador->frecuencia->nombre ?? 'N/A' }}
                                        </span>
                                    @endif
                                </div>
                            </div>

                            {{-- Acciones --}}
                            <div class="flex items-center justify-end gap-1.5 pt-3 sm:pt-0 border-t sm:border-t-0 border-gray-100">
                                <a href="{{ route('admin.areas.indicadores.show', [$area->id, $indicador->id]) }}"
                                   class="inline-flex items-center justify-center px-3 py-2 text-gray-600 hover:text-indigo-600 hover:bg-indigo-100/60 rounded-xl transition-all text-sm font-medium"
                                   title="Ver detalles">
                                    <i class="fas fa-eye sm:mr-1.5"></i>
                                    <span class="hidden sm:inline">Ver</span>
                                </a>

                                <button
                                    @click="showForm = true"
                                    wire:click="editIndicador({{ $indicador->id }})"
                                    class="inline-flex items-center justify-center px-3 py-2 text-gray-600 hover:text-amber-600 hover:bg-amber-100/60 rounded-xl transition-all text-sm font-medium"
                                    title="Editar">
                                    <i class="fas fa-edit sm:mr-1.5"></i>
                                    <span class="hidden sm:inline">Editar</span>
                                </button>

                                <button
                                    wire:click="destroyIndicador({{ $indicador->id }})"
                                    onclick="return confirm('¿Estás seguro de eliminar este indicador?')"
                                    class="inline-flex items-center justify-center px-3 py-2 text-gray-600 hover:text-red-600 hover:bg-red-100/60 rounded-xl transition-all text-sm font-medium"
                                    title="Eliminar">
                                    <i class="far fa-trash-alt sm:mr-1.5"></i>
                                    <span class="hidden sm:inline">Eliminar</span>
                                </button>
                            </div>

                        </div>
                    @endforeach
                </div>
            @else
                {{-- Estado vacío --}}
                <div class="text-center py-16 px-4">
                    <div class="inline-flex items-center justify-center w-20 h-20 bg-indigo-50 text-indigo-400 rounded-full mb-4 shadow-inner">
                        <i class="fa-solid fa-chart-column text-3xl"></i>
                    </div>
                    <h3 class="text-lg font-bold text-gray-900 mb-1">
                        No hay indicadores registrados
                    </h3>
                    <p class="text-sm text-gray-500 max-w-sm mx-auto mb-6">
                        Comienza creando el primer indicador de medición para estructurar el seguimiento de esta área.
                    </p>
                    <button
                        @click="showForm = true"
                        class="inline-flex items-center px-5 py-2.5 bg-indigo-600 text-white font-medium text-sm rounded-xl hover:bg-indigo-700 shadow-lg shadow-indigo-100 transition-all transform active:scale-95">
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
         x-transition:enter="transition ease-out duration-300"
         x-transition:enter-start="opacity-0 transform translate-y-4"
         x-transition:enter-end="opacity-100 transform translate-y-0"
         x-transition:leave="transition ease-in duration-200"
         x-transition:leave-start="opacity-100 transform translate-y-0"
         x-transition:leave-end="opacity-0 transform translate-y-4"
         class="bg-white rounded-2xl shadow-xl border border-gray-100 overflow-hidden"
         style="display: none;">

        {{-- Header --}}
        <div class="px-6 py-5 border-b border-gray-100 bg-gray-50/50 flex justify-between items-center">
            <div class="flex items-center gap-3">
                <div class="bg-indigo-100 text-indigo-600 p-2.5 rounded-xl">
                    <i class="fas fa-sliders-h"></i>
                </div>
                <div>
                    <h3 class="text-lg font-bold text-gray-900">
                        {{ $editingIndicadorId ? 'Editar Indicador' : 'Nuevo Indicador' }}
                    </h3>
                    <p class="text-xs text-gray-500">
                        Completa los campos requeridos para la gestión del indicador
                    </p>
                </div>
            </div>

            <button
                type="button"
                @click="showForm = false; $wire.call('resetForm')"
                class="text-gray-400 hover:text-gray-600 hover:bg-gray-200/60 p-2 rounded-xl transition">
                <i class="fas fa-times text-lg"></i>
            </button>
        </div>

        {{-- Formulario --}}
        <form
            wire:submit="{{ $editingIndicadorId ? 'update' : 'store' }}"
            class="p-6 space-y-6">

            <div class="space-y-4">
                {{-- Nombre --}}
                <div>
                    <x-label for="nombre" value="Nombre del Indicador" class="font-semibold text-gray-700 mb-1" />
                    <x-input
                        id="nombre"
                        wire:model="nombre"
                        class="w-full rounded-xl border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 shadow-sm"
                        placeholder="Ej: Tiempo de respuesta promedio" />
                    <x-input-error for="nombre" class="mt-1" />
                </div>

                {{-- Forma de cálculo --}}
                <div>
                    <x-label value="Forma de Cálculo" class="font-semibold text-gray-700 mb-1" />
                    <textarea
                        wire:model="forma_calculo"
                        rows="4"
                        class="w-full border-gray-300 rounded-xl focus:ring-indigo-500 focus:border-indigo-500 shadow-sm text-sm"
                        placeholder="Describe la metodología o fórmula de cálculo del indicador..."></textarea>
                    <x-input-error for="forma_calculo" class="mt-1" />
                </div>

                {{-- Frecuencia --}}
                <div>
                    <x-label value="Frecuencia de Medición" class="font-semibold text-gray-700 mb-1" />
                    <select
                        wire:model="frecuencia_id"
                        class="w-full border-gray-300 rounded-xl border-gray-300 focus:ring-indigo-500 focus:border-indigo-500 shadow-sm text-sm bg-white">
                        <option value="">Seleccione una frecuencia</option>
                        @foreach ($frecuencias as $freq)
                            <option value="{{ $freq->id }}">{{ $freq->nombre }}</option>
                        @endforeach
                    </select>
                    <x-input-error for="frecuencia_id" class="mt-1" />
                </div>
            </div>

            {{-- Acciones --}}
            <div class="flex flex-col-reverse sm:flex-row sm:justify-end gap-3 pt-5 border-t border-gray-100">
                <button
                    type="button"
                    @click="showForm = false; $wire.call('resetForm')"
                    class="w-full sm:w-auto px-5 py-2.5 bg-gray-100 hover:bg-gray-200 text-gray-700 font-medium rounded-xl transition text-center">
                    <i class="fas fa-times mr-2"></i>
                    Cancelar
                </button>

                <button
                    type="submit"
                    class="w-full sm:w-auto inline-flex items-center justify-center px-6 py-2.5 bg-indigo-600 hover:bg-indigo-700 text-white font-medium rounded-xl shadow-md shadow-indigo-100 transition">
                    <i class="fas fa-save mr-2"></i>
                    {{ $editingIndicadorId ? 'Actualizar Indicador' : 'Guardar Indicador' }}
                </button>
            </div>

        </form>
    </div>
</div>
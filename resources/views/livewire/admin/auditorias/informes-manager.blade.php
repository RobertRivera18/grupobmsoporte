<div class="max-w-5xl mx-auto">

    {{-- CARD PRINCIPAL --}}
    <div class="mb-10 bg-white rounded-2xl shadow-lg border border-gray-200 overflow-hidden">

        {{-- HEADER --}}
        <div class="bg-gradient-to-r from-blue-600 to-indigo-600 px-6 py-5 text-white">
            <h3 class="text-lg font-semibold tracking-wide">
                {{ $modoEdicion ? '✏️ Editar Informe de Auditoría' : '📝 Nuevo Informe de Auditoría' }}
            </h3>
            <p class="text-blue-100 text-sm mt-1">
                Complete la información general y registre las no conformidades detectadas.
            </p>
        </div>

        {{-- BODY --}}
        <div class="p-8 space-y-8 bg-gray-50">

            {{-- RESUMEN --}}
            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-1">
                    Resumen
                </label>
                <input type="text"
                    wire:model="resumen"
                    class="w-full rounded-xl border-gray-300 focus:border-blue-500 focus:ring focus:ring-blue-200 transition text-sm px-4 py-2 shadow-sm">

                @error('resumen')
                    <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span>
                @enderror
            </div>

            {{-- DESCRIPCIÓN --}}
            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-1">
                    Descripción General
                </label>
                <textarea wire:model="descripcion" rows="4"
                    class="w-full rounded-xl border-gray-300 focus:border-indigo-500 focus:ring focus:ring-indigo-200 transition text-sm px-4 py-2 shadow-sm"></textarea>

                @error('descripcion')
                    <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span>
                @enderror
            </div>

            {{-- NO CONFORMIDADES --}}
            <div class="bg-white rounded-xl border border-gray-200 shadow-sm p-6">

                <div class="flex items-center justify-between mb-4">
                    <h4 class="font-semibold text-gray-800 text-sm tracking-wide">
                        🚨 No Conformidades
                    </h4>

                    <button type="button"
                        wire:click="agregarNoConformidad"
                        class="bg-blue-600 text-white px-4 py-2 rounded-xl text-xs font-medium shadow hover:bg-blue-700 hover:scale-105 transition">
                        + Agregar
                    </button>
                </div>

                @foreach ($noConformidades as $index => $nc)
                    <div wire:key="nc-{{ $index }}"
                        x-data="{ open: false }"
                        class="mt-4 rounded-xl border border-gray-200 shadow-sm overflow-hidden">

                        {{-- HEADER --}}
                        <div @click="open = !open"
                            class="cursor-pointer flex items-center justify-between px-5 py-4 bg-gradient-to-r from-gray-100 to-gray-50 hover:from-blue-50 hover:to-indigo-50 transition">

                            <div class="flex items-center gap-3">

                                {{-- BADGE TIPO --}}
                                <span
                                    class="
                                    px-2 py-1 rounded-full text-xs font-semibold
                                    @if(($nc['tipo'] ?? '') == 'NC') bg-red-100 text-red-600
                                    @elseif(($nc['tipo'] ?? '') == 'O') bg-yellow-100 text-yellow-700
                                    @elseif(($nc['tipo'] ?? '') == 'OM') bg-green-100 text-green-600
                                    @else bg-gray-200 text-gray-600
                                    @endif
                                ">
                                    {{ $nc['tipo'] ?? 'NC' }}
                                </span>

                                <span class="text-sm font-semibold text-gray-800">
                                    Hallazgo #{{ $index + 1 }}
                                </span>

                                @if (!empty($nc['descripcion']))
                                    <span class="text-xs text-gray-400">
                                        - {{ \Illuminate\Support\Str::limit($nc['descripcion'], 40) }}
                                    </span>
                                @endif
                            </div>

                            <div class="flex items-center gap-4">

                                {{-- ICON --}}
                                <svg x-show="!open" class="w-5 h-5 text-gray-500 transition"
                                    fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        stroke-width="2" d="M19 9l-7 7-7-7" />
                                </svg>

                                <svg x-show="open" class="w-5 h-5 text-gray-500 transition"
                                    fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        stroke-width="2" d="M5 15l7-7 7 7" />
                                </svg>

                                {{-- DELETE --}}
                                <button type="button"
                                    wire:click.stop="eliminarNoConformidad({{ $index }})"
                                    class="text-red-500 hover:text-red-700 text-sm transition">
                                    ✕
                                </button>

                            </div>
                        </div>

                        {{-- BODY --}}
                        <div x-show="open" x-collapse
                            class="bg-white px-6 py-6 border-t border-gray-200 space-y-5">

                            <div class="grid md:grid-cols-2 gap-5">

                                {{-- REQUISITO ISO --}}
                                <div>
                                    <label class="block text-xs font-semibold text-gray-600 mb-1">
                                        Requisito ISO
                                    </label>
                                    <select
                                        wire:model="noConformidades.{{ $index }}.norma_iso_id"
                                        class="w-full rounded-xl border-gray-300 focus:border-blue-500 focus:ring focus:ring-blue-200 text-sm px-3 py-2 shadow-sm">
                                        <option value="">Seleccione requisito</option>
                                        @foreach ($requisitos as $req)
                                            <option value="{{ $req->id }}">
                                                {{ $req->codigo }} - {{ $req->titulo }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>

                                {{-- TIPO --}}
                                <div>
                                    <label class="block text-xs font-semibold text-gray-600 mb-1">
                                        Tipo
                                    </label>
                                    <select
                                        wire:model="noConformidades.{{ $index }}.tipo"
                                        class="w-full rounded-xl border-gray-300 focus:border-indigo-500 focus:ring focus:ring-indigo-200 text-sm px-3 py-2 shadow-sm">
                                        <option value="NC">No Conformidad</option>
                                        <option value="O">Observación</option>
                                        <option value="OM">Oportunidad Mejora</option>
                                    </select>
                                </div>

                            </div>

                            {{-- DESCRIPCIÓN --}}
                            <div>
                                <label class="block text-xs font-semibold text-gray-600 mb-1">
                                    Descripción
                                </label>
                                <textarea
                                    wire:model="noConformidades.{{ $index }}.descripcion"
                                    rows="3"
                                    class="w-full rounded-xl border-gray-300 focus:border-blue-500 focus:ring focus:ring-blue-200 text-sm px-3 py-2 shadow-sm"></textarea>
                            </div>

                            {{-- EVIDENCIA --}}
                            <div>
                                <label class="block text-xs font-semibold text-gray-600 mb-1">
                                    Evidencia
                                </label>
                                <textarea
                                    wire:model="noConformidades.{{ $index }}.evidencia"
                                    rows="3"
                                    class="w-full rounded-xl border-gray-300 focus:border-indigo-500 focus:ring focus:ring-indigo-200 text-sm px-3 py-2 shadow-sm"></textarea>
                            </div>

                        </div>
                    </div>
                @endforeach
            </div>

            {{-- BOTÓN GUARDAR --}}
            <div class="flex justify-end pt-6 border-t">
                <button wire:click="guardar"
                    class="bg-gradient-to-r from-blue-600 to-indigo-600 text-white px-6 py-3 rounded-xl text-sm font-semibold shadow-md hover:shadow-lg hover:scale-105 transition">
                    {{ $modoEdicion ? 'Actualizar Informe' : 'Guardar Informe' }}
                </button>
            </div>

        </div>
    </div>

</div>

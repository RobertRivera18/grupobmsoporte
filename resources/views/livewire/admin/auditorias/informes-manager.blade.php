@push('css')
    <link href="https://cdn.jsdelivr.net/npm/quill@2.0.3/dist/quill.snow.css" rel="stylesheet">
@endpush
<div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8">
    {{-- CARD PRINCIPAL --}}
    <div class="mb-16 bg-white rounded-2xl shadow-lg border border-gray-200 overflow-hidden">
        {{-- HEADER SUPERIOR --}}
        <div class="bg-gradient-to-r from-slate-800 to-slate-700 px-6 sm:px-8 py-6 text-white">

            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">

                <div>
                    <h2 class="text-lg sm:text-xl font-semibold tracking-wide">
                        {{ $modoEdicion ? 'Editar Informe de Auditoría' : 'Nuevo Informe de Auditoría' }}
                    </h2>
                    <p class="text-slate-300 text-sm mt-1">
                        Registre el resumen general y los hallazgos detectados.
                    </p>
                </div>

                {{-- Indicador de estado --}}
                @if (!empty($resumen) && !empty($descripcion))
                    <div class="flex items-center gap-3 bg-emerald-500/20 border border-emerald-400/40 px-4 py-2 rounded-xl cursor-pointer"
                        wire:click="descargarInforme()">
                        <i class="fa-solid fa-file-word text-emerald-300 text-lg"></i>
                        <span class="text-sm font-medium text-emerald-200">
                            Informe Completo
                        </span>
                    </div>
                @endif

            </div>
        </div>

        {{-- BODY --}}
        <div class="p-5 sm:p-8 space-y-6 sm:space-y-8 bg-gray-50">

            {{-- RESUMEN --}}
            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-1">
                    Resumen
                </label>
                <input type="text" wire:model="resumen"
                    class="w-full rounded-xl border-gray-300 focus:border-blue-500 focus:ring focus:ring-blue-200 transition text-sm px-4 py-2 shadow-sm">

                @error('resumen')
                    <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span>
                @enderror
            </div>
            {{-- DESCRIPCIÓN CON QUILL --}}
            <div class="space-y-1" wire:ignore>
                <label class="block text-sm font-semibold text-gray-700 mb-1">
                    Descripción General
                </label>

                <div id="editor" class="bg-white border border-gray-300 rounded-xl h-56 shadow-inner p-2">
                </div>
            </div>

            <input type="hidden" id="descripcion_hidden" wire:model="descripcion">

            @error('descripcion')
                <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span>
            @enderror



            @error('descripcion')
                <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span>
            @enderror
        </div>

        {{-- NO CONFORMIDADES --}}
        <div class="bg-white rounded-xl border border-gray-200 shadow-sm p-4 sm:p-6">

            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-4">
                <h4 class="font-semibold text-gray-800 text-sm tracking-wide">
                    🚨 No Conformidades
                </h4>

                <button type="button" wire:click="agregarNoConformidad"
                    class="bg-blue-600 text-white px-4 py-2 rounded-xl text-xs font-medium shadow hover:bg-blue-700 active:scale-95 transition w-full sm:w-auto">
                    + Agregar
                </button>
            </div>

            @foreach ($noConformidades as $index => $nc)
                <div wire:key="nc-{{ $index }}" x-data="{ open: false }"
                    class="mt-4 rounded-xl border border-gray-200 shadow-sm overflow-hidden bg-white">

                    {{-- HEADER --}}
                    <div @click="open = !open"
                        class="cursor-pointer flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3 px-4 sm:px-5 py-4 bg-gradient-to-r from-gray-100 to-gray-50 hover:from-blue-50 hover:to-indigo-50 transition">

                        <div class="flex items-center gap-2 flex-wrap">

                            <span
                                class="
                                    px-2 py-1 rounded-full text-[10px] sm:text-xs font-semibold
                                    @if (($nc['tipo'] ?? '') == 'NC') bg-red-100 text-red-600
                                    @elseif(($nc['tipo'] ?? '') == 'O') bg-yellow-100 text-yellow-700
                                    @elseif(($nc['tipo'] ?? '') == 'OM') bg-green-100 text-green-600
                                    @else bg-gray-200 text-gray-600 @endif
                                ">
                                {{ $nc['tipo'] ?? 'NC' }}
                            </span>

                            <span class="text-sm font-semibold text-gray-800">
                                Hallazgo #{{ $index + 1 }}
                            </span>

                            @if (!empty($nc['descripcion']))
                                <span class="text-xs text-gray-400 hidden sm:inline">
                                    - {{ \Illuminate\Support\Str::limit($nc['descripcion'], 40) }}
                                </span>
                            @endif
                        </div>

                        <div class="flex items-center gap-4 self-end sm:self-auto">

                            <svg x-show="!open" class="w-5 h-5 text-gray-500 transition" fill="none"
                                stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M19 9l-7 7-7-7" />
                            </svg>

                            <svg x-show="open" class="w-5 h-5 text-gray-500 transition" fill="none"
                                stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M5 15l7-7 7 7" />
                            </svg>

                            <button type="button" wire:click.stop="eliminarNoConformidad({{ $index }})"
                                class="text-red-500 hover:text-red-700 text-sm transition">
                                ✕
                            </button>

                        </div>
                    </div>

                    {{-- BODY --}}
                    <div x-show="open" x-collapse
                        class="bg-white px-4 sm:px-6 py-6 border-t border-gray-200 space-y-5">

                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 sm:gap-5">

                            {{-- REQUISITO ISO --}}
                            <div>
                                <label class="block text-xs font-semibold text-gray-600 mb-1">
                                    Requisito ISO
                                </label>
                                <select wire:model="noConformidades.{{ $index }}.norma_iso_id"
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
                                <select wire:model="noConformidades.{{ $index }}.tipo"
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
                            <textarea wire:model="noConformidades.{{ $index }}.descripcion" rows="3"
                                class="w-full rounded-xl border-gray-300 focus:border-blue-500 focus:ring focus:ring-blue-200 text-sm px-3 py-2 shadow-sm"></textarea>
                        </div>

                        {{-- EVIDENCIA --}}
                        <div>
                            <label class="block text-xs font-semibold text-gray-600 mb-1">
                                Evidencia
                            </label>
                            <textarea wire:model="noConformidades.{{ $index }}.evidencia" rows="3"
                                class="w-full rounded-xl border-gray-300 focus:border-indigo-500 focus:ring focus:ring-indigo-200 text-sm px-3 py-2 shadow-sm"></textarea>
                        </div>

                    </div>
                </div>
            @endforeach
        </div>

        {{-- BOTÓN GUARDAR --}}
        <div class="pt-6 border-t">

            {{-- Desktop --}}
            <div class="hidden sm:flex justify-end">
                <button wire:click="guardar"
                    class="bg-gradient-to-r from-blue-600 to-indigo-600 text-white px-6 py-3 rounded-xl text-sm font-semibold shadow-md hover:shadow-lg hover:scale-105 transition">
                    {{ $modoEdicion ? 'Actualizar Informe' : 'Guardar Informe' }}
                </button>
            </div>

            {{-- Mobile Sticky --}}
            <div class="sm:hidden fixed bottom-0 left-0 right-0 bg-white border-t p-4 shadow-lg z-50">
                <button wire:click="guardar"
                    class="w-full bg-gradient-to-r from-blue-600 to-indigo-600 text-white py-3 rounded-xl text-sm font-semibold shadow-md active:scale-95 transition">
                    {{ $modoEdicion ? 'Actualizar' : 'Guardar' }}
                </button>
            </div>
        </div>

    </div>
</div>
@push('js')
    <script src="https://cdn.jsdelivr.net/npm/quill@2.0.3/dist/quill.js"></script>

    <script>
        document.addEventListener("livewire:init", () => {

            let quill = new Quill('#editor', {
                theme: 'snow',
                placeholder: 'Escriba la descripción del informe...',
                modules: {
                    toolbar: [
                        ['bold', 'italic', 'underline'],
                        [{
                            'header': [1, 2, 3, false]
                        }],
                        [{
                            'list': 'ordered'
                        }, {
                            'list': 'bullet'
                        }],
                        ['link'],
                        ['clean']
                    ]
                }
            });

            // 👉 Sincronizar cuando escribes
            quill.on('text-change', function() {
                let html = quill.root.innerHTML;
                @this.set('descripcion', html);
            });

            // 👉 Cargar contenido cada vez que Livewire actualiza
            Livewire.hook('morph.updated', () => {

                let contenido = @this.get('descripcion') ?? '';

                if (quill.root.innerHTML !== contenido) {
                    quill.root.innerHTML = contenido;
                }

            });

            // 👉 Cargar al inicio también
            setTimeout(() => {
                quill.root.innerHTML = @this.get('descripcion') ?? '';
            }, 100);

        });
    </script>
@endpush


</div>

<div class="mx-auto max-w-5xl">
    {{-- Toast de éxito --}}
    @if (session()->has('message'))
        <div x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 4000)"
            x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 -translate-y-2"
            x-transition:enter-end="opacity-100 translate-y-0" x-transition:leave="transition ease-in duration-200"
            x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0"
            class="mb-6 flex items-center gap-3 rounded-xl border border-emerald-200 bg-emerald-50 px-4 py-3 text-emerald-800 shadow-sm">
            <svg class="h-5 w-5 shrink-0 text-emerald-500" fill="none" viewBox="0 0 24 24" stroke="currentColor"
                stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round"
                    d="M9 12.75 11.25 15 15 9.75M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
            </svg>
            <p class="flex-1 text-sm font-medium">{{ session('message') }}</p>
            <button @click="show = false" class="text-emerald-500 hover:text-emerald-700">
                <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 18 18 6M6 6l12 12" />
                </svg>
            </button>
        </div>
    @endif
    @if (session()->has('message_error'))
        <div class="mb-6 rounded-xl border border-red-200 bg-red-50 px-4 py-3 text-red-700">
            {{ session('message_error') }}
        </div>
    @endif

    <div class="overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm">
        <div class="border-b border-slate-100 bg-gradient-to-r from-slate-50 to-white px-6 py-5">
            <h1 class="text-lg font-semibold text-slate-900">Reporte de Inventario</h1>
            <p class="mt-0.5 text-sm text-slate-500">Registra el stock final de materiales por cuadrilla.</p>
        </div>

        <form wire:submit.prevent="guardar" class="px-6 py-6">
            <div class="grid grid-cols-1 gap-5 sm:grid-cols-2 lg:grid-cols-4">
                <div>
                    <label class="mb-1.5 block text-sm font-medium text-slate-700">Grupo</label>
                    <select wire:model.live="grupo_id"
                        class="w-full rounded-lg border-slate-300 bg-white text-sm text-slate-900 shadow-sm transition focus:border-indigo-500 focus:ring-2 focus:ring-indigo-100 disabled:cursor-not-allowed disabled:bg-slate-50 disabled:text-slate-400">
                        <option value="">Seleccione un grupo</option>
                        @foreach ($grupos as $grupo)
                            <option value="{{ $grupo->id }}">{{ $grupo->nombre }}
                            </option>
                        @endforeach
                    </select>
                    @error('grupo_id')
                        <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label class="mb-1.5 block text-sm font-medium text-slate-700">Tecnología</label>
                    <select wire:model.live="tecnologia_id" @disabled(!$grupo_id)
                        class="w-full rounded-lg border-slate-300 bg-white text-sm text-slate-900 shadow-sm transition focus:border-indigo-500 focus:ring-2 focus:ring-indigo-100 disabled:cursor-not-allowed disabled:bg-slate-50 disabled:text-slate-400">
                        <option value="">Seleccione una tecnología</option>
                        @foreach ($tecnologias as $tec)
                            <option value="{{ $tec->id }}">{{ $tec->nombre }}</option>
                        @endforeach
                    </select>
                    @error('tecnologia_id')
                        <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label class="mb-1.5 block text-sm font-medium text-slate-700">Cuadrilla</label>
                    <select wire:model="cuadrilla_id" @disabled(!$tecnologia_id || !$grupo_id)
                        class="w-full rounded-lg border-slate-300 bg-white text-sm text-slate-900 shadow-sm transition focus:border-indigo-500 focus:ring-2 focus:ring-indigo-100 disabled:cursor-not-allowed disabled:bg-slate-50 disabled:text-slate-400">
                        <option value="">Seleccione una cuadrilla</option>
                        @foreach ($cuadrillas as $cuadrilla)
                            <option value="{{ $cuadrilla->id }}">{{ $cuadrilla->cua_nombre }}</option>
                        @endforeach
                    </select>
                    @error('cuadrilla_id')
                        <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label class="mb-1.5 block text-sm font-medium text-slate-700">Fecha del inventario</label>
                    <input type="date" wire:model="fecha_inventario"
                        class="w-full rounded-lg border-slate-300 bg-white text-sm text-slate-900 shadow-sm transition focus:border-indigo-500 focus:ring-2 focus:ring-indigo-100">
                    @error('fecha_inventario')
                        <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <div class="my-6 h-px bg-slate-100"></div>

            @php $materiales = $this->materiales; @endphp

            @if ($materiales->isNotEmpty())

                {{-- 
                    FIX: wire:key dinámico basado en tecnologia_id.
                    Sin esto, Livewire/morphdom reutiliza este mismo nodo DOM al
                    cambiar de tecnología, por lo que Alpine NUNCA vuelve a ejecutar
                    x-data y el array "items" se queda con los materiales de la
                    tecnología anterior (aunque las filas <tr> del @foreach de abajo
                    sí se actualicen correctamente vía Livewire). Al forzar un
                    wire:key distinto por tecnología, Livewire destruye y recrea
                    el nodo, obligando a Alpine a reinicializar "items" con los
                    materiales correctos.
                --}}
                <div wire:key="materiales-tabla-{{ $tecnologia_id }}" x-data="{
                    search: '',
                    page: 1,
                    perPage: 10,
                    items: @js(
    $materiales->map(
        fn($m) => [
            'id' => $m->id,
            'codigo' => $m->codigo,
            'descripcion' => $m->descripcion,
        ],
    ),
),
                    get filtered() {
                        if (!this.search.trim()) return this.items;
                        const q = this.search.toLowerCase();
                        return this.items.filter(m =>
                            m.codigo.toLowerCase().includes(q) ||
                            m.descripcion.toLowerCase().includes(q)
                        );
                    },
                    get totalPages() {
                        return Math.max(Math.ceil(this.filtered.length / this.perPage), 1);
                    },
                    get paged() {
                        const start = (this.page - 1) * this.perPage;
                        return this.filtered.slice(start, start + this.perPage);
                    },
                    get from() {
                        return this.filtered.length === 0 ? 0 : (this.page - 1) * this.perPage + 1;
                    },
                    get to() {
                        return Math.min(this.page * this.perPage, this.filtered.length);
                    },
                    isVisible(id) {
                        return this.paged.some(m => m.id === id);
                    },
                    prevPage() { if (this.page > 1) this.page--; },
                    nextPage() { if (this.page < this.totalPages) this.page++; }
                }" x-init="$watch('search', () => page = 1);
                $watch('perPage', () => page = 1);">

                    <div class="mb-4 flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
                        <div>
                            <h2 class="text-sm font-semibold text-slate-900">Materiales</h2>
                            <p class="text-xs text-slate-500">Ingresa el stock final del día para cada material.</p>
                        </div>

                        <div class="flex items-center gap-2">
                            <div class="relative">
                                <svg class="pointer-events-none absolute left-3 top-1/2 h-4 w-4 -translate-y-1/2 text-slate-400"
                                    fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="m21 21-4.34-4.34M19 11a8 8 0 1 1-16 0 8 8 0 0 1 16 0Z" />
                                </svg>
                                <input type="text" x-model="search" placeholder="Buscar por código o descripción..."
                                    class="w-64 rounded-lg border-slate-300 bg-white pl-9 pr-9 text-sm text-slate-900 shadow-sm transition focus:border-indigo-500 focus:ring-2 focus:ring-indigo-100">
                                <button type="button" x-show="search" x-cloak @click="search = ''"
                                    class="absolute right-3 top-1/2 -translate-y-1/2 text-slate-400 hover:text-slate-600">
                                    <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"
                                        stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 18 18 6M6 6l12 12" />
                                    </svg>
                                </button>
                            </div>

                            <select x-model.number="perPage"
                                class="rounded-lg border-slate-300 bg-white text-sm text-slate-900 shadow-sm transition focus:border-indigo-500 focus:ring-2 focus:ring-indigo-100">
                                <option value="10">10 / pág.</option>
                                <option value="25">25 / pág.</option>
                                <option value="50">50 / pág.</option>
                            </select>
                        </div>
                    </div>

                    <div class="overflow-x-auto rounded-xl border border-slate-200">

                        <table class="min-w-[620px] w-full text-sm">

                            <thead>
                                <tr
                                    class="bg-slate-50 text-left text-xs font-medium uppercase tracking-wide text-slate-500">

                                    <th class="w-[120px] px-2 sm:px-4 py-3">
                                        Código
                                    </th>

                                    <th class="px-2 sm:px-4 py-3">
                                        Descripción
                                    </th>

                                    <th class="w-[140px] px-2 sm:px-4 py-3 text-center whitespace-nowrap">
                                        Stock Final
                                    </th>

                                </tr>
                            </thead>

                            <tbody class="divide-y divide-slate-100">

                                @foreach ($materiales as $material)
                                    <tr wire:key="material-{{ $material->id }}"
                                        x-show="isVisible({{ $material->id }})" x-cloak class="hover:bg-slate-50/60">

                                        <td class="px-2 sm:px-4 py-3 font-mono text-xs font-semibold text-slate-500">

                                            {{ $material->codigo }}

                                        </td>

                                        <td class="px-2 sm:px-4 py-3 text-slate-800">

                                            {{ $material->descripcion }}

                                        </td>

                                        <td class="px-2 sm:px-4 py-3">

                                            <input type="number" wire:model.live="stocks.{{ $material->id }}"
                                                min="0" placeholder="0"
                                                class="w-24 sm:w-32 mx-auto block rounded-lg border-slate-300 text-center text-sm shadow-sm transition focus:border-indigo-500 focus:ring-2 focus:ring-indigo-100 @error('stocks.' . $material->id) border-red-400 ring-1 ring-red-100 @enderror">

                                            @error('stocks.' . $material->id)
                                                <p class="mt-1 text-center text-xs text-red-600">
                                                    {{ $message }}
                                                </p>
                                            @enderror

                                        </td>

                                    </tr>
                                @endforeach

                            </tbody>

                        </table>

                        <div x-show="filtered.length === 0" x-cloak
                            class="flex flex-col items-center justify-center px-6 py-10 text-center">

                            <svg class="mb-2 h-8 w-8 text-slate-300" fill="none" viewBox="0 0 24 24"
                                stroke="currentColor" stroke-width="1.5">

                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="m21 21-4.34-4.34M19 11a8 8 0 1 1-16 0 8 8 0 0 1 16 0Z" />

                            </svg>

                            <p class="text-sm font-medium text-slate-600">
                                Sin coincidencias
                            </p>

                            <p class="mt-1 text-xs text-slate-400">
                                Prueba con otro código o descripción.
                            </p>

                        </div>

                    </div>

                    <div class="mt-3 flex flex-col items-center justify-between gap-3 sm:flex-row"
                        x-show="filtered.length > 0" x-cloak>
                        <p class="text-xs text-slate-500">
                            Mostrando <span class="font-medium text-slate-700" x-text="from"></span>–<span
                                class="font-medium text-slate-700" x-text="to"></span>
                            de <span class="font-medium text-slate-700" x-text="filtered.length"></span> materiales
                        </p>

                        <div class="flex items-center gap-1">
                            <button type="button" @click="prevPage" :disabled="page <= 1"
                                class="inline-flex h-8 w-8 items-center justify-center rounded-lg border border-slate-200 text-slate-500 transition hover:bg-slate-50 disabled:cursor-not-allowed disabled:opacity-40">
                                <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"
                                    stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M15.75 19.5 8.25 12l7.5-7.5" />
                                </svg>
                            </button>

                            <span class="px-2 text-xs font-medium text-slate-600">
                                Página <span x-text="page"></span> de <span x-text="totalPages"></span>
                            </span>

                            <button type="button" @click="nextPage" :disabled="page >= totalPages"
                                class="inline-flex h-8 w-8 items-center justify-center rounded-lg border border-slate-200 text-slate-500 transition hover:bg-slate-50 disabled:cursor-not-allowed disabled:opacity-40">
                                <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"
                                    stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="m8.25 4.5 7.5 7.5-7.5 7.5" />
                                </svg>
                            </button>
                        </div>
                    </div>
                </div>

                <div class="mt-6 flex justify-end">
                    <button type="button" wire:click="confirmarGuardar" wire:loading.attr="disabled"
                        wire:target="guardar"
                        class="inline-flex items-center gap-2 rounded-lg bg-indigo-600 px-5 py-2.5 text-sm font-semibold text-white shadow-sm transition hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 disabled:cursor-not-allowed disabled:opacity-60">
                        <svg wire:loading wire:target="guardar" class="h-4 w-4 animate-spin" fill="none"
                            viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor"
                                stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor"
                                d="M4 12a8 8 0 0 1 8-8V0C5.373 0 0 5.373 0 12h4Z"></path>
                        </svg>
                        <span wire:loading.remove wire:target="guardar">Guardar inventario</span>
                        <span wire:loading wire:target="guardar">Guardando...</span>
                    </button>
                </div>
            @else
                <div
                    class="flex flex-col items-center justify-center rounded-xl border border-dashed border-slate-200 bg-slate-50/50 px-6 py-12 text-center">
                    <svg class="mb-3 h-10 w-10 text-slate-300" fill="none" viewBox="0 0 24 24"
                        stroke="currentColor" stroke-width="1.5">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M20.25 7.5l-.625 10.632a2.25 2.25 0 0 1-2.247 2.118H6.622a2.25 2.25 0 0 1-2.247-2.118L3.75 7.5M10 11.25h4M3.375 7.5h17.25c.621 0 1.125-.504 1.125-1.125v-1.5c0-.621-.504-1.125-1.125-1.125H3.375C2.754 3.75 2.25 4.254 2.25 4.875v1.5c0 .621.504 1.125 1.125 1.125Z" />
                    </svg>
                    <p class="text-sm font-medium text-slate-600">Aún no hay materiales para mostrar</p>
                    <p class="mt-1 text-xs text-slate-400">Selecciona un grupo y una tecnología para cargar el listado.
                    </p>
                </div>
            @endif
        </form>
    </div>
    @if ($mostrarConfirmacion)
        <div class="fixed inset-0 z-50 flex items-center justify-center bg-black/50">

            <div class="w-full max-w-2xl rounded-xl bg-white shadow-xl">

                <div class="border-b px-6 py-4">
                    <h2 class="text-lg font-bold">
                        Confirmar inventario
                    </h2>

                    <p class="mt-1 text-sm text-gray-500">
                        Revise los materiales antes de guardar.
                    </p>
                </div>

                <div class="max-h-[400px] overflow-y-auto p-6 space-y-4">

                    @if (count($materialesConfirmacion) > 0)
                        <table class="w-full text-sm">

                            <thead class="bg-gray-100">

                                <tr>

                                    <th class="px-3 py-2 text-left">
                                        Código
                                    </th>

                                    <th class="px-3 py-2 text-left">
                                        Material
                                    </th>

                                    <th class="px-3 py-2 text-center">
                                        Cantidad
                                    </th>

                                </tr>

                            </thead>

                            <tbody>

                                @foreach ($this->materialesConfirmacion as $material)
                                    <tr class="border-b">

                                        <td class="px-3 py-2 font-mono">
                                            {{ $material['codigo'] }}
                                        </td>

                                        <td class="px-3 py-2">
                                            {{ $material['descripcion'] }}
                                        </td>

                                        <td class="px-3 py-2 text-center font-semibold">
                                            {{ $material['cantidad'] }}
                                        </td>

                                    </tr>
                                @endforeach

                            </tbody>

                        </table>
                    @else
                        <div class="rounded-lg border border-yellow-200 bg-yellow-50 p-4 text-yellow-700">
                            No existe ningún material con cantidad mayor a cero.
                        </div>
                    @endif

                    <!-- Campo Observaciones -->
                    <div class="pt-2">
                        <label for="observaciones" class="block text-sm font-medium text-gray-700 mb-1">
                            Observaciones (Opcional)
                        </label>
                        <textarea id="observaciones" wire:model="observaciones" rows="3"
                            placeholder="Ingrese comentarios o detalles adicionales..."
                            class="w-full rounded-lg border border-gray-300 p-2.5 text-sm focus:border-indigo-500 focus:ring-indigo-500"></textarea>
                        @error('observaciones')
                            <span class="text-xs text-red-600 mt-1 block">{{ $message }}</span>
                        @enderror
                    </div>

                </div>

                <div class="flex justify-end gap-3 border-t px-6 py-4">

                    <button wire:click="cancelarConfirmacion" class="rounded-lg border px-4 py-2 hover:bg-gray-100">
                        Cancelar
                    </button>

                    <button wire:click="guardar"
                        class="rounded-lg bg-indigo-600 px-5 py-2 text-white hover:bg-indigo-700">
                        Confirmar y guardar
                    </button>

                </div>

            </div>

        </div>
    @endif

@push('js')
    <script>
        document.addEventListener('livewire:init', () => {
            Livewire.on('swal', (data) => {
                const payload = Array.isArray(data) ? data[0] : data;
                Swal.fire({
                    icon: payload?.icon || 'info',
                    title: payload?.title || '',
                    text: payload?.text || null,
                    html: payload?.html || null,
                    confirmButtonColor: '#3085d6'
                });
            });

            Livewire.on('swal:success', (data) => {
                const payload = Array.isArray(data) ? data[0] : data;
                Swal.fire({
                    icon: payload?.icon || 'success',
                    title: payload?.title || '¡Bien hecho!',
                    text: payload?.text || 'Operación realizada con éxito',
                    position: 'top-end',
                    toast: true,
                    timer: 3000,
                    showConfirmButton: false
                });
            });
        });
    </script>
@endpush
</div>

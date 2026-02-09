<div class="bg-white rounded-xl shadow-sm border space-y-4">

    {{-- 🔍 BUSCADOR --}}
    <div class="px-6 pt-6">
        <div class="relative">
            <input wire:model.live.debounce.400ms="search"
            type="text"
                class="w-full pl-10 pr-4 py-2 text-sm border rounded-full focus:ring-2 focus:ring-blue-500 focus:outline-none"
                placeholder="Buscar indumentaria por nombre o tipo...">
            <span class="absolute left-4 top-1/2 -translate-y-1/2 text-gray-400">
                🔍
            </span>
        </div>
    </div>

    {{-- ⏳ LOADING --}}
    <div wire:loading class="px-6 py-4 text-sm text-blue-600 flex items-center gap-2">
        <svg class="animate-spin h-4 w-4 text-blue-600" xmlns="http://www.w3.org/2000/svg" fill="none"
            viewBox="0 0 24 24">
            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"/>
            <path class="opacity-75" fill="currentColor"
                d="M4 12a8 8 0 018-8v4a4 4 0 00-4 4H4z"/>
        </svg>
        Cargando indumentarias...
    </div>

    {{-- 📊 TABLA --}}
    <div class="relative overflow-x-auto" wire:loading.remove>
        <table class="w-full text-sm text-left text-gray-600">

            {{-- HEADER --}}
            <thead class="bg-gray-100 text-xs uppercase text-gray-600">
                <tr>
                    <th class="px-6 py-3">Indumentaria</th>
                    <th class="px-6 py-3">Características</th>
                    <th class="px-6 py-3 text-center">Nuevo</th>
                    <th class="px-6 py-3 text-center">Usado</th>
                    <th class="px-6 py-3 text-center">Total</th>
                    <th class="px-6 py-3">Detalle por bodega</th>
                    <th class="px-6 py-3 text-center">Acciones</th>
                </tr>
            </thead>

            {{-- BODY --}}
            <tbody>
                @forelse ($indumentarias as $indumentaria)
                    <tr class="border-b hover:bg-gray-50 transition">

                        {{-- 📦 INDUMENTARIA (CON IMAGEN) --}}
                        <td class="px-6 py-4">
                            <div class="flex items-center gap-4">

                                {{-- 🖼️ IMAGEN --}}
                                @if ($indumentaria->image)
                                    <img src="{{ asset('storage/'.$indumentaria->image) }}"
                                         alt="Imagen {{ $indumentaria->nombre }}"
                                         class="w-16 h-16 object-cover rounded-lg border">
                                @else
                                    <div
                                        class="w-12 h-12 flex items-center justify-center
                                               bg-gray-100 rounded-lg text-[10px] text-gray-400">
                                        Sin imagen
                                    </div>
                                @endif

                                {{-- TEXTO --}}
                                <div>
                                    <div class="font-semibold text-gray-800">
                                        {{ $indumentaria->nombre }}
                                    </div>
                                    <div class="text-xs text-gray-500">
                                        {{ $indumentaria->tipo }}
                                    </div>
                                </div>

                            </div>
                        </td>

                        {{-- 🎨 CARACTERÍSTICAS --}}
                        <td class="px-6 py-4">
                            <div class="flex flex-wrap gap-2 text-xs">
                                <span class="px-2 py-1 rounded bg-gray-100">
                                    🎨 {{ $indumentaria->color ?? 'N/A' }}
                                </span>
                                <span class="px-2 py-1 rounded bg-gray-100">
                                    👕 {{ $indumentaria->talla ?? 'N/A' }}
                                </span>
                            </div>
                        </td>

                        {{-- 🆕 NUEVO --}}
                        <td class="px-6 py-4 text-center">
                            <span class="px-3 py-1 text-xs font-semibold rounded-full bg-blue-100 text-blue-700">
                                {{ $indumentaria->stock_nuevo }}
                            </span>
                        </td>

                        {{-- ♻️ USADO --}}
                        <td class="px-6 py-4 text-center">
                            <span class="px-3 py-1 text-xs font-semibold rounded-full bg-yellow-100 text-yellow-700">
                                {{ $indumentaria->stock_usado }}
                            </span>
                        </td>

                        {{-- 📊 TOTAL --}}
                        <td class="px-6 py-4 text-center">
                            <span class="text-lg font-bold text-gray-800">
                                {{ $indumentaria->stock_total }}
                            </span>
                        </td>

                        {{-- 🏭 DETALLE POR BODEGA --}}
                        <td class="px-6 py-4">
                            <div class="space-y-1 text-xs">

                                {{-- NUEVO --}}
                                @foreach ($indumentaria->inventarios as $inv)
                                    <div class="flex justify-between text-blue-700">
                                        <span>🆕 {{ $inv->ubicacion->nombre }}</span>
                                        <span class="font-medium">{{ $inv->stock }}</span>
                                    </div>
                                @endforeach

                                {{-- USADO --}}
                                @foreach ($indumentaria->inventariosUsados as $inv)
                                    <div class="flex justify-between text-yellow-700">
                                        <span>♻️ {{ $inv->ubicacion->nombre }}</span>
                                        <span class="font-medium">{{ $inv->stock }}</span>
                                    </div>
                                @endforeach

                                @if ($indumentaria->inventarios->isEmpty() && $indumentaria->inventariosUsados->isEmpty())
                                    <span class="text-gray-400">
                                        Sin stock
                                    </span>
                                @endif
                            </div>
                        </td>

                        {{-- ✏️ ACCIONES --}}
                        <td class="px-6 py-4 text-center">
                            <a href="{{ route('admin.indumentarias.edit', $indumentaria) }}"
                               class="inline-flex items-center px-4 py-2 text-xs font-medium
                                      text-blue-600 bg-blue-50 rounded-lg
                                      hover:bg-blue-100 transition">
                                ✏️ Editar
                            </a>

                            
                        </td>

                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="px-6 py-10 text-center text-gray-500">
                            😕 No se encontraron indumentarias
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>

        {{-- 📄 PAGINACIÓN --}}
        <div class="px-6 py-4 border-t bg-gray-50">
            {{ $indumentarias->links() }}
        </div>
    </div>

</div>

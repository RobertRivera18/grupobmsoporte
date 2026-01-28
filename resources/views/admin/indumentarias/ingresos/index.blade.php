<x-admin-layout :breadcrumbs="[
    ['name' => 'Home', 'url' => route('admin.dashboard')],
    ['name' => 'Indumentaria', 'url' => route('admin.indumentarias.index')],
    ['name' => 'Ingresos'],
]">

    @push('css')
    @endpush
    <div class="max-w-6xl mx-auto space-y-8 px-4 sm:px-6">

        {{-- ✅ MENSAJE --}}
        @if (session('success'))
            <div class="bg-green-100 text-green-700 p-4 rounded-lg">
                {{ session('success') }}
            </div>
        @endif

        {{-- 📥 FORMULARIO --}}
        <div class="bg-white shadow rounded-lg p-6">
            <h2 class="text-lg font-bold mb-4 flex items-center gap-2">
                📥 Registrar ingreso
            </h2>

            <form action="{{ route('admin.indumentarias.ingresos.store') }}" method="POST"
                class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
                @csrf

                {{-- INDUMENTARIA --}}
                <div>
                    <label class="text-sm font-medium">Indumentaria</label>
                    <select name="indumentaria_id" class="w-full rounded-lg border-gray-300 indumentaria-select">
                        <option value="">👕 Indumentaria</option>
                        @foreach ($indumentarias as $item)
                            <option value="{{ $item->id }}">
                                {{ $item->nombre }} ({{ $item->tipo }})
                            </option>
                        @endforeach
                    </select>
                </div>

                {{-- BODEGA --}}
                <div>
                    <label class="text-sm font-medium">Bodega</label>
                    <select name="ubicacion_id" class="w-full rounded-lg border-gray-300">
                        <option value="">Seleccione</option>
                        @foreach ($ubicaciones as $u)
                            <option value="{{ $u->id }}">{{ $u->nombre }}</option>
                        @endforeach
                    </select>
                </div>

                {{-- CANTIDAD --}}
                <div>
                    <label class="text-sm font-medium">Cantidad</label>
                    <input type="number" name="cantidad" min="1" class="w-full rounded-lg border-gray-300">
                </div>

                {{-- OBSERVACIÓN --}}
                <div>
                    <label class="text-sm font-medium">Observación</label>
                    <input type="text" name="observacion" class="w-full rounded-lg border-gray-300">
                </div>

                {{-- BOTÓN --}}
                <div class="sm:col-span-2 lg:col-span-4 flex justify-end">
                    <button class="px-6 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition">
                        Registrar ingreso
                    </button>
                </div>
            </form>
        </div>

        {{-- 📜 HISTORIAL --}}
        <div class="bg-white shadow rounded-lg overflow-hidden">

            <div class="p-6 border-b">
                <h2 class="text-lg font-bold flex items-center gap-2">
                    📜 Historial de ingresos
                </h2>
            </div>

            {{-- 📱 MOBILE: CARDS --}}
            <div class="block lg:hidden divide-y">
                @forelse ($ingresos as $i)
                    <div class="p-4 space-y-2">
                        <div class="text-xs text-gray-500">
                            {{ $i->created_at->format('d/m/Y H:i') }}
                        </div>

                        <div class="font-semibold">
                            👕 {{ $i->indumentaria->nombre }}
                        </div>

                        <div class="text-sm text-gray-600">
                            📍 {{ $i->ubicacion->nombre }}
                        </div>

                        <div class="flex justify-between items-center">
                            <span class="text-green-600 font-bold">
                                +{{ $i->cantidad }}
                            </span>
                            <span class="text-xs text-gray-500">
                                {{ $i->user->name ?? '—' }}
                            </span>
                        </div>
                    </div>
                @empty
                    <div class="p-6 text-center text-gray-500">
                        Sin ingresos registrados
                    </div>
                @endforelse
            </div>

            {{-- 💻 DESKTOP: TABLA --}}
            <div class="hidden lg:block overflow-x-auto">
                <table class="w-full text-sm">
                    <thead class="bg-gray-100">
                        <tr>
                            <th class="px-4 py-2">Fecha</th>
                            <th class="px-4 py-2">Indumentaria</th>
                            <th class="px-4 py-2">Bodega</th>
                            <th class="px-4 py-2 text-right">Cantidad</th>
                            <th class="px-4 py-2">Usuario</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($ingresos as $i)
                            <tr class="border-t hover:bg-gray-50">
                                <td class="px-4 py-2">
                                    {{ $i->created_at->format('d/m/Y H:i') }}
                                </td>
                                <td class="px-4 py-2">
                                    {{ $i->indumentaria->nombre }}
                                </td>
                                <td class="px-4 py-2">
                                    {{ $i->ubicacion->nombre }}
                                </td>
                                <td class="px-4 py-2 text-right font-bold text-green-600">
                                    +{{ $i->cantidad }}
                                </td>
                                <td class="px-4 py-2">
                                    {{ $i->user->name ?? '—' }}
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="py-6 text-center text-gray-500">
                                    Sin ingresos registrados
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            {{-- PAGINACIÓN --}}
            <div class="p-4 border-t">
                {{ $ingresos->links() }}
            </div>
        </div>

    </div>

    @push('js')
        <script>
               let index = 1;

            function initIndumentariaSelect(el) {
                new TomSelect(el, {
                    placeholder: '🔍 Buscar indumentaria...',
                    allowEmptyOption: true,
                    searchField: ['text'],
                    maxOptions: 100,
                });
            }

            // inicializar el primero
            document.querySelectorAll('.indumentaria-select').forEach(initIndumentariaSelect);
        </script>
    @endpush
</x-admin-layout>

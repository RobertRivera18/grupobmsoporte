<div class="max-w-7xl mx-auto px-6 pb-14 space-y-10">

    {{-- ================= HEADER GENERAL ================= --}}
    <div>
        <h2 class="text-2xl font-bold text-gray-800 tracking-tight">
            Gestión de Procesos de Auditoría
        </h2>
        <p class="text-gray-500 text-sm mt-1">
            Administra los procesos, responsables y normas ISO aplicables.
        </p>
    </div>

    {{-- ================= FORMULARIO ================= --}}
    <div class="bg-white rounded-3xl shadow-lg border border-gray-100 overflow-hidden">

        {{-- Top Gradient --}}
        <div class="bg-gradient-to-r from-indigo-600 via-blue-600 to-purple-600 px-8 py-6 text-white">
            <h3 class="text-lg font-semibold">
                ➕ Crear proceso de auditoría
            </h3>
            <p class="text-indigo-100 text-sm mt-1">
                Configura área, responsables y normas aplicables.
            </p>
        </div>

        <div class="p-8 bg-gray-50">

            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">

                {{-- Área --}}
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">
                        Área
                    </label>
                    <select wire:model="area_id"
                        class="w-full rounded-xl border-gray-300 focus:ring-2 focus:ring-indigo-400 focus:border-indigo-500 shadow-sm transition">
                        <option value="">Seleccione un área</option>
                        @foreach ($areas as $area)
                            <option value="{{ $area->id }}">{{ $area->nombre }}</option>
                        @endforeach
                    </select>
                    @error('area_id')
                        <span class="text-xs text-red-500 mt-1 block">{{ $message }}</span>
                    @enderror
                </div>

                {{-- Auditor --}}
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">
                        Auditor
                    </label>
                    <select wire:model="auditor_id"
                        class="w-full rounded-xl border-gray-300 focus:ring-2 focus:ring-blue-400 focus:border-blue-500 shadow-sm transition">
                        <option value="">Seleccione auditor</option>
                        @foreach ($auditores as $user)
                            <option value="{{ $user->id }}">{{ $user->name }}</option>
                        @endforeach
                    </select>
                </div>

                {{-- Responsable --}}
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">
                        Responsable del área
                    </label>
                    <select wire:model="responsable_id"
                        class="w-full rounded-xl border-gray-300 focus:ring-2 focus:ring-purple-400 focus:border-purple-500 shadow-sm transition">
                        <option value="">Seleccione responsable</option>
                        @foreach ($responsables as $user)
                            <option value="{{ $user->id }}">{{ $user->name }}</option>
                        @endforeach
                    </select>
                </div>

                {{-- Normas ISO --}}
                <div class="md:col-span-3">
                    <label class="block text-sm font-semibold text-gray-700 mb-3">
                        📘 Normas ISO aplicables
                    </label>

                    <div class="bg-white border border-gray-200 rounded-2xl p-6 shadow-sm">
                        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-4 gap-4">

                            @foreach ($normasIso as $norma)
                                <label
                                    class="group flex items-start gap-3 p-4 rounded-xl border border-gray-200 bg-gray-50
                                           hover:bg-indigo-50 hover:border-indigo-400 transition cursor-pointer">

                                    <input type="checkbox"
                                        wire:model="normas"
                                        value="{{ $norma->id }}"
                                        class="mt-1 rounded text-indigo-600 focus:ring-indigo-500">

                                    <div>
                                        <p class="text-sm font-semibold text-gray-800 group-hover:text-indigo-700">
                                            {{ $norma->codigo }}
                                        </p>
                                        <p class="text-xs text-gray-500">
                                            {{ $norma->descripcion }}
                                        </p>
                                    </div>

                                </label>
                            @endforeach

                        </div>
                    </div>
                </div>
            </div>

            {{-- Botón --}}
            <div class="flex justify-end mt-10">
                <button wire:click="save"
                    class="inline-flex items-center gap-2 bg-gradient-to-r from-indigo-600 to-blue-600
                           hover:from-indigo-700 hover:to-blue-700
                           text-white px-7 py-3 rounded-xl font-semibold shadow-md
                           hover:shadow-xl hover:scale-105 transition">
                    💾 Guardar proceso
                </button>
            </div>

        </div>
    </div>

    {{-- ================= LISTADO ================= --}}
    <div class="bg-white rounded-3xl shadow-lg border border-gray-100 overflow-hidden">

        <div class="px-8 py-6 border-b bg-gray-50 flex items-center justify-between">
            <h3 class="text-lg font-semibold text-gray-800">
                📋 Procesos creados
            </h3>
            <span class="text-xs bg-indigo-100 text-indigo-700 px-3 py-1 rounded-full font-medium">
                {{ $procesos->count() }} registros
            </span>
        </div>

        <div class="overflow-x-auto">
            <table class="min-w-full text-sm">

                <thead class="bg-gradient-to-r from-gray-100 to-gray-50 text-gray-600 uppercase text-xs tracking-wide">
                    <tr>
                        <th class="text-left px-6 py-4">Área</th>
                        <th class="text-center px-6 py-4">Auditor</th>
                        <th class="text-center px-6 py-4">Responsable</th>
                        <th class="text-center px-6 py-4">Normas ISO</th>
                        <th class="text-center px-6 py-4">Acciones</th>
                    </tr>
                </thead>

                <tbody class="divide-y divide-gray-100">

                    @forelse ($procesos as $proceso)
                        <tr class="hover:bg-indigo-50 transition">

                            <td class="px-6 py-4 font-semibold text-gray-800">
                                {{ $proceso->area->nombre }}
                            </td>

                            <td class="px-6 py-4 text-center text-gray-600">
                                {{ $proceso->auditor->name }}
                            </td>

                            <td class="px-6 py-4 text-center text-gray-600">
                                {{ $proceso->responsable->name }}
                            </td>

                            <td class="px-6 py-4">
                                <div class="flex flex-wrap gap-2 justify-center">
                                    @foreach ($proceso->normas as $norma)
                                        <span
                                            class="bg-gradient-to-r from-indigo-100 to-blue-100
                                                   text-indigo-700 text-xs font-semibold
                                                   px-3 py-1 rounded-full shadow-sm">
                                            {{ $norma->codigo }}
                                        </span>
                                    @endforeach
                                </div>
                            </td>

                            <td class="px-6 py-4 text-center">
                                <div class="flex justify-center items-center gap-4">

                                    <button
                                        wire:click="eliminar({{ $proceso->id }})"
                                        class="text-red-500 hover:text-red-700 transition"
                                        title="Eliminar">
                                        <i class="fas fa-trash-alt"></i>
                                    </button>

                                    <a href="{{ route('admin.auditorias.procesos.detalle', [$auditoria, $proceso]) }}"
                                        class="text-indigo-600 hover:text-indigo-800 transition"
                                        title="Ver detalle">
                                        <i class="fas fa-eye"></i>
                                    </a>

                                </div>
                            </td>

                        </tr>

                    @empty
                        <tr>
                            <td colspan="5" class="text-center text-gray-400 py-12">
                                🚫 No hay procesos creados todavía.
                            </td>
                        </tr>
                    @endforelse

                </tbody>
            </table>
        </div>
    </div>

</div>

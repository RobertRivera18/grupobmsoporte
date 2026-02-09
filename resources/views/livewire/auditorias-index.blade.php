<div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 xl:grid-cols-4 gap-6">
    @foreach ($auditorias as $auditoria)
        <div
            class="relative group bg-white rounded-2xl border border-gray-100 p-6
                   shadow-sm hover:shadow-xl hover:-translate-y-1
                   transition-all duration-300">

            <!-- Estado -->
            <span
                class="absolute top-4 left-4 px-3 py-1 text-xs font-semibold rounded-full
                @if ($auditoria->estado === 'cerrada') bg-green-100 text-green-700
                @elseif($auditoria->estado === 'en_proceso')
                    bg-yellow-100 text-yellow-700
                @else
                    bg-gray-100 text-gray-600 @endif">
                {{ ucfirst(str_replace('_', ' ', $auditoria->estado)) }}
            </span>

            <!-- Año -->
            <div
                class="flex items-center justify-center h-24 rounded-xl
                        bg-gradient-to-br from-indigo-500 to-indigo-600 text-white">
                <span class="text-3xl font-bold tracking-wide">
                    {{ $auditoria->anio }}
                </span>
            </div>

            <!-- Info -->
            <div class="mt-5 space-y-2 text-sm text-gray-600">
                <div class="flex items-center gap-2">
                    <i class="fas fa-calendar-alt text-indigo-500"></i>
                    <span>
                        {{ \Carbon\Carbon::parse($auditoria->fecha_inicio)->format('d M Y') }}
                        @if ($auditoria->fecha_fin)
                            – {{ \Carbon\Carbon::parse($auditoria->fecha_fin)->format('d M Y') }}
                        @endif
                    </span>
                </div>
            </div>

            <!-- Acciones -->
            <div
                class="absolute top-4 right-4 flex items-center gap-2
                       opacity-0 group-hover:opacity-100 transition">

                <a href="{{ route('admin.auditorias.show', $auditoria) }}"
                    class="p-2 rounded-lg bg-white shadow hover:bg-indigo-50
                           text-gray-500 hover:text-indigo-600 transition"
                    title="Ver auditoría">
                    <i class="fas fa-eye"></i>
                </a>

                <a href="{{ route('admin.auditorias.edit', $auditoria) }}"
                    class="p-2 rounded-lg bg-white shadow hover:bg-indigo-50
                           text-gray-500 hover:text-indigo-600 transition"
                    title="Editar auditoría">
                    <i class="fas fa-pen"></i>
                </a>

                <button wire:click="eliminar({{ $auditoria->id }})"
                    class="p-2 rounded-lg bg-white shadow hover:bg-red-50
                           text-gray-500 hover:text-red-600 transition"
                    title="Eliminar auditoría">
                    <i class="fas fa-trash"></i>
                </button>
            </div>

        </div>
    @endforeach
</div>

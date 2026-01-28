
<div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 xl:grid-cols-4 gap-6">
    @foreach ($areas as $area)
        <div
            class="relative group bg-white rounded-2xl border border-gray-100 p-6 shadow-sm hover:shadow-lg 
            hover:-translate-y-1 transition-all duration-300">

            <!-- Ícono/Inicial -->
            <div
                class="flex items-center justify-center w-14 h-14 rounded-xl 
                bg-indigo-100 text-indigo-600 text-xl font-bold
                group-hover:bg-indigo-600 group-hover:text-white transition">
                {{ strtoupper(substr($area->nombre, 0, 1)) }}
            </div>

            <!-- Nombre -->
            <h3 class="mt-4 text-lg font-semibold text-gray-800 
                group-hover:text-indigo-600 transition">
                {{ $area->nombre }}
            </h3>

            <!-- Acciones -->
            <div
                class="absolute top-4 right-4 flex items-center gap-3 opacity-0 
                group-hover:opacity-100 transition">

                <!-- Ver -->
                <a href="{{ route('admin.areas.show', $area) }}"
                    class="p-2 rounded-lg bg-gray-100 hover:bg-indigo-100 hover:text-indigo-600 transition"
                    title="Ver detalle">
                    <i class="fas fa-eye"></i>
                </a>

                <!-- Editar -->
                <a href="{{ route('admin.areas.edit', $area) }}"
                    class="p-2 rounded-lg bg-gray-100 hover:bg-indigo-100 hover:text-indigo-600 transition"
                    title="Editar">
                    <i class="fas fa-edit"></i>
                </a>

                <!-- Eliminar -->
                <a wire:click="eliminar({{ $area->id }})"
                    class="p-2 rounded-lg bg-gray-100 hover:bg-indigo-100 hover:text-red-600 transition cursor-pointer"
                    title="Eliminar">
                    <i class="fas fa-trash"></i>
                </a>

            </div>

        </div>
    @endforeach
</div>

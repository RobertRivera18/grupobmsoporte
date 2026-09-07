<div class="p-4 sm:p-6 lg:p-8 max-w-7xl mx-auto space-y-6">

    <!-- Header & Action Section -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 pb-2 border-b border-gray-200 dark:border-gray-800">
        <div>
            <h1 class="text-2xl sm:text-3xl font-bold tracking-tight text-gray-900 dark:text-white">
                Cursos de Capacitación
            </h1>
            <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                Gestiona la oferta formativa, módulos e inscritos de la plataforma.
            </p>
        </div>

        <div class="flex items-center gap-3">
            <a href="{{ route('admin.capacitacion.courses.create') }}"
                class="inline-flex items-center justify-center w-full sm:w-auto px-4 py-2.5 text-sm font-semibold text-white bg-indigo-600 hover:bg-indigo-500 active:bg-indigo-700 rounded-xl shadow-xs hover:shadow-indigo-500/20 transition-all duration-200 focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600">
                <i class="fas fa-plus text-xs mr-2"></i>
                Nuevo Curso
            </a>
        </div>
    </div>

    <!-- Alert Status Notice -->
    @if (session()->has('success'))
        <div x-data="{ show: true }" x-show="show" x-transition.out.opacity.duration.300ms
             class="flex items-center justify-between p-4 text-sm text-emerald-800 border border-emerald-200/80 rounded-xl bg-emerald-50/80 dark:bg-emerald-950/40 dark:text-emerald-300 dark:border-emerald-800/60 shadow-xs" role="alert">
            <div class="flex items-center gap-3">
                <div class="p-1 bg-emerald-100 dark:bg-emerald-900/60 rounded-lg shrink-0">
                    <i class="fas fa-check text-emerald-600 dark:text-emerald-400"></i>
                </div>
                <p class="font-medium">{{ session('success') }}</p>
            </div>
            <button @click="show = false" class="text-emerald-500 hover:text-emerald-700 dark:hover:text-emerald-300 transition-colors p-1">
                <i class="fas fa-times"></i>
            </button>
        </div>
    @endif

    <!-- Container Card -->
    <div class="bg-white dark:bg-gray-900 rounded-2xl shadow-sm border border-gray-200/80 dark:border-gray-800 overflow-hidden">
        
        <!-- Toolbar: Search input -->
        <div class="p-4 border-b border-gray-100 dark:border-gray-800 bg-gray-50/50 dark:bg-gray-800/20">
            <div class="relative max-w-md">
                <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-gray-400">
                    <i class="fas fa-search text-sm"></i>
                </div>
                <input type="text" 
                    wire:model.live.debounce.300ms="search" 
                    placeholder="Buscar por curso o URL slug..."
                    class="w-full pl-10 pr-10 py-2 text-sm bg-white dark:bg-gray-900 border border-gray-300 dark:border-gray-700 rounded-xl text-gray-900 dark:text-white placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 transition-all duration-150">
                @if($search)
                    <button wire:click="$set('search', '')" class="absolute inset-y-0 right-0 pr-3 flex items-center text-gray-400 hover:text-gray-600 dark:hover:text-gray-200">
                        <i class="fas fa-times-circle text-xs"></i>
                    </button>
                @endif
            </div>
        </div>

        <!-- Indicador de carga sutil para Livewire -->
        <div wire:loading.flex wire:target="search" class="w-full justify-center py-2 bg-indigo-50/50 dark:bg-indigo-950/20 text-xs text-indigo-600 dark:text-indigo-400 font-medium">
            <i class="fas fa-spinner fa-spin mr-2"></i> Buscando cursos...
        </div>

        <!-- VISTA MOBILE: Cards (ocultas en escritorio) -->
        <div class="block lg:hidden divide-y divide-gray-100 dark:divide-gray-800">
            @forelse($courses as $course)
                <div class="p-4 space-y-3 hover:bg-gray-50/50 dark:hover:bg-gray-800/40 transition-colors">
                    <div class="flex items-start justify-between gap-3">
                        <div>
                            <h3 class="font-semibold text-gray-900 dark:text-white text-base">
                                {{ $course->name }}
                            </h3>
                            <p class="text-xs font-mono text-gray-400 mt-0.5">/{{ $course->slug }}</p>
                        </div>
                        <button wire:click="toggleStatus({{ $course->id }})" class="shrink-0">
                            @if ($course->status)
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-emerald-100 text-emerald-800 dark:bg-emerald-950/80 dark:text-emerald-300">
                                    <span class="w-1.5 h-1.5 mr-1.5 rounded-full bg-emerald-500"></span> Activo
                                </span>
                            @else
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-600 dark:bg-gray-800 dark:text-gray-400">
                                    <span class="w-1.5 h-1.5 mr-1.5 rounded-full bg-gray-400"></span> Inactivo
                                </span>
                            @endif
                        </button>
                    </div>

                    <div class="flex items-center gap-4 text-xs text-gray-500 dark:text-gray-400 pt-1">
                        <span class="inline-flex items-center gap-1.5">
                            <i class="fas fa-layer-group text-gray-400"></i> {{ $course->modules_count }} Módulos
                        </span>
                        <span class="inline-flex items-center gap-1.5">
                            <i class="fas fa-users text-gray-400"></i> {{ $course->enrollments_count }} Inscritos
                        </span>
                    </div>

                    <div class="flex items-center justify-end gap-2 pt-2 border-t border-gray-100 dark:border-gray-800/60">
                        <a href="{{ route('admin.capacitacion.courses.show', $course) }}" class="p-2 text-gray-600 dark:text-gray-400 hover:bg-gray-100 dark:hover:bg-gray-800 rounded-lg text-xs font-medium inline-flex items-center gap-1">
                            <i class="fas fa-folder-open text-indigo-500"></i> Administrar
                        </a>
                        <a href="{{ route('admin.capacitacion.courses.edit', $course) }}" class="p-2 text-gray-600 dark:text-gray-400 hover:bg-gray-100 dark:hover:bg-gray-800 rounded-lg text-xs font-medium inline-flex items-center gap-1">
                            <i class="fas fa-edit text-blue-500"></i> Editar
                        </a>
                        <button wire:click="confirmDelete({{ $course->id }})" class="p-2 text-red-600 dark:text-red-400 hover:bg-red-50 dark:hover:bg-red-950/50 rounded-lg text-xs font-medium inline-flex items-center gap-1">
                            <i class="fas fa-trash-alt"></i> Eliminar
                        </button>
                    </div>
                </div>
            @empty
                <div class="p-8 text-center text-gray-500 dark:text-gray-400 text-sm">
                    No se encontraron cursos que coincidan con la búsqueda.
                </div>
            @endforelse
        </div>

        <!-- VISTA ESCRITORIO: Tabla tradicional -->
        <div class="hidden lg:block overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-gray-50/80 dark:bg-gray-800/50 border-b border-gray-200/80 dark:border-gray-800 text-xs font-semibold uppercase tracking-wider text-gray-500 dark:text-gray-400">
                        <th scope="col" class="px-6 py-4">Curso</th>
                        <th scope="col" class="px-6 py-4">Módulos</th>
                        <th scope="col" class="px-6 py-4">Inscritos</th>
                        <th scope="col" class="px-6 py-4">Estado</th>
                        <th scope="col" class="px-6 py-4 text-right">Acciones</th>
                    </tr>
                </thead>

                <tbody class="divide-y divide-gray-200/80 dark:divide-gray-800 text-sm">
                    @forelse($courses as $course)
                        <tr class="hover:bg-gray-50/50 dark:hover:bg-gray-800/30 transition-colors duration-150">
                            
                            <!-- Nombre & Slug -->
                            <td class="px-6 py-4">
                                <div class="font-medium text-gray-900 dark:text-white">
                                    {{ $course->name }}
                                </div>
                                <div class="text-xs text-gray-400 dark:text-gray-500 font-mono mt-0.5">
                                    /{{ $course->slug }}
                                </div>
                            </td>

                            <!-- Módulos -->
                            <td class="px-6 py-4 text-gray-600 dark:text-gray-300">
                                <span class="inline-flex items-center gap-1.5 font-medium">
                                    <i class="fas fa-layer-group text-xs text-gray-400"></i>
                                    {{ $course->modules_count }}
                                </span>
                            </td>

                            <!-- Inscritos -->
                            <td class="px-6 py-4 text-gray-600 dark:text-gray-300">
                                <span class="inline-flex items-center gap-1.5 font-medium">
                                    <i class="fas fa-users text-xs text-gray-400"></i>
                                    {{ $course->enrollments_count }}
                                </span>
                            </td>

                            <!-- Estado con Interrupción Clic -->
                            <td class="px-6 py-4">
                                <button wire:click="toggleStatus({{ $course->id }})" 
                                        class="cursor-pointer focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 rounded-full dark:focus:ring-offset-gray-900 transition-transform active:scale-95">
                                    @if ($course->status)
                                        <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium bg-emerald-50 text-emerald-700 dark:bg-emerald-950/60 dark:text-emerald-300 border border-emerald-200/80 dark:border-emerald-800/50">
                                            <span class="w-1.5 h-1.5 mr-1.5 rounded-full bg-emerald-500"></span>
                                            Activo
                                        </span>
                                    @else
                                        <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium bg-gray-100 text-gray-600 dark:bg-gray-800 dark:text-gray-400 border border-gray-200 dark:border-gray-700">
                                            <span class="w-1.5 h-1.5 mr-1.5 rounded-full bg-gray-400"></span>
                                            Inactivo
                                        </span>
                                    @endif
                                </button>
                            </td>

                            <!-- Botones de Acción -->
                            <td class="px-6 py-4 text-right">
                                <div class="flex items-center justify-end gap-1">
                                    <a href="{{ route('admin.capacitacion.courses.show', $course) }}"
                                        class="p-2 text-gray-400 hover:text-indigo-600 dark:hover:text-indigo-400 hover:bg-indigo-50 dark:hover:bg-indigo-950/50 rounded-lg transition-colors" 
                                        title="Administrar curso">
                                        <i class="fas fa-folder-open text-base"></i>
                                    </a>

                                    <a href="{{ route('admin.capacitacion.courses.edit', $course) }}"
                                        class="p-2 text-gray-400 hover:text-blue-600 dark:hover:text-blue-400 hover:bg-blue-50 dark:hover:bg-blue-950/50 rounded-lg transition-colors" 
                                        title="Editar curso">
                                        <i class="fas fa-edit text-base"></i>
                                    </a>

                                    <button wire:click="confirmDelete({{ $course->id }})" 
                                        class="p-2 text-gray-400 hover:text-red-600 dark:hover:text-red-400 hover:bg-red-50 dark:hover:bg-red-950/50 rounded-lg transition-colors"
                                        title="Eliminar curso">
                                        <i class="fas fa-trash-alt text-base"></i>
                                    </button>
                                </div>
                            </td>

                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-6 py-12 text-center">
                                <div class="flex flex-col items-center justify-center max-w-xs mx-auto">
                                    <div class="w-12 h-12 rounded-2xl bg-gray-100 dark:bg-gray-800 flex items-center justify-center text-gray-400 mb-3">
                                        <i class="fas fa-graduation-cap text-xl"></i>
                                    </div>
                                    <p class="text-sm font-semibold text-gray-900 dark:text-white">Sin resultados</p>
                                    <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">No hay ningún curso registrado o no coincide con los parámetros de búsqueda.</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Paginación -->
        @if($courses->hasPages())
            <div class="p-4 border-t border-gray-100 dark:border-gray-800 bg-gray-50/50 dark:bg-gray-800/20">
                {{ $courses->links() }}
            </div>
        @endif

    </div>
</div>
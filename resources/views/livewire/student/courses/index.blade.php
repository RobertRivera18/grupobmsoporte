<div class="p-6 max-w-7xl mx-auto space-y-6">

    {{-- Encabezado y Búsqueda --}}
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <h1 class="text-2xl font-black text-gray-900 dark:text-white">Mis Cursos</h1>
            <p class="text-xs text-gray-500 dark:text-gray-400">Accede a tus cursos matriculados y continúa tu
                aprendizaje.</p>
        </div>
        <div class="w-full sm:w-72">
            <input type="text" wire:model.live.debounce.300ms="search" placeholder="Buscar mi curso..."
                class="w-full text-xs rounded-xl border-gray-200 dark:border-gray-800 dark:bg-gray-900 dark:text-white focus:ring-indigo-500 focus:border-indigo-500">
        </div>
    </div>

    {{-- Grid de Cursos --}}
    @if ($courses->count() > 0)
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @foreach ($courses as $course)
                <div
                    class="bg-white dark:bg-gray-900 rounded-2xl border border-gray-200/80 dark:border-gray-800 overflow-hidden shadow-sm hover:shadow-md transition-shadow flex flex-col">
                    {{-- Imagen del curso --}}
                    <div class="h-40 bg-gray-100 dark:bg-gray-800 relative">
                        @if ($course->image)
                            <img src="{{ asset('storage/' . $course->image) }}" alt="{{ $course->name }}"
                                class="w-full h-full object-cover">
                        @else
                            <div
                                class="w-full h-full flex items-center justify-center text-gray-400 bg-gray-100 dark:bg-gray-800">
                                <i class="fas fa-graduation-cap text-4xl"></i>
                            </div>
                        @endif

                        <span
                            class="absolute top-3 right-3 px-2 py-1 bg-emerald-500/90 text-white text-[10px] font-bold rounded-lg backdrop-blur-sm">
                            Enrolado
                        </span>
                    </div>

                    {{-- Cuerpo del Card --}}
                    <div class="p-5 flex-1 flex flex-col justify-between space-y-4">
                        <div>
                            <h2 class="text-base font-bold text-gray-900 dark:text-white line-clamp-1">
                                {{ $course->name }}
                            </h2>
                            <p class="text-xs text-gray-500 dark:text-gray-400 mt-1 line-clamp-2">
                                {{ $course->description }}
                            </p>
                        </div>

                        {{-- Barra de Progreso y Métricas --}}
                        <div class="space-y-3 pt-2 border-t border-gray-100 dark:border-gray-800">
                            <div class="flex items-center justify-between text-[11px] font-semibold text-gray-500">
                                <span><i class="fas fa-layer-group text-indigo-500 mr-1"></i>
                                    {{ $course->modules_count }} Módulos</span>
                                <span><i class="fas fa-file-signature text-amber-500 mr-1"></i>
                                    {{ $course->quizzes_count }} Quizzes</span>
                            </div>

                            <a href="{{ route('student.courses.show', $course->id) }}"
                                class="w-full py-2.5 bg-indigo-600 hover:bg-indigo-700 text-white rounded-xl text-xs font-bold transition-colors flex items-center justify-center gap-2">
                                <span>Ingresar al Curso</span>
                                <i class="fas fa-arrow-right text-[10px]"></i>
                            </a>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        <div>
            {{ $courses->links() }}
        </div>
    @else
        <div class="p-12 text-center bg-white dark:bg-gray-900 rounded-2xl border border-gray-200 dark:border-gray-800">
            <i class="fas fa-book-open text-4xl text-gray-300 dark:text-gray-700 mb-3"></i>
            <h3 class="text-sm font-bold text-gray-800 dark:text-gray-200">No se encontraron cursos</h3>
            <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">Actualmente no estás matriculado en ningún curso
                con este criterio.</p>
        </div>
    @endif

</div>

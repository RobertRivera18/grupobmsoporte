@php
    $completedSet = array_flip($completedLessonIds);
@endphp

<div class="p-6 max-w-5xl mx-auto space-y-6">

    {{-- Volver --}}
    <a href="{{ route('student.courses.index') }}"
        class="inline-flex items-center text-xs font-semibold text-gray-500 hover:text-indigo-600 transition-colors">
        <i class="fas fa-arrow-left mr-1.5"></i> Volver a Mis Cursos
    </a>

    {{-- Header del Curso + Barra de Progreso --}}
    <div
        class="bg-white dark:bg-gray-900 rounded-2xl p-6 border border-gray-200/80 dark:border-gray-800 shadow-sm flex flex-col md:flex-row gap-6 items-start">
        @if ($course->image)
            <div
                class="relative flex-shrink-0 w-full sm:w-52 h-36 rounded-2xl overflow-hidden bg-gray-100 dark:bg-gray-800 border border-gray-200/80 dark:border-gray-800 shadow-sm group">
                <img src="{{ asset('storage/' . $course->image) }}" alt="{{ $course->name }}" loading="lazy"
                    class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300 ease-out">
                <div class="absolute inset-0 bg-gradient-to-t from-black/20 via-transparent to-transparent opacity-60">
                </div>
            </div>
        @else
            <div
                class="relative flex-shrink-0 w-full sm:w-52 h-36 rounded-2xl bg-gradient-to-br from-indigo-500/10 via-purple-500/10 to-indigo-600/20 dark:from-indigo-900/30 dark:to-purple-900/20 border border-indigo-100 dark:border-indigo-900/50 flex flex-col items-center justify-center gap-2 text-indigo-600 dark:text-indigo-400">
                <i class="fas fa-graduation-cap text-3xl"></i>
                <span class="text-[10px] font-bold uppercase tracking-wider">Sin Portada</span>
            </div>
        @endif

        <div class="space-y-4 flex-1 w-full">
            <div>
                <h1 class="text-2xl font-black text-gray-900 dark:text-white">{{ $course->name }}</h1>
                <p class="text-xs text-gray-600 dark:text-gray-400 leading-relaxed mt-1">{{ $course->description }}</p>
            </div>

            {{-- Sección de Progreso Global --}}
            <div
                class="p-3.5 bg-gray-50 dark:bg-gray-800/50 rounded-xl border border-gray-100 dark:border-gray-800/80 space-y-2">
                <div class="flex items-center justify-between text-xs font-semibold">
                    <span class="text-gray-700 dark:text-gray-300 flex items-center gap-1.5">
                        <i class="fas fa-tasks text-indigo-500"></i> Progreso del curso
                    </span>
                    <span class="text-indigo-600 dark:text-indigo-400 font-bold">
                        {{ $progressPercentage }}%
                        <span class="text-gray-400 font-normal">({{ $completedCount }}/{{ $totalLessons }}
                            lecciones)</span>
                    </span>
                </div>
                {{-- Barra de Progreso --}}
                <div class="w-full h-2 bg-gray-200 dark:bg-gray-700 rounded-full overflow-hidden">
                    <div class="h-full bg-gradient-to-r from-indigo-500 to-emerald-500 transition-all duration-500 rounded-full"
                        style="width: {{ $progressPercentage }}%;"></div>
                </div>
            </div>
        </div>
    </div>

    {{-- Lista de Módulos y Lecciones --}}
    <div class="space-y-4">
        <h2 class="text-base font-bold text-gray-900 dark:text-white">Contenido del Curso</h2>

        @forelse ($course->modules as $module)
            @php
                $totalInModule = $module->lessons->count();
                $completedInModule = $module->lessons->filter(fn($l) => isset($completedSet[$l->id]))->count();
            @endphp

            <div x-data="{ open: true }"
                class="bg-white dark:bg-gray-900 rounded-2xl border border-gray-200/80 dark:border-gray-800 overflow-hidden shadow-sm">

                {{-- Encabezado del Módulo --}}
                <button @click="open = !open"
                    class="w-full px-6 py-4 flex items-center justify-between bg-gray-50/50 dark:bg-gray-800/30 text-left border-b border-gray-100 dark:border-gray-800">
                    <div class="flex items-center gap-3">
                        <span
                            class="w-7 h-7 rounded-lg bg-indigo-50 dark:bg-indigo-950 text-indigo-600 dark:text-indigo-400 font-bold text-xs flex items-center justify-center border border-indigo-100 dark:border-indigo-900">
                            {{ $loop->iteration }}
                        </span>
                        <div>
                            <h3 class="text-sm font-bold text-gray-900 dark:text-white">{{ $module->name }}</h3>
                            <span class="text-[10px] text-gray-500 font-medium">
                                {{ $completedInModule }} de {{ $totalInModule }} lecciones completadas
                            </span>
                        </div>
                    </div>
                    <i class="fas fa-chevron-down text-xs text-gray-400 transition-transform duration-200"
                        :class="{ 'rotate-180': open }"></i>
                </button>

                {{-- Lecciones del Módulo --}}
                <div x-show="open" class="divide-y divide-gray-100 dark:divide-gray-800/60">
                    @forelse ($module->lessons as $lesson)
                        @php
                            $isCompleted = isset($completedSet[$lesson->id]);
                        @endphp
                        <a href="{{ route('student.lessons.show', [$course->id, $lesson->id]) }}"
                            class="px-6 py-3.5 flex items-center justify-between hover:bg-gray-50/80 dark:hover:bg-gray-800/40 transition-colors group">

                            <div class="flex items-center gap-3">
                                @if ($isCompleted)
                                    <i class="fas fa-check-circle text-emerald-500 text-sm"></i>
                                @else
                                    <i
                                        class="far fa-play-circle text-indigo-500 group-hover:scale-110 transition-transform text-sm"></i>
                                @endif

                                <span
                                    class="text-xs font-semibold {{ $isCompleted ? 'text-gray-500 line-through dark:text-gray-400' : 'text-gray-700 dark:text-gray-300' }} group-hover:text-indigo-600 dark:group-hover:text-indigo-400">
                                    {{ $lesson->title }}
                                </span>
                            </div>

                            <div class="flex items-center gap-2">
                                @if ($isCompleted)
                                    <span
                                        class="text-[10px] font-bold text-emerald-600 dark:text-emerald-400 bg-emerald-50 dark:bg-emerald-950/60 px-2 py-0.5 rounded-md border border-emerald-200/50 dark:border-emerald-900">
                                        Completada
                                    </span>
                                @endif

                                <span
                                    class="text-[10px] font-bold text-indigo-600 dark:text-indigo-400 opacity-0 group-hover:opacity-100 transition-opacity">
                                    Ver lección <i class="fas fa-chevron-right ml-1"></i>
                                </span>
                            </div>
                        </a>
                    @empty
                        <div class="p-4 text-center text-xs text-gray-400">
                            No hay lecciones disponibles en este módulo.
                        </div>
                    @endforelse
                </div>

            </div>
        @empty
            <div
                class="p-8 text-center bg-white dark:bg-gray-900 rounded-2xl border border-gray-200 dark:border-gray-800 text-xs text-gray-500">
                Este curso aún no tiene módulos publicados.
            </div>
        @endforelse

        {{-- Evaluaciones y Quizzes Generales del Curso --}}
        @if ($course->quizzes->isNotEmpty())
            <div
                class="bg-white dark:bg-gray-900 rounded-2xl border border-gray-200/80 dark:border-gray-800 overflow-hidden shadow-sm p-6 space-y-4">
                <h3 class="text-sm font-bold text-gray-900 dark:text-white flex items-center gap-2">
                    <i class="fas fa-file-signature text-amber-500"></i> Evaluaciones y Quizzes
                </h3>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                    @foreach ($course->quizzes as $quiz)
                        @php
                            $attempts = $quizAttempts->get($quiz->id, collect());
                            $attemptsCount = $attempts->count();

                            $bestAttempt = $attempts->sortByDesc('percentage')->first();
                            $isPassed = $bestAttempt ? (bool) $bestAttempt->passed : false;
                            $highestPercentage = $bestAttempt ? number_format($bestAttempt->percentage, 2) : 0;
                        @endphp

                        <a href="{{ route('student.quizzes.show', [$course->id, $quiz->id]) }}"
                            class="p-4 rounded-xl bg-gray-50 dark:bg-gray-800/40 border border-gray-200/60 dark:border-gray-800 hover:border-amber-400 transition-colors flex items-center justify-between group">

                            <div class="space-y-1 pr-2">
                                <h4
                                    class="text-xs font-bold text-gray-800 dark:text-gray-200 group-hover:text-amber-600 dark:group-hover:text-amber-400 line-clamp-1">
                                    Evaluación: {{ $quiz->title }}
                                </h4>

                                <div class="flex items-center flex-wrap gap-1.5 text-[11px]">
                                    {{-- Indicador de Intentos Permitidos --}}
                                    <span class="text-gray-400 font-medium">
                                        @if (!$quiz->max_attempts || $quiz->max_attempts == 0)
                                            Sin límite
                                        @else
                                            {{ $attemptsCount }} / {{ $quiz->max_attempts }}
                                            {{ Str::plural('intento', $quiz->max_attempts) }}
                                        @endif
                                    </span>

                                    {{-- Nota más alta / Estado de aprobación --}}
                                    @if ($attemptsCount > 0)
                                        <span class="text-gray-300 dark:text-gray-600">•</span>
                                        @if ($isPassed)
                                            <span
                                                class="font-bold text-emerald-600 dark:text-emerald-400 flex items-center gap-1">
                                                <i class="fas fa-check-circle text-[10px]"></i> Aprobado
                                                ({{ $highestPercentage }}%)
                                            </span>
                                        @else
                                            <span
                                                class="font-bold text-rose-500 dark:text-rose-400 flex items-center gap-1">
                                                <i class="fas fa-times-circle text-[10px]"></i> Reprobado
                                                ({{ $highestPercentage }}%)
                                            </span>
                                        @endif
                                    @endif
                                </div>
                            </div>

                            <i class="fas fa-arrow-right text-xs text-gray-400 group-hover:text-amber-500 shrink-0"></i>
                        </a>
                    @endforeach
                </div>
            </div>
        @endif

    </div>

</div>

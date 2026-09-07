<div class="p-4 sm:p-6 lg:p-8 max-w-4xl mx-auto space-y-6">

    {{-- Encabezado --}}
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 pb-4 border-b border-gray-200 dark:border-gray-800">
        <div>
            <div class="flex items-center gap-2 text-xs text-gray-500 dark:text-gray-400 mb-1">
                <a href="{{ route('student.courses.show', $course->id) }}" class="hover:text-indigo-600 transition-colors">
                    {{ $course->title ?? $course->name }}
                </a>
                <i class="fas fa-chevron-right text-[10px]"></i>
                <span>Evaluación</span>
            </div>
            <h1 class="text-2xl font-bold text-gray-900 dark:text-white">{{ $quiz->title }}</h1>
        </div>
    </div>

    {{-- Alertas de Flash --}}
    @if (session()->has('error'))
        <div class="p-4 bg-rose-50 dark:bg-rose-950/60 text-rose-600 dark:text-rose-400 rounded-xl text-sm border border-rose-200 dark:border-rose-800">
            <i class="fas fa-exclamation-circle mr-2"></i>{{ session('error') }}
        </div>
    @endif

    {{-- Pantalla de Resultados y Resumen de Intentos --}}
    @if ($isFinished && !$showReview)
        <div class="bg-white dark:bg-gray-900 rounded-2xl p-6 sm:p-8 border border-gray-200 dark:border-gray-800 space-y-6 shadow-sm">
            
            {{-- Resumen de Nota Prevaleciente --}}
            <div class="text-center space-y-4">
                <div class="inline-flex items-center justify-center w-16 h-16 rounded-full {{ $passed ? 'bg-emerald-100 text-emerald-600 dark:bg-emerald-950/60 dark:text-emerald-400' : 'bg-rose-100 text-rose-600 dark:bg-rose-950/60 dark:text-rose-400' }}">
                    <i class="fas {{ $passed ? 'fa-award' : 'fa-circle-xmark' }} text-3xl"></i>
                </div>

                <div>
                    <h2 class="text-xl font-bold text-gray-900 dark:text-white">
                        {{ $passed ? '¡Felicidades! Has aprobado.' : 'Has completado la evaluación.' }}
                    </h2>
                    <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">
                        Nota Prevaleciente (Calificación Máxima): 
                        <span class="font-bold text-sm text-gray-900 dark:text-white">{{ $percentage }}%</span> ({{ $score }} pts)
                    </p>
                </div>

                <div class="flex items-center justify-center gap-3 pt-2">
                    <button type="button" wire:click="toggleReview"
                            class="px-4 py-2 text-xs font-semibold rounded-xl bg-indigo-50 dark:bg-indigo-950/50 text-indigo-600 dark:text-indigo-400 hover:bg-indigo-100 transition-colors">
                        <i class="fas fa-eye mr-1"></i> Revisar Último Intento
                    </button>

                    @if (!$quiz->max_attempts || $attempts->count() < $quiz->max_attempts)
                        <button type="button" wire:click="retry"
                                class="px-4 py-2 text-xs font-semibold rounded-xl bg-indigo-600 text-white hover:bg-indigo-500 transition-colors shadow-sm">
                            <i class="fas fa-rotate-right mr-1"></i> Realizar Nuevo Intento
                        </button>
                    @endif
                </div>
            </div>

            {{-- Historial de Intentos --}}
            <div class="pt-6 border-t border-gray-100 dark:border-gray-800">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-sm font-bold text-gray-900 dark:text-white">Historial de Intentos</h3>
                    <span class="text-xs text-gray-500 dark:text-gray-400 font-medium">
                        Intentos realizados: {{ $attempts->count() }} @if($quiz->max_attempts) / {{ $quiz->max_attempts }} @endif
                    </span>
                </div>

                <div class="overflow-x-auto">
                    <table class="w-full text-left text-xs">
                        <thead>
                            <tr class="border-b border-gray-200 dark:border-gray-800 text-gray-500 dark:text-gray-400">
                                <th class="pb-2 font-medium">Intento</th>
                                <th class="pb-2 font-medium">Fecha</th>
                                <th class="pb-2 font-medium text-center">Calificación</th>
                                <th class="pb-2 font-medium text-center">Estado</th>
                                <th class="pb-2 font-medium text-right">Acción</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100 dark:divide-gray-800">
                            @foreach ($attempts as $index => $att)
                                @php
                                    $isBest = (float)$att->percentage === $percentage;
                                @endphp
                                <tr>
                                    <td class="py-3 font-semibold text-gray-900 dark:text-white">
                                        Intento #{{ $att->attempt_number ?? ($index + 1) }}
                                        @if($isBest)
                                            <span class="ml-1 text-[10px] font-medium bg-amber-100 text-amber-800 dark:bg-amber-950/60 dark:text-amber-400 px-1.5 py-0.5 rounded">Nota Más Alta</span>
                                        @endif
                                    </td>
                                    <td class="py-3 text-gray-500 dark:text-gray-400">
                                        {{ $att->created_at ? $att->created_at->format('d/m/Y - H:i') : '-' }}
                                    </td>
                                    <td class="py-3 text-center font-bold text-gray-900 dark:text-white">
                                        {{ (float)$att->percentage }}%
                                    </td>
                                    <td class="py-3 text-center">
                                        <span class="px-2 py-0.5 rounded-full text-[10px] font-semibold {{ $att->passed ? 'bg-emerald-100 text-emerald-700 dark:bg-emerald-950/60 dark:text-emerald-400' : 'bg-rose-100 text-rose-700 dark:bg-rose-950/60 dark:text-rose-400' }}">
                                            {{ $att->passed ? 'Aprobado' : 'No Aprobado' }}
                                        </span>
                                    </td>
                                    <td class="py-3 text-right">
                                        <button type="button" wire:click="reviewAttempt({{ $att->id }})" class="text-indigo-600 dark:text-indigo-400 hover:underline font-semibold">
                                            Revisar
                                        </button>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>

        </div>
    @else
        {{-- Modo de Respuesta o Revisión --}}
        @php
            $currentQuestion = $quiz->questions[$currentQuestionIndex] ?? null;
        @endphp

        @if ($currentQuestion)
            @php
                $qType = $currentQuestion->question_type instanceof \BackedEnum
                    ? $currentQuestion->question_type->value
                    : $currentQuestion->question_type;

                $isMultiple = in_array($qType, ['multiple_choice', 'multiple_select', 'checkbox']);
                $userAnswer = $answers[$currentQuestion->id] ?? null;
            @endphp

            <div class="bg-white dark:bg-gray-900 rounded-2xl border border-gray-200 dark:border-gray-800 p-6 space-y-6 shadow-sm">

                {{-- Encabezado de la pregunta --}}
                <div class="flex items-center justify-between pb-4 border-b border-gray-100 dark:border-gray-800">
                    <span class="text-xs font-semibold text-indigo-600 dark:text-indigo-400 bg-indigo-50 dark:bg-indigo-950/50 px-2.5 py-1 rounded-lg">
                        Pregunta {{ $currentQuestionIndex + 1 }} de {{ $quiz->questions->count() }}
                    </span>
                    <span class="text-xs text-gray-400 font-medium">
                        {{ $isMultiple ? 'Selección Múltiple' : 'Selección Única' }} • {{ $currentQuestion->pivot->points ?? ($currentQuestion->points ?? 1) }} Ptos
                    </span>
                </div>

                {{-- Enunciado --}}
                <div class="text-base font-semibold text-gray-900 dark:text-white">
                    {{ $currentQuestion->question }}
                </div>

                {{-- Opciones de respuesta --}}
                <div class="space-y-2.5">
                    @foreach ($currentQuestion->options as $option)
                        @php
                            $isSelected = false;
                            if ($isMultiple) {
                                $isSelected = is_array($userAnswer) && in_array($option->id, $userAnswer);
                            } else {
                                $isSelected = !is_null($userAnswer) && (int) $userAnswer === (int) $option->id;
                            }
                        @endphp

                        <div @if (!$isFinished) wire:click="selectOption({{ $currentQuestion->id }}, {{ $option->id }})" @endif
                             class="flex items-center gap-3 p-3.5 rounded-xl border transition-all 
                             {{ $isFinished ? 'cursor-default' : 'cursor-pointer' }} 
                             {{ $isSelected ? 'border-indigo-600 bg-indigo-50/40 dark:bg-indigo-950/20 dark:border-indigo-500' : 'border-gray-200 dark:border-gray-800 hover:border-gray-300 dark:hover:border-gray-700' }}">

                            @if ($isMultiple)
                                <input type="checkbox" @checked($isSelected) @disabled($isFinished)
                                       class="rounded text-indigo-600 focus:ring-indigo-500 h-4 w-4 pointer-events-none disabled:opacity-80">
                            @else
                                <input type="radio" name="q_{{ $currentQuestion->id }}" @checked($isSelected) @disabled($isFinished)
                                       class="text-indigo-600 focus:ring-indigo-500 h-4 w-4 pointer-events-none disabled:opacity-80">
                            @endif

                            <span class="text-sm font-medium text-gray-800 dark:text-gray-200">
                                {{ $option->option }}
                            </span>
                        </div>
                    @endforeach
                </div>

                {{-- Explicación (Solo en revisión) --}}
                @if ($showReview && !empty($currentQuestion->explanation))
                    <div class="p-3.5 rounded-xl bg-amber-50 dark:bg-amber-950/30 border border-amber-200 dark:border-amber-900/50 text-xs text-amber-800 dark:text-amber-300">
                        <i class="fas fa-lightbulb mr-1"></i> <strong>Explicación:</strong> {{ $currentQuestion->explanation }}
                    </div>
                @endif

                {{-- Navegación --}}
                <div class="flex items-center justify-between pt-4 border-t border-gray-100 dark:border-gray-800">
                    <button type="button" 
                            wire:click="previousQuestion" 
                            @disabled($currentQuestionIndex === 0)
                            class="px-4 py-2 text-xs font-semibold rounded-xl border border-gray-300 dark:border-gray-700 text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-800 disabled:opacity-40">
                        <i class="fas fa-arrow-left mr-1"></i> Anterior
                    </button>

                    <div class="flex items-center gap-2">
                        @if ($currentQuestionIndex < $quiz->questions->count() - 1)
                            <button type="button" 
                                    wire:click="nextQuestion"
                                    class="px-4 py-2 text-xs font-semibold rounded-xl bg-indigo-600 text-white hover:bg-indigo-500 transition-colors">
                                Siguiente <i class="fas fa-arrow-right ml-1"></i>
                            </button>
                        @else
                            @if ($isFinished)
                                <button type="button" 
                                        wire:click="closeReview"
                                        class="px-5 py-2 text-xs font-semibold rounded-xl bg-gray-800 dark:bg-gray-700 text-white hover:bg-gray-700 transition-colors shadow-sm">
                                    <i class="fas fa-xmark mr-1"></i> Cerrar Revisión
                                </button>
                            @else
                                <button type="button" 
                                        wire:click="submitQuiz"
                                        class="px-5 py-2 text-xs font-semibold rounded-xl bg-emerald-600 text-white hover:bg-emerald-500 transition-colors shadow-sm">
                                    <i class="fas fa-paper-plane mr-1"></i> Finalizar Cuestionario
                                </button>
                            @endif
                        @endif
                    </div>
                </div>

            </div>
        @endif
    @endif

</div>
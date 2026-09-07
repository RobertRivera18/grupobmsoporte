<div class="p-4 sm:p-6 lg:p-8 max-w-7xl mx-auto space-y-6">

    {{-- ========================================================= --}}
    {{-- HEADER --}}
    {{-- ========================================================= --}}

    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 pb-2 border-b border-gray-200 dark:border-gray-800">

        <div>
            <div class="flex items-center gap-2 text-xs text-gray-500 dark:text-gray-400 mb-1">

                <a href="{{ route('admin.capacitacion.courses.show', $module->course_id) }}"
                    class="hover:text-indigo-600 transition-colors">
                    Volver al Curso
                </a>

                <i class="fas fa-chevron-right text-[10px]"></i>

                <span>{{ $module->name }}</span>

            </div>

            <h1 class="text-2xl sm:text-3xl font-bold tracking-tight text-gray-900 dark:text-white">
                Gestor de Cuestionario
            </h1>

            <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">
                Crea preguntas nuevas o utiliza preguntas existentes del banco.
            </p>
        </div>

    </div>


    {{-- ========================================================= --}}
    {{-- MENSAJE --}}
    {{-- ========================================================= --}}

    @if (session()->has('message'))

        <div class="p-4 bg-emerald-50 dark:bg-emerald-950/60
                    text-emerald-600 dark:text-emerald-400
                    rounded-xl text-sm
                    border border-emerald-200 dark:border-emerald-800">

            <i class="fas fa-check-circle mr-1"></i>

            {{ session('message') }}

        </div>

    @endif


    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">


        {{-- ========================================================= --}}
        {{-- CONFIGURACIÓN DEL QUIZ --}}
        {{-- ========================================================= --}}

        <div class="lg:col-span-1 space-y-6">

            <div class="bg-white dark:bg-gray-900
                        rounded-2xl shadow-sm
                        border border-gray-200/80 dark:border-gray-800
                        p-6 space-y-4">

                <h3 class="text-lg font-bold text-gray-900 dark:text-white flex items-center gap-2">

                    <i class="fas fa-sliders text-indigo-500"></i>

                    Ajustes del Quiz

                </h3>


                <form wire:submit="saveQuizInfo" class="space-y-4">

                    {{-- TÍTULO --}}
                    <div>

                        <label class="block text-xs font-semibold text-gray-700 dark:text-gray-300 mb-1">
                            Título
                        </label>

                        <input
                            type="text"
                            wire:model="title"
                            placeholder="Ej: Evaluación Módulo 1"
                            class="w-full px-3 py-2 text-sm rounded-xl
                                   border border-gray-300 dark:border-gray-700
                                   dark:bg-gray-800 dark:text-white
                                   focus:ring-2 focus:ring-indigo-500">

                        @error('title')
                            <span class="text-xs text-rose-500 mt-1">
                                {{ $message }}
                            </span>
                        @enderror

                    </div>


                    {{-- TIEMPO / APROBACIÓN --}}
                    <div class="grid grid-cols-2 gap-3">

                        <div>

                            <label class="block text-xs font-semibold text-gray-700 dark:text-gray-300 mb-1">
                                Límite (minutos)
                            </label>

                            <input
                                type="number"
                                min="0"
                                wire:model="time_limit"
                                placeholder="0 = Sin límite"
                                class="w-full px-3 py-2 text-sm rounded-xl
                                       border border-gray-300 dark:border-gray-700
                                       dark:bg-gray-800 dark:text-white
                                       focus:ring-2 focus:ring-indigo-500">

                            @error('time_limit')
                                <span class="text-xs text-rose-500">
                                    {{ $message }}
                                </span>
                            @enderror

                        </div>


                        <div>

                            <label class="block text-xs font-semibold text-gray-700 dark:text-gray-300 mb-1">
                                Aprobación (%)
                            </label>

                            <input
                                type="number"
                                min="1"
                                max="100"
                                wire:model="passing_score"
                                class="w-full px-3 py-2 text-sm rounded-xl
                                       border border-gray-300 dark:border-gray-700
                                       dark:bg-gray-800 dark:text-white
                                       focus:ring-2 focus:ring-indigo-500">

                            @error('passing_score')
                                <span class="text-xs text-rose-500">
                                    {{ $message }}
                                </span>
                            @enderror

                        </div>

                    </div>


                    {{-- INTENTOS --}}
                    <div>

                        <label class="block text-xs font-semibold text-gray-700 dark:text-gray-300 mb-1">
                            Intentos permitidos
                        </label>

                        <input
                            type="number"
                            min="1"
                            wire:model="max_attempts"
                            class="w-full px-3 py-2 text-sm rounded-xl
                                   border border-gray-300 dark:border-gray-700
                                   dark:bg-gray-800 dark:text-white
                                   focus:ring-2 focus:ring-indigo-500">

                        @error('max_attempts')
                            <span class="text-xs text-rose-500">
                                {{ $message }}
                            </span>
                        @enderror

                    </div>


                    {{-- OPCIONES --}}
                    <div class="space-y-2 pt-2 border-t border-gray-100 dark:border-gray-800">

                        <label class="flex items-center gap-2 text-xs font-medium text-gray-700 dark:text-gray-300">

                            <input
                                type="checkbox"
                                wire:model="random_questions"
                                class="rounded text-indigo-600 focus:ring-indigo-500">

                            Preguntas aleatorias

                        </label>


                        <label class="flex items-center gap-2 text-xs font-medium text-gray-700 dark:text-gray-300">

                            <input
                                type="checkbox"
                                wire:model="random_options"
                                class="rounded text-indigo-600 focus:ring-indigo-500">

                            Opciones aleatorias

                        </label>


                        <label class="flex items-center gap-2 text-xs font-medium text-gray-700 dark:text-gray-300">

                            <input
                                type="checkbox"
                                wire:model="show_results"
                                class="rounded text-indigo-600 focus:ring-indigo-500">

                            Mostrar respuestas al finalizar

                        </label>

                    </div>


                    <button
                        type="submit"
                        class="w-full py-2.5 text-xs font-semibold
                               text-white bg-indigo-600 hover:bg-indigo-500
                               rounded-xl transition-colors">

                        <i class="fas fa-save mr-1"></i>

                        Guardar Ajustes

                    </button>

                </form>

            </div>

        </div>


        {{-- ========================================================= --}}
        {{-- CONTENIDO --}}
        {{-- ========================================================= --}}

        <div class="lg:col-span-2 space-y-6">


            {{-- ===================================================== --}}
            {{-- ACCIONES --}}
            {{-- ===================================================== --}}

            <div class="bg-white dark:bg-gray-900
                        rounded-2xl shadow-sm
                        border border-gray-200/80 dark:border-gray-800
                        p-4">

                <div class="flex flex-col sm:flex-row gap-3">

                    {{-- CREAR NUEVA --}}
                    <button
                        type="button"
                        wire:click="resetQuestionForm"
                        class="flex-1 inline-flex items-center justify-center gap-2
                               px-4 py-3 rounded-xl
                               text-sm font-semibold
                               text-white bg-emerald-600
                               hover:bg-emerald-500 transition">

                        <i class="fas fa-plus"></i>

                        Crear nueva pregunta

                    </button>


                    {{-- BANCO --}}
                    <button
                        type="button"
                        wire:click="openQuestionBank"
                        class="flex-1 inline-flex items-center justify-center gap-2
                               px-4 py-3 rounded-xl
                               text-sm font-semibold
                               text-indigo-700 dark:text-indigo-300
                               bg-indigo-50 dark:bg-indigo-950/40
                               border border-indigo-200 dark:border-indigo-900
                               hover:bg-indigo-100 dark:hover:bg-indigo-950/70
                               transition">

                        <i class="fas fa-database"></i>

                        Buscar en banco de preguntas

                    </button>

                </div>

            </div>


            {{-- ===================================================== --}}
            {{-- FORMULARIO DE PREGUNTA --}}
            {{-- ===================================================== --}}

            <div class="bg-white dark:bg-gray-900
                        rounded-2xl shadow-sm
                        border border-gray-200/80 dark:border-gray-800
                        p-6 space-y-5">


                <div class="flex items-center justify-between pb-3 border-b border-gray-100 dark:border-gray-800">

                    <div>

                        <h3 class="text-lg font-bold text-gray-900 dark:text-white">

                            {{ $editingQuestionId ? 'Editar Pregunta' : 'Crear Pregunta' }}

                        </h3>

                        <p class="text-xs text-gray-400 mt-1">

                            Puedes crear una pregunta nueva y almacenarla en el banco.

                        </p>

                    </div>


                    @if ($editingQuestionId)

                        <button
                            type="button"
                            wire:click="resetQuestionForm"
                            class="text-xs text-rose-500 hover:underline">

                            Cancelar edición

                        </button>

                    @endif

                </div>


                <form wire:submit="saveQuestion" class="space-y-5">


                    {{-- ================================================= --}}
                    {{-- PREGUNTA + PUNTOS --}}
                    {{-- ================================================= --}}

                    <div class="grid grid-cols-1 sm:grid-cols-4 gap-3">

                        <div class="sm:col-span-3">

                            <label class="block text-xs font-semibold text-gray-700 dark:text-gray-300 mb-1">
                                Pregunta
                            </label>

                            <textarea
                                wire:model="question_text"
                                rows="3"
                                placeholder="Escribe el enunciado de la pregunta..."
                                class="w-full px-3 py-2 text-sm rounded-xl
                                       border border-gray-300 dark:border-gray-700
                                       dark:bg-gray-800 dark:text-white
                                       focus:ring-2 focus:ring-indigo-500"></textarea>

                            @error('question_text')
                                <span class="text-xs text-rose-500">
                                    {{ $message }}
                                </span>
                            @enderror

                        </div>


                        <div>

                            <label class="block text-xs font-semibold text-gray-700 dark:text-gray-300 mb-1">
                                Puntos
                            </label>

                            <input
                                type="number"
                                step="0.5"
                                min="0.5"
                                wire:model="question_points"
                                class="w-full px-3 py-2 text-sm rounded-xl
                                       border border-gray-300 dark:border-gray-700
                                       dark:bg-gray-800 dark:text-white
                                       focus:ring-2 focus:ring-indigo-500">

                            @error('question_points')
                                <span class="text-xs text-rose-500">
                                    {{ $message }}
                                </span>
                            @enderror

                        </div>

                    </div>


                    {{-- ================================================= --}}
                    {{-- CATEGORÍA + DIFICULTAD --}}
                    {{-- ================================================= --}}

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">


                        {{-- CATEGORÍA --}}
                        <div>

                            <label class="block text-xs font-semibold text-gray-700 dark:text-gray-300 mb-1">

                                Categoría

                            </label>

                            <input
                                type="text"
                                wire:model="category"
                                placeholder="Ej: Seguridad Industrial"
                                class="w-full px-3 py-2 text-sm rounded-xl
                                       border border-gray-300 dark:border-gray-700
                                       dark:bg-gray-800 dark:text-white
                                       focus:ring-2 focus:ring-indigo-500">

                            @error('category')
                                <span class="text-xs text-rose-500">
                                    {{ $message }}
                                </span>
                            @enderror

                        </div>


                        {{-- DIFICULTAD --}}
                        <div>

                            <label class="block text-xs font-semibold text-gray-700 dark:text-gray-300 mb-1">

                                Dificultad

                            </label>

                            <select
                                wire:model="difficulty"
                                class="w-full px-3 py-2 text-sm rounded-xl
                                       border border-gray-300 dark:border-gray-700
                                       dark:bg-gray-800 dark:text-white
                                       focus:ring-2 focus:ring-indigo-500">

                                <option value="easy">
                                    Fácil
                                </option>

                                <option value="medium">
                                    Media
                                </option>

                                <option value="hard">
                                    Difícil
                                </option>

                            </select>

                            @error('difficulty')
                                <span class="text-xs text-rose-500">
                                    {{ $message }}
                                </span>
                            @enderror

                        </div>

                    </div>


                    {{-- ================================================= --}}
                    {{-- TIPO --}}
                    {{-- ================================================= --}}

                    <div>

                        <label class="block text-xs font-semibold text-gray-700 dark:text-gray-300 mb-1">

                            Tipo de pregunta

                        </label>

                        <select
                            wire:model.live="question_type"
                            class="w-full px-3 py-2 text-sm rounded-xl
                                   border border-gray-300 dark:border-gray-700
                                   dark:bg-gray-800 dark:text-white
                                   focus:ring-2 focus:ring-indigo-500">

                            <option value="single_choice">
                                Opción única
                            </option>

                            <option value="multiple_choice">
                                Opción múltiple
                            </option>

                            <option value="true_false">
                                Verdadero / Falso
                            </option>

                            <option value="short_answer">
                                Respuesta corta
                            </option>

                            <option value="long_answer">
                                Respuesta larga
                            </option>

                            <option value="numeric">
                                Respuesta numérica
                            </option>

                        </select>

                        @error('question_type')
                            <span class="text-xs text-rose-500">
                                {{ $message }}
                            </span>
                        @enderror

                    </div>


                    {{-- ================================================= --}}
                    {{-- AYUDA --}}
                    {{-- ================================================= --}}

                    @if ($question_type === 'single_choice')

                        <div class="p-3 rounded-xl bg-indigo-50 dark:bg-indigo-950/30
                                    border border-indigo-100 dark:border-indigo-900/50">

                            <p class="text-xs text-indigo-700 dark:text-indigo-300">

                                <i class="fas fa-circle-info mr-1"></i>

                                Selecciona exactamente una respuesta correcta.

                            </p>

                        </div>

                    @elseif($question_type === 'multiple_choice')

                        <div class="p-3 rounded-xl bg-indigo-50 dark:bg-indigo-950/30
                                    border border-indigo-100 dark:border-indigo-900/50">

                            <p class="text-xs text-indigo-700 dark:text-indigo-300">

                                <i class="fas fa-circle-info mr-1"></i>

                                Puedes seleccionar varias respuestas correctas.

                            </p>

                        </div>

                    @elseif($question_type === 'short_answer')

                        <div class="p-3 rounded-xl bg-amber-50 dark:bg-amber-950/30
                                    border border-amber-100 dark:border-amber-900/50">

                            <p class="text-xs text-amber-700 dark:text-amber-300">

                                <i class="fas fa-circle-info mr-1"></i>

                                Agrega todas las respuestas que serán consideradas correctas.

                            </p>

                        </div>

                    @elseif($question_type === 'long_answer')

                        <div class="p-3 rounded-xl bg-purple-50 dark:bg-purple-950/30
                                    border border-purple-100 dark:border-purple-900/50">

                            <p class="text-xs text-purple-700 dark:text-purple-300">

                                <i class="fas fa-user-pen mr-1"></i>

                                Esta pregunta será respondida mediante texto y deberá ser calificada manualmente.

                            </p>

                        </div>

                    @elseif($question_type === 'numeric')

                        <div class="p-3 rounded-xl bg-cyan-50 dark:bg-cyan-950/30
                                    border border-cyan-100 dark:border-cyan-900/50">

                            <p class="text-xs text-cyan-700 dark:text-cyan-300">

                                <i class="fas fa-calculator mr-1"></i>

                                Introduce el valor numérico exacto que será considerado correcto.

                            </p>

                        </div>

                    @endif


                    {{-- ================================================= --}}
                    {{-- OPCIONES --}}
                    {{-- ================================================= --}}

                    @if ($question_type !== 'long_answer')

                        <div class="space-y-2">

                            <div class="flex items-center justify-between">

                                <label class="block text-xs font-semibold text-gray-700 dark:text-gray-300">

                                    @if ($question_type === 'short_answer')
                                        Respuestas aceptadas
                                    @elseif($question_type === 'numeric')
                                        Respuesta correcta
                                    @else
                                        Alternativas
                                    @endif

                                </label>

                            </div>


                            @error('options')
                                <span class="block text-xs text-rose-500 mb-1">
                                    {{ $message }}
                                </span>
                            @enderror


                            @foreach ($options as $index => $option)

                                <div
                                    wire:key="option-{{ $index }}"
                                    class="flex items-center gap-2">


                                    {{-- CORRECTA --}}
                                    @if (in_array($question_type, ['single_choice', 'true_false', 'numeric']))

                                        <input
                                            type="radio"
                                            name="correct_option"
                                            wire:click="setCorrectOption({{ $index }})"
                                            @checked($option['is_correct'])
                                            class="text-indigo-600 focus:ring-indigo-500 h-4 w-4">

                                    @elseif(in_array($question_type, ['multiple_choice', 'short_answer']))

                                        <input
                                            type="checkbox"
                                            wire:click="setCorrectOption({{ $index }})"
                                            @checked($option['is_correct'])
                                            class="rounded text-indigo-600 focus:ring-indigo-500 h-4 w-4">

                                    @endif


                                    {{-- TEXTO --}}
                                    <input
                                        type="{{ $question_type === 'numeric' ? 'number' : 'text' }}"
                                        wire:model="options.{{ $index }}.option"
                                        placeholder="{{ $question_type === 'numeric' ? 'Ej: 25' : 'Opción ' . ($index + 1) }}"
                                        class="flex-1 px-3 py-2 text-sm rounded-xl
                                               border border-gray-300 dark:border-gray-700
                                               dark:bg-gray-800 dark:text-white
                                               focus:ring-2 focus:ring-indigo-500">


                                    {{-- ELIMINAR --}}
                                    @if (!in_array($question_type, ['true_false', 'numeric']) && count($options) > 1)

                                        <button
                                            type="button"
                                            wire:click="removeOption({{ $index }})"
                                            class="p-1.5 text-rose-500 hover:bg-rose-50 rounded-lg">

                                            <i class="fas fa-times text-xs"></i>

                                        </button>

                                    @endif

                                </div>

                            @endforeach


                            {{-- AÑADIR --}}
                            @if (in_array($question_type, ['single_choice', 'multiple_choice', 'short_answer']))

                                <button
                                    type="button"
                                    wire:click="addOption"
                                    class="inline-flex items-center gap-1.5
                                           text-xs font-semibold text-indigo-600
                                           hover:underline pt-1">

                                    <i class="fas fa-plus"></i>

                                    Añadir opción

                                </button>

                            @endif

                        </div>

                    @endif


                    {{-- ================================================= --}}
                    {{-- EXPLICACIÓN --}}
                    {{-- ================================================= --}}

                    <div>

                        <label class="block text-xs font-semibold text-gray-700 dark:text-gray-300 mb-1">

                            Explicación de la respuesta

                            <span class="font-normal text-gray-400">
                                (opcional)
                            </span>

                        </label>

                        <textarea
                            wire:model="explanation"
                            rows="2"
                            placeholder="Explicación que podrá mostrarse al estudiante..."
                            class="w-full px-3 py-2 text-sm rounded-xl
                                   border border-gray-300 dark:border-gray-700
                                   dark:bg-gray-800 dark:text-white
                                   focus:ring-2 focus:ring-indigo-500"></textarea>

                    </div>


                    {{-- ================================================= --}}
                    {{-- GUARDAR --}}
                    {{-- ================================================= --}}

                    <div class="flex justify-end pt-2">

                        <button
                            type="submit"
                            class="inline-flex items-center gap-2
                                   px-5 py-2.5 text-xs font-semibold
                                   text-white bg-emerald-600
                                   hover:bg-emerald-500
                                   rounded-xl transition-colors">

                            <i class="fas {{ $editingQuestionId ? 'fa-save' : 'fa-plus' }}"></i>

                            {{ $editingQuestionId ? 'Actualizar Pregunta' : 'Guardar Pregunta' }}

                        </button>

                    </div>

                </form>

            </div>


            {{-- ========================================================= --}}
            {{-- PREGUNTAS DEL QUIZ --}}
            {{-- ========================================================= --}}

            @if ($quiz && $quiz->questions->isNotEmpty())

                <div class="bg-white dark:bg-gray-900
                            rounded-2xl shadow-sm
                            border border-gray-200/80 dark:border-gray-800
                            p-6 space-y-4">


                    <div class="flex items-center justify-between">

                        <div>

                            <h3 class="text-base font-bold text-gray-900 dark:text-white">

                                Preguntas agregadas

                            </h3>

                            <p class="text-xs text-gray-400 mt-1">

                                {{ $quiz->questions->count() }}

                                {{ Str::plural('pregunta', $quiz->questions->count()) }}

                            </p>

                        </div>


                        <span class="text-xs font-semibold
                                     px-2.5 py-1 rounded-lg
                                     bg-indigo-50 dark:bg-indigo-950/50
                                     text-indigo-600 dark:text-indigo-400">

                            {{ $quiz->questions->sum(fn($question) => (float) $question->pivot->points) }}
                            pts

                        </span>

                    </div>


                    <div class="space-y-3">

                        @foreach ($quiz->questions as $qIndex => $q)

                            <div
                                wire:key="quiz-question-{{ $q->id }}"
                                class="p-4 rounded-xl
                                       border border-gray-200
                                       dark:border-gray-800
                                       bg-gray-50/50
                                       dark:bg-gray-800/20">


                                <div class="flex items-start justify-between gap-4">

                                    <div class="flex-1">

                                        <div class="flex items-center gap-2 mb-1">


                                            {{-- NÚMERO --}}
                                            <span class="flex items-center justify-center
                                                         w-6 h-6 rounded-md
                                                         bg-indigo-100 dark:bg-indigo-950
                                                         text-indigo-600 dark:text-indigo-400
                                                         text-[10px] font-bold">

                                                {{ $qIndex + 1 }}

                                            </span>


                                            {{-- TIPO --}}
                                            <span class="text-[10px] uppercase font-semibold text-gray-400">

                                                {{ match ($q->question_type instanceof \BackedEnum ? $q->question_type->value : $q->question_type) {

                                                    'single_choice' => 'Opción única',
                                                    'multiple_choice' => 'Opción múltiple',
                                                    'true_false' => 'Verdadero / Falso',
                                                    'short_answer' => 'Respuesta corta',
                                                    'long_answer' => 'Respuesta larga',
                                                    'numeric' => 'Numérica',

                                                    default => 'Pregunta',

                                                } }}

                                            </span>


                                            {{-- CATEGORÍA --}}
                                            @if ($q->category)

                                                <span class="text-[10px]
                                                             px-2 py-0.5
                                                             rounded-md
                                                             bg-gray-100 dark:bg-gray-800
                                                             text-gray-500 dark:text-gray-400">

                                                    <i class="fas fa-folder mr-1"></i>

                                                    {{ $q->category }}

                                                </span>

                                            @endif


                                            {{-- DIFICULTAD --}}
                                            @if ($q->difficulty)

                                                <span class="text-[10px]
                                                             px-2 py-0.5
                                                             rounded-md
                                                             bg-gray-100 dark:bg-gray-800
                                                             text-gray-500 dark:text-gray-400">

                                                    {{ match($q->difficulty) {

                                                        'easy' => 'Fácil',
                                                        'medium' => 'Media',
                                                        'hard' => 'Difícil',

                                                        default => ucfirst($q->difficulty)

                                                    } }}

                                                </span>

                                            @endif

                                        </div>


                                        <p class="text-sm font-semibold text-gray-900 dark:text-white">

                                            {{ $q->question }}

                                        </p>


                                        <span class="text-[10px] text-gray-500">

                                            {{ $q->pivot->points }} puntos

                                        </span>

                                    </div>


                                    {{-- ACCIONES --}}
                                    <div class="flex items-center gap-1">

                                        <button
                                            type="button"
                                            wire:click="editQuestion({{ $q->id }})"
                                            class="p-1.5 text-gray-400 hover:text-indigo-600 rounded">

                                            <i class="fas fa-pen text-xs"></i>

                                        </button>


                                        <button
                                            type="button"
                                            wire:click="deleteQuestion({{ $q->id }})"
                                            wire:confirm="¿Desvincular esta pregunta del quiz?"
                                            class="p-1.5 text-gray-400 hover:text-rose-600 rounded">

                                            <i class="fas fa-trash text-xs"></i>

                                        </button>

                                    </div>

                                </div>


                                {{-- OPCIONES --}}
                                @if ($q->options->isNotEmpty())

                                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-2 mt-3">

                                        @foreach ($q->options as $option)

                                            <div
                                                class="text-xs p-2 rounded-lg border
                                                {{ $option->is_correct

                                                    ? 'border-emerald-300
                                                       bg-emerald-50/50
                                                       text-emerald-700
                                                       dark:text-emerald-300
                                                       font-semibold'

                                                    : 'border-gray-200
                                                       dark:border-gray-800
                                                       text-gray-600
                                                       dark:text-gray-400'
                                                }}">

                                                @if ($option->is_correct)

                                                    <i class="fas fa-check-circle mr-1"></i>

                                                @endif

                                                {{ $option->option }}

                                            </div>

                                        @endforeach

                                    </div>

                                @endif

                            </div>

                        @endforeach

                    </div>

                </div>

            @endif

        </div>

    </div>



    {{-- =============================================================== --}}
    {{-- MODAL BANCO DE PREGUNTAS --}}
    {{-- =============================================================== --}}

    @if ($showQuestionBank)

        <div
            class="fixed inset-0 z-50 overflow-y-auto"
            x-data
            x-init="$nextTick(() => document.body.classList.add('overflow-hidden'))"
            x-on:close-bank-modal.window="document.body.classList.remove('overflow-hidden')">

            {{-- FONDO --}}
            <div
                class="fixed inset-0 bg-black/50 backdrop-blur-sm"
                wire:click="closeQuestionBank">
            </div>


            {{-- CONTENIDO --}}
            <div class="relative min-h-screen flex items-center justify-center p-4">

                <div
                    class="relative w-full max-w-5xl
                           bg-white dark:bg-gray-900
                           rounded-2xl shadow-2xl
                           border border-gray-200 dark:border-gray-800
                           overflow-hidden">


                    {{-- HEADER --}}
                    <div class="p-5 border-b border-gray-200 dark:border-gray-800">

                        <div class="flex items-center justify-between">

                            <div>

                                <h2 class="text-lg font-bold text-gray-900 dark:text-white">

                                    <i class="fas fa-database text-indigo-500 mr-2"></i>

                                    Banco de preguntas

                                </h2>

                                <p class="text-xs text-gray-400 mt-1">

                                    Busca preguntas existentes y agrégalas a este quiz.

                                </p>

                            </div>


                            <button
                                type="button"
                                wire:click="closeQuestionBank"
                                class="p-2 text-gray-400 hover:text-gray-700
                                       dark:hover:text-white rounded-lg">

                                <i class="fas fa-times"></i>

                            </button>

                        </div>

                    </div>


                    {{-- FILTROS --}}
                    <div class="p-5 bg-gray-50 dark:bg-gray-800/40
                                border-b border-gray-200 dark:border-gray-800">

                        <div class="grid grid-cols-1 md:grid-cols-4 gap-3">


                            {{-- BUSCAR --}}
                            <div class="md:col-span-2">

                                <label class="block text-[11px] font-semibold
                                              text-gray-600 dark:text-gray-300 mb-1">

                                    Buscar

                                </label>

                                <div class="relative">

                                    <i class="fas fa-search absolute left-3 top-1/2
                                              -translate-y-1/2 text-gray-400 text-xs"></i>

                                    <input
                                        type="text"
                                        wire:model.live.debounce.400ms="bankSearch"
                                        placeholder="Buscar por pregunta..."
                                        class="w-full pl-9 pr-3 py-2 text-sm rounded-xl
                                               border border-gray-300 dark:border-gray-700
                                               dark:bg-gray-900 dark:text-white
                                               focus:ring-2 focus:ring-indigo-500">

                                </div>

                            </div>


                            {{-- CATEGORÍA --}}
                            <div>

                                <label class="block text-[11px] font-semibold
                                              text-gray-600 dark:text-gray-300 mb-1">

                                    Categoría

                                </label>

                                <select
                                    wire:model.live="bankCategory"
                                    class="w-full px-3 py-2 text-sm rounded-xl
                                           border border-gray-300 dark:border-gray-700
                                           dark:bg-gray-900 dark:text-white
                                           focus:ring-2 focus:ring-indigo-500">

                                    <option value="">
                                        Todas
                                    </option>

                                    @foreach ($this->questionCategories as $cat)

                                        <option value="{{ $cat }}">
                                            {{ $cat }}
                                        </option>

                                    @endforeach

                                </select>

                            </div>


                            {{-- TIPO --}}
                            <div>

                                <label class="block text-[11px] font-semibold
                                              text-gray-600 dark:text-gray-300 mb-1">

                                    Tipo

                                </label>

                                <select
                                    wire:model.live="bankType"
                                    class="w-full px-3 py-2 text-sm rounded-xl
                                           border border-gray-300 dark:border-gray-700
                                           dark:bg-gray-900 dark:text-white
                                           focus:ring-2 focus:ring-indigo-500">

                                    <option value="">
                                        Todos
                                    </option>

                                    @foreach ($this->questionTypes as $type)

                                        <option value="{{ $type->value }}">
                                            {{ $type->label() }}
                                        </option>

                                    @endforeach

                                </select>

                            </div>

                        </div>


                        {{-- DIFICULTAD --}}
                        <div class="mt-3 w-full md:w-1/4">

                            <label class="block text-[11px] font-semibold
                                          text-gray-600 dark:text-gray-300 mb-1">

                                Dificultad

                            </label>

                            <select
                                wire:model.live="bankDifficulty"
                                class="w-full px-3 py-2 text-sm rounded-xl
                                       border border-gray-300 dark:border-gray-700
                                       dark:bg-gray-900 dark:text-white
                                       focus:ring-2 focus:ring-indigo-500">

                                <option value="">
                                    Todas
                                </option>

                                <option value="easy">
                                    Fácil
                                </option>

                                <option value="medium">
                                    Media
                                </option>

                                <option value="hard">
                                    Difícil
                                </option>

                            </select>

                        </div>

                    </div>


                    {{-- PREGUNTAS --}}
                    <div class="p-5 max-h-[55vh] overflow-y-auto">

                        @if ($this->bankQuestions->isNotEmpty())

                            <div class="space-y-2">

                                @foreach ($this->bankQuestions as $question)

                                    @php
                                        $this->questionTypes = $question->question_type instanceof \BackedEnum
                                            ? $question->question_type->value
                                            : $question->question_type;

                                        $isSelected = in_array(
                                            $question->id,
                                            $selectedBankQuestions
                                        );
                                    @endphp


                                    <button
                                        type="button"
                                        wire:click="toggleBankQuestion({{ $question->id }})"
                                        wire:key="bank-question-{{ $question->id }}"
                                        class="w-full text-left p-4 rounded-xl border transition
                                        {{ $isSelected

                                            ? 'border-indigo-400 bg-indigo-50 dark:bg-indigo-950/30'

                                            : 'border-gray-200 dark:border-gray-800
                                               hover:border-indigo-300
                                               bg-white dark:bg-gray-900'
                                        }}">

                                        <div class="flex items-start gap-3">


                                            {{-- CHECK --}}
                                            <div class="pt-0.5">

                                                <div class="w-5 h-5 rounded-md border
                                                    flex items-center justify-center

                                                    {{ $isSelected

                                                        ? 'bg-indigo-600 border-indigo-600 text-white'

                                                        : 'border-gray-300 dark:border-gray-600'
                                                    }}">

                                                    @if ($isSelected)

                                                        <i class="fas fa-check text-[10px]"></i>

                                                    @endif

                                                </div>

                                            </div>


                                            {{-- CONTENIDO --}}
                                            <div class="flex-1 min-w-0">

                                                <div class="flex flex-wrap items-center gap-2 mb-1">

                                                    <span class="text-[10px] uppercase
                                                                 font-semibold text-gray-400">

                                                        {{ match($this->questionTypes) {

                                                            'single_choice' => 'Opción única',
                                                            'multiple_choice' => 'Opción múltiple',
                                                            'true_false' => 'Verdadero / Falso',
                                                            'short_answer' => 'Respuesta corta',
                                                            'long_answer' => 'Respuesta larga',
                                                            'numeric' => 'Numérica',

                                                            default => 'Pregunta'

                                                        } }}

                                                    </span>


                                                    @if ($question->category)

                                                        <span class="text-[10px]
                                                                     px-2 py-0.5 rounded-md
                                                                     bg-gray-100 dark:bg-gray-800
                                                                     text-gray-500">

                                                            {{ $question->category }}

                                                        </span>

                                                    @endif


                                                    @if ($question->difficulty)

                                                        <span class="text-[10px]
                                                                     px-2 py-0.5 rounded-md
                                                                     bg-gray-100 dark:bg-gray-800
                                                                     text-gray-500">

                                                            {{ match($question->difficulty) {

                                                                'easy' => 'Fácil',
                                                                'medium' => 'Media',
                                                                'hard' => 'Difícil',

                                                                default => ucfirst($question->difficulty)

                                                            } }}

                                                        </span>

                                                    @endif

                                                </div>


                                                <p class="text-sm font-semibold
                                                          text-gray-900 dark:text-white">

                                                    {{ $question->question }}

                                                </p>


                                                <div class="flex items-center gap-3 mt-2">

                                                    <span class="text-[10px] text-gray-400">

                                                        {{ $question->points }} pts

                                                    </span>

                                                </div>

                                            </div>

                                        </div>

                                    </button>

                                @endforeach

                            </div>

                        @else

                            <div class="py-12 text-center">

                                <div class="w-12 h-12 mx-auto mb-3
                                            rounded-full bg-gray-100
                                            dark:bg-gray-800
                                            flex items-center justify-center">

                                    <i class="fas fa-search text-gray-400"></i>

                                </div>

                                <p class="text-sm font-semibold
                                          text-gray-700 dark:text-gray-300">

                                    No se encontraron preguntas

                                </p>

                                <p class="text-xs text-gray-400 mt-1">

                                    Prueba cambiando los filtros de búsqueda.

                                </p>

                            </div>

                        @endif

                    </div>


                    {{-- FOOTER --}}
                    <div class="p-4 border-t border-gray-200 dark:border-gray-800
                                flex flex-col sm:flex-row
                                items-center justify-between gap-3">

                        <div class="text-xs text-gray-500">

                            <span class="font-semibold text-indigo-600">

                                {{ count($selectedBankQuestions) }}

                            </span>

                            pregunta(s) seleccionada(s)

                        </div>


                        <div class="flex items-center gap-2">

                            <button
                                type="button"
                                wire:click="clearBankSelection"
                                class="px-4 py-2 text-xs font-semibold
                                       text-gray-600 dark:text-gray-300
                                       hover:bg-gray-100 dark:hover:bg-gray-800
                                       rounded-xl">

                                Limpiar selección

                            </button>


                            <button
                                type="button"
                                wire:click="addSelectedBankQuestions"
                                @disabled(count($selectedBankQuestions) === 0)
                                class="inline-flex items-center gap-2
                                       px-5 py-2.5 text-xs font-semibold
                                       text-white bg-indigo-600
                                       hover:bg-indigo-500
                                       disabled:opacity-50
                                       disabled:cursor-not-allowed
                                       rounded-xl">

                                <i class="fas fa-plus"></i>

                                Agregar al Quiz

                            </button>

                        </div>

                    </div>

                </div>

            </div>

        </div>

    @endif

</div>
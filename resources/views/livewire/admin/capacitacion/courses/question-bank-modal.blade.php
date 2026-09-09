<div class="fixed inset-0 z-50 overflow-y-auto" x-data x-init="$nextTick(() => document.body.classList.add('overflow-hidden'))"
    x-on:close-bank-modal.window="document.body.classList.remove('overflow-hidden')">

    {{-- FONDO --}}
    <div class="fixed inset-0 bg-black/50 backdrop-blur-sm" wire:click="closeQuestionBank">
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


                    <button type="button" wire:click="closeQuestionBank"
                        class="p-2 text-gray-400 hover:text-gray-700
                               dark:hover:text-white rounded-lg">

                        <i class="fas fa-times"></i>

                    </button>

                </div>

            </div>


            {{-- FILTROS --}}
            <div
                class="p-5 bg-gray-50 dark:bg-gray-800/40
                        border-b border-gray-200 dark:border-gray-800">

                <div class="grid grid-cols-1 md:grid-cols-4 gap-3">


                    {{-- BUSCAR --}}
                    <div class="md:col-span-2">

                        <label
                            class="block text-[11px] font-semibold
                                      text-gray-600 dark:text-gray-300 mb-1">

                            Buscar

                        </label>

                        <div class="relative">

                            <i
                                class="fas fa-search absolute left-3 top-1/2
                                      -translate-y-1/2 text-gray-400 text-xs"></i>

                            <input type="text" wire:model.live.debounce.400ms="bankSearch"
                                placeholder="Buscar por pregunta..."
                                class="w-full pl-9 pr-3 py-2 text-sm rounded-xl
                                       border border-gray-300 dark:border-gray-700
                                       dark:bg-gray-900 dark:text-white
                                       focus:ring-2 focus:ring-indigo-500">

                        </div>

                    </div>


                    {{-- CATEGORÍA --}}
                    <div>

                        <label
                            class="block text-[11px] font-semibold
                                      text-gray-600 dark:text-gray-300 mb-1">

                            Categoría

                        </label>

                        <select wire:model.live="bankCategory"
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

                        <label
                            class="block text-[11px] font-semibold
                                      text-gray-600 dark:text-gray-300 mb-1">

                            Tipo

                        </label>

                        <select wire:model.live="bankType"
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

                    <label
                        class="block text-[11px] font-semibold
                                  text-gray-600 dark:text-gray-300 mb-1">

                        Dificultad

                    </label>

                    <select wire:model.live="bankDifficulty"
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
                                $questionTypeValue =
                                    $question->question_type instanceof \BackedEnum
                                        ? $question->question_type->value
                                        : $question->question_type;

                                $isSelected = in_array($question->id, $selectedBankQuestions);
                            @endphp


                            <button type="button" wire:click="toggleBankQuestion({{ $question->id }})"
                                wire:key="bank-question-{{ $question->id }}"
                                class="w-full text-left p-4 rounded-xl border transition
                                {{ $isSelected
                                    ? 'border-indigo-400 bg-indigo-50 dark:bg-indigo-950/30'
                                    : 'border-gray-200 dark:border-gray-800
                                                                                                                       hover:border-indigo-300
                                                                                                                       bg-white dark:bg-gray-900' }}">

                                <div class="flex items-start gap-3">


                                    {{-- CHECK --}}
                                    <div class="pt-0.5">

                                        <div
                                            class="w-5 h-5 rounded-md border
                                            flex items-center justify-center

                                            {{ $isSelected ? 'bg-indigo-600 border-indigo-600 text-white' : 'border-gray-300 dark:border-gray-600' }}">

                                            @if ($isSelected)
                                                <i class="fas fa-check text-[10px]"></i>
                                            @endif

                                        </div>

                                    </div>


                                    {{-- CONTENIDO --}}
                                    <div class="flex-1 min-w-0">

                                        <div class="flex flex-wrap items-center gap-2 mb-1">

                                            <span
                                                class="text-[10px] uppercase
                                                         font-semibold text-gray-400">

                                                {{ match ($questionTypeValue) {
                                                    'single_choice' => 'Opción única',
                                                    'multiple_choice' => 'Opción múltiple',
                                                    'true_false' => 'Verdadero / Falso',
                                                    'short_answer' => 'Respuesta corta',
                                                    'long_answer' => 'Respuesta larga',
                                                    'numeric' => 'Numérica',

                                                    default => 'Pregunta',
                                                } }}

                                            </span>


                                            @if ($question->category)
                                                <span
                                                    class="text-[10px]
                                                             px-2 py-0.5 rounded-md
                                                             bg-gray-100 dark:bg-gray-800
                                                             text-gray-500">

                                                    {{ $question->category }}

                                                </span>
                                            @endif


                                            @if ($question->difficulty)
                                                <span
                                                    class="text-[10px]
                                                             px-2 py-0.5 rounded-md
                                                             bg-gray-100 dark:bg-gray-800
                                                             text-gray-500">

                                                    {{ match ($question->difficulty) {
                                                        'easy' => 'Fácil',
                                                        'medium' => 'Media',
                                                        'hard' => 'Difícil',

                                                        default => ucfirst($question->difficulty),
                                                    } }}

                                                </span>
                                            @endif

                                        </div>


                                        <p
                                            class="text-sm font-semibold
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

                        <div
                            class="w-12 h-12 mx-auto mb-3
                                    rounded-full bg-gray-100
                                    dark:bg-gray-800
                                    flex items-center justify-center">

                            <i class="fas fa-search text-gray-400"></i>

                        </div>

                        <p
                            class="text-sm font-semibold
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
            <div
                class="p-4 border-t border-gray-200 dark:border-gray-800
                        flex flex-col sm:flex-row
                        items-center justify-between gap-3">

                <div class="text-xs text-gray-500">

                    <span class="font-semibold text-indigo-600">

                        {{ count($selectedBankQuestions) }}

                    </span>

                    pregunta(s) seleccionada(s)

                </div>

                @error('selectedBankQuestions')
                    <span class="text-xs text-rose-500">
                        {{ $message }}
                    </span>
                @enderror


                <div class="flex items-center gap-2">

                    <button type="button" wire:click="clearBankSelection"
                        class="px-4 py-2 text-xs font-semibold
                               text-gray-600 dark:text-gray-300
                               hover:bg-gray-100 dark:hover:bg-gray-800
                               rounded-xl">

                        Limpiar selección

                    </button>


                    <button type="button" wire:click="addSelectedBankQuestions" @disabled(count($selectedBankQuestions) === 0)
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
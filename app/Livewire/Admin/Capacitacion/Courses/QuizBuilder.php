<?php

namespace App\Livewire\Admin\Capacitacion\Courses;

use App\Enums\QuestionType;
use App\Models\CourseModule;
use App\Models\Question;
use App\Models\Quiz;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rules\Enum;
use Livewire\Attributes\Layout;
use Livewire\Component;
use Livewire\Attributes\Computed;

#[Layout('layouts.admin')]
class QuizBuilder extends Component
{
    /*
    |--------------------------------------------------------------------------
    | QUIZ / MÓDULO
    |--------------------------------------------------------------------------
    */

    public CourseModule $module;

    public ?Quiz $quiz = null;


    /*
    |--------------------------------------------------------------------------
    | CONFIGURACIÓN DEL QUIZ
    |--------------------------------------------------------------------------
    */

    public string $title = '';

    public ?string $description = null;

    public int $time_limit = 0;

    public float $passing_score = 70.00;

    public int $max_attempts = 3;

    public bool $random_questions = false;

    public bool $random_options = false;

    public bool $show_results = true;


    /*
    |--------------------------------------------------------------------------
    | FORMULARIO DE PREGUNTA
    |--------------------------------------------------------------------------
    */

    public ?int $editingQuestionId = null;

    public string $question_text = '';

    public string $question_type = 'single_choice';

    public ?string $explanation = null;

    /*
     * Puntos por defecto de la pregunta en el banco.
     */
    public float $question_points = 1.00;

    /*
     * Puntos específicos que tendrá la pregunta
     * dentro del quiz actual.
     */
    public float $quiz_question_points = 1.00;

    /*
     * Categoría de la pregunta.
     */
    public ?string $category = null;

    /*
     * Dificultad.
     *
     * Valores sugeridos:
     * easy
     * medium
     * hard
     */
    public ?string $difficulty = 'medium';

    /*
     * Opciones de la pregunta.
     */
    public array $options = [];


    /*
    |--------------------------------------------------------------------------
    | BANCO DE PREGUNTAS
    |--------------------------------------------------------------------------
    */

    public bool $showQuestionBank = false;

    public string $bankSearch = '';

    public ?string $bankCategory = null;

    public ?string $bankType = null;

    public ?string $bankDifficulty = null;

    /*
     * Preguntas seleccionadas del banco.
     */
    public array $selectedBankQuestions = [];


    /*
    |--------------------------------------------------------------------------
    | MOUNT
    |--------------------------------------------------------------------------
    */

    public function mount(CourseModule $module): void
    {
        $this->module = $module->load('course');

        /*
         * Actualmente trabajamos con un quiz por módulo.
         */
        $this->quiz = Quiz::where(
            'module_id',
            $this->module->id
        )->first();

        if ($this->quiz) {

            $this->title =
                $this->quiz->title;

            $this->description =
                $this->quiz->description;

            $this->time_limit =
                $this->quiz->time_limit ?? 0;

            $this->passing_score =
                (float) $this->quiz->passing_score;

            $this->max_attempts =
                $this->quiz->max_attempts ?? 3;

            $this->random_questions =
                (bool) $this->quiz->random_questions;

            $this->random_options =
                (bool) $this->quiz->random_options;

            $this->show_results =
                (bool) $this->quiz->show_results;

            $this->loadQuizQuestions();

        } else {

            $this->title =
                'Evaluación: ' . $this->module->name;
        }

        $this->resetQuestionForm();
    }

    #[Computed]
public function questionTypes()
{
    return QuestionType::cases();
}

    /*
    |--------------------------------------------------------------------------
    | CARGAR PREGUNTAS DEL QUIZ
    |--------------------------------------------------------------------------
    */

    public function loadQuizQuestions(): void
    {
        if (!$this->quiz) {
            return;
        }

        $this->quiz->load([
            'questions' => function ($query) {
                $query
                    ->with('options')
                    ->orderBy('quiz_questions.order');
            },
        ]);
    }


    /*
    |--------------------------------------------------------------------------
    | GUARDAR CONFIGURACIÓN DEL QUIZ
    |--------------------------------------------------------------------------
    */
    #[Computed]
public function questionCategories()
{
    return \App\Models\Question::query()
        ->whereNotNull('category')
        ->where('category', '!=', '')
        ->distinct()
        ->orderBy('category')
        ->pluck('category');
}

    public function saveQuizInfo(): void
    {
        $this->validate([
            'title' => [
                'required',
                'string',
                'max:255',
            ],

            'description' => [
                'nullable',
                'string',
            ],

            'time_limit' => [
                'required',
                'integer',
                'min:0',
            ],

            'passing_score' => [
                'required',
                'numeric',
                'min:1',
                'max:100',
            ],

            'max_attempts' => [
                'required',
                'integer',
                'min:1',
            ],

            'random_questions' => [
                'boolean',
            ],

            'random_options' => [
                'boolean',
            ],

            'show_results' => [
                'boolean',
            ],
        ]);

        $data = [
            'course_id' => $this->module->course_id,

            'module_id' => $this->module->id,

            'title' => $this->title,

            'description' => $this->description,

            'time_limit' => $this->time_limit,

            'passing_score' => $this->passing_score,

            'max_attempts' => $this->max_attempts,

            'random_questions' => $this->random_questions,

            'random_options' => $this->random_options,

            'show_results' => $this->show_results,

            'status' => true,
        ];

        if ($this->quiz) {

            $this->quiz->update($data);

        } else {

            $this->quiz = Quiz::create($data);
        }

        session()->flash(
            'message',
            'Ajustes del quiz guardados correctamente.'
        );
    }


    /*
    |--------------------------------------------------------------------------
    | CAMBIO DE TIPO DE PREGUNTA
    |--------------------------------------------------------------------------
    */

    public function updatedQuestionType($type): void
    {
        $questionType =
            QuestionType::tryFrom($type);

        if (!$questionType) {

            $this->options = [];

            return;
        }

        match ($questionType) {

            /*
             * VERDADERO / FALSO
             */
            QuestionType::TRUE_FALSE =>

                $this->options = [
                    [
                        'option' => 'Verdadero',
                        'is_correct' => true,
                    ],

                    [
                        'option' => 'Falso',
                        'is_correct' => false,
                    ],
                ],


            /*
             * RESPUESTA LARGA
             */
            QuestionType::LONG_ANSWER =>

                $this->options = [],


            /*
             * RESPUESTA CORTA
             */
            QuestionType::SHORT_ANSWER =>

                $this->options = [
                    [
                        'option' => '',
                        'is_correct' => true,
                    ],
                ],


            /*
             * NUMÉRICA
             */
            QuestionType::NUMERIC =>

                $this->options = [
                    [
                        'option' => '',
                        'is_correct' => true,
                    ],
                ],


            /*
             * SELECCIÓN ÚNICA
             */
            QuestionType::SINGLE_CHOICE =>

                $this->options = [
                    [
                        'option' => '',
                        'is_correct' => true,
                    ],

                    [
                        'option' => '',
                        'is_correct' => false,
                    ],
                ],


            /*
             * SELECCIÓN MÚLTIPLE
             */
            QuestionType::MULTIPLE_CHOICE =>

                $this->options = [
                    [
                        'option' => '',
                        'is_correct' => true,
                    ],

                    [
                        'option' => '',
                        'is_correct' => false,
                    ],
                ],
        };

        $this->resetValidation();
    }


    /*
    |--------------------------------------------------------------------------
    | GUARDAR PREGUNTA
    |--------------------------------------------------------------------------
    */

    public function saveQuestion(): void
    {
        /*
         * Si todavía no existe el quiz,
         * lo creamos primero.
         */
        if (!$this->quiz) {
            $this->saveQuizInfo();
        }

        /*
         * Validación general.
         */
        $this->validate([
            'question_text' => [
                'required',
                'string',
            ],

            'question_points' => [
                'required',
                'numeric',
                'min:0.5',
            ],

            'quiz_question_points' => [
                'required',
                'numeric',
                'min:0.5',
            ],

            'question_type' => [
                'required',
                new Enum(QuestionType::class),
            ],

            'category' => [
                'nullable',
                'string',
                'max:100',
            ],

            'difficulty' => [
                'nullable',
                'string',
                'max:50',
            ],
        ]);

        $type =
            QuestionType::from(
                $this->question_type
            );


        /*
        |--------------------------------------------------------------------------
        | VALIDAR OPCIONES
        |--------------------------------------------------------------------------
        */

        switch ($type) {

            case QuestionType::SINGLE_CHOICE:

                $this->validate([
                    'options' => [
                        'required',
                        'array',
                        'min:2',
                    ],

                    'options.*.option' => [
                        'required',
                        'string',
                    ],

                    'options.*.is_correct' => [
                        'boolean',
                    ],
                ]);

                break;


            case QuestionType::MULTIPLE_CHOICE:

                $this->validate([
                    'options' => [
                        'required',
                        'array',
                        'min:2',
                    ],

                    'options.*.option' => [
                        'required',
                        'string',
                    ],

                    'options.*.is_correct' => [
                        'boolean',
                    ],
                ]);

                break;


            case QuestionType::TRUE_FALSE:

                $this->validate([
                    'options' => [
                        'required',
                        'array',
                        'size:2',
                    ],

                    'options.*.option' => [
                        'required',
                        'string',
                    ],

                    'options.*.is_correct' => [
                        'boolean',
                    ],
                ]);

                break;


            case QuestionType::SHORT_ANSWER:

                $this->validate([
                    'options' => [
                        'required',
                        'array',
                        'min:1',
                    ],

                    'options.*.option' => [
                        'required',
                        'string',
                    ],

                    'options.*.is_correct' => [
                        'boolean',
                    ],
                ]);

                break;


            case QuestionType::NUMERIC:

                $this->validate([
                    'options' => [
                        'required',
                        'array',
                        'size:1',
                    ],

                    'options.0.option' => [
                        'required',
                        'numeric',
                    ],

                    'options.0.is_correct' => [
                        'boolean',
                    ],
                ]);

                break;


            case QuestionType::LONG_ANSWER:

                /*
                 * No necesita opciones.
                 */
                break;
        }


        /*
        |--------------------------------------------------------------------------
        | VERIFICAR RESPUESTA CORRECTA
        |--------------------------------------------------------------------------
        */

        $typesWithCorrectAnswer = [
            QuestionType::SINGLE_CHOICE,
            QuestionType::MULTIPLE_CHOICE,
            QuestionType::TRUE_FALSE,
            QuestionType::SHORT_ANSWER,
            QuestionType::NUMERIC,
        ];

        if (
            in_array(
                $type,
                $typesWithCorrectAnswer,
                true
            )
        ) {

            $hasCorrect =
                collect($this->options)
                    ->contains(
                        fn ($option) =>
                            (bool) (
                                $option['is_correct']
                                ?? false
                            )
                    );

            if (!$hasCorrect) {

                $this->addError(
                    'options',
                    'Debes seleccionar al menos una respuesta correcta.'
                );

                return;
            }
        }


        /*
        |--------------------------------------------------------------------------
        | SELECCIÓN ÚNICA = UNA SOLA CORRECTA
        |--------------------------------------------------------------------------
        */

        if (
            $type === QuestionType::SINGLE_CHOICE
        ) {

            $correctCount =
                collect($this->options)
                    ->where(
                        'is_correct',
                        true
                    )
                    ->count();

            if ($correctCount !== 1) {

                $this->addError(
                    'options',
                    'La selección única debe tener exactamente una respuesta correcta.'
                );

                return;
            }
        }


        /*
        |--------------------------------------------------------------------------
        | GUARDAR EN TRANSACCIÓN
        |--------------------------------------------------------------------------
        */

        DB::transaction(function () use ($type) {

            /*
            |--------------------------------------------------------------------------
            | EDITAR PREGUNTA EXISTENTE
            |--------------------------------------------------------------------------
            */

            if ($this->editingQuestionId) {

                $question =
                    Question::findOrFail(
                        $this->editingQuestionId
                    );


                /*
                 * IMPORTANTE:
                 *
                 * Esta es una pregunta del banco.
                 *
                 * Si está siendo utilizada por otros quizzes,
                 * sus cambios afectarán a todos ellos.
                 */
                $question->update([
                    'question_type' =>
                        $type,

                    'question' =>
                        $this->question_text,

                    'explanation' =>
                        $this->explanation,

                    'points' =>
                        $this->question_points,

                    'category' =>
                        $this->category,

                    'difficulty' =>
                        $this->difficulty,
                ]);


                /*
                 * Actualizar los puntos SOLO en este quiz.
                 */
                $this->quiz
                    ->questions()
                    ->updateExistingPivot(
                        $question->id,
                        [
                            'points' =>
                                $this->quiz_question_points,
                        ]
                    );


                /*
                 * Reemplazar opciones.
                 */
                $question
                    ->options()
                    ->delete();
            }


            /*
            |--------------------------------------------------------------------------
            | CREAR NUEVA PREGUNTA
            |--------------------------------------------------------------------------
            */

            else {

                $question =
                    Question::create([
                        'question_type' =>
                            $type,

                        'question' =>
                            $this->question_text,

                        'explanation' =>
                            $this->explanation,

                        /*
                         * Puntos predeterminados
                         * en el banco.
                         */
                        'points' =>
                            $this->question_points,

                        'category' =>
                            $this->category,

                        'difficulty' =>
                            $this->difficulty,

                        'status' =>
                            true,

                        'created_by' =>
                            auth()->id(),
                    ]);


                /*
                 * Obtener siguiente posición.
                 */
                $nextOrder =
                    (int) $this->quiz
                        ->questions()
                        ->max(
                            'quiz_questions.order'
                        );

                $nextOrder++;


                /*
                 * Vincular al quiz.
                 */
                $this->quiz
                    ->questions()
                    ->attach(
                        $question->id,
                        [
                            'order' =>
                                $nextOrder,

                            /*
                             * Puntos específicos
                             * de este quiz.
                             */
                            'points' =>
                                $this->quiz_question_points,
                        ]
                    );
            }


            /*
            |--------------------------------------------------------------------------
            | GUARDAR OPCIONES
            |--------------------------------------------------------------------------
            */

            foreach (
                $this->options
                as $index => $option
            ) {

                $question
                    ->options()
                    ->create([
                        'option' =>
                            $option['option'],

                        'is_correct' =>
                            (bool) (
                                $option['is_correct']
                                ?? false
                            ),

                        'order' =>
                            $index + 1,
                    ]);
            }
        });


        /*
        |--------------------------------------------------------------------------
        | ACTUALIZAR INTERFAZ
        |--------------------------------------------------------------------------
        */

        $this->resetQuestionForm();

        $this->loadQuizQuestions();

        session()->flash(
            'message',
            'Pregunta guardada correctamente.'
        );
    }


    /*
    |--------------------------------------------------------------------------
    | EDITAR PREGUNTA
    |--------------------------------------------------------------------------
    */

    public function editQuestion(int $questionId): void
    {
        $question =
            Question::with('options')
                ->findOrFail($questionId);


        /*
         * Verificar que pertenezca al quiz.
         */
        $pivot =
            $this->quiz
                ->questions()
                ->where(
                    'question_id',
                    $questionId
                )
                ->first()
                ?->pivot;


        if (!$pivot) {

            session()->flash(
                'message',
                'La pregunta no pertenece a este quiz.'
            );

            return;
        }


        $this->editingQuestionId =
            $question->id;

        $this->question_text =
            $question->question;


        /*
         * Puede venir como Enum o string,
         * dependiendo del cast del modelo.
         */
        $this->question_type =
            $question->question_type instanceof \BackedEnum
                ? $question->question_type->value
                : $question->question_type;


        $this->explanation =
            $question->explanation;


        $this->category =
            $question->category;


        $this->difficulty =
            $question->difficulty;


        /*
         * Puntos predeterminados del banco.
         */
        $this->question_points =
            (float) $question->points;


        /*
         * Puntos específicos de este quiz.
         */
        $this->quiz_question_points =
            (float) $pivot->points;


        /*
         * Cargar opciones.
         */
        $this->options =
            $question
                ->options
                ->map(
                    fn ($option) => [
                        'option' =>
                            $option->option,

                        'is_correct' =>
                            (bool) $option->is_correct,
                    ]
                )
                ->toArray();


        /*
         * Pregunta abierta no utiliza opciones.
         */
        if (
            $this->question_type ===
            QuestionType::LONG_ANSWER->value
        ) {

            $this->options = [];
        }


        $this->resetValidation();
    }


    /*
    |--------------------------------------------------------------------------
    | DESVINCULAR PREGUNTA DEL QUIZ
    |--------------------------------------------------------------------------
    */

    public function deleteQuestion(int $questionId): void
    {
        if (!$this->quiz) {
            return;
        }


        /*
         * IMPORTANTE:
         *
         * detach() solamente elimina
         * la relación del quiz.
         *
         * NO elimina la pregunta del banco.
         */
        $this->quiz
            ->questions()
            ->detach($questionId);


        $this->loadQuizQuestions();


        session()->flash(
            'message',
            'Pregunta desvinculada del quiz.'
        );
    }


    /*
    |--------------------------------------------------------------------------
    | BANCO DE PREGUNTAS
    |--------------------------------------------------------------------------
    */

    public function openQuestionBank(): void
    {
        /*
         * Si todavía no existe el quiz,
         * crearlo antes de abrir el banco.
         */
        if (!$this->quiz) {
            $this->saveQuizInfo();
        }


        $this->showQuestionBank = true;

        $this->selectedBankQuestions = [];

        $this->resetValidation();
    }


    public function closeQuestionBank(): void
    {
        $this->showQuestionBank = false;

        $this->selectedBankQuestions = [];

        $this->resetValidation();
    }


    /*
    |--------------------------------------------------------------------------
    | CONSULTA DEL BANCO
    |--------------------------------------------------------------------------
    */

    public function getBankQuestionsProperty()
    {
        if (!$this->quiz) {
            return collect();
        }


        /*
         * Obtener IDs que ya están en este quiz.
         *
         * Esas preguntas no aparecerán en el banco.
         */
        $existingIds =
            $this->quiz
                ->questions()
                ->pluck('questions.id');


        return Question::query()

            /*
             * Solo preguntas activas.
             */
            ->where('status', true)

            /*
             * No mostrar preguntas
             * que ya estén agregadas.
             */
            ->whereNotIn(
                'id',
                $existingIds
            )

            /*
             * Búsqueda.
             */
            ->when(
                trim($this->bankSearch) !== '',
                function ($query) {

                    $search =
                        '%' .
                        trim($this->bankSearch) .
                        '%';

                    $query->where(
                        function ($q) use ($search) {

                            $q->where(
                                'question',
                                'like',
                                $search
                            )
                            ->orWhere(
                                'category',
                                'like',
                                $search
                            );
                        }
                    );
                }
            )

            /*
             * Categoría.
             */
            ->when(
                $this->bankCategory,
                function ($query) {

                    $query->where(
                        'category',
                        $this->bankCategory
                    );
                }
            )

            /*
             * Tipo.
             */
            ->when(
                $this->bankType,
                function ($query) {

                    $query->where(
                        'question_type',
                        $this->bankType
                    );
                }
            )

            /*
             * Dificultad.
             */
            ->when(
                $this->bankDifficulty,
                function ($query) {

                    $query->where(
                        'difficulty',
                        $this->bankDifficulty
                    );
                }
            )

            ->with('options')

            ->orderByDesc('id')

            /*
             * De momento limitamos a 50.
             */
            ->limit(50)

            ->get();
    }


    /*
    |--------------------------------------------------------------------------
    | CATEGORÍAS DEL BANCO
    |--------------------------------------------------------------------------
    */

    public function getQuestionCategoriesProperty()
    {
        return Question::query()

            ->where('status', true)

            ->whereNotNull('category')

            ->where(
                'category',
                '!=',
                ''
            )

            ->distinct()

            ->orderBy('category')

            ->pluck('category');
    }


    /*
    |--------------------------------------------------------------------------
    | TIPOS DEL BANCO
    |--------------------------------------------------------------------------
    */

    public function getQuestionTypesProperty()
    {
        return QuestionType::cases();
    }


    /*
    |--------------------------------------------------------------------------
    | SELECCIONAR PREGUNTA DEL BANCO
    |--------------------------------------------------------------------------
    */

    public function toggleBankQuestion(
        int $questionId
    ): void {

        if (
            in_array(
                $questionId,
                $this->selectedBankQuestions
            )
        ) {

            /*
             * Deseleccionar.
             */
            $this->selectedBankQuestions =
                array_values(
                    array_diff(
                        $this->selectedBankQuestions,
                        [$questionId]
                    )
                );

        } else {

            /*
             * Seleccionar.
             */
            $this->selectedBankQuestions[] =
                $questionId;
        }


        $this->resetValidation();
    }


    /*
    |--------------------------------------------------------------------------
    | SELECCIONAR TODAS LAS PREGUNTAS VISIBLES
    |--------------------------------------------------------------------------
    */

    public function selectAllBankQuestions(): void
    {
        $ids =
            $this->bankQuestions
                ->pluck('id')
                ->toArray();


        $this->selectedBankQuestions =
            array_values(
                array_unique(
                    array_merge(
                        $this->selectedBankQuestions,
                        $ids
                    )
                )
            );
    }


    /*
    |--------------------------------------------------------------------------
    | LIMPIAR SELECCIÓN DEL BANCO
    |--------------------------------------------------------------------------
    */

    public function clearBankSelection(): void
    {
        $this->selectedBankQuestions = [];

        $this->resetValidation();
    }


    /*
    |--------------------------------------------------------------------------
    | AGREGAR PREGUNTAS DEL BANCO AL QUIZ
    |--------------------------------------------------------------------------
    */

    public function addSelectedBankQuestions(): void
    {
        /*
         * Si por alguna razón no existe el quiz,
         * crearlo primero.
         */
        if (!$this->quiz) {
            $this->saveQuizInfo();
        }


        if (
            empty(
                $this->selectedBankQuestions
            )
        ) {

            $this->addError(
                'selectedBankQuestions',
                'Selecciona al menos una pregunta.'
            );

            return;
        }


        DB::transaction(function () {

            /*
             * Último orden utilizado.
             */
            $nextOrder =
                (int) $this->quiz
                    ->questions()
                    ->max(
                        'quiz_questions.order'
                    );


            foreach (
                $this->selectedBankQuestions
                as $questionId
            ) {

                /*
                 * Evitar duplicados.
                 */
                $exists =
                    $this->quiz
                        ->questions()
                        ->where(
                            'question_id',
                            $questionId
                        )
                        ->exists();


                if ($exists) {
                    continue;
                }


                /*
                 * Verificar que la pregunta
                 * todavía exista.
                 */
                $question =
                    Question::find(
                        $questionId
                    );


                if (!$question) {
                    continue;
                }


                $nextOrder++;


                /*
                 * Vincular pregunta existente.
                 *
                 * NO creamos otra pregunta.
                 */
                $this->quiz
                    ->questions()
                    ->attach(
                        $question->id,
                        [
                            'order' =>
                                $nextOrder,

                            /*
                             * Utilizar puntos
                             * predeterminados del banco.
                             */
                            'points' =>
                                (float) $question->points,
                        ]
                    );
            }
        });


        $this->selectedBankQuestions = [];

        $this->showQuestionBank = false;

        $this->loadQuizQuestions();

        $this->resetValidation();


        session()->flash(
            'message',
            'Preguntas agregadas al quiz correctamente.'
        );
    }


    /*
    |--------------------------------------------------------------------------
    | AGREGAR OPCIÓN
    |--------------------------------------------------------------------------
    */

    public function addOption(): void
    {
        $type =
            QuestionType::tryFrom(
                $this->question_type
            );


        if (
            in_array(
                $type,
                [
                    QuestionType::SINGLE_CHOICE,
                    QuestionType::MULTIPLE_CHOICE,
                    QuestionType::SHORT_ANSWER,
                ],
                true
            )
        ) {

            $this->options[] = [
                'option' => '',
                'is_correct' => false,
            ];
        }
    }


    /*
    |--------------------------------------------------------------------------
    | ELIMINAR OPCIÓN
    |--------------------------------------------------------------------------
    */

    public function removeOption(
        int $index
    ): void {

        $type =
            QuestionType::tryFrom(
                $this->question_type
            );


        /*
         * No permitir eliminar opciones
         * en tipos que tienen estructura fija.
         */
        if (
            in_array(
                $type,
                [
                    QuestionType::TRUE_FALSE,
                    QuestionType::NUMERIC,
                ],
                true
            )
        ) {
            return;
        }


        /*
         * Debe quedar al menos una opción.
         */
        if (
            count($this->options) <= 1
        ) {
            return;
        }


        unset(
            $this->options[$index]
        );


        $this->options =
            array_values(
                $this->options
            );
    }


    /*
    |--------------------------------------------------------------------------
    | MARCAR RESPUESTA CORRECTA
    |--------------------------------------------------------------------------
    */

    public function setCorrectOption(
        int $index
    ): void {

        $type =
            QuestionType::tryFrom(
                $this->question_type
            );


        /*
         * UNA SOLA RESPUESTA
         */
        if (
            in_array(
                $type,
                [
                    QuestionType::SINGLE_CHOICE,
                    QuestionType::TRUE_FALSE,
                    QuestionType::NUMERIC,
                ],
                true
            )
        ) {

            foreach (
                $this->options
                as $i => &$option
            ) {

                $option['is_correct'] =
                    ($i === $index);
            }

            unset($option);

            return;
        }


        /*
         * VARIAS RESPUESTAS
         */
        if (
            in_array(
                $type,
                [
                    QuestionType::MULTIPLE_CHOICE,
                    QuestionType::SHORT_ANSWER,
                ],
                true
            )
        ) {

            $this->options[$index]['is_correct'] =
                !(
                    $this->options[$index]['is_correct']
                    ?? false
                );
        }
    }


    /*
    |--------------------------------------------------------------------------
    | RESET FORMULARIO
    |--------------------------------------------------------------------------
    */

    public function resetQuestionForm(): void
    {
        $this->editingQuestionId = null;

        $this->question_text = '';

        $this->question_type =
            QuestionType::SINGLE_CHOICE->value;

        $this->explanation = null;

        /*
         * Puntos por defecto.
         */
        $this->question_points = 1.00;

        /*
         * Puntos en el quiz.
         */
        $this->quiz_question_points = 1.00;

        /*
         * Nueva categoría.
         */
        $this->category = null;

        /*
         * Dificultad por defecto.
         */
        $this->difficulty = 'medium';


        /*
         * Opciones iniciales.
         */
        $this->options = [
            [
                'option' => '',
                'is_correct' => true,
            ],

            [
                'option' => '',
                'is_correct' => false,
            ],
        ];


        $this->resetValidation();
    }


    /*
    |--------------------------------------------------------------------------
    | RENDER
    |--------------------------------------------------------------------------
    */

    public function render()
    {
        return view(
            'livewire.admin.capacitacion.courses.quiz-builder'
        );
    }
}
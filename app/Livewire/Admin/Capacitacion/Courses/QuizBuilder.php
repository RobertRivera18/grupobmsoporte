<?php

namespace App\Livewire\Admin\Capacitacion\Courses;

use App\Enums\QuestionType;
use App\Models\CourseModule;
use App\Models\Question;
use App\Models\Quiz;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rules\Enum;
use Livewire\Attributes\Layout;
use Livewire\Attributes\On;
use Livewire\Component;

#[Layout('layouts.admin')]
class QuizBuilder extends Component
{
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

    public float $question_points = 1.00;
    public float $quiz_question_points = 1.00;

    public ?string $category = null;
    public ?string $difficulty = 'medium';

    public array $options = [];

    /*
    |--------------------------------------------------------------------------
    | BANCO DE PREGUNTAS
    |--------------------------------------------------------------------------
    |
    | El banco ahora pertenece al componente hijo QuestionBankModal.
    | QuizBuilder únicamente controla cuándo se muestra.
    |
    */

    public bool $showQuestionBank = false;

    /*
    |--------------------------------------------------------------------------
    | MOUNT
    |--------------------------------------------------------------------------
    */

    public function mount(CourseModule $module): void
    {
        $this->module = $module->load('course');

        $this->quiz = Quiz::where(
            'module_id',
            $this->module->id
        )->first();

        if ($this->quiz) {
            $this->title = $this->quiz->title;
            $this->description = $this->quiz->description;
            $this->time_limit = $this->quiz->time_limit ?? 0;
            $this->passing_score = (float) $this->quiz->passing_score;
            $this->max_attempts = $this->quiz->max_attempts ?? 3;
            $this->random_questions = (bool) $this->quiz->random_questions;
            $this->random_options = (bool) $this->quiz->random_options;
            $this->show_results = (bool) $this->quiz->show_results;

            $this->loadQuizQuestions();
        } else {
            $this->title = 'Evaluación: ' . $this->module->name;
        }

        $this->resetQuestionForm();
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
        $questionType = QuestionType::tryFrom($type);

        if (!$questionType) {
            $this->options = [];
            return;
        }

        match ($questionType) {
            QuestionType::TRUE_FALSE => $this->options = [
                [
                    'option' => 'Verdadero',
                    'is_correct' => true,
                ],
                [
                    'option' => 'Falso',
                    'is_correct' => false,
                ],
            ],

            QuestionType::LONG_ANSWER => $this->options = [],

            QuestionType::SHORT_ANSWER => $this->options = [
                [
                    'option' => '',
                    'is_correct' => true,
                ],
            ],

            QuestionType::NUMERIC => $this->options = [
                [
                    'option' => '',
                    'is_correct' => true,
                ],
            ],

            QuestionType::SINGLE_CHOICE => $this->options = [
                [
                    'option' => '',
                    'is_correct' => true,
                ],
                [
                    'option' => '',
                    'is_correct' => false,
                ],
            ],

            QuestionType::MULTIPLE_CHOICE => $this->options = [
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
        if (!$this->quiz) {
            $this->saveQuizInfo();
        }

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

        $type = QuestionType::from($this->question_type);

        /*
        |--------------------------------------------------------------------------
        | VALIDACIONES SEGÚN TIPO
        |--------------------------------------------------------------------------
        */

        switch ($type) {
            case QuestionType::SINGLE_CHOICE:
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
                break;
        }

        /*
        |--------------------------------------------------------------------------
        | COMPROBAR RESPUESTA CORRECTA
        |--------------------------------------------------------------------------
        */

        $typesWithCorrectAnswer = [
            QuestionType::SINGLE_CHOICE,
            QuestionType::MULTIPLE_CHOICE,
            QuestionType::TRUE_FALSE,
            QuestionType::SHORT_ANSWER,
            QuestionType::NUMERIC,
        ];

        if (in_array($type, $typesWithCorrectAnswer, true)) {
            $hasCorrect = collect($this->options)
                ->contains(
                    fn ($option) => (bool) (
                        $option['is_correct'] ?? false
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
        | OPCIÓN ÚNICA = UNA SOLA CORRECTA
        |--------------------------------------------------------------------------
        */

        if ($type === QuestionType::SINGLE_CHOICE) {
            $correctCount = collect($this->options)
                ->where('is_correct', true)
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
            | EDITAR
            |--------------------------------------------------------------------------
            */

            if ($this->editingQuestionId) {

                $question = Question::findOrFail(
                    $this->editingQuestionId
                );

                $question->update([
                    'question_type' => $type,
                    'question' => $this->question_text,
                    'explanation' => $this->explanation,
                    'points' => $this->question_points,
                    'category' => $this->category,
                    'difficulty' => $this->difficulty,
                ]);

                $this->quiz
                    ->questions()
                    ->updateExistingPivot(
                        $question->id,
                        [
                            'points' => $this->quiz_question_points,
                        ]
                    );

                $question->options()->delete();
            }

            /*
            |--------------------------------------------------------------------------
            | CREAR
            |--------------------------------------------------------------------------
            */

            else {

                $question = Question::create([
                    'question_type' => $type,
                    'question' => $this->question_text,
                    'explanation' => $this->explanation,
                    'points' => $this->question_points,
                    'category' => $this->category,
                    'difficulty' => $this->difficulty,
                    'status' => true,
                    'created_by' => auth()->id(),
                ]);

                $nextOrder = (int) $this->quiz
                    ->questions()
                    ->max('quiz_questions.order');

                $nextOrder++;

                $this->quiz
                    ->questions()
                    ->attach(
                        $question->id,
                        [
                            'order' => $nextOrder,
                            'points' => $this->quiz_question_points,
                        ]
                    );
            }

            /*
            |--------------------------------------------------------------------------
            | GUARDAR OPCIONES
            |--------------------------------------------------------------------------
            */

            foreach ($this->options as $index => $option) {
                $question->options()->create([
                    'option' => $option['option'],
                    'is_correct' => (bool) (
                        $option['is_correct'] ?? false
                    ),
                    'order' => $index + 1,
                ]);
            }
        });

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
        $question = Question::with('options')
            ->findOrFail($questionId);

        $pivot = $this->quiz
            ->questions()
            ->where('question_id', $questionId)
            ->first()?->pivot;

        if (!$pivot) {
            session()->flash(
                'message',
                'La pregunta no pertenece a este quiz.'
            );

            return;
        }

        $this->editingQuestionId = $question->id;

        $this->question_text = $question->question;

        $this->question_type =
            $question->question_type instanceof \BackedEnum
                ? $question->question_type->value
                : $question->question_type;

        $this->explanation = $question->explanation;
        $this->category = $question->category;
        $this->difficulty = $question->difficulty;

        $this->question_points = (float) $question->points;
        $this->quiz_question_points = (float) $pivot->points;

        $this->options = $question->options
            ->map(
                fn ($option) => [
                    'option' => $option->option,
                    'is_correct' => (bool) $option->is_correct,
                ]
            )
            ->toArray();

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
    | DESVINCULAR PREGUNTA
    |--------------------------------------------------------------------------
    */

    public function deleteQuestion(int $questionId): void
    {
        if (!$this->quiz) {
            return;
        }

        $this->quiz->questions()->detach($questionId);

        $this->loadQuizQuestions();

        session()->flash(
            'message',
            'Pregunta desvinculada del quiz.'
        );
    }

    /*
    |--------------------------------------------------------------------------
    | ABRIR BANCO DE PREGUNTAS
    |--------------------------------------------------------------------------
    |
    | QuizBuilder solamente crea el Quiz si todavía no existe y
    | posteriormente muestra el componente hijo.
    |
    */

    public function openQuestionBank(): void
    {
        if (!$this->quiz) {
            $this->saveQuizInfo();
        }

        $this->showQuestionBank = true;
    }

    /*
    |--------------------------------------------------------------------------
    | CERRAR BANCO
    |--------------------------------------------------------------------------
    */

    #[On('question-bank-closed')]
    public function closeQuestionBank(): void
    {
        $this->showQuestionBank = false;
    }

    /*
    |--------------------------------------------------------------------------
    | PREGUNTAS AGREGADAS DESDE EL BANCO
    |--------------------------------------------------------------------------
    */

    #[On('questions-added-to-quiz')]
    public function handleQuestionsAddedToQuiz(): void
    {
        $this->loadQuizQuestions();

        $this->showQuestionBank = false;

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
        $type = QuestionType::tryFrom(
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

    public function removeOption(int $index): void
    {
        $type = QuestionType::tryFrom(
            $this->question_type
        );

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

        if (count($this->options) <= 1) {
            return;
        }

        unset($this->options[$index]);

        $this->options = array_values(
            $this->options
        );
    }

    /*
    |--------------------------------------------------------------------------
    | MARCAR RESPUESTA CORRECTA
    |--------------------------------------------------------------------------
    */

    public function setCorrectOption(int $index): void
    {
        $type = QuestionType::tryFrom(
            $this->question_type
        );

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
                $this->options as $i => &$option
            ) {
                $option['is_correct'] =
                    ($i === $index);
            }

            unset($option);

            return;
        }

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

        $this->question_points = 1.00;
        $this->quiz_question_points = 1.00;

        $this->category = null;
        $this->difficulty = 'medium';

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
<?php

namespace App\Livewire\Admin\Capacitacion\Questions;

use App\Enums\QuestionType;
use App\Models\Question;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rules\Enum;
use Livewire\Attributes\Computed;
use Livewire\Component;
use Livewire\WithPagination;

class QuestionIndex extends Component
{
    use WithPagination;

    public string $search = '';
    public ?string $category = null;
    public ?string $type = null;
    public ?string $difficulty = null;

    public bool $isModalOpen = false;
    public ?int $editingQuestionId = null;

    public string $question_text = '';
    public string $question_type = 'single_choice';
    public float $question_points = 1.0;
    public ?string $explanation = '';
    public ?string $category_input = '';
    public string $difficulty_input = 'medium';

    public array $options = [
        ['option' => '', 'is_correct' => false],
        ['option' => '', 'is_correct' => false],
    ];

    protected $queryString = [
        'search' => ['except' => ''],
        'category' => ['except' => ''],
        'type' => ['except' => ''],
        'difficulty' => ['except' => ''],
    ];

    public function updatingSearch(): void
    {
        $this->resetPage();
    }

    #[Computed]
    public function questions()
    {
        return Question::query()
            ->when(trim($this->search) !== '', function ($query) {
                $search = '%' . trim($this->search) . '%';
                $query->where(fn($q) => $q->where('question', 'like', $search)->orWhere('category', 'like', $search));
            })
            ->when($this->category, fn($q) => $q->where('category', $this->category))
            ->when($this->type, fn($q) => $q->where('question_type', $this->type))
            ->when($this->difficulty, fn($q) => $q->where('difficulty', $this->difficulty))
            ->with('options')
            ->latest()
            ->paginate(10);
    }

    #[Computed]
    public function categories()
    {
        return Question::whereNotNull('category')
            ->where('category', '!=', '')
            ->distinct()
            ->pluck('category');
    }

    #[Computed]
    public function questionTypes()
    {
        return QuestionType::cases();
    }

    /*
    |--------------------------------------------------------------------------
    | GESTIÓN DE OPCIONES DINÁMICAS
    |--------------------------------------------------------------------------
    */
    public function addOption(): void
    {
        $this->options[] = ['option' => '', 'is_correct' => false];
    }

    public function removeOption(int $index): void
    {
        if (count($this->options) > 1) {
            unset($this->options[$index]);
            $this->options = array_values($this->options);
        }
    }

    private function getDefaultOptions(QuestionType $type): array
    {
        return match ($type) {
            QuestionType::TRUE_FALSE => [
                ['option' => 'Verdadero', 'is_correct' => true],
                ['option' => 'Falso', 'is_correct' => false],
            ],
            QuestionType::NUMERIC => [
                ['option' => '', 'is_correct' => true],
            ],
            default => [
                ['option' => '', 'is_correct' => false],
                ['option' => '', 'is_correct' => false],
            ],
        };
    }

    public function updatedQuestionType($value): void
    {
        $type = QuestionType::tryFrom($value);
        if ($type) {
            $this->options = $this->getDefaultOptions($type);
        }
    }

    /*
    |--------------------------------------------------------------------------
    | MODAL (CREAR / EDITAR)
    |--------------------------------------------------------------------------
    */
    public function openCreateModal(): void
    {
        $this->reset(['editingQuestionId', 'question_text', 'explanation', 'category_input']);
        $this->question_type = QuestionType::SINGLE_CHOICE->value;
        $this->question_points = 1.0;
        $this->difficulty_input = 'medium';
        $this->options = $this->getDefaultOptions(QuestionType::SINGLE_CHOICE);
        $this->resetValidation();
        $this->isModalOpen = true;
    }

    public function edit(Question $question): void
    {
        $this->resetValidation();

        $this->editingQuestionId = $question->id;
        $this->question_text = $question->question;
        $this->question_type = $question->question_type instanceof QuestionType
            ? $question->question_type->value
            : $question->question_type;

        $this->question_points = $question->points;
        $this->explanation = $question->explanation;
        $this->category_input = $question->category;
        $this->difficulty_input = $question->difficulty ?? 'medium';
        
        $this->options = $question->options->map(fn($opt) => [
            'option' => $opt->option,
            'is_correct' => (bool) $opt->is_correct,
        ])->toArray();

        if (empty($this->options)) {
            $this->options = $this->getDefaultOptions(QuestionType::from($this->question_type));
        }

        $this->isModalOpen = true;
    }

    public function closeModal(): void
    {
        $this->isModalOpen = false;
    }

    /*
    |--------------------------------------------------------------------------
    | GUARDAR O ACTUALIZAR PREGUNTA
    |--------------------------------------------------------------------------
    */
    public function saveQuestion(): void
    {
        $this->validate([
            'question_text' => ['required', 'string'],
            'question_points' => ['required', 'numeric', 'min:0.5'],
            'question_type' => ['required', new Enum(QuestionType::class)],
            'category_input' => ['nullable', 'string', 'max:100'],
            'difficulty_input' => ['nullable', 'string', 'max:50'],
        ]);

        $type = QuestionType::from($this->question_type);

        // Validación dinámica de opciones según el tipo usando match
        match ($type) {
            QuestionType::SINGLE_CHOICE, QuestionType::MULTIPLE_CHOICE => $this->validate([
                'options' => ['required', 'array', 'min:2'],
                'options.*.option' => ['required', 'string'],
                'options.*.is_correct' => ['boolean'],
            ]),
            QuestionType::TRUE_FALSE => $this->validate([
                'options' => ['required', 'array', 'size:2'],
                'options.*.option' => ['required', 'string'],
                'options.*.is_correct' => ['boolean'],
            ]),
            QuestionType::SHORT_ANSWER => $this->validate([
                'options' => ['required', 'array', 'min:1'],
                'options.*.option' => ['required', 'string'],
                'options.*.is_correct' => ['boolean'],
            ]),
            QuestionType::NUMERIC => $this->validate([
                'options' => ['required', 'array', 'size:1'],
                'options.0.option' => ['required', 'numeric'],
                'options.0.is_correct' => ['boolean'],
            ]),
            QuestionType::LONG_ANSWER => null,
        };

        $typesWithCorrectAnswer = [
            QuestionType::SINGLE_CHOICE,
            QuestionType::MULTIPLE_CHOICE,
            QuestionType::TRUE_FALSE,
            QuestionType::SHORT_ANSWER,
            QuestionType::NUMERIC,
        ];

        if (in_array($type, $typesWithCorrectAnswer, true)) {
            $hasCorrect = collect($this->options)->contains(fn($option) => (bool) ($option['is_correct'] ?? false));
            if (!$hasCorrect) {
                $this->addError('options', 'Debes seleccionar al menos una respuesta correcta.');
                return;
            }
        }

        if ($type === QuestionType::SINGLE_CHOICE) {
            $correctCount = collect($this->options)->where('is_correct', true)->count();
            if ($correctCount !== 1) {
                $this->addError('options', 'La selección única debe tener exactamente una respuesta correcta.');
                return;
            }
        }

        DB::transaction(function () use ($type) {
            $question = Question::updateOrCreate(
                ['id' => $this->editingQuestionId],
                [
                    'question_type' => $type,
                    'question' => $this->question_text,
                    'explanation' => $this->explanation,
                    'points' => $this->question_points,
                    'category' => $this->category_input,
                    'difficulty' => $this->difficulty_input,
                    'status' => true,
                    'created_by' => auth()->id(),
                ]
            );
            $question->options()->delete();
            $formattedOptions = collect($this->options)->map(fn($option, $index) => [
                'option' => $option['option'],
                'is_correct' => (bool) ($option['is_correct'] ?? false),
                'order' => $index + 1,
            ])->toArray();

            $question->options()->createMany($formattedOptions);
        });

        $message = $this->editingQuestionId ? 'Pregunta actualizada correctamente.' : 'Pregunta creada y agregada al banco global exitosamente.';

        $this->closeModal();
        session()->flash('success', $message);
    }

    public function delete(Question $question): void
    {
        $question->delete();
        session()->flash('success', 'Pregunta eliminada correctamente del banco.');
    }

    public function render()
    {
        return view('livewire.admin.capacitacion.questions.question-index')
            ->layout('layouts.admin', [
                'breadcrumbs' => [
                    ['name' => 'Dashboard', 'url' => route('admin.dashboard')],
                    ['name' => 'Banco de Preguntas']
                ]
            ]);
    }
}

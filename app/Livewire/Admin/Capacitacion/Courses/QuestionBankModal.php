<?php

namespace App\Livewire\Admin\Capacitacion\Courses;

use App\Enums\QuestionType;
use App\Models\Question;
use App\Models\Quiz;
use Illuminate\Support\Facades\DB;
use Livewire\Attributes\Computed;
use Livewire\Component;

class QuestionBankModal extends Component
{
    public Quiz $quiz;

    /*
    |--------------------------------------------------------------------------
    | FILTROS DEL BANCO
    |--------------------------------------------------------------------------
    */
    public string $bankSearch = '';
    public ?string $bankCategory = null;
    public ?string $bankType = null;
    public ?string $bankDifficulty = null;
    public array $selectedBankQuestions = [];

    public function mount(Quiz $quiz): void
    {
        $this->quiz = $quiz;
    }

    /*
    |--------------------------------------------------------------------------
    | CERRAR MODAL
    |--------------------------------------------------------------------------
    | Notifica al padre para que oculte el componente. El propio componente
    | no controla su visibilidad: eso vive en el padre (QuizBuilder).
    */
    public function closeQuestionBank(): void
    {
        $this->dispatch('question-bank-closed');
    }

    /*
    |--------------------------------------------------------------------------
    | CONSULTA DEL BANCO
    |--------------------------------------------------------------------------
    */
    #[Computed]
    public function bankQuestions()
    {
        $existingIds = $this->quiz->questions()->pluck('questions.id');

        return Question::query()
            ->where('status', true)
            ->whereNotIn('id', $existingIds)
            ->when(
                trim($this->bankSearch) !== '',
                function ($query) {
                    $search = '%' . trim($this->bankSearch) . '%';

                    $query->where(function ($q) use ($search) {
                        $q->where('question', 'like', $search)
                            ->orWhere('category', 'like', $search);
                    });
                }
            )
            ->when(
                $this->bankCategory,
                fn($query) => $query->where('category', $this->bankCategory)
            )
            ->when(
                $this->bankType,
                fn($query) => $query->where('question_type', $this->bankType)
            )
            ->when(
                $this->bankDifficulty,
                fn($query) => $query->where('difficulty', $this->bankDifficulty)
            )
            ->with('options')
            ->orderByDesc('id')
            ->limit(50)
            ->get();
    }

    /*
    |--------------------------------------------------------------------------
    | CATEGORÍAS DEL BANCO
    |--------------------------------------------------------------------------
    */
    #[Computed]
    public function questionCategories()
    {
        return Question::query()
            ->where('status', true)
            ->whereNotNull('category')
            ->where('category', '!=', '')
            ->distinct()
            ->orderBy('category')
            ->pluck('category');
    }

    /*
    |--------------------------------------------------------------------------
    | TIPOS DE PREGUNTA DISPONIBLES
    |--------------------------------------------------------------------------
    */
    #[Computed]
    public function questionTypes()
    {
        return QuestionType::cases();
    }

    /*
    |--------------------------------------------------------------------------
    | SELECCIONAR / DESELECCIONAR UNA PREGUNTA
    |--------------------------------------------------------------------------
    */
    public function toggleBankQuestion(int $questionId): void
    {
        if (in_array($questionId, $this->selectedBankQuestions)) {
            $this->selectedBankQuestions = array_values(
                array_diff($this->selectedBankQuestions, [$questionId])
            );
        } else {
            $this->selectedBankQuestions[] = $questionId;
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
        $ids = $this->bankQuestions->pluck('id')->toArray();

        $this->selectedBankQuestions = array_values(
            array_unique(array_merge($this->selectedBankQuestions, $ids))
        );
    }

    /*
    |--------------------------------------------------------------------------
    | LIMPIAR SELECCIÓN
    |--------------------------------------------------------------------------
    */
    public function clearBankSelection(): void
    {
        $this->selectedBankQuestions = [];
        $this->resetValidation();
    }

    /*
    |--------------------------------------------------------------------------
    | AGREGAR PREGUNTAS SELECCIONADAS AL QUIZ
    |--------------------------------------------------------------------------
    | Al terminar, avisa al padre (evento) para que recargue las preguntas
    | del quiz, muestre el mensaje de éxito y cierre el modal.
    */
    public function addSelectedBankQuestions(): void
    {
        if (empty($this->selectedBankQuestions)) {
            $this->addError('selectedBankQuestions', 'Selecciona al menos una pregunta.');
            return;
        }

        DB::transaction(function () {
            $nextOrder = (int) $this->quiz->questions()->max('quiz_questions.order');

            foreach ($this->selectedBankQuestions as $questionId) {
                $exists = $this->quiz->questions()->where('question_id', $questionId)->exists();

                if ($exists) {
                    continue;
                }

                $question = Question::find($questionId);

                if (!$question) {
                    continue;
                }

                $nextOrder++;

                $this->quiz->questions()->attach($question->id, [
                    'order' => $nextOrder,
                    'points' => (float) $question->points,
                ]);
            }
        });

        $this->selectedBankQuestions = [];
        $this->resetValidation();

        $this->dispatch('questions-added-to-quiz');
    }

    public function render()
    {
        return view('livewire.admin.capacitacion.courses.question-bank-modal');
    }
}
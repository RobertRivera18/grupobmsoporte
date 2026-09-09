<?php

namespace App\Livewire\Student\Quizz;

use Livewire\Component;
use App\Models\Course;
use App\Models\Question;
use App\Models\Quiz;
use App\Models\QuizAttempt;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class Show extends Component
{
    public Course $course;
    public Quiz $quiz;

    public array $answers = [];
    public int $currentQuestionIndex = 0;
    public bool $isFinished = false;

    // Variables para la nota prevaleciente (Máxima alcanzada)
    public float $score = 0;
    public float $percentage = 0;
    public bool $passed = false;
    public ?QuizAttempt $attempt = null;
    public ?QuizAttempt $activeAttempt = null;
    public ?string $expiresAt = null;

    public bool $showReview = false;

    public function mount(Course $course, Quiz $quiz)
    {
        if (!Auth::user()->courses()->where('courses.id', $course->id)->exists()) {
            abort(403, 'No estás matriculado en este curso.');
        }

        if (isset($quiz->status) && in_array($quiz->status, ['draft', 'inactive', 0, false], true)) {
            abort(403, 'Esta evaluación no se encuentra disponible en este momento.');
        }

        $this->course = $course;
        $this->quiz = $quiz;

        $completedAttempts = QuizAttempt::where('user_id', Auth::id())
            ->where('quiz_id', $quiz->id)
            ->where('status', 'completed')
            ->get();

        if ($completedAttempts->isNotEmpty()) {
            $this->isFinished = true;

            // Obtener el intento con el porcentaje más alto (nota prevaleciente)
            $bestAttempt = $completedAttempts->sortByDesc('percentage')->first();
            $latestAttempt = $completedAttempts->sortByDesc('created_at')->first();

            $this->percentage = (float) $bestAttempt->percentage;
            $this->score = (float) $bestAttempt->score;
            $this->passed = (bool) $bestAttempt->passed;

            // Cargar por defecto la revisión del último intento
            $this->loadAttemptForReview($latestAttempt);
            $this->loadQuizQuestions();
            return;
        }

        // Si el cuestionario tiene límite de tiempo, gestionar intento activo
        if ($this->quiz->time_limit) {
            $activeAttempt = QuizAttempt::where('user_id', Auth::id())
                ->where('quiz_id', $quiz->id)
                ->where('status', 'in_progress')
                ->first();

            $previousAttemptsCount = $completedAttempts->count();

            if (!$activeAttempt) {
                if ($this->quiz->max_attempts && $previousAttemptsCount >= $this->quiz->max_attempts) {
                    $this->isFinished = true;
                    $this->loadQuizQuestions();
                    return;
                }

                // Crear intento en curso
                $activeAttempt = QuizAttempt::create([
                    'quiz_id' => $this->quiz->id,
                    'user_id' => Auth::id(),
                    'attempt_number' => $previousAttemptsCount + 1,
                    'started_at' => now(),
                    'status' => 'in_progress',
                ]);
            }

            $this->activeAttempt = $activeAttempt;
            $startTime = Carbon::parse($activeAttempt->started_at);
            $expirationTime = $startTime->copy()->addMinutes((int)$this->quiz->time_limit);

            if (now()->greaterThan($expirationTime)) {
                $this->autoSubmitAttempt($activeAttempt);
                return;
            }

            $this->expiresAt = $expirationTime->toIso8601String();
        }

        $this->loadQuizQuestions();
    }

    private function loadQuizQuestions()
    {
        $this->quiz->load([
            'questions' => function ($q) {
                if ($this->quiz->random_questions && !$this->isFinished) {
                    $q->inRandomOrder();
                }
                $q->with(['options' => function ($optQuery) {
                    if ($this->quiz->random_options && !$this->isFinished) {
                        $optQuery->inRandomOrder();
                    }
                }]);
            }
        ]);
    }

    public function loadAttemptForReview(QuizAttempt $attempt)
    {
        $this->attempt = $attempt;
        $savedAnswers = $attempt->answers()->pluck('answer', 'question_id')->toArray();

        $processed = [];
        foreach ($savedAnswers as $qId => $val) {
            if (is_null($val)) continue;

            if (str_contains($val, '[') || str_contains($val, ',')) {
                $decoded = json_decode($val, true);
                $processed[$qId] = is_array($decoded)
                    ? array_map('intval', $decoded)
                    : array_map('intval', explode(',', $val));
            } else {
                $processed[$qId] = (int) $val;
            }
        }

        $this->answers = $processed;
    }

    public function toggleReview()
    {
        $this->showReview = !$this->showReview;
    }

    // Permitir revisar un intento específico del historial
    public function reviewAttempt($attemptId)
    {
        $selectedAttempt = QuizAttempt::where('user_id', Auth::id())
            ->where('quiz_id', $this->quiz->id)
            ->findOrFail($attemptId);

        $this->loadAttemptForReview($selectedAttempt);
        $this->showReview = true;
        $this->currentQuestionIndex = 0;
    }

    public function closeReview()
    {
        $this->showReview = false;
        $this->currentQuestionIndex = 0;
    }

    public function selectOption($questionId, $optionId)
    {
        if ($this->isFinished) {
            return;
        }

        $question = Question::findOrFail($questionId);
        $typeValue = $question->question_type instanceof \BackedEnum ? $question->question_type->value : $question->question_type;

        $isMultiple = in_array($typeValue, ['multiple_choice', 'multiple_select', 'checkbox']);

        if ($isMultiple) {
            if (!isset($this->answers[$questionId]) || !is_array($this->answers[$questionId])) {
                $this->answers[$questionId] = [];
            }

            if (in_array((int)$optionId, $this->answers[$questionId])) {
                $this->answers[$questionId] = array_values(
                    array_diff($this->answers[$questionId], [(int)$optionId])
                );
            } else {
                $this->answers[$questionId][] = (int)$optionId;
            }
        } else {
            $this->answers[$questionId] = (int)$optionId;
        }
    }

    public function nextQuestion()
    {
        if ($this->currentQuestionIndex < $this->quiz->questions->count() - 1) {
            $this->currentQuestionIndex++;
        }
    }

    public function previousQuestion()
    {
        if ($this->currentQuestionIndex > 0) {
            $this->currentQuestionIndex--;
        }
    }

    public function submitQuiz()
    {
        if ($this->isFinished && !$this->showReview) return;

        if (isset($this->quiz->status) && in_array($this->quiz->status, ['draft', 'inactive', 0, false], true)) {
            session()->flash('error', 'Esta evaluación ya no está disponible.');
            return;
        }

        $questions = $this->quiz->questions;
        if ($questions->isEmpty()) return;

        $completedAttemptsCount = QuizAttempt::where('user_id', Auth::id())
            ->where('quiz_id', $this->quiz->id)
            ->where('status', 'completed')
            ->count();

        if ($this->quiz->max_attempts && $completedAttemptsCount >= $this->quiz->max_attempts && !$this->activeAttempt) {
            session()->flash('error', 'Has alcanzado el límite máximo de intentos permitidos.');
            return;
        }

        DB::transaction(function () use ($completedAttemptsCount) {
            if ($this->quiz->time_limit && $this->activeAttempt) {
                $attempt = $this->activeAttempt;
            } else {
                $attempt = QuizAttempt::create([
                    'quiz_id' => $this->quiz->id,
                    'user_id' => Auth::id(),
                    'attempt_number' => $completedAttemptsCount + 1,
                    'started_at' => now(),
                    'status' => 'in_progress',
                ]);
            }

            $this->processAndCompleteAttempt($attempt);
        });
    }

    private function autoSubmitAttempt(QuizAttempt $attempt)
    {
        DB::transaction(function () use ($attempt) {
            $this->processAndCompleteAttempt($attempt);
        });

        session()->flash('error', 'El tiempo límite ha expirado. Tu evaluación se ha enviado automáticamente.');
    }

    private function processAndCompleteAttempt(QuizAttempt $attempt)
    {
        $questions = $this->quiz->questions;
        $totalPointsPossible = 0;
        $earnedPoints = 0;
        $answersToInsert = [];

        foreach ($questions as $question) {
            $questionPoints = (float) ($question->pivot->points ?? $question->points ?? 1);
            $totalPointsPossible += $questionPoints;

            $selected = $this->answers[$question->id] ?? null;
            $isCorrect = false;
            $pointsEarnedForQuestion = 0;

            $correctOptionIds = $question->options->where('is_correct', true)->pluck('id')->map(fn($id) => (int)$id)->toArray();

            if (!is_null($selected)) {
                if (is_array($selected)) {
                    $selectedInts = array_map('intval', $selected);
                    sort($selectedInts);
                    sort($correctOptionIds);
                    if ($selectedInts === $correctOptionIds) {
                        $isCorrect = true;
                    }
                } else {
                    if (in_array((int)$selected, $correctOptionIds)) {
                        $isCorrect = true;
                    }
                }

                if ($isCorrect) {
                    $pointsEarnedForQuestion = $questionPoints;
                    $earnedPoints += $questionPoints;
                }
            }

            $answersToInsert[] = [
                'question_id' => $question->id,
                'answer' => is_array($selected) ? json_encode(array_values($selected)) : ($selected ? (string) $selected : null),
                'is_correct' => $isCorrect,
                'points' => $pointsEarnedForQuestion,
            ];
        }

        $calculatedPercentage = $totalPointsPossible > 0
            ? round(($earnedPoints / $totalPointsPossible) * 100, 2)
            : 0;

        $passingScore = (float) ($this->quiz->passing_score ?? 70);
        $isPassed = $calculatedPercentage >= $passingScore;

        $attempt->update([
            'finished_at' => now(),
            'score' => $earnedPoints,
            'percentage' => $calculatedPercentage,
            'passed' => $isPassed,
            'status' => 'completed',
        ]);

        $attempt->answers()->delete();
        foreach ($answersToInsert as $ans) {
            $attempt->answers()->create($ans);
        }

        $allAttempts = QuizAttempt::where('user_id', Auth::id())
            ->where('quiz_id', $this->quiz->id)
            ->where('status', 'completed')
            ->get();

        $bestAttempt = $allAttempts->sortByDesc('percentage')->first();

        $this->percentage = (float) $bestAttempt->percentage;
        $this->score = (float) $bestAttempt->score;
        $this->passed = (bool) $bestAttempt->passed;

        $this->attempt = $attempt;
        $this->isFinished = true;
        $this->activeAttempt = null;
        $this->expiresAt = null;
    }

    public function retry()
    {
        $completedAttemptsCount = QuizAttempt::where('user_id', Auth::id())
            ->where('quiz_id', $this->quiz->id)
            ->where('status', 'completed')
            ->count();

        if ($this->quiz->max_attempts && $completedAttemptsCount >= $this->quiz->max_attempts) {
            session()->flash('error', 'No te quedan más intentos disponibles para esta evaluación.');
            return;
        }

        // Limpiar intentos previos en curso que no se terminaron
        QuizAttempt::where('user_id', Auth::id())
            ->where('quiz_id', $this->quiz->id)
            ->where('status', 'in_progress')
            ->delete();

        $this->reset(['answers', 'currentQuestionIndex', 'isFinished', 'showReview', 'expiresAt', 'activeAttempt']);

        // Si hay límite de tiempo, crear el intento en curso para el nuevo intento
        if ($this->quiz->time_limit) {
            $this->activeAttempt = QuizAttempt::create([
                'quiz_id' => $this->quiz->id,
                'user_id' => Auth::id(),
                'attempt_number' => $completedAttemptsCount + 1,
                'started_at' => now(),
                'status' => 'in_progress',
            ]);

            $expirationTime = now()->addMinutes((int)$this->quiz->time_limit);
            $this->expiresAt = $expirationTime->toIso8601String();
        }

        $this->loadQuizQuestions();
    }

    public function render()
    {
        $attempts = QuizAttempt::where('user_id', Auth::id())
            ->where('quiz_id', $this->quiz->id)
            ->where('status', 'completed')
            ->orderBy('attempt_number', 'asc')
            ->get();

        return view('livewire.student.quizz.show', [
            'attempts' => $attempts,
        ])->layout('layouts.admin');
    }
}
<?php
namespace App\Services;
use App\Models\Course;
use App\Models\Lesson;
use App\Models\LessonProgress;
use App\Models\User;
use App\Models\QuizAttempt;

class CourseProgressService
{
    /**
     * Obtiene el porcentaje de progreso total del estudiante en un curso (0 - 100).
     */
    public function getCourseProgress(User $user, Course $course): int
    {
        $totalItems = $this->getTotalCourseItemsCount($course);

        if ($totalItems === 0) {
            return 0;
        }

        $completedLessonsCount = $this->getCompletedLessonsCount($user, $course);
        $passedQuizzesCount = $this->getPassedQuizzesCount($user, $course);

        $completedItems = $completedLessonsCount + $passedQuizzesCount;

        return (int) round(($completedItems / $totalItems) * 100);
    }

    /**
     * Verifica si una lección específica está completada por el usuario.
     */
    public function isLessonCompleted(User $user, Lesson $lesson): bool
    {
        return LessonProgress::where('user_id', $user->id)
            ->where('lesson_id', $lesson->id)
            ->where('is_completed', true)
            ->exists();
    }

    /**
     * Alterna o marca como completada una lección.
     */
    public function markLessonAsCompleted(User $user, Lesson $lesson, bool $completed = true): void
    {
        LessonProgress::updateOrCreate(
            [
                'user_id' => $user->id,
                'lesson_id' => $lesson->id,
            ],
            [
                'is_completed' => $completed,
                'completed_at' => $completed ? now() : null,
            ]
        );
    }

    /**
     * Determina si un contenido (Lección o Quiz) está desbloqueado para el estudiante.
     */
    public function isItemUnlocked(User $user, Course $course, $item, int $moduleIndex): bool
    {
        // El primer módulo o lección siempre está desbloqueado
        if ($moduleIndex === 0) {
            return true;
        }

        // Si la restricción exige completar el módulo anterior:
        $previousModule = $course->modules->get($moduleIndex - 1);
        if (!$previousModule) {
            return true;
        }

        return $this->isModuleCompleted($user, $previousModule);
    }

    /**
     * Revisa si todas las lecciones y quizzes de un módulo están completados.
     */
    public function isModuleCompleted(User $user, $module): bool
    {
        foreach ($module->lessons as $lesson) {
            if (!$this->isLessonCompleted($user, $lesson)) {
                return false;
            }
        }

        foreach ($module->quizzes as $quiz) {
            $hasPassed = QuizAttempt::where('user_id', $user->id)
                ->where('quiz_id', $quiz->id)
                ->where('status', 'passed')
                ->exists();

            if (!$hasPassed) {
                return false;
            }
        }

        return true;
    }

    private function getTotalCourseItemsCount(Course $course): int
    {
        $lessonsCount = $course->modules->sum(fn($m) => $m->lessons->count());
        $quizzesCount = $course->modules->sum(fn($m) => $m->quizzes->count());

        return $lessonsCount + $quizzesCount;
    }

    private function getCompletedLessonsCount(User $user, Course $course): int
    {
        $lessonIds = $course->modules->pluck('lessons')->flatten()->pluck('id');

        return LessonProgress::where('user_id', $user->id)
            ->whereIn('lesson_id', $lessonIds)
            ->where('is_completed', true)
            ->count();
    }

    private function getPassedQuizzesCount(User $user, Course $course): int
    {
        $quizIds = $course->modules->pluck('quizzes')->flatten()->pluck('id');

        return QuizAttempt::where('user_id', $user->id)
            ->whereIn('quiz_id', $quizIds)
            ->where('status', 'passed')
            ->distinct('quiz_id')
            ->count('quiz_id');
    }
}
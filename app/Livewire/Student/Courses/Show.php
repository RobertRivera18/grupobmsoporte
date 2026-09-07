<?php

namespace App\Livewire\Student\Courses;

use Livewire\Component;
use Livewire\Attributes\Computed;
use App\Models\Course;
use App\Models\LessonProgress;
use App\Models\QuizAttempt;
use Illuminate\Support\Facades\Auth;

class Show extends Component
{
    public Course $course;

    public function mount(Course $course)
    {
        if (!Auth::user()->courses()->where('courses.id', $course->id)->exists()) {
            abort(403, 'No estás matriculado en este curso.');
        }
        $this->course = $course->load([
            'modules' => fn($q) => $q->orderBy('order')->with([
                'lessons' => fn($l) => $l->orderBy('order')->select('id', 'module_id', 'title')
            ]),
            'quizzes' => fn($q) => $q->select('id', 'course_id', 'title', 'time_limit', 'max_attempts')
        ]);
    }

    #[Computed]
    public function allLessons()
    {
        return $this->course->modules->pluck('lessons')->flatten();
    }

   
    #[Computed]
    public function completedLessonIds(): array
    {
        $lessonIds = $this->allLessons->pluck('id');

        if ($lessonIds->isEmpty()) {
            return [];
        }

        return LessonProgress::query()
            ->where('user_id', Auth::id())
            ->whereIn('lesson_id', $lessonIds)
            ->where('completed', true)
            ->pluck('lesson_id')
            ->toArray();
    }

   
    #[Computed]
    public function quizAttempts()
    {
        $quizIds = $this->course->quizzes->pluck('id');

        if ($quizIds->isEmpty()) {
            return collect();
        }

        return QuizAttempt::query()
            ->where('user_id', Auth::id())
            ->whereIn('quiz_id', $quizIds)
            ->select('id', 'quiz_id', 'user_id', 'score', 'percentage', 'passed', 'status', 'created_at')
            ->get()
            ->groupBy('quiz_id');
    }

    
    #[Computed]
    public function progressPercentage(): int
    {
        $total = $this->allLessons->count();
        if ($total === 0) return 0;

        return (int) round((count($this->completedLessonIds) / $total) * 100);
    }

    public function render()
    {
        return view('livewire.student.courses.show', [
            'completedLessonIds' => $this->completedLessonIds,
            'completedCount'    => count($this->completedLessonIds),
            'totalLessons'      => $this->allLessons->count(),
            'progressPercentage' => $this->progressPercentage,
            'quizAttempts'      => $this->quizAttempts,
        ])->layout('layouts.admin');
    }
}

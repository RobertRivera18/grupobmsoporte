<?php

namespace App\Livewire\Student\Lessons;

use Livewire\Component;
use App\Models\Course;
use App\Models\Lesson;
use App\Models\LessonProgress;
use Illuminate\Support\Facades\Auth;

class Show extends Component
{
    public Course $course;
    public Lesson $lesson;
    public int $activeLessonId;
    public bool $isCompleted = false;

    public function mount(Course $course, Lesson $lesson = null)
    {
        // Validar matrícula del estudiante
        if (!Auth::user()->courses()->where('courses.id', $course->id)->exists()) {
            abort(403, 'No estás matriculado en este curso.');
        }

        $this->course = $course->load([
            'modules' => function ($query) {
                $query->orderBy('order')->with(['lessons' => function ($q) {
                    $q->orderBy('order');
                }]);
            }
        ]);

        // Si no hay lección en la URL, cargar la primera
        if (!$lesson || !$lesson->id) {
            $firstModule = $this->course->modules->first();
            $firstLesson = $firstModule ? $firstModule->lessons->first() : null;

            if (!$firstLesson) {
                session()->flash('error', 'Este curso no tiene lecciones disponibles.');
                return redirect()->route('student.courses.show', $course->id);
            }

            $lesson = $firstLesson;
        }

        $this->lesson = $lesson;
        $this->activeLessonId = $lesson->id;
        $this->checkLessonStatus();
    }

    public function selectLesson($lessonId)
    {
        $selectedLesson = Lesson::find($lessonId);

        if ($selectedLesson) {
            $this->lesson = $selectedLesson;
            $this->activeLessonId = $selectedLesson->id;
            $this->checkLessonStatus();

            // Actualiza la URL del navegador sin recargar la página
            $this->js("window.history.pushState({}, '', '/student/courses/{$this->course->id}/lessons/{$selectedLesson->id}')");
        }
    }

    public function toggleLessonCompletion()
    {
        $userId = Auth::id();

        $progress = LessonProgress::firstOrCreate(
            [
                'user_id' => $userId,
                'lesson_id' => $this->lesson->id,
            ],
            [
                'completed' => false,
            ]
        );

        $progress->completed = !$progress->completed;
        $progress->completed_at = $progress->completed ? now() : null;
        $progress->save();

        $this->isCompleted = $progress->completed;
    }

    private function checkLessonStatus()
    {
        $this->isCompleted = LessonProgress::where('user_id', Auth::id())
            ->where('lesson_id', $this->lesson->id)
            ->where('completed', true)
            ->exists();
    }

    public function render()
    {
        $allLessons = $this->course->modules->pluck('lessons')->flatten();
        $totalLessons = $allLessons->count();
        $completedLessonIds = LessonProgress::where('user_id', Auth::id())
            ->whereIn('lesson_id', $allLessons->pluck('id'))
            ->where('completed', true)
            ->pluck('lesson_id')
            ->toArray();
        $completedCount = count($completedLessonIds);
        $progressPercentage = $totalLessons > 0 ? round(($completedCount / $totalLessons) * 100) : 0;

        $currentIndex = $allLessons->search(fn($item) => $item->id === $this->lesson->id);
        $previousLesson = $currentIndex > 0 ? $allLessons->get($currentIndex - 1) : null;
        $nextLesson = $currentIndex !== false && $currentIndex < $totalLessons - 1 
            ? $allLessons->get($currentIndex + 1) 
            : null;

        return view('livewire.student.lessons.show', [
            'previousLesson' => $previousLesson,
            'nextLesson' => $nextLesson,
            'completedLessonIds' => $completedLessonIds,
            'completedCount' => $completedCount,
            'totalLessons' => $totalLessons,
            'progressPercentage' => $progressPercentage,
        ])->layout('layouts.admin');
    }
}

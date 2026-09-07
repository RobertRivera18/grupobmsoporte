<?php

namespace App\Livewire\Admin\Capacitacion\Courses;

use App\Models\Course;
use App\Models\CourseModule;
use App\Models\Lesson;
use App\Models\Quiz;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Livewire\Attributes\Layout;
use Livewire\Component;
use Livewire\WithFileUploads;

#[Layout('layouts.admin')]
class Show extends Component
{
    use WithFileUploads;

    public Course $course;

    // Estado Módulo
    public ?int $moduleId = null;
    public string $module_name = '';

    // Estado Lección
    public ?int $lessonId = null;
    public ?int $target_module_id = null;
    public string $lesson_title = '';
    public ?string $lesson_content = null;
    public ?string $lesson_video_url = null;
    public $lesson_pdf = null;
    public ?string $existing_pdf = null;

    // Estado Evaluación / Quiz
    public ?int $quizId = null;
    public ?int $quiz_module_id = null;
    public string $quiz_title = '';
    public ?string $quiz_description = null;
    public int $quiz_passing_score = 70;
    public ?int $quiz_time_limit = null;

    public function mount(Course $course): void
    {
        $this->course = $course;
        $this->loadCourseData();
    }

    public function loadCourseData(): void
    {
        $this->course->load([
            'modules' => fn($q) => $q->orderBy('order', 'asc'),
            'modules.lessons' => fn($q) => $q->orderBy('order', 'asc'),
            'modules.quizzes.questions',
        ]);
    }

    public function saveModule(): void
    {
        $this->validate([
            'module_name' => 'required|string|max:255',
        ]);

        if ($this->moduleId) {
            $module = CourseModule::findOrFail($this->moduleId);
            $module->update(['name' => $this->module_name]);
        } else {
            $nextOrder = $this->course->modules()->max('order') + 1;
            $this->course->modules()->create([
                'name' => $this->module_name,
                'order' => $nextOrder,
            ]);
        }

        $this->resetModuleForm();
        $this->loadCourseData();
        $this->dispatch('close-modals');
    }

    public function editModule(int $id): void
    {
        $module = CourseModule::findOrFail($id);
        $this->moduleId = $module->id;
        $this->module_name = $module->name;
    }

    public function deleteModule(int $id): void
    {
        CourseModule::findOrFail($id)->delete();
        $this->loadCourseData();
    }

    public function resetModuleForm(): void
    {
        $this->reset(['moduleId', 'module_name']);
        $this->resetValidation();
    }

    // --- LECCIONES ---
    public function openLessonModal(int $moduleId): void
    {
        $this->resetLessonForm();
        $this->target_module_id = $moduleId;
    }

    public function saveLesson(): void
    {
        $this->validate([
            'lesson_title'     => 'required|string|max:255',
            'lesson_content'   => 'nullable|string',
            'lesson_video_url' => 'nullable|url|max:500',
            'lesson_pdf'       => 'nullable|file|mimes:pdf|max:10240',
        ]);

        $pdfPath = $this->existing_pdf;

        if ($this->lesson_pdf) {
            if ($this->existing_pdf && Storage::disk('public')->exists($this->existing_pdf)) {
                Storage::disk('public')->delete($this->existing_pdf);
            }
            $pdfPath = $this->lesson_pdf->store('lessons/pdfs', 'public');
        }

        if ($this->lessonId) {
            $lesson = Lesson::findOrFail($this->lessonId);
            $lesson->update([
                'title'     => $this->lesson_title,
                'slug'      => Str::slug($this->lesson_title),
                'content'   => $this->lesson_content,
                'video_url' => $this->lesson_video_url,
                'pdf_path'  => $pdfPath,
            ]);
        } else {
            $module = CourseModule::findOrFail($this->target_module_id);
            $nextOrder = $module->lessons()->max('order') + 1;

            $module->lessons()->create([
                'title'     => $this->lesson_title,
                'slug'      => Str::slug($this->lesson_title),
                'content'   => $this->lesson_content,
                'video_url' => $this->lesson_video_url,
                'pdf_path'  => $pdfPath,
                'order'     => $nextOrder,
                'status'    => true,
            ]);
        }

        $this->resetLessonForm();
        $this->loadCourseData();
        $this->dispatch('close-modals');
    }

    public function editLesson(int $id): void
    {
        $lesson = Lesson::findOrFail($id);
        $this->lessonId = $lesson->id;
        $this->lesson_title = $lesson->title;
        $this->lesson_content = $lesson->content;
        $this->lesson_video_url = $lesson->video_url;
        $this->existing_pdf = $lesson->pdf_path;

        $this->dispatch('load-lesson-content', content: $lesson->content ?? '');
    }

    public function deleteLesson(int $id): void
    {
        $lesson = Lesson::findOrFail($id);
        if ($lesson->pdf_path && Storage::disk('public')->exists($lesson->pdf_path)) {
            Storage::disk('public')->delete($lesson->pdf_path);
        }
        $lesson->delete();
        $this->loadCourseData();
    }

    public function removePdf(): void
    {
        if ($this->existing_pdf && Storage::disk('public')->exists($this->existing_pdf)) {
            Storage::disk('public')->delete($this->existing_pdf);
        }

        if ($this->lessonId) {
            Lesson::where('id', $this->lessonId)->update(['pdf_path' => null]);
        }

        $this->existing_pdf = null;
        $this->lesson_pdf = null;
    }

    public function resetLessonForm(): void
    {
        $this->reset([
            'lessonId',
            'target_module_id',
            'lesson_title',
            'lesson_content',
            'lesson_video_url',
            'lesson_pdf',
            'existing_pdf'
        ]);
        $this->resetValidation();
        $this->dispatch('load-lesson-content', content: '');
    }

    // --- EVALUACIONES / QUIZZES ---
    public function openQuizModal(int $moduleId): void
    {
        $this->resetQuizForm();
        $this->quiz_module_id = $moduleId;
    }

    public function saveQuiz(): void
    {
        $this->validate([
            'quiz_title'         => 'required|string|max:255',
            'quiz_description'   => 'nullable|string',
            'quiz_passing_score' => 'required|integer|min:0|max:100',
            'quiz_time_limit'    => 'nullable|integer|min:1',
        ]);

        if ($this->quizId) {
            $quiz = Quiz::findOrFail($this->quizId);
            $quiz->update([
                'title'         => $this->quiz_title,
                'description'   => $this->quiz_description,
                'passing_score' => $this->quiz_passing_score,
                'time_limit'    => $this->quiz_time_limit,
            ]);
        } else {
            $module = CourseModule::findOrFail($this->quiz_module_id);
            $module->quizzes()->create([
                'title'         => $this->quiz_title,
                'description'   => $this->quiz_description,
                'passing_score' => $this->quiz_passing_score,
                'time_limit'    => $this->quiz_time_limit,
            ]);
        }

        $this->resetQuizForm();
        $this->loadCourseData();
        $this->dispatch('close-modals');
    }

    public function editQuiz(int $id): void
    {
        $quiz = Quiz::findOrFail($id);
        $this->quizId = $quiz->id;
        $this->quiz_module_id = $quiz->course_module_id ?? $quiz->module_id;
        $this->quiz_title = $quiz->title ?? $quiz->name;
        $this->quiz_description = $quiz->description;
        $this->quiz_passing_score = $quiz->passing_score ?? 70;
        $this->quiz_time_limit = $quiz->time_limit;
    }

    public function deleteQuiz(int $id): void
    {
        Quiz::findOrFail($id)->delete();
        $this->loadCourseData();
    }

    public function resetQuizForm(): void
    {
        $this->reset([
            'quizId',
            'quiz_module_id',
            'quiz_title',
            'quiz_description',
            'quiz_time_limit'
        ]);
        $this->quiz_passing_score = 70;
        $this->resetValidation();
    }

    public function render()
    {
        return view('livewire.admin.capacitacion.courses.show');
    }
}

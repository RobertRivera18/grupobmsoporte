<?php

namespace App\Livewire\Admin\Enrollments;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Course;
use App\Models\User;
use App\Models\CourseEnrollment;

class Index extends Component
{
    use WithPagination;

    // Filtros y Búsqueda
    public $search = '';
    public $selectedCourseFilter = '';

    // Formulario de Nueva Matriculación
    public $user_id = '';
    public $course_id = '';
    public $status = true; // Booleano según el casting del modelo

    protected $rules = [
        'user_id' => 'required|exists:users,id',
        'course_id' => 'required|exists:courses,id',
        'status' => 'required|boolean',
    ];

    public function enroll()
    {
        $this->validate();

        // Evitar matrículas duplicadas
        $exists = CourseEnrollment::where('user_id', $this->user_id)
            ->where('course_id', $this->course_id)
            ->exists();

        if ($exists) {
            $this->addError('user_id', 'Este estudiante ya está matriculado en el curso.');
            return;
        }

        CourseEnrollment::create([
            'user_id' => $this->user_id,
            'course_id' => $this->course_id,
            'status' => (bool) $this->status,
            'progress' => 0.00,
            'enrolled_at' => now(),
        ]);

        $this->reset(['user_id', 'course_id']);
        $this->status = true;

        session()->flash('message', 'Estudiante matriculado con éxito.');
    }

    public function toggleStatus($enrollmentId)
    {
        $enrollment = CourseEnrollment::findOrFail($enrollmentId);
        $enrollment->update([
            'status' => !$enrollment->status
        ]);

        session()->flash('message', 'Estado de matrícula actualizado.');
    }

    public function unenroll($enrollmentId)
    {
        CourseEnrollment::findOrFail($enrollmentId)->delete();
        session()->flash('message', 'Matrícula eliminada correctamente.');
    }

    public function render()
    {
        $courses = Course::orderBy('name')->get();
        $users = User::orderBy('name')->get();

        $enrollments = CourseEnrollment::with(['user', 'course'])
            ->when($this->search, function ($query) {
                $query->whereHas('user', function ($q) {
                    $q->where('name', 'like', '%' . $this->search . '%')
                        ->orWhere('email', 'like', '%' . $this->search . '%');
                });
            })
            ->when($this->selectedCourseFilter, function ($query) {
                $query->where('course_id', $this->selectedCourseFilter);
            })
            ->latest()
            ->paginate(15);

        return view('livewire.admin.enrollments.index', [
            'enrollments' => $enrollments,
            'courses' => $courses,
            'users' => $users,
        ])->layout('layouts.admin');
    }
}

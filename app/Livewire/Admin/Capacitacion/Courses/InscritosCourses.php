<?php

namespace App\Livewire\Admin\Capacitacion\Courses;

use App\Models\Course;
use Livewire\Component;
use Livewire\WithPagination;

class InscritosCourses extends Component
{
    use WithPagination;

    public Course $course;
    public $search = '';

    protected $queryString = ['search' => ['except' => '']];

    public function mount(Course $course)
    {
        $this->course = $course;
    }

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function render()
    {
        $users = $this->course->students()
            ->where(function ($query) {
                $query->where('name', 'like', '%' . $this->search . '%')
                      ->orWhere('email', 'like', '%' . $this->search . '%');
            })
            ->latest('course_enrollments.created_at')
            ->paginate(10);

        foreach ($users as $user) {
            $realProgress = $this->course->calculateProgressForUser($user);
            
            if ($realProgress == 100 && $user->pivot->status != 2) {
                $this->course->students()->updateExistingPivot($user->id, [
                    'progress' => 100,
                    'status' => 2, // 2: Completado
                    'completed_at' => now(),
                ]);
                $user->pivot->progress = 100;
                $user->pivot->status = 2;
            } else if ($user->pivot->progress != $realProgress) {
                $this->course->students()->updateExistingPivot($user->id, [
                    'progress' => $realProgress
                ]);
                $user->pivot->progress = $realProgress;
            }
        }

        return view('livewire.admin.capacitacion.courses.inscritos-courses', [
            'users' => $users
        ])->layout('layouts.admin');
    }
}
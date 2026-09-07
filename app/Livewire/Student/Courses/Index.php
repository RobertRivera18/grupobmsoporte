<?php

namespace App\Livewire\Student\Courses;

use Livewire\Component;
use Livewire\WithPagination;
use Illuminate\Support\Facades\Auth;

class Index extends Component
{
    use WithPagination;

    public string $search = '';

    public function render()
    {
        $user = Auth::user();

      $courses = $user->courses()
    ->where('courses.status', true) // <-- Especificar 'courses.status'
    ->when($this->search, function ($query) {
        $query->where('courses.name', 'like', '%' . $this->search . '%')
              ->orWhere('courses.description', 'like', '%' . $this->search . '%');
    })
    ->withCount(['modules', 'quizzes'])
    ->paginate(9);

        return view('livewire.student.courses.index', [
            'courses' => $courses,
        ])->layout('layouts.admin');
    }
}

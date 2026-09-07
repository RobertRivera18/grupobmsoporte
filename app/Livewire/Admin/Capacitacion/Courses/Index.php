<?php

namespace App\Livewire\Admin\Capacitacion\Courses;

use App\Models\Course;
use Livewire\Component;
use Livewire\WithPagination;

class Index extends Component
{
    use WithPagination;

    public string $search = '';

    public int $perPage = 10;

    public bool $showDeleteModal = false;

    public ?int $courseToDelete = null;

    protected $queryString = [
        'search' => [
            'except' => '',
        ],
    ];

    public function updatingSearch(): void
    {
        $this->resetPage();
    }

    public function confirmDelete(int $courseId): void
    {
        $this->courseToDelete = $courseId;

        $this->showDeleteModal = true;
    }

    public function delete(): void
    {
        if (!$this->courseToDelete) {
            return;
        }

        $course = Course::find($this->courseToDelete);

        if ($course) {
            $course->delete();

            session()->flash(
                'success',
                'Curso eliminado correctamente.'
            );
        }

        $this->reset([
            'courseToDelete',
            'showDeleteModal',
        ]);
    }

    public function toggleStatus(int $courseId): void
    {
        $course = Course::findOrFail($courseId);

        $course->update([
            'status' => !$course->status,
        ]);
    }

    public function render()
    {
        $courses = Course::query()
            ->with('creator')
            ->withCount([
                'modules',
                'enrollments',
            ])
            ->when(
                $this->search,
                function ($query) {

                    $search = '%' . $this->search . '%';

                    $query->where(function ($q) use ($search) {

                        $q->where(
                            'name',
                            'like',
                            $search
                        )->orWhere(
                            'description',
                            'like',
                            $search
                        );

                    });

                }
            )
            ->latest()
            ->paginate($this->perPage);

        return view(
            'livewire.admin.capacitacion.courses.index',
            compact('courses')
        )->layout('layouts.admin');
    }
}
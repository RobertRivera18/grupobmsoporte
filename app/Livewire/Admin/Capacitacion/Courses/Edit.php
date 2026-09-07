<?php

namespace App\Livewire\Admin\Capacitacion\Courses;

use App\Models\Course;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Livewire\Component;
use Livewire\WithFileUploads;

class Edit extends Component
{
    use WithFileUploads;

    public Course $course;

    public string $name = '';

    public string $slug = '';

    public ?string $description = null;

    public $image = null;

    public ?string $currentImage = null;

    public bool $status = true;

    public function mount(Course $course): void
    {
        $this->course = $course;

        $this->name = $course->name;

        $this->slug = $course->slug;

        $this->description = $course->description;

        $this->currentImage = $course->image;

        $this->status = $course->status;
    }

    public function updatedName(string $value): void
    {
        $this->slug = Str::slug($value);
    }

    protected function rules(): array
    {
        return [
            'name' => [
                'required',
                'string',
                'max:255',
            ],

            'slug' => [
                'required',
                'string',
                'max:255',
                'unique:courses,slug,' . $this->course->id,
            ],

            'description' => [
                'nullable',
                'string',
            ],

            'image' => [
                'nullable',
                'image',
                'max:2048',
            ],

            'status' => [
                'boolean',
            ],
        ];
    }

    public function update()
    {
        $this->validate();

        $data = [
            'name' => $this->name,

            'slug' => $this->slug,

            'description' => $this->description,

            'status' => $this->status,
        ];

        if ($this->image) {
            if ($this->course->image && Storage::disk('public')->exists($this->course->image)) {
                Storage::disk('public')->delete($this->course->image);
            }

            $data['image'] = $this->image->store(
                'courses',
                'public'
            );
        }

        $this->course->update($data);

        session()->flash(
            'success',
            'Curso actualizado correctamente.'
        );
        return redirect()->route(
            'admin.capacitacion.courses.index'
        );
    }
    public function render()
    {
        return view('livewire.admin.capacitacion.courses.edit')->layout('layouts.admin');
    }
}

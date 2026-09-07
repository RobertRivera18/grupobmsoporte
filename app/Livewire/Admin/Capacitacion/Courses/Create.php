<?php

namespace App\Livewire\Admin\Capacitacion\Courses;

use App\Models\Course;
use Livewire\Component;
use Illuminate\Support\Str;
use Livewire\WithFileUploads;

class Create extends Component

{
    use WithFileUploads;

    public string $name = '';

    public string $slug = '';

    public ?string $description = null;

    public $image = null;

    public bool $status = true;

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
                'unique:courses,slug',
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

    public function save()
    {
        $this->validate();

        $imagePath = null;

        if ($this->image) {

            $imagePath = $this->image->store(
                'courses',
                'public'
            );
        }

        Course::create([
            'name' => $this->name,

            'slug' => $this->slug,

            'description' => $this->description,

            'image' => $imagePath,

            'status' => $this->status,

            'created_by' => auth()->id(),
        ]);

        session()->flash(
            'success',
            'Curso creado correctamente.'
        );

        return redirect()->route(
            'admin.capacitacion.courses.index'
        );
    }

    public function render()
    {
        return view('livewire.admin.capacitacion.courses.create')->layout('layouts.admin');
    }
}

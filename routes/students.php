<?php

use App\Livewire\Student\Courses\Show;
use App\Livewire\Student\Courses\Index;
use Illuminate\Support\Facades\Route;
use App\Livewire\Student\Lessons\Show as LessonShow;
use App\Livewire\Student\Quizz\Show as QuizzShow;


/*
|--------------------------------------------------------------------------
| Rutas del Panel de Estudiantes
|--------------------------------------------------------------------------
*/

Route::get('/courses', Index::class)->name('courses.index');
Route::get('/courses/{course}', Show::class)->name('courses.show');
Route::get('/courses/{course}/lessons/{lesson?}', LessonShow::class)
    ->name('lessons.show');

Route::get('/courses/{course}/quizzes/{quiz}', QuizzShow::class)->name('quizzes.show');

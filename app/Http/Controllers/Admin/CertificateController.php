<?php

namespace App\Http\Controllers\Admin;
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Course;
use App\Models\User;
use Carbon\Carbon;

class CertificateController extends Controller
{
    public function generate(Course $course, User $user)
    {
        $student = $course->students()->where('users.id', $user->id)->first();
        if (!$student) {
            abort(404, 'El colaborador no está inscrito en este curso.');
        }
        $completedAt = $student->pivot->completed_at 
            ?Carbon::parse($student->pivot->completed_at)->translatedFormat('d \d\e F \d\e Y') 
            : now()->translatedFormat('d \d\e F \d\e Y');
        $user = $student;

        return view('admin.capacitacion.courses.certificate-pdf', compact('course', 'user', 'completedAt'));
    }
}


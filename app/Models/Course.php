<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Course extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'slug',
        'description',
        'image',
        'status',
        'created_by',
    ];

    protected $casts = [
        'status' => 'boolean',
    ];

    /*
    |--------------------------------------------------------------------------
    | Relaciones
    |--------------------------------------------------------------------------
    */

    public function creator(): BelongsTo
    {
        return $this->belongsTo(
            User::class,
            'created_by'
        );
    }

    public function modules(): HasMany
    {
        return $this->hasMany(
            CourseModule::class
        )->orderBy('order');
    }

    public function quizzes(): HasMany
    {
        return $this->hasMany(
            Quiz::class
        );
    }

    public function enrollments(): HasMany
    {
        return $this->hasMany(
            CourseEnrollment::class
        );
    }

    public function students(): BelongsToMany
    {
        return $this->belongsToMany(
            User::class,
            'course_enrollments'
        )->withPivot([
            'enrolled_at',
            'completed_at',
            'progress',
            'status',
        ])->withTimestamps();
    }

    public function calculateProgressForUser(User $user): int
    {
        $lessonIds = $this->modules()->with('lessons:id')->get()->pluck('lessons')->flatten()->pluck('id');
        $totalLessons = $lessonIds->count();

        $quizIds = $this->quizzes()->pluck('id');
        $totalQuizzes = $quizIds->count();

        $totalItems = $totalLessons + $totalQuizzes;

        if ($totalItems === 0) {
            return 0;
        }

        $completedLessonsCount = 0;
        if ($totalLessons > 0) {
            $completedLessonsCount = LessonProgress::where('user_id', $user->id)
                ->whereIn('lesson_id', $lessonIds)
                ->where('completed', 1)
                ->count();
        }
        $passedQuizzesCount = 0;
        if ($totalQuizzes > 0) {
            $passedQuizzesCount = \App\Models\QuizAttempt::where('user_id', $user->id)
                ->whereIn('quiz_id', $quizIds)
                ->where('passed', 1)
                ->distinct('quiz_id')
                ->count('quiz_id');
        }

        $completedItems = $completedLessonsCount + $passedQuizzesCount;
        $progress = round(($completedItems / $totalItems) * 100);

        return min((int) $progress, 100);
    }
}

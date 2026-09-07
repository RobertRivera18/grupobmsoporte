<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Quiz extends Model
{
    use HasFactory;

    protected $fillable = [
        'course_id',
        'module_id',
        'title',
        'description',
        'time_limit',
        'passing_score',
        'max_attempts',
        'random_questions',
        'random_options',
        'show_results',
        'status',
    ];

    protected $casts = [
        'time_limit' => 'integer',
        'passing_score' => 'decimal:2',
        'max_attempts' => 'integer',
        'random_questions' => 'boolean',
        'random_options' => 'boolean',
        'show_results' => 'boolean',
        'status' => 'boolean',
    ];

    /*
    |--------------------------------------------------------------------------
    | Relaciones
    |--------------------------------------------------------------------------
    */

    public function course(): BelongsTo
    {
        return $this->belongsTo(
            Course::class
        );
    }

    public function module(): BelongsTo
    {
        return $this->belongsTo(
            CourseModule::class,
            'module_id'
        );
    }

    public function questions(): BelongsToMany
    {
        return $this->belongsToMany(
            Question::class,
            'quiz_questions'
        )->withPivot([
            'order',
            'points',
        ])->orderBy('order');
    }

    public function attempts(): HasMany
    {
        return $this->hasMany(
            QuizAttempt::class
        );
    }
}
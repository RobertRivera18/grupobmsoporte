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
}
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Str;

class Lesson extends Model
{
    use HasFactory;

    protected $fillable = [
        'module_id',
        'title',
        'slug',
        'content',
        'video_url',
        'pdf_path',
        'order',
        'status',
    ];

    protected $casts = [
        'order' => 'integer',
        'status' => 'boolean',
    ];

    protected static function booted(): void
    {
        static::creating(function (Lesson $lesson) {
            if (empty($lesson->slug) && !empty($lesson->title)) {
                $lesson->slug = Str::slug($lesson->title);
            }
        });

        static::updating(function (Lesson $lesson) {
            if ($lesson->isDirty('title')) {
                $lesson->slug = Str::slug($lesson->title);
            }
        });
    }

    public function module(): BelongsTo
    {
        return $this->belongsTo(CourseModule::class, 'module_id');
    }

    public function progress(): HasMany
    {
        return $this->hasMany(LessonProgress::class);
    }

    protected function embedVideoUrl(): Attribute
    {
        return Attribute::make(
            get: function () {
                if (!$this->video_url) {
                    return null;
                }

                $url = $this->video_url;

                // YouTube
                if (Str::contains($url, ['youtube.com/watch?v=', 'youtu.be/'])) {
                    preg_match('/(?:youtube\.com\/(?:[^\/]+\/.+\/|(?:v|e(?:mbed)?)\/|.*[?&]v=)|youtu\.be\/)([^"&?\/\s]{11})/', $url, $matches);
                    if (!empty($matches[1])) {
                        return 'https://www.youtube.com/embed/' . $matches[1];
                    }
                }

                // Vimeo
                if (Str::contains($url, 'vimeo.com/') && !Str::contains($url, 'player.vimeo.com')) {
                    $vimeoId = last(explode('/', parse_url($url, PHP_URL_PATH)));
                    return 'https://player.vimeo.com/video/' . $vimeoId;
                }

                return $url;
            }
        );
    }
}

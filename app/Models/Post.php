<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class Post extends Model
{
    /** @use HasFactory<\Database\Factories\PostFactory> */
    use HasFactory;


    protected $fillable = [
        'title',
        'slug',
        'category_id',
        'excerpt',
        'body',
        'user_id',
        'published',
        'image_path',

    ];

    protected $casts = [
        'published' => 'boolean',
        'published_at' => 'datetime',
    ];
    protected function title(): Attribute
    {
        return new Attribute(
            set: fn($value) => strtolower($value),
        );
    }

    protected function image(): Attribute
    {
        return new Attribute(
            // get: fn() => $this->image_path ?? 'https://img.freepik.com/premium-vector/default-image-icon-vector-missing-picture-page-website-design-mobile-app-no-photo-available_87543-11093.jpg'
            get: function () {
                if ($this->image_path) {
                    if (substr($this->image_path, 0, 8) === 'https://') {
                        return $this->image_path;
                    }
                    return Storage::url($this->image_path);
                } else {
                    return 'https://img.freepik.com/premium-vector/default-image-icon-vector-missing-picture-page-website-design-mobile-app-no-photo-available_87543-11093.jpg';
                }
            }
        );
    }


    public function category()
    {
        return $this->belongsTo(Category::class);
    }


    public function user()
    {
        return $this->belongsTo(User::class);
    }


    public function tags()
    {
        return $this->morphToMany(Tag::class, 'taggable');
    }

    public function  comments()
    {
        return $this->morphMany(Comment::class, 'commentable');
    }

    public function getRouteKeyName()
    {
        return 'slug';
    }


    public function scopeFilter($query, $filters)
    {
        $query->when($filters['category'] ?? null, function ($query, $category) {
            $query->whereIn('category_id', $category);
        });

        $query->when(isset($filters['order']), function ($query) use ($filters) {
            $sort = $filters['order'] === 'new' ? 'desc' : 'asc';
            $query->orderBy('published_at', $sort);
        }, function ($query) {
            // Orden por defecto si no se especifica el filtro 'order'
            $query->orderBy('published_at', 'desc');
        })->when($filters['tag'] ?? null, function ($query, $tag) {
            $query->whereHas('tags', function ($query) use ($tag) {
            $query->where('tags.name',$tag);
            });
        });
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Comment extends Model
{
    public function user()
    {
        return $this->belongsTo(User::class);
    }



    public function images(){ 
         return $this->morphMany(Image::class, 'imageable');
    }

    public function commentable()
    {
        return $this->morphTo(Comment::class,);
    }
}

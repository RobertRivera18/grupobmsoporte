<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Post;
use Illuminate\Http\Request;

class PostController extends Controller
{
    public function show(Post $post)
    {

        $postRelacionados = Post::where('published', true)
        ->where('category_id', $post->category_id)
        ->where('id', '!=', $post->id)
        ->orderBy('id', 'desc')
        ->take(10)
        ->get();
        return view('posts.show', compact('post','postRelacionados'));
    }
}

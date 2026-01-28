<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Post;
use Illuminate\Http\Request;

class HomeController extends Controller
{

    public function __invoke()
    {

        $posts = Post::where('published', true)
            ->filter(request()->all())
            ->orderBy('id', 'desc')
            ->paginate(10);

                         ;
        $categories = Category::all();
        return view('welcome', compact('posts', 'categories'));
    }
}

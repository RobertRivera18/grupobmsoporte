<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Jobs\ResizeImage;
use App\Models\Category;
use App\Models\Post;
use App\Models\Tag;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\ImageManager;
use Intervention\Image\Drivers\Gd\Driver;

class PostController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $posts = Post::where('user_id', auth()->id())
            ->latest('id')->paginate();
        return view('admin.posts.index', compact('posts'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $categories = Category::all();
        return view('admin.posts.create', compact('categories'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required',
            'slug' => 'required|unique:posts',
            'category_id' => 'required|exists:categories,id'
        ]);
        $post = Post::create($request->all());

        session()->flash('swal', [
            'icon' => 'success',
            'title' => '!Bien Hecho!',
            'text' => 'Post creado con éxito'
        ]);
        return redirect()->route('admin.posts.edit', $post);
    }


    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Post $post)
    {

        if (!Gate::allows('author', $post)) {
            abort(403, 'No tiene permisos para editar este Post');
        }
        // Gate::authorize('author', $post);
        $categories = Category::all();

        return view('admin.posts.edit', compact('categories', 'post'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Post $post)
    {
        $request->validate([
            'title' => 'required',
            'slug' => 'required|unique:posts,slug,' . $post->id,
            'category_id' => 'required|exists:categories,id',
            'excerpt' => $request->published ? 'required' : 'nullable',
            'body' => $request->published ? 'required' : 'nullable',
            'published' => 'required|boolean',
            'tags' => 'nullable|array',
            'image' => 'nullable|image',
        ]);
        $data = $request->all();
        $tags = [];
        foreach ($request->tags ?? [] as $name) {
            $tag = Tag::firstOrCreate([
                'name' => $name

            ]);
            $tags[] = $tag->id;
        }

        $post->tags()->sync($tags);
        if ($request->file('image')) {

            if ($post->image_path) {
                Storage::delete($post->image_path);
            }
            $file_name = $request->slug . '.' . $request->file('image')->getClientOriginalExtension();
            $data['image_path'] = Storage::putFileAs('posts', $request->image, $file_name);


            ResizeImage::dispatch($data['image_path']);
        }
        $post->update($data);
        session()->flash('swal', [
            'icon' => 'success',
            'title' => 'Bien Hecho!',
            'text' => 'El Post se ha Actualizado con Exito!',

        ]);
        return redirect()->route('admin.posts.edit', $post);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Post $post)
    {
        $post->delete();
        session()->flash('swal', [
            'icon' => 'success',
            'title' => '!Bien Hecho!',
            'text' => 'Post eliminado con éxito'
        ]);
        return redirect()->route('admin.posts.index');
    }
}

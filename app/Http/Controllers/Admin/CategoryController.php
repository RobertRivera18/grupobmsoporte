<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Post;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $categories = Category::latest('id')->paginate();

        return view('admin.categories.index', compact('categories'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.categories.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
        ]);
        Category::create($request->all());

        session()->flash('swal', [
            'icon' => 'success',
            'title' => 'Bien Hecho!',
            'text' => 'Categoria Creada con Exito!',

        ]);
        return redirect()->route('admin.categories.index');
    }

    /**
     * Display the specified resource.
     */

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Category $category)
    {

        return view('admin.categories.edit', compact('category'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Category $category)
    {
        $request->validate([
            'name' => 'required|string|max:255',
        ]);
        $category->update($request->all());
        session()->flash('swal', [
            'icon' => 'success',
            'title' => 'Bien Hecho!',
            'text' => 'Categoria Actualizada con Exito!',

        ]);
        return redirect()->route('admin.categories.index');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Category $category)
    {

        $posts = Post::where('category_id', $category->id)->exists();
        if ($posts) {
            session()->flash('swal', [
                'icon' => 'error',
                'title' => '!Upsss, Algo Ocurrio!',
                'text' => 'La Categoría no se puede eliminar porque tiene un Post asociado'
            ]);
            return redirect()->route('admin.categories.edit', $category);
        }

        $category->delete();
        session()->flash('swal', [
            'icon' => 'success',
            'title' => '!Bien Hecho!',
            'text' => 'Categoría eliminada con éxito'
        ]);
        return redirect()->route('admin.categories.index');
    }
}

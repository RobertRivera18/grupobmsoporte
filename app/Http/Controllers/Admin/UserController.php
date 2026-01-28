<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {

        return view('admin.users.index');
    }

    public function create()
    {
        return view('admin.users.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|string|max:255|unique:users,email,',
            'cedula' => 'required|string|max:10|unique:users,cedula,',
            'password' => 'required|string|min:8|confirmed',
        ]);
        User::create($request->all());
      

        session()->flash('swal', [
            'icon' => 'success',
            'title' => '¡Bien hecho!',
            'text' => 'Usuario Creado con éxito',
            'position' => 'top-end',
            'toast' => true,
            'timer' => 3000,
            'showConfirmButton' => false
        ]);
        return redirect()->route('admin.users.index');
    }
    /**
     * Display the specified resource.
     */
    public function show(User $user)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(User $user)
    {
        $roles = Role::all();
        return view('admin.users.edit', compact('user', 'roles'));
    }

    public function update(Request $request, User $user)
    {

        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|string|max:255|unique:users,email,' . $user->id,
            'cedula' => 'required|string|max:10|unique:users,cedula,' . $user->id,
            'password' => 'nullable|string|min:8|confirmed',

        ]);
        $user->name = $request->name;
        $user->email = $request->email;
        if ($request->filled('password')) {
            $user->password = bcrypt($request->password);
        }
        $user->cedula = $request->cedula;

        $user->roles()->sync($request->roles);
        $user->save();
        session()->flash('swal', [
            'icon' => 'success',
            'title' => '¡Bien hecho!',
            'text' => 'Usuario Actualizado con éxito',
            'position' => 'top-end',
            'toast' => true,
            'timer' => 3000,
            'showConfirmButton' => false
        ]);
        return redirect()->route('admin.users.index');
    }




    /**
     * Remove the specified resource from storage.
     */
    public function destroy(User $user)
    {
        //
    }
}

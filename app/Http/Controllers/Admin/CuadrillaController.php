<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Cuadrilla;
use App\Models\Grupo;
use App\Models\User;
use Illuminate\Http\Request;

class CuadrillaController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('admin.cuadrillas.index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $grupos = Grupo::all();
        return view('admin.cuadrillas.create', compact('grupos'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
{
    $request->validate([
        'cua_nombre' => 'required|string|max:255',
        'cua_empresa' => 'required|integer',
        'cua_ciudad' => 'required|integer',
    ]);

    $datos = $request->only('cua_nombre', 'cua_empresa', 'cua_ciudad');
    $datos['estado'] = 0;

    // Asignar grupo según la ciudad
    if ($request->cua_ciudad == 1) {
        // Guayaquil -> R2
        $datos['grupo_id'] = 2; // ID del grupo R2
    } elseif ($request->cua_ciudad == 2) {
        // Quito -> R1
        $datos['grupo_id'] = 1; // ID del grupo R1
    }

    Cuadrilla::create($datos);

    return redirect()
        ->route('admin.cuadrillas.index')
        ->with('success', 'Cuadrilla creada exitosamente');
}

    /**
     * Display the specified resource.
     */
    public function show(Cuadrilla $cuadrilla)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Cuadrilla $cuadrilla)
    {
        $users = User::whereDoesntHave('cuadrillas')
            ->orWhereHas('cuadrillas', function ($query) use ($cuadrilla) {
                $query->where('cuadrilla_id', $cuadrilla->id);
            })->get();
        return view('admin.cuadrillas.edit', compact('cuadrilla', 'users'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Cuadrilla $cuadrilla)
{
    $request->validate([
        'cua_nombre'   => 'required|string',
        'cua_ciudad'   => 'required|integer',
        'cua_empresa'  => 'required|integer',
        'recargas'     => 'nullable|boolean',
        'users'        => 'nullable|array',
    ]);

    $datos = $request->only([
        'cua_nombre',
        'cua_ciudad',
        'cua_empresa',
        'recargas'
    ]);

    // Asignar el grupo automáticamente
    if ($request->cua_ciudad == 1) {
        // Guayaquil -> R2
        $datos['grupo_id'] = 2;
    } elseif ($request->cua_ciudad == 2) {
        // Quito -> R1
        $datos['grupo_id'] = 1;
    }

    $cuadrilla->update($datos);

    // Sincronizar usuarios
    $cuadrilla->users()->sync($request->input('users', []));

    session()->flash('swal', [
        'icon' => 'success',
        'title' => '¡Bien hecho!',
        'text' => 'Cuadrilla actualizada con éxito',
        'position' => 'top-end',
        'toast' => true,
        'timer' => 3000,
        'showConfirmButton' => false
    ]);

    return redirect()->route('admin.cuadrillas.index');
}
    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Cuadrilla $cuadrilla)
    {
        $cuadrilla->delete();
        session()->flash('swal', [
            'icon' => 'success',
            'title' => '¡Bien hecho!',
            'text' => 'Cuadrilla Eliminada con éxito',
            'position' => 'top-end',
            'toast' => true,
            'timer' => 3000,
            'showConfirmButton' => false
        ]);
        return redirect()->route('admin.cuadrillas.index');
    }
}

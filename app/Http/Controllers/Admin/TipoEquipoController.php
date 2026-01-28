<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\TipoEquipo;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;


class TipoEquipoController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $tipoEquipos = TipoEquipo::all();
        return view('admin.tipoequipo.index', compact('tipoEquipos'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.tipoequipo.create');
    }

    /**
     * Store a newly created resource in storage.
     */


public function store(Request $request)
{
    $request->validate([
        'nombre' => 'required',
    ]);

    try {
        TipoEquipo::create([
            'nombre' => $request->nombre,
        ]);

        session()->flash('swal', [
            'icon' => 'success',
            'title' => '¡Bien hecho!',
            'text' => 'Tipo de equipo creado con éxito',
            'position' => 'top-end',
            'toast' => true,
            'timer' => 3000,
            'showConfirmButton' => false
        ]);
    } catch (QueryException $e) {
        if ($e->errorInfo[1] == 1062) {
            // Error por clave duplicada (nombre no único)
            session()->flash('swal', [
                'icon' => 'error',
                'title' => 'Registro duplicado',
                'text' => 'Ya existe un tipo de equipo con ese nombre. Por favor, ingrese uno diferente.',
                'position' => 'top-end',
                'toast' => true,
                'timer' => 4000,
                'showConfirmButton' => false
            ]);
        } else {
            throw $e; // Re-lanza si es otro error
        }
    }

    return redirect()->route('admin.tipoequipos.index');
}


    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(TipoEquipo $tipoequipo)
    {
        return view('admin.tipoequipo.edit',compact('tipoequipo'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, TipoEquipo $tipoequipo)
    {
         $request->validate([
            'nombre' => 'required|string|max:255',
        ]);
        $tipoequipo->update($request->all());
        session()->flash('swal', [
            'icon' => 'success',
            'title' => 'Bien Hecho!',
            'text' => 'Tipo de Equipo Actualizado con Exito!',

        ]);
        return redirect()->route('admin.tipoequipos.index');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(TipoEquipo $tipoequipo)
    {
        $tipoequipo->delete();
        session()->flash('swal', [
            'icon' => 'success',
            'title' => '¡Bien hecho!',
            'text' => 'Tipo de Equipo Eliminado con éxito',
            'position' => 'top-end',
            'toast' => true,
            'timer' => 3000,
            'showConfirmButton' => false
        ]);

        return redirect()->route('admin.tipoequipos.index');
    }
}

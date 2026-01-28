<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Cuadrilla;
use Illuminate\Http\Request;
use App\Models\Equipos;
use App\Models\TipoEquipo;
use App\Models\User;

class EquiposController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //$equipos = Equipos::latest('id')->paginate();
        return view('admin.equipos.index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $tipoEquipos = TipoEquipo::all();
        return view('admin.equipos.create', compact('tipoEquipos'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {

        $request->validate([
            'nombre' => 'required|string',
            'marca' => 'required|string',
            'modelo' => 'required|string',
            'serie' => 'required|unique:equipos,serie|string',
            'user_id' => 'nullable',
            'datos' => 'required|integer',
            'tipo_equipo_id' => 'required|integer|exists:tipo_equipos,id',
        ]);
        Equipos::create($request->all());

        session()->flash('swal', [
            'icon' => 'success',
            'title' => '¡Bien hecho!',
            'text' => 'Equipo creado con éxito',
            'position' => 'top-end',
            'toast' => true,
            'timer' => 3000,
            'showConfirmButton' => false
        ]);
        return redirect()->route('admin.equipos.index');
    }


    /**
     * Display the specified resource.
     */
    public function show(Equipos $equipo)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Equipos $equipo)
    {
        $tipoEquipos = TipoEquipo::all();
        return view('admin.equipos.edit', compact('equipo', 'tipoEquipos'));
    }


    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Equipos $equipo)
    {
        $request->validate([
            'nombre' => 'required|string',
            'marca' => 'required|string',
            'modelo' => 'required|string',
            'serie' => 'required|unique:equipos,serie,' . $equipo->id,
            'user_id' => 'nullable',
            'ciudad' => 'required|integer',
            'datos' => 'required|integer',
            'tipo_equipo_id' => 'required|integer|exists:tipo_equipos,id',
        ]);

        $equipo->update($request->all());

        session()->flash('swal', [
            'icon' => 'success',
            'title' => '¡Bien hecho!',
            'text' => 'Equipo Actualizado con éxito',
            'position' => 'top-end',
            'toast' => true,
            'timer' => 3000,
            'showConfirmButton' => false
        ]);

        return redirect()->route('admin.equipos.index');
    }


    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Equipos $equipo)
    {
        $equipo->delete();
        session()->flash('swal', [
            'icon' => 'success',
            'title' => '¡Bien hecho!',
            'text' => 'Equipo Eliminado con éxito',
            'position' => 'top-end',
            'toast' => true,
            'timer' => 3000,
            'showConfirmButton' => false
        ]);
        return redirect()->route('admin.equipos.index');
    }

    public function asignacionEquipos()
    {
        $users = User::all();
        return view('admin.equipos.asignacion', compact('users'));
    }

    public function equipostecnicos()
    {
        $cuadrillas = Cuadrilla::all();
        return view('admin.equipos.tecnicos', compact('cuadrillas'));
    }

    public function historial(Equipos $equipo)
    {
        return view('admin.equipos.historial', compact('equipo'));
    }
}

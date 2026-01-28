<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Area;
use App\Models\Indicador;
use Illuminate\Http\Request;

class AreaController extends Controller
{
    public function index()
    {
        $areas = Area::all();
        return view('admin.areas.index', compact('areas'));
    }
    public function create()
    {
        return view('admin.areas.create');
    }


    public function show(Area $area)
    {
        return view('admin.areas.show', compact('area'));
    }

    public function store(Request $request, Area $area)
    {
        $request->validate([
            'nombre' => 'required|string|max:255|unique:areas,nombre,' . $area->id,
        ]);

        Area::create($request->all());

        session()->flash('swal', [
            'icon' => 'success',
            'title' => '¡Bien hecho!',
            'text' => 'Departamento creado con éxito',
            'position' => 'top-end',
            'toast' => true,
            'timer' => 3000,
            'showConfirmButton' => false
        ]);
        return redirect()->route('admin.areas.index');
    }
    public function edit(Area $area)
    {

        return view('admin.areas.edit', compact('area'));
    }

    public function update(Request $request, Area $area)
    {
        $request->validate([
            'nombre' => 'required|string|max:255',
        ]);

        $area->update($request->all());
        session()->flash('swal', [
            'icon' => 'success',
            'title' => '¡Bien hecho!',
            'text' => 'Departamento actualizado con éxito',
            'position' => 'top-end',
            'toast' => true,
            'timer' => 3000,
            'showConfirmButton' => false
        ]);
        return redirect()->route('admin.areas.index');
    }

public function showIndicador($areaId, $indicadorId)
{
    $indicador = Indicador::with(['anios', 'frecuencia'])
        ->where('area_id', $areaId)
        ->where('id', $indicadorId)
        ->firstOrFail();

    return view('admin.indicadores.show', compact('indicador'));
}

}

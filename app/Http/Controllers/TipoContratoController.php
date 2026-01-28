<?php

namespace App\Http\Controllers;

use App\Models\TipoContrato;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;

class TipoContratoController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $tipoContratos = TipoContrato::all();
        return view('admin.tipocontrato.index', compact('tipoContratos'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.tipocontrato.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required',
        ]);

        try {
            TipoContrato::create([
                'name' => $request->name,
            ]);

            session()->flash('swal', [
                'icon' => 'success',
                'title' => '¡Bien hecho!',
                'text' => 'Tipo de contrato creado con éxito',
                'position' => 'top-end',
                'toast' => true,
                'timer' => 3000,
                'showConfirmButton' => false
            ]);
        } catch (QueryException $e) {
            if ($e->errorInfo[1] == 1062) {

                session()->flash('swal', [
                    'icon' => 'error',
                    'title' => 'Registro duplicado',
                    'text' => 'Ya existe un tipo de contrato con ese nombre. Por favor, ingrese uno diferente.',
                    'position' => 'top-end',
                    'toast' => true,
                    'timer' => 4000,
                    'showConfirmButton' => false
                ]);
            } else {
                throw $e;
            }
        }

        return redirect()->route('admin.tipocontratos.index');
    }

    public function edit(TipoContrato $tipocontrato)
    {
        return view('admin.tipocontrato.edit', compact('tipocontrato'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, TipoContrato $tipocontrato)
    {
         $request->validate([
            'name' => 'required|string|max:255',
        ]);
        $tipocontrato->update($request->all());
        session()->flash('swal', [
            'icon' => 'success',
            'title' => 'Bien Hecho!',
            'text' => 'Tipo de Contrato Actualizado con Exito!',

        ]);
        return redirect()->route('admin.tipocontratos.index');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(TipoContrato $tipocontrato)
    {
         $tipocontrato->delete();
        session()->flash('swal', [
            'icon' => 'success',
            'title' => '¡Bien hecho!',
            'text' => 'Tipo de Contrato Eliminado con éxito',
            'position' => 'top-end',
            'toast' => true,
            'timer' => 3000,
            'showConfirmButton' => false
        ]);

        return redirect()->route('admin.tipocontratos.index');
    }
}

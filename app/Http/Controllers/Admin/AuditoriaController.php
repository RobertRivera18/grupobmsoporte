<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Auditoria;
use App\Models\AuditoriaProceso;
use Illuminate\Http\Request;

class AuditoriaController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $auditorias = Auditoria::all();
        return view('admin.auditorias.index', compact('auditorias'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.auditorias.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'anio' => 'required|integer|min:2000|max:2100',
            'fecha_inicio' => 'required|date',
            'fecha_fin' => 'nullable|date|after_or_equal:fecha_inicio',
        ]);

        Auditoria::create([
            'anio' => $validated['anio'],
            'fecha_inicio' => $validated['fecha_inicio'],
            'fecha_fin' => $validated['fecha_fin'],
            'estado' => 'Planificada', // opcional si manejas estado
        ]);

        session()->flash('swal', [
            'icon' => 'success',
            'title' => '¡Bien hecho!',
            'text' => 'Auditoría creada con éxito',
            'position' => 'top-end',
            'toast' => true,
            'timer' => 3000,
            'showConfirmButton' => false,
        ]);

        return redirect()->route('admin.auditorias.index');
    }


    public function show(Auditoria $auditoria)
    {
        
        return view('admin.auditorias.show', compact('auditoria'));
    }

    public function detalle(Auditoria $auditoria, AuditoriaProceso $proceso)
    {
        abort_if($proceso->auditoria_id !== $auditoria->id, 404);
        
        return view('admin.auditorias.detalle', [
            'auditoria' => $auditoria,
            'proceso'   => $proceso,
        ]);
        
    }


    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}

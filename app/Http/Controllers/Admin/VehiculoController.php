<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Vehiculo;
use Illuminate\Http\Request;

class VehiculoController extends Controller
{
    public function index()
    {
        $vehiculos = Vehiculo::with(['movimientos' => function ($query) {
            $query->latest()->first();
        }])->get();

        return view('admin.vehiculos.index', compact('vehiculos'));
    }

    public function show($id)
    {
        $vehiculo = Vehiculo::with(['movimientos' => function ($query) {
            $query->latest();
        }])->findOrFail($id);

        return view('admin.vehiculos.show', compact('vehiculo'));
    }
  public function inspeccionar(Vehiculo $vehiculo)
    {
        return view('admin.vehiculos.inspeccionar', compact('vehiculo'));
    }
}

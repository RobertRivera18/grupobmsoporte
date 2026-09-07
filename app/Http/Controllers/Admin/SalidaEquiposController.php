<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\SalidaEquipo;
use Illuminate\Http\Request;

class SalidaEquiposController extends Controller
{
    public function index()
    {
        return view('admin.salidas.index');
    }

    public function create()
    {
        return view('admin.salidas.create');
    }
    

   public function edit(SalidaEquipo $salidaequipo)
{
    return view('admin.salidas.edit', compact('salidaequipo'));
}
}

<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\RegistroMantenimiento;
use Illuminate\Http\Request;

class MantenimientosVehiculosController extends Controller
{
    public function index()
    {
        return view('admin.mantenimientos.index');
    }
}

<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class IncidenteController extends Controller
{
    public function index()
    {
        return view('incidentes.index');
    }
}

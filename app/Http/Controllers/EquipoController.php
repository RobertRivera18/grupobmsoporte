<?php

namespace App\Http\Controllers;

use App\Models\Equipos;
use App\Models\User;
use Illuminate\Http\Request;

class EquipoController extends Controller
{
    public function show(User $user)
    {
        return view('equipos.show', compact('user'));
    }
}

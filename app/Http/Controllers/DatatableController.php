<?php

namespace App\Http\Controllers;

use App\Models\User;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Http\Request;

class DatatableController extends Controller
{
    public function user()
    {
        $users = User::select('id', 'name', 'email')->get();
       
        return DataTables::of($users)->toJson();
        
        
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Grupo extends Model
{
    protected $table = 'grupos';
    protected $fillable = [
        'nombre'
    ];

    public function cuadrillas()
    {
        return $this->hasMany(Cuadrilla::class, 'grupo_id');
    }
    public function inventarios()
    {
        return $this->hasMany(InventarioControl::class, 'grupo_id');
    }
}

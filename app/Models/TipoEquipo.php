<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TipoEquipo extends Model
{
    protected $fillable = ['nombre'];

    public function equipos()
    {
        return $this->hasMany(Equipos::class);
    }
}

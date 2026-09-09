<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TipoActividad extends Model
{
    protected $table = 'tipo_actividads';
    protected $fillable = [
        'nombre'
    ];

    public function inventarioControl()
    {
        return $this->hasMany(InventarioControl::class, 'tipo_actividad_id');
    }
}

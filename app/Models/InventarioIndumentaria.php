<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class InventarioIndumentaria extends Model
{
    protected $table = 'inventario_indumentaria';

    protected $fillable = [
        'indumentaria_id',
        'ubicacion_id',
        'stock'
    ];

    public function indumentaria()
    {
        return $this->belongsTo(Indumentaria::class);
    }

    public function ubicacion()
    {
        return $this->belongsTo(Ubicacion::class);
    }
}

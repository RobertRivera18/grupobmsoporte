<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Ubicacion extends Model
{
    protected $table = 'ubicaciones';
    protected $fillable = ['nombre'];


    public function inventarios()
    {
        return $this->hasMany(InventarioIndumentaria::class);
    }

     public function entregasIndumentaria()
    {
        return $this->hasMany(EntregaIndumentaria::class);
    }
    
    public function devoluciones()
    {
        return $this->hasMany(
            DevolucionIndumentaria::class,
            'ubicacion_id'
        );
    }
}

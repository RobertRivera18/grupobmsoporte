<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MovimientoVehiculo extends Model
{
    
    protected $table = 'movimiento_vehiculo'; 
    protected $fillable = [
        'vehiculo_id',
        'tipo',
        'fecha',
        'kilometraje',
        'observaciones'
    ];

 
    public function vehiculo()
    {
        return $this->belongsTo(Vehiculo::class, 'vehiculo_id');
    }
}

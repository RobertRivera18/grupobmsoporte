<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Vehiculo extends Model
{
    protected $table = 'vehiculos';

    protected $fillable = [
        'placa',
        'marca',
        'modelo',
        'color'
    ];
    public function incidentes()
    {
        return $this->hasMany(Incidente::class, 'vehiculo_id');
    }

    public function movimientos()
    {
        return $this->hasMany(MovimientoVehiculo::class, 'vehiculo_id');
    }
    public function inspecciones()
    {
        return $this->hasMany(VehiculoInspeccion::class);
    }
}
